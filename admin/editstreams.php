<?php
require_once "../config/bootstrap.php";
//Stranica koja prikazuje streamove samo za ADMINA, MODERATORA i IZDAVAČA. Razlika je u tome što u url adresi ima idAlb, kao i provjeri

include "headerAdmin.php";
include "functions/masterAdmin.func.php";
include "classes/masterClasses.class.php";

$adEdPan= new adminEditPanel();
$adStrms= new adminStreaming();
$artEdPan= new artistEditPanel();
$lbEdPan= new labelEditPanel();

@$idAlb= $_GET["idAlb"];
@$idLab= $_GET["idLab"];
@$sesId= $_SESSION["idKorisnici"];
@$sesStatusK= $_SESSION["statusKorisnika"];
?>
<div class="container-fluid slikeAlbumaPregled">
    <div class="row">
        <?php
        if(empty($idKorisnici) || empty($username)){
            zabranjenPristupBezValidacije($sesStatusK);
        }else
            {
                //PRIKAZ LIEJVOG PANELA
                switch($sesStatusK)
                {
                    case "0"; zabranjenPristup($sesStatusK); break; //blokiran
                    case "1"; $adEdPan->leftSidePanel($idKorisnici, $idIzv); break; //admin
                    case "2"; $adEdPan->leftSidePanel($idKorisnici, $idIzv); break; //moderator
                    case "3"; zabranjenPristup($sesStatusK); break; //slušalac
                    case "4"; zabranjenPristup($sesStatusK); break; //izvođač
                    case "5"; $lbEdPan->leftSidePanelLabel($idKorisnici, $idIzv); break; //label
                    default; zabranjenPristup($sesStatusK); break;
                }  
                ?>
                <div class="col-md-10 panel">
                    <?php
                    $q2= "SELECT * FROM albumi 
                    JOIN albumi_izdavaci ON albumi_izdavaci.idAlbum=albumi.idAlbum 
                    JOIN izdavaci ON izdavaci.idIzdavaci=albumi_izdavaci.idIzdavaci
                    WHERE albumi.idAlbum='{$idAlb}'";
                    $check_query= mysqli_query($conn, $q2);

                    $row = mysqli_fetch_assoc($check_query);

                    $userAdminIzdavac= $row["userAdminIzdavac"];
                    
                    if(($sesStatusK==1) || ($sesStatusK==2) || ($userAdminIzdavac==$sesId))
                    {
                        //PRIKAZ IZMJENE PODATAKA NA SREDINI
                        switch($sesStatusK)
                        {
                            case "0"; zabranjenPristup($sesStatusK); break; //blokiran
                            case 1; $adStrms->adminStreamovi($idAlb); break; //admin
                            case 2; $adStrms->adminStreamovi($idAlb); break; //moderator
                            case 3; zabranjenPristup($sesStatusK); break; //slušalac
                            case 4; zabranjenPristup($sesStatusK); break; break; //izvođač
                            case 5; $adStrms->adminStreamovi($idAlb); break; //label
                            default; zabranjenPristup($sesStatusK); break;
                        }  
                    }else{
                        zabranjenPristup2("yellow", "NEMATE PRAVA PRISTUPA OVIM STRIMOVIMA!");
                    }
                    ?>
                </div><!-- end col-md-10 --> 
                <?php
            }//end if else(validacija)
            ?>      
    </div><!-- end row --> 
</div><!-- end .container-fluid slikeAlbumaPregled --> 
<?php
include "footer.php";