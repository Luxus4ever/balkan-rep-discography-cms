<?php
//FUNKCIJE U OVOM FAJLU
//sviSingloviPoGodini (Prikaz svih singlova po godini)
//selectZaPrikazSinglova (prikaz forme za biranje redosleda prikaza singlova)
//sviSingloviAbecedno (uzima vrijednost izabrane opcije iz select menija)
//redosledSinglova (prikaz redosleda u padajućem meniju svih singlova)
//prikazIzabranihSinglova (Prikaz redolseda singlova singlova po izabranoj opciji)

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Prikaz svih singlova po godini *********************************//
function sviSingloviPoGodini()
{
    global $conn;

    // Za SVAKI singl, pridruži i broj singlova u njegovoj godini
    $q = "SELECT 
            s.idSinglovi,
            s.singlNaziv,
            s.godinaIzdanjaSingl,
            s.singleFeat,
            s.singleIzvodjaci,
            t.brSinglova
        FROM singlovi s
        JOIN (
            SELECT godinaIzdanjaSingl, COUNT(*) AS brSinglova
            FROM singlovi
            GROUP BY godinaIzdanjaSingl
        ) t ON t.godinaIzdanjaSingl = s.godinaIzdanjaSingl
        ORDER BY s.godinaIzdanjaSingl ASC, s.idSinglovi ASC
    ";

    $rs = mysqli_query($conn, $q);
    if (!$rs) {
        echo "<p class='text-danger'>Greška u upitu: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
        return;
    }//end if()

    $trenutnaGodina = null;
    $otvorenDiv = false;

    while ($row = mysqli_fetch_assoc($rs)) 
    {
        $idSinglovi            = (int)$row["idSinglovi"];
        $singlNaziv            = $row["singlNaziv"];
        $godinaIzdanjaSingl    = $row["godinaIzdanjaSingl"];
        $singleFeat        = $row["singleFeat"];
        $brojSinglova          = (int)$row["brSinglova"];
        $singleIzvodjaci    = $row["singleIzvodjaci"];

        // Novi blok kad se promijeni godina
        if ($godinaIzdanjaSingl !== $trenutnaGodina) 
        {
            if ($otvorenDiv) {
                echo "</div><!-- end .albumiPoGodini -->";
                echo '<hr class="hrLinija1">';
            }//end if()
            echo '<div class="albumiPoGodini">';
            echo '<h1 style="color:goldenrod">' . htmlspecialchars($godinaIzdanjaSingl) . '</h1>';
            echo '<p>Trenutno dodatih singlova za ' . htmlspecialchars($godinaIzdanjaSingl) . '. godinu je ' . $brojSinglova . "</p><br>";

            $trenutnaGodina = $godinaIzdanjaSingl;
            $otvorenDiv = true;
        }//end if()

        // Red singla
        echo '<a class="clickLink mb-1" href="singlovi.php?singl=' . $idSinglovi . '">';
        if (empty($singleFeat) && !empty($singleIzvodjaci)) {
            echo htmlspecialchars($singleIzvodjaci . " - " . $singlNaziv);
        } elseif (empty($singleIzvodjaci) && !empty($singleFeat)) {
            echo htmlspecialchars($singlNaziv . " - " . $singleFeat);
        } else {
            // oba popunjena ili oba prazna – sastavi pažljivo
            $komadi = array_filter(
                [$singleIzvodjaci, $singlNaziv, $singleFeat],
                function ($v) {
                    return $v !== null && $v !== "";
                }
            );

            echo htmlspecialchars(implode(" - ", $komadi));
        }
        echo "</a><br>";
    }//end while

    if ($otvorenDiv) {
        echo "</div><!-- end .albumiPoGodini -->";
    }
}//end sviSingloviPoGodini()


