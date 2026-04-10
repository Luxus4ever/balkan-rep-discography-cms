<?php

//METODE U OVOM FAJLU
//tipKorisnikaAdmin (Metoda koja prikazuje koji je tip korisnika za admin panel)
//adminSpisakAlbumaIzvodjaca (prikaz svih albuma u lijevom panelu za admina i moderatora)
//adminSpisakSinglovaIzvodjaca (prikaz svih singlova u lijevom panelu za admina i moderatora)
//adminSpisakAlbumaIzvodjacaStrimovi (prikaz svih albuma u lijevom panelu za admina i moderatora)
//adminSpisakPjesama (pjesme izabranog album)
//adminUpdateSong (ažuriranje jedne pjesme)
//adminIzabraniAlbum (prikazuje album i spisak pjesama sa lijevog menija IZVOĐAČI)
//adminIzabraniSingleAlbum (prikazuje album i spisak pjesama sa lijevog menija ALBUMI za MODERATORE)
//updateAboutAlbum (forma za izmjenu podataka o albumu ADMIN, MODERATOR, IZVOĐAČ)
//updateAboutAlbumLabel (Funkcija za izmjenu podataka o albumu za IZDAVAČ/LABEL)
//updateWholeAlbum (Funkcija za izmjenu svih pjesama odjednom na albumu)
//zabranjenPristup (Zabranjen pristup za lijevi panel)
//zabranjenPristupBezValidacije (Zabranjen pristup preko čitave stranice)
//zabranjenPristup1 (Zabranjen pristup za lijevi panel sa unosom teksta i bojom teksta)
//zabranjenPristup2 (Zabranjen pristup za srednji panel sa unosom teksta i bojom teksta)

//adminIzabraniJedanSingle (Funkcija kojom se mijenjaju podaci o jednom singlu)
//zamjenaNaslovneSlikeSingla (Funkcija kojom mijenjamo naslovnu sliku singla)
//sqlNullable (Metoda koja upisuje null u bazu bez navodnika)


