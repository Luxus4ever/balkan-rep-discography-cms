<?php

//METODE U OVOM FAJLU
//adminEditUser (izmjena profila korisnika)
//adminIzmenaSlikeKorisnika (izmena slike korisnika)
//formaEditUser (forma za izmjenu)
//getStatusNaziv (prati izmjenu statusa tipa korisnika u logovima)
//getVerifikacijaNaziv (prati izmjenu statusa verifikacije korisnika u logovima)
//getArtistMasterById (prikazuje kom korisniku je dodijeljen koji izvođač po imenu u logovima)
//getLabelNazivById (prikazuje kom korisniku je dodijeljen koji Label po imenu u logovima)


//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Izmjena podataka o profilu *********************************//
function adminEditUser($idProfil, $sesStatusK)
{
    global $conn;
    $q2= "SELECT * FROM korisnici 
    JOIN drzave2 ON drzave2.kodZemljeDugi=korisnici.drzava
    WHERE idKorisnici='{$idProfil}'";
    $pregledajPodatke= mysqli_query($conn, $q2);

    while($row= mysqli_fetch_assoc($pregledajPodatke))
    {
        $idKorisnici= $row["idKorisnici"];
        $ime= $row["ime"];
        $prezime= $row["prezime"];
        $email= $row["email"];
        $username= $row["username"];
        $datumRegistracije= date("d.m.Y. H:i", strtotime($row["datumRegistracije"]));
        $pol= $row["pol"];
        $tipKorisnika= $row["tipKorisnika"];
        $statusKorisnika= $row["statusKorisnika"];
        $verifikacijaKorisnika= $row["verifikacijaKorisnika"];
        $drzava= $row["drzava"];
        $grad= $row["grad"];
        $profilnaSlika= $row["profilnaSlika"];
        $facebookPr= $row["facebookPr"];
        $instagramPr= $row["instagramPr"];
        $twitterPr= $row["twitterPr"];
        $tiktokPr= $row["tiktokPr"];
        $sajt= $row["websajt"];
        $sifra= $row["sifra"];
        $sifra2= $row["sifra2"];

        if($sesStatusK==1){
            ?>
            <div class="row">
                <div class="col-md-8 bg-dark mx-auto">
                    <main >
                        <?php
                            adminIzmenaSlikeKorisnika($idProfil, $profilnaSlika, $username, $datumRegistracije);
    
                            formaEditUser($username, $ime, $prezime, $pol, $tipKorisnika, $statusKorisnika, $verifikacijaKorisnika, $email, $drzava, $grad, $facebookPr, $instagramPr, $twitterPr, $tiktokPr, $sajt, $idProfil);
    
                        ?>
                    </main>  
                </div> <!-- kraj slikeAlbumaPregled -->
            </div> <!-- kraj #row -->
            <?php
        }else if($sesStatusK=2 AND $statusKorisnika==1){
            echo "<h1 class='sredina'>Imate ograničen pristup!</h1>";
        }else
            {
            ?>
            <div class="row">
                <div class="col-md-8 bg-dark mx-auto">
                    <main >
                        <?php
                            adminIzmenaSlikeKorisnika($idProfil, $profilnaSlika, $username, $datumRegistracije);

                            formaEditUser($username, $ime, $prezime, $pol, $tipKorisnika, $statusKorisnika, $verifikacijaKorisnika, $email, $drzava, $grad, $facebookPr, $instagramPr, $twitterPr, $tiktokPr, $sajt, $idProfil);
                                        ?>
                    </main>  
		        </div> <!-- kraj slikeAlbumaPregled -->
            </div> <!-- kraj #row -->
            <?php
            }//end if else(provjera statusa)
    }

}//end adminEditUser()

