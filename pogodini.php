<?php
require_once "config/config.php";
//require_once "config/bootstrap.php";
include "header.php";
include "classes/detaljiAlbum.class.php";

headerPutanja();
?>
<div id="wrapper">
	<?php 
	$godina= $_GET["godina"];

	$detAlb= new albumDetalji();
	$detAlb->poGodini($godina);
	
include "footer.php";
footerPutanja();