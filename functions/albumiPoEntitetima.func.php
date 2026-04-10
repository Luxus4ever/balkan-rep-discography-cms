<?php
//FUNKCIJE U OVOM FAJLU
//sviAlbumiPoEnt (Prikaz svih abluma po izabranom entitetu)
//sviAlbumiPoEntPoGodini (Prikaz svih albuma po izabranom entitetu po godini)
//sviAlbumiAbecednoEnt (Metoda koja uzima vrijednost izabrane opcije option menija)
//prikazIzabranihAlbumaEnt (Prikaz albuma po izabranoj opciji select menija)
//redosledPoEntitetima (Prikaz redosleda svih albuma po izabranom entitetu)
//prikazZastaveEntitet (Prikaz zastave po izabranom entitetu)

//********************************* Prikaz svih albuma po izabranom entitetu *********************************//
function sviAlbumiPoEnt($imeEnt)
{
    global $conn;

        $q = "SELECT * FROM entiteti 
            JOIN albumi ON albumi.entitetAlbumi = entiteti.idEntiteti
            JOIN izvodjaci ON izvodjaci.idIzvodjaci = albumi.idIzvodjacAlbumi
            /*JOIN albumi_izdavaci ON albumi_izdavaci.idAlbum = albumi.idAlbum
            JOIN izdavaci ON izdavaci.idIzdavaci = albumi_izdavaci.idIzdavaci*/
            WHERE entitetNaziv = '{$imeEnt}' ORDER BY RAND()";
    $select_albumi= mysqli_query($conn, $q);

    prikazZastaveEntitet($imeEnt);
    ?>
    <div class="slikeAlbuma">
        <?php 
        selectZaPrikazAlbuma();

        while($row=mysqli_fetch_array($select_albumi))
        {
            $idAlbum= $row["idAlbum"];
            $idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
            $idIzvodjac2= $row["idIzvodjac2"];
            $idIzvodjac3= $row["idIzvodjac3"];
            $nazivAlbuma= $row["nazivAlbuma"];
            $godinaIzdanja= $row["godinaIzdanja"];
            $slikaAlbuma= $row["slikaAlbuma"];
            $drzavaAlbumi= $row["drzavaAlbumi"];
            $entitetAlbumi= $row["entitetAlbumi"];
            $izvodjacMaster= $row["izvodjacMaster"];
            $idEntiteti= $row["idEntiteti"];
            $entitet= $row["entitetNaziv"];
            //$entitet1= $row["entitet1"];
            //$entitet2= $row["entitet2"];


           /*-------------------- Početak Izdavači --------------------*/
            $qIzdavaci = "SELECT izdavaciNaziv FROM izdavaci 
            JOIN albumi_izdavaci ON albumi_izdavaci.idIzdavaci= izdavaci.idIzdavaci
            JOIN albumi ON albumi.idAlbum= albumi_izdavaci.idAlbum
            WHERE albumi_izdavaci.idAlbum='{$idAlbum}'";

            $rezIzdavaci = mysqli_query($conn, $qIzdavaci);

            $izdavaciHTML = [];

            while ($izd = mysqli_fetch_assoc($rezIzdavaci)) 
            {
                $izdavaciNaziv = $izd['izdavaciNaziv'];
                $izdavaciHTML[]= $izdavaciNaziv;
            
            }//end while
            /*-------------------- end izdavači --------------------*/

            $cleanIzvodjacMaster= konverzijaLatinica($izvodjacMaster);
            $cleanNazivAlbuma= konverzijaLatinica($nazivAlbuma);
            $cleanIzvodjacMaster= konverzijaLatinica(removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($cleanIzvodjacMaster))));
            $cleanNazivAlbuma= konverzijaLatinica(removeSerbianLetters(cleanAlbum($cleanNazivAlbuma)));
            ?>
            
            <div class="myCard myCard-size2">
                <a href="oalbumu.php?izv=<?php echo $idIzvodjacAlbumi."&album=".$idAlbum."&naziv=".$cleanIzvodjacMaster."-".$cleanNazivAlbuma; ?>" >
                    <img loading="lazy" class="myCard-img-top" src="images/albumi/<?php echo $slikaAlbuma; ?>" alt="<?php echo $izvodjacMaster . " - " . $nazivAlbuma; ?>" title="<?php echo izabraniIzvodjac($izvodjacMaster, $idIzvodjac2, $idIzvodjac3) . " - " . $nazivAlbuma; ?>">
                    <div class="myCard-body">
                        <h5 class="myCard-title"><?php echo izabraniIzvodjac($izvodjacMaster, $idIzvodjac2, $idIzvodjac3) . " - " . $nazivAlbuma; ?></h5>
                        <p class="myCard-text1"><span class="godinaIzd"><?php echo $godinaIzdanja . "</span>"; ?></p>
                        <p class="myCard-text2"><?php echo implode(", ", $izdavaciHTML); ?></p>
                    </div><!-- end .myCard-body -->    
                </a>
            </div><!-- end .myCard -->
            <?php
        }//end while
        ?>
    </div><!-- end .slikeAlbuma -->

    <?php
}//end sviAlbumiPoEnt()

