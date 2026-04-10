<?php

class labelEditPanel{

    //METODE SADRŽANE U OVOJ KLASI
    //leftSidePanel (lijeva strana admin panela za izdavača/label)
    //prikazLabelEditPanela (prikaz kompletnog admin panela za izdavača/label)
    //leftSideLabel (prikaz vrijednosti (menija) admin panela za izdavače)
    //labelSpisakAlbuma (prikaz svih albuma za izdavača/label)
    //labelSpisakAlbumaZaStrimove (prikaz svih albuma za strimove za izdavača/label)
    //labelTekstoviPjesama (dodavanje tekstova pjesama  za izdavača/label)

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

    protected $idIzdavaci;
    protected $izdavaciNaziv;
    protected $izdavaciStatus;
    protected $izdavaciOpis;
    protected $logoLabel;

    protected $idPjesme;
    protected $tekstPjesme;

    protected $label_id;
    public $userAdmin;

    //********************************* Metoda za prikaz lijeve strane admin panela za izvodjač nalog *********************************//
    public function leftSidePanelLabel($idKorisnici, $idIzv=""){
        ?>
        <div class="col-md-2 visina leftPanel">
        <?php
        @$idIzv= $_GET["idIzv"];
        $this->leftSideLabel($idKorisnici, $idIzv);

        ?>
        </div><!-- end col-md-2 --> 
        <?php
        
    }//end leftSidePanelLabel()
    /********************************* Pozvana metoda u ovom fajlu u funkciji prikazLabelEditPanela(), takođe i u fajlovima adminupdatealbum.php, editstreams.php, showalbum.php, showeditlabel.php, updatesongs.php, updatetext.php  *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------
    
    //********************************* Metoda za prikaz kompletnog panela za izdavač/label nalog *********************************//
    public function prikazLabelEditPanela($idKorisnici, $nazivPromjeneLinka, $sesId, $sesStatusK)
    {
        global $conn;
        $q1= "SELECT * FROM albumi 
        JOIN albumi_izdavaci ON albumi_izdavaci.idAlbum=albumi.idAlbum 
        JOIN izdavaci ON izdavaci.idIzdavaci=albumi_izdavaci.idIzdavaci WHERE userAdminIzdavac='{$idKorisnici}'";
        $select_Label= mysqli_query($conn, $q1);

        while($row= mysqli_fetch_array($select_Label))
        {
            $izdavaciNaziv= $row["izdavaciNaziv"];
            $idIzdavaci= $row["idIzdavaci"];
        }
        ?>
    
        <div class="container-fluid adminMainPanel visina">
            <div class="row">
                <?php
                $this->leftSidePanelLabel($idKorisnici);
                ?>
                <div class="col-md-10 panel">
                    <?php 
                    switch($nazivPromjeneLinka)
                    {
                        case "albumi"; $this->labelSpisakAlbuma($idKorisnici); break;
                        case "strimovi"; $this->labelSpisakAlbumaZaStrimove($idKorisnici); break;
                        case "tekstovi"; $this->labelTekstoviPjesama($idKorisnici, $izdavaciNaziv); break;
                        default; ""; break;
                    }             
                    ?>
                </div><!-- end col-md-10 --> 
            </div><!-- end row --> 
        </div><!-- end container-fluid --> 
        <?php
    }//end prikazLabelEditPanela()
    //********************************* Pozvana metoda u indexadmin.php  *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------
    
