<?php

//METODE U OVOM FAJLU
//nazivAlbuma (Prikaz naziva izabranog albuma koja vodi na uređivanje čitavog albuma ADMIN, MODERATOR, IZVOĐAČ, IZDAVAČ)
//updateBiografija (ažuriranje biografije izvođača ADMIN, MODERATOR, IZVOĐAČ)

//********************************* Metoda za prikaz naziva izabranog albuma koja vodi na uređivanje čitavog albuma ADMIN, MODERATOR, IZVOĐAČ, IZDAVAČ *********************************//
function nazivAlbuma($idAlb)
{
    global $conn;
    $q= "SELECT idAlbum, nazivAlbuma, slikaAlbuma FROM albumi WHERE idAlbum='{$idAlb}'";
    $select_album= mysqli_query($conn, $q);
    @$idIzv= $_GET["idIzv"];

    while($row = mysqli_fetch_array($select_album))
    {
        $nazivAlbuma= $row["nazivAlbuma"];
        $slikaAlbuma= $row["slikaAlbuma"];
        ?>
        <a href="updatesongs.php?idIzv=<?php echo $idIzv; ?>&idAlb=<?php echo $idAlb; ?>"><h4 class="naslov-centar text-warning"><?php echo $nazivAlbuma; ?></h4></a>
        <?php
    }//end while
}//end nazivAlbuma()
//********************************* Pozvana metoda u fajlu adminFunkcije.func.php u metodi adminSpisakPjesama, updateAboutAlbum,updateAboutAlbumLabel  *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Metoda kojom se vrši ažuriranje biografije izvođača ADMIN, MODERATOR, IZVOĐAČ,  *********************************//
function updateBiografija($idIzv, $statusKorisnika)
{
    //-------------------------------------------------------------------
    include_once "../classes/insertData-classes/imageUploader.class.php";
    $uploader = new ImageUploader();
    //-------------------------------------------------------------------
    $imgWork= new adminWorkImages();

    global $conn;
    $q= "SELECT * FROM izvodjaci WHERE  idIzvodjaci='{$idIzv}'";
    $select_tekst= mysqli_query($conn, $q);

    while($row= mysqli_fetch_array($select_tekst))
    {
        $idIzvodjaci= $row["idIzvodjaci"];
        $izvodjacMaster= $row["izvodjacMaster"];
        $ime= $row["ime"];
        $prezime= $row["prezime"];
        $tipIzvodjaca= $row["tipIzvodjaca"];
        $drzavaIzvodjac= $row["drzavaIzvodjac"];
        $entitetIzvodjac= $row["entitetIzvodjac"];
        $clanGrupe= $row["clanoviOveGrupe"];
        $slikaIzvodjac= $row["slikaIzvodjac"];
        $biografija= $row["biografija"];
        $nadimciIzvodjac= $row["nadimciIzvodjac"];
        $izvodjacFacebook= $row["izvodjacFacebook"];
        $izvodjacInstagram= $row["izvodjacInstagram"];
        $izvodjacSajt= $row["izvodjacSajt"];

        if(isset($_POST["obrisiSlikuIzvodjaca"])){
                    $imgWork->obrisiSlikuIzvodjaca($idIzv);
                    logLabelImageDeleted($idIzvodjaci, $izvodjacMaster);
                }
    
        if(isset($_POST["promjeniSliku"]))
        {
            if(!empty($_FILES["promjenaSlikeIzvodjaca"]["name"]) && $_FILES["promjenaSlikeIzvodjaca"]["error"] === UPLOAD_ERR_OK)
            {
                $res = $uploader->uploadAndUpdateImageField("promjenaSlikeIzvodjaca", "../images/izvodjaci/", "promjena_slike_izvodjaca", (int)$idIzv, $conn,"izvodjaci", /* tabela*/ "slikaIzvodjac",  /*kolona slike*/ "idIzvodjaci", /* id kolona*/ 75);
                echo "<meta http-equiv='refresh' content='1'; url='adminalbumi.php'>";
            }else if(empty($_FILES["promjenaSlikeIzvodjaca"])){
                echo "Niste izabrali sliku.";
            }
        }//end master if()
        ?>
        <div class="slikeAlbumaPregled sredina">
            <div class="nazivAlbumPanel">
                <img src="../images/izvodjaci/<?php echo $slikaIzvodjac; ?>" alt="<?php echo $izvodjacMaster; ?>" title="<?php echo $izvodjacMaster; ?>" class=""/>
                <form action="" method="POST" enctype="multipart/form-data" name="promjenaSlike" id="promjenaSlike">
            
                    <input type="file" class="btn btn-light" name="promjenaSlikeIzvodjaca"><br><br>
                    <button type="submit" class="btn btn-primary" name="promjeniSliku" value="izmjeni">Izmjeni</button>
                    <br><hr class="hrLinija">
                    <button type="submit" class="btn btn-danger" name="obrisiSlikuIzvodjaca" value="obrisiSlikuIzvodjaca">Obriši</button><br><br>
                </form> 
            </div><!-- end .nazivAlbumPanel -->

            <script>
                document.getElementById('buttonid').addEventListener('click', openDialog);
                function openDialog() {
                document.getElementById('promjenaSlikeIzvodjaca').click();
                }
            </script>
            
            <form method="POST" action="" enctype="multipart/form-data" name="izmjenaBiografije" id="izmjenaBiografije">
                <?php
                if($statusKorisnika==1){
                    ?>
                <label for="nazivIzvodjaca" class="text-warning"><strong>Naziv izvođača ili grupe</label><br>
                <input type="text" name="nazivIzvodjaca" class="form-control form-control-sm text-danger"  value="<?php echo $izvodjacMaster; ?>"><br><br>

                <label for="ime"><strong class="text-warning">Ime</strong></label><br>
                <input type="text" name="ime" class="form-control form-control-sm text-danger" value="<?php echo $ime; ?>"><br><br>
                <label for="prezime"><strong class="text-warning">Prezime</strong></label><br>
                <input type="text" name="prezime" class="form-control form-control-sm text-danger" value="<?php echo $prezime; ?>"><br><br>
                <?php
                }else{
                    ?>
                    <label for="nazivIzvodjaca" class="text-warning"><strong>Naziv izvođača ili grupe</label><br>
                    <input type="text" name="nazivIzvodjaca" class="form-control form-control-sm text-danger"  value="<?php echo $izvodjacMaster; ?>" readonly><br>

                    <label for="ime"><strong class="text-warning">Ime</strong></label><br>
                    <input type="text" name="ime" class="form-control form-control-sm text-danger" value="<?php echo $ime; ?>" readonly><br><br>
                    <label for="prezime"><strong class="text-warning">Prezime</strong></label><br>
                    <input type="text" name="prezime" class="form-control form-control-sm text-danger" value="<?php echo $prezime; ?>" readonly><br><br>
                    <?php
                }
                ?>
                <label for="nadimci"><strong class="text-warning">Ostali nazivi / aliases</strong></label><br>
                <b><input type="text" name="nadimci" class="form-control form-control-sm text-dark" value="<?php echo $nadimciIzvodjac; ?>" placeholder="npr. Skajvikler, Sky Wikluh, Vikler Skaj"></b><br><br>

                <fieldset>
                <legend class="text-warning"><strong>Tip izvođača</strong></legend>
                    <div class="form-check">
                    <input type="radio" class="form-check-input" name="tipIzvodjaca" id="solo" value="solo"  <?php if($tipIzvodjaca == "solo") echo "checked"; ?>>
                    <label for="tipIzvodjaca" class="form-check-label text-white">Solo izvođač</label>
                    </div><!-- end .input-group-prepend -->
                    <br>
                    
                    <div class="form-check">
                    <input type="radio" class="form-check-input" name="tipIzvodjaca" id="grupa" value="grupa" <?php if($tipIzvodjaca == "grupa") echo "checked"; ?>>
                    <label for="tipIzvodjaca" class="form-check-label text-white">Grupa</label>
                    </div><!-- end .input-group -->
                    <br>
                </fieldset>
                <br><br>

                <label for="clanGrupe"><strong class="text-warning">Član grupe</strong></label><br>
                <input type="text" name="clanGrupe" class="form-control form-control-sm" value="<?php echo $clanGrupe; ?>"><br><br>

                <label for="drzava" class="text-warning"><strong>Država (koje mu je prvo/osnovno tržište)</strong></label><br>
                <select class="form-control" name="drzava" id="drzava">
                    <option class="form-control" disabled selected value="">Izaberite državu</option>
                    <?php 
                        $q= "SELECT * FROM drzave";
        $select_drzavu= mysqli_query($conn, $q);

        while($row= mysqli_fetch_assoc($select_drzavu))
        {
            $idDrzave= $row["idDrzave"];
            $drzavaNaziv= $row["nazivDrzave"];
            $kodZemljeDugi= $row["kodZemljeDugi"];
            $zastava= $row["zastavaDrzave"];

            // selected ako je država izvođača
            $selected = ($idDrzave == $drzavaIzvodjac) ? "selected" : "";
            
            echo "<option value='{$idDrzave}' {$selected}>$drzavaNaziv </option>";
        }                              
                        ?>
                </select>
                <br><br>

                <label for="entitet" class="hide text-warning"><strong>Entitet (ako je iz BiH obavezno polje)</strong></label><br>
                <select class="form-control hide" name="entitet" id="entitet">
                    <option class="form-control" value="">Izaberite entitet</option>
                    <?php 
                        $q= "SELECT * FROM entiteti";
        $select_drzavu= mysqli_query($conn, $q);

        while($row= mysqli_fetch_assoc($select_drzavu))
        {
            $idEntiteti= $row["idEntiteti"];
            $entitetNaziv= $row["entitetNaziv"];
            $entDrzava= $row["entDrzava"];
            $zastavaEnt= $row["zastavaEnt"];
            $kodEntiteta= $row["kodEntiteta"];

            // selected ako je entitet izvođača
            $selectedEnt = ($kodEntiteta == $entitetIzvodjac) ? "selected" : "";
            echo "<option value='{$kodEntiteta}' {$selectedEnt}>$entitetNaziv </option>";
        }                     
                        ?>
                </select><br><br>

                <script>
                    // Добијте референцу на оба <select> тага
                    const drzavaSelect  = document.getElementById('drzava');
                    const entitetSelect = document.getElementById('entitet');
                    const entitetLabel  = document.getElementById('entitetLabel'); // ako imaš id na labelu

                    function resetEntitet() {
                        entitetSelect.value = "";          // vrati na "Izaberite entitet"
                    }

                    function toggleEntitet(forceReset = false) {

                        if (drzavaSelect.value === '2') { // BiH
                            entitetSelect.style.display = 'block';
                            if (entitetLabel) entitetLabel.style.display = 'block';

                            entitetSelect.disabled = false;

                            // Ako je tek izabrana BiH (ili želiš uvijek reset) -> pokaži placeholder
                            if (forceReset) {
                                resetEntitet();
                            }

                        } else {
                            // nije BiH -> sakrij + obavezno reset + disable (da se NE šalje u POST)
                            resetEntitet();

                            entitetSelect.disabled = true;
                            entitetSelect.style.display = 'none';
                            if (entitetLabel) entitetLabel.style.display = 'none';
                        }
                    }

                    // Na load: prikaži po defaultu ako je već BiH u bazi (edit mode),
                    // ali NE resetuj da ne uništi postojeću vrijednost.
                    document.addEventListener("DOMContentLoaded", function() {
                        toggleEntitet(false);
                    });

                    // Na promjenu države: uvijek resetuj entitet
                    drzavaSelect.addEventListener('change', function() {
                        toggleEntitet(true);
                    });
                </script>

                <label for="biografija"><strong class="text-warning">Biografija</strong></label><br>
                <textarea class="dodajTekst" name="biografija"><?php echo $biografija; ?></textarea><br><br>

                <div class="col-auto">
                    <label for="sajt"><strong class="text-warning">Facebook profil izvođača</strong></label><br>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">https://www.facebook.com/</div><!-- end .input-group-text -->
                        </div><!-- end .input-group-prepend -->
                    <input type="text"  class="form-control" name="facebookIzvodjac" id="facebookIzvodjac" value="<?php echo $izvodjacFacebook; ?>" placeholder="Facebook profil">
                    </div><!-- .input-group mb-2 -->
                </div><!-- end .col-auto -->
                    
                <br><br>

                <div class="col-auto">
                    <label for="sajt"><strong class="text-warning">Instagram profil izvođača</strong></label><br>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">https://www.instagram.com/</div><!-- end .input-group-text -->
                        </div><!-- end .input-group-prepend -->
                        <input type="text"  class="form-control" name="instagramIzvodjac" id="instagramIzvodjac" value="<?php echo $izvodjacInstagram; ?>"placeholder="Instagram profil">
                    </div><!-- .input-group mb-2 -->
                </div><!-- end .col-auto --><br><br>

                <div class="col-auto">
                    <label for="sajtLog"><strong class="text-warning">Sajt izvođača</strong></label><br>
                    <label for="sajt">Unesite pun naziv sajta sa početkom kao https:// ili kao www.</label><br>
                    <input type="text"  class="form-control" name="sajtIzvodjac" id="sajtIzvodjac" value="<?php echo $izvodjacSajt; ?>" placeholder="Sajt izvođača">
                </div><!-- end .col-auto --><br><br>


                <button class="btn btn-primary mb-0" type="submit" name="posalji" >Pošalji</button>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input class="btn btn-warning mt-0" type="reset" value="Reset">
            </form>
        </div><!-- end .slikeAlbumaPregled sredina -->                
        <?php 
    }//end while

    if(isset($_POST["posalji"]))
    {
		// 1) OLD stanje iz baze (prije UPDATE)
        $qOld = "SELECT ime, prezime, idIzvodjaci, izvodjacMaster, nadimciIzvodjac, tipIzvodjaca, drzavaIzvodjac, entitetIzvodjac, clanoviOveGrupe, biografija, izvodjacFacebook, izvodjacInstagram, izvodjacSajt
                FROM izvodjaci
                WHERE idIzvodjaci='{$idIzv}'
                LIMIT 1";
        $rOld = mysqli_query($conn, $qOld);
        $old  = mysqli_fetch_assoc($rOld) ?: [];

        if (empty($old)) {
            echo "Greška: izvođač nije pronađen.";
            return;
        }

        // 2) NEW stanje iz forme
        $izvodjacMaster= trim(removeSimbols($_POST["nazivIzvodjaca"] ?? ''));
        $ime= trim(removeSimbols($_POST["ime"] ?? ''));
        $prezime= trim(removeSimbols($_POST["prezime"] ?? ''));
        $nadimci= trim(removeSimbols($_POST["nadimci"] ?? ''));
        $tipIzvodjaca= trim(removeSimbols($_POST["tipIzvodjaca"] ?? ''));
        $drzavaIzvodjac= trim(removeSimbols($_POST["drzava"] ?? ''));
        $entitetIzvodjac= trim(removeSimbols($_POST["entitet"] ?? ''));
        // Ako država nije BiH -> entitet mora biti NULL
        if ($drzavaIzvodjac !== '2' && $drzavaIzvodjac !== 2) {
            $entitetIzvodjac = null;
        }

        $clanGrupe= trim(removeSimbols($_POST["clanGrupe"] ?? ''));
        $biografija= cleanText($_POST["biografija"] ?? '');
        $facebookIzvodjac= removeLinksSocialMedia($_POST["facebookIzvodjac"] ?? '');
        $instagramIzvodjac= removeLinksSocialMedia($_POST["instagramIzvodjac"] ?? '');
        $sajtIzvodjac= checkLinks($_POST["sajtIzvodjac"] ?? '');

        

        $new = [
            'izvodjacMaster'   => ($izvodjacMaster === '' ? null : $izvodjacMaster),
            'ime'              => ($ime === '' ? null : $ime),
            'prezime'          => ($prezime === '' ? null : $prezime),
            'nadimciIzvodjac'  => ($nadimci === '' ? null : $nadimci),
            'tipIzvodjaca'     => ($tipIzvodjaca === '' ? null : $tipIzvodjaca),
            'drzavaIzvodjac'     => ($drzavaIzvodjac === '' ? null : $drzavaIzvodjac),
            'entitetIzvodjac'     => ($entitetIzvodjac === '' ? null : $entitetIzvodjac),
            'clanoviOveGrupe'  => ($clanGrupe === '' ? null : $clanGrupe),
            'biografija'       => ($biografija === '' ? null : $biografija),
            'izvodjacFacebook' => ($facebookIzvodjac === '' ? null : $facebookIzvodjac),
            'izvodjacInstagram'=> ($instagramIzvodjac === '' ? null : $instagramIzvodjac),
            'izvodjacSajt'     => ($sajtIzvodjac === '' ? null : $sajtIzvodjac),
        ];

        // SQL vrijednosti (NULL ili 'value')
        $drzavaSql  = ($new['drzavaIzvodjac'] === null) 
            ? "NULL" 
            : "'" . mysqli_real_escape_string($conn, $new['drzavaIzvodjac']) . "'";

        $entitetSql = ($new['entitetIzvodjac'] === null) 
            ? "NULL" 
            : "'" . mysqli_real_escape_string($conn, $new['entitetIzvodjac']) . "'";

        // 3) DIFF old/new
        $changes = [];

        foreach ($new as $k => $v) {
            $oldVal = $old[$k] ?? null;

            if ($oldVal === '') $oldVal = null;
            if ($v === '') $v = null;

            if ($oldVal != $v) {

                // Biografija može biti duga -> ne loguj sadržaj
                if ($k === 'biografija') {
                    $changes[$k] = [
                        'old_len' => is_string($oldVal) ? mb_strlen($oldVal, 'UTF-8') : 0,
                        'new_len' => is_string($v) ? mb_strlen($v, 'UTF-8') : 0
                    ];
                } else {
                    $changes[$k] = [
                        'old' => $oldVal,
                        'new' => $v
                    ];
                }
            }
        }

        // 3.1) Log prikaz za drzava/entitet (umjesto samo ID)
        if (isset($changes['drzavaIzvodjac'])) {
            $oldId = $changes['drzavaIzvodjac']['old'] ?? null;
            $newId = $changes['drzavaIzvodjac']['new'] ?? null;

            $changes['drzavaIzvodjac']['old_naziv'] = ($oldId !== null) ? drzavaNazivById($oldId) : null;
            $changes['drzavaIzvodjac']['new_naziv'] = ($newId !== null) ? drzavaNazivById($newId) : null;
        }

        if (isset($changes['entitetIzvodjac'])) {
            $oldKod = $changes['entitetIzvodjac']['old'] ?? null;
            $newKod = $changes['entitetIzvodjac']['new'] ?? null;

            // Ovdje već imaš RS/FBiH kao kod (što je super),
            // a dodajemo i naziv iz tabele (npr. "Republika Srpska", "Federacija BiH")
            $changes['entitetIzvodjac']['old_naziv'] = ($oldKod !== null) ? entitetNazivByKod($oldKod) : null;
            $changes['entitetIzvodjac']['new_naziv'] = ($newKod !== null) ? entitetNazivByKod($newKod) : null;
        }

        // 4) Ako nema promjena - nema update/log
        if (empty($changes)) {
            echo "<h4 class='boja sredina'>Nema promjena za snimiti.</h4>";
            return;
        }

        // 5) UPDATE
        $q2= "UPDATE izvodjaci SET 
            izvodjacMaster='{$new['izvodjacMaster']}', 
            ime='{$new['ime']}', 
            prezime='{$new['prezime']}', 
            nadimciIzvodjac='{$new['nadimciIzvodjac']}', 
            tipIzvodjaca='{$new['tipIzvodjaca']}',
            drzavaIzvodjac={$drzavaSql},
            entitetIzvodjac={$entitetSql},
            clanoviOveGrupe='{$new['clanoviOveGrupe']}', 
            biografija='{$new['biografija']}', 
            izvodjacFacebook='{$new['izvodjacFacebook']}', 
            izvodjacInstagram='{$new['izvodjacInstagram']}', 
            izvodjacSajt='{$new['izvodjacSajt']}' 
        WHERE idIzvodjaci='{$idIzv}'";

        $update_izvodjac= mysqli_query($conn, $q2);

        if($update_izvodjac == TRUE)
        {
            // 6) LOG (old/new)
            logArtistUpdated((int)$old['idIzvodjaci'], ($new['izvodjacMaster'] ?? $old['izvodjacMaster']), $changes);


            echo "<meta http-equiv='refresh' content='1'; url='adminalbumi.php'>";
        }else{
            echo "Greška " . mysqli_error($conn). "<br>";
        }
    }//end master if()
}//end updateBiografija()

//********************************* Pozvana metoda u adminupdateartist.php  *********************************//

//--------------------------------------------------------------------------------------------------------------------------------