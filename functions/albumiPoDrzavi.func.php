<?php
//FUNKCIJE U OVOM FAJLU
//sviAlbumiPoDrzavi (Prikaz svih albuma po izabranoj državi)
//sviAlbumiPoGodiniPoDrzavi Prikaz sviha obuma po godini po izabranoj državi)
//sviAlbumiAbecedno (Metoda koja uzima vrijednost izabrane opcije option menija)
//prikazIzabranihAlbuma (Prikaz albuma po izabranoj opciji select menija)
//redosledPoDrzavama (Prikaz redosleda svih albuma po izabranoj državi)
//selectZaPrikazAlbuma (Prikaz Select forme za izbor prikaza redosleda albuma)
//prikazZastave (prikaz zastave i naziva države po državama)


//********************************* Prikaz svih albuma po izabranoj državi *********************************//
function sviAlbumiPoDrzavi($imeDrzave)
{
    global $conn;
    $q = "SELECT * FROM drzave 
            JOIN albumi ON albumi.drzavaAlbumi = drzave.idDrzave
            JOIN izvodjaci ON izvodjaci.idIzvodjaci = albumi.idIzvodjacAlbumi
            WHERE nazivDrzave = '{$imeDrzave}' ORDER BY RAND()";

    $select_albumi = mysqli_query($conn, $q);

    // Prikaz zastave
    prikazZastave($imeDrzave);

    ?>
    <div class="slikeAlbuma">
        <?php 
        selectZaPrikazAlbuma();
        
        while ($row = mysqli_fetch_array($select_albumi)) 
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
            $idDrzave= $row["idDrzave"];
            $nazivDrzave= $row["nazivDrzave"];
            $entitet1= $row["entitet1"];
            $entitet2= $row["entitet2"];

            

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

           

            // Čišćenje podataka (ako je potrebno)
            $cleanIzvodjacMaster = konverzijaLatinica(removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($izvodjacMaster))));
            $cleanNazivAlbuma = konverzijaLatinica(removeSerbianLetters(cleanAlbum($nazivAlbuma)));

            ?>
            <div class="myCard myCard-size2">
                <a href="oalbumu.php?izv=<?php echo $idIzvodjacAlbumi."&album=".$idAlbum."&naziv=".$cleanIzvodjacMaster."-".$cleanNazivAlbuma; ?>">
                    <img loading="lazy" class="myCard-img-top" src="images/albumi/<?php echo $slikaAlbuma; ?>" alt="<?php echo izabraniIzvodjac($izvodjacMaster, $idIzvodjac2, $idIzvodjac3) . " - " . $nazivAlbuma; ?>" title="<?php echo izabraniIzvodjac($izvodjacMaster, $idIzvodjac2, $idIzvodjac3) . " - " . $nazivAlbuma; ?>">
                    <div class="myCard-body">
                        <h5 class="myCard-title"><?php echo izabraniIzvodjac($izvodjacMaster, $idIzvodjac2, $idIzvodjac3) . " - " . $nazivAlbuma; ?></h5>
                        <p class="myCard-text1"><span class="godinaIzd"><?php echo $godinaIzdanja . "</span>"; ?></p>
                        <!-- Prikazivanje svih izdavača -->
                        <p class="myCard-text2"><?php echo implode(", ", $izdavaciHTML); ?></p>
                    </div><!-- end .myCard-body -->    
                </a>
            </div><!-- end .myCard -->
            <?php
        }//end while
        ?>
    </div><!-- end .slikeAlbuma -->
<?php
}//end sviAlbumiPoDrzavi()