//********************************* Pozvana metoda u fajlu adminviewuser.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------
//********************************* Funkcija za promjenu slike korisnika *********************************//
function adminIzmenaSlikeKorisnika($idProfil, $profilnaSlika, $username, $datumRegistracije)
{
    //-------------------------------------------------------------------
    include_once "../classes/insertData-classes/imageUploader.class.php";
    $uploader = new ImageUploader();
    //-------------------------------------------------------------------
    global $conn;
    ?>
    <aside >
        <div class="sredina2">
            <img class="adminEditImage" src="../images/profilne/<?php echo $profilnaSlika;?>" alt="<?php echo $username;?>" title="<?php echo $username;?>">
            <p id="promSlik">Promjeni sliku</p>
            <form action="" method="POST" enctype="multipart/form-data" name="promjenaSlike" id="promjenaSlike">
                <input type="file" name="promjenaProfilneSlike"><br><br>
                <button type="submit" class="btn btn-primary" name="promjeniSliku" value="izmjeni">Izmjeni</button>
                <button type="submit" class="btn btn-danger" name="obrisi" value="obrisi">Obriši</button>
                <?php
                if(isset($_POST["promjeniSliku"]))
                {
                    if(!empty($_FILES["promjenaProfilneSlike"]) && $_FILES["promjenaProfilneSlike"]["error"] === UPLOAD_ERR_OK)
                    {
                        $res = $uploader->uploadAndUpdateImageField("promjenaProfilneSlike", "../images/profilne/", "admin_izmjena_slika_profilna", (int)$idProfil, $conn,"korisnici", /* tabela*/ "profilnaSlika",  /*kolona slike*/ "idKorisnici", /* id kolona*/ 75);

                        echo "<meta http-equiv='refresh' content='1'; url='adminviewusers.php?idus={$idProfil}'>";
                    }else{
                        echo "Niste izabrali sliku.";
                    }
                }//end master if()

                if(isset($_POST["obrisi"]))
                {
                    $q_profilnaSlika = "SELECT * FROM korisnici WHERE idKorisnici='{$idProfil}'";
                    $select_profilnaSlika = mysqli_query($conn, $q_profilnaSlika);
                    while ($row = mysqli_fetch_array($select_profilnaSlika)) 
                    {
                        $profilnaSlikaTemp = $row["profilnaSlika"];
                        $putanjaDoSlike = '../images/profilne/' . $profilnaSlikaTemp; 
                        if (file_exists($putanjaDoSlike)) 
                        {
                            if(unlink($putanjaDoSlike)){
                                echo "Slika uspješno obrisana.";
                                logUserImageDeleted($idProfil, $username);
                            //echo "<meta http-equiv='refresh' content='0'>";
                            }
                        }//end if(file_exists())
                    }//end while

                    $delete_query="UPDATE korisnici SET profilnaSlika='' WHERE idKorisnici='{$idProfil}'";
                    mysqli_query($conn, $delete_query);
                    echo "<meta http-equiv='refresh' content='0'; url='profileedit.php?{$idProfil}'>";
                }//end if()
                ?>
                <hr class="hrLinija">
                <h5>Korisnik je registrovan na sajt: <?php echo $datumRegistracije; ?></h5>
                <fieldset class="border p-5 rounded">
                <h3>
                    <a href="../profile.php?username=<?php echo $username;?>&lid=<?php echo $idProfil;?>">Klikni za pregled profila</a>
                </h3>
                </fieldset><!--end .border-->
                <br>
            </form>
        </div><!-- end .sredina2 -->
    </aside>
    <?php
}//end adminIzmenaSlikeKorisnika()


