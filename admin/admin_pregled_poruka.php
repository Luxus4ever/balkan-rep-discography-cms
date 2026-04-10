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
                    if($sesStatusK===1 OR $sesStatusK===2)
                    {
                        //PRIKAZ IZMJENE PODATAKA NA SREDINI
                        $q = "SELECT p.*, k.idKorisnici, k.username, k.ime, k.prezime
                            FROM poruke_admin p
                            JOIN korisnici k ON p.idPosiljalac = k.idKorisnici
                            ORDER BY p.datumPoslano DESC";
                        $res = mysqli_query($conn, $q);
                        ?>
                        <div class="slikeAlbumaPregled">
                            <div class="col-md-10 panel">
                                <h3 class="text-warning">Poruke od korisnika</h3><hr>

                                <table class="table table-bordered table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Korisnik</th>
                                            <th>Naslov</th>
                                            <th>Poruka</th>
                                            <th>Slika</th>
                                            <th>Datum</th>
                                            <th>Odgovor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($r = mysqli_fetch_assoc($res)) { ?>
                                        <tr>
                                            <td><a href="../profile.php?username=<?= htmlspecialchars($r['username']); ?>&lid=<?= $r['idKorisnici']; ?>"><?= htmlspecialchars($r['username']); ?></a></td>
                                            <td><?= htmlspecialchars($r['naslov']); ?></td>
                                            <td><?= nl2br(htmlspecialchars($r['tekst'])); ?></td>
                                            <td>
                                                <?php if($r['slika']) { ?>
                                                    <a href="../images/uploads_poruke/<?= $r['slika']; ?>" data-lightbox="slika-1" >
                                                        <img src="../images/uploads_poruke/<?= $r['slika']; ?>" width="60">
                                                    </a>
                                                <?php } else { echo "-"; } ?>
                                            </td>
                                            <td><?= date("H:i:s - d.m.Y", strtotime($r['datumPoslano'])); ?></td>
                                            <td>
                                                <?php if(empty($r['odgovorAdmin'])) { ?>
                                                    <form method="POST" action="#">
                                                        <textarea name="odgovorAdmin" rows="2" class="form-control" required></textarea>
                                                        <input type="hidden" name="idPoruke" value="<?= $r['idPoruke']; ?>">
                                                        <button class="btn btn-sm btn-warning mt-1">Pošalji odgovor</button>
                                                    </form>
                                                <?php } else { ?>
                                                    <?= nl2br(htmlspecialchars($r['odgovorAdmin'])); ?><br>
                                                    <small><i><?= date("H:i:s - d.m.Y", strtotime($r['datumOdgovora'])); ?></i></small>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div><!--end .col-md-10 panel -->
                        </div><!--end .slikeAlbumaPregled -->
                        <?php
                        if(isset($_POST['idPoruke']) && isset($_POST['odgovorAdmin']))
                        {
                            $idPoruke = (int)$_POST['idPoruke'];
                            $odgovor = trim($_POST['odgovorAdmin']);
                            $datum = date("Y-m-d H:i:s");

                            $stmt = $conn->prepare("UPDATE poruke_admin SET odgovorAdmin=?, datumOdgovora=?, procitanoKorisnik = 0, procitanoAdmin = 1 WHERE idPoruke=?");
                            $stmt->bind_param("ssi", $odgovor, $datum, $idPoruke);
                            $stmt->execute();

                            echo "<script>alert('Odgovor poslat!'); window.location='admin_pregled_poruka.php';</script>";
                        }
                    }else
                        {
                            zabranjenPristup2("yellow", "NEMATE PRAVA PRISTUPA OVIM IZVOĐAČIMA!");
                        }//end if else provjera statusa korisnika
                        ?>
                </div><!-- end col-md-10 --> 
                <?php
            }//end if else(validacija)
            ?>    
    </div><!-- end .row --> 
</div><!-- end .container-fluid slikeAlbumaPregled --> 
<?php
include "footer.php";