<?php
//FUNKCIJE U OVOM FAJLU
//poAlbumima (Vrši pretragu po albumima)
//poIzvodjacima (Vrši pretragu po izvođačima)
//poPjesmama (Vrši pretragu po pjesmama)
//poLabelu (Vrši pretragu po izdavačima/labelu)
//poSinglovima (Vrši pretragu po singlovima)
//poProfilimaIzvodjaca (Vrši pretragu po verifikovanim profilima izvođača)
//poProfilimaIzdavaca (Vrši pretragu po verifikovanim profilima izzdavača/Labela)

//********************************* Vrši pretragu po albumima *********************************//
function poAlbumima($pretraga)
{ 
    $rezultat= removeSimbols(trim($pretraga));
     if ($rezultat === "") {
        return 0; 
    }

    global $conn;
    echo "<h2 class='naslovPretrage'>Albumi:</h2>";
    $q= "SELECT * FROM albumi JOIN izvodjaci ON izvodjaci.idIzvodjaci=albumi.idIzvodjacAlbumi WHERE izvodjacMaster LIKE '%$rezultat%' OR nazivAlbuma LIKE '%$rezultat%'";
    $select_rez= mysqli_query($conn, $q);
    //if(mysqli_num_rows($select_rez)>0)
    $count = mysqli_num_rows($select_rez);
    if($count > 0)
    {
        ?>
        
            
            <?php 
            //Završetak div taga .albumPrikaz je u metodi poPjesmama, zato što se zadnja prikazuje. 
            while($row=mysqli_fetch_array($select_rez))
            {
                $idIzvodjaci= $row["idIzvodjaci"];
                $albumId= $row["idAlbum"];
                $izvodjacMaster= $row["izvodjacMaster"];
                $album= $row["nazivAlbuma"];
                $slikaAlbuma= $row["slikaAlbuma"];
                $nazivAlbuma= $row["nazivAlbuma"];

                $cleanIzvodjacMaster= konverzijaLatinica($izvodjacMaster);
                $cleanNazivAlbuma= konverzijaLatinica($nazivAlbuma);
                $cleanIzvodjacMaster= removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($cleanIzvodjacMaster)));
                $cleanNazivAlbuma= removeSerbianLetters(cleanAlbum($cleanNazivAlbuma));
                ?>
                <div class="myCard">
                    <a href="oalbumu.php?izv=<?php echo $idIzvodjaci; ?>&album=<?php echo $albumId; ?>&naziv=<?php echo $cleanIzvodjacMaster . "-" .$cleanNazivAlbuma; ?>">
                    <div class="card-header">
                            <h5 class="myCard-title sredina"><?php echo $izvodjacMaster . " - " . $nazivAlbuma; ?></h5>
                    </div><!-- end .card-header -->
                        <img src="images/albumi/<?php echo $slikaAlbuma;?>">
                    </a>
                </div><!-- end .myCard -->
                <?php
            }//end while
    }else{
            ?>
            <div class="albumPrikaz">
            <?php
            
            echo "Nema rezultata pretrage po albumima";
        }//end else
        echo "<hr class='hrLinija1'>";
        return $count; //ukupno sabira rezultate
}//end poAlbumima()
//******************************* Sve funkcije su pozvane u search.php *******************************//

//--------------------------------------------------------------------------------------------------------------------------------
    