//********************************* Pozvana metoda u ovom fajlu u funkciji adminEditUser() *********************************//
//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Funkcija za prikaz forme za izmjenu *********************************//
function formaEditUser($username, $ime, $prezime, $pol, $tipKorisnika, $statusKorisnika, $verifikacijaKorisnika, $email, $drzava, $grad, $facebookPr, $instagramPr, $twitterPr, $tiktokPr, $sajt, $idProfil)
{
    $sesStatusK= $_SESSION["statusKorisnika"];
    global $conn;
    $idKorisnici= $_GET["idus"];
    if(!isset($_SESSION['username']) && !isset($_SESSION['idKorisnici']))
    {
        echo "<h1>Nemate prava pristupa!</h1>";
    }else
    {
        if(isset($_POST["izmjeni"]))
        {
            if(!empty(["grad"]))
            {
                // 1) OLD stanje iz baze (prije UPDATE)
                $qOld = "SELECT ime, prezime, username, email, pol, drzava, grad, statusKorisnika, verifikacijaKorisnika,
                                facebookPr, instagramPr, twitterPr, tiktokPr, websajt
                        FROM korisnici
                        WHERE idKorisnici='{$idProfil}' LIMIT 1";
                $rOld = mysqli_query($conn, $qOld);
                $old  = mysqli_fetch_assoc($rOld) ?: [];

                if (empty($old)) {
                    echo "Greška: korisnik nije pronađen.";
                    return;
                }

                // 2) NEW iz forme
                $username= $_POST["username"];
                $ime= $_POST["ime"];
                $prezime= $_POST["prezime"];
                $email= $_POST["email"];
                $grad= $_POST["grad"];
                $drzava= $_POST["drzava"];
                $idIzvodjaci= $_POST["izvodjac"];
                $idIzdavaci= $_POST["izdavac"];
                $idStatusKorisnika= $_POST["vrstaKorisnika"];
                $idVerifikacijaKorisnika= $_POST["idVerifikacijaKorisnika"];
                $pol= $_POST["pol"];
                $facebookPr= removeLinksSocialMedia($_POST["facebook"]);
                $instagramPr= removeLinksSocialMedia($_POST["instagram"]);
                $twitterPr= removeLinksSocialMedia($_POST["twitter"]);
                $tiktokPr= removeLinksSocialMedia($_POST["tiktok"]);
                $sajt= checkLinks($_POST["sajt"]);

                $new = [
                    'ime'                    => ($ime === '' ? null : $ime),
                    'prezime'                => ($prezime === '' ? null : $prezime),
                    'username'               => ($username === '' ? null : $username),
                    'email'                  => ($email === '' ? null : $email),
                    'pol'                    => ($pol === '' ? null : $pol),
                    'drzava'                 => ($drzava === '' ? null : $drzava),
                    'grad'                   => ($grad === '' ? null : $grad),
                    'statusKorisnika'        => ($idStatusKorisnika === '' ? null : $idStatusKorisnika),
                    'verifikacijaKorisnika'  => ($idVerifikacijaKorisnika === '' ? null : $idVerifikacijaKorisnika),
                    'facebookPr'             => ($facebookPr === '' ? null : $facebookPr),
                    'instagramPr'            => ($instagramPr === '' ? null : $instagramPr),
                    'twitterPr'              => ($twitterPr === '' ? null : $twitterPr),
                    'tiktokPr'               => ($tiktokPr === '' ? null : $tiktokPr),
                    'websajt'                => ($sajt === '' ? null : $sajt),
                ];

                // 3) DIFF old/new
                $changes = [];
                foreach ($new as $k => $v) 
                {
                    $oldVal = $old[$k] ?? null;

                    if ($oldVal === '') $oldVal = null;
                    if ($v === '') $v = null;

                    if ($oldVal != $v) {
                        $changes[$k] = [
                            'old' => $oldVal,
                            'new' => $v
                        ];
                    }
                }//end foreach

                // (Opcionalno) Ako nema promjena u korisnik tabeli, i dalje možeš imati promjenu vezivanja izvodjac/izdavac,
                // ali to ćemo uhvatiti malo niže kroz posebne changes ključeve.

                $regex = '/^[a-zA-Z0-9]+([._+-]?[a-zA-Z0-9]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.[a-zA-Z]{2,}$/';
                if (preg_match($regex, $email))
                {
                    $update_query="UPDATE korisnici SET ime='{$ime}', prezime='{$prezime}', username='{$username}', email='{$email}', pol='{$pol}',drzava='{$drzava}', grad='{$grad}', statusKorisnika='{$idStatusKorisnika}', verifikacijaKorisnika='{$idVerifikacijaKorisnika}', facebookPr='{$facebookPr}', instagramPr='{$instagramPr}', twitterPr='{$twitterPr}', tiktokPr='{$tiktokPr}', websajt='{$sajt}' WHERE idKorisnici='{$idProfil}'";
                    $command_update= mysqli_query($conn, $update_query);

                    //------Update (izvodjac veza)
                    // Prije toga uzmi staro vezivanje (ko je bio userAdmin za ovog korisnika)
                    $oldIzv = null;
                    $rOldIzv = mysqli_query($conn, "SELECT idIzvodjaci FROM izvodjaci WHERE userAdmin='{$idKorisnici}' LIMIT 1");
                    if ($rOldIzv && mysqli_num_rows($rOldIzv) > 0){
                        $tmp = mysqli_fetch_assoc($rOldIzv);
                        $oldIzv = $tmp['idIzvodjaci'] ?? null;
                    }

                    // ---------- IZVOĐAČ ----------
                    if ((int)$idStatusKorisnika === 4)
                    {
                        // obriši sve stare veze
                        mysqli_query($conn, "UPDATE izvodjaci SET userAdmin=NULL WHERE userAdmin='{$idKorisnici}'");

                        // dodijeli novi
                        if (!empty($idIzvodjaci)) {
                            mysqli_query($conn, "UPDATE izvodjaci SET userAdmin='{$idKorisnici}' WHERE idIzvodjaci='{$idIzvodjaci}'");
                        }

                    }else {

                        // ako više nije izvođač → očisti
                        mysqli_query($conn, "UPDATE izvodjaci SET userAdmin=NULL WHERE userAdmin='{$idKorisnici}'");
                    }

                    // upiši promjenu veze u changes (ako se promijenilo)
                    $newIzv = empty($idIzvodjaci) ? null : $idIzvodjaci;
                    if ($oldIzv != $newIzv) {
                        $changes['izvodjac_link'] = ['old' => $oldIzv, 'new' => $newIzv];
                    }

                    //------Update (izdavac veza)
                    $oldIzd = null;
                    $rOldIzd = mysqli_query($conn, "SELECT idIzdavaci FROM izdavaci WHERE userAdminIzdavac='{$idKorisnici}' LIMIT 1");
                    if ($rOldIzd && mysqli_num_rows($rOldIzd) > 0){
                        $tmp = mysqli_fetch_assoc($rOldIzd);
                        $oldIzd = $tmp['idIzdavaci'] ?? null;
                    }

                    // ---------- LABEL ----------
                    if ((int)$idStatusKorisnika === 5){

                        mysqli_query($conn, "UPDATE izdavaci SET userAdminIzdavac=NULL WHERE userAdminIzdavac='{$idKorisnici}'");

                        if (!empty($idIzdavaci)) {
                            mysqli_query($conn, "UPDATE izdavaci SET userAdminIzdavac='{$idKorisnici}' WHERE idIzdavaci='{$idIzdavaci}'");
                        }

                    }else {

                        mysqli_query($conn, "UPDATE izdavaci SET userAdminIzdavac=NULL WHERE userAdminIzdavac='{$idKorisnici}'");
                    }

                    $newIzd = empty($idIzdavaci) ? null : $idIzdavaci;
                    if ($oldIzd != $newIzd) {
                        $changes['izdavac_link'] = ['old' => $oldIzd, 'new' => $newIzd];
                    }

                    if($command_update == TRUE)
                    {

                        // --- PRETVARANJE ID -> NAZIV za pregledniji log ---

                        // statusKorisnika: umjesto ID (2/4/...) upiši i naziv
                        if (!empty($changes['statusKorisnika']))
                        {
                            $oldId = $changes['statusKorisnika']['old'] ?? null;
                            $newId = $changes['statusKorisnika']['new'] ?? null;

                            $changes['statusKorisnika'] = [
                                'old' => [
                                    'id'    => $oldId,
                                    'naziv' => getStatusNaziv($conn, $oldId)
                                ],
                                'new' => [
                                    'id'    => $newId,
                                    'naziv' => getStatusNaziv($conn, $newId)
                                ]
                            ];

                            // Ako je novi status Izvođač (4) i izabran izvođač -> dopiši izvodjacMaster
                            if ((string)$newId === "4") {
                                // $idIzvodjaci ti već postoji iz forme
                                $changes['izvodjac'] = [
                                    'id'    => (empty($idIzvodjaci) ? null : $idIzvodjaci),
                                    'naziv' => (empty($idIzvodjaci) ? null : getArtistMasterById($conn, $idIzvodjaci))
                                ];
                            }

                            // Ako je novi status Label (5) -> dopiši naziv izdavača
                            if ((string)$newId === "5") {
                                $changes['izdavac'] = [
                                    'id'    => (empty($idIzdavaci) ? null : $idIzdavaci),
                                    'naziv' => (empty($idIzdavaci) ? null : getLabelNazivById($conn, $idIzdavaci))
                                ];
                            }
                        }//end if (!empty($changes['statusKorisnika']))

                        // verifikacijaKorisnika: umjesto ID upiši i naziv
                        if (!empty($changes['verifikacijaKorisnika'])) 
                        {
                            $oldId = $changes['verifikacijaKorisnika']['old'] ?? null;
                            $newId = $changes['verifikacijaKorisnika']['new'] ?? null;

                            $changes['verifikacijaKorisnika'] = [
                                'old' => [
                                    'id'    => $oldId,
                                    'naziv' => getVerifikacijaNaziv($conn, $oldId)
                                ],
                                'new' => [
                                    'id'    => $newId,
                                    'naziv' => getVerifikacijaNaziv($conn, $newId)
                                ]
                            ];
                        }//end if(verifikacijaKorisnika)


                        // 4) LOG (poslije uspješnog update-a)
                        // username u logu neka bude NOVI (jer se možda promijenio)
                        if (!empty($changes)) {
                            logUserUpdated($idProfil, $username, $changes);
                        }

                        echo "<meta http-equiv='refresh' content='1'; url='profileedit.php?{$idProfil}'>";
                    }else{
                        echo "Greška " . mysqli_error($conn). "<br>";
                    }//end if else($command_update == TRUE) (slanje upita u bazu)
                }else {
                    echo "<h4 class='warning-notice'>Nije dobar format email-a.</h4>";
                }//end if else (preg_match($regex, $email))
            }//end if(!empty(["grad"]))
        }//end if(isset($_POST["izmjeni"]))
        ?>
        <section class="col">
            
            <form class="sredina2" action="" enctype="multipart/form-data" method="POST" name="editProfile" id="editProfile">
                <div class="form-group col-md-6 mx-auto mb-1">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupPrepend2">Korisničko ime</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="username" placeholder="Unesite korisničko ime" value="<?php echo $username; ?>">
                    </div><!-- end .input-group --><br><br>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupPrepend2">Ime</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="ime" placeholder="Unesite ime" value="<?php echo $ime; ?>">
                    </div><!-- end .input-group --><br><br>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupPrepend2">Prezime</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="prezime" placeholder="Unesite prezime" value="<?php echo $prezime; ?>">
                    </div><!-- end .input-group --><br><br>

                    <label for="pol" class="text-warning"><strong>Pol</strong></label><br>
                    <select class="form-control" name="pol" id="pol">
                        <?php
                        if($pol=="Muško"){
                            echo "<option value='Muško' selected>Muško</option>";
                            echo "<option value='Žensko'>Žensko</option>";
                        }else{
                            echo "<option value='Žensko' selected>Žensko</option>";
                            echo "<option value='Muško'>Muško</option>";
                        }

                        ?>
                    </select><br>
                    <?php
                    if($sesStatusK==1)
                    {
                        ?>
                        <!-- Glavni meni -->
                        <label for="statusKorisnika" class="text-warning"><strong>Tip korisnika</strong></label><br>
                        <select class="form-control" name="vrstaKorisnika" id="vrstaKorisnika">
                            <?php 
                            
                            $q = "SELECT * FROM status_korisnika";
                            $select_status = mysqli_query($conn, $q);
                            while ($row = mysqli_fetch_assoc($select_status)) 
                            {
                                $idStatusKorisnika = $row["idStatusKorisnika"];
                                $nazivStatusaKorisnika = $row["nazivStatusaKorisnika"];
                                $selected = ($idStatusKorisnika == $statusKorisnika) ? "selected" : "";
                                echo "<option value='{$idStatusKorisnika}' {$selected}>{$nazivStatusaKorisnika}</option>";
                            }//end while
                            checkUserStatus($conn, $idProfil);
                            ?>
                        </select>
                        <?php
                    }else if($sesStatusK==2)
                        {
                            ?>
                            <!-- Glavni meni -->
                            <label for="statusKorisnika" class="text-warning"><strong>Tip korisnika</strong></label><br>
                            <select class="form-control" name="vrstaKorisnika" id="vrstaKorisnika">
                                <?php 
                                $q = "SELECT * FROM status_korisnika WHERE idStatusKorisnika != 1";
                                $select_status = mysqli_query($conn, $q);
                                while ($row = mysqli_fetch_assoc($select_status)) 
                                {
                                    $idStatusKorisnika = $row["idStatusKorisnika"];
                                    $nazivStatusaKorisnika = $row["nazivStatusaKorisnika"];
                                    $selected = ($idStatusKorisnika == $statusKorisnika) ? "selected" : "";
                                    echo "<option value='{$idStatusKorisnika}' {$selected}>{$nazivStatusaKorisnika}</option>";
                                }//end while
                                checkUserStatus($conn, $idProfil);
                                ?>
                            </select>
                            <?php
                        }//end else if()
                    ?>
                    <br><br>

                    <!-- Ova dva select menija su skrivena i pojaviće se kad treba -->
                    <div id="dodatniSelect">
                        <div id="izvodjacSelect" style="display: none;">
                            <label for="izvodjac" class="text-danger"><strong>Izaberite izvođača:</strong></label>
                            <label for="izvodjac">Prilikom uklanjanja izvođača, prvo izabrati "Izaberite izvođača", zatim promjeniti status npr. slušalac ili blokiran.</label>
                            <select name="izvodjac" id="izvodjac" class="form-control">
                                <option value="">-- Izaberite izvođača --</option>
                                <?php
                                $rez = mysqli_query($conn, "SELECT idIzvodjaci, izvodjacMaster, userAdmin FROM izvodjaci ORDER BY izvodjacMaster");
                                while ($row = mysqli_fetch_assoc($rez)) 
                                {
                                    $idIzvodjaci = $row['idIzvodjaci'];
                                    $izvodjacMaster = $row['izvodjacMaster'];
                                    $userAdmin = $row['userAdmin'];

                                    // Prikaži samo one izvođače koji nemaju userAdmin (ili koji su dodeljeni ovom korisniku)
                                    if ($userAdmin == '' || is_null($userAdmin) || $userAdmin == $idKorisnici) {
                                        $selected = ($userAdmin == $idKorisnici) ? "selected" : "";
                                        echo "<option value='{$idIzvodjaci}' {$selected}>{$izvodjacMaster}</option>";
                                    }
                                }//end while
                                ?>
                            </select><!-- end #izvodjac -->
                        </div><!-- end #izvodjacSelect -->

                        <div id="izdavacSelect" style="display: none;">
                            <label for="izdavac" class="text-danger"><strong>Izaberite izdavača:</strong></label>
                            <label for="izdavac">Prilikom uklanjanja izdavača, prvo izabrati "Izaberite izdavača", zatim promjeniti status npr. slušalac ili blokiran.</label>
                            <select name="izdavac" id="izdavac" class="form-control">
                                <option value="">-- Izaberite izdavača --</option>
                                <?php
                                $rez = mysqli_query($conn, "SELECT idIzdavaci, izdavaciNaziv, userAdminIzdavac FROM izdavaci ORDER BY izdavaciNaziv");
                                while ($row = mysqli_fetch_assoc($rez)) 
                                {
                                    $idIzdavaci=$row["idIzdavaci"];
                                    $izdavaciNaziv= $row["izdavaciNaziv"];
                                    $userAdminIzdavac= $row["userAdminIzdavac"];

                                    // Prikaži samo one izdavače koji nemaju userAdminIzdavac (ili koji su dodeljeni ovom korisniku)
                                    if ($userAdminIzdavac == '' || is_null($userAdminIzdavac) || $userAdminIzdavac == $idKorisnici) {
                                        $selected = ($userAdminIzdavac == $idKorisnici) ? "selected" : "";
                                        echo "<option value='{$idIzdavaci}' {$selected}>{$izdavaciNaziv}</option>";
                                    }
                                }//end while
                                ?>        
                            </select><!-- end #izdavac -->
                        </div><!-- end #izdavacSelect -->
                    </div><!-- end #dodatniSelect -->
                                    
                    <script>
                        document.addEventListener("DOMContentLoaded", function() 
                        {
                            const vrstaKorisnika = document.getElementById("vrstaKorisnika");
                            const izvodjacSelect = document.getElementById("izvodjacSelect");
                            const izdavacSelect = document.getElementById("izdavacSelect");

                            function prikaziOdgovarajuciSelect() {
                                const value = vrstaKorisnika.value;
                                izvodjacSelect.style.display = "none";
                                izdavacSelect.style.display = "none";

                                if (value === "4") {
                                    izvodjacSelect.style.display = "block";
                                } else if (value === "5") {
                                    izdavacSelect.style.display = "block";
                                }
                            }

                            vrstaKorisnika.addEventListener("change", prikaziOdgovarajuciSelect);

                            // Ako je stranica već učitana s prethodnim izborom
                            prikaziOdgovarajuciSelect();
                        });

                        function prikaziOdgovarajuciSelect() {
                        const value = vrstaKorisnika.value;

                        // sakrij sve + reset values
                        izvodjacSelect.style.display = "none";
                        izdavacSelect.style.display = "none";

                        document.getElementById("izvodjac").value = "";
                        document.getElementById("izdavac").value = "";

                        if (value === "4") {
                            izvodjacSelect.style.display = "block";
                        } else if (value === "5") {
                            izdavacSelect.style.display = "block";
                        }
                        }

                    </script>
                </div><!-- end .form-group col-md-6 mx-auto mb-1 -->
                <br><br>
                <?php
                $zastava= "";
                ?>
                <div class="form-group col-md-6 mx-auto">
                    <!-- Verfikacija profila -->
                    <label for="verifikacija" class="text-warning"><strong>Verifikacija korisnika</strong></label><br>
                    <select class="form-control" name="idVerifikacijaKorisnika" id="idVerifikacijaKorisnika">
                        <?php 
                        $q = "SELECT * FROM verifikacija_korisnika";
                        $select_status = mysqli_query($conn, $q);
                        while ($row = mysqli_fetch_assoc($select_status)) 
                        {
                            $idVerifikacijaKorisnika = $row["idVerifikacijaKorisnika"];
                            $nazivVerifikacije = $row["nazivVerifikacije"];
                            $selected = ($idVerifikacijaKorisnika == $verifikacijaKorisnika) ? "selected" : "";
                            echo "<option value='{$idVerifikacijaKorisnika}' {$selected}>{$nazivVerifikacije}</option>";
                        }//end while
                        checkUserStatus($conn, $idProfil);
                        ?>
                    </select>
                    <br><br>
                     <!-- kraj Verfikacija profila -->

                    <label for="email" class="text-warning"><strong>Email</strong></label><br>
                    <input type="text" name="email" class="form-control" placeholder="Email" value="<?php echo $email;?>" required><br><br>
                        <label for="država" class="text-warning"><strong>Država </strong><img class='adminEditImage' src="../images/zastave/<?php echo "$drzava";?>" alt="<?php ?>"></label><br>
                        <select class="form-control" name="drzava" id="država">
                            <?php 
                            $q= "SELECT * FROM drzave2";
                            $select_drzavu= mysqli_query($conn, $q);

                            while($row= mysqli_fetch_assoc($select_drzavu))
                            {
                                $idDrzave2= $row["idDrzave2"];
                                $drzavaNaziv= $row["drzavaNaziv"];
                                $kodZemljeDugi= $row["kodZemljeDugi"];
                                $zastava= $row["zastava"];

                                if($kodZemljeDugi==$drzava){
                                    echo "<option value='{$kodZemljeDugi}' selected>$drzavaNaziv</option>";
                                }else{
                                    echo "<option value='{$kodZemljeDugi}'>$drzavaNaziv </option>";
                                }
                                echo "";
                            }//end while
                            ?>
                        </select> <br><br>
                </div><!-- end .form-group col-md-6 mx-auto -->

                <div class="form-group col-md-6 mx-auto">
                    <div class="input-group">
                        <input type="text" class="form-control" name="grad" placeholder="Grad" value="<?php echo $grad; ?>">
                    </div><!-- end .input-group --><br><br>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupPrepend2">https://www.facebook.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="facebook" placeholder="Facebook profil" value="<?php echo $facebookPr; ?>">
                    </div><!-- end .input-group --><br><br>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupPrepend2">https://www.instagram.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="instagram" placeholder="Instagram profil" value="<?php echo $instagramPr; ?>">
                    </div><!-- end .input-group --><br><br>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupPrepend2">https://www.x.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="twitter" placeholder="X profil" value="<?php echo $twitterPr; ?>">
                    </div><!-- end .input-group --><br><br>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupPrepend2">https://www.tiktok.com/@</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="tiktok" placeholder="Tik-tok profil" value="<?php echo $tiktokPr; ?>">
                    </div><!-- end .input-group --><br><br>
                    
                    
                    <label for="sajt"  class="text-warning"><strong>Unesite pun naziv sajta sa početkom kao https:// ili kao www.</strong></label><br>
                    <div class="input-group">
                        <input type="text" name="sajt" class="form-control" placeholder="Vaš sajt" value="<?php echo $sajt; ?>">
                    </div><!-- end .input-group --><br><br>
                    
                    
                    <div class="sredina">
                        <button type="submit" class="btn btn-primary" name="izmjeni" value="izmjeni">Izmjeni</button>
                    </div>
                </div><!-- end .form-group col-md-6 mx-auto -->
            </form>
            <hr class="hrLinija">
            
            <?php 
            if(isset($_POST["novaSifra"]))
            {
                if(!empty($_POST["pass1"]) && !empty($_POST["pass2"]))
                {
                    $password1= trim($_POST["pass1"]);
                    $password2= trim($_POST["pass2"]);

                    if($password1===$password2)
                    {
                        if (mb_strlen($password1, "UTF-8") < 8) 
                            {
                            echo "<h4 class='warning'>Šifra mora imati najmanje 8 karaktera</h4>";
                        } else 
                        {
                            $sifrovano = password_hash($password1, PASSWORD_DEFAULT);
                            $sifrovano2= hash("gost-crypto", $password2);
                            $update_password="UPDATE korisnici SET sifra='{$sifrovano}', sifra2='{$sifrovano2}' WHERE idKorisnici='{$idProfil}'";
                            $command_update_password= mysqli_query($conn, $update_password);

                            if($command_update_password == TRUE)
                            {
                                 // OLD username za target (da log ne zavisi od forme)
                                $rU = mysqli_query($conn, "SELECT username FROM korisnici WHERE idKorisnici='{$idProfil}' LIMIT 1");
                                $uRow = mysqli_fetch_assoc($rU);
                                $targetUsername = $uRow['username'] ?? null;

                                logUserPasswordChanged($idProfil, $targetUsername);

                                echo "<meta http-equiv='refresh' content='1'; url='profileedit.php?{$idProfil}'>";
                            }else{
                                echo "Greška " . mysqli_error($conn). "<br>";
                            }//end if($command_update_password == TRUE)
                        }//end if else (dužina šifre)
                    }else{
                        echo "<h4 class='warning-notice'>Šifra nije ista</h4>";
                    }
                }else{
                    echo "<h4 class='warning-notice'>Polja za unos šifre ne mogu biti prazna</h4>";
                }//end if else(provera upoređivanja obe šifre)
            }// end if(isset($_POST["novaSifra"]))
            ?>
            <section class="sredina">
                <div class="form-group col-md-6 mx-auto">
                    <form action="" method="POST" name="editProfile" id="editProfile">
                        <h3>Promjena šifre</h3>
                        <h6 class="sredina">Šifra mora imati najmanje 8 karaktera</h6>

                        <div class="input-group">
                            <input type="password" name="pass1" class="form-control" placeholder="Unesite novu šifru">
                        </div><br><br><!-- end .input-group -->

                        <div class="input-group">
                            <input type="password" name="pass2" class="form-control" placeholder="Ponovite novu šifru">
                        </div><br><br><!-- end .input-group -->

                        <button type="submit" class="btn btn-danger" name="novaSifra" value="novaSifra">Nova šifra</button>
                    </form>
                </div><!-- end .form-group col-md-6 mx-auto -->
            </section><!-- end .sredina -->
        </section><!-- end .col -->
        <?php
    }//end master if else()
    
}//end formaEditUser()