//********************************* Metoda pozvana u ovom fajlu funkciji redosledPoDrzavama *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Metoda za prikaz forme za izbor redosleda singlova *********************************//
function selectZaPrikazSinglova()
{
    ?>
    <div class="sredina">
        <form method="POST" action="">
            <div class="col-auto">
                Izaberi redosled prikaza singlova 
                <select class="form-control" id="count" name="redosledSinglova" onchange="this.form.submit()">
                    <option class="form-control" id="prazni" value="" disabled selected>--Izaberi prikaz--</option>
                    <option class="" id="abecednoIzvodjac" value="abecednoIzvodjac">Abecedno po izvodjačima</option>
                    <option class="" id="abecednoSingl" value="abecednoSingl">Abecedno po singlovima</option>
                    <option class="" id="poGodinama" value="poGodinama">Po godinama</option>
                </select>
            </div><!-- end .col-auto -->
        </form>
    </div><!-- end .sredina -->
    <?php
}//end selectZaPrikazSinglova()

//********************************* Metoda pozvana u fajlu svisinglovi.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Uzima vrijednost izabrane opcije iz select menija *********************************//
function sviSingloviAbecedno()
{
    $redosledSinglova= $_POST["redosledSinglova"];

    //print_r("$redosledSinglova<hr>");
    if($redosledSinglova=="abecednoSingl"){
        $redosledPrikaza= "singlNaziv";
        prikazIzabranihSinglova($redosledSinglova, $redosledPrikaza);
    }else if($redosledSinglova=="abecednoIzvodjac"){
        $redosledPrikaza="singleIzvodjaci";
        prikazIzabranihSinglova($redosledSinglova, $redosledPrikaza);
    }//end if else if()
    
}//end sviAlbumiAbecedno()

//********************************* Metoda pozvna u ovom fajlu u funkciji redosledSinglova *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Prikaz redosleda u padajućem meniju svih singlova *********************************//
function redosledSinglova()
{
    if(isset($_POST["redosledSinglova"]))
    {
        $redosledAlbuma=$_POST["redosledSinglova"];
        ?>
        <script>
            document.getElementById("prazni").removeAttribute("selected");
        </script>
        <?php
        if($redosledAlbuma=="poGodinama"){
            ?> 
            <script>
                document.getElementById("<?php echo $redosledAlbuma; ?>").setAttribute("selected", "selected");
            </script>
            <?php
            sviSingloviPoGodini($redosledAlbuma);
        }else if($redosledAlbuma=="abecednoSingl"){
            ?> 
            <script>
                
            </script>
            <?php
            sviSingloviAbecedno();
        }else if($redosledAlbuma=="abecednoIzvodjac"){
            ?> 
            <script>
                
            </script>
            <?php
            sviSingloviAbecedno();
        }//end if else if()
    }//end if(isset($_POST["redosledAlbuma"]))
}//end redosledPoDrzavama()
//********************************* Metoda pozvana u svisinglovi.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

/********************************* Prikaz redolseda singlova singlova po izabranoj opciji *********************************/

function prikazIzabranihSinglova($redosledAlbuma, $redosledPrikaza)
{
    global $conn;
    $q= "SELECT * FROM singlovi 
     ORDER BY $redosledPrikaza";
    $select_singlovi= mysqli_query($conn, $q);
    //print_r($q);

    ?>
    <div class="slikeAlbuma">
        <script>
            document.getElementById("<?php echo $redosledAlbuma; ?>").setAttribute("selected", "selected");
        </script>

        <?php
        while($row=mysqli_fetch_array($select_singlovi))
        {
            $idSinglovi= $row["idSinglovi"];
            $singlNaziv= $row["singlNaziv"];
            $godinaIzdanjaSingl= $row["godinaIzdanjaSingl"];
            $singleFeat= $row["singleFeat"];
            $singleIzvodjaci= $row["singleIzvodjaci"];

            if($redosledAlbuma== "abecednoIzvodjac"){
                ?>
                <a class="clickLink" href="singlovi.php?singl=<?php echo $idSinglovi; ?>" >
                    <?php echo "$singleIzvodjaci - $singlNaziv $singleFeat"; ?>
                </a><br>
                <?php
            }else if($redosledAlbuma== "abecednoSingl"){
                ?>
                <a class="clickLink" href="singlovi.php?singl=<?php echo $idSinglovi; ?>" >
                    <?php echo "$singlNaziv - $singleIzvodjaci $singleFeat"; ?>
                </a><br>
                <?php
            }//end if(redosled)
        }//end while
        ?>
    </div><!-- end .slikeAlbuma -->
    <?php
}//end prikazIzabranihAlbuma()

/********************************* Metoda pozvana u funkciji svisingloviabecedno() *********************************/




