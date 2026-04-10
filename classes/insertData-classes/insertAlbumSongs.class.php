<?php
class insertSongs{

    //METODE SADRŽANE U OVOJ KLASI
    //spisakAlbuma (metoda za prikaz forme za unos naziva pjesama i ostalih potrebnih podataka)
    //insertLyrics (Metoda za dodavanje teksta pjesme za izabranu pjesmu)
    //csrf_token (generiše 32bitni token) PROVJERITI DA LI JE U UPOTREBI NEGDJE
    //spisakAlbumaZaStrimove (Poziva padajući meni sa svim albumima za strimove)
    //streamovi (metoda za prikaz forme za strimove)
    //dodajPjesme (metoda za dodavanje pjesama na izabranom albumu)

    //U ZADNJOJ METODI OBRATITI PAŽNJU DA JE UKLJUČEN PRINT_r() PRILIKOM IZVRŠAVANJA UNOSA. TESTIRATI JOŠ JEDNOM

    protected $idAlbum;
    protected $idIzvodjacAlbumi;
    protected $idIzvodjaci;
    protected $idIzvodjac2;
    protected $idIzvodjac3;
    protected $nazivAlbuma;
    protected $godinaIzdanja;
    protected $izvodjacMaster;
    protected $dodaoAlbum;
    protected $brojPjesama;
    protected $slikaAlbuma;
    protected $mixtapeIzvodjac;

    protected $idPjesme;
    protected $nazivPjesme;
    protected $feat;
    protected $saradnici;
    protected $trajanjePjesme;
    protected $ostaleNapomene;
    protected $tekstPjesme;

    protected $izvodjac2;
    protected $izvodjac3;



    //********************************* Metoda kojom se vrši unos naziva pjesama i ostalih potrebnih podataka za jedan album  *********************************//
    public function spisakPjesamaJednogAlbuma()
    {
        global $conn;

        $q= "SELECT * FROM albumi JOIN izvodjaci ON izvodjaci.idIzvodjaci=albumi.idIzvodjacAlbumi 
        WHERE brojPjesama IS NOT NULL 
        AND NOT EXISTS (
            SELECT 1 FROM pjesme WHERE albumi.idAlbum = pjesme.albumId
        )
        ORDER BY izvodjacMaster;";

        $select_tekst= mysqli_query($conn, $q);
        ?>
        <form class="visina slikeAlbumaPregled sredina">
            <div class="form-group col-md-6 mx-auto">
                <h6>Ukoliko nema ni jednog albuma znači da su dodate sve pjesme za trenutno unešene albume.</h6>
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
        function SelectRedirect(){
        let alb=document.getElementById('albumi').value;
        //let alb=document.getElementById('izvodjaci').value;
        console.log(alb);
        window.location=`insertsongs.php?idAlb=${alb}`;
        }
        </script>
        <?php
    }// end function spisakPjesamaJednogAlbuma()

    //********************************* Pozvana metoda fajlu insertDataPanel.class.php u metodi prikazUnosPanela() *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------
    
