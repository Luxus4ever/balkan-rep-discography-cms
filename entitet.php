<?php
require_once "config/bootstrap.php";
include "classes/detaljiAlbum.class.php";
include "functions/master.func.php";
include "header.php";

headerPutanja();

$ent= str_replace("-", " ", $_GET["ent"]);

?>
<div id="wrapper">
  <!-- Prikaz albuma -->
  <div class="albumPrikaz">
    <?php 
    $drzAlb= new albumDetalji();
    sviAlbumiPoEnt($ent);
    ?>              
  </div> <!-- kraj albumPrikaz -->
<?php
include "footer.php";
footerPutanja();