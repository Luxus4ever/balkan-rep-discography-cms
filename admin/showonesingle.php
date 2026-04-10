<?php
require_once "../config/bootstrap.php";
//Stranica koja prikazuje singlove za ADMINA i MODERATORA. 

include "headerAdmin.php";
include "./functions/masterAdmin.func.php";
include "classes/masterClasses.class.php";

$adEdPan= new adminEditPanel();
$lbEdPan= new labelEditPanel();
@$idIzv= $_GET["idIzv"];
@$sesId= $_SESSION["idKorisnici"];
@$sesStatusK= $_SESSION["statusKorisnika"];
$idSingl= $_GET["single"];

?>
<div class="container-fluid adminMainPanel">
    <div class="row">
        <?php
        if(empty($idKorisnici) || empty($username)){
            zabranjenPristupBezValidacije($sesStatusK);
        }else
            {
                //PRIKAZ LIEJVOG PANELA
                switch($sesStatusK)
                {
                    case "0";zabranjenPristup($sesStatusK); break; //blokiran
                    case "1"; $adEdPan->leftSidePanel($idKorisnici, $idIzv); break; //admin
                    case "2"; $adEdPan->leftSidePanel($idKorisnici, $idIzv); break; //moderator
                    case "albumi"; $this->adminSpisakAlbuma(); break; //slušalac
                    case "strimovi"; $this->adminSpisakAlbumaZaStrimove(); break; //izvođač
                    case "5"; zabranjenPristup1("red", "NEMATE PRAVA PRISTUPA!!!"); break; //label
                    case "tekstovi"; $this->adminTekstoviPjesama($sesId); break;
                    default; ""; break;
                }  
                ?>
        
                <div class="col-md-10 panel">
                    <?php
                    $q2= "SELECT * FROM izdavaci WHERE userAdminIzdavac='{$sesId}'";
                    $check_query= mysqli_query($conn, $q2);

                    while($row=mysqli_fetch_array($check_query)){
                    $userAdmin= $row["userAdminIzdavac"];
                    }
                    
                    if(($sesStatusK==1) || ($sesStatusK==2) || (!empty($userAdmin)===$sesId))
                    {

                        //PRIKAZ IZMJENE PODATAKA NA SREDINI
                        switch($sesStatusK)
                        {
                            case "0"; zabranjenPristup($sesStatusK); break; //blokiran
                            case 1; adminIzabraniJedanSingle($idSingl); break; //admin
                            case 2; adminIzabraniJedanSingle($idSingl); break; //moderator
                            case 3; zabranjenPristup($sesStatusK); break;   //slušalac
                            case 4; zabranjenPristup($sesStatusK); break;   //izvođač
                            case 5 AND !empty($userAdmin); zabranjenPristup2("yellow", "NEMATE PRAVA PRISTUPA!!!"); break; //label
                            default; zabranjenPristup($sesStatusK); break;
                        }
                    }else
                        {
                            zabranjenPristup2("yellow", "NEMATE PRAVA PRISTUPA OVIM IZVOĐAČIMA!!!");
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
		

