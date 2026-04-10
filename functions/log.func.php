<?php
//FUNKCIJE U OVOM FAJLU
//log_allowed_channels (Kanali koji se dopuštaju)
//appLog (Glavna log funkcija)
//purgeOldLogs (Brisanje starih logova
//roleNazivZaLog (hvata sesiju i status korisnika i konvertuje u tekst)
//drzavaNazivById (uzima naziv države da bi prikazalo u logu)
//entitetNazivByKod (uzima naziv entiteta da bi prikazalo u logu)

//logLoginSuccess (Uspješan login)
//logLoginFail (pogrešan login)
//logUploadSuccess (dodata slika albuma)
//logUploadFail (nije dodata slika albuma)
//logAlbumAdded (Dodat album)
//logAlbumUpdated (Izmjenjeni podaci o albumu)
//logArtistAdded (Dodat izvođač)
//logArtistUpdated (Ažuriran izvođač)
//logArtistImageUpdated (ažurirana slika izvođača)
//logArtistImageDeleted (obrisana slika izvođača) ---NIJE POZVANA NIGDJE JER NEMA OPCIJE ZA BRISANJE
//logSingleAdded (Dodat singl)
//logSingleUpdated (Ažuriran singl)
//logStreamAdded (Dodati strimovi)
//logStreamUpdated (Ažuriranje strimova)
//logMultiplePjesmeAdded (Dodati nazivi pjesama na novom albumu)
//logMultiplePjesmeUpdated (Izmjena podataka na više pjesama odjednom)
//logSongUpdated (Izmjena podataka na jednoj pjesmi sa albuma)
//logLabelAdded (Dodat Label)
//logLabelUpdated (Ažuriranje podataka o izdavaču/Labelu)
//logLabelImageUpdated (ažurirana slika izdavača/Labela)
//logLabelImageDeleted (obrisana slika izdavača/Labela)
//logSongTextAdded (Dodat tekst pjesme)
//logSongTextUpdated (Izmjenjen tekst pjesme)

//logChatBlockAction (Log za blok/odbloku chatu)
//logChatImageSent (Log da je poslata slika u chatu)
//logChatVideoSent (Log da je poslat video u chatu)
//logChatUploadBlocked (Log da fajl/slika/video nisu poslati jer su preveliki)

//getAnonId (Generiše anoniman ID za goste (cookie) kako bi se pratile pretrage bez logina)
//logSearch (Funkcija koja uzima podatke iz pretrage i šalje ih u log)
//log_truncate (Skraćuje dugačke stringove prije logovanja (da log ne bude prevelik))
//detectSearchThreat (Detektuje sumnjive search upite (SQLi, XSS, path traversal) i vraća listu pogodaka)

//logPasswordResetRequest (Zahtjev za reset šifre)
//logPasswordResetInvalidToken (Pogrešan ili istekao token)
//logPasswordResetSuccess (Šifra uspješno promjenjena)

//logMailSuccess (Loguje uspješnu registraciju)
//logMailFail (Loguje grešku prilikom registracije)

//=============================================================================================================

// 1) Podesi gdje stoje logovi (preporuka: storage/logs)
// Ako ti je ovaj fajl u includes/functions, onda je root: dirname(__DIR__, 2)
//define('LOG_BASE_DIR', dirname(__DIR__, 2) . '../logovi/');
define('LOG_BASE_DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'logovi');


// kanali koje dopuštaš
function log_allowed_channels(): array {
    return ['auth', 'activity', 'upload', 'chat', 'security', 'error', 'user_admin', 'search'];
}
//********************************* Metoda pozvana u ovom fajlu više puta *********************************//

// 2) Glavna log funkcija
function appLog(string $channel, string $level, string $message, array $context = []): void
{
    $allowed = log_allowed_channels();
    if (!in_array($channel, $allowed, true)) {
        $channel = 'error';
    }

    $date = date('Y-m-d H:i:s');
    $day  = date('Y-m-d'); // za ime fajla

    $ip   = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
    $uri  = $_SERVER['REQUEST_URI'] ?? '';
    $uid  = $_SESSION['idKorisnici'] ?? null;
    $uname = $_SESSION['username'] ?? null;

    // napravi direktorijum /storage/logs/<channel>/
    $dir = rtrim(LOG_BASE_DIR, '/\\') . DIRECTORY_SEPARATOR . $channel;
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }

    // fajl po danu: channel-YYYY-MM-DD.log
    $file = $dir . DIRECTORY_SEPARATOR . $channel . '-' . $day . '.log';

    // JSON context (čitljivo i kasnije lakše filtriranje)
    //$ctx = $context ? json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '';
    $ctx = $context ? json_encode(
    $context,
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE | JSON_PARTIAL_OUTPUT_ON_ERROR
) : '';

    $line = "[$date][$level][ip:$ip][uid:" . ($uid ?? '-') . "][user:" . ($uname ?? '-') . "][$uri] $message";
    if ($ctx !== '') $line .= " | $ctx";
    $line .= PHP_EOL;

    file_put_contents($file, $line, FILE_APPEND);
}
//********************************* Metoda pozvana u ovom fajlu više puta *********************************//

