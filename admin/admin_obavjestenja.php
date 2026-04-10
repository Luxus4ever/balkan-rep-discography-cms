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
                       ?>
                        <div class="slikeAlbumaPregled">
                            <form method="POST" enctype="multipart/form-data" action="" class="p-4 border rounded">
                                <h4 class="text-warning">Pošalji obaveštenje svim korisnicima</h4><hr>

                                <div class="form-group">
                                    <label for="naslov">Naslov</label>
                                    <input type="text" class="form-control" name="naslov" id="naslov" required>
                                </div><!-- end .form-group -->

                                <div class="form-group">
                                    <label for="tekst">Tekst obaveštenja</label>
                                    <textarea class="form-control" name="tekst" id="tekst" rows="4" required></textarea>
                                </div><!-- end .form-group -->

                                <div class="form-group">
                                    <label for="slika">Dodaj sliku (opciono)</label>
                                    <input type="file" class="form-control-file" name="slika" accept="image/*">
                                </div><!-- end .form-group -->

                                <button type="submit" name="posalji" class="btn btn-warning mt-2">Pošalji svima</button>
                            </form>
                        </div><!--end .slikeAlbumaPregled -->
                        <?php
                        //print_r($_SESSION);
                        // samo admini i moderatori mogu
                        if(!isset($_SESSION['statusKorisnika']) || !in_array($_SESSION['statusKorisnika'], ['1', '2'])) {
                            die("Nemate prava pristupa!");
                        }

                        if(isset($_POST['posalji']))
                        {
                            $naslov = trim($_POST['naslov']);
                            $tekst = trim($_POST['tekst']);
                            $slika = "";

                            // Upload slike ako postoji
                            if(!empty($_FILES['slika']['name'])) {
                                $dozvoljene = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                                $ime = $_FILES['slika']['name'];
                                $tmp = $_FILES['slika']['tmp_name'];
                                $ext = strtolower(pathinfo($ime, PATHINFO_EXTENSION));

                                if(in_array($ext, $dozvoljene)) {
                                    $datumSufiks = "_im_" . date("dmY_His"); 
                                    $novoIme = time() . "_" . uniqid() . $datumSufiks . "." . $ext;
                                    $putanja = "../images/uploads_obavjestenja/" . $novoIme;
                                    move_uploaded_file($tmp, $putanja);
                                    $slika = $novoIme;
                                }
                            }//end if

                            // Унос у табелу обавештења
                            $stmt = $conn->prepare("INSERT INTO obavjestenja (naslov, tekst, slika, datum) VALUES (?, ?, ?, NOW())");
                            $stmt->bind_param("sss", $naslov, $tekst, $slika);
                            $stmt->execute();
                            $idObav = $stmt->insert_id;

                            // Слање свим корисницима
                            $rez = mysqli_query($conn, "SELECT idKorisnici FROM korisnici WHERE statusKorisnika!=0");
                            while($r = mysqli_fetch_assoc($rez)) {
                                $idKor = $r['idKorisnici'];
                                mysqli_query($conn, "INSERT INTO korisnik_obavjestenja (idKorisnik, idObavjestenje) VALUES ($idKor, $idObav)");
                            }

                            echo "<div class='alert alert-success'>✅ Obaveštenje je poslato svim korisnicima!</div>";
                        }//if(isset($_POST['posalji']))
                    }else
                        {
                            zabranjenPristup2("yellow", "NEMATE PRAVA PRISTUPA OVIM OPCIJAMA!");
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