//********************************* Metoda pozvana u drzava.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Prikaz svih albuma po godini po izabranoj državi *********************************//
function sviAlbumiPoGodiniPoDrzavi($imeDrzave, $redosledAlbuma)
{
    global $conn;
    $q= "SELECT drzave.idDrzave, drzave.nazivDrzave, albumi.godinaIzdanja, count(albumi.godinaIzdanja) AS brAlb 
    FROM drzave JOIN albumi ON albumi.drzavaAlbumi=drzave.idDrzave
        JOIN izvodjaci ON izvodjaci.idIzvodjaci= albumi.idIzvodjacAlbumi
        WHERE nazivDrzave='{$imeDrzave}'
        GROUP BY albumi.godinaIzdanja 
        HAVING count(albumi.godinaIzdanja) > 0
        ORDER BY albumi.godinaIzdanja ASC";
    $select_godinu_izdanja= mysqli_query($conn, $q);

    prikazZastave($imeDrzave);
    selectZaPrikazAlbuma();
    ?>

    <script>
        document.getElementById("<?php echo $redosledAlbuma; ?>").setAttribute("selected", "selected");
    </script>

    <?php 
    while($row= mysqli_fetch_assoc($select_godinu_izdanja))
    {
        $godinaIzdanja= $row["godinaIzdanja"];
        $brojAlbuma= $row["brAlb"];
        $idDrzave= $row["idDrzave"];
        $nazivDrzave= $row["nazivDrzave"];
        ?>
        <div class="albumiPoGodini">
            <h1><?php echo $godinaIzdanja; ?></h1>
            <?php
            $q2= "SELECT * FROM albumi JOIN izvodjaci ON izvodjaci.idIzvodjaci= albumi.idIzvodjacAlbumi 
            JOIN drzave ON albumi.drzavaAlbumi=drzave.idDrzave
            WHERE godinaIzdanja='{$godinaIzdanja}' AND nazivDrzave='{$nazivDrzave}'
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
                $cleanIzvodjacMaster= removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($cleanIzvodjacMaster)));
                $cleanNazivAlbuma= removeSerbianLetters(cleanAlbum($cleanNazivAlbuma));    
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
        <hr class="hrLinija1">
        <?php
    }/**** end while 1 ****/
}//end sviAlbumiPoGodiniPoDrzavi()

//********************************* Metoda pozvana u ovom fajlu funkciji redosledPoDrzavama *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Uzima vrijednost izabrane opcije abecedno po državi *********************************//
function sviAlbumiAbecedno($imeDrzave)
{
    $redosledAlbuma= $_POST["redosledAlbuma"];

    if($redosledAlbuma=="nasumicno"){
        $redosledPrikaza= "RAND()";
        prikazIzabranihAlbuma($imeDrzave, $redosledAlbuma, $redosledPrikaza);
    }else if($redosledAlbuma=="abecednoAlbum"){
        $redosledPrikaza= "nazivAlbuma";
        prikazIzabranihAlbuma($imeDrzave, $redosledAlbuma, $redosledPrikaza);
    }else if($redosledAlbuma=="abecednoIzvodjac"){
        $redosledPrikaza="izvodjacMaster";
        prikazIzabranihAlbuma($imeDrzave, $redosledAlbuma, $redosledPrikaza);
    }//end if else if()
    
}//end sviAlbumiAbecedno()

//********************************* Metoda pozvna u ovom fajlu u funkciji redosledPoDrzavama *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

/********************************* Prikaz albuma po izabranoj državi sortirano po izabranoj opciji *********************************/

function prikazIzabranihAlbuma($imeDrzave, $redosledAlbuma, $redosledPrikaza)
{
    global $conn;
    $q= "SELECT * FROM drzave 
    JOIN albumi ON albumi.drzavaAlbumi=drzave.idDrzave
    JOIN izvodjaci ON izvodjaci.idIzvodjaci= albumi.idIzvodjacAlbumi
    WHERE nazivDrzave='{$imeDrzave}' ORDER BY $redosledPrikaza";
    $select_albumi= mysqli_query($conn, $q);
    
    prikazZastave($imeDrzave);
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
            $idDrzave= $row["idDrzave"];
            $nazivDrzave= $row["nazivDrzave"];
            $entitet1= $row["entitet1"];
            $entitet2= $row["entitet2"];
            //$entitet= $row["entitet"];

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

            $cleanIzvodjacMaster= konverzijaLatinica(removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($izvodjacMaster))));
            $cleanNazivAlbuma= konverzijaLatinica(removeSerbianLetters(cleanAlbum($nazivAlbuma)));
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
}//end prikazIzabranihAlbuma()

