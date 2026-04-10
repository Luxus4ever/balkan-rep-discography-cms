<?php

class adminEditPanel{

    //METODE SADRŽANE U OVOJ KLASI
    //leftSidePanel (lijeva strana admin panela)
    //prikazAdminEditPanela (prikaz kompletnog admin panela)
    //leftSideAdmin (prikaz vrijednosti (menija) admin panela)
    //adminSpisakAlbuma (prikaz svih albuma)
    //adminSpisakIzvodjaca (prikaz svih izvođača)
    //adminSpisakKorisnika (prikaz svih korisnika)
    //adminPrikazKomentara (prikaz svih komentara)
    //adminsSpisakIzdavaca (spisak svih izdavača/labela)
    //adminUpdateLabel (izmjena izdavača/labela)
    //unosLogoLabel (unos logo-a za izdavača /labela)
    //adminSpisakAlbumaZaStrimove (prikaz svih albuma za strimove)
    //adminTekstoviPjesama (dodavanje tekstova pjesama kojih nema)
    //adminSpisakSinglova (prikaz svih singlova za izmjenu)
    //adminObavjestenja (prikaz opcija za obavještenja)
    //adminKategorijeAlbuma (prikaz svih kategorija)
    //adminUpdateCategory (Metoda koja vrši update kategorija)
    //adminAddCategory (Metoda kojom se dodaje nova kategorija/žanr)

    protected $sesId;

    protected $idRecenzije;
    protected $recenzija;
    protected $vrijemeRecenzije;
    protected $profilnaSlika;
    protected $slikaAlbuma;

    protected $idKorisnici;
    protected $username;
    private $tipKorisnika;
    private $statusKorisnika;
    private $nazivStatusaKorisnika;

    protected $idAlbum;
    protected $idIzvodjacAlbumi;
    protected $idIzvodjac2;
    protected $idIzvodjac3;
    protected $nazivAlbuma;
    protected $godinaIzdanja;
    protected $idIzvodjaci;
    protected $izvodjacMaster;
    protected $izvodjac2;
    protected $izvodjac3;
    protected $mixtapeIzvodjac;

    protected $idIzdavaci;
    protected $izdavaciNaziv;
    protected $izdavaciStatus;
    protected $izdavaciOpis;
    protected $logoLabel;

    protected $idPjesme;
    protected $tekstPjesme;

    protected $idSinglovi;
    protected $singleIzvodjaci;
    protected $singlNaziv; 
    protected $singleFeat;

    protected $idKategorijeAlbuma;
    protected $nazivKategorijeAlbuma;
    protected $opisKategorijeAlbuma;

    //********************************* Metoda za prikaz lijeve strane admin panela za admin nalog *********************************//
    public function leftSidePanel($idKorisnici, $idIzv=""){
        ?>
        <div class="col-md-2 visina leftPanel">
        <?php
        @$idIzv= $_GET["idIzv"];
        $this->leftSideAdmin($idKorisnici, $idIzv);

        ?>
        </div><!-- end col-md-2 --> 
        <?php
        
    }//end leftSidePanel()
    /********************************* Pozvana metoda u ovom fajlu u funkciji prikazAdminEditPanela(), takođe i u fajlovima artistEditPanel.class.php, adminupdatealbum.php, adminupdateartist.php, adminviewusers.php, editstreams.php, showalbum.php, showalbumstreams.php, showeditlabel.php, showsinglealbum.php, updatesongs.php, updatetext.php *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------
    
    //********************************* Metoda za prikaz kompletnog admin panela *********************************//
    public function prikazAdminEditPanela($idKorisnici, $nazivPromjeneLinka, $sesId)
    {
        //$idKorisnici predstavljaju status korisnika
        //echo "OVO JE:" . $this->sesId= $_SESSION["idKorisnici"];
        ?>
    
        <div class="container-fluid adminMainPanel visina">
            <div class="row">
                <?php
                $this->leftSidePanel($idKorisnici);
                ?>
                <div class="col-md-10 panel">
                    <?php
                    switch($nazivPromjeneLinka)
                    {
                        case "izvodjaci"; $this->adminSpisakIzvodjaca(); break;
                        case "korisnici"; $this->adminSpisakKorisnika(); break;
                        case "komentari"; $this->adminPrikazKomentara(); break;
                        case "albumi"; $this->adminSpisakAlbuma(); break;
                        case "singlovi"; $this->adminSpisakSinglova(); break;
                        case "strimovi"; $this->adminSpisakAlbumaZaStrimove(); break;
                        case "izdavaci"; $this->adminSpisakIzdavaca(); break;
                        case "tekstovi"; $this->adminTekstoviPjesama($sesId); break;
                        case "obavestenja"; $this->adminObavjestenja(); break;
                        case "kategorije"; $this->adminKategorijeAlbuma(); break;
                        default; ""; break;
                    }                      
                    ?>
                </div><!-- end col-md-10 --> 
            </div><!-- end row --> 
        </div><!-- end container-fluid --> 
        <?php
    }//end prikazAdminEditPanela()
    //********************************* Pozvana metoda u indexadmin.php  *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------
    
