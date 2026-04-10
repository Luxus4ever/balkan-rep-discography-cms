<?php
require_once "../config/bootstrap.php";
//Stranica koja prikazuje dodavanje nove kategorije za ADMINA. 

include "headerAdmin.php";
include "./functions/masterAdmin.func.php";
include "classes/masterClasses.class.php";

$adEdPan= new adminEditPanel();

@$idcat= $_GET['idcat'];
@$sesStatusK= $_SESSION["statusKorisnika"];

?>
<div class="container-fluid adminMainPanel">
    <div class="row">
    
            <?php
            if(empty($idKorisnici) || empty($username)){
                zabranjenPristupBezValidacije($sesStatusK);
            }else
            {
                if(($sesStatusK==1))
                {
                    $adEdPan->leftSidePanel($idKorisnici);
                }else{
                    $poruka="NEMATE PRAVO PRISTUPA OVOJ STRANICI!";
                    zabranjenPristup1("red", $poruka);

                }
                ?>
                <div class="col-md-10 panel">
                    <?php
                    if(($sesStatusK==1))
                    {
                        $adEdPan->adminAddCategory();
                    }else{
                            $poruka="NEMATE PRAVO PRISTUPA OVOJ STRANICI!";
                            zabranjenPristup2("yellow", $poruka);
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
		