    //********************************* Metoda za prikaz vrijednosti (menija) admin panela za izdavač nalog *********************************//
    public function leftSideLabel($userAdmin, $idIzv, $idLab="")
    {
        global $conn;
        global $idKorisnici;
        $q0= "SELECT * FROM korisnici JOIN status_korisnika ON statusKorisnika=idStatusKorisnika WHERE idKorisnici='{$userAdmin}'";
        $select_adminUser= mysqli_query($conn, $q0);

        while($row= mysqli_fetch_array($select_adminUser))
        {
            $this->username= $row["username"];
            $this->statusKorisnika= $row["nazivStatusaKorisnika"];
            tipKorisnikaAdmin($idKorisnici);
            ?>
            <h4 class="text-center text-danger pt-3"><strong><a href="indexadmin.php" class="text-decoration-none text-danger"><?php echo $this->username; ?></a></strong></h4> 
            <hr class="bg-light">
            <?php            
        }
        
        $q1= "SELECT * FROM izdavaci WHERE userAdminIzdavac='{$userAdmin}'";
        $select_label_by_user= mysqli_query($conn, $q1);

        while($row=mysqli_fetch_array($select_label_by_user))
        {
            $this->idIzdavaci= $row["idIzdavaci"];
            $this->izdavaciNaziv= $row["izdavaciNaziv"];
            ?>
            <a class="text-decoration-none" href="showeditlabel.php?idLab=<?php echo $this->idIzdavaci; ?>"><h5 class="text-center text-warning">O Izdavaču</h5></a>
            <hr class="bg-light">
            <a class="text-decoration-none" href="indexadmin.php?data=albumi"><h5 class="text-center text-warning">Albumi</h5></a>
            <hr class="bg-light">
            <a class="text-decoration-none" href="indexadmin.php?data=strimovi"><h5 class="text-center text-warning">Strimovi</h5></a>
            <?php
        }//end while
        ?>
        <hr class="bg-light">
        <a class="text-decoration-none" href="indexadmin.php?data=tekstovi"><h5 class="text-center text-warning">Tekstovi pjesama</h5></a>
        <hr class="bg-light">
        <?php     
        
    }//end leftSideLabel()

