<?php
require_once "config/bootstrap.php";
include "header.php";
include "functions/master.func.php";
include "classes/detaljiAlbum.class.php";

headerPutanja();

?>
<div id="wrapper">
	<?php 
	$detAlb= new albumDetalji();
	$detAlb->poIzvodjacima();
	
include "footer.php";
footerPutanja();