//********************************* Vrši pretragu po izvođačima *********************************//
function poIzvodjacima($pretraga)
{ 
    $rezultat= removeSimbols(trim($pretraga));
    global $conn;
    echo "<h2 class='naslovPretrage'>Izvođači:</h2>";
    $q2= "SELECT * FROM izvodjaci WHERE izvodjacMaster LIKE '%$rezultat%' OR nadimciIzvodjac LIKE '%$rezultat%' OR ime LIKE '%$rezultat%' OR prezime LIKE '%$rezultat%'";
    $select_rez2= mysqli_query($conn, $q2);
    //if(mysqli_num_rows($select_rez2)>0)
    $count = mysqli_num_rows($select_rez2);
    if($count > 0)
    {
        while($row2=mysqli_fetch_array($select_rez2))
        {
            $idIzvodjac= $row2["idIzvodjaci"];
            $izvodjacMaster= $row2["izvodjacMaster"];

            $cleanIzvodjacMaster= konverzijaLatinica($izvodjacMaster);
            //$cleanIzvodjacMaster= konverzijaLatinica(removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($cleanIzvodjacMaster))));
            $cleanIzvodjacMaster= konverzijaLatinica(str_replace(" ", "+", removeSpecialLetters($cleanIzvodjacMaster)));
            

            ?>
            <a class="clickLink" href="izvodjac.php?idIzv=<?php echo $idIzvodjac;?>&izvodjac=<?php echo $cleanIzvodjacMaster; ?>"><?php echo $izvodjacMaster; ?></a> <br>
            <?php
        }//end while
    }else
        {
        echo "Nema rezultata pretrage po izvođačima.";
        }//end else
    echo "<hr class='hrLinija1'>";
     return $count; //ukupno sabira rezultate
}//end poIzvodjacima()
//******************************* Sve funkcije su pozvane u search.php *******************************//

//--------------------------------------------------------------------------------------------------------------------------------
    
//********************************* Vrši pretragu po pjesmama *********************************//
function poPjesmama($pretraga)
{ 
    $rezultat= removeSimbols(trim($pretraga));
    global $conn;
    echo "<h2 class='naslovPretrage'>Pjesme:</h2>";
    $q3= "SELECT * FROM pjesme WHERE nazivPjesme LIKE '%$rezultat%' OR feat LIKE '%$rezultat%'";
    $select_rez3= mysqli_query($conn, $q3);
    //if(mysqli_num_rows($select_rez3)>0)
    $count = mysqli_num_rows($select_rez3);
    if($count > 0)
    {
        while($row3=mysqli_fetch_array($select_rez3))
        {
            $idPjesme= $row3["idPjesme"];
            $pjesma= $row3["nazivPjesme"];
            $feat= $row3["feat"];

            ?>
            <a class="clickLink" href="tekstovi.php?tekst=<?php echo $idPjesme; ?>"><?php echo $pjesma . " " . $feat ?></a> <br>
            <?php
        }//end while
    }else
        {
            echo "Nema rezultata po pjesmama.";
        }
        echo "<hr class='hrLinija1'>";
        return $count; //ukupno sabira rezultate
        //Završetak div taga .albumPrikaz je u metodi poPjesmama, zato što se zadnja prikazuje. 
}//end poPjesmama()
//******************************* Sve funkcije su pozvane u search.php *******************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Vrši pretragu po izdavačima/labelu *********************************//
function poLabelu($pretraga)
{ 
    $rezultat= removeSimbols(trim($pretraga));
    global $conn;
    echo "<h2 class='naslovPretrage'>Izdavačka kuća:</h2>";
    $q4= "SELECT * FROM izdavaci WHERE izdavaciNaziv LIKE '%$rezultat%'";
    $select_rez4= mysqli_query($conn, $q4);
    //if(mysqli_num_rows($select_rez4)>0)
    $count = mysqli_num_rows($select_rez4);
    if($count > 0)
    {
        while($row4=mysqli_fetch_array($select_rez4))
        {
            $izdavaci= $row4["izdavaciNaziv"];
            $idIzdvaci=$row4["idIzdavaci"];
            $cleanIzdavaci= str_replace(" ", "+", removeSpecialLetters($izdavaci));

            ?>
            <a class="clickLink" href="label.php?izdavac=<?php echo $cleanIzdavaci ?>&idIzdavac=<?php echo $idIzdvaci; ?>"><?php echo $izdavaci; ?></a> <br>
            <?php
        }//end while
    }else
        {
        echo "Nema rezultata pretrage po izdavačkim kućama.";
        }//end else
    echo "<hr class='hrLinija1'>";
    return $count; //ukupno sabira rezultate
}//end poLabelu()
//******************************* Sve funkcije su pozvane u search.php *******************************//

//--------------------------------------------------------------------------------------------------------------------------------
    