//********************************* Metoda koja prikazuje tip korinsika za admin panel  *********************************//
function tipKorisnikaAdmin($idKorisnici){
    global $conn;

    $q= "SELECT * FROM korisnici JOIN status_korisnika ON statusKorisnika=idStatusKorisnika WHERE idKorisnici='{$idKorisnici}'";
    $select_korisnik= mysqli_query($conn, $q);

    $tipKorisnika= "";
    while($row= mysqli_fetch_array($select_korisnik))
    {
        $tipKorisnika = $row["nazivStatusaKorisnika"];
    }
    echo "<small>Tip profila: <span class='user-role' data-role=$tipKorisnika>$tipKorisnika</span> </small>";
    ?>
    <style>
        .role-admin { color: aliceblue; }
        .role-moderator { color: #f3db7b; }
        .role-Izvođač { color: #01cee5; }
        .role-Izdavač\/Label { color: #6d6dff; }
        .role-Blokiran { color: red; }
        .role-default { color: gray; }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var roleElements = document.querySelectorAll('.user-role');
            roleElements.forEach(function(element) {
                var role = element.getAttribute('data-role');
                element.className = 'role role-' + (role ? role : 'default');
            });
        })
    </script>
    <?php

}//end tipKorisnika()
/********************************* Metoda pozvana u fajlovimaadminEditPanel.class.php, artistEditPanel.class.php, labelEditPanel.class.php  *********************************/

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Metoda za prikaz svih albuma u lijevom panelu za Administratora i Moderatora *********************************//
function adminSpisakAlbumaIzvodjaca($idIzv)
{
    global $conn;

    $qNaziv= "SELECT * FROM izvodjaci WHERE idIzvodjaci='{$idIzv}'";
    $red= mysqli_fetch_assoc(mysqli_query($conn, $qNaziv));
    ?>
    <h6 class="sredina izvodjacAdmin"><?php echo $red["izvodjacMaster"]; ?></h6>
    
    <div class="sredina">
        <?php
        $q= "SELECT * FROM albumi 
        JOIN izvodjaci ON izvodjaci.idIzvodjaci=albumi.idIzvodjacAlbumi
        WHERE idIzvodjacAlbumi='{$idIzv}' OR idIzvodjac2='{$idIzv}' OR idIzvodjac3='{$idIzv}'";

        $select_tekst= mysqli_query($conn, $q);
        $izvodjacMaster= mysqli_query($conn, $q);
        ?>
    
        <ul>
            <?php
            while($row= mysqli_fetch_array($select_tekst))
            {
                $idAlbum= $row["idAlbum"];
                $idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
                $idIzvodjac2= $row["idIzvodjac2"];
                $idIzvodjac3= $row["idIzvodjac3"];
                $nazivAlbuma= $row["nazivAlbuma"];
                $godinaIzdanja= $row["godinaIzdanja"];
                $idIzvodjaci= $row["idIzvodjaci"];
                $izvodjacMaster= $row["izvodjacMaster"];
                ?>
                <li class=""><a class="text-decoration-none" href="showalbum.php?idIzv=<?php echo $idIzvodjacAlbumi; ?>&idAlb=<?php echo $idAlbum; ?>"><?php echo $nazivAlbuma . " ($godinaIzdanja.)"; ?></a></li>
                <?php 
            }
            ?>
        </ul>   
    </div><!-- end .sredina --> 
    <?php
}//end adminSpisakAlbumaIzvodjaca()
//********************************* Pozvana metoda u fajlu adminEditPanel.class.php u metodi leftSideAdmin()  *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Metoda za prikaz svih singlova u lijevom panelu za Administratora i Moderatora *********************************//
function adminSpisakSinglovaIzvodjaca($idIzv)
{
    global $conn;

    $qNaziv= "SELECT * FROM izvodjaci WHERE idIzvodjaci='{$idIzv}'";
    $red= mysqli_fetch_assoc(mysqli_query($conn, $qNaziv));

    $izvodjacNaziv= $red["izvodjacMaster"];
    ?>
    <h6 class="sredina izvodjacAdmin"><?php echo $red["izvodjacMaster"]; ?></h6>
    
    <div class="sredina">
        <?php
        $q= "SELECT * 
        FROM singlovi 
        WHERE (
            singleFeat LIKE '%$izvodjacNaziv%' 
            OR singleIzvodjaci LIKE '%$izvodjacNaziv%'
        )
        AND EXISTS (
            SELECT 1 FROM izvodjaci 
            WHERE izvodjacMaster LIKE '%$izvodjacNaziv%' 
            OR nadimciIzvodjac LIKE '%$izvodjacNaziv%'
        ) ";

        $select_singl_list= mysqli_query($conn, $q);
        $izvodjacMaster= mysqli_query($conn, $q);
        ?>
    
        <ul>
            <?php
            while($row= mysqli_fetch_array($select_singl_list))
            {
                $idSinglovi= $row["idSinglovi"];
                $singleFeat= $row["singleFeat"];
                $singleIzvodjaci= $row["singleIzvodjaci"];
                $singlNaziv= $row["singlNaziv"];
                $godinaIzdanjaSingl= $row["godinaIzdanjaSingl"];
                ?>
                <li class=""><a class="text-decoration-none" href="showonesingle.php?single=<?php echo $idSinglovi; ?>"><?php echo $singlNaziv . " ($godinaIzdanjaSingl.)"; ?></a></li>
                <?php 
            }
            ?>
        </ul>   
    </div><!-- end .sredina --> 
    <?php
}//end adminSpisakAlbumaIzvodjaca()
//********************************* Pozvana metoda u fajlu adminEditPanel.class.php u metodi leftSideAdmin()  *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Metoda za prikaz albuma za Strimove za Administratora i Moderatora *********************************//
function adminSpisakAlbumaIzvodjacaStrimovi($idIzv)
{
    global $conn;
    $qNaziv= "SELECT * FROM izvodjaci WHERE idIzvodjaci='{$idIzv}'";
    $red= mysqli_fetch_assoc(mysqli_query($conn, $qNaziv));
    ?>
    <h6 class="sredina izvodjacAdmin"><?php echo $red["izvodjacMaster"]; ?></h6>
    
    <div class="sredina">
        <?php
        $q= "SELECT * FROM albumi 
        JOIN izvodjaci ON izvodjaci.idIzvodjaci=albumi.idIzvodjacAlbumi
        WHERE idIzvodjacAlbumi='{$idIzv}' OR idIzvodjac2='{$idIzv}' OR idIzvodjac3='{$idIzv}'";

        $select_tekst= mysqli_query($conn, $q);
        $izvodjacMaster= mysqli_query($conn, $q);
        ?>
    
        <ul>
            <?php
            while($row= mysqli_fetch_array($select_tekst))
            {
                $idAlbum= $row["idAlbum"];
                $idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
                $idIzvodjac2= $row["idIzvodjac2"];
                $idIzvodjac3= $row["idIzvodjac3"];
                $nazivAlbuma= $row["nazivAlbuma"];
                $godinaIzdanja= $row["godinaIzdanja"];
                $idIzvodjaci= $row["idIzvodjaci"];
                $izvodjacMaster= $row["izvodjacMaster"];
                ?>              
                <li class=""><a class="text-decoration-none" href="showalbumstreams.php?idIzv=<?php echo $idIzvodjacAlbumi; ?>&idAlb=<?php echo $idAlbum; ?>"><?php echo $nazivAlbuma . " ($godinaIzdanja.)"; ?></a></li>
                <?php                
            }
            ?>
        </ul>    
    </div><!-- end .sredina -->         
    <?php
}//end adminSpisakAlbumaIzvodjacaStrimovi()

//********************************* Pozvana metoda u fajlu adminEditPanel.class.php u metodi leftSideAdmin()  *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Metoda za prikaz pjesama izabranog albuma *********************************//
function adminSpisakPjesama($idIzv, $idAlb)
{
    global $conn;
    $q= "SELECT * FROM albumi 
    JOIN pjesme ON albumi.idAlbum=pjesme.albumId 
    JOIN izvodjaci ON izvodjaci.idIzvodjaci=albumi.idIzvodjacAlbumi
    WHERE idIzvodjacAlbumi='{$idIzv}' AND albumId='{$idAlb}'";
    
    $select_tekst= mysqli_query($conn, $q);
    ?>
    <p class="sredina izvodjacAdmin"><b>1. Kliknite na naziv albuma da bi ste uredili sve pjesme odjednom</b></p>
    <p class="sredina izvodjacAdmin"><b>2. Da bi ste uredili SAMO jednu pjesmu, kliknite na naziv pjesme</b></p>
    <?php
    nazivAlbuma($idAlb);
    ?>
    <br>
    <div class="sredina">
        <ul class="">
            <?php
            while($row= mysqli_fetch_array($select_tekst))
            {
                $idPjesme= $row["idPjesme"];
                $redniBrojPjesme= $row["redniBroj"];
                $nazivPjesme= $row["nazivPjesme"];
                $feat= $row["feat"];
                $idAlbum= $row["idAlbum"];
                $idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
                $idIzvodjac2= $row["idIzvodjac2"];
                $idIzvodjac3= $row["idIzvodjac3"];
                $nazivAlbuma= $row["nazivAlbuma"];
                $godinaIzdanja= $row["godinaIzdanja"];
                $idIzvodjaci= $row["idIzvodjaci"];
                $izvodjacMaster= $row["izvodjacMaster"];
                $slikaAlbuma= $row["slikaAlbuma"];
                $izvodjacId= $row["izvodjacId"];

                $maybeFeat= ($feat==TRUE) ? "$feat" : ""
                ?>     
                <li class="text-left"><a class="text-decoration-none" href="updatetext.php?idIzv=<?php echo $idIzv; ?>&idAlb=<?php echo $idAlb; ?>&idPjs=<?php echo $idPjesme; ?>"><?php echo $redniBrojPjesme . ". " . "$nazivPjesme " . $maybeFeat; ?></p></a></li>     
                <?php 
            }//end while
            ?>
        </ul>
    </div><!-- end .sredina -->
    <?php
}//end adminSpisakPjesama()

/********************************* Pozvana metoda u ovom fajlu u funkciji adminIzabraniAlbumi i funkciji adminIzabraniSingleAlbum kao i fajlu artistEditPanel.class.php *********************************/

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Metoda kojom se vrši ažuriranje jedne izabrane pjesme  *********************************//
function adminUpdateSong($idIzv, $idAlb, $idPjs)
{
    include_once "../classes/insertData-classes/insertStreams.class.php";
    $newStream= new insertStreaming();
    global $conn;
    $q= "SELECT * FROM albumi JOIN pjesme ON albumi.idAlbum=pjesme.albumId 
    JOIN izvodjaci ON izvodjaci.idIzvodjaci=albumi.idIzvodjacAlbumi 
    WHERE idIzvodjacAlbumi='{$idIzv}' AND idPjesme='{$idPjs}'";

    $select_pjesmu= mysqli_query($conn, $q);

    while($row= mysqli_fetch_array($select_pjesmu))
    {
        $nazivAlbuma= $row["nazivAlbuma"];
        $slikaAlbuma= $row["slikaAlbuma"];
        $idIzv= $row["izvodjacId"];
        $idPjesme= $row["idPjesme"];
        $redniBrojPjesme= $row["redniBroj"];
        $nazivPjesme= $row["nazivPjesme"];
        $mixtapeIzvodjac= $row["mixtapeIzvodjac"];
        $tekstPjesme= $row["tekstPjesme"];
        $trajanjePjesme= $row["trajanjePjesme"];
        $feat= $row["feat"];
        $saradnici= $row["saradnici"];
        $ostaleNapomene= $row["ostaleNapomene"];
        $youtubeJednaPjesma= $row["youtubeJednaPjesma"];

        ?>
        <div class="slikeAlbumaPregled sredina">
            <div class="nazivAlbumPanel">
                <h6 class=""><strong>Naziv Albuma:</strong> <?php echo $nazivAlbuma; ?></h6>
                <img src="../images/albumi/<?php echo $slikaAlbuma; ?>" alt="<?php echo $nazivAlbuma; ?>" title="<?php echo $nazivAlbuma; ?>" class=""/>
            </div><!-- end .nazivAlbumPanel -->
            

            <form method="POST" action="" enctype="multipart/form-data" name="izmjenaPjesme" id="izmjenaPjesme">
                <h3 class="izvodjacAdmin"><?php echo $redniBrojPjesme . ". " . $nazivPjesme; ?></h3>
                <div class="form-group col-md-9 mx-auto mb-1">

                    <br>
                    <label for="redniBroj" class="text-warning"><strong>Ukoliko je više CD-ova napišite CD1- 1, CD2- 1 <br><span class="bg-danger text-light">&nbsp; U suprotnom ne dirajte &nbsp;</span></strong></label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Redni broj</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="hidden" name="pjesmaId_<?php echo $redniBrojPjesme; ?>; ?>" id="pjesmaId" value="<?php echo $redniBrojPjesme; ?>">
                        <input type="text" class="form-control" name="redniBroj" class="form-control form-control-sm" value="<?php echo $redniBrojPjesme; ?>">
                    </div><!-- end .input-group --><br>


                    <label for="nazivPjesme">Izmeniti naziv pjesme</label><br>
                    <div class="input-group">
                        <input type="hidden" name="pjesmaId" id="pjesmaId" value="<?php echo $idPjesme; ?>">
                        <input type="text"  class="form-control" name="nazivPjesme" class="form-control form-control-sm" value="<?php echo $nazivPjesme; ?>">
                    </div><!-- end .input-group-prepend --><br><br>

                    <label for="mixtapeIzvodjac" class="text-warning">Ukoliko je mixtape, navedite glavnog (prvog) izvođača ove pjesme</label><br>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Mixtape Izvođač</span>
                                </div><!-- end .input-group-prepend -->
                                <input type="hidden" name="pjesmaId" id="pjesmaId" value="<?php echo $idPjesme; ?>">
                                <input type="text" class="form-control" name="mixtapeIzvodjac" class="form-control form-control-sm" value="<?php echo $mixtapeIzvodjac; ?>">
                    </div><!-- end .input-group --><br>

                    <label for="feat" class="text-warning">Feat (gost na pjesmi)</label><br>
                    <label for="feat"class="text-light">(bez navodnika, zagrada ili bilo kojih drugih specijalnih karkatera, <br>kao na primjeru ispod)</label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">feat. / Feat. / Featuring</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="hidden" name="pjesmaId" id="pjesmaId" value="<?php echo $idPjesme; ?>">
                        <input type="text"  class="form-control" name="feat" class="form-control form-control-sm" value="<?php echo $feat; ?>" placeholder="npr. feat. Bvana, Gru, Kandžija">
                    </div><!-- end .input-group --><br><br>


                    <label for="saradnici" class="text-warning">Saradnici, producenti, muzičari,...</label><br>
                    <div class="input-group">
                        <textarea class="saradniciUpdate form-control" name="saradnici"><?php echo $saradnici; ?></textarea>
                    </div><!-- end .input-group-prepend --><br>

                    <!--<input type="time" step="any" name="trajanje" value="<?php echo $trajanjePjesme; ?>"><br><br>-->
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Trajanje pjesme</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text"  class="form-control" step="any" name="trajanje" value="<?php echo $trajanjePjesme; ?>" placeholder="00:00:00">
                    </div><!-- end .input-group --><br><br>

                    <label for="ostaleNapomene" class="text-warning">Ostale napomene za album (koje se ne odnose na određenu pjesmu, biće prikazano ispod liste pjesama)</label><br>
                    <div class="input-group">
                        <textarea class="saradniciUpdate form-control" name="ostaleNapomene"> <?php echo $ostaleNapomene ?></textarea>
                    </div><!-- end .input-group --><br><br>

                    <label for="tekstPjesme" class="text-warning">Tekst pjesme</label><br>
                    <div class="input-group">
                        <textarea class="dodajTekst" name="tekstPjesme"><?php echo $tekstPjesme; ?></textarea>
                    </div><!-- end .input-group --><br><br>

                    <label for="youtubeJednaPjesma" class="text-warning"><strong>Youtube Link</strong></label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://www.youtube.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="youtubeJednaPjesma" class="form-control form-control-sm text-danger" value="<?php echo $youtubeJednaPjesma; ?>">
                    </div><!-- end .input-group --><br><br>
                    
                    <input class="btn btn-primary mb-0" type="submit" name="posalji" value="Pošalji">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="btn btn-danger pt-1 mt-0" type="reset" value="Reset">
                </div><!-- end .form-group -->
            </form>
        </div><!-- .slikeAlbumaPregled sredina -->    
        <?php 
    }//end while

    if(isset($_POST["posalji"]))
    {
        $idPjesme = (int)$_POST["pjesmaId"];

        // 1) UZMI STARO STANJE (i naziv za log)
        $qOld = "SELECT redniBroj, nazivPjesme, mixtapeIzvodjac, feat, saradnici, trajanjePjesme, ostaleNapomene, tekstPjesme
                FROM pjesme
                WHERE idPjesme='{$idPjesme}'
                LIMIT 1";
        $rOld = mysqli_query($conn, $qOld);
        $old  = mysqli_fetch_assoc($rOld) ?: [];

        // ako se iz nekog razloga ne nađe pjesma
        if (empty($old)) {
            echo "Greška: pjesma nije pronađena.";
            return;
        }

        // 2) NOVO STANJE IZ FORME
        $redniBroj= trim(cleanText($_POST["redniBroj"] ?? ''));
        $nazivPjesme= trim(removeSimbols($_POST["nazivPjesme"] ?? ''));
        $mixtapeIzvodjac= trim(removeSimbols($_POST["mixtapeIzvodjac"] ?? ''));
        $feat= trim(removeSimbols($_POST["feat"] ?? ''));
        $saradnici= cleanText($_POST["saradnici"] ?? '');
        $trajanjePjesme= trim($_POST["trajanje"] ?? '');
        $ostaleNapomene= trim(removeSimbols($_POST["ostaleNapomene"] ?? ''));
        $tekstPjesme= cleanText($_POST["tekstPjesme"] ?? '');
        $youtubeJednaPjesma= $newStream->cleanStreamsYoutubeVideo($_POST["youtubeJednaPjesma"]);

        $feat2= ($feat==="") ? null : $feat;
        $saradnici2= ($saradnici==="") ? null : $saradnici;
        $trajanjePjesme2= ($trajanjePjesme==="") ? null : $trajanjePjesme;
        $ostaleNapomene2= ($ostaleNapomene==="") ? null : $ostaleNapomene;
        $tekstPjesme2= ($tekstPjesme==="") ? null : $tekstPjesme;
        $youtubeJednaPjesma2= ($youtubeJednaPjesma==="") ? null : $youtubeJednaPjesma;

        // 3) DIFF (šta se promijenilo)
        $new = [
            'redniBroj'   => $redniBroj,
            'nazivPjesme'   => $nazivPjesme,
            'mixtapeIzvodjac'   => $mixtapeIzvodjac,
            'feat'          => $feat2,
            'saradnici'     => $saradnici2,
            'trajanjePjesme'=> $trajanjePjesme2,
            'ostaleNapomene'=> $ostaleNapomene2,
            'tekstPjesme'   => $tekstPjesme2
        ];

        $changes = [];

        foreach ($new as $k => $v) {
            $oldVal = $old[$k] ?? null;

            if ($oldVal === '') $oldVal = null;
            if ($v === '') $v = null;

            if ($oldVal != $v) {

                // Tekst može biti ogroman -> ne logujemo sadržaj
                if ($k === 'tekstPjesme') {
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

        // 4) Ako nema promjena, nemoj ni update (opciono, ali korisno)
        if (empty($changes)) {
            echo "<h4 class='boja sredina'>Nema promjena za snimiti.</h4>";
            return;
        }

        // 5) UPDATE
        $q2= "UPDATE pjesme 
            SET redniBroj='{$redniBroj}', nazivPjesme='{$nazivPjesme}', mixtapeIzvodjac='{$mixtapeIzvodjac}', feat='{$feat2}', saradnici='{$saradnici2}', trajanjePjesme='{$trajanjePjesme2}', 
                ostaleNapomene='{$ostaleNapomene2}', tekstPjesme='{$tekstPjesme2}', youtubeJednaPjesma='{$youtubeJednaPjesma2}' 
            WHERE idPjesme='{$idPjesme}'";
        $update_pjesme= mysqli_query($conn, $q2);

        if($update_pjesme == TRUE)
        {
            // 6) LOG (staro/novo)
            logSongUpdated($idPjesme, $old['nazivPjesme'], $changes);

            echo "<meta http-equiv='refresh' content='1'; url='adminalbumi.php'>";
        }else{
            echo "Greška " . mysqli_error($conn). "<br>";
        }
    }//end if(posalji)

}//end adminUpdateSong()

//********************************* Pozvana metoda u updatetext.php  *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Metoda koja prikazuje izabrani album ili spisak pjesama izabranog albuma sa sa lijevog menija IZDAVAČI *********************************//
function adminIzabraniAlbum($idIzv, $idAlb)
{
    global $conn;
    $q1= "SELECT * FROM albumi WHERE idIzvodjacAlbumi='{$idIzv}' AND idAlbum='{$idAlb}'";
    
    $select_album= mysqli_query($conn, $q1);
    ?>
    <br>
    <div class="pregledAlbumaUredi">
        <div class="col-md-5">
            <div class="slikeAlbumaPregled sredina">
                <div class="editAlbum">         
                    <?php
                    while($row= mysqli_fetch_array($select_album))
                    {
                        $idAlbum= $row["idAlbum"];
                        $idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
                        $idIzvodjac2= $row["idIzvodjac2"];
                        $idIzvodjac3= $row["idIzvodjac3"];
                        $nazivAlbuma= $row["nazivAlbuma"];
                        $slikaAlbuma= $row["slikaAlbuma"];

                        ?>
                        <p class="sredina izvodjacAdmin">Kliknite na sliku da bi ste uredili informacije o albumu</p>
                        
                        <a href="adminupdatealbum.php?idIzv=<?php echo $idIzv; ?>&idAlb=<?php echo $idAlb; ?>">
                        <img src="../images/albumi/<?php echo $slikaAlbuma; ?>" alt="<?php echo $nazivAlbuma; ?>" title="<?php echo $nazivAlbuma; ?>"></a>    
                        <?php 
                    }//end while
                    ?>
                </div><!-- .editAlbum -->
            </div><!-- .slikeAlbumaPregled .sredina -->
        </div><!-- .col-md-5 -->
        
        <div class="col-md-5">
        <?php adminSpisakPjesama($idIzv, $idAlb); ?>
        </div><!-- .col-md-5 -->
    </div><!-- .pregledAlbumaUredi -->
    <?php
}//end adminIzabraniAlbum()
//********************************* Pozvana metoda u showalbum.php  *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Metoda koja prikazuje izabrani album ili spisak pjesama izabranog albuma sa lijevog menija ALBUMI za MODERATORE *********************************//
function adminIzabraniSingleAlbum($idAlb)
{
    global $conn;
    $q1= "SELECT * FROM albumi JOIN izvodjaci ON izvodjaci.idIzvodjaci=albumi.idIzvodjacAlbumi WHERE idAlbum='{$idAlb}'";
    $select_album= mysqli_query($conn, $q1);
    ?>
    <br>
    <div class="pregledAlbumaUredi">
        <div class="col-md-5">
            <div class="slikeAlbumaPregled sredina">
                <div class="editAlbum">
                    <?php
                    while($row= mysqli_fetch_array($select_album))
                    {
                        $idAlbum= $row["idAlbum"];
                        $idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
                        $idIzvodjac2= $row["idIzvodjac2"];
                        $idIzvodjac3= $row["idIzvodjac3"];
                        $nazivAlbuma= $row["nazivAlbuma"];
                        $godinaIzdanja= $row["godinaIzdanja"];
                        $slikaAlbuma= $row["slikaAlbuma"];
                        ?>
                        <p class="sredina izvodjacAdmin">Kliknite na sliku da bi ste uredili informacije o albumu</p>

                        <a href="adminupdatealbum.php?idIzv=<?php echo $idIzvodjacAlbumi; ?>&idAlb=<?php echo $idAlb; ?>">
                        <img src="../images/albumi/<?php echo $slikaAlbuma; ?>" alt="<?php echo $nazivAlbuma; ?>" title="<?php echo $nazivAlbuma; ?>"></a>
                        <?php 
                    }//end while
                    ?>
                </div><!-- .editAlbum -->
            </div><!-- .slikeAlbumaPregled .sredina -->
        </div><!-- .col-md-5 -->
        
        <div class="col-md-5">
        <?php
        adminSpisakPjesama($idIzvodjacAlbumi, $idAlb);
        ?>
        </div><!-- .col-md-5 -->
    </div><!-- .pregledAlbumaUredi -->
    <?php
}//end adminIzabraniSingleAlbum()

//********************************* Pozvana metoda u showsinglealbum.php  *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Funkcija za izmjenu podataka o albumu ADMIN, MODERATOR, IZVOĐAČ  *********************************//
function updateAboutAlbum($idAlb, $sesId)
{
    //-------------------------------------------------------------------
    include_once "../classes/insertData-classes/imageUploader.class.php";
    $uploader = new ImageUploader();
    //-------------------------------------------------------------------
    $imgWork= new adminWorkImages();
    global $conn;
    
    $q= "SELECT * FROM albumi 
    JOIN izvodjaci ON izvodjaci.idIzvodjaci=albumi.idIzvodjacAlbumi
    JOIN albumi_izdavaci ON albumi.idAlbum = albumi_izdavaci.idAlbum 
    JOIN izdavaci ON izdavaci.idIzdavaci = albumi_izdavaci.idIzdavaci
    WHERE albumi.idAlbum='{$idAlb}' GROUP BY albumi.idAlbum";
    //LIMIT 1 ili GROUP BY albumi.idAlbum --na kraju upita
    //označava da prikaže samo jedan rezultat ukoliko ima više izdavača, u suprotnom prikazuje onoliko koliko ima izdavača
    $select_tekst= mysqli_query($conn, $q);

    echo "";
    nazivAlbuma($idAlb);
    ?>
    <br>
    <div class="sredina">
        <?php
        while($row= mysqli_fetch_array($select_tekst))
        {
            $idAlbum= $row["idAlbum"];
            $idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
            $nazivAlbuma= $row["nazivAlbuma"];
            $godinaIzdanja= $row["godinaIzdanja"];
            $tacanDatumIzdanja= $row["tacanDatumIzdanja"];
            $slikaAlbuma= $row["slikaAlbuma"];
            $slikaAlbumaZadnja= $row["slikaAlbumaZadnja"];
            $brojPjesama= $row["brojPjesama"];
            $ostaleNapomeneAlbum= $row["ostaleNapomeneAlbum"];
            $idIzvodjaci= $row["idIzvodjaci"];
            $izvodjacMaster= $row["izvodjacMaster"];
            $dodaoAlbum= $row["dodaoAlbum"];

            $izdavaciNaziv= $row["izdavaciNaziv"];
            
            if(isset($_POST["promjeniSlikuAlbumaPrednja"]))
            {
                if(!empty($_FILES["promjeniSlikuAlbumaPrednja"]) && $_FILES["promjeniSlikuAlbumaPrednja"]["error"] === UPLOAD_ERR_OK)
                {
                    $res = $uploader->uploadAndUpdateImageField("promjeniSlikuAlbumaPrednja", "../images/albumi/", "album_front_update", (int)$idAlb, $conn,"albumi", /* tabela*/ "slikaAlbuma",  /*kolona slike*/ "idAlbum", /* id kolona*/ 80);  
                    echo "<meta http-equiv='refresh' content='0'; url='adminupadatealbum.php?idIzv=$idIzvodjacAlbumi&idAlb=$idAlb'>";
                }else if(empty($_FILES["promjeniSlikuAlbumaPrednja"])){
                    echo "Niste izabrali sliku.";
                }
            }//end master if()

            if(isset($_POST["obrisiPrednjuSliku"])){
                $imgWork->obrisiPrednjuSlikuAlbuma($idAlbum);
            }
            ?>
            <div id="" class="pregledAlbuma">
                <div class="col-md-3">
                    <div class="slikeAlbumaPregled sredina">
                        <div class="editAlbum">
                            <p class="text-warning"><strong>Prednja slika albuma (max 2mb</strong></p>
                            <div class="border">
                            <label class="text-light bg-secondary">Naziv <strong>prednje</strong> slike albuma</label><br>
                            <label class="text-light opisDodavanjaSlike">Izvođač - Ime Albuma (godina izdanja) - prednja/front</label><br>
                            </div><!-- end .border -->
                            <img src="../images/albumi/<?php echo $slikaAlbuma; ?>" alt="<?php echo $nazivAlbuma; ?>" title="<?php echo $nazivAlbuma; ?> (front)" class=""/>
                            <form action="" method="POST" enctype="multipart/form-data" name="promjeniSlikuAlbumaPrednja" id="promjenaSlikeAlbuma">
                                <input type="file" class="btn btn-light" name="promjeniSlikuAlbumaPrednja"><br><br>
                                <button type="submit" class="btn btn-primary" name="promjeniSlikuAlbumaPrednja" value="izmjeni">Izmjeni</button>
                                <button type="submit" class="btn btn-danger" name="obrisiPrednjuSliku" value="obrisiPrednjuSliku">Obriši</button><br><br>
                                <br><hr class="hrLinija">
                            </form>
                            <?php
                            if(isset($_POST["promjenaSlikeAlbumaZadnja"]))
                            {
                                if(!empty($_FILES["promjenaSlikeAlbumaZadnja"]) && $_FILES["promjenaSlikeAlbumaZadnja"]["error"] === UPLOAD_ERR_OK)
                                {
                                    $res = $uploader->uploadAndUpdateImageField("promjenaSlikeAlbumaZadnja", "../images/albumi/back", "album_back_update", (int)$idAlb, $conn,"albumi", /* tabela*/ "slikaAlbumaZadnja",  /*kolona slike*/ "idAlbum", /* id kolona*/ 80);       
                                    echo "<meta http-equiv='refresh' content='0'; url='adminupadatealbum.php?idIzv=$idIzvodjacAlbumi&idAlb=$idAlb'>";                             
                                }else if(empty($_FILES["promjenaSlikeAlbumaZadnja"])){
                                    echo "Niste izabrali sliku.";
                                }
                            }//end master if()

                            if(isset($_POST["obrisiZadnjuSliku"])){
                                $imgWork->obrisiZadnjuSlikuAlbuma($idAlbum);
                            }
                            ?>

                            <form action="" method="POST" enctype="multipart/form-data" name="promjeniSlikuAlbumaZadnja" id="promjenaSlikeAlbuma">
                                <p class="text-warning"><strong>Zadnja slika albuma (max 2mb)</strong></p>
                                <div class="border">
                                <label class="text-light bg-secondary">Naziv <strong>zadnje</strong> slike albuma</label><br>
                                <label class="text-light opisDodavanjaSlike">Izvođač - Ime Albuma (godina izdanja) - zadnja/back</label><br>
                                </div><!-- end .border -->
                                <img src="../images/albumi/back/<?php echo $slikaAlbumaZadnja; ?>" alt="<?php echo $nazivAlbuma; ?>" title="<?php echo $nazivAlbuma; ?> (back)" class=""/>
                                <input type="file" class="btn btn-light" name="promjenaSlikeAlbumaZadnja"><br><br>
                                <button type="submit" class="btn btn-primary" name="promjenaSlikeAlbumaZadnja" value="izmjeni">Izmjeni</button>
                                <button type="submit" class="btn btn-danger" name="obrisiZadnjuSliku">Obriši</button><br><br>
                                <br><hr class="hrLinija">
                            </form> 
            
                            <form action="" method="post" enctype="multipart/form-data" name="multipleImageUpload" id="multipleImageUpload">
                                <label for="files" class="text-warning"><strong>Izaberi ostale slike za upload <br> (moguće više fajlova odjednom)<br>(max 2mb po slici)</strong></label>
                                <input type="file" class="btn btn-light" name="images[]" id="files" multiple required/><br><br>
                                <button type="submit" class="btn btn-primary" name="promjeniSlikeAlbumaOstale" value="izmjeni">Izmjeni</button>
                            </form>
                            <?php 
                            if(isset($_POST["promjeniSlikeAlbumaOstale"]))
                            {
                                if(!empty($_FILES["images"]))
                                {
                                    //$imgWork->multipleImageUpload($nazivAlbuma, $idIzvodjaci, $idAlb);   
                                    $imgWork->insertAlbumImagesAdmin($idAlb);  
                                    echo "<meta http-equiv='refresh' content='0'; url='adminupadatealbum.php?idIzv=$idIzvodjacAlbumi&idAlb=$idAlb'>";
                                }
                                    
                            }else{
                                $imgWork->obrisiPoJednuSliku($idAlb);
                            }
                            ?>
                        </div><!-- end .editAlbum -->
                    </div><!-- end .slikeAlbumaPregled .sredina -->
                </div><!-- /.col-md-3 -->

                <script>
                    document.getElementById('buttonid').addEventListener('click', openDialog);
                    function openDialog() {
                    document.getElementById('promjenaSlikeAlbuma').click();
                    }
                </script>
            
                <div class="col-md-7 sredina3"> 
                    <form method="POST" action="" enctype="multipart/form-data" name="izmjenaAlbuma" id="izmjenaAlbuma">
                        <label for="izvodjac" class="text-warning"><strong>Izvođač</strong></label><br>
                        <input type="text" name="izvodjac" class="form-control form-control-sm text-danger" value="<?php echo $izvodjacMaster; ?>" readonly><br><br>
                        <label for="nazivAlbuma" class="text-warning"><strong>Album</strong></label><br>
                        <input type="text" name="nazivAlbuma" class="form-control form-control-sm text-danger" value="<?php echo $nazivAlbuma; ?>"><br><br>

                        <!-- Padajuća lista za izvođača 2 i 3 početak -->

                        <label for="izvodjac2" class="text-warning"><strong>Izvođač 2 (ukoliko ima još jedan izvođač na albumu)</strong></label><br>
                        <label for="izvodjac2" class="">Ukoliko nema izvođača u listi, idite u opciju <b>dodaj novog izvođača</b></label><br>
                        <label for="izvodjac2" class="">Ukoliko kliknete na izvođača jedina opcija da poništite je "refresh" stranice</label><br>
                        <select class="form-control" name="izvodjac2" id="izvodjac2">
                            <option value="">Izaberi drugog izvođača</option>
                            <?php 
                            $q= "SELECT * FROM izvodjaci";
                            $select_izvodjaci= mysqli_query($conn, $q);

                            while($red= mysqli_fetch_array($select_izvodjaci))
                            {
                                $idIzvodjaci= $red["idIzvodjaci"];
                                $izvodjacMaster= $red["izvodjacMaster"];

                                $q22= "SELECT * FROM albumi WHERE idAlbum='{$idAlb}'";
                                $select_izvodjaci2= mysqli_query($conn, $q22);
                                while($red2= mysqli_fetch_array($select_izvodjaci2))
                                {
                                    echo $idIzvodjac2= $red2["idIzvodjac2"];
                                    if($idIzvodjac2==$idIzvodjaci){
                                        ?>
                                        <option class="" value="<?php echo $idIzvodjac2; ?>" selected><a class="text-decoration-none" href="upadateartist.php?idIzv=<?php echo $idIzvodjaci; ?>"><?php echo $izvodjacMaster; ?></a></option>
                                        <?php
                                    }else{
                                    ?>
                                    <option class="" value="<?php echo $idIzvodjaci; ?>"><a class="text-decoration-none" href="upadateartist.php?idIzv=<?php echo $idIzvodjaci; ?>"><?php echo $izvodjacMaster; ?></a></option>
                                    <?php
                                    }//end if-else
                                }//end while 2
                            }//end while 1
                            ?>
                        </select> <br><br>
                        <label for="izvodjac3" class="text-warning"><strong>Izvođač 3 (ukoliko ima još jedan izvođač na albumu)</strong></label><br>
                        <label for="izvodjac3">Ukoliko nema izvođača u listi, idite u opciju <b>dodaj novog izvođača</b></label><br>
                        <label for="izvodjac3">Ukoliko kliknete na izvođača jedina opcija da poništite je "refresh" stranice</label><br>
                        <select class="form-control" name="izvodjac3" id="izvodjac3">
                            <option selected value="">Izaberi trećeg izvođača</option>
                                <?php 
                                $q= "SELECT * FROM izvodjaci";
                                $select_izvodjaci= mysqli_query($conn, $q);

                                while($red= mysqli_fetch_array($select_izvodjaci))
                                {
                                    $idIzvodjaci= $red["idIzvodjaci"];
                                    $izvodjacMaster= $red["izvodjacMaster"];

                                    $q33= "SELECT * FROM albumi WHERE idAlbum='{$idAlb}'";
                                    $select_izvodjaci3= mysqli_query($conn, $q33);
                                    while($red3= mysqli_fetch_array($select_izvodjaci3))
                                    {
                                    echo $idIzvodjac3= $red3["idIzvodjac3"];
                                    if($idIzvodjac3==$idIzvodjaci){
                                        ?>
                                        <option class="" value="<?php echo $idIzvodjac3; ?>" selected><a class="text-decoration-none" href="upadateartist.php?idIzv=<?php echo $idIzvodjaci; ?>"><?php echo $izvodjacMaster; ?></a></option>
                                        <?php
                                    }else{
                                        ?>
                                    <option class="" value="<?php echo $idIzvodjaci; ?>" ><a class="text-decoration-none" href="upadateartist.php?idIzv=<?php echo $idIzvodjaci; ?>"><?php echo $izvodjacMaster; ?></a></option>
                                    <?php
                                    }// end if-else
                                    }//end while 2
                                }//end while 1
                                ?>            
                        </select> <br><br>

                        <!-- Kraj padajuća liste za izvođača 2 i 3 -->

                        <label for="godinaIzdanja" class="text-warning"><strong>Godina izdanja</strong></label><br>
                        <label for="godinaIzdanja">Upisati samo godinu bez tačke na kraju</label><br>
                        <b><input type="text" name="godinaIzdanja" class="form-control form-control-sm text-dark" value="<?php echo $godinaIzdanja; ?>" placeholder=""></b><br><br>

                        <label for="tacanDatumIzdanja" class="text-warning"><strong>Tačan datum izdanja</strong></label><br>
                        <label for="tacanDatumIzdanja">Upisati u formatu 01.02.2023. (dd.mm.gggg.)</label><br>
                        <b><input type="date" name="tacanDatumIzdanja" class="form-control form-control-sm text-dark" value="<?php echo $tacanDatumIzdanja; ?>" placeholder=""></b><br><br>

                    <?php 
                    /*------------------------ Početak padajućeg menija izdavači ------------------------*/
                    ?>
                    <fieldset class="border p-5 rounded">
                        <h4 class="podebljano bg-dark text-warning sredina">&nbsp;Izdavači</h4>
                        <label for="izdavac" class="text-warning"><strong>Izdavač/Label</strong></label><br>

                        <?php
                        // Prvo uzmi sve izdavače koji su povezani s albumom
                        $qIzdavaciAlbuma = "SELECT idIzdavaci FROM albumi_izdavaci WHERE idAlbum = '{$idAlb}'";
                        $rez = mysqli_query($conn, $qIzdavaciAlbuma);

                        $izdavaciAlbuma = [];
                        while ($r = mysqli_fetch_assoc($rez)) {
                            $izdavaciAlbuma[] = $r['idIzdavaci'];
                        }

                        // Izvuci sve izdavače iz baze
                        $rezSviIzdavaci = mysqli_query($conn, "SELECT * FROM izdavaci ORDER BY izdavaciNaziv");
                        $sviIzdavaci = [];
                        while ($r = mysqli_fetch_assoc($rezSviIzdavaci)) {
                            $sviIzdavaci[] = $r;
                        }

                        for ($i = 1; $i <= 5; $i++) 
                        {
                            $naziv = "izdavac" . $i;
                            echo "<label for='$naziv'><strong>Izdavač $i</strong></label><br>";
                            echo "<select class='form-control izdavac-select' name='$naziv' id='$naziv'>";
                            echo "<option value=''>Izaberi izdavača</option>";

                            foreach ($sviIzdavaci as $izd) {
                                $selected = (isset($izdavaciAlbuma[$i - 1]) && $izd['idIzdavaci'] == $izdavaciAlbuma[$i - 1]) ? "selected" : "";
                                echo "<option value='{$izd['idIzdavaci']}' $selected>{$izd['izdavaciNaziv']}</option>";
                            }

                            echo "</select><br><br>";
                        }//end for()
                        ?>

                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const selects = document.querySelectorAll(".izdavac-select");

                                function validateUniqueSelection() {
                                    const selectedValues = [];
                                    let hasDuplicates = false;

                                    selects.forEach(select => {
                                        select.classList.remove("border-danger");
                                    });

                                    selects.forEach(select => {
                                        const value = select.value;
                                        if (value && selectedValues.includes(value)) {
                                            select.classList.add("border-danger");
                                            hasDuplicates = true;
                                        }
                                        selectedValues.push(value);
                                    });

                                    if (hasDuplicates) {
                                        alert("Ne možete izabrati istog izdavača više puta!");
                                    }
                                }

                                selects.forEach(select => {
                                    select.addEventListener("change", validateUniqueSelection);
                                });
                            });
                        </script>

                        <style>
                            .border-danger {
                                border: 2px solid red !important;
                            }
                        </style>
                    </fieldset><br>
                        <!-- Kraj padajuća liste za izdavača 2 i 3 -->

                <?php 
                /*------------------------ end padajućeg menija izdavači ------------------------*/



                /*------------------------ početak padajućeg menija kategorije ------------------------*/
                ?>

                <fieldset class="border p-5 rounded">
                    <h4 class="podebljano bg-dark text-warning sredina">Kategorije</h4>
                     <?php
                     // Prvo uzmi sve izdavače koji su povezani s albumom
                    $qKategorijeAlbuma = "SELECT idKategorijeAlbuma FROM albumi_kategorije WHERE idAlbum = '{$idAlb}'";
                    $rez = mysqli_query($conn, $qKategorijeAlbuma);

                   $kategorijeAlbuma = [];
                    while ($r = mysqli_fetch_assoc($rez)) {
                       $kategorijeAlbuma[] = $r['idKategorijeAlbuma'];
                    }

                        $q = "SELECT * FROM kategorije_albuma";
                        $select_kategorije = mysqli_query($conn, $q);

                        $kategorije = [];
                        while ($red = mysqli_fetch_assoc($select_kategorije)) {
                            $kategorije[] = $red; // čuvamo rezultate u niz
                        }

                        for ($i = 1; $i <= 3; $i++) 
                        {
                            $naziv = "kategorija" . $i;
                            echo "<label for='$naziv'><strong>Kategorija $i</strong></label><br>";
                            echo "<select class='form-control kategorija-select' name='$naziv' id='$naziv'>";
                            echo "<option value=''>Izaberi kategoriju</option>";

                            foreach ($kategorije as $kat) {
                                $selected = (isset($kategorijeAlbuma[$i - 1]) && $kat['idKategorijeAlbuma'] == $kategorijeAlbuma[$i - 1]) ? "selected" : "";
                                echo "<option value='{$kat['idKategorijeAlbuma']}' $selected>{$kat['nazivKategorijeAlbuma']}</option>";
                            }

                            echo "</select><br><br>";
                        }//end for()
                        ?>

                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const selects = document.querySelectorAll(".kategorija-select");

                                function validateUniqueSelection() {
                                    const selectedValues = [];
                                    let hasDuplicates = false;

                                    selects.forEach(select => {
                                        select.classList.remove("border-danger");
                                    });

                                    selects.forEach(select => {
                                        const value = select.value;
                                        if (value && selectedValues.includes(value)) {
                                            select.classList.add("border-danger");
                                            hasDuplicates = true;
                                        }
                                        selectedValues.push(value);
                                    });

                                    if (hasDuplicates) {
                                        alert("Ne možete izabrati istu kategoriju više puta!");
                                    }
                                }

                                selects.forEach(select => {
                                    select.addEventListener("change", validateUniqueSelection);
                                });
                            });
                        </script>

                        <style>
                            .border-danger {
                                border: 2px solid red !important;
                            }
                        </style>


                </fieldset><br>

                <?php 
                /*------------------------ end padajućeg menija kategorije ------------------------*/
                ?>





                        <label for="brojPjesama" class="text-warning"><strong>Broj pjesama na albumu</strong></label><br>
                        <label for="brojPjesama">Ukoliko je više CD-ova navedite ukupno pjesama</label><br>
                        <input type="text" name="brojPjesama" class="form-control form-control-sm" value="<?php echo $brojPjesama; ?>" placeholder="npr. Bassivity Music, Menart, Samoizdanje"><br><br>

                        <label for="ostaleNapomeneAlbum" class="text-warning"><strong>Ostale napomene bitne za album</strong></label><br>
                        <textarea class="dodajTekstNapomene" name="ostaleNapomeneAlbum"><?php echo $ostaleNapomeneAlbum; ?></textarea><br><br>
                        <button class="btn btn-primary mb-0" type="submit" name="posalji" >Pošalji</button>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input class="btn btn-warning mt-0" type="reset" value="Reset">
                    </form>
                </div><!-- .col-md-7 .sredina -->
            </div><!-- end .pregledAlbuma -->
            <?php 
        }//end while

        if(isset($_POST["posalji"]))
        {
            // STARI PODACI (za diff log) - uzmi prije UPDATE-a
            $qOld = "SELECT nazivAlbuma, idIzvodjac2, idIzvodjac3, godinaIzdanja, tacanDatumIzdanja, brojPjesama, ostaleNapomeneAlbum
                    FROM albumi
                    WHERE idAlbum='{$idAlb}'
                    LIMIT 1";
            $rOld = mysqli_query($conn, $qOld);
            $old  = mysqli_fetch_assoc($rOld) ?: [];


            $nazivAlbuma= $_POST["nazivAlbuma"];
            $izvodjac2= $_POST["izvodjac2"];
            $izvodjac3= $_POST["izvodjac3"];
            $godinaIzdanja= trim(removeSimbols($_POST["godinaIzdanja"]));
            $tacanDatumIzdanja= trim(removeSimbols($_POST["tacanDatumIzdanja"]));
            //$izdavac= cleanText(trim(removeSimbols($_POST["izdavac"])));
            $ostaleNapomeneAlbum= trim(cleanText($_POST["ostaleNapomeneAlbum"]));
            $brojPjesama= trim($_POST["brojPjesama"]);

            if (!empty($brojPjesama)) 
            {
                if (!empty($izvodjac2) && !empty($izvodjac3)) {
                    $q2 = "UPDATE albumi SET nazivAlbuma='{$nazivAlbuma}', idIzvodjac2='{$izvodjac2}', idIzvodjac3='{$izvodjac3}', godinaIzdanja='{$godinaIzdanja}', tacanDatumIzdanja='{$tacanDatumIzdanja}', brojPjesama='{$brojPjesama}', ostaleNapomeneAlbum='{$ostaleNapomeneAlbum}' WHERE idAlbum='{$idAlb}'";
                } else if (empty($izvodjac2)) {
                    $q2 = "UPDATE albumi SET nazivAlbuma='{$nazivAlbuma}', idIzvodjac2=NULL, idIzvodjac3=NULL, godinaIzdanja='{$godinaIzdanja}', tacanDatumIzdanja='{$tacanDatumIzdanja}', brojPjesama='{$brojPjesama}', ostaleNapomeneAlbum='{$ostaleNapomeneAlbum}' WHERE idAlbum='{$idAlb}'";
                } else if (empty($izvodjac3)) {
                    $q2 = "UPDATE albumi SET nazivAlbuma='{$nazivAlbuma}', idIzvodjac2='{$izvodjac2}', idIzvodjac3=NULL, godinaIzdanja='{$godinaIzdanja}', tacanDatumIzdanja='{$tacanDatumIzdanja}', brojPjesama='{$brojPjesama}', ostaleNapomeneAlbum='{$ostaleNapomeneAlbum}' WHERE idAlbum='{$idAlb}'";
                }

                $update_album = mysqli_query($conn, $q2);

                if ($update_album == TRUE) 
                {
                    $new = [
                        'nazivAlbuma'         => $nazivAlbuma,
                        'idIzvodjac2'         => ($izvodjac2 === '' ? null : $izvodjac2),
                        'idIzvodjac3'         => ($izvodjac3 === '' ? null : $izvodjac3),
                        'godinaIzdanja'       => $godinaIzdanja,
                        'tacanDatumIzdanja'   => $tacanDatumIzdanja,
                        'brojPjesama'         => $brojPjesama,
                        'ostaleNapomeneAlbum' => $ostaleNapomeneAlbum
                    ];


                        // Snima u log ako postoje promjene
                        $changes = [];

                        foreach ($new as $k => $v) {
                            if (($old[$k] ?? null) != $v) {
                                $changes[$k] = [
                                    'old' => $old[$k] ?? null,
                                    'new' => $v
                                ];
                            }
                        }

                    logAlbumUpdated($idAlbum, $nazivAlbuma, $changes);

                    // Prvo obriši sve postojeće izdavače za taj album
                    mysqli_query($conn, "DELETE FROM albumi_izdavaci WHERE idAlbum='{$idAlb}'");

                    // Uzmi izdavače iz forme
                    $izdavaci = [];
                    if (!empty($_POST['izdavac1'])) $izdavaci[] = $_POST['izdavac1'];
                    if (!empty($_POST['izdavac2'])) $izdavaci[] = $_POST['izdavac2'];
                    if (!empty($_POST['izdavac3'])) $izdavaci[] = $_POST['izdavac3'];
                    if (!empty($_POST['izdavac4'])) $izdavaci[] = $_POST['izdavac4'];
                    if (!empty($_POST['izdavac5'])) $izdavaci[] = $_POST['izdavac5'];

                    // Unesi ih u poveznu tabelu
                    foreach ($izdavaci as $idIzdavac) {
                        $idIzdavac = mysqli_real_escape_string($conn, $idIzdavac);
                        mysqli_query($conn, "INSERT INTO albumi_izdavaci (idAlbum, idIzdavaci) VALUES ('{$idAlb}', '{$idIzdavac}')");
                    }

                    // Prvo obriši sve postojeće izdavače za taj album
                    mysqli_query($conn, "DELETE FROM albumi_kategorije WHERE idAlbum='{$idAlb}'");

                    // Uzmi kategorije iz forme
                    $kategorije = [];
                    if (!empty($_POST['kategorija1'])) $kategorije[] = $_POST['kategorija1'];
                    if (!empty($_POST['kategorija2'])) $kategorije[] = $_POST['kategorija2'];
                    if (!empty($_POST['kategorija3'])) $kategorije[] = $_POST['kategorija3'];

                    // Unesi ih u poveznu tabelu kategorije_albuma
                    foreach ($kategorije as $idKategorijeAlbuma) {
                        $idKategorijeAlbuma = mysqli_real_escape_string($conn, $idKategorijeAlbuma);
                        $q_kategorija= "INSERT INTO albumi_kategorije (idAlbum, idKategorijeAlbuma) VALUES ('{$idAlb}', '{$idKategorijeAlbuma}')";
                        mysqli_query($conn, $q_kategorija);
                        //print_r($q_kategorija);
                    }

                    //echo "<meta http-equiv='refresh' content='0; url=adminalbumi.php'>";
                    echo "<meta http-equiv='refresh' content='0'; url='adminalbumi.php'>";
                }else {
                    echo "Greška " . mysqli_error($conn) . "<br>";
                }//end if else ($update_album)
            }//end if(!empty($brojPjesama))
        }//end if master
        ?>
    </div><!-- end .sredina -->
    <?php
}//end updateAboutAlbum()

//********************************* Pozvana metoda u fajlu adminupdatealbum.php  *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Funkcija za izmjenu podataka o albumu za IZDAVAČ/LABEL *********************************//
function updateAboutAlbumLabel($idAlb, $sesId)
{
    global $conn;
    $imgWork= new adminWorkImages();
    $q= "SELECT * FROM albumi 
    JOIN izvodjaci ON izvodjaci.idIzvodjaci=albumi.idIzvodjacAlbumi
    JOIN albumi_izdavaci ON albumi_izdavaci.idAlbum=albumi.idAlbum 
    JOIN izdavaci ON izdavaci.idIzdavaci=albumi_izdavaci.idIzdavaci
    WHERE albumi.idAlbum='{$idAlb}'";
    $select_tekst= mysqli_query($conn, $q);

    echo "";
    nazivAlbuma($idAlb);
    ?>
    <br>
    <div class="sredina">
        <?php
        while($row= mysqli_fetch_array($select_tekst))
        {
            $idAlbum= $row["idAlbum"];
            $idizvodjacAlbumi= $row["idIzvodjacAlbumi"];
            $nazivAlbuma= $row["nazivAlbuma"];
            $godinaIzdanja= $row["godinaIzdanja"];
            $idIzdavaci= $row["idIzdavaci"];
            $tacanDatumIzdanja= $row["tacanDatumIzdanja"];
            $slikaAlbuma= $row["slikaAlbuma"];
            $slikaAlbumaZadnja= $row["slikaAlbumaZadnja"];
            $brojPjesama= $row["brojPjesama"];
            $ostaleNapomeneAlbum= $row["ostaleNapomeneAlbum"];
            $idIzvodjaci= $row["idIzvodjaci"];
            $izvodjacMaster= $row["izvodjacMaster"];
            $dodaoAlbum= $row["dodaoAlbum"];

            $izdavaciNaziv= $row["izdavaciNaziv"];
            
            if(isset($_POST["promjeniSlikuAlbumaPrednja"]))
            {
                if(!empty($_FILES["promjeniSlikuAlbumaPrednja"]))
                {
                    $slikaAlbumaPrednja= $_FILES["promjeniSlikuAlbumaPrednja"]["name"];
                    $imgWork->promjenaPrednjeSlikeAlbuma($slikaAlbumaPrednja, $idAlbum);
                }else if(empty($_FILES["promjeniSlikuAlbumaPrednja"])){
                    echo "Niste izabrali sliku.";
                }
            }//end master if()

            if(isset($_POST["obrisiPrednjuSliku"])){
                $imgWork->obrisiPrednjuSlikuAlbuma($idAlbum);
            }
            ?>
            <div id="" class="pregledAlbuma">
                <div class="col-md-3">
                    <div class="slikeAlbumaPregled sredina">
                        <div class="editAlbum">
                            <p>Prednja slika albuma</p>
                            <img src="../images/albumi/<?php echo $slikaAlbuma; ?>" alt="<?php echo $nazivAlbuma; ?>" title="<?php echo $nazivAlbuma; ?> (front)" class=""/>
                            <form action="" method="POST" enctype="multipart/form-data" name="promjeniSlikuAlbumaPrednja" id="promjenaSlikeAlbuma">
                                <input type="file" class="btn btn-light" name="promjeniSlikuAlbumaPrednja"><br><br>
                                <button type="submit" class="btn btn-primary" name="promjeniSlikuAlbumaPrednja" value="izmjeni">Izmjeni</button>
                                <button type="submit" class="btn btn-danger" name="obrisiPrednjuSliku" value="obrisiPrednjuSliku">Obriši</button><br><br>
                                <br><hr class="hrLinija">
                            </form>
                            <?php
                            if(isset($_POST["promjenaSlikeAlbumaZadnja"]))
                            {
                                if(!empty($_FILES["promjenaSlikeAlbumaZadnja"]))
                                {
                                    $slikaAlbumaZadnja= $_FILES["promjenaSlikeAlbumaZadnja"]["name"];

                                    $imgWork->adminPromjenaZadnjeSlikeAlbuma($slikaAlbumaZadnja, $idAlb);
                                    
                                }else if(empty($_FILES["promjenaSlikeAlbumaZadnja"])){
                                    echo "Niste izabrali sliku.";
                                }
                            }//end master if()

                            if(isset($_POST["obrisiZadnjuSliku"])){
                                $imgWork->obrisiZadnjuSlikuAlbuma($idAlbum);
                            }
                            ?>
                            <form action="" method="POST" enctype="multipart/form-data" name="promjeniSlikuAlbumaZadnja" id="promjenaSlikeAlbuma">
                                <p class="text-warning"><strong>Zadnja slika albuma</strong></p>
                                <img src="../images/albumi/back/<?php echo $slikaAlbumaZadnja; ?>" alt="<?php echo $nazivAlbuma; ?>" title="<?php echo $nazivAlbuma; ?> (back)" class=""/>
                                <input type="file" class="btn btn-light" name="promjenaSlikeAlbumaZadnja"><br><br>
                                <button type="submit" class="btn btn-primary" name="promjenaSlikeAlbumaZadnja" value="izmjeni">Izmjeni</button>
                                <button type="submit" class="btn btn-danger" name="obrisiZadnjuSliku">Obriši</button><br><br>
                                <br><hr class="hrLinija">
                            </form> 
            
                            <form action="" method="post" enctype="multipart/form-data" name="multipleImageUpload" id="multipleImageUpload">
                                <label for="files" class="text-warning"><strong>Izaberi ostale slike za upload <br> (moguće više fajlova odjednom)</strong></label>
                                <input type="file" class="btn btn-light" name="images[]" id="files" multiple required/><br><br>
                                <button type="submit" class="btn btn-primary" name="promjeniSlikeAlbumaOstale" value="izmjeni">Izmjeni</button>
                            </form>
                            <?php 
                            if(isset($_POST["promjeniSlikeAlbumaOstale"]))
                            {
                                if(!empty($_FILES["images"]))
                                {
                                    $imgWork->multipleImageUpload($nazivAlbuma, $idIzvodjaci, $idAlb);   
                                }
                                    
                            }else{
                                $imgWork->obrisiPoJednuSliku($idAlb);
                            }//end if else (isset($_POST["promjeniSlikeAlbumaOstale"]))
                            ?>
                        </div><!-- end .editAlbum -->
                    </div><!-- end .slikeAlbumaPregled .sredina -->
                </div><!-- end .col-md-3 -->

                <script>
                    document.getElementById('buttonid').addEventListener('click', openDialog);
                    function openDialog() {
                    document.getElementById('promjenaSlikeAlbuma').click();
                    }
                </script>
            
                <div class="col-md-7 sredina"> 
                    <form method="POST" action="" enctype="multipart/form-data" name="izmjenaAlbuma" id="izmjenaAlbuma">
                        <label for="izvodjac" class="text-warning"><strong>Izvođač</strong></label><br>
                        <input type="text" name="izvodjac" class="form-control form-control-sm text-danger" value="<?php echo $izvodjacMaster; ?>" readonly><br><br>
                        <label for="nazivAlbuma" class="text-warning"><strong>Album</strong></label><br>
                        <input type="text" name="nazivAlbuma" class="form-control form-control-sm text-danger" value="<?php echo $nazivAlbuma; ?>"><br><br>

                        <!-- Padajuća lista za izvođača 2 i 3 početak -->

                        <label for="izvodjac2" class="text-warning"><strong>Izvođač 2 (ukoliko ima još jedan izvođač na albumu)</strong></label><br>
                        <label for="izvodjac2" class="">Ukoliko nema izvođača u listi, idite u opciju <b>dodaj novog izvođača</b></label><br>
                        <label for="izvodjac2" class="">Ukoliko kliknete na izvođača jedina opcija da poništite je "refresh" stranice</label><br>
                        <select class="form-control" name="izvodjac2" id="izvodjac2">
                            <option value="">Izaberi drugog izvođača</option>
                            <?php 
                            $q= "SELECT * FROM izvodjaci";
                            $select_izvodjaci= mysqli_query($conn, $q);

                            while($red= mysqli_fetch_array($select_izvodjaci))
                            {
                                $idIzvodjaci= $red["idIzvodjaci"];
                                $izvodjacMaster= $red["izvodjacMaster"];

                                $q22= "SELECT * FROM albumi WHERE idAlbum='{$idAlb}'";
                                $select_izvodjaci2= mysqli_query($conn, $q22);
                                while($red2= mysqli_fetch_array($select_izvodjaci2))
                                {
                                    echo $idIzvodjac2= $red2["idIzvodjac2"];
                                    if($idIzvodjac2==$idIzvodjaci){
                                        ?>
                                        <option class="" value="<?php echo $idIzvodjac2; ?>" selected><a class="text-decoration-none" href="upadateartist.php?idIzv=<?php echo $idIzvodjaci; ?>"><?php echo $izvodjacMaster; ?></a></option>
                                        <?php
                                    }else{
                                    ?>
                                    <option class="" value="<?php echo $idIzvodjaci; ?>"><a class="text-decoration-none" href="upadateartist.php?idIzv=<?php echo $idIzvodjaci; ?>"><?php echo $izvodjacMaster; ?></a></option>
                                    <?php
                                    }//end if-else
                                }//end while 2
                            }//end while 1
                            ?>
                        </select> <br><br>
                        <label for="izvodjac3" class="text-warning"><strong>Izvođač 3 (ukoliko ima još jedan izvođač na albumu)</strong></label><br>
                        <label for="izvodjac3">Ukoliko nema izvođača u listi, idite u opciju <b>dodaj novog izvođača</b></label><br>
                        <label for="izvodjac3">Ukoliko kliknete na izvođača jedina opcija da poništite je "refresh" stranice</label><br>
                        <select class="form-control" name="izvodjac3" id="izvodjac3">
                            <option selected value="">Izaberi trećeg izvođača</option>
                                <?php 
                                $q= "SELECT * FROM izvodjaci";
                                $select_izvodjaci= mysqli_query($conn, $q);

                                while($red= mysqli_fetch_array($select_izvodjaci))
                                {
                                    $idIzvodjaci= $red["idIzvodjaci"];
                                    $izvodjacMaster= $red["izvodjacMaster"];

                                    $q33= "SELECT * FROM albumi WHERE idAlbum='{$idAlb}'";
                                    $select_izvodjaci3= mysqli_query($conn, $q33);
                                    while($red3= mysqli_fetch_array($select_izvodjaci3))
                                    {
                                    echo $idIzvodjac3= $red3["idIzvodjac3"];
                                    if($idIzvodjac3==$idIzvodjaci){
                                        ?>
                                        <option class="" value="<?php echo $idIzvodjac3; ?>" selected><a class="text-decoration-none" href="upadateartist.php?idIzv=<?php echo $idIzvodjaci; ?>"><?php echo $izvodjacMaster; ?></a></option>
                                        <?php
                                    }else{
                                        ?>
                                    <option class="" value="<?php echo $idIzvodjaci; ?>" ><a class="text-decoration-none" href="upadateartist.php?idIzv=<?php echo $idIzvodjaci; ?>"><?php echo $izvodjacMaster; ?></a></option>
                                    <?php
                                    }// end if-else
                                    }//end while 2
                                }//end while 1
                                ?>            
                        </select> <br><br>

                        <!-- Kraj padajuća liste za izvođača 2 i 3 -->

                        <label for="godinaIzdanja" class="text-warning"><strong>Godina izdanja</strong></label><br>
                        <label for="godinaIzdanja">Upisati samo godinu bez tačke na kraju</label><br>
                        <b><input type="text" name="godinaIzdanja" class="form-control form-control-sm text-dark" value="<?php echo $godinaIzdanja; ?>" placeholder=""></b><br><br>

                        <label for="tacanDatumIzdanja" class="text-warning"><strong>Tačan datum izdanja (ukoliko je poznat)</strong></label><br>
                        <label for="tacanDatumIzdanja">Upisati u formatu 01.02.2023. (dd.mm.gggg.)</label><br>
                        <b><input type="text" name="tacanDatumIzdanja" class="form-control form-control-sm text-dark" value="<?php echo $tacanDatumIzdanja; ?>" placeholder=""></b><br><br>

                        <label for="brojPjesama" class="text-warning"><strong>Broj pjesama na albumu</strong></label><br>
                        <label for="brojPjesama">Ukoliko je više CD-ova navedite ukupno pjesama</label><br>
                        <input type="text" name="brojPjesama" class="form-control form-control-sm" value="<?php echo $brojPjesama; ?>" placeholder="npr. Bassivity Music, Menart, Samoizdanje"><br><br>

                        <label for="ostaleNapomeneAlbum" class="text-warning"><strong>Ostale napomene bitne za album</strong></label><br>
                        <textarea class="dodajTekst" name="ostaleNapomeneAlbum"><?php echo $ostaleNapomeneAlbum; ?></textarea><br><br>
                        <button class="btn btn-primary mb-0" type="submit" name="posalji" >Pošalji</button>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input class="btn btn-warning mt-0" type="reset" value="Reset">
                    </form>
                </div><!-- end .col-md-7 .sredina -->
            </div><!-- end .pregledAlbuma -->
            <?php 
        }//end while

        if(isset($_POST["posalji"]))
        {
            // STARI PODACI (za diff log) - uzmi prije UPDATE-a
            $qOld = "SELECT nazivAlbuma, idIzvodjac2, idIzvodjac3, godinaIzdanja, tacanDatumIzdanja, brojPjesama, ostaleNapomeneAlbum
                    FROM albumi
                    WHERE idAlbum='{$idAlb}'
                    LIMIT 1";
            $rOld = mysqli_query($conn, $qOld);
            $old  = mysqli_fetch_assoc($rOld) ?: [];

            $nazivAlbuma= $_POST["nazivAlbuma"];
            $izvodjac2= $_POST["izvodjac2"];
            $izvodjac3= $_POST["izvodjac3"];
            $godinaIzdanja= trim(removeSimbols($_POST["godinaIzdanja"]));
            $tacanDatumIzdanja= trim(removeSimbols($_POST["tacanDatumIzdanja"]));
            $ostaleNapomeneAlbum= trim(cleanText($_POST["ostaleNapomeneAlbum"]));
            $brojPjesama= trim($_POST["brojPjesama"]);

            if(!empty($brojPjesama))
            {
                if(!empty($izvodjac2) && !empty($izvodjac3)){
                    $q2= "UPDATE albumi SET nazivAlbuma='{$nazivAlbuma}', idIzvodjac2='{$izvodjac2}', idIzvodjac3='{$izvodjac3}', godinaIzdanja='{$godinaIzdanja}', tacanDatumIzdanja='{$tacanDatumIzdanja}', brojPjesama='{$brojPjesama}', ostaleNapomeneAlbum='{$ostaleNapomeneAlbum}' WHERE idAlbum='{$idAlb}'";
                    $update_album= mysqli_query($conn, $q2);
                    //print_r($q2);
                }else if(empty($izvodjac2)){
                        
                    $q2= "UPDATE albumi SET nazivAlbuma='{$nazivAlbuma}', idIzvodjac2=NULL, idIzvodjac3=NULL, godinaIzdanja='{$godinaIzdanja}', tacanDatumIzdanja='{$tacanDatumIzdanja}', brojPjesama='{$brojPjesama}', ostaleNapomeneAlbum='{$ostaleNapomeneAlbum}' WHERE idAlbum='{$idAlb}'";
                    $update_album= mysqli_query($conn, $q2);
                    //print_r($q2);
                }else if(empty($izvodjac3)){

                    $q2= "UPDATE albumi SET nazivAlbuma='{$nazivAlbuma}', idIzvodjac2='{$izvodjac2}', idIzvodjac3=NULL, godinaIzdanja='{$godinaIzdanja}', tacanDatumIzdanja='{$tacanDatumIzdanja}', brojPjesama='{$brojPjesama}', ostaleNapomeneAlbum='{$ostaleNapomeneAlbum}' WHERE idAlbum='{$idAlb}'";
                    $update_album= mysqli_query($conn, $q2);
                    //print_r($q2);
                }

                if($update_album == TRUE)
                {
                    $new = [
                        'nazivAlbuma'         => $nazivAlbuma,
                        'idIzvodjac2'         => ($izvodjac2 === '' ? null : $izvodjac2),
                        'idIzvodjac3'         => ($izvodjac3 === '' ? null : $izvodjac3),
                        'godinaIzdanja'       => $godinaIzdanja,
                        'tacanDatumIzdanja'   => $tacanDatumIzdanja,
                        'brojPjesama'         => $brojPjesama,
                        'ostaleNapomeneAlbum' => $ostaleNapomeneAlbum
                    ];


                        // Snima u log ako postoje promjene
                        $changes = [];

                        foreach ($new as $k => $v) {
                            if (($old[$k] ?? null) != $v) {
                                $changes[$k] = [
                                    'old' => $old[$k] ?? null,
                                    'new' => $v
                                ];
                            }
                        }

                    logAlbumUpdated($idAlbum, $nazivAlbuma, $changes);

                    echo "<meta http-equiv='refresh' content='0'; url='adminalbumi.php'>";
                }else{
                    echo "Greška " . mysqli_error($conn). "<br>";
                }
            }//end if(!empty($brojPjesama))
        }//end if(posalji)
        ?>
    </div><!-- end .sredina -->
    <?php
}//end updateAboutAlbumLabel()

//********************************* Pozvana metoda u fajlu adminupdatealbum.php  *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Izmjena svih pjesama na albumu odjednom u jednoj formi  *********************************//
function updateWholeAlbum($idIzv, $idAlb)
{
    include_once "../classes/insertData-classes/insertStreams.class.php";
    $newStream= new insertStreaming();
	global $conn;
	$q= "SELECT * FROM albumi 
    JOIN izvodjaci ON izvodjaci.idIzvodjaci = albumi.idIzvodjacAlbumi
    JOIN pjesme ON albumi.idAlbum=pjesme.albumId WHERE albumId='{$idAlb}'";
	$select_tekst= mysqli_query($conn, $q);
	?>
    <div class="slikeAlbumaPregled sredina">
        <form method="POST" action="" enctype="multipart/form-data" name="updatePjesme" id="updatePjesme">
            <?php
            while($row= mysqli_fetch_array($select_tekst))
            {
                $idPjesme= $row["idPjesme"];
                $redniBroj= $row["redniBroj"];
                $nazivPjesme= $row["nazivPjesme"];
                $feat= $row["feat"];
                $saradnici= $row["saradnici"];
                $tekstPjesme= $row["tekstPjesme"];
                $albumId= $row["albumId"]; //Id albuma iz tabele pjesme
                $trajanjePjesme= $row["trajanjePjesme"];
                $ostaleNapomene= $row["ostaleNapomene"];
                $izvodjacMaster= $row["izvodjacMaster"];
                $mixtapeIzvodjac= $row["mixtapeIzvodjac"];
                $youtubeJednaPjesma= $row["youtubeJednaPjesma"];

                $nazivIzvodjac= ($mixtapeIzvodjac==null) ? $izvodjacMaster : $mixtapeIzvodjac;

                ?>
                <div class="form-group col-md-9 mx-auto mb-1">
                    <h3 class="izvodjacAdmin"><?php echo $redniBroj . ". $nazivIzvodjac - " . $nazivPjesme; ?></h3><br>

                    <br>
                    <label for="redniBroj" class="text-warning"><strong>Ukoliko je više CD-ova napišite CD1- 1, CD2- 1 <br><span class="bg-danger text-light">&nbsp; U suprotnom ne dirajte &nbsp;</span></strong></label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Redni broj</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="hidden" name="pjesmaId_<?php echo $redniBroj; ?>; ?>" id="pjesmaId" value="<?php echo $redniBroj; ?>">
                        <input type="text" class="form-control" name="redniBroj[<?php echo $idPjesme; ?>]" class="form-control form-control-sm" value="<?php echo $redniBroj; ?>">
                    </div><!-- end .input-group --><br>

                    <fieldset class="border p-5 rounded">
                    <label for="nazivPjesme" class="text-warning"><strong>Naziv pjesme <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong></label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Naziv pjesme</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="hidden" name="pjesmaId_<?php echo $idPjesme; ?>; ?>" id="pjesmaId" value="<?php echo $idPjesme; ?>">
                        <input type="text" class="form-control" name="nazivPjesme[<?php echo $idPjesme; ?>]" class="form-control form-control-sm" value="<?php echo $nazivPjesme; ?>">
                    </div><!-- end .input-group -->
                    </fieldset><br>

                    <label for="mixtapeIzvodjac" class="text-warning">Ukoliko je mixtape, navedite glavnog (prvog) izvođača ove pjesme</label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Mixtape Izvođač</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="hidden" name="pjesmaId" id="pjesmaId" value="">
                        <input type="text" class="form-control" name="mixtapeIzvodjac[<?php echo $idPjesme; ?>]" class="form-control form-control-sm" value="<?php echo $mixtapeIzvodjac; ?>">
                    </div><!-- end .input-group --><br>
                    
                    <label for="feat" class="text-warning">Feat (gost na pjesmi)</label><br>
                    <label for="feat"class="text-light">(bez navodnika, zagrada ili bilo kojih drugih specijalnih karkatera, <br>kao na primjeru ispod)</label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">feat. / Feat. / Featuring</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="hidden" name="id[]" id="pjesma" value="<?php echo $idPjesme; ?>">
                        <input type="text" class="form-control" name="feat[<?php echo $idPjesme; ?>]" value="<?php echo $feat; ?>">
                    </div><!-- end .input-group --><br><br>
                    
                    <label for="saradnici" class="text-warning">Saradnici, producenti, muzičari,...</label><br>
                    <div class="input-group">
                        <textarea class="saradniciUpdate form-control" type="text" name="saradnici[<?php echo $idPjesme; ?>]"> <?php echo $saradnici; ?></textarea>
                    </div><!-- end .input-group --><br><br>

                    <!--<input type="time" step="any" name="trajanje" value="<?php echo $trajanjePjesme; ?>"><br><br>-->
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" class="text-warning">Trajanje pjesme</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text"  class="form-control"  name="trajanje[<?php echo $idPjesme; ?>]" value="<?php echo $trajanjePjesme; ?>" placeholder="00:00:00">
                    </div><!-- end .input-group --><br><br>

                    <label for="ostaleNapomene" class="text-warning">Ostale napomene za album (koje se ne odnose na određenu pjesmu, biće prikazano ispod liste pjesama)</label><br>
                    <div class="input-group">
                        <textarea class="saradniciUpdate form-control" name="ostaleNapomene[<?php echo $idPjesme; ?>]"> <?php echo $ostaleNapomene ?></textarea>
                    </div><!-- end .input-group --><br><br>


                    <label for="tekstPjesme" class="text-warning">Tekst pjesme</label><br>
                    <div class="input-group">
                        <textarea class="dodajTekst" name="tekstPjesme[<?php echo $idPjesme; ?>]"><?php echo $tekstPjesme; ?></textarea>
                    </div><!-- end .input-group --><br><br>

                    <label for="youtubeJednaPjesma" class="text-warning"><strong>Youtube Link</strong></label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://www.youtube.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="feat[<?php echo $idPjesme; ?>]" class="form-control form-control-sm text-danger" value="<?php echo $youtubeJednaPjesma; ?>">
                    </div><!-- end .input-group --><br><br>

                    <hr class="hrLinija">
                </div><!-- .form-group col-md-9 mx-auto mb-1 -->
                <?php
            }//end while  
            ?>
            <input type="submit" class="btn btn-primary mb-0" name="posalji" value="Pošalji">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="reset" class="btn btn-danger mb-0" value="Reset">
        </form>
    </div> <!-- end .slikeAlbumaPregled .sredina -->
	<?php
	global $conn;
	if(isset($_POST["posalji"]))
    {
        $promijenjenePjesme = []; // ovdje skupljamo samo pjesme koje su stvarno mijenjane

        foreach($_POST["id"] as $id)
        {
            $id = (int)$id;

            // 1) UZMI STARO STANJE (da bi mogao napraviti diff i dobiti naziv pjesme za log)
            $qOld = "SELECT redniBroj, nazivPjesme, mixtapeIzvodjac, feat, saradnici, trajanjePjesme, tekstPjesme, ostaleNapomene
                    FROM pjesme
                    WHERE idPjesme='{$id}'
                    LIMIT 1";
            $rOld = mysqli_query($conn, $qOld);
            $old  = mysqli_fetch_assoc($rOld) ?: [];

            // Ako se iz nekog razloga pjesma ne nađe, preskoči
            if (empty($old)) {
                continue;
            }

            // 2) NOVO STANJE IZ FORME
            $redniBroj= cleanText($_POST["redniBroj"][$id] ?? '');
            $nazivPjesme= removeSimbols($_POST["nazivPjesme"][$id] ?? '');
            $mixtapeIzvodjac= removeSimbols($_POST["mixtapeIzvodjac"][$id] ?? '');
            $feat= trim(removeSimbols($_POST["feat"][$id] ?? ''));
            $saradnici= trim(removeSimbols($_POST["saradnici"][$id] ?? ''));
            $trajanjePjesme = trim($_POST["trajanje"][$id] ?? '');
            $ostaleNapomene= trim(removeSimbols($_POST["ostaleNapomene"][$id] ?? ''));
            $tekstPjesme= trim(removeSimbols($_POST["tekstPjesme"][$id] ?? ''));
            $youtubeJednaPjesma= $newStream->cleanStreamsYoutubeVideo($_POST["youtubeJednaPjesma"]);

            $feat2= ($feat==="") ? null : $feat;
            $saradnici2= ($saradnici==="") ? null : $saradnici;
            $trajanjePjesme2= ($trajanjePjesme==="") ? null : $trajanjePjesme;
            $ostaleNapomene2= ($ostaleNapomene==="") ? null : $ostaleNapomene;
            $tekstPjesme2= ($tekstPjesme==="") ? null : $tekstPjesme;
            $youtubeJednaPjesma2= ($youtubeJednaPjesma==="") ? null : $youtubeJednaPjesma;

            // 3) DIFF - provjeri da li se išta promijenilo (ne logujemo vrijednosti, samo polja)
            $new = [
                'redniBroj'   => $redniBroj,
                'nazivPjesme'   => $nazivPjesme,
                'mixtapeIzvodjac'   => $mixtapeIzvodjac,
                'feat'          => $feat2,
                'saradnici'     => $saradnici2,
                'trajanjePjesme'=> $trajanjePjesme2,
                'tekstPjesme'   => $tekstPjesme2,
                'ostaleNapomene'=> $ostaleNapomene2
            ];

            $changedFields = [];

            foreach ($new as $k => $v) {
                $oldVal = $old[$k] ?? null;

                if ($oldVal === '') $oldVal = null;
                if ($v === '') $v = null;

                if ($oldVal != $v) {

                    // Tekst pjesme je ogroman - ne loguj sadržaj
                    if ($k === 'tekstPjesme') {
                        $changedFields[$k] = [
                            'old_len' => is_string($oldVal) ? mb_strlen($oldVal, 'UTF-8') : 0,
                            'new_len' => is_string($v) ? mb_strlen($v, 'UTF-8') : 0
                        ];
                    } else {
                        // Ostala polja su kratka - loguj old/new
                        $changedFields[$k] = [
                            'old' => $oldVal,
                            'new' => $v
                        ];
                    }
                }
            }


            // 4) AKO NEMA PROMJENA - preskoči UPDATE
            if (empty($changedFields)) {
                continue;
            }

            // 5) UPDATE (pošto ima promjena)
            // (Zadržavam tvoj stil upita)
            $q2= "UPDATE pjesme 
                SET redniBroj='{$redniBroj}', nazivPjesme='{$nazivPjesme}', mixtapeIzvodjac='{$mixtapeIzvodjac}', feat='{$feat2}', saradnici='{$saradnici2}', trajanjePjesme='{$trajanjePjesme2}', 
                    tekstPjesme='{$tekstPjesme2}', ostaleNapomene='{$ostaleNapomene2}', youtubeJednaPjesma='{$youtubeJednaPjesma2}'
                WHERE idPjesme='{$id}'";
            $update_pjesme= mysqli_query($conn, $q2);

            if($update_pjesme == TRUE)
            {
                // 6) SKUPI ZA LOG (samo naziv + polja koja su mijenjana)
                $promijenjenePjesme[] = [
                    'idPjesme'    => $id,
                    'nazivPjesme' => $old['nazivPjesme'],
                    'fields'      => $changedFields
                ];
            }else{
                echo "Greška " . mysqli_error($conn). "<br>";
            }
        }//end foreach

        // 7) LOG JEDNOM NA KRAJU (ako je bilo promjena)
        if (!empty($promijenjenePjesme)) {
            logMultiplePjesmeUpdated($idAlb, $promijenjenePjesme);
        }

        // 8) REDIRECT TEK POSLIJE SVIH UPDATERA
        echo "<meta http-equiv='refresh' content='0'; url='adminalbumi.php'>";
    }//end if(posalji)

}//end updateWholeAlbum()

//********************************* Pozvana metoda u fajlu updatesongs.php  *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Zabranjen pristup za lijevi panel *********************************//
function zabranjenPristup($statusKorisnika)
{ 
    global $conn;
    ?>
    <div class='col-md-2 visina leftPanel'>
        <div class="visina sredina">
            <h1 style="color:red">Nemate prava pristupa!</h1><br>
            <?php echo mysqli_error($conn);
            ?>
        </div><!-- .visina sredina -->
    </div><!-- .col-md-2 visina leftPanel -->
    <?php
}
/********************************* Pozvana metoda u adminupdatealbum.php, adminupdateartist.php, editstreams.php, indexadmin.php, showalbum.php, showalbumstreams.php, showeditlabel.php, showsignlealbum.php, updatesongs.php, updatetext.php *********************************/

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Zabranjen pristup preko čitave stranice *********************************//
function zabranjenPristupBezValidacije($statusKorisnika)
{ 
    global $conn;
    ?>
    <div class='container-fluid slikeAlbumaPregled sredina panel visina'>
        <div class="visina sredina">
            <h1 style="color:gold">Ne možete da pristupite ovom dijelu bez validnih podataka!</h1><br>
            <?php echo mysqli_error($conn);
            ?>
        </div><!-- .visina sredina -->
    </div><!-- .container-fluid slikeAlbumaPregled sredina panel visina -->
    <?php
}
/********************************* Pozvana metoda u adminupdatealbum.php, adminupdateartist.php, editstreams.php, indexadmin.php, showalbum.php, showalbumstreams.php, showeditlabel.php, showsignlealbum.php, updatesongs.php, updatetext.php  *********************************/

//--------------------------------------------------------------------------------------------------------------------------------


//********************************* Zabranjen pristup za lijevi panel sa unosom teksta i bojom teksta *********************************//
function zabranjenPristup1($bojaTeksta, $poruka)
{ 
    global $conn;
    ?>
    <div class='col-md-2 visina leftPanel'>
        <div class="visina sredina">
            <h2 style="color:<?php echo $bojaTeksta; ?>"><?php echo $poruka; ?></h2>
        </div><!-- .visina sredina -->
    </div><!-- .col-md-2 visina leftPanel -->
    <?php
}
/********************************* Pozvana metoda u adminvieweusers.php, showalbum.php, showalbumstreams.php, showsinglealbum.php *********************************/

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Zabranjen pristup za srednji panel sa unosom teksta i bojom teksta *********************************//
function zabranjenPristup2($bojaTeksta, $poruka)
{ 
    global $conn;
    ?>
    <div class='col-md-10 panel'>
        <div class="visina sredina">
            <h1 class="sredina" style="color:<?php echo $bojaTeksta; ?>"><?php echo $poruka; ?></h1>
        </div><!-- .visina sredina -->
    </div><!-- .col-md-2 visina leftPanel -->
    <?php
}
/********************************* Pozvana metoda u adminupdatealbum.php, adminupdateartist.php, editstreams.php, index.php, showalbum.php, showalbumstreams.php, showeditlabel.php, showsignlealbum.php *********************************/

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Admin izmjena podataka o jednom singlu *********************************//
function adminIzabraniJedanSingle($idSingle)
{
    global $conn;
    //$addStream= new adminStreaming();
    //$imgWork= new adminWorkImages();
    $q_singlovi= "SELECT * FROM singlovi WHERE idSinglovi='{$idSingle}'";
    $select_one_single= mysqli_query($conn, $q_singlovi);

    ?>
    <br>
    <div id="" class="pregledAlbumaUredi">
        <?php 
        while($row= mysqli_fetch_array($select_one_single))
        {
            $idSinglovi= $row["idSinglovi"];
            $singlNaziv= $row["singlNaziv"];
            $singleIzvodjaci= $row["singleIzvodjaci"];
            $singleFeat= $row["singleFeat"];
            $slikaSingla= $row["slikaSingla"];
            $godinaIzdanjaSingl= $row["godinaIzdanjaSingl"];
            $tacanDatumIzdanja= $row["tacanDatumIzdanja"];
            $ostaleNapomeneSingl= $row["ostaleNapomeneSingl"];
            $youtubeVideo= $row["youtubeVideo"];
            $spotify= $row["spotify"];
            $deezer= $row["deezer"];
            $appleMusic= $row["appleMusic"];
            $tidal= $row["tidal"];
            $youtubeMusic= $row["youtubeMusic"];
            $amazonMusic= $row["amazonMusic"];
            $soundCloud= $row["soundCloud"];
            $amazonShop= $row["amazonShop"];
            $bandCamp= $row["bandCamp"];
            $qobuz= $row["qobuz"];
            $tekstSingla= $row["tekstSingla"];
            $drzavaSingl= $row["drzavaSingl"];
            $entitetSingl= $row["entitetSingl"];
            ?>

            <div class="col-md-3">
                <div class="slikeAlbumaPregled sredina">
                    <div class="editAlbum">
                        <img src="../images/singlovi/<?php echo $slikaSingla; ?>" alt="<?php echo $singlNaziv; ?>" title="<?php echo $singlNaziv; ?> (front)" class=""/>
                        <p class="text-warning"><strong>Slika naslovnica singla</strong></p>
                        <img src="../images/albumi/" alt="" title=" (front)" class=""/>
                        <form method="POST" action="" enctype="multipart/form-data" name="singlForm" id="singlForm">
                            <input type="file" class="btn btn-light" name="dodajNaslovnuSlikuSingla"><br><br>
                            <br><hr class="hrLinija">
                            <br><br>
                    </div><!-- end .editAlbum -->
                </div><!-- end .slikeAlbumaPregled .sredina -->
            </div><!-- /.col-md-3 -->

            <script>
            document.getElementById('buttonid').addEventListener('click', openDialog);

            function openDialog() {
            document.getElementById('promjenaSlikeAlbuma').click();
            }
            </script>

        
                            
            <div class="col-md-7"> 
                <div class=""> 
                    <h3 class='text-danger sredina'><strong><span class="bg-danger text-white">&nbsp;Uputstvo za dodavanje singla: &nbsp;</strong></h3>
                    <h5 class='text-warning sredina'>1. Unesite naziv pjesme</h5>
                    <h5 class='text-warning sredina'>2. Unesite imena svih učesnika singla odvojene zarezom</h5>
                    <h5 class='text-warning sredina'>3. Popunite obavezna polja.</h5>
                </div>



                <fieldset class="border p-5 rounded">
                    <label for="nazivSingla" class="text-warning"><strong>Naziv singla <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong></label><br>
                    <input type="text" name="nazivSingla" id="nazivSingla" class="form-control form-control-sm text-danger" value="<?php echo $singlNaziv; ?>" placeholder="npr. Novosadska Setka"><br><br>

                    <label for="singleIzvodjaci" class="text-warning"><strong>Naziv solo izvođača</span> <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong></label><br>
                    <label for="singleIzvodjaci" class="text-light">Solo izvođač (ili više njih) ili grupa autor singla</span></strong></label><br>
                    <input type="text" name="singleIzvodjaci" class="form-control form-control-sm text-danger" value="<?php echo $singleIzvodjaci; ?>" placeholder="npr. Mija, Djare, Bulch, Jovica Dobrica, Ralmo, Tatula, Riga Dri, Flow, Dolar, Fox"><br><br>

                    <label for="feat" class="text-warning"><strong>feat. (ukoliko ima)</strong></label><br>
                    <label for="singleIzvodjaci" class="text-light">Ukoliko je nešto kao npr. "Bassivity Cyhper", napišite samo koji su izvođači</label><br>
                    <label for="singleIzvodjaci" class="text-info">Ukoliko je feat napišite npr. feat. (ili featuring) Bvana</label><br>
                    <label for="feat"class="text-light"><span class="bg-danger text-light">&nbsp; Ukoliko ima feat. dodati bez navodnika, zagrada ili bilo kojih drugih specijalnih karkatera osim zareza</span></strong></label><br>
                    <input type="text" name="feat" class="form-control form-control-sm text-danger" value="<?php echo $singleFeat; ?>" placeholder="feat. Bvana"><br><br>
                </fieldset><br><br>

                <label for="godinaIzdanjaSingl" class="text-warning"><strong>Godina izdanja <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong></label><br>
                <label for="godinaIzdanjaSingl" class="text-light">Upisati samo godinu bez tačke na kraju</label><br>
                <b><input type="number" name="godinaIzdanjaSingl" id="godinaIzdanjaSingl" class="form-control form-control-sm text-dark" placeholder="1995" value="<?php echo $godinaIzdanjaSingl; ?>"></b><br><br>

                <label for="tacanDatumIzdanja" class="text-warning"><strong>Tačan datum izdanja (ukoliko je poznat)</strong></label><br>
                <label for="tacanDatumIzdanja" class="text-light">Upisati u formatu 01.02.2023. (dd.mm.gggg.)</label><br>
                <b><input type="text" name="tacanDatumIzdanja" class="form-control form-control-sm text-dark" placeholder="01.02.2023." value="<?php echo $tacanDatumIzdanja; ?>"></b><br><br>

                <?php
                // vrijednosti iz baze za trenutni album
                $drzava_db   = !empty($drzavaSingl)   ? $drzavaSingl   : null;
                $entitet_db  = !empty($entitetSingl) ? $entitetSingl : null;
                ?>

                <label for="drzava" class="text-warning">
                <strong>Država (za čije glavno tržište je album) 
                <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong>
                </label><br>

                <select class="form-control" name="drzava" id="drzava" required>
                <option value="" disabled <?= $drzava_db === null ? "selected" : "" ?>>Izaberite državu</option>
                <?php 
                $q= "SELECT * FROM drzave";
                $select_drzavu= mysqli_query($conn, $q);

                while($row= mysqli_fetch_assoc($select_drzavu)) {
                    $idDrzave= $row["idDrzave"];
                    $drzavaNaziv= $row["nazivDrzave"];
                    $selected = ($drzava_db == $idDrzave) ? "selected" : "";
                    echo "<option value='{$idDrzave}' $selected>$drzavaNaziv</option>";
                }                     
                ?>
                </select><br><br>

                <label for="entitet" id="entitetLabel" class="text-warning hide">
                <strong>Entitet <span class="bg-danger text-light">&nbsp; (ako je iz BiH obavezno polje) &nbsp;</span></strong>
                </label><br>

                <select class="form-control hide" name="entitet" id="entitet">
                <option value="" disabled <?= $entitet_db === null ? "selected" : "" ?>>Izaberite entitet</option>
                <?php 
                $q= "SELECT * FROM entiteti";
                $select_entitet= mysqli_query($conn, $q);

                while($row= mysqli_fetch_assoc($select_entitet)) {
                    $idEntiteti   = $row["idEntiteti"];
                    $entitetNaziv = $row["entitetNaziv"];
                    $selected = ($entitet_db == $idEntiteti) ? "selected" : "";
                    echo "<option value='{$idEntiteti}' $selected>$entitetNaziv</option>";
                }                     
                ?>
                </select><br><br>
            
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const drzavaSelect = document.getElementById("drzava");
                        const entitetSelect = document.getElementById("entitet");
                        const entitetLabel = document.getElementById("entitetLabel");

                        function toggleEntitet() {
                            if (drzavaSelect.value === "2") { // ovdje stavi ID BiH iz baze
                                entitetSelect.classList.remove("hide");
                                entitetLabel.classList.remove("hide");
                                entitetSelect.setAttribute("required", "required");
                            } else {
                                entitetSelect.classList.add("hide");
                                entitetLabel.classList.add("hide");
                                entitetSelect.removeAttribute("required");
                                entitetSelect.value = ""; // reset ako nije BiH
                            }
                        }

                        drzavaSelect.addEventListener("change", toggleEntitet);

                        // pokreni jednom pri učitavanju (ako je već BiH u bazi)
                        toggleEntitet();
                    });
                </script>

                <style>
                .hide {
                    display: none;
                }
                </style>

                <!-- Izdavači --> 
                <fieldset class="border p-5 rounded">
                    <?php
                    //Opcije za prikaz izdavača

                    $q = "SELECT i.idIzdavaci, i.izdavaciNaziv, si.idSingle AS povezano
                        FROM izdavaci i
                        LEFT JOIN single_izdavaci si 
                            ON si.idIzdavaci = i.idIzdavaci 
                        AND si.idSingle = '{$idSingle}'
                        ORDER BY i.izdavaciNaziv";
                    $select_izdavaci = mysqli_query($conn, $q);

                    $izdavaci = [];
                    while ($red = mysqli_fetch_assoc($select_izdavaci)) {
                        $izdavaci[] = $red;
                    }

                    // dohvatimo sve ID izdavača koji su vezani za ovaj singl
                    $q2 = "SELECT idIzdavaci FROM single_izdavaci WHERE idSingle = '{$idSingle}'";
                    $res2 = mysqli_query($conn, $q2);
                    $izdavaciSingl = [];
                    while ($r = mysqli_fetch_assoc($res2)) {
                        $izdavaciSingl[] = $r["idIzdavaci"];
                    }

                    function ispisiOpcijeIzdavaciZaSingl($izdavaci, $izabrani = null) {
                        foreach ($izdavaci as $red) {
                            $selected = ($izabrani == $red["idIzdavaci"]) ? "selected" : "";
                            echo '<option value="'.$red["idIzdavaci"].'" '.$selected.'>'.$red["izdavaciNaziv"].'</option>';
                        }
                    }
                    ?>

                    <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const selects = document.querySelectorAll(".izdavac-select");

                                function validateUniqueSelection() {
                                    const selectedValues = [];
                                    let hasDuplicates = false;

                                    selects.forEach(select => {
                                        select.classList.remove("border-danger");
                                    });

                                    selects.forEach(select => {
                                        const value = select.value;
                                        if (value && selectedValues.includes(value)) {
                                            select.classList.add("border-danger");
                                            hasDuplicates = true;
                                        }
                                        selectedValues.push(value);
                                    });

                                    if (hasDuplicates) {
                                        alert("Ne možete izabrati istog izdavača više puta!");
                                    }
                                }

                                selects.forEach(select => {
                                    select.addEventListener("change", validateUniqueSelection);
                                });
                            });
                        </script>

                        <style>
                            .border-danger {
                                border: 2px solid red !important;
                            }
                        </style>

                        <label for="izdavac1" class="text-warning">
                            <strong>Izdavač 1 <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong>
                        </label><br>
                        <label for="izdavac1" class="text-light">
                            Ako je singl samostalno postavljen na net ili nije zvanično objavljen izaberite <b>Samoizdanje</b>
                        </label><br>

                        <select class="form-control izdavac-select" name="izdavac1" id="izdavac1" required>
                            <option value="" disabled <?= empty($izdavaciSingl[0]) ? "selected" : "" ?>>Izaberi izdavača</option>
                            <?php ispisiOpcijeIzdavaciZaSingl($izdavaci, $izdavaciSingl[0] ?? null); ?>
                        </select><br><br>

                        <label for="izdavac2" class="text-warning"><strong>Izdavač 2</strong></label><br>
                        <select class="form-control izdavac-select" name="izdavac2" id="izdavac2">
                            <option value="" <?= empty($izdavaciSingl[1]) ? "selected" : "" ?>>Izaberi izdavača</option>
                            <?php ispisiOpcijeIzdavaciZaSingl($izdavaci, $izdavaciSingl[1] ?? null); ?>
                        </select><br><br>

                        <label for="izdavac3" class="text-warning"><strong>Izdavač 3</strong></label><br>
                        <select class="form-control izdavac-select" name="izdavac3" id="izdavac3">
                            <option value="" <?= empty($izdavaciSingl[2]) ? "selected" : "" ?>>Izaberi izdavača</option>
                            <?php ispisiOpcijeIzdavaciZaSingl($izdavaci, $izdavaciSingl[2] ?? null); ?>
                        </select><br><br>
                </fieldset><br><br>
                <!-- end Izdavači --> 

                <label for="ostaleNapomeneSingl" class="text-warning"><strong>Ostale napomene vezane za singl</strong></label><br>
                <label for="ostaleNapomeneSingl" class="text-light">Nije obavezno polje, primjeri podataka koji se tiču za čitavu pjesmu</label><br>
                <textarea class="dodajTekstNapomene" name="ostaleNapomeneSingl"
                placeholder="Snimano u studiju: ***
                Phonographic Copyright ℗ – **** 
                Copyright © – *** 
                Printed By – ***
                Design – ***
                Music By – ***"><?php echo $ostaleNapomeneSingl; ?></textarea><br><br>

                <label for="tekstSingla" class="text-warning"><strong>Tekst pjesme</strong></label><br>
                <div id="textInput">
                    <input type="hidden" name="csrf_token" value="">
                    <textarea class="dodajTekst" name="tekstSingla"><?php echo $tekstSingla; ?></textarea><br><br>
                </div><!-- end #textInput -->
            
                <fieldset class="border p-5 rounded">
                    <legend class="w-auto px-2"><span class="podebljano bg-dark text-warning sredina">&nbsp;Strimovi&nbsp;</span></legend>
                    <br><br>
                    <p class="text-light">Polja ispod nisu obavezna, ali ukoliko znate dodajte odmah i strimove</p>

                    <label for="youtubeVideoLink" class="text-warning"><strong>Youtube Plejlsita</strong></label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://www.youtube.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="youtubeVideoLink" class="form-control form-control-sm text-danger" value="<?php echo $youtubeVideo; ?>">
                    </div><!-- end .input-group --><br><br>

                    <label for="spotifyLink" class="text-warning"><strong>Spotify</strong></label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://open.spotify.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="spotifyLink" class="form-control form-control-sm text-danger" value="<?php echo $spotify; ?>">
                    </div><!-- end .input-group --><br><br>

                    <label for="deezerLink" class="text-warning"><strong>Deezer</strong></label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://www.deezer.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="deezerLink" class="form-control form-control-sm text-danger" value="<?php echo $deezer; ?>">
                    </div><!-- end .input-group --><br><br>
                
                    <label for="appleMusicLink" class="text-warning"><strong>Apple Music</strong></label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://music.apple.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="appleMusicLink" class="form-control form-control-sm text-danger" value="<?php echo $appleMusic; ?>">
                    </div><!-- end .input-group --><br><br>
                    
                    <label for="tidalLink" class="text-warning"><strong>Tidal</strong></label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://tidal.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="tidalLink" class="form-control form-control-sm text-danger" value="<?php echo $tidal; ?>">
                    </div><!-- end .input-group --><br><br>
                    
                    <label for="youtubeMusicLink" class="text-warning"><strong>Youtube Music</strong></label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://music.youtube.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="youtubeMusicLink" class="form-control form-control-sm text-danger" value="<?php echo $youtubeMusic; ?>">
                    </div><!-- end .input-group --><br><br>

                    <label for="amazonMusicLink" class="text-warning"><strong>Amazon Music</strong></label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://music.amazon.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="amazonMusicLink" class="form-control form-control-sm text-danger" value="<?php echo $amazonMusic; ?>">
                    </div><!-- end .input-group --><br><br>

                    <p class="text-light">Mjesta gdje se može kupiti mp3 fajl ili CD (ili drugi format)</p>
                    
                    <label for="soundCloudLink" class="text-warning"><strong>SoundCloud Shop</strong></label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://soundcloud.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="soundCloudLink" class="form-control form-control-sm text-danger" value="<?php echo $soundCloud; ?>">
                    </div><!-- end .input-group --><br><br>

                    <label for="amazonShopLink" class="text-warning"><strong>Amazon Shop</strong></label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://www.amazon.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="amazonShopLink" class="form-control form-control-sm text-danger" value="<?php echo $amazonShop; ?>">
                    </div><!-- end .input-group --><br><br>

                    <label for="nazivStreamingServisa" class="text-light">BandCamp Shop</label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://bandcamp.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="bandCampLink" class="form-control form-control-sm text-danger" value="<?php echo $bandCamp; ?>">
                    </div><!-- end .input-group --><br><br>

                    <label for="nazivStreamingServisa" class="text-light">Qobuz Shop</label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://www.qobuz.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="qobuzLink" class="form-control form-control-sm text-danger" value="<?php echo $qobuz; ?>">
                    </div><!-- end .input-group --><br><br>
                </fieldset><br>


                <button class="btn btn-primary mb-0" type="submit" name="posalji" >Pošalji</button>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input class="btn btn-warning mt-0" type="reset" value="Reset">
            </div><!-- /.col-md-7 -->

            </form>

            <style>
            .error-border {
                border: 2px solid red !important;
            }
            </style>

            <script>
            document.getElementById("singlForm").addEventListener("submit", function(e) {
            let errors = [];

            // očisti stare crvene okvire
            document.querySelectorAll("#singlForm input, #singlForm select, #singlForm textarea").forEach(el => {
                el.classList.remove("error-border");
            });

            // elementi
            let nazivSingla   = document.getElementById("nazivSingla");
            let godinaIzdanjaSingl = document.getElementById("godinaIzdanjaSingl");
            let drzava        = document.getElementById("drzava");
            let feat          = document.getElementById("feat");      // FEAT
            let izdavac1      = document.getElementById("izdavac1");  // IZDAVAC 1

            // validacije
            if (nazivSingla && nazivSingla.value.trim() === "") {
                errors.push("Naziv singla");
                nazivSingla.classList.add("error-border");
            }
            if (godinaIzdanjaSingl && godinaIzdanjaSingl.value.trim() === "") {
                errors.push("Godina izdanja");
                godinaIzdanjaSingl.classList.add("error-border");
            }
            if (drzava && drzava.value.trim() === "") {
                errors.push("Država");
                drzava.classList.add("error-border");
            }
            if (feat && feat.value.trim() === "") {
                errors.push("Feat (izvođač)");
                feat.classList.add("error-border");
            }
            if (izdavac1 && izdavac1.value.trim() === "") {
                errors.push("Izdavač 1");
                izdavac1.classList.add("error-border");
            }

            // ako ima grešaka - stopiraj submit
            if (errors.length > 0) {
                e.preventDefault();
                alert("Niste popunili sledeća polja:\n- " + errors.join("\n- "));
            }
            });
            </script>
            <?php
        }//end while
        ?>
    </div><!-- end .pregledAlbumaUredi -->
                
        <?php         
        if(isset($_POST["posalji"]))
        {
            // STARI PODACI (za diff log) - uzmi prije UPDATE-a
            $qOld = "SELECT 
            singlNaziv, singleIzvodjaci, singleFeat, godinaIzdanjaSingl, tacanDatumIzdanja,
            ostaleNapomeneSingl, tekstSingla, drzavaSingl, entitetSingl,
            youtubeVideo, spotify, deezer, appleMusic, tidal, youtubeMusic, amazonMusic, soundCloud, amazonShop, soundCloud, qobuz
            FROM singlovi
            WHERE idSinglovi='{$idSingle}'
            LIMIT 1";
            $rOld = mysqli_query($conn, $qOld);
            $old  = mysqli_fetch_assoc($rOld) ?: [];


            $nazivSingla= trim(removeSimbols($_POST["nazivSingla"]));
            $singleIzvodjaci= trim(removeSimbols($_POST["singleIzvodjaci"]));
            $singleFeat= trim(removeSimbols($_POST["feat"]));
            $godinaIzdanjaSingl= trim(removeSimbols($_POST["godinaIzdanjaSingl"]));
            //$tacanDatumIzdanja= cleanText($_POST["tacanDatumIzdanja"]);
            $drzavaSingl= trim(removeSimbols($_POST["drzava"]));
            @$entitetSingl= trim(removeSimbols($_POST["entitet"]));

            @$ostaleNapomeneSingl= trim(removeSimbols($_POST["ostaleNapomeneSingl"]));
            @$tekstSingla= trim(removeSimbols(($_POST["tekstSingla"])));
            $izdavac1= $_POST["izdavac1"];


            include_once "../classes/insertData-classes/insertStreams.class.php";
            $newStream= new insertStreaming();

            $youtubeVideoLink= $newStream->cleanStreamsYoutubeVideo($_POST["youtubeVideoLink"]);
            $spotifyLink= $newStream->cleanStreamsSpotify($_POST["spotifyLink"]);
            $deezerLink= $newStream->cleanStreamsDeezer($_POST["deezerLink"]);
            $appleMusicLink= $newStream->cleanStreamsAppleMusic($_POST["appleMusicLink"]);
            $tidalLink= $newStream->cleanStreamsTidal($_POST["tidalLink"]);
            $youtubeMusicLink= $newStream->cleanStreamsYoutubeMusic($_POST["youtubeMusicLink"]);
            $amazonMusicLink= $newStream->cleanStreamsAmazonMusic($_POST["amazonMusicLink"]);
            $soundCloudLink= $newStream->cleanStreamsSoundCloud($_POST["soundCloudLink"]);
            $amazonShopLink= $newStream->cleanStreamsAmazonShop($_POST["amazonShopLink"]);
            $bandCampLink= $newStream->cleanStreamsbandCamp($_POST["bandCampLink"]);
            $qobuzLink= $newStream->cleanStreamsqobuz($_POST["qobuzLink"]);

            // NOVO STANJE (ono što upisuješ)
            $new = [
                'singlNaziv'         => $nazivSingla,
                'singleIzvodjaci'    => $singleIzvodjaci,
                'singleFeat'         => $singleFeat,
                'godinaIzdanjaSingl' => $godinaIzdanjaSingl,
                'tacanDatumIzdanja'  => $tacanDatumIzdanja,
                'ostaleNapomeneSingl'=> $ostaleNapomeneSingl,
                'tekstSingla'        => $tekstSingla,
                'drzavaSingl'        => $drzavaSingl,
                'entitetSingl'       => $entitetSingl,

                'youtubeVideo' => $youtubeVideoLink,
                'spotify'      => $spotifyLink,
                'deezer'       => $deezerLink,
                'appleMusic'   => $appleMusicLink,
                'tidal'        => $tidalLink,
                'youtubeMusic' => $youtubeMusicLink,
                'amazonMusic'  => $amazonMusicLink,
                'soundCloud'   => $soundCloudLink,
                'amazonShop'   => $amazonShopLink,
                'bandCamp'   => $bandCampLink,
                'qobuz'   => $qobuzLink
            ];

            // DIFF (old/new) - tekstove ne loguj cijele
            $changes = [];

            foreach ($new as $k => $v) 
            {
                $oldVal = $old[$k] ?? null;

                if ($oldVal === '') $oldVal = null;
                if ($v === '') $v = null;

                if ($oldVal != $v) 
                {
                    if ($k === 'tekstSingla') {
                        $changes[$k] = [
                            'old_len' => is_string($oldVal) ? mb_strlen($oldVal, 'UTF-8') : 0,
                            'new_len' => is_string($v) ? mb_strlen($v, 'UTF-8') : 0
                        ];
                    } else if ($k === 'ostaleNapomeneSingl') {
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
                }//end if($oldVal != $v)
            }//end foreach()


            if(!empty($nazivSingla) AND !empty($godinaIzdanjaSingl) AND !empty($drzavaSingl))
            {
                if(!empty($singleIzvodjaci) || !empty($singleFeat))
                {
                    $q3= "UPDATE singlovi SET singlNaziv='{$nazivSingla}', singleIzvodjaci='{$singleIzvodjaci}', singleFeat='{$singleFeat}', godinaIzdanjaSingl='{$godinaIzdanjaSingl}', tacanDatumIzdanja='{$tacanDatumIzdanja}', ostaleNapomeneSingl='{$ostaleNapomeneSingl}', tekstSingla='{$tekstSingla}', drzavaSingl='{$drzavaSingl}', entitetSingl='{$entitetSingl}', youtubeVideo='{$youtubeVideoLink}', spotify='{$spotifyLink}', deezer='{$deezerLink}', appleMusic='{$appleMusicLink}', tidal='{$tidalLink}', youtubeMusic='{$youtubeMusicLink}', amazonMusic='{$amazonMusicLink}', soundCloud='{$soundCloudLink}', amazonShop='{$amazonShopLink}', bandCamp='{$bandCampLink}', qobuz='{$qobuzLink}' WHERE idSinglovi='{$idSingle}'";
                    $update_single_song= mysqli_query($conn, $q3);
                    //print_r($q3. "<hr>");
                    if ($update_single_song == TRUE) 
                    {
                        // LOG (staro/novo)
                        if (!empty($changes)) {
                            logSingleUpdated($idSingle, $nazivSingla, $changes);
                        }

                        // Prvo obriši sve postojeće izdavače za taj album
                        mysqli_query($conn, "DELETE FROM single_izdavaci WHERE idSingle='{$idSingle}'");

                        // Uzmi izdavače iz forme
                        $izdavaci = [];
                        if (!empty($_POST['izdavac1'])) $izdavaci[] = $_POST['izdavac1'];
                        if (!empty($_POST['izdavac2'])) $izdavaci[] = $_POST['izdavac2'];

                        // Unesi ih u poveznu tabelu
                        foreach ($izdavaci as $idIzdavac) {
                            $idIzdavac = mysqli_real_escape_string($conn, $idIzdavac);
                            $q4= "INSERT INTO single_izdavaci (idSingle, idIzdavaci) VALUES ('{$idSingle}', '{$idIzdavac}')";
                            $update_single_izdavace= mysqli_query($conn, $q4);
                            //print_r("<hr>".$q4);
                        }

                        if(!empty($_FILES["dodajNaslovnuSlikuSingla"]["name"]))
                        {
                            $naslovnaSlikaSingla= $_FILES["dodajNaslovnuSlikuSingla"]["name"];
                            zamjenaNaslovneSlikeSingla($naslovnaSlikaSingla, $idSingle);
                            //print_r("$naslovnaSlikaSingla - $idSingle");
                        }//dodavanje naslovne slike albuma

                        echo "<meta http-equiv='refresh' content='1'; url='admin/showonesingle.php?single={$idSingle}'>";
                    }else {
                        echo "Greška " . mysqli_error($conn). "<br>";
                    }//end if else provjera update-a

                }else{
                    echo "Greška " . mysqli_error($conn). "<br>";
                }//end if(provjera uslova prazan single Izvodjac ili feat.)
            }else{
                echo "Niste unijeli podatke u jedno od polja: Naziv singla, godina izdanja ili Država";
            }//end if(provjera uslova)
        }// end if(isset($_POST["posalji"]))
}// end insertSingle() 
//********************************* Funkcija pozvana u fajlu showonesingle.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Metoda sa kojom mijenjamo sliku singla *********************************//
function zamjenaNaslovneSlikeSingla($naslovnaSlikaSingla, $idSingle)
{
    include_once "../functions/removeSymbols.func.php";
    global $conn; 

    $whitelist = array(".jpg",".jpeg",".gif",".png", ".svg", ".webp"); 
    //$whitelist= array("image/jpeg", "image/png", "image/gif", "image/svg", "image/webp", "image/apng", "image/avif");

    $maxVelicinaSlike= 2097152; //2mb
    $minVelicinaSlike= 10000; //10kb 
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $size= $_FILES["dodajNaslovnuSlikuSingla"]["size"];
        //print_r($size) . "<hr>";
        if(($size > $maxVelicinaSlike) || ($size< $minVelicinaSlike))
        {  
            echo "<script>
                        document.getElementById('promSlik').innerHTML='Prevelika slika (veća od 2mb) ili je premala slika (manja od 10kb)';
            </script>"; 
        }else
            {
                $putanja = "../images/singlovi/";
                $skeniraj= scandir($putanja);
                //print_r($skeniraj);

                /*$provjeraEkstenzije1= pathinfo($putanja.$slikaAlbuma);
                $provjeraEkstenzije2= $provjeraEkstenzije1['extension'];*/

                $imeSlike= removeSimbolsImg($naslovnaSlikaSingla);
                $ukloniEkstenziju= explode(".", $imeSlike);
                $ekstenzija= end($ukloniEkstenziju);
                $vrijeme= "_im".date("dmY_His", time())."_".time().".";
                $slikaVrijeme= $ukloniEkstenziju[0].$vrijeme;
                //$slikaVrijeme= array_shift($ukloniEkstenziju);

                //if provjera ekstenzije
                if (!(in_array(".".$ekstenzija, $whitelist))) 
                {
                    die('Nepravilan format slike, pokušajte sa drugom slikom');
                }else
                    {
                        $provjeraSlike= $putanja.$slikaVrijeme.$ekstenzija;
                        if(!file_exists(($provjeraSlike)))
                        {
                            $slikaVrijeme= $slikaVrijeme.$ekstenzija;
                            $slikaAlbuma_tmp= $_FILES["dodajNaslovnuSlikuSingla"]["tmp_name"];
                            move_uploaded_file($slikaAlbuma_tmp, $provjeraSlike);
                        }//end else !file_exists(($provjeraSlike)

                        $putanja = "images/singlovi/";
                        $q="UPDATE singlovi SET slikaSingla='{$slikaVrijeme}' WHERE idSinglovi='{$idSingle}'";
                        $promjeniSliku= mysqli_query($conn, $q);
                        move_uploaded_file($slikaAlbuma_tmp, $provjeraSlike);
                        //print_r("<hr>$q");

                        if($promjeniSliku == TRUE)
                        {
                            echo "<meta http-equiv='refresh' content='0'>";
                        }else{
                            echo "Greška " . mysqli_error($conn). "<br>";

                        }    
                    }//end while loop provjera korisničkog imena i šifre 
            }//end provjera ekstenzije
    }// end if provjera REQUEST_METHOD
}//end function zamjenaNaslovneSlikeSingla()
 
//********************************* Pozvana metoda u ovom fajlu u metodi adminIzabraniJedanSingle() *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Metoda koja upisuje null u bazu bez navodnika  *********************************//
function sqlNullable(mysqli $conn, $v): string
{
    if ($v === null || $v === '') return "NULL";
    return "'" . mysqli_real_escape_string($conn, $v) . "'";
}
//********************************* Pozvana metoda u ovom fajlu u funkciji adminStreamovi()  *********************************//

