<?php
require_once "../config/bootstrap.php";
//Stranica koja prikazuje izmjenu podataka o izvođaču za za sve statuse osim izdavača zavisno od pristupa

include "headerAdmin.php";

include "functions/masterAdmin.func.php";
include "classes/masterClasses.class.php";

$adEdPan= new adminEditPanel();
$artEdPan= new artistEditPanel();
@$idIzv= $_GET["idIzv"];
@$sesId= $_SESSION["idKorisnici"];
@$sesStatusK= (int)$_SESSION["statusKorisnika"];


?>

<div class="container-fluid adminMainPanel">
    <div class="row">
        <?php
        if(empty($idKorisnici) || empty($username)){
            zabranjenPristupBezValidacije($sesStatusK);
        }else{
                //PRIKAZ LIEJVOG PANELA
                switch($sesStatusK)
                {
                    case 0; zabranjenPristup($sesStatusK); break; //blokiran
                    case 1; $adEdPan->leftSidePanel($idKorisnici, $idIzv); break; //admin
                    case 2; $adEdPan->leftSidePanel($idKorisnici, $idIzv); break; //moderator
                    case 3; zabranjenPristup($sesStatusK); break; //slušalac
                    case 4; $artEdPan->leftSidePanelArtist($idKorisnici, $idIzv); break; //izvođač
                    case 5; zabranjenPristup($sesStatusK); break; //label
                    default; zabranjenPristup($sesStatusK); break;
                }     
                ?>
        
                <div class="col-md-10 panel">
                    <?php                        
                    $q2= "SELECT * FROM izvodjaci WHERE idIzvodjaci='{$idIzv}'";
                    $check_query= mysqli_query($conn, $q2);

                    while($row=mysqli_fetch_array($check_query)){
                        $idArtistCheck= $row["idIzvodjaci"];
                        $userAdmin= $row["userAdmin"];
                    }
                    
                    if($userAdmin===$sesId OR $sesStatusK==1 OR $sesStatusK==2)
                    {
                        //PRIKAZ IZMJENE PODATAKA NA SREDINI
                        switch($sesStatusK)
                        {
                            case 0; zabranjenPristup($sesStatusK); break; //blokiran
                            case 1; updateBiografija($idIzv, $sesStatusK); break; //admin
                            case 2; updateBiografija($idIzv, $sesStatusK); break; //moderator
                            case 3; zabranjenPristup($sesStatusK); break; //slušalac
                            case 4 AND !empty($userAdmin); updateBiografija($idIzv, $sesStatusK); break; //izvođač
                            case 5; zabranjenPristup($sesStatusK); break; //label
                            default; zabranjenPristup($sesStatusK); break;
                        }  
                    }else
                        {
                            
                            zabranjenPristup2("yellow", "NEMATE PRAVA PRISTUPA OVIM IZVOĐAČIMA!");
                        }
                        ?>
                </div><!-- end col-md-10 --> 
                <?php
            }//end if else(validacija)
            ?>    
    </div><!-- end .row --> 
</div><!-- end .container-fluid slikeAlbumaPregled --> 
<?php
include "footer.php";