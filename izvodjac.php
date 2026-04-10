<?php
require_once "config/bootstrap.php";
include "header.php";
include "classes/detaljiIzvodjac.class.php";
include "classes/detaljiAlbum.class.php";
include "functions/master.func.php";

headerPutanja();

?>
<div id="wrapper">

<!-- Prikaz detalja o izvođaču -->
<div class="slikeAlbumaPregled">
	<?php                 
	$artistId= $_GET["idIzv"];
	$artistID2= (empty($artistId)) ? null: $artistId;
	$artist= removeSpecialLetters(str_replace("+", " ", $_GET["izvodjac"]));
	//$artist3=reverseSerbianLetters($artist);
	$artist3=reverseRemoveSpecialLetters($artist);

	//<!-- Info albuma -->
	$detIzv= new izvodjacDetalji();
	$detAlb= new albumDetalji();
	?>
	<div class="tekstP">
		<?php
		nadjiIzvodjaca($artist3);
		
		if(!empty($artistId)){
			$detIzv->biografija($artistId);
		}
		
		$detAlb->prikazSinglovaIzvodjaca($artist3);
		?>
	</div> <!-- kraj tekstP -->		
</div><!-- kraj .slikeAlbumaPregled -->
<?php
include "footer.php";
footerPutanja();