    //********************************* Metoda za dodavanje teksta za pjesmu na unijetom albumu  *********************************//
    public function insertLyrics($sesId)
    {
        include "insertStreams.class.php";
        $newStream= new insertStreaming();
        global $conn;
        
        // Upit za dohvat podataka iz baze
        $q = "SELECT pjesme.idPjesme,
        pjesme.nazivPjesme,
        pjesme.mixtapeIzvodjac,
        albumi.nazivAlbuma,
        izvodjaci.izvodjacMaster
        FROM pjesme
        JOIN albumi ON albumi.idAlbum = pjesme.albumId
        JOIN izvodjaci ON izvodjaci.idIzvodjaci = albumi.idIzvodjacAlbumi
        WHERE (pjesme.tekstPjesme = '' OR pjesme.tekstPjesme IS NULL) ORDER BY pjesme.nazivPjesme ASC;";
        
        $select_izvodjac = mysqli_query($conn, $q);
        ?>
        
        <!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const items = document.querySelectorAll('.pjesma-item');
                const textInput = document.getElementById('textInput');
                const songTitle = document.getElementById('selectedSong');
                const songId = document.getElementById('pjesmaId');

                items.forEach(item => {
                    item.addEventListener('click', () => {
                        const id = item.dataset.id;
                        const title = item.querySelector('strong').textContent;
                        songTitle.textContent = title;
                        songId.value = id;
                        textInput.style.display = 'block';
                        window.scrollTo({ top: textInput.offsetTop - 100, behavior: 'smooth' });
                    });
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
        <h5 class="boja sredina">Izmjena teksta je moguća samo ako imate dozvolu za to.</h5>
        
        <form class="visina slikeAlbumaPregled " method="POST" action="" enctype="multipart/form-data" name="dodajTekst" id="dodajTekst">
            <div class="form-group col-md-6 mx-auto">
                <div class="mb-3">
                    <input type="text" id="filterInput" class="form-control" placeholder="Pretraži pjesme po nazivu, izvođaču ili albumu...">
                </div>
                <div id="listaPjesama" class="lista-pjesama">
                    <?php 
                    while ($row = mysqli_fetch_array($select_izvodjac)) :
                        $idPjesme = (int)$row['idPjesme'];
                        $nazivPjesme = $row['nazivPjesme'];
                        $this->izvodjacMaster = $row['izvodjacMaster'];
                        $album = $row['nazivAlbuma'];
                        $this->mixtapeIzvodjac= $row["mixtapeIzvodjac"];

                        $nazivIzvodjac= ($this->mixtapeIzvodjac==null || $this->mixtapeIzvodjac=="") ? $this->izvodjacMaster : $this->mixtapeIzvodjac;
                    ?>
                        <div class="pjesma-item" data-id="<?php echo $idPjesme; ?>">
                            🎵 <strong><?php echo $nazivPjesme; ?></strong>
                            <span class=""> (<?php echo $nazivIzvodjac; ?> — <?php echo $album; ?>)</span>
                        </div><!-- end .pjesmaItem -->
                    <?php endwhile; ?>
                </div><!-- end .listaPjesama -->
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const items = document.querySelectorAll('.pjesma-item');
                        const textInput = document.getElementById('textInput');
                        const songTitle = document.getElementById('selectedSong');
                        const songId = document.getElementById('pjesmaId');

                        items.forEach(item => {
                            item.addEventListener('click', () => {
                                // Dohvati ID i naziv pjesme
                                const id = item.dataset.id;
                                const naziv = item.querySelector('strong').textContent;

                                // Postavi vrijednosti u formu
                                songId.value = id;
                                songTitle.textContent = naziv;

                                // Prikaži formu
                                textInput.style.display = 'block';

                                // Vizuelno označi izabranu pjesmu
                                document.querySelectorAll('.pjesma-item').forEach(el => el.classList.remove('active-song'));
                                item.classList.add('active-song');

                                // Glatko skrolaj do forme
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


        
                <div id="textInput">
                    <h3 class="boja" id="selectedSong"></h3><br>
                    <input type="hidden" name="pjesmaId" id="pjesmaId">
                    <input type="hidden" name="csrf_token" value="<?php echo $this->generate_csrf_token(); ?>">
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
            </div>
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

            $youtubeJednaPjesma= $newStream->cleanStreamsYoutubeVideo($_POST["youtubeJednaPjesma"]);
        
            // Upit za ažuriranje teksta pesme u bazi
            if (!empty($this->tekstPjesme)) {
                $q = "UPDATE pjesme SET tekstPjesme = '$this->tekstPjesme', dodaoTekst= '$sesId', youtubeJednaPjesma='$youtubeJednaPjesma' WHERE idPjesme = '$this->idPjesme'";
                $update_pjesme = mysqli_query($conn, $q);
        
                if ($update_pjesme== TRUE) {
                    $q = "SELECT nazivPjesme FROM pjesme WHERE idPjesme = $this->idPjesme LIMIT 1";
                    $res = mysqli_query($conn, $q);
                    $row = mysqli_fetch_assoc($res);

                    $this->nazivPjesme = $row['nazivPjesme'];
                    logSongTextAdded($this->idPjesme, $this->nazivPjesme);
                    echo "<h1 class='boja sredina'>Tekst pjesme je uspešno dodan.</h1>";
                    echo "<script>
                        const id = '{$this->idPjesme}';
                        const item = document.querySelector('.pjesma-item[data-id=\"' + id + '\"]');
                        if (item) item.remove();
                    </script>";
                } else {
                    echo "<h1 class='boja sredina'>Greška prilikom ažuriranja: " . mysqli_error($conn);
                }
            }else{
                echo "<h1 class='boja sredina'>Morate uneti tekst pesme.</h1>";
            }//end if else
        }//end if($_SERVER["REQUEST_METHOD"] == "POST")
    }//end insertLyrics()

    //********************************* Metoda pozvana u fajlu insertDatapanel.class.php u metodi prikazUnosPanela() *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------
    //********************************* U metodi iznad generiše token Ista metoda kao ova (samo ima prefiks generate_). Provjeriti da li je ova ispod negdje u upotrebi *********************************//
    public function generate_csrf_token() 
    {
        if (!isset($_SESSION['csrf_token'])) 
        {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generiše 32 bajta nasumičnih podataka i pretvara ih u heksadecimalni format
        }
        return $_SESSION['csrf_token'];
    }//end csrf_token()

    //********************************* Metoda pozvana u ovom fajlu u metodi insertLyrics() *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda kojom se prikazuje padajući meni sa svim albmima za unos potrebnih strimova (ukoliko nisu prazni)  *********************************//
    public function spisakAlbumaZaStrimove()
    {
        global $conn;

        $q= "SELECT a.*, i.izvodjacMaster,
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
            JOIN izvodjaci i ON i.idIzvodjaci = a.idIzvodjacAlbumi
            LEFT JOIN streamovi s ON s.albumId = a.idAlbum
            WHERE
                s.albumId IS NULL OR
                s.youtubeVideo IS NULL OR s.spotify IS NULL OR s.deezer IS NULL OR s.appleMusic IS NULL OR
                s.tidal IS NULL OR s.youtubeMusic IS NULL OR s.amazonMusic IS NULL OR s.soundCloud IS NULL OR
                s.amazonShop IS NULL OR s.bandCamp IS NULL OR s.qobuz IS NULL
            ORDER BY i.izvodjacMaster";

        $select_albume_strimovi= mysqli_query($conn, $q);
        ?>
        <form class="visina slikeAlbumaPregled sredina">
            <div class="form-group col-md-6 mx-auto">
                <select class="form-control" name="albumi" id="albumi" onchange="SelectRedirect()">
                <option id="prazni" value="" disabled selected>Izaberi album</option>
                <?php
                while($row= mysqli_fetch_array($select_albume_strimovi))
                {
                    $this->idAlbum= $row["idAlbum"];
                    $this->idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
                    $this->idIzvodjac2= $row["idIzvodjac2"];
                    $this->idIzvodjac3= $row["idIzvodjac3"];
                    $this->nazivAlbuma= $row["nazivAlbuma"];
                    $this->godinaIzdanja= $row["godinaIzdanja"];
                    $this->idIzvodjaci= $row["idIzvodjaci"];
                    $this->izvodjacMaster= $row["izvodjacMaster"];

                    $pop = (int)$row["streams_popunjeno"];
                    $ukupno = 11;
                    $badge = "[$pop/$ukupno]";

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
                        <option value="<?php echo $this->idAlbum; ?>">
                            <?php echo "$this->izvodjacMaster, $this->izvodjac2, $this->izvodjac3 - $this->nazivAlbuma ($this->godinaIzdanja.) $badge"; ?>
                        </option>
                        <?php
                    }else if(!empty($this->idIzvodjac2)){
                        ?>
                        <option value="<?php echo $this->idAlbum; ?>">
                            <?php echo "$this->izvodjacMaster & $this->izvodjac2 - $this->nazivAlbuma ($this->godinaIzdanja.) $badge"; ?>
                        </option>
                        <?php
                    }else{
                        ?>
                        <option value="<?php echo $this->idAlbum; ?>">
                            <?php echo $this->izvodjacMaster . " - " . $this->nazivAlbuma . " ($this->godinaIzdanja.) $badge"; ?>
                        </option>
                        <?php
                    }//end else
                }//end while
                ?>
                </select>
            </div><!-- .form-group -->
        </form>

        <script language="javascript">
            function SelectRedirect(){
            let alb=document.getElementById('albumi').value;
            //let alb=document.getElementById('izvodjaci').value;
            console.log(alb);
            window.location=`insertstreams.php?idAlb=${alb}`;
            }
        </script>
        <?php
    }// end function spisakAlbumaZaStrimove()

    //********************************* Pozvana metoda fajlu insertDataPanel.php u metodi prikazUnosPanela *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda za dodavanje linkova za streamove unijetom albumu  *********************************//
    public function streamovi($idAlb)
    {
        global $conn;
        
        //$q= "SELECT * FROM albumi JOIN izvodjaci ON izvodjaci.idIzvodjaci=albumi.idIzvodjacAlbumi WHERE idAlbum='{$idAlb}'";
        $q= "SELECT 
        albumi.idAlbum,
        albumi.nazivAlbuma,
        albumi.godinaIzdanja,
        albumi.slikaAlbuma,
        izvodjaci.izvodjacMaster,

        streamovi.youtubeVideo,
        streamovi.spotify,
        streamovi.deezer,
        streamovi.appleMusic,
        streamovi.tidal,
        streamovi.youtubeMusic,
        streamovi.amazonMusic,
        streamovi.soundCloud,
        streamovi.amazonShop,
        streamovi.bandCamp,
        streamovi.qobuz

        FROM albumi
        JOIN izvodjaci ON izvodjaci.idIzvodjaci = albumi.idIzvodjacAlbumi
        LEFT JOIN streamovi ON streamovi.albumId = albumi.idAlbum
        WHERE albumi.idAlbum = '" . (int)$idAlb . "'";

        $select_stream= mysqli_query($conn, $q);
        $row= mysqli_fetch_array($select_stream);

        $this->izvodjacMaster= $row["izvodjacMaster"];
        $this->nazivAlbuma= $row["nazivAlbuma"];
        $this->godinaIzdanja= $row["godinaIzdanja"];
        $slikaAlbuma= $row["slikaAlbuma"];
        $youtubeVideoLinkBaza= $row["youtubeVideo"];
        $spotifyLinkBaza= $row["spotify"];
        $deezerLinkBaza= $row["deezer"];
        $appleMusicLinkBaza= $row["appleMusic"];
        $tidalLinkBaza= $row["tidal"];
        $youtubeMusicLinkBaza= $row["youtubeMusic"];
        $amazonMusicLinkBaza= $row["amazonMusic"];
        $soundCloudLinkBaza= $row["soundCloud"];
        $amazonShopLinkBaza= $row["amazonShop"];
        $bandCampLinkBaza= $row["bandCamp"];
        $qobuzLinkBaza= $row["qobuz"];
        
        include_once "insertStreams.class.php";
        $stream= new insertStreaming();

        function nullIfEmpty($v){
            $v = trim((string)$v);
            return ($v === '') ? null : $v;
        }

        ?>
        <div class="col-md-10 panel">
        <?php

        if(isset($_POST["posalji"]))
        {
            $youtubeVideoLink = empty($youtubeVideoLinkBaza) ? $stream->cleanStreamsYoutubeVideo($_POST["youtubeVideoLink"] ?? '') : $youtubeVideoLinkBaza;
            $spotifyLink      = empty($spotifyLinkBaza)      ? $stream->cleanStreamsSpotify($_POST["spotifyLink"] ?? '')      : $spotifyLinkBaza;
            $deezerLink       = empty($deezerLinkBaza)       ? $stream->cleanStreamsDeezer($_POST["deezerLink"] ?? '')       : $deezerLinkBaza;
            $appleMusicLink   = empty($appleMusicLinkBaza)   ? $stream->cleanStreamsAppleMusic($_POST["appleMusicLink"] ?? '') : $appleMusicLinkBaza;
            $tidalLink        = empty($tidalLinkBaza)        ? $stream->cleanStreamsTidal($_POST["tidalLink"] ?? '')         : $tidalLinkBaza;
            $youtubeMusicLink = empty($youtubeMusicLinkBaza) ? $stream->cleanStreamsYoutubeMusic($_POST["youtubeMusicLink"] ?? '') : $youtubeMusicLinkBaza;
            $amazonMusicLink  = empty($amazonMusicLinkBaza)  ? $stream->cleanStreamsAmazonMusic($_POST["amazonMusicLink"] ?? '') : $amazonMusicLinkBaza;
            $soundCloudLink   = empty($soundCloudLinkBaza)   ? $stream->cleanStreamsSoundCloud($_POST["soundCloudLink"] ?? '') : $soundCloudLinkBaza;
            $amazonShopLink   = empty($amazonShopLinkBaza)   ? $stream->cleanStreamsAmazonShop($_POST["amazonShopLink"] ?? '') : $amazonShopLinkBaza;
            $bandCampLink   = empty($bandCampLinkBaza)   ? $stream->cleanStreamsBandCamp($_POST["bandCampLink"] ?? '') : $bandCampLinkBaza;
            $qobuzLink   = empty($qobuzLinkBaza)   ? $stream->cleanStreamsqobuz($_POST["qobuzLink"] ?? '') : $qobuzLinkBaza;

            // ✅ prazno -> NULL (ali samo za ono što dolazi iz forme)
            $youtubeVideoLink = nullIfEmpty($youtubeVideoLink);
            $spotifyLink      = nullIfEmpty($spotifyLink);
            $deezerLink       = nullIfEmpty($deezerLink);
            $appleMusicLink   = nullIfEmpty($appleMusicLink);
            $tidalLink        = nullIfEmpty($tidalLink);
            $youtubeMusicLink = nullIfEmpty($youtubeMusicLink);
            $amazonMusicLink  = nullIfEmpty($amazonMusicLink);
            $soundCloudLink   = nullIfEmpty($soundCloudLink);
            $amazonShopLink   = nullIfEmpty($amazonShopLink);
            $bandCampLink   = nullIfEmpty($bandCampLink);
            $qobuzLink   = nullIfEmpty($qobuzLink);

            if(!empty($youtubeVideoLink || $spotifyLink || $deezerLink || $appleMusicLink || $tidalLink || $youtubeMusicLink ||$amazonMusicLink OR $soundCloudLink OR $amazonShopLink OR $bandCampLink OR $qobuzLink))
            {
                // ✅ bez pripremljenih upita ne možemo elegantno NULL; radimo ručno:
                $yt   = ($youtubeVideoLink === null) ? "NULL" : "'" . mysqli_real_escape_string($conn, $youtubeVideoLink) . "'";
                $sp   = ($spotifyLink      === null) ? "NULL" : "'" . mysqli_real_escape_string($conn, $spotifyLink)      . "'";
                $dz   = ($deezerLink       === null) ? "NULL" : "'" . mysqli_real_escape_string($conn, $deezerLink)       . "'";
                $am   = ($appleMusicLink   === null) ? "NULL" : "'" . mysqli_real_escape_string($conn, $appleMusicLink)   . "'";
                $td   = ($tidalLink        === null) ? "NULL" : "'" . mysqli_real_escape_string($conn, $tidalLink)        . "'";
                $ym   = ($youtubeMusicLink === null) ? "NULL" : "'" . mysqli_real_escape_string($conn, $youtubeMusicLink) . "'";
                $azm  = ($amazonMusicLink  === null) ? "NULL" : "'" . mysqli_real_escape_string($conn, $amazonMusicLink)  . "'";
                $sc   = ($soundCloudLink   === null) ? "NULL" : "'" . mysqli_real_escape_string($conn, $soundCloudLink)   . "'";
                $azs  = ($amazonShopLink   === null) ? "NULL" : "'" . mysqli_real_escape_string($conn, $amazonShopLink)   . "'";
                $bc   = ($bandCampLink   === null) ? "NULL" : "'" . mysqli_real_escape_string($conn, $bandCampLink)   . "'";
                $qb  = ($qobuzLink   === null) ? "NULL" : "'" . mysqli_real_escape_string($conn, $qobuzLink)   . "'";

                $q_stream = "INSERT INTO streamovi
                (albumId, youtubeVideo, spotify, deezer, appleMusic, tidal, youtubeMusic, amazonMusic, soundCloud, amazonShop, bandCamp, qobuz)
                VALUES
                (" . (int)$idAlb . ", $yt, $sp, $dz, $am, $td, $ym, $azm, $sc, $azs, $bc, $qb)
                     ON DUPLICATE KEY UPDATE
                youtubeVideo = VALUES(youtubeVideo),
                spotify      = VALUES(spotify),
                deezer       = VALUES(deezer),
                appleMusic   = VALUES(appleMusic),
                tidal        = VALUES(tidal),
                youtubeMusic = VALUES(youtubeMusic),
                amazonMusic  = VALUES(amazonMusic),
                soundCloud   = VALUES(soundCloud),
                amazonShop   = VALUES(amazonShop),
                bandCamp     = VALUES(bandCamp),
                qobuz        = VALUES(qobuz)";

                $insert_stream= mysqli_query($conn, $q_stream);
                if($insert_stream == TRUE)
                {
                    logStreamAdded($idAlb, $youtubeVideoLink, $spotifyLink);
                    $streams = [
                        'youtubeVideo' => $youtubeVideoLink,
                        'spotify' => $spotifyLink,
                        'deezer' => $deezerLink,
                        'appleMusic' => $appleMusicLink,
                        'tidal' => $tidalLink,
                        'youtubeMusic' => $youtubeMusicLink,
                        'amazonMusic' => $amazonMusicLink,
                        'soundCloud' => $soundCloudLink,
                        'amazonShop' => $amazonShopLink,
                        'bandCamp' => $bandCampLink,
                        'qobuz' => $qobuzLink,

                    ];

                    // Pozivaš funkciju koja će logovati sve strimove za jedan album
                    //logMultipleStreamsAdded($idAlb, $streams);
                    echo "<meta http-equiv='refresh' content='1'; url='dodajalbume.php'>";
                }else{
                    echo "Greška " . mysqli_error($conn). "<br>";
                }//end else
            }//end if(!empty())
        }//end if(isset($_POST["posalji"]))
        ?>
        
            <div class="pregledAlbumaUredi">
                <div class="col-md-2">
                    <div class="slikeAlbumaPregled sredina">
                        <div class="editAlbum">
                            <h6 class="boja"><strong><?php echo $this->nazivAlbuma; ?></strong></h6><br>
                            <img src="images/albumi/<?php echo $slikaAlbuma; ?>" alt="<?php echo $this->nazivAlbuma; ?>" title="<?php echo $this->nazivAlbuma; ?>" class=""/>
                        </div><!-- end .editAlbum -->
                    </div><!-- end .slikeAlbumPregled -->
                </div><!-- end .col-md-2 -->

                <div class="col-md-7 sredina">
                    <form method="POST" action="" enctype="multipart/form-data" name="izmjenaPjesme" id="izmjenaPjesme">
                        <h3 class="sredina mt-3">Linkovi koji nedostaju</h3>
                        <?php 
                        if($youtubeVideoLinkBaza!=TRUE)
                        {
                            ?>
                            <label for="nazivStreamingServisa" class="text-warning"><strong>Youtube Plejlsita</strong></label><br>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">https://www.youtube.com/</span>
                                </div><!-- end .input-group-prepend -->
                                <input type="text" class="form-control" name="youtubeVideoLink" class="form-control form-control-sm text-danger" value="">
                            </div><!-- end .input-group -->
                            <br><br>
                            <?php
                        }//end if()
                        
                        if($spotifyLinkBaza!=TRUE)
                        {
                            ?>
                            <label for="nazivStreamingServisa" class="text-warning"><strong>Spotify</strong></label><br>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">https://open.spotify.com/</span>
                                </div><!-- end .input-group-prepend -->
                                <input type="text" class="form-control" name="spotifyLink" class="form-control form-control-sm text-danger" value="">
                            </div><!-- end .input-group -->
                            <br><br>
                            <?php
                        }//end if()

                        if(empty($deezerLinkBaza))
                        {
                            ?>
                            <label for="nazivStreamingServisa" class="text-warning"><strong>Deezer</strong></label><br>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">https://www.deezer.com/</span>
                                </div><!-- end .input-group-prepend -->
                                <input type="text" class="form-control" name="deezerLink" class="form-control form-control-sm text-danger" value="">
                            </div><!-- end .input-group -->
                            <br><br>
                            <?php
                        }//end if()

                        if($appleMusicLinkBaza!=TRUE)
                        {
                            ?>
                            <label for="nazivStreamingServisa" class="text-warning"><strong>Apple Music</strong></label><br>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">https://music.apple.com/</span>
                                </div><!-- end .input-group-prepend -->
                                <input type="text" class="form-control" name="appleMusicLink" class="form-control form-control-sm text-danger" value="">
                            </div><!-- end .input-group -->
                            <br><br>
                            <?php
                        }

                        if($tidalLinkBaza!=TRUE)
                        {
                            ?>
                            <label for="nazivStreamingServisa" class="text-warning"><strong>Tidal</strong></label><br>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">https://tidal.com/</span>
                                </div><!-- end .input-group-prepend -->
                                <input type="text" class="form-control" name="tidalLink" class="form-control form-control-sm text-danger" value="">
                            </div><!-- end .input-group -->
                            <br><br>
                            <?php
                        }

                        if($youtubeMusicLinkBaza!=TRUE)
                        {
                            ?>
                            <label for="nazivStreamingServisa" class="text-warning"><strong>Youtube Music</strong></label><br>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">https://music.youtube.com/</span>
                                </div><!-- end .input-group-prepend -->
                                <input type="text" class="form-control" name="youtubeMusicLink" class="form-control form-control-sm text-danger" value="">
                            </div><!-- end .input-group -->
                            <br><br>
                            <?php
                        }//end if()

                        if($amazonMusicLinkBaza!=TRUE)
                        {
                            ?>
                            <label for="nazivStreamingServisa" class="text-warning"><strong>Amazon Music</strong></label><br>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">https://music.amazon.com/</span>
                                </div><!-- end .input-group-prepend -->
                                <input type="text" class="form-control" name="amazonMusicLink" class="form-control form-control-sm text-danger" value="">
                            </div><!-- end .input-group -->
                            <br><br>
                            <?php
                        }//end if()
                        

                        echo "<p class='text-light'>Mjesta gdje se može kupiti mp3 fajl ili CD (ili drugi format)</p>";
                        
                        
                        if($soundCloudLinkBaza!=TRUE)
                        {
                            ?>
                            <label for="nazivStreamingServisa" class="text-warning"><strong>SoundCloud Shop</strong></label><br>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">https://soundcloud.com/</span>
                                </div><!-- end .input-group-prepend -->
                                <input type="text" class="form-control" name="soundCloudLink" class="form-control form-control-sm text-danger" value="">
                            </div><!-- end .input-group --><br><br>
                            <?php
                        }//end if()

                        if($amazonShopLinkBaza!=TRUE)
                        {
                            ?>
                            <label for="nazivStreamingServisa" class="text-warning"><strong>Amazon Shop</strong></label><br>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">https://www.amazon.com/</span>
                                </div><!-- end .input-group-prepend -->
                                <input type="text" class="form-control" name="amazonShopLink" class="form-control form-control-sm text-danger" value="">
                            </div><!-- end .input-group --><br><br>
                            <?php
                        }//end if()

                        if($bandCampLinkBaza!=TRUE)
                        {
                            ?>
                            <label for="nazivStreamingServisa" class="text-warning"><strong>BandCamp Shop</strong></label><br>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">https://bandcamp.com/</span>
                                </div><!-- end .input-group-prepend -->
                                <input type="text" class="form-control" name="bandCampLink" class="form-control form-control-sm text-danger" value="">
                            </div><!-- end .input-group --><br><br>
                            <?php
                        }//end if()

                        if($qobuzLinkBaza!=TRUE)
                        {
                            ?>
                            <label for="nazivStreamingServisa" class="text-warning"><strong>Qobuz Shop</strong></label><br>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">https://www.qobuz.com/</span>
                                </div><!-- end .input-group-prepend -->
                                <input type="text" class="form-control" name="qobuzLink" class="form-control form-control-sm text-danger" value="">
                            </div><!-- end .input-group --><br><br>
                            <?php
                        }//end if()
                        ?>

                            <br><br>
                            <hr class="hrLinija">
                            <input class="btn btn-primary mb-0" type="submit" name="posalji" value="Pošalji">
                            <br><br>
                            <input class="btn btn-danger pt-1 mt-0" type="reset" value="Reset">
                    </form>
                </div><!-- end .col-md-7 .sredina --><br>
            </div><!-- end pregledAlbumaUredi -->
        </div><!-- end col-md-10 --> 
        <?php
    
    }//end streamovi()

    //********************************* Pozvana metoda u fajlu insertStreams.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda za dodavanje pjesama na nekom unijetom albumu  *********************************//
    public function dodajPjesme($albumId)
    {
        global $conn;
        $q= "SELECT * FROM albumi JOIN pjesme WHERE idAlbum='{$albumId}'";
        $select_album= mysqli_query($conn, $q);
        $row= mysqli_fetch_array($select_album);

        $this->nazivAlbuma= $row["nazivAlbuma"];
        $this->dodaoAlbum= $row["dodaoAlbum"];
        $this->idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
        $this->brojPjesama= $row["brojPjesama"];
        $this->slikaAlbuma= $row["slikaAlbuma"];

        ?>
        <div class="col-md-10 panel">
            <div class="pregledAlbumaUredi">
                <div class="col-md-2">
                    <div class="slikeAlbumaPregled sredina">
                        <div class="editAlbum">
                            <h6 class="boja"><strong><?php echo $this->nazivAlbuma; ?></strong></h6><br>
                            <img src="images/albumi/<?php echo $this->slikaAlbuma; ?>" alt="<?php echo $this->nazivAlbuma; ?>" title="<?php echo $this->nazivAlbuma; ?>" class=""/>
                        </div><!-- end .editAlbum -->
                    </div><!-- end .slikeAlbumPregled -->
                </div><!-- end .col-md-5 -->

                <div class="col-md-7 sredina">
                    <form method="POST" action="" enctype="multipart/form-data" name="izmjenaPjesme" id="izmjenaPjesme">
                        <?php
                        $pjesmeLog = [];
                        for($i=0; $i<$this->brojPjesama; $i++)
                        {
                            $songNumber= $i+1;
                            ?>
                            <h3 class="boja">Redni broj pjesme <?php echo $songNumber; ?></h3>

                            <br>
                            <label for="redniBroj" class="text-warning"><strong>Ukoliko je više CD-ova napišite CD1- 1, CD2- 1 <br><span class="bg-danger text-light">&nbsp; U suprotnom ne dirajte &nbsp;</span></strong></label><br>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Redni broj</span>
                                </div><!-- end .input-group-prepend -->
                                <input type="hidden" name="pjesmaId_<?php echo $songNumber; ?>; ?>" id="pjesmaId" value="<?php echo $songNumber; ?>">
                                <input type="text" class="form-control" name="redniBroj_<?php echo $songNumber; ?>" class="form-control form-control-sm" value="<?php echo $songNumber; ?>">
                            </div><!-- end .input-group --><br>

                            <fieldset class="border p-5 rounded">
                            <label for="nazivPjesme" class="text-warning"><strong>Naziv pjesme <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong></label><br>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Naziv pjesme</span>
                                </div><!-- end .input-group-prepend -->
                                <input type="hidden" name="pjesmaId_<?php echo $songNumber; ?>; ?>" id="pjesmaId" value="<?php echo $songNumber; ?>">
                                <input type="text" class="form-control" name="nazivPjesme_<?php echo $songNumber; ?>" class="form-control form-control-sm" value="">
                            </div><!-- end .input-group -->
                            </fieldset><br>

                            <label for="mixtapeIzvodjac" class="text-warning">Ukoliko je mixtape, navedite glavnog (prvog) izvođača ove pjesme</label><br>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Mixtape Izvođač</span>
                                </div><!-- end .input-group-prepend -->
                                <input type="hidden" name="pjesmaId" id="pjesmaId" value="">
                                <input type="text" class="form-control" name="mixtapeIzvodjac_<?php echo $songNumber; ?>" class="form-control form-control-sm" value="">
                            </div><!-- end .input-group --><br>
                            
                            <label for="feat" class="text-warning">Feat (gost na pjesmi)</label><br>
                            <label for="feat"class="text-light">(bez navodnika, zagrada ili bilo kojih drugih specijalnih karkatera, <br>kao na primjeru ispod)</label><br>
                            <input type="hidden" name="pjesmaId" id="pjesmaId" value="">
                            <input type="text" name="feat_<?php echo $songNumber; ?>" class="form-control form-control-sm" value="" placeholder="feat. Ajs Nigrutin, Skywikler, Timbe"><br><br>

                            <label for="saradnici" class="text-warning">Saradnici, producenti, muzičari,...</label><br>
                            <textarea class="saradniciUpdate" name="saradnici_<?php echo $songNumber; ?>"></textarea><br>

                            <label for="trajanje" class="text-warning">Trajanje pjesme</label><br>
                            <!--<input type="time" step="any" name="trajanje" value=""><br><br>-->
                            <input type="text" step="any" name="trajanje_<?php echo $songNumber; ?>" value="" placeholder="00:00:00"><br><br>

                            <label for="ostaleNapomene" class="text-warning">Ostale napomene</label><br>
                            <textarea class="saradniciUpdate" name="ostaleNapomene_<?php echo $songNumber; ?>"></textarea><br><br>

                            <label for="tekstPjesme" class="text-warning">Tekst pjesme</label><br>
                            <textarea class="dodajTekst" name="tekstPjesme_<?php echo $songNumber; ?>"></textarea><br><br>
                            
                            <hr class="hrLinija">                   
                            <?php 
                            if(isset($_POST["posalji"]))
                            {
                                $idPjesme= $_POST["pjesmaId"];
                                $redniBroj= cleanText($_POST["redniBroj_$songNumber"]);
                                $mixtapeIzvodjac= cleanText($_POST["mixtapeIzvodjac_$songNumber"]);
                                $nazivPjesme= trim(removeSimbols($_POST["nazivPjesme_$songNumber"]));
                                $feat= trim(removeSimbols($_POST["feat_$songNumber"]));
                                //$saradnici= trim(removeSimbols($_POST["saradnici"]));
                                $saradnici= cleanText($_POST["saradnici_$songNumber"]);
                                $trajanjePjesme= trim($_POST["trajanje_$songNumber"]);
                                $ostaleNapomene= trim(removeSimbols($_POST["ostaleNapomene_$songNumber"]));
                                //$tekstPjesme= trim(removeSimbols($_POST["tekstPjesme"]));
                                $tekstPjesme= cleanText($_POST["tekstPjesme_$songNumber"]);
                                //$mixtapeFeat= trim(removeSimbols($_POST["mixtapeFeat"]));

                                $mixtapeIzvodjac2= ($mixtapeIzvodjac=="") ? null : $mixtapeIzvodjac;
                                $feat2= ($feat=="") ? null : $feat;
                                $saradnici2= ($saradnici=="") ? null : $saradnici;
                                $trajanjePjesme2= ($trajanjePjesme=="") ? null : $trajanjePjesme;
                                $ostaleNapomene2= ($ostaleNapomene=="") ? null : $ostaleNapomene;
                                $tekstPjesme2= ($tekstPjesme=="") ? null : $tekstPjesme;
                                //$mixtapeFeat2= ($mixtapeFeat=="") ? null : $mixtapeFeat;
                                
                                $q= "INSERT INTO pjesme (redniBroj, mixtapeIzvodjac, nazivPjesme, feat, saradnici, trajanjePjesme, ostaleNapomene, tekstPjesme, albumId, izvodjacId) VALUES('$redniBroj', '$mixtapeIzvodjac2', '$nazivPjesme', '$feat2', '$saradnici2', '$trajanjePjesme2', '$ostaleNapomene2', '$tekstPjesme2', '$albumId', '$this->idIzvodjacAlbumi')";
                                $insert_pjesme= mysqli_query($conn, $q);
                                //print_r($q);

                                if($insert_pjesme== TRUE)
                                {
                                    $newIdPjesme = mysqli_insert_id($conn);

                                    // skupljamo info za jedan zajednički log
                                    $pjesmeLog[] = [
                                        'idPjesme'   => $newIdPjesme,
                                        'redniBroj'  => $songNumber,
                                        'nazivPjesme' => ($nazivPjesme === "" ? null : $nazivPjesme)
                                        // ako hoćeš, možemo dodati i 'nazivPjesme' => $nazivPjesme
                                    ];

                                    // logujemo samo jednom, kada se obradi zadnja pjesma
                                    if($songNumber == $this->brojPjesama)
                                    {
                                        logMultiplePjesmeAdded($albumId, $this->idIzvodjacAlbumi, $this->nazivAlbuma, $pjesmeLog);
                                    }

                                    ?> 
                                    <meta http-equiv="refresh" content="0; url=dodajalbume.php">
                                    <?php
                                }else{
                                    echo "Greška " . mysqli_error($conn). "<br>";
                                }//end if else($insert_pjesme== TRUE)

                            }//end if(isset($_POST["posalji"]))
                        }//end for petlje koja prikazuje broj pjesama za unos u novi album
                        ?>
                        <input class="btn btn-primary mb-0" type="submit" name="posalji" value="Pošalji">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input class="btn btn-danger pt-1 mt-0" type="reset" value="Reset">
                    </form>
                </div><!-- end .col-md-7 -->
            </div><!-- end pregledAlbumaUredi -->
        </div><!-- end col-md-10 --> 
            <?php
    }//end dodajPjesme()
    //********************************* Pozvana metoda u fajlu insertSongs.php  *********************************//

} //end class insertSongs
