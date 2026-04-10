<?php
require_once "../config/bootstrap.php";
//Stranica preko koje se vrši izmjena jedne pjesame na izabranom albumu

include "headerAdmin.php";
include "functions/masterAdmin.func.php";
include "classes/masterClasses.class.php";

$adEdPan= new adminEditPanel();
$artEdPan= new artistEditPanel();
$lbEdPan= new labelEditPanel();

@$idIzv= $_GET["idIzv"];
@$idAlb= $_GET["idAlb"];
@$idPjs= $_GET["idPjs"];
@$sesStatusK= $_SESSION["statusKorisnika"];

?>
<div class="container-fluid slikeAlbumaPregled">
    <div class="row">
        <?php
        if(empty($idKorisnici) || empty($username)){
            zabranjenPristupBezValidacije($sesStatusK);
        }else{
                //PRIKAZ LIEJVOG PANELA
                switch($sesStatusK)
                {
                    case "0"; zabranjenPristup($sesStatusK); break; //blokiran
                    case "1"; $adEdPan->leftSidePanel($idKorisnici, $idIzv); break; //admin
                    case "2"; $adEdPan->leftSidePanel($idKorisnici, $idIzv); break; //moderator
                    case "3"; zabranjenPristup($sesStatusK); break; //slušalac
                    case 4; $artEdPan->leftSidePanelArtist($idKorisnici, $idIzv); break; //izvođač
                    case "5"; $lbEdPan->leftSidePanelLabel($idKorisnici, $idIzv, $idAlb); break; //label
                    default; zabranjenPristup($sesStatusK); break;
                }  
                ?>
            
                <div class="col-md-10 panel">
                    <?php
                    //PRIKAZ IZMJENE PODATAKA NA SREDINI
                    switch($sesStatusK)
                    {
                        case "0"; zabranjenPristup($sesStatusK); break; //blokiran
                        case 1; adminUpdateSong($idIzv, $idAlb, $idPjs); break; //admin
                        case 2; adminUpdateSong($idIzv, $idAlb, $idPjs); break; //moderator
                        case 3; zabranjenPristup($sesStatusK); break; //slušalac
                        case 4; adminUpdateSong($idIzv, $idAlb, $idPjs); break; //izvođač
                        case 5; adminUpdateSong($idIzv, $idAlb, $idPjs); break; //label
                        default; zabranjenPristup($sesStatusK); break;
                    }  
                    ?>    
                </div><!-- end col-md-10 --> 
                <?php
            }//end if else(validacija)
            ?>    
    </div><!-- end row --> 
</div><!-- end container-fluid --> 
<?php
include "footer.php";
		

