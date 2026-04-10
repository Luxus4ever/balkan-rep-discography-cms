<?php
require_once "../config/bootstrap.php";
//Stranica koja prikazuje izmjenu podataka o albumu za za sve statuse članova zavisno od pristupa

include "headerAdmin.php";
include "functions/masterAdmin.func.php";
include "classes/masterClasses.class.php";

$adEdPan= new adminEditPanel();
$artEdPan= new artistEditPanel();
$lbEdPan= new labelEditPanel();

@$sesId= $_SESSION["idKorisnici"];
@$idIzv= $_GET['idIzv'];
@$idAlb= $_GET['idAlb'];
@$sesStatusK= $_SESSION["statusKorisnika"];

?>
<div class="container-fluid slikeAlbumaPregled">
    <div class="row">
        <?php
        if(empty($sesId) || empty($username)){
            zabranjenPristupBezValidacije($sesStatusK);
        }else
            {
                //PRIKAZ LIEJVOG PANELA
                switch($sesStatusK)
                {
                    case "0"; zabranjenPristup($sesStatusK); break; //blokiran
                    case "1"; $adEdPan->leftSidePanel($sesId); break; //admin
                    case "2"; $adEdPan->leftSidePanel($sesId); break; //moderator
                    case "3"; zabranjenPristup($sesStatusK); break; //slušalac
                    case "4"; $artEdPan->leftSidePanelArtist($idKorisnici, $idIzv); break; //izvođač
                    case "5"; $lbEdPan->leftSidePanelLabel($idKorisnici, $idIzv); break; //label
                    default; zabranjenPristup($sesStatusK); break;
                }  
                ?>
                <div class="col-md-10 panel">
                    
                    <?php
                    $q2= "SELECT * FROM albumi 
                    JOIN izvodjaci ON albumi.idIzvodjacAlbumi=izvodjaci.idIzvodjaci
                    JOIN albumi_izdavaci ON albumi.idAlbum = albumi_izdavaci.idAlbum 
                    JOIN izdavaci ON izdavaci.idIzdavaci = albumi_izdavaci.idIzdavaci
                    WHERE idIzvodjacAlbumi='{$idIzv}' AND albumi.idAlbum='{$idAlb}'";
                    $check_query= mysqli_query($conn, $q2);

                    while($row=mysqli_fetch_array($check_query)){
                        $idAlbCheck= $row["idAlbum"];
                        $idIzvCheck= $row["idIzvodjacAlbumi"];
                        $nazivAlbuma= $row["nazivAlbuma"];
                        $izvodjacMaster= $row["izvodjacMaster"];
                        $userAdmin= $row["userAdminIzdavac"];
                        $izdavaciNaziv= $row["izdavaciNaziv"];
                    }//end while
                    
                    if(($sesStatusK==1) || ($sesStatusK==2) || !empty($idAlbCheck) || (!empty($userAdmin) AND $userAdmin==$sesId AND $izdavacAlbum===$izdavaciNaziv)){
                        //PRIKAZ IZMJENE PODATAKA NA SREDINI
                        switch($sesStatusK)
                        {
                            case 0; zabranjenPristup($sesStatusK); break; //blokiran
                            case 1; updateAboutAlbum($idAlb, $sesId); break; //admin
                            case 2; updateAboutAlbum($idAlb, $sesId); break; //moderator
                            case 3; zabranjenPristup($sesStatusK); break; //slušalac
                            case 4; updateAboutAlbum($idAlb, $sesId); break; //izvođač
                            case 5; updateAboutAlbumLabel($idAlb, $sesId); break; //label
                            default; zabranjenPristup($sesStatusK); break;
                        }  
                    }else
                        {
                            zabranjenPristup2("yellow", "NEMATE PRAVA PRISTUPA IZMJENE PODATAKA OVIM ALBUMIMA!");
                        }
                        ?>
                </div><!-- end .col-md-10 -->
                <?php
            }//end if else(validacija)
            ?>         
    </div><!-- end .row --> 
</div><!-- end .container-fluid slikeAlbumaPregled --> 

<?php
include "footer.php";