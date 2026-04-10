<?php

class adminKontakt{

    //METODE SADRŽANE U OVOJ KLASI
    //__construct (prikaz stranice za kontakt admina i primljenih poruka od admina)

    public $idKorisnici;
    public $idP;

    public $idPosiljalac;
    public $naslov;
    public $tekst;
    public $slika;
    public $lastInsertKontaktAdminId;

    //********************************* Prikaz stranice za kontakt admina i primljenih poruka *********************************//
    public function __construct()
    {
        $this->idKorisnici = $_SESSION['idKorisnici'];
        global $conn;

        // упит за све поруке тог корисника
        $q = "SELECT * FROM poruke_admin WHERE idPosiljalac=$this->idKorisnici ORDER BY datumPoslano DESC";
        $res = mysqli_query($conn, $q);
        ?>
        
        <div id="" class="wrapper">
            <div class="slikeAlbumaPregled">
                <div class="container-fluid">
                <div class="row">
                    <!-- ЛЕВА СТРАНА: историја порука -->
                    <div class="col-md-4 border-right">
                        <h5 class="text-warning">Moje poruke</h5>
                        <ul class="list-group">
                            <?php 
                            if (isset($_GET['poruka'])) 
                            {
                                $idPoruke = (int)$_GET['poruka'];
                                $q = "UPDATE poruke_admin SET procitanoKorisnik = 1 WHERE idPoruke = $idPoruke";
                                mysqli_query($conn, $q);
                            }
                            while($r = mysqli_fetch_assoc($res)) { 
                            // дефиниши стил за непрочитане / оне са одговором
                            $style = '';

                            // ако има одговор администратора
                            if (!empty($r['odgovorAdmin']) && $r['procitanoKorisnik'] == 0) {
                                $style = 'color: red; font-weight: bold;'; // црвено и болдовано
                            } elseif (!empty($r['odgovorAdmin'])) {
                                $style = 'color: darkred;'; // прочитана али има одговор (светлија нијанса)
                            }

                            // ако је изабрана тренутно отворена порука — без стила (нормално)
                            if (isset($_GET['poruka']) && $_GET['poruka'] == $r['idPoruke']) {
                                $style = '';
                            }
                        ?>
                            <li class="list-group-item <?= $r['procitanoKorisnik'] ? 'bg-light' : 'bg-warning'; ?>">
                                <span style="<?= $style ?>"><?= htmlspecialchars($r['naslov']); ?></span><br>
                                <small class="text-primary"><?= date("H:i:s - d.m.Y", strtotime($r['datumPoslano'])); ?></small><br>
                                <a href="?poruka=<?= $r['idPoruke']; ?>" class="text-dark">Prikaži</a>
                            </li>
                        <?php } ?>
                        </ul>
                    </div><!-- end col-md-4 border-right -->

                    <!-- ДЕСНА СТРАНА: приказ одређене поруке и одговора -->
                    <div class="col-md-8">
                        <?php
                        if(isset($_GET['poruka'])) 
                        {
                            $this->idP = (int)$_GET['poruka'];
                            $sel = mysqli_query($conn, "SELECT * FROM poruke_admin WHERE idPoruke=$this->idP AND idPosiljalac=$this->idKorisnici");
                            if(mysqli_num_rows($sel) > 0) 
                            {
                                $por = mysqli_fetch_assoc($sel);
                                ?>
                                <div class="p-3 border border-3 border-warning rounded">
                                    <h5><?= htmlspecialchars($por['naslov']); ?></h5>
                                    <p><?= nl2br(htmlspecialchars($por['tekst'])); ?></p>
                                    <?php if($por['slika']) { ?>
                                        
                                    <a href="images/uploads_poruke/<?= $por['slika']; ?>" data-lightbox="slikaKontaktPoruka-<?= $por['idPoruke']; ?>">
                                        <img src="images/uploads_poruke/<?= $por['slika']; ?>" 
                                            alt="<?= htmlspecialchars($por['slika']); ?>" 
                                            title="<?= htmlspecialchars($por['slika']); ?>" 
                                            width="200" style="cursor:pointer;">
                                    </a>
                                    <?php } ?>
                                    <small class="text-info">Poslato: <?= date("H:i:s - d.m.Y", strtotime($por['datumPoslano'])); ?></small>
                                    <hr>
                                    <?php if($por['odgovorAdmin']) { ?>
                                        <p><b>Odgovor administratora:</b></p>
                                        <div class="bg-danger p-2 border rounded text-white">
                                            <?= nl2br(htmlspecialchars($por['odgovorAdmin'])); ?><br>
                                            <small class="text-info">Odgovoreno: <?= date("H:i:s - d.m.Y", strtotime($por['datumOdgovora'])); ?></small>
                                        </div>
                                    <?php } else { ?>
                                        <p class="text-secondary">Administrator još nije odgovorio.</p>
                                    <?php } ?>
                                </div><!-- p-3 border rounded -->
                                <?php 
                            }//end if 
                        } else { ?>
                            <div class="alert alert-info">Izaberi poruku sa leve strane ili pošalji novu.</div><!-- end .alert alert-info -->
                        <?php } ?>

                        <hr>
                        <h5 class="text-warning">Pošalji novu poruku</h5>
                        <form method="POST" enctype="multipart/form-data" action="">
                            <input type="text" name="naslov" class="form-control mb-2" placeholder="Naslov" required>
                            <textarea name="tekst" class="form-control mb-2" rows="3" placeholder="Tekst poruke" required></textarea>
                            <input type="file" name="slikaKontaktPoruka" class="form-control mb-2" accept="image/*">
                            <button type="submit" name="posalji" class="btn btn-warning">Pošalji</button>
                        </form>

                        <h4 class="text-info">Potrebni uslovi da nam pošaljete poruku:</h4>
                        <ol>
                            <li class="text-info">Ukoliko ste <strong>izvođač</strong>, potrebno je da nas kontaktirate i pošaljete neki dokument sa slikom. VAŽNA NAPOMENA: ne zanima nas JMB, OIB, broj dokumenta, datum i mjesto rođenja ili slično, slobodno sakrijte te podatke. Bitno je da se vidi ime i prezime kao i slika. Nakon verifikacije ta slika se briše. Nakon potvrde imaćete mogućnost da održavate podatke o vašim izdanjima.</li>
                            <li class="text-info">Ukoliko ste registrovana <strong>Izdavačka kuća/Label</strong>, potrebno je da nas kontaktirate i pošaljete dokument gde je vidljiv vaš JIB, ili nam napišite u poruci vaš JIB. VAŽNA NAPOMENA: nakon verifikacije ti podaci se brišu. Nakon potvrde autentičnosti imaćete mogućnost da održavate podatke o vašim izdanjima.</li>
                            <li class="text-info">Ukoliko imate primjedbe na nekog člana (u komentarima/recenzijama ili porukama), zbog vrijeđanja, prijetnji, piraterije ili slično, pošaljite i sliku sa dokazima (screenshot/print screen)</li>
                            <li class="text-info">Ukoliko imate predlog za neku ispravku na sajtu, ažuriranje podataka ili slično.</li>
                            <li class="text-info">Ukoliko imate neku primjedbu na neke podatke.</li>
                        </ol>
                    </div><!-- end. col-md-8 -->
                </div><!-- end .row -->
                </div><!-- end .container -->
            </div><!-- end .slikeAlbumaPregled -->
        </div><!-- end .wrapper -->

        <?php
        //----------------------------------------
        include_once "classes/insertData-classes/imageUploader.class.php";
        $uploader = new ImageUploader();
        //----------------------------------------
        global $conn;
        if(isset($_POST['posalji'])) 
        {
            $this->idPosiljalac = $_SESSION['idKorisnici'];
            $this->naslov = trim($_POST['naslov']);
            $this->tekst = trim($_POST['tekst']);
            //$this->slika = "";

            

            $stmt = $conn->prepare("INSERT INTO poruke_admin (idPosiljalac, naslov, tekst, procitanoAdmin) VALUES (?, ?, ?, 0)");
            $stmt->bind_param("iss", $this->idPosiljalac, $this->naslov, $this->tekst);
            if($stmt->execute()) {

                //$this->lastInsertKontaktAdminId= mysqli_insert_id($conn);  //hvata zadnji insertovani ID
                $this->lastInsertKontaktAdminId = $conn->insert_id; //hvata zadnji insertovani ID


                // Upload slike ako postoji
                if(!empty($_FILES['slikaKontaktPoruka']['name']) && $_FILES["slikaKontaktPoruka"]["error"] === UPLOAD_ERR_OK) 
                {
                $res = $uploader->uploadAndUpdateImageField("slikaKontaktPoruka", "images/uploads_poruke/", "admin_kontakt_poruka", (int)$this->lastInsertKontaktAdminId, $conn,"poruke_admin", /* tabela*/ "slika",  /*kolona slike*/ "idPoruke", /* id kolona*/ 77);
                //print_r($res);




                /*$dozvoljene = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $ime = $_FILES['slikaKontaktPoruka']['name'];
                $tmp = $_FILES['slikaKontaktPoruka']['tmp_name'];
                $ext = strtolower(pathinfo($ime, PATHINFO_EXTENSION));

                if(in_array($ext, $dozvoljene)) {
                    $datumSufiks = "_im_" . date("dmY_His"); 
                    $novoIme = time() . "_" . uniqid() . $datumSufiks . "." . $ext;
                    $putanja = "images/uploads_poruke/" . $novoIme;
                    move_uploaded_file($tmp, $putanja);
                    $this->slika = $novoIme;
                }*/
                }//end if(!empty($_FILES)
                echo "<div class='alert alert-success'>Poruka je uspešno poslata administratoru!</div>";
            } else {
                echo "<div class='alert alert-danger'>Greška pri slanju poruke</div>";
            }
        }//end if(isset($_POST['posalji']))
    }//end function __construct
    //********************************* Pozvana metoda u kontaktAdmin.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------
}//end class