// 3) Brisanje starih logova (retention) - pozovi 1x dnevno ili kad admin otvori viewer
function purgeOldLogs(int $daysToKeep = 60, ?array $channels = null): void
{
    $channels = $channels ?: log_allowed_channels();
    $base = rtrim(LOG_BASE_DIR, '/\\');

    $cutoff = time() - ($daysToKeep * 86400);

    foreach ($channels as $ch) {
        $dir = $base . DIRECTORY_SEPARATOR . $ch;
        if (!is_dir($dir)) continue;

        $files = glob($dir . DIRECTORY_SEPARATOR . $ch . '-*.log');
        if (!$files) continue;

        foreach ($files as $f) {
            // sigurnosno: samo .log fajlove briši
            if (!is_file($f) || substr($f, -4) !== '.log') continue;

            $mtime = filemtime($f);
            if ($mtime !== false && $mtime < $cutoff) {
                @unlink($f);
            }
        }
    }
}

//********************************* Metoda koja hvata sesiju i konvertuje status korisnika u tekst  *********************************//
function roleNazivZaLog(): ?string
{
    $sesStatusK= $_SESSION["statusKorisnika"] ?? null;

    switch ($sesStatusK) {
        case 1: return 'admin';
        case 2: return 'moderator';
        case 4: return 'izvođač';
        case 5: return 'label';
    }

    return null; // ako nije prepoznato, ne upisuj ništa
}
//********************************* Metoda pozvana u ovom fajlu više puta *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

/*________________________________ LOG HELEPERI PRILIKOM IZMJENE PODATAKA O IZVOĐAČU ________________________________*/

//********************************* Uzima naziv države da bi prikazalo u logu *********************************//
function drzavaNazivById($idDrzave)
{
    global $conn;

    $idDrzave = (int)$idDrzave;
    if ($idDrzave <= 0) return null;

    $q = "SELECT nazivDrzave FROM drzave WHERE idDrzave='{$idDrzave}' LIMIT 1";
    $r = mysqli_query($conn, $q);
    if ($r && ($row = mysqli_fetch_assoc($r))) {
        return $row['nazivDrzave'] ?? null;
    }
    return null;
}
//********************************* Metoda pozvana u middlePanel.func.php fajlu u funckiji updateBiografija *********************************//

//********************************* Uzima naziv države da bi prikazalo u logu *********************************//
function entitetNazivByKod($kodEntiteta)
{
    global $conn;

    $kodEntiteta = trim((string)$kodEntiteta);
    if ($kodEntiteta === '') return null;

    $kod = mysqli_real_escape_string($conn, $kodEntiteta);

    // pretpostavka: u tabeli entiteti ima kolona kodEntiteta (RS / FBiH) i entitetNaziv
    $q = "SELECT entitetNaziv FROM entiteti WHERE kodEntiteta='{$kod}' LIMIT 1";
    $r = mysqli_query($conn, $q);
    if ($r && ($row = mysqli_fetch_assoc($r))) {
        return $row['entitetNaziv'] ?? null;
    }
    return null;
}
//********************************* Metoda pozvana u middlePanel.func.php fajlu u funckiji updateBiografija *********************************//




//--------------------------------------------------------------------------------------------------------------------------------




/*________________________________ LOGIN ________________________________*/
//********************************* Metoda koja upisuje u log da je login uspješan  *********************************//

function logLoginSuccess($userId, $status = null, $labelId = null) {
    appLog('auth', 'INFO', 'Uspešan login', [
        'userId' => (string)$userId,
        'status' => $status,
        'labelId' => $labelId
    ]);
}
//********************************* Pozvana u fajlu login.process.php *********************************//


//********************************* Metoda koja upisuje u log da je login neuspješan  *********************************//
function logLoginFail($username) {
    appLog('auth', 'WARN', 'Neuspešan login', ['username'=>$username]);
}
//********************************* Pozvana u fajlu login.process.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------