    //********************************* Metoda za prikaz vrijednosti (menija) admin panela *********************************//
    private function leftSideAdmin($userAdmin, $idIzv, $idLab="")
    {
        global $conn;
        global $idKorisnici;
        $q0= "SELECT * FROM korisnici JOIN status_korisnika ON statusKorisnika=idStatusKorisnika WHERE idKorisnici='{$userAdmin}'";
        $select_adminUser= mysqli_query($conn, $q0);

        while($row= mysqli_fetch_array($select_adminUser))
        {
            $this->username= $row["username"];
            $this->nazivStatusaKorisnika= $row["nazivStatusaKorisnika"];
            tipKorisnikaAdmin($idKorisnici);
            ?>
            <h4 class="text-center text-danger pt-3"><strong><a href="indexadmin.php" class="text-decoration-none bg-white text-danger"><?php echo $this->username; ?></a></strong></h4> 
            <hr class="bg-light">
            <?php            
        }

        $q = "SELECT COUNT(*) AS neprocitane FROM poruke_admin WHERE procitanoAdmin = 0";
        $res = mysqli_query($conn, $q);
        $neprocitane = 0;
        if($row = mysqli_fetch_assoc($res)) {
            $neprocitane = (int)$row['neprocitane'];
        }


        ?>
        <a class="text-decoration-none" href="indexadmin.php?data=izvodjaci"><h5 class="text-center text-warning">Izvođači</h5></a>
        <hr class="bg-light">

        <a class="text-decoration-none" href="indexadmin.php?data=korisnici"><h5 class="text-center text-warning">Korisnici</h5></a>
        <hr class="bg-light">

        <a class="text-decoration-none" href="indexadmin.php?data=komentari"><h5 class="text-center text-warning">Komentari</h5></a>
        <hr class="bg-light">

        <a class="text-decoration-none" href="indexadmin.php?data=albumi"><h5 class="text-center text-warning">Albumi</h5></a>
        <?php
        if(empty($idIzv)){
            echo "";
        }else{
            adminSpisakAlbumaIzvodjaca($idIzv);
        }       
        ?>
        <hr class="bg-light">

        <a class="text-decoration-none" href="indexadmin.php?data=singlovi"><h5 class="text-center text-warning">Singlovi</h5></a>
        <?php
        if(empty($idIzv)){
            echo "";
        }else{
            adminSpisakSinglovaIzvodjaca($idIzv);
        }       
        ?>
        <hr class="bg-light">

        <a class="text-decoration-none" href="indexadmin.php?data=strimovi"><h5 class="text-center text-warning">Strimovi</h5></a>
        <?php
        if(empty($idIzv)){
            echo "";
        }else{
            adminSpisakAlbumaIzvodjacaStrimovi($idIzv);
        }       
        ?>
        <hr class="bg-light">

        <a class="text-decoration-none" href="indexadmin.php?data=izdavaci"><h5 class="text-center text-warning">Izdavači</h5></a>
        <hr class="bg-light">

        <a class="text-decoration-none" href="indexadmin.php?data=tekstovi"><h5 class="text-center text-warning">Tekstovi pjesama</h5></a>
        <hr class="bg-light">

        <a class="text-decoration-none" href="indexadmin.php?data=kategorije"><h5 class="text-center text-warning">Žanrovi albuma</h5></a>
        <hr class="bg-light">

        <a class="text-decoration-none" href="logs/adminlogs.php"><h5 class="text-center text-warning bg-dark">Logovi</h5></a>
        <hr class="bg-light">

        <a class="text-decoration-none" href="indexadmin.php?data=obavestenja"><h5 class="text-center text-white bg-danger">Obavještenja</h5></a>
        <hr class="bg-light">

       <a class="text-decoration-none nav-link" href="admin_pregled_poruka.php">
            <h5 class="text-center text-white bg-dark">
                Kontakt poruke 
                <?php if ($neprocitane > 0): ?>
                    <span id="contactUnreadBadge" class="unread-badge-contact">
                        <?= $neprocitane ?>
                    </span>
                <?php endif; ?>
            </h5>
        </a>

        <hr class="bg-light">
        <?php     
    }//end leftSideAdmin()

