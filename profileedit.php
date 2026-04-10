<?php
require_once "config/bootstrap.php";
include "classes/detaljiAlbum.class.php";
include_once "functions/master.func.php";
include "header.php";

headerPutanja();

?>
<div id="wrapper">
    <div class="slikeAlbumaPregled sredina">
        <main>
            <?php
            @$profil= $_GET["username"];
            @$idIzGETa= $_GET["lid"];;
            @$statusKorisnika= $_SESSION["statusKorisnika"];
            @$sesId= $_SESSION["idKorisnici"];
            @$username= $_SESSION["username"];

            if($sesId===$idIzGETa){
                editUser($profil, $sesId);
            }else{
                //echo "<h1>NEMATE PRAVA PRISTUPA OVOM DIJELU!!!</h1>";
                zabranjenPristupBezValidacije($sesId);
            }

            if(empty($profil) || empty($idIzGETa)){
                    echo ("<h1>Ne možete da pristupite ovom dijelu bez validnih podataka.</h1>");
                }

            ?>
        </main>
    </div> <!-- kraj .slikeAlbumaPregled -->
</div> <!-- kraj #wrapper -->
<?php
include "footer.php";
footerPutanja();