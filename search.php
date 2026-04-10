<?php
require_once "config/bootstrap.php";
getAnonId();
include "classes/detaljiAlbum.class.php";
include "header.php";
include "functions/master.func.php";
headerPutanja();

@$sesId= $_SESSION["idKorisnici"];
?>
<div id="wrapper">
    <div class="slikeAlbumaPregled">
        <form method="POST" action="" name="pretraga" class="pretragaForma">
        <input type="text" name="pretraga" id="pretraga" placeholder="Unesite pojam za pretragu" required="required">
        <input type="submit" id="pretraziButton" name="pretrazi" value="Pretraži">
        </form>
    <?php
    if(isset($_POST["pretrazi"]))
    {
        $start = microtime(true); 

        $pretraga = $_POST["pretraga"] ?? "";
        $pretragaClean = removeSimbols(trim($pretraga));

        if ($pretragaClean === "") {
            echo "<h2 class='naslovPretrage sredina'>Pretraga:</h2>";
            zabranjenPristup3("gold", "Unesite pojam za pretragu.");
        }elseif (mb_strlen($pretragaClean, "UTF-8") < 2) {
        echo "<h2 class='naslovPretrage sredina'>Pretraga:</h2>";
        zabranjenPristup3("gold", "Unesite najmanje 2 slova za pretragu.");
    }else{
            echo "<div class='albumPrikaz'>";
    
            $total = 0;

            $total += poAlbumima($pretragaClean);
            $total += poIzvodjacima($pretragaClean);
            $total += poLabelu($pretragaClean);
            $total += poPjesmama($pretragaClean);
            $total += poSinglovima($pretragaClean);

            if(!empty($sesId)){
                 $total += poProfilimaIzvodjaca($pretragaClean);
                $total += poProfilimaIzdavaca($pretragaClean);
            }
           

            $durationMs = (int) round((microtime(true) - $start) * 1000);

            // scope: ovo je “global search”, ne samo albumi
            logSearch($pretraga, $pretragaClean, 'global', $total, $durationMs);

            // neka tvoja funkcija vrati broj rezultata u $resultsCount
            $resultsCount = $brojRezultata ?? 0;

            //$durationMs = (int) round((microtime(true) - $start) * 1000);

            //logSearch($pretraga, $pretragaClean, 'albumi', $resultsCount, $durationMs);
            echo "</div>";
        }
    }//end if
    ?>
    </div><!-- end .slikeAlbumaPregled -->
<?php
include "footer.php";
footerPutanja();