<?php

class artistEditPanel{

    //METODE SADRŽANE U OVOJ KLASI
    //leftSidePanelArtist (lijeva strana admin panela za izvođače)
    //prikazArtistEditPanela (prikaz kompletnog admin panela za izvođače)
    //leftSideArtist (prikaz vrijednosti (menija) admin panela za izvođače)
    //spisakAlbumaArtist (Spisak svih albuma za izvođač nalog)
    //artistSpisakAlbumaIzvodjacaStrimovi (Metoda za prikaz albuma za Strimove za izvođač nalog)
    //artistIzabraniAlbum (Metoda za prikaz izabranog albuma za izvođač nalog)
    //artistTekstoviPjesama (Metoda za dodavanje teksta za pjesmu na unijetom albumu za izvođač nalog)

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

    public $userAdmin;

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

    //********************************* Metoda za prikaz lijeve strane admin panela za izvodjač nalog *********************************//
    public function leftSidePanelArtist($idKorisnici, $idIzv=""){
        ?>
        <div class="col-md-2 visina leftPanel">
        <?php
        @$idIzv= $_GET["idIzv"];
        $this->leftSideArtist($idKorisnici, $idIzv);

        ?>
        </div><!-- end col-md-2 --> 
        <?php
    }//end leftSidePanelArtist()
    /********************************* Pozvana metoda u ovom fajlu u funkciji prikazArtistEditPanela(), takođe i u fajlovima 
    adminupdatealbum.php, adminupdateartist.php,showalbum.php, showalbumstreams.php, updatesongs.php, updatetext.php *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------
    
    //********************************* Metoda za prikaz kompletnog artist panela *********************************//
    public function prikazArtistEditPanela($idKorisnici, $nazivPromjeneLinka, $sesId, $userAdmin)
    {
        //$idKorisnici predstavljaju status korisnika
        ?>
        <div class="container-fluid adminMainPanel visina">
            <div class="row">
                <?php
                $this->leftSidePanelArtist($idKorisnici);
                ?>
                <div class="col-md-10 panel">
                    <?php 
                    switch($nazivPromjeneLinka)
                    {
                        case "tekstovi"; $this->artistTekstoviPjesama($sesId, $userAdmin); break;
                        default; ""; break;
                    }                      
                    ?>
                </div><!-- end col-md-10 --> 
            </div><!-- end row --> 
        </div><!-- end container-fluid --> 
        <?php
    }//end prikazArtistEditPanela()
    //********************************* Pozvana metoda u indexadmin.php  *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------
    