//********************************* Pozvana metoda u ovom fajlu u funkciji adminEditUser() *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Funkcija koja prati izmjenu statusa tipa korisnika u logovima *********************************//
function getStatusNaziv($conn, $idStatus)
{
    if (empty($idStatus)) return null;
    $idStatus = (int)$idStatus;

    $q = "SELECT nazivStatusaKorisnika FROM status_korisnika WHERE idStatusKorisnika='{$idStatus}' LIMIT 1";
    $r = mysqli_query($conn, $q);
    $row = mysqli_fetch_assoc($r);

    return $row['nazivStatusaKorisnika'] ?? null;
}//end getStatusNaziv()

//********************************* Pozvana u ovom fajlu u metodi FormaEditUser() *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Funkcija koja prati izmjenu statusa verifikacije u logovima *********************************//
function getVerifikacijaNaziv($conn, $idVer)
{
    if (empty($idVer)) return null;
    $idVer = (int)$idVer;

    $q = "SELECT nazivVerifikacije FROM verifikacija_korisnika WHERE idVerifikacijaKorisnika='{$idVer}' LIMIT 1";
    $r = mysqli_query($conn, $q);
    $row = mysqli_fetch_assoc($r);

    return $row['nazivVerifikacije'] ?? null;
}//end getVerifikacijaNaziv()
//********************************* Pozvana u ovom fajlu u metodi FormaEditUser() *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Ako je novi status korisnika izvođač da piše u logovima koji izvođač mu je doijeljen *********************************//
function getArtistMasterById($conn, $idIzv)
{
    if (empty($idIzv)) return null;
    $idIzv = (int)$idIzv;

    $q = "SELECT izvodjacMaster FROM izvodjaci WHERE idIzvodjaci='{$idIzv}' LIMIT 1";
    $r = mysqli_query($conn, $q);
    $row = mysqli_fetch_assoc($r);

    return $row['izvodjacMaster'] ?? null;
}//end getArtistMasterById()
//********************************* Pozvana u ovom fajlu u metodi FormaEditUser() *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Ako je novi status korisnika izdavač/Label da piše u logovima koji Label mu je doijeljen *********************************//
function getLabelNazivById($conn, $idIzd)
{
    if (empty($idIzd)) return null;
    $idIzd = (int)$idIzd;

    $q = "SELECT izdavaciNaziv FROM izdavaci WHERE idIzdavaci='{$idIzd}' LIMIT 1";
    $r = mysqli_query($conn, $q);
    $row = mysqli_fetch_assoc($r);

    return $row['izdavaciNaziv'] ?? null;
}//end getLabelNazivById()
//********************************* Pozvana u ovom fajlu u metodi FormaEditUser() *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