/*________________________________ UPLOAD SLIKA ALBUMA ________________________________*/
//********************************* Metoda koja upisuje u log da je dodata slika albuma  *********************************//
function logUploadSuccess($type, $albumId, $file, $size = null) {
    appLog('upload', 'INFO', 'Upload success', [
        'type'=>$type,      // front|back|inside|profile|single
        'albumId'=>$albumId,
        'file'=>$file,
        'size'=>$size
    ]);
}
//********************************* Pozvana u fajlu insertAlbum.class.php *********************************//


//********************************* Metoda koja upisuje u log da nije dodata slika albuma  *********************************//
function logUploadFail($type, $albumId, $file, $reason) {
    appLog('upload', 'ERROR', 'Upload fail', [
        'type'    => $type,
        'albumId' => $albumId,
        'file'    => $file,
        'reason'  => $reason
    ]);
}

//********************************* Pozvana u fajlu insertAlbum.class.php *********************************//


//--------------------------------------------------------------------------------------------------------------------------------





/*________________________________ ALBUMI ________________________________*/

//********************************* Metoda koja upisuje u log da je album dodat  *********************************//
function logAlbumAdded($albumId, $nazivAlbuma) {
    appLog('activity', 'INFO', 'Dodat album', [
        'albumId'=>$albumId,
        'nazivAlbuma'=>$nazivAlbuma
        ]);
}
//********************************* Pozvana u fajlu insertAlbum.class.php *********************************//

/********************************* Ažuriranje podataka o albumu *********************************/
/*function logAlbumUpdated($idAlbum, $nazivAlbuma){
    appLog('activity', 'INFO', 'Izmijenjeni podaci o albumu', [
        'role'        => roleNazivZaLog(), // može biti null
        'action'      => 'update',
        'idAlbum'    => $idAlbum,
        'nazivAlbuma' => $nazivAlbuma
    ]);
}*/


function logAlbumUpdated($idAlbum, $nazivAlbuma, array $changes = [])
{
    $ctx = [
        'role'       => roleNazivZaLog(), // može biti null
        'action'     => 'update',
        'idAlbum'    => $idAlbum,
        'nazivAlbuma'=> $nazivAlbuma,
    ];

    // upiši promjene samo ako ih ima
    if (!empty($changes)) {
        $ctx['changes'] = $changes;
    }

    appLog('activity', 'INFO', 'Izmijenjeni podaci o albumu', $ctx);
}
//********************************* Metoda pozvana u adminFunkcije.func.php  *********************************//



//--------------------------------------------------------------------------------------------------------------------------------




/*________________________________ IZVOĐAČI ________________________________*/
//********************************* Metoda koja upisuje u log da je izvođač dodat  *********************************//
function logArtistAdded($idIzvodjac, $imeIzvodjaca) {
    appLog('activity', 'INFO', 'Dodat izvođač', [
        'idIzvodjac' => $idIzvodjac,
        'izvodjacMaster' => $imeIzvodjaca
    ]);
}
//********************************* Pozvana u fajlu insertArtitst.class.php *********************************//

//********************************* Ažuriranje podataka o izvođaču  *********************************//
function logArtistUpdated($idIzvodjaci, $izvodjacMaster, array $changes = [])
{
    $ctx = [
        'role'          => roleNazivZaLog(),
        'action'        => 'update',
        'idIzvodjaci'   => $idIzvodjaci,
        'izvodjacMaster'=> $izvodjacMaster
    ];

    if (!empty($changes)) {
        $ctx['changes'] = $changes;
    }

    appLog('activity', 'INFO', 'Izmijenjeni podaci o izvođaču', $ctx);
}
//********************************* Pozvana u fajlu middlePanel.func.php *********************************//

//********************************* Ažuriranje slike o izvođaču  *********************************//
function logArtistImageUpdated($idIzvodjaci, $izvodjacMaster)
{
    appLog('upload', 'INFO', 'Izmijenjena slika izvođača', [
        'role'          => roleNazivZaLog(),
        'action'        => 'update',
        'idIzvodjaci'   => $idIzvodjaci,
        'izvodjacMaster'=> $izvodjacMaster
    ]);
}
//********************************* Pozvana u fajlu adminPromjenaSlikeIzvodjaca.func.php *********************************//

