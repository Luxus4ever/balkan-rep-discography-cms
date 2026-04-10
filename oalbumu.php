<?php
require_once "config/bootstrap.php";
include "header.php";
include "classes/ratings.class.php";
include "functions/master.func.php";
include_once "classes/streams.class.php";
include "classes/detaljiAlbum.class.php";
include "classes/pjesme.class.php";


global $conn;

// ---------------------- AJAX OBRADA FAVORITA ---------------------- //
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $detAlb = new albumDetalji();

    if (isset($_POST["odabranoId"]) && isset($_POST["album"])) {
        $detAlb->unesiOmiljeniAlbum($_POST["album"]);
        echo "✅ Album dodat u favorite";
        exit;
    }

    if (isset($_POST["action"]) && $_POST["action"] === "unchecked" && isset($_POST["albumId"])) {
        $detAlb->obrisiOmiljeniAlbum($_POST["albumId"]);
        echo "❌ Album uklonjen iz favorite";
        exit;
    }
}
// ------------------------------------------------------------------ //



headerPutanja();
?>
<div id="wrapper">
    
<?php 
$izvodjacId = $_GET['izv'] ?? null;
$albumId = $_GET["album"] ?? null;
$naziv = $_GET["naziv"] ?? null;
$lid = $_SESSION["idKorisnici"] ?? null;
$statusId = $_SESSION["statusKorisnika"] ?? null;

if (is_numeric($izvodjacId) && is_numeric($albumId)) 
{
    $detAlb = new albumDetalji();
    $detAlb->prikazAlbuma($izvodjacId, $albumId);

    $strm = new streaming();
    $strm->streamoviPrikaz($albumId);

    ?>
    <table>
        <?php
        $pj = new pjesme();
        $pj->listaPjesama($albumId);
        ?>
    </table>
    <?php
    $pj->ostaleNapomene($albumId);
    ?>
    <article class="sredina">
			<div class="uvodTekst">
				<p>Da bi ste podržali naš rad možete nam platiti "virtuelnu" kafu (ili više) klikom na sliku ispod ili u donjem desnom uglu ekrana.</p>

				<div class="sredina"> <a href="https://www.buymeacoffee.com/balkanhiphopr" target="_blank"><img src="https://cdn.buymeacoffee.com/buttons/v2/default-violet.png" alt="Buy Me A Coffee" style="height: 60px !important;width: 217px !important;" ></a>
    			</div><!-- Kraj kafa -->
			</div><!-- end .uvod-tekst -->
		</article>
        <?php
    $rt = new ocjene();

    if ($lid === null) {
        $rt->trenutniRezultat($albumId);
    } else {
        $rt->ocjeni($albumId, $lid);
    }

    if($lid !== null){
        echo '<p class="sredina">Trenutni broj glasova: <span id="brojGlasova">0</span></p>';
    }
    ?>
    </div> <!-- kraj #wrapper -->

    <div class="ostaliAlbumi">
        <?php $detAlb->ostaliAlbumi(); ?>
    </div>

    <?php
    $ses = ($lid === null) ? 
        dodajNapomenu("Morate biti ulogovani da ostavite komentar!") : 
        dodajKomentar($izvodjacId, $albumId, $naziv, $lid, $statusId);

    prikazKomentara($albumId, $lid);
} else {
    zabranjenPristup2("gold", "Vrednosti nisu ispravne!");
}//end if else

include "footer.php";
footerPutanja();
?>
<!-- Početak šoljice za kafu u donjem desnom uglu ekrana -->
    <script data-name="BMC-Widget" data-cfasync="false" src="https://cdnjs.buymeacoffee.com/1.0.0/widget.prod.min.js" data-id="balkanhiphopr" data-description="Support me on Buy me a coffee!" data-message="" data-color="#BD5FFF" data-position="Right" data-x_margin="18" data-y_margin="18"></script>
    <!-- Kraj šoljice za kafu u donjem desnom uglu ekrana -->