    //********************************* Metoda za prikaz vrijednosti (menija) admin panela za izvođače *********************************//
    public function leftSideArtist($userAdmin, $idIzv, $idLab="")
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
        }//end while
        
        $q1= "SELECT * FROM izvodjaci WHERE userAdmin='{$userAdmin}'";
        $select_artist_by_user= mysqli_query($conn, $q1);

        while($row=mysqli_fetch_array($select_artist_by_user))
        {
            $this->idIzvodjaci= $row["idIzvodjaci"];
            ?>
            <a class="text-decoration-none" href="adminupdateartist.php?idIzv=<?php echo $this->idIzvodjaci; ?>"><h5 class="text-center text-warning">O Izvođaču</h5></a>
            <hr class="bg-light">
            <?php
        }//end while
        ?>

        <a class="text-decoration-none" href="indexadmin.php?data=albumi"><h5 class="text-center text-warning">Albumi</h5></a>
        <?php
        $this->spisakAlbumaArtist($idKorisnici);

        ?>
        <hr class="bg-light">

        <a class="text-decoration-none" href="indexadmin.php?data=strimovi"><h5 class="text-center text-warning">Strimovi</h5></a>
        <?php
        $this->artistSpisakAlbumaIzvodjacaStrimovi($userAdmin, $idIzv);
        ?>
        <hr class="bg-light">

        <a class="text-decoration-none" href="indexadmin.php?data=tekstovi"><h5 class="text-center text-warning">Tekstovi pjesama</h5></a>
        <hr class="bg-light">
        <?php     
    }//end leftSideArtist()

    //********************************* Pozvana metoda u u ovom fajlu u metodi leftSidePanelArtist()  *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Spisak svih albuma za izvođač nalog *********************************//
    public function spisakAlbumaArtist($userAdmin)
    {
        global $conn;

        $qNaziv= "SELECT * FROM izvodjaci WHERE userAdmin='{$userAdmin}'";
        $red= mysqli_fetch_assoc(mysqli_query($conn, $qNaziv));
        ?>
        <h6 class="sredina izvodjacAdmin"><?php echo $red["izvodjacMaster"]; ?></h6>
        
        <div class="sredina">
            <?php
            $q= "SELECT idAlbum, idIzvodjacAlbumi, idIzvodjac2, idIzvodjac3, nazivAlbuma, godinaIzdanja, idIzvodjaci, izvodjacMaster
            FROM albumi 
            JOIN izvodjaci ON izvodjaci.idIzvodjaci=albumi.idIzvodjacAlbumi
            WHERE userAdmin='{$userAdmin}'";

            $select_tekst= mysqli_query($conn, $q);
            ?>
            <ul>
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
                    ?>
                    <li class=""><a class="text-decoration-none" href="showalbum.php?idIzv=<?php echo $this->idIzvodjacAlbumi; ?>&idAlb=<?php echo $this->idAlbum; ?>"><?php echo $this->nazivAlbuma . " ($this->godinaIzdanja.)"; ?></a></li>
                    <?php 
                }//end while
                ?>
            </ul>
        </div><!-- end .sredina --> 
        <?php
    }//end spisakAlbumaArtist()

    //********************************* Pozvana metoda u ovom fajlu u metodi leftSideArtist() *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------
    
    //********************************* Metoda za prikaz albuma za Strimove za izvođač nalog *********************************//
    public function artistSpisakAlbumaIzvodjacaStrimovi($userAdmin, $idIzv)
    {
        global $conn;
        $qNaziv="SELECT idAlbum, idIzvodjacAlbumi, idIzvodjac2, idIzvodjac3, nazivAlbuma, godinaIzdanja, idIzvodjaci, izvodjacMaster
            FROM albumi 
            JOIN izvodjaci ON izvodjaci.idIzvodjaci=albumi.idIzvodjacAlbumi
            WHERE userAdmin='{$userAdmin}'";
        $red= mysqli_fetch_assoc(mysqli_query($conn, $qNaziv));
        ?>
        <h6 class="sredina izvodjacAdmin"><?php echo $red["izvodjacMaster"]; ?></h6>
        
        <div class="sredina">
            <?php
            $q= "SELECT idAlbum, idIzvodjacAlbumi, idIzvodjac2, idIzvodjac3, nazivAlbuma, godinaIzdanja, idIzvodjaci, izvodjacMaster
            FROM albumi 
            JOIN izvodjaci ON izvodjaci.idIzvodjaci=albumi.idIzvodjacAlbumi
            WHERE userAdmin='{$userAdmin}'";

            $select_tekst= mysqli_query($conn, $q);
            $izvodjacMaster= mysqli_query($conn, $q);
            ?>
        
            <ul>
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
                    ?>              
                    <li class=""><a class="text-decoration-none" href="showalbumstreams.php?idIzv=<?php echo $this->idIzvodjacAlbumi; ?>&idAlb=<?php echo $this->idAlbum; ?>"><?php echo $this->nazivAlbuma . " ($this->godinaIzdanja.)"; ?></a></li>
                    <?php                
                }//end while
                ?>
            </ul>    
        </div><!-- end .sredina -->         
        <?php
    }//end artistSpisakAlbumaIzvodjacaStrimovi()

    //********************************* Pozvana metoda u ovom fajlu leftSideArtist() *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda za prikaz izabranog albuma za izvođač nalog *********************************//
    public function artistIzabraniAlbum($idKorisnici, $idIzv, $idAlb)
    {
        global $conn;
        $q1= "SELECT * FROM albumi JOIN izvodjaci ON albumi.idIzvodjacAlbumi=izvodjaci.idIzvodjaci JOIN korisnici ON izvodjaci.userAdmin=korisnici.idKorisnici WHERE userAdmin='{$idKorisnici}' AND idAlbum='{$idAlb}'";
        
        $select_album= mysqli_query($conn, $q1);
        ?>
        <br>
        <div class="pregledAlbumaUredi">
            <div class="col-md-5">
                <div class="slikeAlbumaPregled sredina">
                    <div class="editAlbum">         
                        <?php
                        while($row= mysqli_fetch_array($select_album))
                        {
                            $this->idAlbum= $row["idAlbum"];
                            $this->idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
                            $this->idIzvodjac2= $row["idIzvodjac2"];
                            $this->idIzvodjac3= $row["idIzvodjac3"];
                            $this->nazivAlbuma= $row["nazivAlbuma"];
                            $this->slikaAlbuma= $row["slikaAlbuma"];

                            ?>
                            <p class="sredina izvodjacAdmin">Kliknite na sliku da bi ste uredili informacije o albumu</p>
                            
                            <a href="adminupdatealbum.php?idIzv=<?php echo $idIzv; ?>&idAlb=<?php echo $idAlb; ?>">
                            <img src="../images/albumi/<?php echo $this->slikaAlbuma; ?>" alt="<?php echo $this->nazivAlbuma; ?>" title="<?php echo $this->nazivAlbuma; ?>"></a>    
                            <?php 
                        }//end while
                        ?>
                    </div><!-- .editAlbum -->
                </div><!-- .slikeAlbumaPregled .sredina -->
            </div><!-- .col-md-5 -->
            
            <div class="col-md-5">
                <?php adminSpisakPjesama($idIzv, $idAlb); ?>
            </div><!-- .col-md-5 -->
        </div><!-- .pregledAlbumaUredi -->
        <?php
    }//end artistIzabraniAlbum()

    //********************************* Pozvana metoda u showalbum.php  *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------


    //********************************* Metoda za dodavanje teksta za pjesmu na unijetom albumu za izvođač nalog  *********************************//
    public function artistTekstoviPjesama($sesId, $userAdmin)
    {
        global $conn;
        include_once "../functions/removeSymbols.func.php";

        // Funkcija za generisanje CSRF tokena
        function generate_csrf_token() {
            if (!isset($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generiše 32 bajta nasumičnih podataka i pretvara ih u heksadecimalni format
            }
            return $_SESSION['csrf_token'];
        }
        
        // Upit za dohvat podataka iz baze
        $q = "SELECT * FROM pjesme 
            JOIN albumi ON albumi.idAlbum = pjesme.albumId
            JOIN izvodjaci ON izvodjaci.idIzvodjaci = albumi.idIzvodjacAlbumi 
            WHERE userAdmin=$userAdmin GROUP BY nazivPjesme";
        
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
                    var tekstPjesme = selectedOption.data('tekst'); // Dohvatiti tekstPjesme

                    if (selectedValue) {
                        $('#selectedSong').text(selectedText);
                        $('#pjesmaId').val(selectedValue);
                        $('#textInput').show();
                        $('textarea[name="tekstPjesme"]').val(tekstPjesme); // Postaviti tekst u textarea
                    } else {
                        $('#textInput').hide();
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
                    <option disabled selected value>Izaberi pjesmu</option>
                    <?php while ($row = mysqli_fetch_array($select_izvodjac)) : ?>
                    <option 
                        value="<?php echo $row['idPjesme']; ?>" 
                        data-tekst="<?php echo $row['tekstPjesme']; ?>">
                        <?php echo "{$row['nazivPjesme']} ({$row['izvodjacMaster']} - {$row['nazivAlbuma']})"; ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            
                <div id="textInput">
                    <h3 class="boja" id="selectedSong"></h3><br>
                    <input type="hidden" name="pjesmaId" id="pjesmaId">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    <textarea class="dodajTekst" name="tekstPjesme"><?php echo htmlspecialchars($this->tekstPjesme); ?></textarea>
                    <br><br>
                    <input class="btn btn-primary mb-0" type="submit" name="posalji" value="Pošalji">
                </div><!-- end #textInput -->
            </div><!-- end .form-group col-md-6 mx-auto -->
        </form>
        
        <?php
        // Obrada forme kada se podaci pošalju
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            // Provera CSRF tokena
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die("<h1 class='boja sredina'>Neovlašćen pristup.</h1>");
            }
            
            // Čišćenje i uzimanje vrednosti iz forme
            $this->idPjesme = $_POST["pjesmaId"];
            $this->tekstPjesme = cleanText($_POST["tekstPjesme"]);
        
            // Upit za ažuriranje teksta pesme u bazi
            if (!empty($this->tekstPjesme)) 
            {
                $q = "UPDATE pjesme SET tekstPjesme = '$this->tekstPjesme', dodaoTekst= '$sesId' WHERE idPjesme = '$this->idPjesme'";
                $update_pjesme = mysqli_query($conn, $q);
        
                if ($update_pjesme) {
                    echo "<h1 class='boja sredina'>Tekst pjesme je uspešno dodan.</h1>";
                } else {
                    echo "<h1 class='boja sredina'>Greška prilikom ažuriranja: " . mysqli_error($conn);
                }
            }else{
                echo "<h1 class='boja sredina'>Morate uneti tekst pesme.</h1>";
            }
        }//end if()
    }//end artistTekstoviPjesama()

    //********************************* Metoda pozvana u ovom fajlu u metodi prikazArtistEditPanela()  *********************************//
    
    //--------------------------------------------------------------------------------------------------------------------------------
    
}//end class