//********************************* Log o brisanju slike o izvođaču  *********************************//
function logArtistImageDeleted($idIzvodjaci, $izvodjacMaster)
{
    appLog('upload', 'INFO', 'Obrisana slika izvođača', [
        'role'          => roleNazivZaLog(),
        'action'        => 'delete',
        'idIzvodjaci'   => $idIzvodjaci,
        'izvodjacMaster'=> $izvodjacMaster
    ]);
}
//********************************* TRENUTNO NIJE POZVANA NIGDJE JER NEMA OPCIJE ZA BRISANJE  *********************************//

//--------------------------------------------------------------------------------------------------------------------------------



/*________________________________ SINGLOVI ________________________________*/

/********************************* Dodavanje jednog singla *********************************/
function logSingleAdded($idSinglovi, $singlNaziv){
        appLog('activity', 'INFO', 'Dodat singl', [
        'idSinglovi' => $idSinglovi,
        'singlNaziv' => $singlNaziv
    ]);
}
//********************************* Pozvana u fajlu insertSingleSong.class.php *********************************//

/********************************* Ažuriranje jednog singla *********************************/
function logSingleUpdated($idSingle, $singlNaziv, array $changes = [])
{
    $ctx = [
        'role'      => roleNazivZaLog(),
        'action'    => 'update',
        'idSingle'  => $idSingle,
        'singlNaziv'=> $singlNaziv
    ];

    if (!empty($changes)) {
        $ctx['changes'] = $changes;
    }

    appLog('activity', 'INFO', 'Izmijenjeni podaci o singlu', $ctx);
}
//********************************* Pozvana u fajlu adminFunkcije.func.php *********************************//



//--------------------------------------------------------------------------------------------------------------------------------


/*________________________________ STRIMOVI ________________________________*/
/********************************* Dodavanej strimova albuma *********************************/
function logStreamAdded($idAlbum, $youtubeVideoLink="", $spotifyLink="", $deezerLink="", $appleMusicLink="", $tidalLink="", $youtubeMusicLink="", $amazonMusicLink="", $soundCloudLink="", $amazonShopLink="", $bandCampLink="", $qobuzLink=""){
        appLog('activity', 'INFO', 'Dodati strimovi', [
        'idAlbum' => $idAlbum,
        'youtubeVideoLink' => $youtubeVideoLink,
        'spotifyLink' => $spotifyLink,
        'deezer' => $deezerLink,
        'appleMusic' => $appleMusicLink,
        'tidal' => $tidalLink,
        'youtubeMusic' => $youtubeMusicLink,
        'amazonMusic' => $amazonMusicLink,
        'soundCloud' => $soundCloudLink,
        'amazonShop' => $amazonShopLink,
        'bandCamp' => $bandCampLink,
        'qobuz' => $qobuzLink
    ]);
}
//********************************* Pozvana u fajlu insertStreams.class.php *********************************//

/********************************* Ažuriranje strimova albuma *********************************/
function logStreamUpdated($idAlbum, $nazivAlbuma, array $changes = [])
{
    $ctx = [
        'role'       => roleNazivZaLog(),
        'action'     => 'update',
        'idAlbum'    => $idAlbum,
        'nazivAlbuma'=> $nazivAlbuma
    ];

    if (!empty($changes)) {
        $ctx['changes'] = $changes;
    }

    appLog('activity', 'INFO', 'Izmijenjeni strimovi albuma', $ctx);
}
//********************************* Pozvana u fajlu adminStreams.class.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------



/*________________________________ PJESME ________________________________*/

/********************************* Dodavanje naziva pjesama na novom dodatom albumu *********************************/
function logMultiplePjesmeAdded($albumId, $izvodjacId,  $nazivAlbuma, array $pjesmeLog): void
{
    // Kompaktan prikaz: [redniBroj => idPjesme]
    $map = [];
    $ids = [];

    foreach ($pjesmeLog as $p)
    {
        $map[$p['redniBroj']] = $p['idPjesme'];
        $ids[] = $p['idPjesme'];
    }

    appLog('activity', 'INFO', 'Dodate pjesme u album', [
        'albumId'    => $albumId,
        'nazivAlbuma' => $nazivAlbuma,
        'izvodjacId' => $izvodjacId,
        'count'      => count($pjesmeLog),
        'idPjesme'   => $ids,
        'map'        => $map,
        'pjesme'     => $pjesmeLog
        // ako želiš i nazive, možemo dodati i 'nazivi' niz (vidi niže)
    ]);
}

//********************************* Pozvana u fajlu insertAlbumSongs.class.php *********************************//

