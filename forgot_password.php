<?php
require_once "config/bootstrap.php";
include "header.php";

headerPutanja();

$poruka = "";
$greska = "";

// ✅ inicijalizuj da ne bude notice ako se forma nije slala
$devResetLink = "";
$emailTekst = "";

// Podesi ovo:
$RESET_TOKEN_MINUTES = 60;

// ✅ SITE_URL već imaš u config.php kao define("SITE_URL", "...");
// Ova varijabla ti više ne treba, ali ostavljam komentar da znaš razliku.
// $SITE_URL = "https://tvoj-domen.com"; // <-- PROMIJENI (PREPORUKA: koristi SITE_URL iz config.php)

function sendResetEmail($toEmail, $subject, $bodyText) {
    // TODO: ovdje ubaci PHPMailer/SMTP slanje

    // Za test možeš logovati sadržaj u fajl:
    // file_put_contents(__DIR__."/reset_links.log", $toEmail." | ".$subject." | ".$bodyText.PHP_EOL, FILE_APPEND);

    return true;
}

if (isset($_POST['posaljiResetLink'])) {

    $email = trim($_POST['email'] ?? '');
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Uvijek ista poruka (protiv enumeracije)
    $genericMsg = "Ako email postoji u bazi, poslat je link za reset šifre.";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Može i ovdje generic msg da bude 100% uniformno
        $poruka = $genericMsg;
    } else {

        // 1) Nađi korisnika po emailu
        $stmt = mysqli_prepare($conn, "SELECT idKorisnici FROM korisnici WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $idKorisnici);
        $found = mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if ($found) {

            // 2) Invalidate starih tokena za tog korisnika (opciono, ali preporuka)
            //$stmt = mysqli_prepare($conn, "UPDATE password_resets SET used_at = NOW() WHERE korisnikId = ? AND used_at IS NULL");
            $stmt = mysqli_prepare($conn, "UPDATE password_resets SET used_at = NOW() WHERE idPasswordResets = ?");
            mysqli_stmt_bind_param($stmt, "i", $idKorisnici);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // 3) Kreiraj token i hash
            $token = bin2hex(random_bytes(32)); // 64 hex chars
            $tokenHash = hash('sha256', $token);

            // 4) Upis u bazu sa expiry
            $stmt = mysqli_prepare($conn, "
                INSERT INTO password_resets (korisnikId, token_hash, expires_at, request_ip, user_agent)
                VALUES (?, ?, DATE_ADD(NOW(), INTERVAL ? MINUTE), ?, ?)
            ");

            $ip = $_SERVER['REMOTE_ADDR'] ?? null;
            $ua = $_SERVER['HTTP_USER_AGENT'] ?? null;

            mysqli_stmt_bind_param($stmt, "isiss", $idKorisnici, $tokenHash, $RESET_TOKEN_MINUTES, $ip, $ua);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // 5) Link za reset
            // ✅ koristi SITE_URL iz config.php
            $resetLink = rtrim(SITE_URL, "/") . "/reset_password.php?token=" . urlencode($token);

            // 5.1) ✅ Tekst maila (ovo ti je "email tekst" koji si pitao gdje ide)
            $emailSubject = "Reset šifre - Diskografija";
            $emailTekst =
                "Zatražili ste reset šifre na sajtu Diskografija.\n\n" .
                "Kliknite na link ispod da postavite novu šifru (link važi {$RESET_TOKEN_MINUTES} minuta):\n" .
                $resetLink . "\n\n" .
                "Ako niste vi zatražili reset, slobodno ignorišite ovu poruku.";

            // 6) Pošalji mail
            //sendResetEmail($email, $emailSubject, $emailTekst);
            //KOD IZNAD JE ZA PRODUKCIONU VERZIJU


            //KOD ISPOD JE TEST U WAMPU
            if (defined('APP_ENV') && APP_ENV === 'local') {
                // DEV: prikaži "preview emaila" + link na ekranu umjesto slanja emaila
                $poruka = "DEV režim: Link za reset je generisan. Ispod je preview email poruke:";
                $devResetLink = $resetLink;
            } else {
                // PROD: pošalji email
                sendResetEmail($email, $emailSubject, $emailTekst);
            }
            //KOD IZNAD JE TEST U WAMPU
        }

        // ✅ OVDJE JE BITNO:
        // - U PRODUKCIJI uvijek pokaži generic poruku
        // - U LOCAL (WAMP) pokaži dev poruku (ako je generisana), inače generic
        if (defined('APP_ENV') && APP_ENV === 'local') {
            if (empty($poruka)) {
                $poruka = $genericMsg; // ako nije nađen korisnik, ostaje uniformno
            }
        } else {
            $poruka = $genericMsg;
        }
    }
}
?>
<div class="slikeAlbumaPregled sredina">
    <fieldset class="border p-5 rounded">
        <legend class="w-auto px-2">Unesite vaš mail</legend>
        <p>Ukoliko postoji mail u bazi, biće vam poslan link za reset šifre.</p>

        <?php if (!empty($poruka)) { ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($poruka); ?></div>

            <?php if (!empty($devResetLink)) { ?>
                <div class="alert alert-info">
                    <!-- KOD ISPOD JE TEST (WAMP): preview email poruke -->
                    <div><strong>Email preview:</strong></div>
                    <pre style="white-space: pre-wrap; margin:0;"><?php echo htmlspecialchars($emailTekst); ?></pre>
                    <hr>
                    <div>Za reset nove šifre kliknite na link:</div>
                    <a href="<?php echo htmlspecialchars($devResetLink); ?>" target="_blank">
                        <?php echo htmlspecialchars($devResetLink); ?>
                    </a>
                    <!-- KOD IZNAD JE TEST (WAMP) -->
                </div>
            <?php } ?>

        <?php } ?>

        <?php if (!empty($greska)) { ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($greska); ?></div>
        <?php } ?>

        <form method="POST" action="">
            <div class="col-auto">
                <div class="form-group">
                    <input type="text" class="form-control form-control-sm" name="email" id="email"
                           placeholder="email@adresa.com" required="required">
                </div>
            </div><br><br>

            <div class="col-auto">
                <input type="submit" class="btn btn-primary btn-sm" name="posaljiResetLink" value="Pošalji">
            </div>
        </form>
    </fieldset>
</div>
<?php
include "footer.php";
footerPutanja();