//********************************* Vrši pretragu po singlovima *********************************//
function poSinglovima($pretraga)
{ 
    $rezultat= removeSimbols(trim($pretraga));
    global $conn;
    echo "<h2 class='naslovPretrage'>Singlovi:</h2>";
    $q5= "SELECT * FROM singlovi WHERE singlNaziv LIKE '%$rezultat%' OR singleFeat LIKE '%$rezultat%' OR singleIzvodjaci LIKE '%$rezultat%'";
    $select_rez5= mysqli_query($conn, $q5);
    //if(mysqli_num_rows($select_rez5)>0)
    $count = mysqli_num_rows($select_rez5);
    if($count > 0)
    {
        while($row5=mysqli_fetch_array($select_rez5))
        {
            $idSinglovi= $row5["idSinglovi"];
            $singlNaziv= $row5["singlNaziv"];
            $singleFeat= $row5["singleFeat"];
            $singleIzvodjaci= $row5["singleIzvodjaci"];
            
            ?>
            <a class="clickLink" href="singlovi.php?singl=<?php echo $idSinglovi; ?>"><?php echo $singleIzvodjaci . " - " . $singlNaziv . " " . $singleFeat ?></a> <br>
            <?php
            
        }//end while
    }else
        {
            echo "Nema rezultata po singlovima.";
        }
        echo "<hr class='hrLinija1'>";
        return $count; //ukupno sabira rezultate
        //Završetak div taga .albumPrikaz je u metodi poPjesmama, zato što se zadnja prikazuje. 
}//end poPjesmama()
//******************************* Sve funkcije su pozvane u search.php *******************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Vrši pretragu po verifikovanim profilima izvođača *********************************//
function poProfilimaIzvodjaca($pretraga)
{ 
    $rezultat= removeSimbols(trim($pretraga));
    global $conn;
    echo "<h2 class='naslovPretrage'>Profili izvođači:</h2>";
    $q6= "SELECT idKorisnici, username FROM korisnici WHERE username LIKE '%$rezultat%' AND statusKorisnika=4 AND verifikacijaKorisnika=2";
    $select_rez6= mysqli_query($conn, $q6);
    //if(mysqli_num_rows($select_rez6)>0)
    $count = mysqli_num_rows($select_rez6);
    if($count > 0)
    {
        while($row6=mysqli_fetch_array($select_rez6))
        {
            $idKorisnici= $row6["idKorisnici"];
            $username= $row6["username"];
            
            ?>
            <a class="clickLink" href="profile.php?username=<?php echo $username; ?>&lid=<?php echo $idKorisnici; ?>"><?php echo $username; ?></a> <br>
            <?php
            
        }//end while
    }else
        {
            echo "Nema rezultata po verifikovanim izvođačima.";
        }
        echo "<hr class='hrLinija1'>";
        return $count; //ukupno sabira rezultate
        //Završetak div taga .albumPrikaz je u metodi poPjesmama, zato što se zadnja prikazuje. 
}//end poPjesmama()
//******************************* Sve funkcije su pozvane u search.php *******************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Vrši pretragu po verifikovanim profilima Izdavača/Labela *********************************//
function poProfilimaIzdavaca($pretraga)
{ 
    $rezultat= removeSimbols(trim($pretraga));
    global $conn;
    echo "<h2 class='naslovPretrage'>Profili izdavači/Labeli:</h2>";
    $q7= "SELECT idKorisnici, username FROM korisnici WHERE username LIKE '%$rezultat%' AND statusKorisnika=5 AND verifikacijaKorisnika=2";
    $select_rez7= mysqli_query($conn, $q7);
    //if(mysqli_num_rows($select_rez7)>0)
    $count = mysqli_num_rows($select_rez7);
    if($count > 0)
    {
        while($row6=mysqli_fetch_array($select_rez7))
        {
            $idKorisnici= $row6["idKorisnici"];
            $username= $row6["username"];
            
            ?>
            <a class="clickLink" href="profile.php?username=<?php echo $username; ?>&lid=<?php echo $idKorisnici; ?>"><?php echo $username; ?></a> <br>
            <?php
            
        }//end while
    }else
        {
            echo "Nema rezultata po verifikovanim izvođačima.";
        }
        echo "<hr class='hrLinija1'>";
        return $count; //ukupno sabira rezultate
        //Završetak div taga .albumPrikaz je u metodi poPjesmama, zato što se zadnja prikazuje. 
}//end poPjesmama()
//******************************* Sve funkcije su pozvane u search.php *******************************//

//--------------------------------------------------------------------------------------------------------------------------------