//********************************* Metoda pozvana u entitet.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Prikaz svih albuma po izabranom entitetu po godini *********************************//
function sviAlbumiPoEntPoGodini($imeEnt, $redosledAlbuma)
{
    global $conn;
    $q= "SELECT *, count(albumi.godinaIzdanja) AS brAlb 
    FROM drzave JOIN entiteti ON entiteti.entDrzava=drzave.idDrzave
    JOIN albumi ON albumi.entitetAlbumi=entiteti.idEntiteti
    JOIN izvodjaci ON izvodjaci.idIzvodjaci= albumi.idIzvodjacAlbumi
    WHERE entitetNaziv='{$imeEnt}' 
    GROUP BY albumi.godinaIzdanja 
    HAVING count(albumi.godinaIzdanja) > 0
    ORDER BY albumi.godinaIzdanja ASC";
    $select_godinu_izdanja= mysqli_query($conn, $q);
    
    prikazZastaveEntitet($imeEnt);
    selectZaPrikazAlbuma();
    ?>

    <script>
        document.getElementById("<?php echo $redosledAlbuma; ?>").setAttribute("selected", "selected");
    </script>
    
    <?php 
    while($row= mysqli_fetch_assoc($select_godinu_izdanja))
    {
        $idAlbum= $row["idAlbum"];
        $godinaIzdanja= $row["godinaIzdanja"];
        $brojAlbuma= $row["brAlb"];
        $idDrzave= $row["idDrzave"];
        $nazivDrzave= $row["nazivDrzave"];
        $entitetNaziv= $row["entitetNaziv"];

        ?>
        <div class="albumiPoGodini">
            <h1><?php echo $godinaIzdanja; ?></h1>
            <?php
            $q2= "SELECT * FROM albumi JOIN izvodjaci ON izvodjaci.idIzvodjaci= albumi.idIzvodjacAlbumi 
            JOIN drzave ON albumi.drzavaAlbumi=drzave.idDrzave
            JOIN entiteti ON entiteti.idEntiteti=albumi.entitetAlbumi 
            WHERE godinaIzdanja='{$godinaIzdanja}' AND entitetNaziv='{$entitetNaziv}'
            ORDER BY albumi.idAlbum ASC";
            $select_godinu= mysqli_query($conn, $q2);

            while($row= mysqli_fetch_assoc($select_godinu))
            {
                $idAlbum= $row["idAlbum"];
                $idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
                $idIzvodjac2= $row["idIzvodjac2"];
                $idIzvodjac3= $row["idIzvodjac3"];
                $nazivAlbuma= $row["nazivAlbuma"];
                $godinaIzdanja= $row["godinaIzdanja"];
                $slikaAlbuma= $row["slikaAlbuma"];
                $drzavaAlbumi= $row["drzavaAlbumi"];
                $entitetAlbumi= $row["entitetAlbumi"];
                $izvodjacMaster= $row["izvodjacMaster"];

                /*-------------------- Početak Izdavači --------------------*/
                $qIzdavaci = "SELECT izdavaciNaziv FROM izdavaci 
                JOIN albumi_izdavaci ON albumi_izdavaci.idIzdavaci= izdavaci.idIzdavaci
                JOIN albumi ON albumi.idAlbum= albumi_izdavaci.idAlbum
                WHERE albumi_izdavaci.idAlbum='{$idAlbum}'";

                $rezIzdavaci = mysqli_query($conn, $qIzdavaci);

                $izdavaciHTML = [];

                while ($izd = mysqli_fetch_assoc($rezIzdavaci)) 
                {
                    $izdavaciNaziv = $izd['izdavaciNaziv'];
                    $izdavaciHTML[]= $izdavaciNaziv;
                
                }//end while
                /*-------------------- end izdavači --------------------*/

                $cleanIzvodjacMaster= konverzijaLatinica($izvodjacMaster);
                $cleanNazivAlbuma= konverzijaLatinica($nazivAlbuma);
                $cleanIzvodjacMaster= konverzijaLatinica(removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($cleanIzvodjacMaster))));
                $cleanNazivAlbuma= konverzijaLatinica(removeSerbianLetters(cleanAlbum($cleanNazivAlbuma)));
                
                ?>
                <div class="myCard myCard-size1">
                    <a href="oalbumu.php?izv=<?php echo $idIzvodjacAlbumi."&album=".$idAlbum."&naziv=".$cleanIzvodjacMaster."-".$cleanNazivAlbuma; ?>" >
                        <img loading="lazy" src="images/albumi/<?php echo $slikaAlbuma; ?>" alt="<?php echo izabraniIzvodjac($izvodjacMaster, $idIzvodjac2, $idIzvodjac3) . " - " . $nazivAlbuma; ?>" title="<?php echo izabraniIzvodjac($izvodjacMaster, $idIzvodjac2, $idIzvodjac3) . " - " . $nazivAlbuma; ?>">
                        <div class="myCard-body">
                            <h5 class="myCard-title"><?php echo izabraniIzvodjac($izvodjacMaster, $idIzvodjac2, $idIzvodjac3) . " - " . $nazivAlbuma; ?></h5>
                            <p class="myCard-text2"><?php echo implode(", ", $izdavaciHTML); ?></p>
                        </div><!-- end .myCard-body -->   
                    </a>
                </div><!-- end .myCard -->
                
                <?php
            }/**** end while 2 ****/
            ?> 
            <br>
            <p>Trenutno dodatih albuma za <?php echo $godinaIzdanja . ". godinu je " .  $brojAlbuma;  ?></p><br>
        </div><!-- end .albumiPoGodini -->
        <hr>
        <?php
    }/**** end while 1 ****/
}//sviAlbumiPoEntPoGodini()

