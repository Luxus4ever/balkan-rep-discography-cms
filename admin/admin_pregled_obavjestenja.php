<?php
require_once "../config/bootstrap.php";
include "headerAdmin.php";

include "functions/masterAdmin.func.php";
include "classes/masterClasses.class.php";

$adEdPan= new adminEditPanel();

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
                    if($sesStatusK===1)
                    {
                        //PRIKAZ IZMJENE PODATAKA NA SREDINI
                        $q = "SELECT * FROM obavjestenja ORDER BY datum DESC";
                        $res = mysqli_query($conn, $q);
                        ?>
                        <div class="slikeAlbumaPregled">
                                <h4 class="text-warning">Послата обавештења</h4><hr>
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Naslov</th>
                                            <th>Tekst</th>
                                            <th>Slika</th>
                                            <th>Datum</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        while($r = mysqli_fetch_assoc($res)) { ?>
                                        <tr>
                                            <td><?= htmlspecialchars($r['naslov']); ?></td>
                                            <td><?= nl2br(htmlspecialchars($r['tekst'])); ?></td>
                                            <td>
                                                <?php if($r['slika']) { ?>
                                                    <a href="../images/uploads_obavjestenja/<?= $r['slika']; ?>" data-lightbox="1">
                                                        <img src="../images/uploads_obavjestenja/<?= $r['slika']; ?>" width="60">
                                                    </a>
                                                <?php } else echo "-"; ?>
                                            </td>
                                            <td><?= date("H:i:s - d.m.Y", strtotime($r['datum'])); ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                        </div><!--end .slikeAlbumaPregled -->
                        <?php
                    }else
                        {
                            zabranjenPristup2("yellow", "NEMATE PRAVA PRISTUPA OVIM OPCIJAMA!");
                        }//end if else status korisnika
                        ?>
                </div><!-- end col-md-10 --> 
                <?php
            }//end if else(validacija)
            ?>    
    </div><!-- end .row --> 
</div><!-- end .container-fluid slikeAlbumaPregled --> 
<?php
include "footer.php";