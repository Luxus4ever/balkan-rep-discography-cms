<?php
require_once "config/bootstrap.php";
include "header.php";

headerPutanja();
?>
<div id="wrapper">
	<?php 
	$kategorija= str_replace("+", " ", $_GET["kategorija"]);
	@$kategorijaId= $_GET["idKategorije"];

	include "classes/izdavaci.class.php";
	$detLab= new detaljiLabel();
	?>
	<div class="albumPrikaz">
		<h1 class="drzava"><span class="boja">Kategorija:</span> <?php echo $kategorija; ?></h1>
		<?php
		$detLab->aboutKategorija($kategorijaId);
		echo "<hr><h2 class='drzava'>Albumi u kategoriji $kategorija:</h2>";
		$detLab->albumiKategorije($kategorijaId);
		?>
	</div><!-- end .albumPrikaz -->
<?php
include "footer.php";
footerPutanja();