    //********************************* Pozvana metoda u u ovom fajlu u metodi leftSidePanelLabel()  *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

//********************************* Metoda za prikaz svih albuma za izdavača/label  *********************************//
    private function labelSpisakAlbuma($userAdminIzdavac)
    {
        global $conn;
        
        $q= "SELECT * FROM albumi 
            JOIN izvodjaci ON izvodjaci.idIzvodjaci=albumi.idIzvodjacAlbumi
            JOIN albumi_izdavaci ON albumi_izdavaci.idAlbum=albumi.idAlbum 
            JOIN izdavaci ON izdavaci.idIzdavaci=albumi_izdavaci.idIzdavaci
            WHERE izdavaci.userAdminIzdavac='{$userAdminIzdavac}' ORDER BY izvodjacMaster";
        $select_tekst= mysqli_query($conn, $q);
        
        ?>
            <form class="visina slikeAlbumaPregled sredina">
                <div class="form-group col-md-6 mx-auto">
                    <select class="form-control" name="albumi" id="albumi" onchange="SelectRedirect()">
                    <option id="prazni" value="" disabled selected>Izaberi album</option>
                    <?php

                    // Prikazivanje albuma
                    while($row= mysqli_fetch_array($select_tekst))
                    {
                        $this->idAlbum= $row["idAlbum"];
                        $this->idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
                        $this->idIzvodjac2= $row["idIzvodjac2"];
                        $this->idIzvodjac3= $row["idIzvodjac3"];
                        $this->nazivAlbuma= $row["nazivAlbuma"];
                        $this->godinaIzdanja= $row["godinaIzdanja"];
                        $this->idIzvodjaci= $row["idIzvodjaci"];
                        $this->izvodjacMaster= $row["izvodjacMaster"];
                        
                        // Dodavanje izvođača 2
                        $q2= "SELECT izvodjacMaster FROM izvodjaci WHERE idIzvodjaci='{$this->idIzvodjac2}'";
                        $select_izvodjace2=mysqli_query($conn, $q2);
                        if(mysqli_num_rows($select_izvodjace2)>0) 
                        {
                            while($row2= mysqli_fetch_array($select_izvodjace2)) {
                                $this->izvodjac2= $row2["izvodjacMaster"];
                            }
                        }

                        // Dodavanje izvođača 3
                        $q3= "SELECT izvodjacMaster FROM izvodjaci WHERE idIzvodjaci='{$this->idIzvodjac3}'";
                        $select_izvodjace3=mysqli_query($conn, $q3);
                        if(mysqli_num_rows($select_izvodjace3)>0) 
                        {
                            while($row3= mysqli_fetch_array($select_izvodjace3)) {
                                $this->izvodjac3= $row3["izvodjacMaster"];
                            }
                        }
                        
                        // Prikazivanje albuma sa izvođačima
                        if(!empty($this->idIzvodjac3)){
                            ?>
                            <option class="" value="<?php echo $this->idAlbum; ?>" data-idizv="<?php echo $this->idIzvodjacAlbumi; ?>" data-izv="<?php echo $this->izvodjacMaster; ?>"><?php echo "$this->izvodjacMaster, $this->izvodjac2, $this->izvodjac3 -  $this->nazivAlbuma ($this->godinaIzdanja.)" ; ?></option>
                            <?php
                        } else if(!empty($this->idIzvodjac2)){
                            ?>
                            <option class="" value="<?php echo $this->idAlbum; ?>" data-idizv="<?php echo $this->idIzvodjacAlbumi; ?>" data-izv="<?php echo $this->izvodjacMaster; ?>"><?php echo "$this->izvodjacMaster & $this->izvodjac2 -  $this->nazivAlbuma ($this->godinaIzdanja.)" ; ?></option>
                            <?php
                        } else {
                            ?>
                            <option class="" value="<?php echo $this->idAlbum; ?>" data-idizv="<?php echo $this->idIzvodjacAlbumi; ?>" data-izv="<?php echo $this->izvodjacMaster; ?>"><?php echo $this->izvodjacMaster . " - " . $this->nazivAlbuma . " ($this->godinaIzdanja.)" ; ?></option>
                        <?php 
                        }
                    }//end while
                    ?>
                    </select>
                </div><!-- .form-group -->
            </form>
        <script language="javascript">
            // JavaScript funkcija za preusmerenje
            function SelectRedirect()
            {
                // Uhvatiti ID albuma i izvođača
                let alb = document.getElementById('albumi').value;
                let izvodjac = document.querySelector('#albumi option:checked').getAttribute('data-izv');
                let idIzvodjaca = document.querySelector('#albumi option:checked').getAttribute('data-idizv');
                
                // Ispisivanje vrednosti u konzoli radi provere
                console.log("ID Albuma: " + alb);
                console.log("Izvođač: " + izvodjac);
                
                // Preusmeravanje na novu adresu sa oba parametra
                window.location = `showalbum.php?idIzv=${idIzvodjaca}&idAlb=${alb}`;
            }
        </script>
        <?php
    }//end labelSpisakAlbuma()

    //********************************* Pozvana metoda u ovom fajlu u metodi prikazLabelEditPanela() *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda sa kojom vršimo izmjene streamova za albume za izdavača  *********************************//