/********************************* Ažuriranje više pjesama na albumu odjednom kada se izabere album *********************************/
function logMultiplePjesmeUpdated($idAlbum, array $songs)
{
    appLog('activity', 'INFO', 'Izmijenjene pjesme na albumu', [
        'role'    => roleNazivZaLog(), // može biti null
        'action'  => 'update',
        'idAlbum' => $idAlbum,
        'count'   => count($songs),
        'songs'   => $songs
    ]);
}

//********************************* Metoda pozvana u adminFunkcije.func.php  *********************************//

/********************************* Ažuriranje jedne pjesame na albumu kada se izabere album *********************************/
function logSongUpdated($idPjesme, $nazivPjesme, array $changes = [])
{
    $ctx = [
        'role'        => roleNazivZaLog(),
        'action'      => 'update',
        'idPjesme'    => $idPjesme,
        'nazivPjesme' => $nazivPjesme,
    ];

    if (!empty($changes)) {
        $ctx['changes'] = $changes;
    }

    appLog('activity', 'INFO', 'Izmijenjeni podaci o pjesmi', $ctx);
}
//********************************* Metoda pozvana u adminFunkcije.func.php  *********************************//

//--------------------------------------------------------------------------------------------------------------------------------




/*________________________________ LABELI ________________________________*/
/********************************* Dodavanje novog teksta pjesme *********************************/
function logLabelAdded($idIzdavaci, $izdavaciNaziv){
        appLog('activity', 'INFO', 'Dodat izdavač/Label', [
        'idIzdavaci' => $idIzdavaci,
        'izdavaciNaziv' => $izdavaciNaziv
    ]);
}
//********************************* Pozvana u fajlu insertLabel.class.php *********************************//

//********************************* Ažuriranje podataka o Labelu  *********************************//
function logLabelUpdated($idLabel, $izdavaciNaziv, array $changes = [])
{
    $ctx = [
        'role'          => roleNazivZaLog(),
        'action'        => 'update',
        'idLabel'   => $idLabel,
        'izdavaciNaziv'=> $izdavaciNaziv
    ];

    if (!empty($changes)) {
        $ctx['changes'] = $changes;
    }

    appLog('activity', 'INFO', 'Izmijenjeni podaci o Labelu', $ctx);
}
//********************************* Pozvana u fajlu adminEditPanel.class.php *********************************//

//********************************* Ažuriranje slike o izdavaču/Labelu  *********************************//
function logLabelImageUpdated($idLabel, $izdavaciNaziv)
{
    appLog('upload', 'INFO', 'Izmijenjena slika zdavača/Labela', [
        'role'          => roleNazivZaLog(),
        'action'        => 'update',
        'idLabel'   => $idLabel,
        'izdavaciNaziv'=> $izdavaciNaziv
    ]);
}
//********************************* Pozvana u fajlu adminEditPanel.class.php *********************************//

//********************************* Log o brisanju slike o izdavaču/Labelu  *********************************//
function logLabelImageDeleted($idLabel, $izdavaciNaziv)
{
    appLog('upload', 'INFO', 'Obrisana slika izdavača/Labela', [
        'role'          => roleNazivZaLog(),
        'action'        => 'delete',
        'idLabel'   => $idLabel,
        'izdavaciNaziv'=> $izdavaciNaziv
    ]);
}
//********************************* Pozvana u fajlu adminEditPanel.class.php  *********************************//



//--------------------------------------------------------------------------------------------------------------------------------

/*________________________________ TETKSTOVI PJESAMA ________________________________*/
/********************************* Dodavanje novog teksta pjesme *********************************/
function logSongTextAdded($idPjesme, $nazivPjesme){
        appLog('activity', 'INFO', 'Dodat tekst pjesme', [
        'idPjesme' => $idPjesme,
        'nazivPjesme' => $nazivPjesme
    ]);
}
//********************************* Metoda pozvana u insertAlbumSongs.class.php  *********************************//

/********************************* Ažuriranje teksta pjesme na "Tekstovi pjesama" *********************************/
function logSongTextUpdated($idPjesme, $nazivPjesme){
    appLog('activity', 'INFO', 'Izmijenjen tekst pjesme', [
        'role'        => roleNazivZaLog(), // može biti null
        'action'      => 'update',
        'idPjesme'    => $idPjesme,
        'nazivPjesme' => $nazivPjesme
    ]);
}
//********************************* Metoda pozvana u adminEditPanel.class.php  *********************************//


//--------------------------------------------------------------------------------------------------------------------------------




/*________________________________ KORISNICI  ________________________________*/

