<?php
require_once "config/bootstrap.php";
include "classes/detaljiAlbum.class.php";
include "functions/master.func.php";
include "header.php";

headerPutanja();

@$imeDrzave= str_replace("-", " ", $_GET["nazivdrzave"]);
@$ent= str_replace("-", " ", $_GET["ent"]);

?>
<div id="wrapper">
    <!-- Prikaz albuma -->
    <div class="albumPrikaz">
        <?php
        if($imeDrzave==TRUE)
        {
            sviAlbumiPoDrzavi($imeDrzave);
            redosledPoDrzavama($imeDrzave);
            
        }else if($ent==TRUE)
            {
            sviAlbumiPoEnt($ent);
            redosledPoEntitetima($ent);
            }
            ?>     
    </div> <!-- kraj albumPrikaz -->
<?php
include "footer.php";
footerPutanja();