    private function labelSpisakAlbumaZaStrimove($idKorisnici)
    {
        global $conn;

        $q= "SELECT albumi.idAlbum, idIzvodjacAlbumi, idIzvodjac2, idIzvodjac3, nazivAlbuma, godinaIzdanja, idIzvodjaci, izvodjacMaster
            FROM albumi 
            JOIN izvodjaci ON izvodjaci.idIzvodjaci=albumi.idIzvodjacAlbumi
            JOIN albumi_izdavaci ON albumi_izdavaci.idAlbum=albumi.idAlbum
            JOIN izdavaci ON izdavaci.idIzdavaci=albumi_izdavaci.idIzdavaci
            WHERE izdavaci.userAdminIzdavac='{$idKorisnici}' ORDER BY izvodjacMaster";

        $select_tekst= mysqli_query($conn, $q);
        ?>
        <form class="visina slikeAlbumaPregled sredina">
            <div class="form-group col-md-6 mx-auto">
                <select class="form-control" name="albumi" id="albumi" onchange="SelectRedirect()">
                <option id="prazni" value="" disabled selected>Izaberi album</option>
                <?php
                while($row= mysqli_fetch_array($select_tekst))
                {
                    $this->idAlbum= $row["idAlbum"];
                    $this->idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
                    $this->idIzvodjac2= $row["idIzvodjac2"];
                    $this->idIzvodjac3= $row["idIzvodjac3"];
                    $this->nazivAlbuma= $row["nazivAlbuma"];
                    $this->godinaIzdanja= $row["godinaIzdanja"];
                    $this->idIzvodjaci= $row["idIzvodjaci"];
                    $this->izvodjacMaster= $row["izvodjacMaster"];

                    $q2= "SELECT izvodjacMaster FROM izvodjaci WHERE idIzvodjaci='{$this->idIzvodjac2}'";
                    $select_izvodjace2=mysqli_query($conn, $q2);

                    if(mysqli_num_rows($select_izvodjace2)>0)
                    {
                        while($row2= mysqli_fetch_array($select_izvodjace2))
                        {
                            $this->izvodjac2= $row2["izvodjacMaster"];
                        }
                        
                    }//end if($select_izvodjace2)

                    $q3= "SELECT izvodjacMaster FROM izvodjaci WHERE idIzvodjaci='{$this->idIzvodjac3}'";
                    $select_izvodjace3=mysqli_query($conn, $q3);

                    if(mysqli_num_rows($select_izvodjace3)>0)
                    {

                        while($row3= mysqli_fetch_array($select_izvodjace3))
                        {
                            $this->izvodjac3= $row3["izvodjacMaster"];
                        }
                        
                    }//end if($select_izvodjace3)
                    
                    if(!empty($this->idIzvodjac3)){
                        ?>
                        <option class="" value="<?php echo $this->idAlbum; ?>"><a class="text-decoration-none" href="indexadmin.php?albumi=<?php echo $this->idIzvodjacAlbumi; ?>&idAlb=<?php echo $this->idAlbum; ?>"><?php echo "$this->izvodjacMaster, $this->izvodjac2, $this->izvodjac3 -  $this->nazivAlbuma ($this->godinaIzdanja.)" ; ?></a></option>
                        <?php
                    }else if(!empty($this->idIzvodjac2)){
                        ?>
                        <option class="" value="<?php echo $this->idAlbum; ?>"><a class="text-decoration-none" href="indexadmin.php?albumi=<?php echo $this->idIzvodjacAlbumi; ?>&idAlb=<?php echo $this->idAlbum; ?>"><?php echo "$this->izvodjacMaster & $this->izvodjac2 -  $this->nazivAlbuma ($this->godinaIzdanja.)" ; ?></a></option>
                        <?php
                    }else{
                    ?>
                        <option class="" value="<?php echo $this->idAlbum; ?>"><a class="text-decoration-none" href="indexadmin.php?albumi=<?php echo $this->idIzvodjacAlbumi; ?>&idAlb=<?php echo $this->idAlbum; ?>"><?php echo $this->izvodjacMaster . " - " . $this->nazivAlbuma . " ($this->godinaIzdanja.)" ; ?></a></option>
                    <?php 
                    }//end else
                }//end while
                ?>
                </select>
            </div><!-- .form-group -->
        </form>
        <script language="javascript">
        function SelectRedirect()
        {
            let alb=document.getElementById('albumi').value;
            console.log(alb);
            window.location=`editstreams.php?idAlb=${alb}`;
        }
        </script>
        <?php
    }// end labelSpisakAlbumaZaStrimove()