//********************************* Log izmjene podataka korisnika *********************************//
function logUserUpdated($idKorisnik, $username, array $changes = [])
{
    $ctx = [
        'role'       => roleNazivZaLog(),
        'action'     => 'update',
        'idKorisnik' => $idKorisnik,
        'username'   => $username
    ];

    if (!empty($changes)) {
        $ctx['changes'] = $changes;
    }

    appLog('user_admin', 'INFO', 'Izmijenjeni podaci korisnika', $ctx);
}

//********************************* Log promjene šifre *********************************//
// Ne loguj šifru, hash, ništa. Samo event.
function logUserPasswordChanged($idKorisnik, $username)
{
    appLog('user_admin', 'INFO', 'Promijenjena šifra korisnika', [
        'role'       => roleNazivZaLog(),
        'action'     => 'password_change',
        'idKorisnik' => $idKorisnik,
        'username'   => $username
    ]);
}


//********************************* Ažuriranje slike korisnika  *********************************//
function logUserImageUpdated($idKorisnik, $username)
{
    appLog('upload', 'INFO', 'Izmijenjena profilna slika korisnika', [
        'role'          => roleNazivZaLog(),
        'action'        => 'update',
        'idKorisnik'    => $idKorisnik,
        'username'      => $username
    ]);
}
//********************************* Pozvana u fajlu admineditusers.func.php *********************************//

//********************************* Log o brisanju slike korisnika  *********************************//
function logUserImageDeleted($idKorisnik, $username)
{
    appLog('upload', 'INFO', 'Obrisana profilna slika korisnika', [
        'role'          => roleNazivZaLog(),
        'action'        => 'delete',
        'idKorisnik'    => $idKorisnik,
        'username'      => $username
    ]);
}
//********************************* Pozvana u fajlu adminusers.func.php  *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

/*________________________________ CHAT  ________________________________*/

//********************************* Log za blok/odblok u chatu  *********************************//
function logChatBlockAction(int $otherUserId, string $otherUsername = '', string $action = 'block'): void
{
    // $action: 'block' ili 'unblock'
    global $conn;

    // ✅ Ako username nije prosleđen ili je prazan, probaj dohvatiti iz baze
    $otherUsername = trim($otherUsername);
    if ($otherUsername === '' && !empty($conn)) {
        $qU = mysqli_query($conn, "SELECT username FROM korisnici WHERE idKorisnici='{$otherUserId}' LIMIT 1");
        if ($qU && mysqli_num_rows($qU) > 0) {
            $tmp = mysqli_fetch_assoc($qU);
            $otherUsername = $tmp['username'] ?? '';
        }
    }

    // ✅ Ako roleNazivZaLog() vrati null, neka default bude 'user'
    $role = roleNazivZaLog();
    if ($role === null || $role === '') {
        $role = 'user'; // ili 'slušalac' ako ti je draže
    }

    appLog('chat', 'INFO', 'Chat block action', [
        'role'       => $role,
        'action'     => $action,
        'other_uid'  => $otherUserId,
        'other_user' => $otherUsername
    ]);
}
//********************************* Pozvana u fajlu block-user.php  *********************************//

//********************************* Log da je poslana slika u chatu  *********************************//
function logChatImageSent($fromId, $toId, $file, $size)
{
    global $conn;

    // dohvat username-a iz baze
    $q = "SELECT idKorisnici, username 
          FROM korisnici 
          WHERE idKorisnici IN ($fromId, $toId)";
    $r = mysqli_query($conn, $q);

    $users = [];
    while($row = mysqli_fetch_assoc($r)){
        $users[$row['idKorisnici']] = $row['username'];
    }

    appLog('chat', 'INFO', 'Chat slika poslata', [
        'action'   => 'image_sent',
        'fromId'   => $fromId,
        'fromUser' => $users[$fromId] ?? null,
        'toId'     => $toId,
        'toUser'   => $users[$toId] ?? null,
        'file'     => $file,
        'size_kb'  => round($size/1024, 1)
    ]);
}
//********************************* Pozvana u fajlu insert-chat.php  *********************************//


//********************************* Log da je poslat video u chatu  *********************************//
function logChatVideoSent($fromId, $toId, $file, $size)
{
    global $conn;

    $q = "SELECT idKorisnici, username 
          FROM korisnici 
          WHERE idKorisnici IN ($fromId, $toId)";
    $r = mysqli_query($conn, $q);

    $users = [];
    while($row = mysqli_fetch_assoc($r)){
        $users[$row['idKorisnici']] = $row['username'];
    }

    appLog('chat', 'INFO', 'Chat video poslat', [
        'action'   => 'video_sent',
        'fromId'   => $fromId,
        'fromUser' => $users[$fromId] ?? null,
        'toId'     => $toId,
        'toUser'   => $users[$toId] ?? null,
        'file'     => $file,
        'size_kb'  => round($size/1024, 1)
    ]);
}
//********************************* Pozvana u fajlu insert-chat.php  *********************************//


