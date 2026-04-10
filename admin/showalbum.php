<?php
require_once "../config/bootstrap.php";
//Stranica koja prikazuje izabrani album za IZVOĐAČA i IZDAVAČA/LABEL. Razlika je u tome što u url adresi ima idIzv & idAlb
//I ovu stranicu može da otvori i ADMIN i MODERATOR, ali kada se sa lijeve strane prikazuju svi albumi od tog izvođača

include "headerAdmin.php";
include "functions/masterAdmin.func.php";
include "classes/masterClasses.class.php";

$adEdPan= new adminEditPanel();
$artEdPan= new artistEditPanel();
$lbEdPan= new labelEditPanel();
@$idIzv= $_GET["idIzv"];
@$sesStatusK= $_SESSION["statusKorisnika"];
?>

<div class="container-fluid adminMainPanel">
    <div class="row">
        <?php
        if(empty($idKorisnici) || empty($username)){
            zabranjenPristupBezValidacije($sesStatusK);
        }else
            {
                $q= "SELECT * FROM albumi WHERE idAlbum='{$idAlb}'";
                $check_query1= mysqli_query($conn, $q);

                while($row=mysqli_fetch_array($check_query1)){
                    $idIzvCheck= $row["idIzvodjacAlbumi"];
                }

                if($idIzv===@$idIzvCheck)
                {
                    //PRIKAZ LIEJVOG PANELA
                    switch($sesStatusK)
                    {
                        case 0; zabranjenPristup($sesStatusK); break; //blokiran
                        case 1; $adEdPan->leftSidePanel($idKorisnici, $idIzv); break; //admin
                        case 2; $adEdPan->leftSidePanel($idKorisnici, $idIzv); break; //moderator
                        case 3; zabranjenPristup($sesStatusK); break; //slušalac
                        case 4; $artEdPan->leftSidePanelArtist($idKorisnici, $idIzv); break; //izvođač
                        case 5; $lbEdPan->leftSidePanelLabel($idKorisnici, $idIzv); break; //label
                        default; zabranjenPristup($sesStatusK); break;
                    }
                }else{
                    zabranjenPristup1("red", "NEMATE PRAVA PRISTUPA OVIM KATEGORIJAMA");
                }
                ?>
                <div class="col-md-10 panel">
                    <?php
                    $q2= "SELECT * FROM albumi JOIN izvodjaci WHERE idIzvodjacAlbumi='{$idIzv}' AND idAlbum='{$idAlb}'";
                    $check_query= mysqli_query($conn, $q2);

                    while($row=mysqli_fetch_array($check_query))
                    {
                        $idAlbCheck= $row["idAlbum"];
                        $idIzvCheck= $row["idIzvodjacAlbumi"];
                        $nazivAlbuma= $row["nazivAlbuma"];
                        $izvodjacMaster= $row["izvodjacMaster"];
                    }//end while
                    
                    if(!empty($idAlbCheck))
                    {
                        //PRIKAZ IZMJENE PODATAKA NA SREDINI
                        switch($sesStatusK)
                        {
                            case "0"; zabranjenPristup($sesStatusK); break; //blokiran
                            case 1; adminIzabraniAlbum($idIzv, $idAlb); break; //admin
                            case 2; adminIzabraniAlbum($idIzv, $idAlb); break; //moderator
                            case 3; zabranjenPristup($sesStatusK); break; //slušalac
                            case 4; $artEdPan->artistIzabraniAlbum($idKorisnici, $idIzv, $idAlb); break; //izvođač
                            case 5; adminIzabraniAlbum($idIzv, $idAlb); break; //label
                            default; zabranjenPristup($sesStatusK); break;
                        }  
                    }else{
                            zabranjenPristup2("yellow", "NEMATE PRAVA PRISTUPA OVIM KATEGORIJAMA");
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