/********************************* Metoda pozvana u funkciji svialbumiabecedno() *********************************/

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Prikaz redosleda svih albuma po izabranoj državi *********************************//
function redosledPoDrzavama($imeDrzave)
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
                document.getElementById("imeDrzave").remove();
                document.getElementsByClassName("slikeAlbuma")[0].remove();
                document.getElementById("<?php echo $redosledAlbuma; ?>").setAttribute("selected", "selected");
            </script>
            <?php
            sviAlbumiPoGodiniPoDrzavi($imeDrzave, $redosledAlbuma);
        }else if($redosledAlbuma=="abecednoAlbum"){
            ?> 
            <script>
                document.getElementById("imeDrzave").remove();
                document.getElementsByClassName("slikeAlbuma")[0].remove();
            </script>
            <?php
            sviAlbumiAbecedno($imeDrzave);
        }else if($redosledAlbuma=="abecednoIzvodjac"){
            ?> 
            <script>
                document.getElementById("imeDrzave").remove();
                document.getElementsByClassName("slikeAlbuma")[0].remove();
            </script>
            <?php
            sviAlbumiAbecedno($imeDrzave);
        }//end if else if()
    }//end if(isset($_POST["redosledAlbuma"]))
}//end redosledPoDrzavama()
//********************************* Metoda pozvana u drzava.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Metoda za prikaz forme za izbor redosleda albuma *********************************//
function selectZaPrikazAlbuma()
{
    ?>
    <div class="sredina">
        <form method="POST" action="">
            <div class="col-auto">
                Izaberi redosled prikaza albuma 
                <select class="form-control" id="count" name="redosledAlbuma" onchange="this.form.submit()">
                    <option id="prazni" value="" disabled selected>--Izaberi prikaz--</option>
                    <option id="nasumicno" value="nasumicno">Nasumičan prikaz</option>
                    <option id="poGodinama" value="poGodinama">Po godinama</option>
                    <option id="abecednoAlbum" value="abecednoAlbum">Abecedno po albumu</option>
                    <option id="abecednoIzvodjac" value="abecednoIzvodjac">Abecedno po izvodjaču</option>
                </select>
            </div><!-- end .col-auto -->
        </form>
    </div><!-- end .sredina -->
    <?php
}//end selectZaPrikazAlbuma()

//********************************* Metoda pozvana u ovom fajlu u funkcijama sviAlbumiPoDrzavi, sviAlbumiPoGodinamaDrzavi, prikazIzabranihAlbuma *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Metoda za prikaz zastave i naziva države *********************************//

function prikazZastave($imeDrzave)
{
    global $conn;
    $q0= "SELECT * FROM drzave WHERE nazivDrzave='{$imeDrzave}'";
    $select_zastavu_drzave= mysqli_query($conn,$q0);
    $red= mysqli_fetch_assoc($select_zastavu_drzave);
    
    $imeDrz= $red["nazivDrzave"];
    $zastavaDrzave= $red["zastavaDrzave"];

    echo  "<h1 class='inline-block drzava' id='imeDrzave'><div class='zastava'><img class='zastava' src='images/zastave/{$zastavaDrzave}' alt='{$imeDrz}' title='{$imeDrz}'></div>  $imeDrz</h1>";
}//end prikazZastave()

//********************************* Metoda pozvana u ovom fajlu  u funkcijama sviAlbumiPoDrzavi, sviAlbumiPoGodinamaDrzavi, prikazIzabranihAlbuma *********************************//

//--------------------------------------------------------------------------------------------------------------------------------