//********************************* ✅ Chat upload blokiran (prevelik fajl / pogrešan format / itd.)  *********************************//
function logChatUploadBlocked($type, $fromId, $toId, $fileName, $sizeBytes, $reason)
{
    global $conn;

    // username za from/to
    $fromUser = null;
    $toUser   = null;

    $q = "SELECT idKorisnici, username
          FROM korisnici
          WHERE idKorisnici IN ('".(int)$fromId."', '".(int)$toId."')";
    $r = mysqli_query($conn, $q);
    while($row = mysqli_fetch_assoc($r)){
        if((int)$row['idKorisnici'] === (int)$fromId) $fromUser = $row['username'];
        if((int)$row['idKorisnici'] === (int)$toId)   $toUser   = $row['username'];
    }

    appLog('chat', 'WARN', 'Chat upload blokiran', [
        'action'   => 'upload_blocked',
        'type'     => $type, // image/video
        'fromId'   => (string)$fromId,
        'fromUser' => $fromUser,
        'toId'     => (string)$toId,
        'toUser'   => $toUser,
        'file'     => (string)$fileName,
        'size_kb'  => round(((int)$sizeBytes) / 1024, 1),
        'reason'   => $reason
    ]);
}
//********************************* Pozvana u fajlu insert-chat.php  *********************************//

//--------------------------------------------------------------------------------------------------------------------------------


/*________________________________ PRETRAGA (SEARCH)  ________________________________*/
//********************************* Generiše anoniman ID za goste (cookie) kako bi se pratile pretrage bez logina *********************************//
function getAnonId(): string
{
    if (!empty($_COOKIE['anon_id'])) return (string)$_COOKIE['anon_id'];

    $id = bin2hex(random_bytes(8)); // 16 chars

    // Ako su headeri već poslani, NE pokušavaj setcookie (da ne baca warning)
    if (!headers_sent()) {
        setcookie('anon_id', $id, time() + 180*86400, '/', '', false, true);
        $_COOKIE['anon_id'] = $id;
    }

    return $id;
}
//********************************* Pozvana u u ovom fajlu u funkciji logSearch()  *********************************//


//********************************* Funkcija koja uzima podatke iz pretrage i šalje ih u log *********************************//
function logSearch(string $qOriginal, string $qClean, string $scope, int $resultsCount = 0, ?int $durationMs = null, array $extra = []): void
{
    $uid    = $_SESSION['idKorisnici'] ?? null;
    $uname  = $_SESSION['username'] ?? null;
    $status = $_SESSION['statusKorisnika'] ?? null;

    $ip  = $_SERVER['REMOTE_ADDR'] ?? null;
    $ua  = $_SERVER['HTTP_USER_AGENT'] ?? null;

    // anon_id (ako si ga već dodao ranije)
    $anon = function_exists('getAnonId') ? getAnonId() : null;

    $ctx = array_merge([
        'q'        => log_truncate($qOriginal, 300),
        'q_clean'  => log_truncate($qClean, 300),
        'scope'    => $scope,
        'results'  => $resultsCount,
        'ms'       => $durationMs,

        // user / guest info
        'uid'      => $uid,
        'username' => $uname,
        'status'   => $status,
        'ip'       => $ip,
        'ua'       => $ua,
        'anon_id'  => $anon,
    ], $extra);

    // 1) prazna pretraga
    if (trim($qClean) === '') {
        appLog('search', 'WARN', 'Prazna pretraga', $ctx);
        return;
    }

    // 2) security bonus: detekcija sumnjivih obrazaca
    $hits = detectSearchThreat($qOriginal);
    if (!empty($hits)) {
        // loguj u security kao WARN, uz listu hitova
        $secCtx = $ctx;
        $secCtx['threat_hits'] = $hits;
        appLog('security', 'WARN', 'Sumnjiv search upit', $secCtx);
    }

    // 3) normalan search log
    appLog('search', 'INFO', 'Pretraga', $ctx);
}
//********************************* Pozvana u fajlu search.php  *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Skraćuje dugačke stringove prije logovanja (da log ne bude prevelik) *********************************//
function log_truncate($s, int $max = 300): string
{
    $s = (string)$s;
    if (mb_strlen($s, 'UTF-8') <= $max) return $s;
    return mb_substr($s, 0, $max, 'UTF-8') . '...';
}
//********************************* Pozvana u u ovom fajlu u funkciji logSearch()  *********************************//


