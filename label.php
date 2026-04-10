<?php
require_once "config/bootstrap.php";
include "header.php";

headerPutanja();
?>
<div id="wrapper">
	<?php 
	$izdavac= str_replace("-", " ", $_GET["izdavac"]);
	@$izdavacId= $_GET["idIzdavac"];

	include "classes/izdavaci.class.php";
	$detLab= new detaljiLabel();
	?>
	<div class="albumPrikaz">
		<h1 class="drzava"><span class="boja">Izdavač:</span> <?php echo $izdavac; ?></h1>
		<?php
		$detLab->aboutLabel($izdavacId);
		echo "<h2 class='drzava'>Albumi izdavača $izdavac:</h2>";
		$detLab->izdavaci($izdavacId);
		?>
	</div><!-- end .albumPrikaz -->
<?php
include "footer.php";
footerPutanja();