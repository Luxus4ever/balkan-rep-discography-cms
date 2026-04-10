<?php
require_once "../config/bootstrap.php";
//Stranica koja prikazuje izmjenu podataka o izdavaču za sve statuse članova osim izvođača zavisno od pristupa

include "headerAdmin.php";
include "functions/masterAdmin.func.php";
include "classes/masterClasses.class.php";

$adEdPan= new adminEditPanel();
$lbEdPan= new labelEditPanel();
@$idLab= $_GET["idLab"];
@$sesId= $_SESSION["idKorisnici"];
@$sesStatusK= $_SESSION["statusKorisnika"];
@$sesIdLabel= $_SESSION["label_Id"];


?>
<div class="container-fluid adminMainPanel">
    <div class="row">
        <?php
        if(empty($idKorisnici) || empty($username)){
            zabranjenPristupBezValidacije($sesStatusK);
        }else
        {
            $q= "SELECT statusKorisnika FROM korisnici WHERE idKorisnici='{$idKorisnici}'";
            $status_korisnika= mysqli_query($conn, $q);

            while($row= mysqli_fetch_array($status_korisnika))
            {
                $statusKorisnika= $row["statusKorisnika"];

                //PRIKAZ LIEJVOG PANELA
                switch($sesStatusK)
                {
                    case 0; zabranjenPristup($statusKorisnika); break; //blokiran
                    case 1; $adEdPan->leftSidePanel($idKorisnici, $idIzv); break; //admin
                    case 2; $adEdPan->leftSidePanel($idKorisnici, $idIzv); break; //moderator
                    case 3; zabranjenPristup($statusKorisnika); break; //slušalac
                    case 4; zabranjenPristup($statusKorisnika); break; //izvođac
                    case 5; $lbEdPan->leftSidePanelLabel($idKorisnici, $idIzv, $idAlb); break; //label
                    default; zabranjenPristup($statusKorisnika); break;
                }
            }//end while loop
            
            ?>
            <div class="col-md-10 panel">
                <?php
                $q2= "SELECT * FROM izdavaci WHERE idIzdavaci='{$idLab}'";
                $check_query= mysqli_query($conn, $q2);

                while($row=mysqli_fetch_array($check_query))
                {
                    $userAdmin= $row["userAdminIzdavac"];
                }//end while
                if(($sesStatusK==1) || ($sesStatusK==2) || $userAdmin===$sesId){

                //PRIKAZ IZMJENE PODATAKA NA SREDINI
                switch($sesStatusK)
                {
                    case 0; zabranjenPristup($sesStatusK); break; //blokiran
                    case 1; $adEdPan->adminUpdateLabel($idLab); break; //admin
                    case 2; $adEdPan->adminUpdateLabel($idLab); break; //moderator
                    case 3; zabranjenPristup($sesStatusK); break; //slušalac
                    case 4; zabranjenPristup($sesStatusK); break; //izvođač
                    case 5 AND !empty($userAdmin); $adEdPan->adminUpdateLabel($idLab); break; //label
                    default; zabranjenPristup($sesStatusK); break;
                }  
                }else{
                    zabranjenPristup2("yellow", "NEMATE PRAVA PRISTUPA OVIM IZDAVAČIMA!");
                }
                ?>    
            </div><!-- end col-md-10 --> 
            <?php
            
        }//end if else(validacija)
            ?>    
    </div><!-- end row --> 
</div><!-- end container-fluid --> 
<?php

global $conn;



include "footer.php";