    //********************************* Pozvana metoda u u ovom fajlu u metodi leftSidePanel()  *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda za prikaz svih albuma  *********************************//
    protected function adminSpisakAlbuma()
    {
        global $conn;

        $q = "SELECT albumi.*, 
                i1.izvodjacMaster AS izvodjacMaster1, 
                i2.izvodjacMaster AS izvodjacMaster2, 
                i3.izvodjacMaster AS izvodjacMaster3
            FROM albumi 
            JOIN izvodjaci i1 ON i1.idIzvodjaci = albumi.idIzvodjacAlbumi
            LEFT JOIN izvodjaci i2 ON i2.idIzvodjaci = albumi.idIzvodjac2
            LEFT JOIN izvodjaci i3 ON i3.idIzvodjaci = albumi.idIzvodjac3
            ORDER BY i1.izvodjacMaster";

        $select_tekst = mysqli_query($conn, $q);
        ?>
        <form class="visina slikeAlbumaPregled sredina">
            <div class="form-group col-md-6 mx-auto">
                <select class="form-control" name="albumi" id="albumi" onchange="SelectRedirect()">
                    <option id="prazni" value="" disabled selected>Izaberi album</option>
                    <?php
                    while ($row = mysqli_fetch_array($select_tekst))
                    {
                        $this->idAlbum = $row["idAlbum"];
                        $this->nazivAlbuma = $row["nazivAlbuma"];
                        $this->godinaIzdanja = $row["godinaIzdanja"];
                        $this->idIzvodjacAlbumi = $row["idIzvodjacAlbumi"];

                        $izvodjac1 = $row["izvodjacMaster1"];
                        $this->izvodjac2 = $row["izvodjacMaster2"];
                        $this->izvodjac3 = $row["izvodjacMaster3"];

                        $labela = $izvodjac1;

                        if (!empty($this->izvodjac3)) {
                            $izvodjac1 .= ", $this->izvodjac2, $this->izvodjac3";
                        } elseif (!empty($izvodjac2)) {
                            $izvodjac1 .= " & $izvodjac2";
                        }

                        $izvodjac1.= " - $this->nazivAlbuma ($this->godinaIzdanja.)";
                        ?>
                        <option value="<?php echo htmlspecialchars($this->idAlbum); ?>">
                            <?php echo $izvodjac1; ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div><!-- .form-group -->
        </form>

        <script language="javascript">
            function SelectRedirect() {
                let alb = document.getElementById('albumi').value;
                if (alb) {
                    window.location = `showsinglealbum.php?idAlb=${alb}`;
                }
            }
        </script>
        <?php
    }//end adminSpisakAlbuma()

    /********************************* Pozvana metoda u ovom fajlu u metodi prikazAdminEditPanela(), i u fajlu showsinglealbum.php *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda za prikaz svih izvođača  *********************************//
    protected function adminSpisakIzvodjaca()
    {
        global $conn;
        $q= "SELECT * FROM izvodjaci ORDER BY izvodjacMaster";

        $select_izvodjac= mysqli_query($conn, $q);
        ?>        
            <form class="visina slikeAlbumaPregled sredina">
                <div class="form-group col-md-6 mx-auto">
                    <select class="form-control" id="izvodjaci" name="izvodjaci" onchange="SelectRedirect()">
                        <option disabled selected value>Izaberi Izvođača</option>
                        <?php
                        while($row= mysqli_fetch_array($select_izvodjac))
                        {                
                            $this->izvodjacMaster= $row["izvodjacMaster"];
                            $this->idIzvodjaci= $row["idIzvodjaci"];
                            ?>
                            <option class="" value="<?php echo $this->idIzvodjaci; ?>"><a class="text-decoration-none" href="upadateartist.php?idIzv=<?php echo $this->idIzvodjaci; ?>"><?php echo $this->izvodjacMaster; ?></a></option>
                            <?php 
                        }
                        ?>
                    </select>
                </div><!-- .form-group -->
            </form>
        <script language="javascript">
        function SelectRedirect(){
        let x=document.getElementById('izvodjaci').value;
        //console.log(x);
        window.location=`adminupdateartist.php?idIzv=${x}`;
        }
        </script>
        <?php    
    }//end adminSpisakIzvodjaca()

    //********************************* Pozvana metoda u ovom fajlu u metodi prikazAdminEditPanela()  *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda za prikaz svih korisnika  *********************************//
    private function adminSpisakKorisnika()
    {
        global $conn;
        $q= "SELECT * FROM korisnici JOIN status_korisnika ON statusKorisnika=idStatusKorisnika";

        $select_korisnici= mysqli_query($conn, $q);
        ?>
        <table class="visina tableAdmin ">
            <tbody>
                <tr>
                    <th>Redni broj</th>
                    <th>Korisničko ime</th>
                    <th>Željeni Tip Korisnika</th>
                    <!--<th>Status</th>-->
                    <th>Stvarni tip korisnika</th>
                </tr>
                <?php
                while($row= mysqli_fetch_array($select_korisnici))
                {
                    $this->idKorisnici= $row["idKorisnici"];
                    $this->username= $row["username"];
                    $this->tipKorisnika= $row["tipKorisnika"];
                    $this->statusKorisnika= $row["statusKorisnika"];
                    $this->nazivStatusaKorisnika= $row["nazivStatusaKorisnika"];
                    ?>
                    <style>
                        .role-admin { color: aliceblue; }
                        .role-moderator { color: #f3db7b; }
                        .role-Izvođač { color: #01cee5; }
                        .role-Izdavač\/Label { color: #6d6dff; }
                        .role-Blokiran { color: red; }
                        .role-default { color: gray; }
                    </style>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var roleElements = document.querySelectorAll('.user-role');
                            roleElements.forEach(function(element) {
                                var role = element.getAttribute('data-role');
                                element.className = 'role role-' + (role ? role : 'default');
                            });
                        })
                    </script>
                    <?php
                    echo "<tr>
                    <td>$this->idKorisnici</td>";
                    echo "<td><a class='clickLink' href='adminviewusers.php?idus=$this->idKorisnici'>$this->username</a></td>";
                    echo "<td class='user-role' data-role='$this->tipKorisnika'>$this->tipKorisnika</td>";
                    //echo "<td>$this->statusKorisnika</td>";
                    echo "<td class='user-role' data-role='$this->nazivStatusaKorisnika'>$this->nazivStatusaKorisnika</td>
                    </tr>";
                }//end while
                ?>
            </tbody>
        </table>
        <?php
    }//end adminSpisakKorisnika()

    //********************************* Pozvana metoda u ovom fajlu u metodi prikazAdminEditPanela()  *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda za prikaz svih komentara  *********************************//
    protected function adminPrikazKomentara()
    {
        global $conn;
        $q="SELECT recenzije.*, korisnici.idKorisnici, korisnici.username, korisnici.profilnaSlika, albumi.nazivAlbuma, albumi.slikaAlbuma FROM recenzije 
        JOIN korisnici ON recenzije.korisnikId=korisnici.idKorisnici
        JOIN albumi ON recenzije.albumId=albumi.idAlbum";
        $sviKomentari= mysqli_query($conn, $q);
    
        while($row= mysqli_fetch_array($sviKomentari))
        {
            $this->idRecenzije= $row["idRecenzije"];
            $this->recenzija= $row["recenzija"];
            $this->vrijemeRecenzije= $row["vrijemeRecenzije"];
            $this->idKorisnici= $row["idKorisnici"];
            $this->username= $row["username"];
            $this->profilnaSlika= $row["profilnaSlika"];
            $this->nazivAlbuma= $row["nazivAlbuma"];
            $this->slikaAlbuma= $row["slikaAlbuma"];
    
            $cleanUsername= str_replace(" ", "-", removeSpecialLetters($this->username));
    
            $datumRec= strtotime($this->vrijemeRecenzije);
            $vrijemeRecenzije= date("d.m.Y. H:i:s", $datumRec);
    
            ?>
            <div class="sredinaNapomenaKomentari">
                <div class="prikazKomentara m-2 p-2">
                    
                    <div class='clear-both'>
                        <div class='float-left'>
                            <h4 class=''>Korisnik: <a class='boja ' href='adminviewusers.php?idus=<?php echo $this->idKorisnici; ?>'><?php echo $this->username; ?></a></h4>
                        </div><!-- .float-left -->
                        <p class='vrijemeRecenzije float-right'><?php echo $vrijemeRecenzije; ?></p>
                    </div><!-- .clear-both --><br><br>
                    <img src='../images/profilne/<?php echo $this->profilnaSlika; ?>'/> 
                    <p><?php echo $this->recenzija; ?></p>   
                </div><!-- .prikazKomentara -->
                <div class="komentarAdminAlbum p-2">
                    <p class=''>Album:<br> <?php echo $this->nazivAlbuma; ?></p><br>
                    <a href=""><img class='float-left' src='../images/albumi/<?php echo $this->slikaAlbuma; ?>'/></a>
                    <?php
                    if(isset($_POST["brisanjeKomentara"]))
                    {
                        $obrisanKomentar= $_POST["brisanjeKomentara"];
                        $q= "DELETE FROM recenzije WHERE idRecenzije={$obrisanKomentar}";
                        mysqli_query($conn,$q);
                        echo "<meta http-equiv='refresh' content='0'; url='admin/indexadmin.php'>";
                    }
                    ?> 
                    <form action="" method="POST">
                    <button class="btn btn-danger float-right brisanjeKomentara" name="brisanjeKomentara" value="<?php echo $this->idRecenzije; ?>">Obriši komentar</button>
                    </form>
                </div><!-- .komentarAdminAlbum -->
            </div><!-- .sredinaNapomenaKomentari -->
            <?php
        }//end while
    
    }//end adminPrikazKomentara()

    //********************************* Pozvana metoda u ovom fajlu u metodi prikazAdminEditPanela()  *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda za prikaz svih izdavača  *********************************//
    protected function adminSpisakIzdavaca()
    {
        global $conn;
        $q= "SELECT * FROM izdavaci ORDER BY izdavaciNaziv";
        $select_izdavace= mysqli_query($conn, $q);
        ?>
        <form class="visina slikeAlbumaPregled sredina">
        <div class="form-group col-md-6 mx-auto">
            <select class="form-control" name="updateLabel" id="updateLabel" onchange="SelectRedirect()">
            <option id="prazni" value="" disabled selected>Izaberi izdavača/Label</option>
            <?php
            while($row= mysqli_fetch_array($select_izdavace))
            {
            
                $this->idIzdavaci= $row["idIzdavaci"];
                $this->izdavaciNaziv= $row["izdavaciNaziv"];
                //$this->izdavaciStatus= $row["izdavaciStatus"];
                $this->izdavaciOpis= $row["izdavaciOpis"];
                $this->logoLabel= $row["izdavaciLogo"];

                ?>
                <option class="" value="<?php echo $this->idIzdavaci; ?>"><?php echo "$this->izdavaciNaziv"; ?></a></option>   
                <?php 
            }
        ?>
            </select>
        </div><!-- .form-group -->
        </form>
        <script language="javascript">
            function SelectRedirect(){
            let label=document.getElementById('updateLabel').value;
            window.location=`showeditlabel.php?idLab=${label}`;
            }
        </script>  

        <?php
    }//end adminSpisakIzdavaca()

    //********************************* Pozvana metoda u ovom fajlu u metodi prikazAdminEditPanela() *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda sa kojom vršimo izmjenu izdavača/labela  *********************************//
    public function adminUpdateLabel($idLabel)
    {
        //-------------------------------------------------------------------
        include_once "../classes/insertData-classes/imageUploader.class.php";
        $uploader = new ImageUploader();
        //-------------------------------------------------------------------
        global $conn;
        $imgWork= new adminWorkImages();
        ?>
        <br>
        <div id="" class="pregledAlbumaUredi">
            <?php 
            $q= "SELECT * FROM izdavaci WHERE idIzdavaci='{$idLabel}'";
            $select_izdavaci= mysqli_query($conn, $q);

            while($red= mysqli_fetch_array($select_izdavaci))
            {
                $this->idIzdavaci= $red["idIzdavaci"];
                $this->izdavaciNaziv= $red["izdavaciNaziv"];
                $this->izdavaciOpis= $red["izdavaciOpis"];
                $this->logoLabel= $red["izdavaciLogo"];
                $this->izdavaciStatus= $red["userAdminIzdavac"];

                if(isset($_POST["obrisiLogoIzdavaca"])){
                    $imgWork->obrisiLogoIzdavaca($idLabel);
                    logLabelImageDeleted($this->idIzdavaci, $this->izdavaciNaziv);
                }
                ?>
                <div class="col-md-3">
                    <div class="slikeAlbumaPregled sredina">
                        <div class="editAlbum">
                            <p class="text-light">Logo Izdavača/Label-a</p>
                        
                            <img src="../images/labels/<?php echo $this->logoLabel; ?>" alt="" title="<?php echo $this->izdavaciNaziv; ?>" class=""/>
                            <br><br>
                            
                            <form method="POST" action="" enctype="multipart/form-data" name="insertLabel" id="insertLabel">
                                <label for="logoLabel" class="text-light">Promijenite logo izdavača/labela</label><br>
                                <input type="file" class="btn btn-light" name="logoLabel"><br><br>
                                <label for="logoLabel" class="boja">Slika ne može da bude manja od 7kb i veća od 2mb</label><br>
                                <br><hr class="hrLinija">
                                <button type="submit" class="btn btn-danger" name="obrisiLogoIzdavaca" value="obrisiLogoIzdavaca">Obriši</button><br><br>
                        </div><!-- end .editAlbum --><br>
                    </div><!-- end .slikeAlbumaPregled .sredina -->
                </div><!-- /.col-md-3 -->
                        
                    <div class="col-md-5 sredina2"> 
                        <label for="izdavac" class="text-warning">Upisati naziv Izdavača (Labela-a)</label><br>
                        <input type="text" name="izdavacNaziv" class="form-control form-control-sm text-dark" placeholder="Bassivity Music" value="<?php echo $this->izdavaciNaziv; ?>"><br><br>

                        <label for="detaljiIzdavac" class="text-light">Detalji o izdavačkoj kući/Label-u (nije obavezno)</label><br>
                        <textarea class="dodajTekst" name="detaljiIzdavac" 
                        placeholder="Ovaj izdavačaka kuća/label postoji od ...."><?php echo $this->izdavaciOpis; ?></textarea><br><br>
                        <button class="btn btn-primary mb-0" type="submit" name="posalji" >Pošalji</button>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input class="btn btn-warning mt-0" type="reset" value="Reset">
                    </div><!-- /.col-md-5 -->
                </form>
                <?php
            }//end while
            ?> 
        </div><!-- end .pregledAlbuma -->   
        <?php 
        if(isset($_POST["posalji"]))
        {
            // 1) OLD stanje iz baze (prije UPDATE)
            $qOld = "SELECT izdavaciNaziv, izdavaciOpis FROM izdavaci WHERE idIzdavaci='{$idLabel}' LIMIT 1";
            $rOld = mysqli_query($conn, $qOld);
            $old  = mysqli_fetch_assoc($rOld) ?: [];

            if (empty($old)) {
                echo "Greška: izdavač/Label nije pronađen.";
                return;
            }

            // 2) NEW stanje iz forme
            $this->izdavaciNaziv= trim(removeSimbols($_POST["izdavacNaziv"] ?? ''));
            $this->izdavaciOpis= trim(cleanText($_POST["detaljiIzdavac"] ?? ''));

            $new = [
                'izdavaciNaziv'      => ($this->izdavaciNaziv === '' ? null : $this->izdavaciNaziv),
                'izdavaciOpis'  => ($this->izdavaciOpis === '' ? null : $this->izdavaciOpis),
            ];

            // 3) DIFF old/new
            $changes = [];

            foreach ($new as $k => $v) {
                $oldVal = $old[$k] ?? null;

                if ($oldVal === '') $oldVal = null;
                if ($v === '') $v = null;

                if ($oldVal != $v) {

                    // Opis može biti dugačak -> ne loguj sadržaj
                    if ($k === 'izdavaciOpis') {
                        $changes[$k] = [
                            'old_len' => is_string($oldVal) ? mb_strlen($oldVal, 'UTF-8') : 0,
                            'new_len' => is_string($v) ? mb_strlen($v, 'UTF-8') : 0
                        ];
                    } else {
                        $changes[$k] = [
                            'old' => $oldVal,
                            'new' => $v
                        ];
                    }
                }
            }//end foreach

            // 4) Ako nema promjena - nema update/log (ali logo može da se uploaduje)
            $hasLogoUpload = !empty($_FILES["logoLabel"]["name"]);

            if (empty($changes) && !$hasLogoUpload) {
                echo "<h4 class='boja sredina'>Nema promjena za snimiti.</h4>";
                return;
            }


            if(!empty($this->izdavaciNaziv)){
                $q_updateIzdavac= "UPDATE izdavaci SET izdavaciNaziv='{$this->izdavaciNaziv}', izdavaciOpis='{$this->izdavaciOpis}' WHERE idIzdavaci='{$idLabel}'"; 
                $insert_izdavac= mysqli_query($conn, $q_updateIzdavac);
            }else{
                echo "<h2>Morate unijeti naziv izdavača/labela</h2> <br>";
            }
             
            if(@$insert_izdavac == TRUE)
            {
                logLabelUpdated($idLabel, $this->izdavaciNaziv, $changes);
                //$this->idIzdavaci= mysqli_insert_id($conn);  //hvata zadnji insertovani ID
                

                if(!empty($_FILES["logoLabel"]["name"]) && $_FILES["logoLabel"]["error"] === UPLOAD_ERR_OK)
                {
                    $res = $uploader->uploadAndUpdateImageField("logoLabel", "../images/labels/", "promjena_label_logo", (int)$idLabel, $conn,"izdavaci", /* tabela*/ "izdavaciLogo",  /*kolona slike*/ "idIzdavaci", /* id kolona*/ 75);
                    //print_r($this->idIzdavaci);
                }
                echo "<meta http-equiv='refresh' content='0'>";
            }else{
                echo "Greška <br>";
            }
        }//**end if(isset($_POST["posalji"])) **//
    }//** end adminUpdateLabel() **//

    //********************************* Pozvana u ovom fajlu showeditlabel.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda sa kojom dodajemo logo labela  *********************************//
    protected function unosLogoLabel($logoLabel, $idLabel, $izdavaciNaziv)
    {
        global $conn; 

        $whitelist = array(".jpg",".jpeg",".gif",".png", ".svg", ".webp"); 
        //$whitelist= array("image/jpeg", "image/png", "image/gif", "image/svg", "image/webp", "image/apng", "image/avif");

        $maxVelicinaSlike= 2097152; //2mb
        $minVelicinaSlike= 7000; //7kb 
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            $size= $_FILES["logoLabel"]["size"];
            //print_r($size) . "<hr>";
            if(($size > $maxVelicinaSlike) || ($size< $minVelicinaSlike))
            {  
                echo "<script>
                            document.getElementById('promSlik').innerHTML='Prevelika slika (veća od 2mb) ili je premala slika (manja od 10kb)';
                </script>"; 
            }else
                {
                    $putanja = "../images/labels/";
                    $skeniraj= scandir($putanja);
                    //print_r($skeniraj);

                    /*$provjeraEkstenzije1= pathinfo($putanja.$slikaAlbuma);
                    $provjeraEkstenzije2= $provjeraEkstenzije1['extension'];*/

                    $imeSlike= removeSimbolsImg($logoLabel);
                    $ukloniEkstenziju= explode(".", $imeSlike);
                    $ekstenzija= end($ukloniEkstenziju);
                    $vrijeme= "_im".date("dmY_His", time())."_".time().".";
                    $slikaVrijeme= $ukloniEkstenziju[0].$vrijeme;
                    //$slikaVrijeme= array_shift($ukloniEkstenziju);

                    //if provjera ekstenzije
                    if (!(in_array(".".$ekstenzija, $whitelist))) 
                    {
                        die('Nepravilan format slike, pokušajte sa drugom slikom');
                    }else
                        {
                            $provjeraSlike= $putanja.$slikaVrijeme.$ekstenzija;
                            if(!file_exists(($provjeraSlike)))
                            {
                                $slikaVrijeme= $slikaVrijeme.$ekstenzija;
                                $slikaAlbuma_tmp= $_FILES["logoLabel"]["tmp_name"];
                                move_uploaded_file($slikaAlbuma_tmp, $provjeraSlike);
                            }//end else !file_exists(($provjeraSlike)

                                
                                $q_insertSliku="UPDATE izdavaci SET izdavaciLogo='{$slikaVrijeme}' WHERE idIzdavaci='{$this->idIzdavaci}'";
                                $dodajSlikuLabel= mysqli_query($conn, $q_insertSliku);
                                move_uploaded_file($slikaAlbuma_tmp, $provjeraSlike);

                                            

                                if($dodajSlikuLabel == TRUE)
                                {
                                    logLabelImageUpdated($idLabel, $izdavaciNaziv);
                                    echo "<meta http-equiv='refresh' content='0'>";
                                }else{
                                    echo "Greška " . mysqli_error($conn). "<br>";

                                }       
                        }//end if else provjera ekstenzije
                }//end if else provjera veličine
        }// end if provjera REQUEST_METHOD

    }//end function unosLogoLabel()

    //********************************* Pozvana u ovom fajlu u metodi adminupdatelabel() *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda sa kojom vršimo izmjene streamova za albume  *********************************//

    private function adminSpisakAlbumaZaStrimove()
    {
        global $conn;

        $q = "SELECT a.*, i.izvodjacMaster AS izvodjacMaster,
            COALESCE(
                ( (s.youtubeVideo  IS NOT NULL) +
                (s.spotify       IS NOT NULL) +
                (s.deezer        IS NOT NULL) +
                (s.appleMusic    IS NOT NULL) +
                (s.tidal         IS NOT NULL) +
                (s.youtubeMusic  IS NOT NULL) +
                (s.amazonMusic   IS NOT NULL) +
                (s.soundCloud    IS NOT NULL) +
                (s.amazonShop    IS NOT NULL) +
                (s.bandCamp      IS NOT NULL) +
                (s.qobuz         IS NOT NULL)
                ),
                0
            ) AS streams_popunjeno
            FROM albumi a
            LEFT JOIN izvodjaci i ON i.idIzvodjaci = a.idIzvodjacAlbumi
            LEFT JOIN streamovi s ON s.albumId = a.idAlbum
            ORDER BY i.izvodjacMaster, a.nazivAlbuma";

        $select_tekst = mysqli_query($conn, $q);
        ?>
        <form class="visina slikeAlbumaPregled sredina">
            <div class="form-group col-md-6 mx-auto">
                <select class="form-control" name="albumi" id="albumi" onchange="SelectRedirect()">
                    <option id="prazni" value="" disabled selected>Izaberi album</option>
                    <?php
                    while ($row = mysqli_fetch_array($select_tekst)) {
                        $this->idAlbum = $row["idAlbum"];
                        $this->nazivAlbuma = $row["nazivAlbuma"];
                        $this->godinaIzdanja = $row["godinaIzdanja"];
                        $this->idIzvodjacAlbumi = $row["idIzvodjacAlbumi"];

                        $this->izvodjacMaster = $row["izvodjacMaster"];
                        $this->izvodjac2      = $row["izvodjac2"];
                        $this->izvodjac3      = $row["izvodjac3"];

                        $labela = $this->izvodjacMaster;

                        $pop = (int)$row["streams_popunjeno"];

                        if($pop == 0){
                            $badge = "[0/11 ❌]";
                        }elseif($pop < 11){
                            $badge = "[$pop/11 ⏳]";
                        }else{
                            $badge = "[11/11 ✔]";
                        }

                        if (!empty($this->izvodjac3)) {
                            $labela .= ", $this->izvodjac2, $this->izvodjac3";
                        } elseif (!empty($this->izvodjac2)) {
                            $labela .= " & $this->izvodjac2";
                        }

                        $labela .= " - $this->nazivAlbuma ($this->godinaIzdanja.)";
                        ?>
                        <option value="<?php echo htmlspecialchars($this->idAlbum); ?>">
                            <?php echo $labela . " $badge"; ?>
                        </option>
                        <?php
                    }//end while
                    ?>
                </select>
            </div><!-- .form-group -->
        </form>

        <script>
            function SelectRedirect() {
                let alb = document.getElementById('albumi').value;
                if (alb) {
                    window.location = `editstreams.php?idAlb=${alb}`;
                }
            }
        </script>
        <?php
    }//end adminSpisakAlbumaZaStrimove()


    //********************************* Metoda pozvana u ovom fajlu u metodi prikazAdminEditPanela() i u fajlu showsignlealbum.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda za dodavanje teksta za pjesmu na unijetom albumu  *********************************//
   private function adminTekstoviPjesama($sesId)
    {
        global $conn;
        include_once "../functions/removeSymbols.func.php";
        include_once "../classes/insertData-classes/insertStreams.class.php";
        $newStream= new insertStreaming();

        // CSRF token
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Upit za dohvat pjesama bez teksta
        $q = "SELECT 
                pjesme.idPjesme,
                pjesme.nazivPjesme,
                pjesme.mixtapeIzvodjac,
                albumi.nazivAlbuma,
                izvodjaci.izvodjacMaster
            FROM pjesme
            JOIN albumi ON albumi.idAlbum = pjesme.albumId
            JOIN izvodjaci ON izvodjaci.idIzvodjaci = albumi.idIzvodjacAlbumi
            WHERE (pjesme.tekstPjesme = '' OR pjesme.tekstPjesme IS NULL)
            ORDER BY pjesme.nazivPjesme ASC";

        $select_izvodjac = mysqli_query($conn, $q);
        ?>

        <style>
            #textInput {
                display: none;
            }
            .lista-pjesama {
                max-height: 500px;
                overflow-y: auto;
                border: 1px solid #ccc;
                border-radius: 6px;
                padding: 10px;
            }
            .pjesma-item {
                cursor: pointer;
                padding: 6px;
                border-bottom: 1px solid #eee;
            }
            .pjesma-item:hover {
                background-color: #f7f7f7;
            }
            .pjesma-item.active-song {
                background-color: #cce5ff;
                border-left: 4px solid #007bff;
            }
        </style>

        <h3 class="boja sredina">Pjesme koje nemaju dodat tekst</h3>
        <h4 class="boja sredina">Obavezno koristite ispravna slova ć, č, ž, đ, š ili Ћирилицу</h4>

        <form class="visina slikeAlbumaPregled sredina" method="POST" action="" enctype="multipart/form-data" name="dodajTekst" id="dodajTekst">
            <div class="form-group col-md-6 mx-auto">

                <!-- Lista pjesama -->
                <!-- Polje za pretragu -->
                <div class="mb-3">
                    <input type="text" id="filterInput" class="form-control" placeholder="Pretraži pjesme po nazivu, izvođaču ili albumu...">
                </div>
                <div id="listaPjesama" class="lista-pjesama">
                    <?php while ($row = mysqli_fetch_array($select_izvodjac)) :
                        $idPjesme = (int)$row['idPjesme'];
                        $nazivPjesme = $row['nazivPjesme'];
                        $this->izvodjacMaster = $row['izvodjacMaster'];
                        $album = $row['nazivAlbuma'];
                        $this->mixtapeIzvodjac= $row["mixtapeIzvodjac"];

                        $nazivIzvodjac= ($this->mixtapeIzvodjac==null || $this->mixtapeIzvodjac=="") ? $this->izvodjacMaster : $this->mixtapeIzvodjac;
                    ?>
                        <div class="pjesma-item" data-id="<?php echo $idPjesme; ?>">
                            🎵 <strong><?php echo $nazivPjesme; ?></strong>
                            <span>(<?php echo $nazivIzvodjac; ?> — <?php echo $album; ?>)</span>
                        </div>
                    <?php endwhile; ?>
                </div><!-- end .listaPjesama -->

                <!-- JS logika za klik na pjesmu -->
                <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const items = document.querySelectorAll('.pjesma-item');
                    const textInput = document.getElementById('textInput');
                    const songTitle = document.getElementById('selectedSong');
                    const songId = document.getElementById('pjesmaId');

                    items.forEach(item => {
                        item.addEventListener('click', () => {
                            const id = item.dataset.id;
                            const naziv = item.querySelector('strong').textContent;

                            songId.value = id;
                            songTitle.textContent = naziv;
                            textInput.style.display = 'block';

                            document.querySelectorAll('.pjesma-item').forEach(el => el.classList.remove('active-song'));
                            item.classList.add('active-song');

                            textInput.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        });
                    });
                });
                </script>
                
                <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const filterInput = document.getElementById('filterInput');
                    const songItems = document.querySelectorAll('.pjesma-item');

                    filterInput.addEventListener('input', () => {
                        const filterValue = filterInput.value.toLowerCase();

                        songItems.forEach(item => {
                            const text = item.textContent.toLowerCase();
                            if (text.includes(filterValue)) {
                                item.style.display = '';
                            } else {
                                item.style.display = 'none';
                            }
                        });
                    });
                });
                </script>


                <!-- Forma za dodavanje teksta -->
                <div id="textInput">
                    <h3 class="boja" id="selectedSong"></h3><br>
                    <input type="hidden" name="pjesmaId" id="pjesmaId">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <textarea class="dodajTekst" name="tekstPjesme"></textarea><br><br>

                    <h5 class="sredina text-info">Dodavanje linka za pjesmu je moguće samo ako dodate i tekst</h5>
                    <label for="youtubeJednaPjesma" class="text-warning"><strong>Youtube Link</strong></label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://www.youtube.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="youtubeJednaPjesma" class="form-control form-control-sm text-danger" value="">
                    </div><!-- end .input-group --><br><br>

                    <input class="btn btn-primary mb-0" type="submit" name="posalji" value="Pošalji">
                </div><!-- end #textInput -->
            </div><!-- end .form-group -->
        </form>

        <?php
        // Obrada forme
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die("<h1 class='boja sredina'>Neovlašćen pristup.</h1>");
            }

            $this->idPjesme = sanitizeInt($_POST["pjesmaId"]);
            $this->tekstPjesme = cleanText($_POST["tekstPjesme"]);

            $youtubeJednaPjesma= $newStream->cleanStreamsYoutubeVideo($_POST["youtubeJednaPjesma"]);

            // Dohvati naziv pjesme za log (tačno po ID-u)
            $qNaziv = "SELECT nazivPjesme FROM pjesme WHERE idPjesme = '{$this->idPjesme}' LIMIT 1";
            $rNaziv = mysqli_query($conn, $qNaziv);
            $rowNaziv = mysqli_fetch_assoc($rNaziv);
            $nazivPjesmeLog = $rowNaziv['nazivPjesme'] ?? '';

            if (!empty($this->tekstPjesme)) {
                $q = "UPDATE pjesme 
                    SET tekstPjesme = '$this->tekstPjesme', dodaoTekst = '$sesId', youtubeJednaPjesma='$youtubeJednaPjesma' 
                    WHERE idPjesme = '$this->idPjesme'";
                $update_pjesme = mysqli_query($conn, $q);

                if ($update_pjesme== TRUE) {
                echo logSongTextUpdated($this->idPjesme, $nazivPjesmeLog);
                    echo "<h1 class='boja sredina'>Tekst pjesme je uspešno dodan.</h1>";
                    echo "<script>
                        const id = '{$this->idPjesme}';
                        const item = document.querySelector('.pjesma-item[data-id=\"' + id + '\"]');
                        if (item) item.remove();
                    </script>";
                } else {
                    echo "<h1 class='boja sredina'>Greška prilikom ažuriranja: " . mysqli_error($conn) . "</h1>";
                }
            } else {
                echo "<h1 class='boja sredina'>Morate uneti tekst pesme.</h1>";
            }
        } // end POST
    }//end function adminTekstoviPjesama()


    //********************************* Metoda pozvana u ovom fajlu u metodi prikazAdminEditPanela() i u fajlu showsinglealbum.php  *********************************//
    
    //--------------------------------------------------------------------------------------------------------------------------------
    /********************************* Metoda za prikaz i izmjenu svih singlova *********************************/
    protected function adminSpisakSinglova()
    {
        global $conn;

        $q = "SELECT * FROM singlovi ORDER BY singlNaziv";

        $select_singlovi = mysqli_query($conn, $q);
        ?>
        <form class="visina slikeAlbumaPregled sredina">
            <div class="form-group col-md-6 mx-auto">
                <select class="form-control" name="singlovi" id="singlovi" onchange="SelectRedirect()">
                    <option id="prazni" value="" disabled selected>Izaberi singl (prikazano prema nazivu singla)</option>
                    <?php
                    while ($row = mysqli_fetch_array($select_singlovi))
                    {
                        $this->idSinglovi = $row["idSinglovi"];
                        $this->singlNaziv = $row["singlNaziv"];
                        $this->singleIzvodjaci = $row["singleIzvodjaci"];
                        $this->singleFeat = $row["singleFeat"];
                        ?>
                        <option value="<?php echo htmlspecialchars($this->idSinglovi); ?>">
                            <?php echo "$this->singlNaziv - $this->singleIzvodjaci $this->singleFeat"; ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div><!-- .form-group -->
        </form>

        <script language="javascript">
            function SelectRedirect() {
                let single = document.getElementById('singlovi').value;
                if (single) {
                    window.location = `showonesingle.php?single=${single}`;
                }
            }
        </script>
        <?php
    }//end adminSpisakSinglova()

    
    /********************************* Pozvana metoda u ovom fajlu u metodi prikazAdminEditPanela(), i u fajlu showsinglealbum.php *********************************/
    //--------------------------------------------------------------------------------------------------------------------------------

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda za prikaz opvija obavještenja  *********************************//
    protected function adminObavjestenja()
    {
        global $conn;

        ?>
        <div class="slikeAlbumaPregled" style="padding-top: 200px">
        <h2 class="naslov-centar"><a href="admin_obavjestenja.php" class="text-decoration-none text-warning">Pošalji obavještenje svim korisnicima</a></h2>
        <h2 class="naslov-centar"><a href="admin_pregled_obavjestenja.php" class="text-decoration-none text-primary">Pregled poslatih obavještenja</a></h2>
        </div>
        <?php
    }//end adminSpisakAlbuma()

    /********************************* Pozvana metoda u ovom fajlu u metodi prikazAdminEditPanela() *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda za prikaz svih kategorija  *********************************//
    private function adminKategorijeAlbuma()
    {
        global $conn;
        $q= "SELECT * FROM kategorije_albuma";

        $select_kategorije= mysqli_query($conn, $q);
        ?>
        <div class="mt-3">
            <a href="adminaddcategories.php" class="btn btn-warning mt-0" role="button">Dodaj novu kategoriju</a>
        </div><!-- end div -->

        <table class="visina tableAdmin ">
            <tbody>
                <tr>
                    <th>Redni broj</th>
                    <th>Kategorija</th>
                </tr>
                <?php
                while($row= mysqli_fetch_array($select_kategorije))
                {
                    $this->idKategorijeAlbuma= $row["idKategorijeAlbuma"];
                    $this->nazivKategorijeAlbuma= $row["nazivKategorijeAlbuma"];
                    ?>
                    
                    <?php
                    echo "<tr>
                    <td>$this->idKategorijeAlbuma</td>";
                    echo "<td><a class='clickLink' href='adminupdatecategories.php?idcat=$this->idKategorijeAlbuma'>$this->nazivKategorijeAlbuma</a></td>";
                    echo "</tr>";
                }//end while
                ?>
            </tbody>
        </table>
        <?php
    }//end adminSpisakKorisnika()

    //********************************* Pozvana metoda u ovom fajlu u metodi prikazAdminEditPanela()  *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda sa kojom vršimo izmjenu kategorije  *********************************//
    public function adminUpdateCategory($idCategory)
    {
        global $conn;
        ?>
        <br>
        <div id="" class="pregledAlbumaUredi">
            <?php 
            $q= "SELECT * FROM kategorije_albuma WHERE idKategorijeAlbuma='{$idCategory}'";
            $select_izdavaci= mysqli_query($conn, $q);

            while($red= mysqli_fetch_array($select_izdavaci))
            {
                $this->idKategorijeAlbuma= $red["idKategorijeAlbuma"];
                $this->nazivKategorijeAlbuma= $red["nazivKategorijeAlbuma"];
                $this->opisKategorijeAlbuma= $red["opisKategorijeAlbuma"];

                ?>
                <div class="col-md-3">
                    <div class="slikeAlbumaPregled sredina">
                        <div class="editAlbum">
                            <form method="POST" action="" enctype="multipart/form-data" name="updateCategories" id="updateCategories">
                                
                        </div><!-- end .editAlbum --><br>
                    </div><!-- end .slikeAlbumaPregled .sredina -->
                </div><!-- /.col-md-3 -->
                        
                    <div class="col-md-5 sredina2"> 
                        <label for="izdavac" class="text-warning">Ispraviti naziv kategorije</label><br>
                        <input type="text" name="kategorijaNaziv" class="form-control form-control-sm text-dark" placeholder="Rap & RnB" value="<?php echo $this->nazivKategorijeAlbuma; ?>"><br><br>

                        <label for="opisKategorije" class="text-light">Opis o kategoriji (nije obavezno)</label><br>
                        <textarea class="dodajTekst" name="opisKategorije" 
                        placeholder=""><?php echo $this->opisKategorijeAlbuma; ?></textarea><br><br>
                        <button class="btn btn-primary mb-0" type="submit" name="posalji" >Pošalji</button>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input class="btn btn-warning mt-0" type="reset" value="Reset">
                    </div><!-- /.col-md-5 -->
                </form>
                <?php
            }//end while
            ?> 
        </div><!-- end .pregledAlbuma -->   
        <?php 
        if(isset($_POST["posalji"]))
        {
            $this->nazivKategorijeAlbuma= trim(removeSimbols($_POST["kategorijaNaziv"]));
            $this->opisKategorijeAlbuma= trim(removeSimbols($_POST["opisKategorije"]));

            if(!empty($this->nazivKategorijeAlbuma)){
                $q_updateKategoriju= "UPDATE kategorije_albuma SET nazivKategorijeAlbuma='{$this->nazivKategorijeAlbuma}', opisKategorijeAlbuma='{$this->opisKategorijeAlbuma}' WHERE idKategorijeAlbuma='{$idCategory}'"; 
                $update_kategorija= mysqli_query($conn, $q_updateKategoriju);
            }else{
                echo "<h2>Morate unijeti naziv kategorije!</h2> <br>";
            }
             
            if($update_kategorija == TRUE)
            {
                //$this->idIzdavaci= mysqli_insert_id($conn);  //hvata zadnji insertovani ID
                echo "<meta http-equiv='refresh' content='0'>";
            }else{
                echo "Greška <br>";
            }
        }//end if(isset($_POST["posalji"]))
    }//end adminUpdateCategory() 

    //********************************* Pozvana u ovom fajlu adminupdatecategories.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda sa kojom dodajemo NOVU kategorije  *********************************//
    public function adminAddCategory()
    {
        global $conn;
        ?>
        <br>
        <div id="" class="pregledAlbumaUredi">
                <div class="col-md-3">
                    <div class="slikeAlbumaPregled sredina">
                        <div class="editAlbum">
                            <form method="POST" action="" enctype="multipart/form-data" name="insertCategories" id="insertCategories">
                                
                        </div><!-- end .editAlbum --><br>
                    </div><!-- end .slikeAlbumaPregled .sredina -->
                </div><!-- /.col-md-3 -->
                        
                    <div class="col-md-5 sredina2"> 
                        <label for="izdavac" class="text-warning">Dodaj naziv kategorije</label><br>
                        <input type="text" name="kategorijaNaziv" class="form-control form-control-sm text-dark" placeholder="Rap & RnB" value="<?php echo $this->nazivKategorijeAlbuma; ?>"><br><br>

                        <label for="opisKategorije" class="text-light">Opis o kategoriji (nije obavezno)</label><br>
                        <textarea class="dodajTekst" name="opisKategorije" 
                        placeholder=""><?php echo $this->opisKategorijeAlbuma; ?></textarea><br><br>
                        <button class="btn btn-primary mb-0" type="submit" name="posalji" >Pošalji</button>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input class="btn btn-warning mt-0" type="reset" value="Reset">
                    </div><!-- /.col-md-5 -->
                </form>
        </div><!-- end .pregledAlbuma -->   
        <?php 
        if(isset($_POST["posalji"]))
        {
            $this->nazivKategorijeAlbuma= trim(removeSimbols($_POST["kategorijaNaziv"]));
            $this->opisKategorijeAlbuma= trim(removeSimbols($_POST["opisKategorije"]));

            if(!empty($this->nazivKategorijeAlbuma)){
                $q_insertKategorija= "INSERT INTO kategorije_albuma (nazivKategorijeAlbuma, opisKategorijeAlbuma) VALUES ('{$this->nazivKategorijeAlbuma}', '{$this->opisKategorijeAlbuma}')";
                $update_kategorija= mysqli_query($conn, $q_insertKategorija);
                //echo $q_insertKategorija;
            }else{
                echo "<h2>Morate unijeti naziv kategorije!</h2> <br>";
            }
             
            if($update_kategorija == TRUE)
            {
                //$this->idIzdavaci= mysqli_insert_id($conn);  //hvata zadnji insertovani ID
                echo "<meta http-equiv='refresh' content='0'>";
            }else{
                echo "Greška <br>";
            }
        }//end if(isset($_POST["posalji"]))
    }// end adminAddCategory()

    //********************************* Pozvana u ovom fajlu adminaddcategories.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------
    
}//end class