//********************************* Metoda pozvana u ovom fajlu u funkciji redosledPoEntitetima *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Metoda koja uzima vrijednost izabrane opcije *********************************//
function sviAlbumiAbecednoEnt($imeEnt)
{
    $redosledAlbuma= $_POST["redosledAlbuma"];

    if($redosledAlbuma=="nasumicno"){
        $redosledPrikaza= "RAND()";
        prikazIzabranihAlbumaEnt($imeEnt, $redosledAlbuma, $redosledPrikaza);
    }else if($redosledAlbuma=="abecednoAlbum"){
        $redosledPrikaza= "nazivAlbuma";
        prikazIzabranihAlbumaEnt($imeEnt, $redosledAlbuma, $redosledPrikaza);
    }else if($redosledAlbuma=="abecednoIzvodjac"){
        $redosledPrikaza="izvodjacMaster";
        prikazIzabranihAlbumaEnt($imeEnt, $redosledAlbuma, $redosledPrikaza);
    }//end if else if()
}//end sviAlbumiAbecednoEnt()

//********************************* Metoda pozvana u ovom fajlu u funkciji redosledPoEntitetima *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Prikaz albuma po izabranom entitetu sortirano po izabranoj opciji *********************************//
function prikazIzabranihAlbumaEnt($imeEnt, $redosledAlbuma, $redosledPrikaza)
{
    global $conn;
    
    $q= "SELECT * FROM entiteti JOIN albumi ON albumi.entitetAlbumi=entiteti.idEntiteti
    JOIN izvodjaci ON izvodjaci.idIzvodjaci= albumi.idIzvodjacAlbumi
    WHERE entitetNaziv='{$imeEnt}' ORDER BY $redosledPrikaza";
    $select_albumi= mysqli_query($conn, $q);

    prikazZastaveEntitet($imeEnt);
    ?>
    <div class="slikeAlbuma">
        <?php 
        selectZaPrikazAlbuma();
        ?>

        <script>
            document.getElementById("<?php echo $redosledAlbuma; ?>").setAttribute("selected", "selected");
        </script>

        <?php
        while($row=mysqli_fetch_array($select_albumi))
        {
            $idAlbum= $row["idAlbum"];
            $idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
            $idIzvodjac2= $row["idIzvodjac2"];
            $idIzvodjac3= $row["idIzvodjac3"];
            $nazivAlbuma= $row["nazivAlbuma"];
            $godinaIzdanja= $row["godinaIzdanja"];
            $slikaAlbuma= $row["slikaAlbuma"];
            $drzavaAlbumi= $row["drzavaAlbumi"];
            $entitetAlbumi= $row["entitetAlbumi"];
            $izvodjacMaster= $row["izvodjacMaster"];
            $idEntiteti= $row["idEntiteti"];
            $entitetNaziv= $row["entitetNaziv"];
            //$entitet1= $row["entitet1"];
            //$entitet2= $row["entitet2"];

            /*-------------------- Početak Izdavači --------------------*/
            $qIzdavaci = "SELECT izdavaciNaziv FROM izdavaci 
            JOIN albumi_izdavaci ON albumi_izdavaci.idIzdavaci= izdavaci.idIzdavaci
            JOIN albumi ON albumi.idAlbum= albumi_izdavaci.idAlbum
            WHERE albumi_izdavaci.idAlbum='{$idAlbum}'";

            $rezIzdavaci = mysqli_query($conn, $qIzdavaci);

            $izdavaciHTML = [];

            while ($izd = mysqli_fetch_assoc($rezIzdavaci)) 
            {
                $izdavaciNaziv = $izd['izdavaciNaziv'];
                $izdavaciHTML[]= $izdavaciNaziv;
            
            }//end while
            /*-------------------- end izdavači --------------------*/

            $cleanIzvodjacMaster= konverzijaLatinica($izvodjacMaster);
            $cleanNazivAlbuma= konverzijaLatinica($nazivAlbuma);
            $cleanIzvodjacMaster= konverzijaLatinica(removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($cleanIzvodjacMaster))));
            $cleanNazivAlbuma= konverzijaLatinica(removeSerbianLetters(cleanAlbum($cleanNazivAlbuma)));
            ?>
            <div class="myCard myCard-size2">
                <a href="oalbumu.php?izv=<?php echo $idIzvodjacAlbumi."&album=".$idAlbum."&naziv=".$cleanIzvodjacMaster."-".$cleanNazivAlbuma; ?>" >
                    <img loading="lazy" class="myCard-img-top" src="images/albumi/<?php echo $slikaAlbuma; ?>" alt="<?php echo izabraniIzvodjac($izvodjacMaster, $idIzvodjac2, $idIzvodjac3) . " - " . $nazivAlbuma; ?>" title="<?php echo izabraniIzvodjac($izvodjacMaster, $idIzvodjac2, $idIzvodjac3) . " - " . $nazivAlbuma; ?>">
                    <div class="myCard-body">
                        <h5 class="myCard-title"><?php echo izabraniIzvodjac($izvodjacMaster, $idIzvodjac2, $idIzvodjac3) . " - " . $nazivAlbuma; ?></h5>
                        <p class="myCard-text1"><span class="godinaIzd"><?php echo $godinaIzdanja . "</span>"; ?></p>
                        <p class="myCard-text2"><?php echo implode(", ", $izdavaciHTML); ?></p>
                    </div><!-- end .myCard-body -->    
                </a>
            </div><!-- end .myCard -->
            <?php
        }//end while
        ?>
    </div><!-- end .slikeAlbuma -->
    <?php
}//prikazIzabranihAlbumaEnt()
//********************************* Metoda pozvana u ovom fajlu u funkciji sviAlbumiAbecednoEnt() *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Prikaz redosleda svih albuma po izabranom entitetu *********************************//
function redosledPoEntitetima($ent)
{
    if(isset($_POST["redosledAlbuma"]))
    {
        $redosledAlbuma=$_POST["redosledAlbuma"];
        ?>
        <script>
            document.getElementById("prazni").removeAttribute("selected");
        </script>
        <?php
        if($redosledAlbuma=="poGodinama"){
            ?> 
            <script>
                document.getElementById("imeEntiteta").remove();
                document.getElementsByClassName("slikeAlbuma")[0].remove();
                document.getElementById("<?php echo $redosledAlbuma; ?>").setAttribute("selected", "selected");
            </script>
            <?php
            sviAlbumiPoEntPoGodini($ent, $redosledAlbuma);
        }else if($redosledAlbuma=="abecednoAlbum"){
            ?> 
            <script>
                document.getElementById("imeEntiteta").remove();
                document.getElementsByClassName("slikeAlbuma")[0].remove(); 
            </script>
            <?php
            sviAlbumiAbecednoEnt($ent);
        }else if($redosledAlbuma=="abecednoIzvodjac"){
            ?> 
            <script>
                document.getElementById("imeEntiteta").remove();
                document.getElementsByClassName("slikeAlbuma")[0].remove();
            </script>
            <?php
            sviAlbumiAbecednoEnt($ent);
        }//end if else if()
    }//end if(isset($_POST["redosledAlbuma"]))
}//end redosledPoEntitetima()

//********************************* Metoda pozvana u drzava.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Metoda za prikaz zastave i naziv entiteta *********************************//
function prikazZastaveEntitet($imeEnt)
{
    global $conn;
    $q0= "SELECT * FROM entiteti WHERE entitetNaziv='{$imeEnt}'";
    $select_zastavu_drzave= mysqli_query($conn,$q0);
    $red= mysqli_fetch_assoc($select_zastavu_drzave);
    
    $entDrzava= $red["entitetNaziv"];
    $zastavaEnt= $red["zastavaEnt"];

    echo  "<h1 class='inline-block drzava' id='imeEntiteta'><div class='zastava'><img class='zastava' src='images/zastave/{$zastavaEnt}' alt='{$entDrzava}' title='{$entDrzava}'></div>  $entDrzava</h1>";
}//end prikazZastaveEntitet()

//********************************* Metoda pozvana u ovom fajlu u funkcijama sviAlbumiPoEnt, sviAlbumiPoEntPoGodini prikazIzabranihAlbumaEnt *********************************//

//--------------------------------------------------------------------------------------------------------------------------------