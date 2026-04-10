<?php
require_once "config/bootstrap.php";
include "classes/detaljiAlbum.class.php";
include "functions/master.func.php";
include "header.php";

headerPutanja();
?>
<div id="wrapper">
    <!-- Prikaz albuma -->
    <div class="albumPrikaz">
        <?php
            selectZaPrikazSinglova();
            redosledSinglova();
            ?>     
    </div> <!-- kraj albumPrikaz -->
<?php
include "footer.php";
footerPutanja();