//********************************* Detektuje sumnjive search upite (SQLi, XSS, path traversal) i vraća listu pogodaka *********************************//
function detectSearchThreat(string $q): array
{
    // ukloni null byte (ako ga ima)
    $q = str_replace("\0", '', $q);

    // ako je nakon čišćenja prazno, nema šta da se provjerava
    if ($q === '') return [];

    $qLower = mb_strtolower($q, 'UTF-8');
    $hits = [];

    $patterns = [
        // SQLi / DB probing (heuristike)
        'sqli_tautology' => '/\b(or|and)\b\s+1\s*=\s*1\b/i',
        'sqli_union'     => '/\bunion\b\s+\bselect\b/i',
        'sqli_sleep'     => '/\bsleep\s*\(\s*\d+\s*\)/i',
        'sqli_benchmark' => '/\bbenchmark\s*\(/i',
        'sqli_comment'   => '/(--|#|\/\*)/i',
        'sqli_info'      => '/\b(information_schema|@@version|version\(\))\b/i',

        // XSS / HTML injection
        'xss_script'     => '/<\s*script\b/i',
        'xss_event'      => '/on\w+\s*=/i',
        'xss_js_proto'   => '/\bjavascript\s*:/i',

        // Path traversal / file probing
        'path_traversal' => '/\.\.\//',
        'windows_path'   => '/\.\.\\\\/',
    ];

    foreach ($patterns as $name => $re) {
        if (preg_match($re, $q)) $hits[] = $name;
    }

    // Dodatne heuristike (bez regexa)
    if (substr_count($qLower, "'") >= 3) $hits[] = 'many_quotes';
    if (substr_count($qLower, '"') >= 3) $hits[] = 'many_dquotes';
    if (strlen($q) > 250) $hits[] = 'very_long';

    return array_values(array_unique($hits));
}

//********************************* Pozvana u u ovom fajlu u funkciji logSearch()  *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

// =====================================================================================
// PASSWORD RESET LOGOVI
// - KORISTI appLog()
// - NE LOGUJ RAW TOKEN
// =====================================================================================

//********************************* Zahtjev za reset šifre *********************************//
function logPasswordResetRequest($email, $userId = null, $resetId = null) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? null;

    appLog('security', 'INFO', 'Password reset requested', [
        'email'  => $email,
        'userId' => $userId,
        'resetId'=> $resetId,
        'ip'     => $ip,
        'ua'     => $ua
    ]);
}
//********************************* Pozvana u fajlu forgot_password.php *********************************//


//********************************* Pogrešan ili istekao token *********************************//
function logPasswordResetInvalidToken($tokenHash = null) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? null;

    appLog('security', 'WARNING', 'Invalid or expired reset token', [
        'tokenHash' => $tokenHash,
        'ip'        => $ip,
        'ua'        => $ua
    ]);
}
//********************************* Pozvana u fajlu reset_password.php *********************************//



//********************************* Šifra uspješno promjenjena *********************************//
function logPasswordResetSuccess($userId, $resetId = null) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? null;

    appLog('security', 'INFO', 'Password successfully reset', [
        'userId' => $userId,
        'resetId'=> $resetId,
        'ip'     => $ip,
        'ua'     => $ua
    ]);
}
//********************************* Pozvana u fajlu reset_password.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------


/*________________________________ REGISTRACIJA ________________________________*/

//********************************* Loguje uspješnu registraciju *********************************//
function logMailSuccess($type, $email, $username, $uid = null)
{
    appLog('security', 'INFO', 'Mail success', [
        'type'     => $type,      // welcome, reset, notify...
        'email'    => $email,
        'username' => $username,
        'uid'      => $uid,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
    ]);
}
//********************************* Pozvana u fajlu welcome.func.php *********************************//


//********************************* Loguje neuspješnu registraciju *********************************//
function logMailFail($type, $email, $username, $error)
{
    appLog('security', 'ERROR', 'Mail fail', [
        'type'     => $type,
        'email'    => $email,
        'username' => $username,
        'error'    => $error,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        'error'    => $error
    ]);
}
//********************************* Pozvana u fajlu welcome.func.php *********************************//


//--------------------------------------------------------------------------------------------------------------------------------