    //********************************* Metoda pozvana u ovom fajlu u metodi prikazLabelEditPanela() *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda za dodavanje teksta za pjesmu na unijetom albumu  *********************************//
    private function labelTekstoviPjesama($idKorisnici, $izdavaciNaziv)
    {
        global $conn;

        include_once "../functions/removeSymbols.func.php";
        include_once "../classes/insertData-classes/insertStreams.class.php";
        $newStream= new insertStreaming();

        // CSRF token
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $q = "SELECT pjesme.*, albumi.nazivAlbuma, izvodjaci.izvodjacMaster
            FROM pjesme 
            JOIN albumi ON albumi.idAlbum = pjesme.albumId
            JOIN izvodjaci ON izvodjaci.idIzvodjaci = albumi.idIzvodjacAlbumi 
            JOIN albumi_izdavaci ON albumi_izdavaci.idAlbum = albumi.idAlbum
            JOIN izdavaci ON izdavaci.idIzdavaci = albumi_izdavaci.idIzdavaci
            WHERE izdavaci.userAdminIzdavac = '{$idKorisnici}'
            GROUP BY pjesme.idPjesme
            ORDER BY pjesme.nazivPjesme ASC";

        $select_izvodjac = mysqli_query($conn, $q);
        ?>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() 
            {
                $('#izvodjaci').change(function() 
                {
                    var selectedOption = $(this).find('option:selected');
                    var selectedText = selectedOption.text();
                    var selectedValue = selectedOption.val();
                    var tekstPjesme = selectedOption.data('tekst') || '';
                    var youtubeLink = selectedOption.data('youtube') || '';

                    if (selectedValue) {
                        $('#selectedSong').text(selectedText);
                        $('#pjesmaId').val(selectedValue);
                        $('#textInput').show();
                        $('textarea[name="tekstPjesme"]').val(tekstPjesme);
                        $('input[name="youtubeJednaPjesma"]').val(youtubeLink);
                    } else {
                        $('#textInput').hide();
                        $('#pjesmaId').val('');
                        $('textarea[name="tekstPjesme"]').val('');
                        $('input[name="youtubeJednaPjesma"]').val('');
                    }
                });
            });
        </script>

