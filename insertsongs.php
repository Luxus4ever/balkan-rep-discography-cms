<?php
require_once "config/bootstrap.php";
include "classes/detaljiAlbum.class.php";
include "classes/userOnline.class.php";
include "classes/insertData-classes/insertAlbumSongs.class.php";
include "functions/master.func.php";
include "header.php";
include "classes/insertData-classes/insertDataPanel.class.php";

headerPutanja();


@$profil= $_GET["username"];
@$lid=$_GET["lid"];
@$idAlb=$_GET["idAlb"];
@$sesId= $_SESSION["idKorisnici"];
@$sesVrijeme= $_SESSION["vrijeme"];
@$statusKorisnika= $_SESSION["statusKorisnika"];
@$usernameSession= $_SESSION["username"];

if(empty($sesId) || empty($usernameSession) || empty($sesVrijeme) || empty($statusKorisnika)){
    zabranjenPristupBezValidacije($sesId);
}else{
    ?>
    <div class="container-fluid slikeAlbumaPregled">
        <div class="row">
            <?php
            $adEdPan= new insertDataPanel();
            $adEdPan->leftSideUnosPanel($sesId, $idIzv="");

            $insSong= new insertSongs();
            $insSong->dodajPjesme($idAlb); 
            ?>    
       </div><!-- end row --> 
    </div><!-- end container-fluid --> 
    <?php
}//end if else()

include "footer.php";
footerPutanja();