<?php
//FUNKCIJE U OVOM FAJLU
//prikazDobrodošlice (prikaz na osnovu pola)
//profilnaSlikaPrikaz (Prikaz profilne slike automatski dodijeljene ukoliko nije uplodovana)
//detailsUser (prikaz detalja o profilu)
//ocjeneProfila (prikaz ocjene albuma i komentara izabranog profila)

//********************************* Prikaz dobrodošlice na osnovu pola *********************************//

use function PHPSTORM_META\type;

function prikazDobrodoslice($pol)
{
	if($pol==="Muško"){
		echo "Dobrodošao <br>";
	}else if($pol==="Žensko"){
		echo "Dobrodošla <br>";
	}//end if else if()
}//prikazDobrodoslice()

//********************************* Pozvana metoda u ovom fajlu u metodi detailsUser *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Prikaz automatske profilne slike ukoliko nije upldoovana *********************************//
function profilnaSlikaPrikaz($pol, $profilnaSlika)
{
    if($pol==="Muško"){
    return (empty($profilnaSlika)) ? "Lino_Vortex.png" : $profilnaSlika;
    
	}else if($pol==="Žensko"){
		return (empty($profilnaSlika)) ? "Lina_Vortex.png" : $profilnaSlika;
        
	}//end if else if()
}
//********************************* Pozvana metoda u ovom fajlu u metodi detailsUser *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Prikaz detalja o profilu *********************************//
function detailsUser($profil, $lid, $sesId)
{

    if(!isset($_SESSION['username']) && !isset($_SESSION['idKorisnici']))
    {
        zabranjenPristupBezValidacije($sesId);
    }else
    {
        global $conn;
        $q2= "SELECT * FROM korisnici WHERE username='{$profil}' AND idKorisnici='{$lid}'";
        $pregledajPodatke= mysqli_query($conn, $q2);

        while($row= mysqli_fetch_assoc($pregledajPodatke))
        {
            $idKorisnici= (int) $row["idKorisnici"];
            $ime= $row["ime"];
            $prezime= $row["prezime"];
            $email= $row["email"];
            $username= $row["username"];
            $datumRegistracije= date("d.m.Y. H:i", strtotime($row["datumRegistracije"]));
            $pol= $row["pol"];
            $tipKorisnika= $row["tipKorisnika"];
            $drzava= $row["drzava"];
            $profilnaSlika= $row["profilnaSlika"];
            $grad= $row["grad"];
            $facebookPr= $row["facebookPr"];
            $instagramPr= $row["instagramPr"];
            $twitterPr= $row["twitterPr"];
            $tiktokPr= $row["tiktokPr"];
            $sajt= $row["websajt"];
            $zadnjiLogin= $row["zadnjiLogin"];
            $statusKorisnika= (int) $row["statusKorisnika"];
            $verifikacijaKorisnika= $row["verifikacijaKorisnika"];
            $entitet= $row["entitet"];
            
            $datum1= strtotime($datumRegistracije);
            $noviDatum=date("d.m.Y. H:i:s", $datum1);

            $datum2= strtotime($zadnjiLogin);
            $noviZadnjiLogin= date("d.m.Y. H:i:s", $datum2);
            /*$formatDatuma= date_format(now(), "%d.%m.%Y");
            date_format(now(), ' Датум: %d.%m.%Y. ');
            now();*/

            $cleanUsername= str_replace(" ", "+", removeSpecialLetters($username));
            $cleanProfil= str_replace(" ", "-", removeSpecialLetters($profil));

            $profilna = profilnaSlikaPrikaz($pol, $profilnaSlika);

            $q3= "SELECT * FROM drzave2 WHERE kodZemljeDugi='{$drzava}'";
            $sveDrzave= mysqli_query($conn, $q3);

            while($row= mysqli_fetch_array($sveDrzave))
            {
                $idDrzave2= $row["idDrzave2"];
                $drzavaNaziv= $row["drzavaNaziv"];
                $kodZemljeDugi= $row["kodZemljeDugi"];
                $zastava= $row["zastava"];
                ?>
                <main>
                    <aside class="profilBocno">
                        <?php 
                        if($statusKorisnika!==0 && $statusKorisnika!==3){
                            if($lid===$sesId){
                                pristupAdmin();
                            }
                        }
                        
                        ?>

                        
                        <a href="images/profilne/<?php echo $profilna; ?>" data-lightbox="slika-1">
                        <img src="images/profilne/<?php echo $profilna;?>" class="profilnaSlika" alt="<?php echo $username;?>" title="<?php echo $username;?>">
                        </a>

                        <?php 
                        if($sesId===$idKorisnici){
                            echo "<h6 class='prikazRegistracijeNaSajt'>Registrovali ste se na sajt: <br> $datumRegistracije</h6>";
                            ?>
                            <h4 class="dobrodoslica"><?php prikazDobrodoslice($pol); ?></h4>
                            <?php
                        }
                        ?>
                        <h2 class="dobrodoslica"><?php echo $username;?></h2>
                        
                        <?php 
                        if($sesId!==$idKorisnici){
                            echo "<h6 class='prikazRegistracijeNaSajt'>Korisnik je registrovan na sajtu od: <br> $datumRegistracije</h6>";
                            }
                        if(!empty($verifikacijaKorisnika) && ($verifikacijaKorisnika==2))
                        {
                            ?>
                            <img src="images/website/verifikacija.png" class="verifikacija" alt="Verifikovan profil" title="Verifikovan profil"><br>
                            <?php
                        }
                        ?>

                        <a href='chat/chat.php?username=<?php echo $cleanUsername ?>&user_id=<?php echo $lid; ?>'>
                        <?php 
                        if($statusKorisnika!==0)
                        {
                            ?>
                            <svg xmlns="http://www.w3.org/2000/svg" height="3.50em" viewBox="0 0 512 512" style="fill: gold">
                            <title>Pošalji mi poruku</title><!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 112c-8.8 0-16 7.2-16 16v22.1L220.5 291.7c20.7 17 50.4 17 71.1 0L464 150.1V128c0-8.8-7.2-16-16-16H64zM48 212.2V384c0 8.8 7.2 16 16 16H448c8.8 0 16-7.2 16-16V212.2L322 328.8c-38.4 31.5-93.7 31.5-132 0L48 212.2zM0 128C0 92.7 28.7 64 64 64H448c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128z"/></svg>
                            <?php
                        }else if($sesId===$idKorisnici){
                             echo "<h5 class='text-warning'>Blokirani ste od strane sajta!</h5>";
                        }else{
                           echo "<h5 class='text-warning'>Ovaj korisnik je blokiran!</h5>";
                        }
                        ?>
                            </a>
                        <p>Zadnja aktivnost <br> <?php echo $noviZadnjiLogin; ?></p>
                        <?php
                        $usr= new User();
                        $usr->userOnline($lid);
                        ?>
                        <hr>
                        <?php 
                        if($kodZemljeDugi===$drzava){
                        echo "<div class='zastavaProfil'>
                            <img class='zastavaProfilSlika' src='images/zastave/$zastava' alt='$drzavaNaziv' title='$drzavaNaziv'>
                            </div>" ;
                        echo " <br><p class='inline-block'>$drzavaNaziv</p> ";

                        if($drzava=="BIH"){
                            $q3= "SELECT * FROM drzave2 
                            JOIN entiteti ON entiteti.entDrzava=drzave2.idDrzave2
                            WHERE idDrzave2='{$idDrzave2}' AND kodEntiteta='{$entitet}'";
                            $sveDrzave= mysqli_query($conn, $q3);

                            while($row= mysqli_fetch_array($sveDrzave))
                            {
                                $drzavaNaziv= $row["drzavaNaziv"];
                                $kodZemljeDugi= $row["kodZemljeDugi"];
                                $zastava= $row["zastava"];
                                $entitetNaziv= $row["entitetNaziv"];
                                $kodEntiteta= $row["kodEntiteta"];
                                $zastavaEnt= $row["zastavaEnt"];
                                $idEntiteti= $row["idEntiteti"];
                        
                                if($entitet=="RS"){
                                        echo "<br><div class='zastavaProfil'><img class='zastava' src='images/zastave/$zastavaEnt' alt='$entitetNaziv' title='$entitetNaziv'></div>" ;
                                        echo " <br><p class='inline-block'>$entitetNaziv</p> ";
                                    }else if($entitet=="FBIH"){
                                        echo "<br><div class='zastavaProfil'><img class='zastava' src='images/zastave/$zastavaEnt' alt='$entitetNaziv' title='$entitetNaziv'></div>" ;
                                    echo " <br><p class='inline-block'>$entitetNaziv</p> ";
                                    }
                            }
                        }
                        }else{
                            echo "imamo neki problem <br> " . mysqli_error($conn);
                        }
                        ?>
                        <p><?php echo $grad; ?></p>
                        <?php 
                        if(!empty($facebookPr))
                        {
                            ?>
                            <span class="fa-stack">
                                <a href="<?php echo "https://www.facebook.com/".$facebookPr; ?>" target="_blank">
                                    <i class="fa fa-circle fa-2x" aria-hidden="true"></i>
                                    <i class="fab fa-facebook-f fa-stack-1x"></i>
                                </a>
                            </span>
                            <?php
                        }else{echo "";}

                        if(!empty($instagramPr))
                        {
                            ?>
                            <span class="fa-stack">
                                <a href="<?php echo "https://www.instagram.com/".$instagramPr; ?>" target="_blank">
                                    <i class="fas fa-circle fa-stack-2x"></i>
                                    <i class="fab fa-instagram fa-stack-1x"></i>
                                </a>
                            </span>
                        <?php
                        }else{echo "";}
                            
                        if(!empty($twitterPr))
                        {
                            ?>
                        <span class="fa-stack">
                            <a href="<?php echo "https://twitter.com/".$twitterPr; ?>" target="_blank">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-twitter fa-stack-1x"></i>
                            </a>
                        </span>
                        <?php
                        }else{echo "";}
                            
                        if(!empty($tiktokPr))
                        {
                            ?>
                        <span class="fa-stack">
                            <a href="<?php echo "https://www.tiktok.com/@".$tiktokPr; ?>" target="_blank">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-tiktok fa-stack-1x"></i>
                            </a>
                        </span>
                        <?php
                        }else{echo "";}
                        ?>
                        <p><a href="<?php echo $sajt; ?>" target="_blank"><?php echo $sajt; ?></a></p>
                        <?php
                        if($sesId===$idKorisnici){
                            ?>
                            <hr>
                            <a href="profileedit.php?username=<?php echo $cleanProfil."&lid=".$lid; ?>" class="btn btn-success d-flex align-items-center hover-shadow sredina">Izmjena profila</a>
                            <?php
                        }else {echo "";}
                        ?>
                    </aside>
                    <?php
                    ocjeneProfila($lid);
                    ?>
                </main>  
                <?php
            }/* end while sve države */
        }/* end while Select * od korisnici */
    }//end if provjera sesije
}//end detailsUser()
//********************************* Pozvana metoda u profil.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Prikaz ocjene albuma i komentara izabranog profila *********************************//
function ocjeneProfila($lid)
{
    global $conn;
    ?>
    <section class="profilCentar">
        <div class="flexPrikaz">
            <div class="ocjenjeniAlbumi">
                <h4>Ocijenjeni albumi</h4>
                <ol>
                    <?php
                    $q="SELECT ocjene.*, izvodjaci.* , albumi.nazivAlbuma, albumi.slikaAlbuma FROM ocjene 
                    JOIN izvodjaci ON ocjene.izvodjacId=izvodjaci.idIzvodjaci 
                    JOIN albumi ON albumId=idAlbum 
                    WHERE korisniciId='{$lid}'";
                    $izvuciOcjene= mysqli_query($conn, $q);

                    while($row= mysqli_fetch_assoc($izvuciOcjene))
                    {
                        $idIzvodjac= $row["idIzvodjaci"];
                        $albumId= $row["albumId"];
                        $izvodjacMaster= $row["izvodjacMaster"];
                        $ocjena= $row["ratedIndex"];
                        $vrijeme= $row["vrijeme"];
                        $izvodjac= $row["izvodjacMaster"];
                        $nazivAlbuma= $row["nazivAlbuma"];
                        $slikaAlbuma= $row["slikaAlbuma"];

                        $cleanIzvodjacMaster= konverzijaLatinica($izvodjacMaster);
                        $cleanNazivAlbuma= konverzijaLatinica($nazivAlbuma);
                        $cleanIzvodjacMaster= konverzijaLatinica(removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($cleanIzvodjacMaster))));
                        $cleanNazivAlbuma= konverzijaLatinica(removeSerbianLetters(cleanAlbum($cleanNazivAlbuma)));
                        ?>
                        <li class="profilPrikazAlbuma">
                            <a href="oalbumu.php?izv=<?php echo $idIzvodjac."&album=".$albumId."&naziv=". $cleanIzvodjacMaster."-".$cleanNazivAlbuma; ?>" >
                            <?php echo "<img src='images/albumi/$slikaAlbuma' alt='$izvodjac - $nazivAlbuma' title='$izvodjac - $nazivAlbuma'> (<span class='fas fa-microphone'></span> $ocjena)" ?>
                            </a>
                        </li><br>
                        <?php
                    }//end while izvuciOcjene
                    ?>
                </ol>
            </div><!-- end .ocjenjeniAlbumi -->
                        
            <div class="komentarisaniAlbumi">
                <h4>Komentarisani albumi</h4>
                <ol>
                    <?php
                    $q2="SELECT recenzije.*, albumi.idAlbum, albumi.nazivAlbuma, albumi.idIzvodjacAlbumi, izvodjaci.idIzvodjaci, izvodjaci.izvodjacMaster FROM recenzije 
                    JOIN albumi ON albumId=idAlbum 
                    JOIN izvodjaci ON albumi.idIzvodjacAlbumi= izvodjaci.idIzvodjaci
                    WHERE korisnikId='{$lid}'";
                    $izvuciKomentare= mysqli_query($conn, $q2);

                    while($row= mysqli_fetch_assoc($izvuciKomentare))
                    {
                        $idIzvodjac= $row["idIzvodjaci"];
                        $albumId= $row["idAlbum"];
                        $izvodjacMaster= $row["izvodjacMaster"];
                        $nazivAlbuma= $row["nazivAlbuma"];
                        $recenzija= $row["recenzija"];
                        $vrijeme= $row["vrijemeRecenzije"];
                        $izvodjac= $row["izvodjacMaster"];
                        $nazivAlbuma= $row["nazivAlbuma"];

                        $cleanIzvodjacMaster= konverzijaLatinica($izvodjacMaster);
                        $cleanNazivAlbuma= konverzijaLatinica($nazivAlbuma);
                        $cleanIzvodjacMaster= konverzijaLatinica(removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($cleanIzvodjacMaster))));
                        $cleanNazivAlbuma= konverzijaLatinica(removeSerbianLetters(cleanAlbum($cleanNazivAlbuma)));
                        ?>
                        <li>
                            <a href="oalbumu.php?izv=<?php echo $idIzvodjac."&album=".$albumId."&naziv=". $cleanIzvodjacMaster."-".$cleanNazivAlbuma; ?>" ><?php echo "<span class='clickLink'>$izvodjac - $nazivAlbuma</span> <br>($recenzija)"; ?>
                            </a>
                        </li>
                        <?php
                    }//end while izvuciKomentare
                    ?>
                </ol>
            </div><!-- end .komentarisaniAlbumi -->
        </div><!-- end .flexPrikaz -->

        <div class="slikeAlbuma favoriteAlbums">
            <h4>Omiljeni albumi</h4>
            <?php
            $q3= "SELECT izvodjaci.idIzvodjaci, izvodjaci.izvodjacMaster, albumi.idAlbum, albumi.nazivAlbuma, albumi.slikaAlbuma, omiljeni_albumi.userIdFavorite, omiljeni_albumi.idOmiljeniAlbumi, omiljeni_albumi.albumiFavorite FROM omiljeni_albumi 
            JOIN albumi ON albumiFavorite=idAlbum 
            JOIN izvodjaci ON albumi.idIzvodjacAlbumi=izvodjaci.idIzvodjaci 
            WHERE userIdFavorite='{$lid}'";
            $izvuciFavAlbume= mysqli_query($conn, $q3);

            while($row= mysqli_fetch_array($izvuciFavAlbume))
            {
                $idIzvodjac= $row["idIzvodjaci"];
                $izvodjacMaster= $row["izvodjacMaster"];
                $albumId= $row["idAlbum"];
                $nazivAlbuma= $row["nazivAlbuma"];
                $slikaAlbuma= $row["slikaAlbuma"];
                $idOmiljeniAlbumi= $row["idOmiljeniAlbumi"];

                $cleanIzvodjacMaster= konverzijaLatinica($izvodjacMaster);
                $cleanNazivAlbuma= konverzijaLatinica($nazivAlbuma);
                $cleanIzvodjacMaster= konverzijaLatinica(removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($cleanIzvodjacMaster))));
                $cleanNazivAlbuma= konverzijaLatinica(removeSerbianLetters(cleanAlbum($cleanNazivAlbuma)));

                ?>
                <div class='myCard'>
                    <a href="oalbumu.php?izv=<?php echo $idIzvodjac."&album=".$albumId."&naziv=". $cleanIzvodjacMaster."-".$cleanNazivAlbuma; ?>" >
                    <img src="images/albumi/<?php echo $slikaAlbuma; ?>" alt="<?php echo $izvodjacMaster ."-". $nazivAlbuma; ?> title="<?php echo $izvodjacMaster ."-". $nazivAlbuma; ?>"></img></a>
                </div><!-- end .myCard -->
                <?php
            }//end while izvuciAlbume
            ?>
        </div><!-- slikeAlbuma favoriteAlbums -->
    </section>
    <?php
}//end ocjeneProfila()
//********************************* Pozvana metoda u ovom fajlu u metodi detailsUser *********************************//

//--------------------------------------------------------------------------------------------------------------------------------