        <style>
            #textInput {
                display: none;
            }
        </style>

        <h3 class="boja sredina">Pjesme koje nemaju dodat tekst</h3>
        <h4 class="boja sredina">Obavezno prekontrolišite i upotrebljavajte slova ć, č, ž, đ, š ili Ћирилицу</h4>

        <form class="visina slikeAlbumaPregled sredina" method="POST" action="" enctype="multipart/form-data" name="dodajTekst" id="dodajTekst">
            <div class="form-group col-md-6 mx-auto">
                <select class="form-control" id="izvodjaci" name="izvodjaci">
                    <option disabled selected value="">Izaberi pjesmu</option>
                    <?php while ($row = mysqli_fetch_array($select_izvodjac)) : ?>
                        <option 
                            value="<?php echo (int)$row['idPjesme']; ?>" 
                            data-tekst="<?php echo htmlspecialchars($row['tekstPjesme'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                            data-youtube="<?php echo htmlspecialchars($row['youtubeJednaPjesma'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo "{$row['nazivPjesme']} ({$row['izvodjacMaster']} - Album: {$row['nazivAlbuma']})"; ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <div id="textInput">
                    <h3 class="boja" id="selectedSong"></h3><br>
                    <input type="hidden" name="pjesmaId" id="pjesmaId">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <textarea class="dodajTekst" name="tekstPjesme"><?php echo htmlspecialchars($this->tekstPjesme ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>

                    <br><br>
                    <div class="form-group col-md-9 mx-auto mb-1">
                    <label for="youtubeJednaPjesma" class="text-warning"><strong>Youtube Link</strong></label><br>
                    <div class="input-group mb-3">
                        <span class="input-group-text">https://www.youtube.com/</span>
                        <input type="text" name="youtubeJednaPjesma" class="form-control" value="">
                    </div><!-- end .input-group -->
                    </div><!-- .form-group col-md-9 mx-auto mb-1 --><br><br>

                    <input class="btn btn-primary mb-0" type="submit" name="posalji" value="Pošalji">
                </div><!-- end .textInput -->
            </div><!-- end .form-group col-md-6 mx-auto -->
        </form>

        <?php
        // Obrada forme kada se podaci pošalju
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["posalji"])) 
        {
            // Provera CSRF tokena
            if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die("<h1 class='boja sredina'>Neovlašćen pristup.</h1>");
            }

            $idPjesme = (int)($_POST["pjesmaId"] ?? 0);

            if ($idPjesme <= 0) {
                echo "<h1 class='boja sredina'>Morate izabrati pjesmu.</h1>";
                return;
            }

            // 1) UZMI STARO STANJE
            $qOld = "SELECT nazivPjesme, feat, tekstPjesme, youtubeJednaPjesma
                    FROM pjesme
                    WHERE idPjesme = '{$idPjesme}'
                    LIMIT 1";
            $rOld = mysqli_query($conn, $qOld);
            $old  = mysqli_fetch_assoc($rOld) ?: [];

            // Ako se iz nekog razloga ne nađe pjesma
            if (empty($old)) {
                echo "<h1 class='boja sredina'>Greška: pjesma nije pronađena.</h1>";
                return;
            }

            // 2) NOVO STANJE IZ FORME
            $tekstPjesme = cleanText($_POST["tekstPjesme"] ?? '');
            $youtubeJednaPjesma = $newStream->cleanStreamsYoutubeVideo($_POST["youtubeJednaPjesma"] ?? '');

            $tekstPjesme = trim($tekstPjesme);
            $youtubeJednaPjesma = trim($youtubeJednaPjesma);

            $tekstPjesme2 = ($tekstPjesme === "") ? null : $tekstPjesme;
            $youtubeJednaPjesma2 = ($youtubeJednaPjesma === "") ? null : $youtubeJednaPjesma;

            if ($tekstPjesme2 === null) {
                echo "<h1 class='boja sredina'>Morate uneti tekst pesme.</h1>";
                return;
            }

            // 3) DIFF (šta se promijenilo)
            $new = [
                'tekstPjesme' => $tekstPjesme2,
                'youtubeJednaPjesma' => $youtubeJednaPjesma2
            ];

            $changes = [];

            foreach ($new as $k => $v) {
                $oldVal = $old[$k] ?? null;

                if ($oldVal === '') $oldVal = null;
                if ($v === '') $v = null;

                if ($oldVal != $v) {
                    // Tekst može biti ogroman -> ne logujemo sadržaj
                    if ($k === 'tekstPjesme') {
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
            }

            // Ako nema promjena
            if (empty($changes)) {
                echo "<h1 class='boja sredina'>Nema izmjena za upis.</h1>";
                return;
            }

            // 4) Escape za SQL
            $tekstSql = mysqli_real_escape_string($conn, $tekstPjesme2);

            if ($youtubeJednaPjesma2 === null) {
                $youtubeSql = "NULL";
            } else {
                $youtubeSql = "'" . mysqli_real_escape_string($conn, $youtubeJednaPjesma2) . "'";
            }

            // 5) Upit za ažuriranje teksta pjesme u bazi
            $qUpdate = "UPDATE pjesme 
                        SET tekstPjesme = '{$tekstSql}',
                            youtubeJednaPjesma = {$youtubeSql},
                            dodaoTekst = '{$idKorisnici}'
                        WHERE idPjesme = '{$idPjesme}'";

            $update_pjesme = mysqli_query($conn, $qUpdate);

            if ($update_pjesme) {
                // 6) LOG
                logSongUpdated($idPjesme, $old['nazivPjesme'], $changes);
                echo "<h1 class='boja sredina'>Tekst pjesme je uspešno dodan.</h1>";
                echo "<meta http-equiv='refresh' content='7'; url='adminalbumi.php'>";
            } else {
                echo "<h1 class='boja sredina'>Greška prilikom ažuriranja: " . mysqli_error($conn) . "</h1>";
            }
        }//end if($_SERVER["REQUEST_METHOD"] == "POST")
    }//end labelTekstoviPjesama()

    //********************************* Metoda pozvana u ovom fajlu u metodi prikazLabelEditPanela()  *********************************//
    
}//end class