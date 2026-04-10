<?php

//METODE SADRŽANE U OVOJ KLASI
//insertSingl (Forma za unos novog singla)
//unosNaslovneSlikeSingla (metoda sa kojom dodajemo naslovnu sliku singla)

class insertSingleSong{

    protected $izvodjac1;
    protected $izvodjac2;
    protected $izvodjac3;
    protected $naslovnaSlikaSingla;
    protected $nazivSingla;
    protected $singleIzvodjaci;
    protected $godinaIzdanjaSingl;
    protected $tacanDatumIzdanja;
    protected $ostaleNapomeneSingl;
    protected $lastInsertSinglId;
    protected $idIzdavaci;
    protected $izdavac;
    protected $izdavac1;
    protected $izdavac2;
    protected $singleFeat;
    protected $tekstSingla;
    protected $drzavaSingl;
    protected $entitetSingl;

    protected $idDrzave;
    protected $drzavaNaziv;
    protected $kodZemljeDugi; 
    protected $zastava;
    protected $idEntiteti;
    protected $entitetNaziv;
    protected $entDrzava;
    protected $zastavaEnt;
    protected $kodEntiteta;


    //********************************* Metoda u koju je smještena forma za unos novog albuma  *********************************//
    public function insertSingl($slid)
    {
        global $conn;
        include "insertStreams.class.php";
        $newStream= new insertStreaming();

        include_once "insertAlbumSongs.class.php";
        $newLyrics= new insertSongs();
        
        ?>
        <br>
        <div id="" class="razmakIzmedju">
            <div class="col-md-3">
                <div class="slikeAlbumaPregled sredina">
                    <div class="editAlbum">
                        <p class="text-warning"><strong>Slika naslovnica singla</strong></p>
                        <img src="../images/albumi/" alt="" title=" (front)" class=""/>
                        <form method="POST" action="" enctype="multipart/form-data" name="singlForm" id="singlForm">
                            <input type="file" class="btn btn-light" name="dodajNaslovnuSlikuSingla"><br><br>
                            <br><hr class="hrLinija">
                            <br><br>
                    </div><!-- end .editAlbum -->
                </div><!-- end .slikeAlbumaPregled .sredina -->
            </div><!-- /.col-md-3 -->

            <script>
            document.getElementById('buttonid').addEventListener('click', openDialog);

            function openDialog() {
            document.getElementById('promjenaSlikeAlbuma').click();
            }
            </script>
          
            <div class="col-md-7 sredina inline-block"> 
                <div class=""> 
                    <h3 class='text-danger sredina'><strong><span class="bg-danger text-white">&nbsp;Uputstvo za dodavanje singla: &nbsp;</strong></h3>
                    <h5 class='text-warning sredina'>1. Unesite naziv pjesme</h5>
                    <h5 class='text-warning sredina'>2. Unesite imena svih učesnika singla odvojene zarezom</h5>
                    <h5 class='text-warning sredina'>3. Popunite obavezna polja.</h5>
                    <h5 class='text-warning sredina'>4. DEMO IZVOĐAČI koji nisu ostvarili značajniji uspeh sa singlom, biće obrisani!</h5>
                </div>



                <fieldset class="border p-5 rounded">
                    <label for="nazivSingla" class="text-warning"><strong>Naziv singla <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong></label><br>
                    <input type="text" name="nazivSingla" id="nazivSingla" class="form-control form-control-sm" value="" placeholder="npr. Novosadska Setka"><br><br>

                    <label for="singleIzvodjaci" class="text-warning"><strong>Naziv solo izvođača</span> <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong></label><br>
                    <label for="singleIzvodjaci" class="text-light">Solo izvođač (ili više njih) ili grupa autor singla</span></strong></label><br>
                    <input type="text" name="singleIzvodjaci" class="form-control form-control-sm text-danger" value="" placeholder="npr. Mija, Djare, Bulch, Jovica Dobrica, Ralmo, Tatula, Riga Dri, Flow, Dolar, Fox"><br><br>

                    <label for="feat" class="text-warning"><strong>feat. (ukoliko ima)</strong></label><br>
                    <label for="singleIzvodjaci" class="text-warning bg-dark">Ukoliko je feat napišite npr. feat. (ili featuring) Bvana</label><br>
                    <label for="feat"class="text-light"><span class="bg-danger text-light">&nbsp; (bez navodnika, zagrada ili bilo kojih drugih specijalnih karkatera osim zareza)</span></strong></label><br>
                    <input type="text" name="feat" class="form-control form-control-sm" value="" placeholder="feat. Bvana"><br><br>
                </fieldset><br><br>

                <label for="godinaIzdanjaSingl" class="text-warning"><strong>Godina izdanja <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong></label><br>
                <label for="godinaIzdanjaSingl" class="text-light">Upisati samo godinu bez tačke na kraju</label><br>
                <b><input type="number" name="godinaIzdanjaSingl" id="godinaIzdanjaSingl" class="form-control form-control-sm text-dark" placeholder="1995"></b><br><br>

                <label for="tacanDatumIzdanja" class="text-warning"><strong>Tačan datum izdanja (ukoliko je poznat)</strong></label><br>
                <label for="tacanDatumIzdanja" class="text-light">Upisati u formatu 01.02.2023. (dd.mm.gggg.)</label><br>
                <b><input type="text" name="tacanDatumIzdanja" class="form-control form-control-sm text-dark" placeholder="01.02.2023."></b><br><br>

                <label for="drzava" class="text-warning"><strong>Država (za čije glavno tržište je album) <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong></label><br>
                <select class="form-control" name="drzava" id="drzava">
                    <option class="form-control" disabled selected value="">Izaberite državu</option>
                    <?php 
                    $q= "SELECT * FROM drzave";
                    $select_drzavu= mysqli_query($conn, $q);

                    while($row= mysqli_fetch_assoc($select_drzavu))
                    {
                        $this->idDrzave= $row["idDrzave"];
                        $this->drzavaNaziv= $row["nazivDrzave"];
                        $this->kodZemljeDugi= $row["kodZemljeDugi"];
                        $this->zastava= $row["zastavaDrzave"];

                        echo "<option value='{$this->idDrzave}'>$this->drzavaNaziv </option>";
                    }                     
                    ?>
                </select>
                <br><br>

                <label for="entitet" class="hide text-warning"><strong>Entitet <span class="bg-danger text-light">&nbsp; (ako je iz BiH obavezno polje) &nbsp;</span></strong></label><br>
                <select class="form-control hide" name="entitet" id="entitet">
                    <option class="form-control" disabled selected value="">Izaberite entitet</option>
                    <?php 
                    $q= "SELECT * FROM entiteti";
                    $select_drzavu= mysqli_query($conn, $q);

                    while($row= mysqli_fetch_assoc($select_drzavu))
                    {
                        $this->idEntiteti= $row["idEntiteti"];
                        $this->entitetNaziv= $row["entitetNaziv"];
                        $this->entDrzava= $row["entDrzava"];
                        $this->zastavaEnt= $row["zastavaEnt"];
                        $this->kodEntiteta= $row["kodEntiteta"];

                        echo "<option value='{$this->kodEntiteta}'>$this->entitetNaziv </option>";
                    }                     
                    ?>
                </select><br><br>

                
                <script>
                    // Добијте референцу на оба <select> тага
                    const drzavaSelect = document.getElementById('drzava');
                    const entitetSelect = document.getElementById('entitet');

                    // Додајте слушач на промену вредности првог <select> тага
                    drzavaSelect.addEventListener('change', function() {
                    // Ако је изабрана вредност "2" (BiH), прикажите други <select> таг
                    if (drzavaSelect.value === '2') {
                        entitetSelect.style.display = 'block'; // Прикажи други <select> таг
                    } else {
                        entitetSelect.style.display = 'none'; // Сакриј други <select> таг
                    }
                    });
                </script>

                 <fieldset class="border p-5 rounded">
                     <?php
                        $q = "SELECT * FROM izdavaci ORDER BY izdavaciNaziv";
                        $select_izdavaci = mysqli_query($conn, $q);

                        $izdavaci = [];
                        while ($red = mysqli_fetch_assoc($select_izdavaci)) {
                            $izdavaci[] = $red; // čuvamo rezultate u niz
                        }

                        function ispisiOpcijeIzdavaciZaSingl($izdavaci) {
                            foreach ($izdavaci as $red) {
                                echo '<option value="'.$red["idIzdavaci"].'">'.$red["izdavaciNaziv"].'</option>';
                            }
                        }//end function ispisiOpcije
                        ?>

                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const selects = document.querySelectorAll(".izdavac-select");

                                function validateUniqueSelection() {
                                    const selectedValues = [];
                                    let hasDuplicates = false;

                                    selects.forEach(select => {
                                        select.classList.remove("border-danger");
                                    });

                                    selects.forEach(select => {
                                        const value = select.value;
                                        if (value && selectedValues.includes(value)) {
                                            select.classList.add("border-danger");
                                            hasDuplicates = true;
                                        }
                                        selectedValues.push(value);
                                    });

                                    if (hasDuplicates) {
                                        alert("Ne možete izabrati istog izdavača više puta!");
                                    }
                                }

                                selects.forEach(select => {
                                    select.addEventListener("change", validateUniqueSelection);
                                });
                            });
                        </script>

                        <style>
                            .border-danger {
                                border: 2px solid red !important;
                            }
                        </style>

                    <label for="izdavac1" class="text-warning">
                        <strong>Izdavač 1 <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong>
                    </label><br>
                    <label for="izdavac" class="text-light">Ako je singl samostalno postavljen na net ili nije zvanično objavljen izaberite <b>Samoizdanje</b></label><br>
                    <select class="form-control izdavac-select" name="izdavac1" id="izdavac1" required>
                        <option value="" disabled selected>Izaberi izdavača</option>
                        <?php ispisiOpcijeIzdavaciZaSingl($izdavaci); ?>
                    </select><br><br>

                    <label for="izdavac2" class="text-warning"><strong>Izdavač 2</strong></label><br>
                    <select class="form-control izdavac-select" name="izdavac2" id="izdavac2">
                        <option value="" disabled selected>Izaberi izdavača</option>
                        <?php ispisiOpcijeIzdavaciZaSingl($izdavaci); ?>
                    </select><br><br>
                </fieldset><br><br>

                <label for="ostaleNapomeneSingl" class="text-warning"><strong>Ostale napomene vezane za singl</strong></label><br>
                <label for="ostaleNapomeneSingl" class="text-light">Nije obavezno polje, primjeri podataka koji se tiču za čitavu pjesmu</label><br>
                <textarea class="dodajTekstNapomene" name="ostaleNapomeneSingl" 
                placeholder="Snimano u studiju: ***
                Phonographic Copyright ℗ – **** 
                Copyright © – *** 
                Printed By – ***
                Design – ***
                Music By – ***"><?php ; ?></textarea><br><br>

                <label for="tekstSingla" class="text-warning"><strong>Tekst pjesme</strong></label><br>
                <div id="textInput">
                <input type="hidden" name="csrf_token" value="<?php echo $newLyrics->generate_csrf_token(); ?>">
                    <textarea class="dodajTekst" name="tekstSingla"></textarea><br><br>
                </div><!-- end #textInput -->
                
                <fieldset class="border p-5 rounded">
                    <legend class="w-auto px-2"><span class="podebljano bg-dark text-warning sredina">&nbsp;Strimovi&nbsp;</span></legend>
                    <br><br>
                <p class="text-light">Polja ispod nisu obavezna, ali ukoliko znate dodajte odmah i strimove</p>

                <label for="youtubeVideoLink" class="text-warning"><strong>Youtube Plejlsita</strong></label><br>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">https://www.youtube.com/</span>
                    </div><!-- end .input-group-prepend -->
                    <input type="text" class="form-control" name="youtubeVideoLink" class="form-control form-control-sm text-danger" value="">
                </div><!-- end .input-group --><br><br>

                <label for="spotifyLink" class="text-warning"><strong>Spotify</strong></label><br>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">https://open.spotify.com/</span>
                    </div><!-- end .input-group-prepend -->
                    <input type="text" class="form-control" name="spotifyLink" class="form-control form-control-sm text-danger" value="">
                </div><!-- end .input-group --><br><br>

                <label for="deezerLink" class="text-warning"><strong>Deezer</strong></label><br>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">https://www.deezer.com/</span>
                    </div><!-- end .input-group-prepend -->
                    <input type="text" class="form-control" name="deezerLink" class="form-control form-control-sm text-danger" value="">
                </div><!-- end .input-group --><br><br>
                
                <label for="appleMusicLink" class="text-warning"><strong>Apple Music</strong></label><br>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">https://music.apple.com/</span>
                    </div><!-- end .input-group-prepend -->
                    <input type="text" class="form-control" name="appleMusicLink" class="form-control form-control-sm text-danger" value="">
                </div><!-- end .input-group --><br><br>
                
                <label for="tidalLink" class="text-warning"><strong>Tidal</strong></label><br>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">https://tidal.com/</span>
                    </div><!-- end .input-group-prepend -->
                    <input type="text" class="form-control" name="tidalLink" class="form-control form-control-sm text-danger" value="">
                </div><!-- end .input-group --><br><br>
                
                <label for="youtubeMusicLink" class="text-warning"><strong>Youtube Music</strong></label><br>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">https://music.youtube.com/</span>
                    </div><!-- end .input-group-prepend -->
                    <input type="text" class="form-control" name="youtubeMusicLink" class="form-control form-control-sm text-danger" value="">
                </div><!-- end .input-group --><br><br>

                <label for="amazonMusicLink" class="text-warning"><strong>Amazon Music</strong></label><br>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">https://music.amazon.com/</span>
                    </div><!-- end .input-group-prepend -->
                    <input type="text" class="form-control" name="amazonMusicLink" class="form-control form-control-sm text-danger" value="">
                </div><!-- end .input-group --><br><br>

                <p class="text-light">Mjesta gdje se može kupiti mp3 fajl ili CD (ili drugi format)</p>
                
                <label for="soundCloudLink" class="text-warning"><strong>SoundCloud Shop</strong></label><br>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">https://soundcloud.com/</span>
                    </div><!-- end .input-group-prepend -->
                    <input type="text" class="form-control" name="soundCloudLink" class="form-control form-control-sm text-danger" value="">
                </div><!-- end .input-group --><br><br>

                <label for="amazonShopLink" class="text-warning"><strong>Amazon Shop</strong></label><br>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">https://www.amazon.com/</span>
                    </div><!-- end .input-group-prepend -->
                    <input type="text" class="form-control" name="amazonShopLink" class="form-control form-control-sm text-danger" value="">
                </div><!-- end .input-group --><br><br>

                <label for="nazivStreamingServisa" class="text-warning"><strong>BandCamp Shop</strong></label><br>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">https://bandcamp.com/</span>
                    </div><!-- end .input-group-prepend -->
                    <input type="text" class="form-control" name="bandCampLink" class="form-control form-control-sm text-danger" value="">
                </div><!-- end .input-group --><br><br>

                <label for="nazivStreamingServisa" class="text-warning"><strong>Qobuz Shop</strong></label><br>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">https://www.qobuz.com/</span>
                    </div><!-- end .input-group-prepend -->
                    <input type="text" class="form-control" name="qobuzLink" class="form-control form-control-sm text-danger" value="">
                </div><!-- end .input-group --><br><br>
                </fieldset><br>


                <button class="btn btn-primary mb-0" type="submit" name="posalji" >Pošalji</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="btn btn-warning mt-0" type="reset" value="Reset">
            </div><!-- /.col-md-7 sredina inline-block -->

            </form>

            <style>
            .error-border {
                border: 2px solid red !important;
            }
            </style>

            <script>
            document.getElementById("singlForm").addEventListener("submit", function(e) {
                let errors = [];

                // očisti stare crvene okvire
                document.querySelectorAll("#singlForm input, #singlForm select, #singlForm textarea").forEach(el => {
                    el.classList.remove("error-border");
                });

                // elementi
                let nazivSingla   = document.getElementById("nazivSingla");
                let godinaIzdanjaSingl = document.getElementById("godinaIzdanjaSingl");
                let drzava        = document.getElementById("drzava");
                let feat          = document.getElementById("feat");      // FEAT
                let izdavac1      = document.getElementById("izdavac1");  // IZDAVAC 1

                // validacije
                if (nazivSingla && nazivSingla.value.trim() === "") {
                    errors.push("Naziv singla");
                    nazivSingla.classList.add("error-border");
                }
                if (godinaIzdanjaSingl && godinaIzdanjaSingl.value.trim() === "") {
                    errors.push("Godina izdanja");
                    godinaIzdanjaSingl.classList.add("error-border");
                }
                if (drzava && drzava.value.trim() === "") {
                    errors.push("Država");
                    drzava.classList.add("error-border");
                }
                if (feat && feat.value.trim() === "") {
                    errors.push("Feat (izvođač)");
                    feat.classList.add("error-border");
                }
                if (izdavac1 && izdavac1.value.trim() === "") {
                    errors.push("Izdavač 1");
                    izdavac1.classList.add("error-border");
                }

                // ako ima grešaka - stopiraj submit
                if (errors.length > 0) {
                    e.preventDefault();
                    alert("Niste popunili sledeća polja:\n- " + errors.join("\n- "));
                }
            });
            </script>
        </div><!-- end .razmakIzmedju -->
                
        <?php 
        if(isset($_POST["posalji"]))
        {
            $this->nazivSingla= trim(removeSimbols($_POST["nazivSingla"]));
            $this->singleIzvodjaci= trim(removeSimbols($_POST["singleIzvodjaci"]));
            $this->singleFeat= trim(removeSimbols($_POST["feat"]));
            $this->godinaIzdanjaSingl= trim(removeSimbols($_POST["godinaIzdanjaSingl"]));
            $this->tacanDatumIzdanja= cleanText($_POST["tacanDatumIzdanja"]);
            $this->drzavaSingl= trim(removeSimbols($_POST["drzava"]));
            @$this->entitetSingl= trim(removeSimbols($_POST["entitet"]));

            @$this->ostaleNapomeneSingl= trim(removeSimbols($_POST["ostaleNapomeneSingl"]));
            @$this->tekstSingla= trim(removeSimbols(($_POST["tekstSingla"])));
            $this->izdavac1= $_POST["izdavac1"];

            $this->izdavac1= ($this->izdavac=="") ? $this->idIzdavaci=10 : $this->izdavac;


            $youtubeVideoLink= $newStream->cleanStreamsYoutubeVideo($_POST["youtubeVideoLink"]);
            $spotifyLink= $newStream->cleanStreamsSpotify($_POST["spotifyLink"]);
            $deezerLink= $newStream->cleanStreamsDeezer($_POST["deezerLink"]);
            $appleMusicLink= $newStream->cleanStreamsAppleMusic($_POST["appleMusicLink"]);
            $tidalLink= $newStream->cleanStreamsTidal($_POST["tidalLink"]);
            $youtubeMusicLink= $newStream->cleanStreamsYoutubeMusic($_POST["youtubeMusicLink"]);
            $amazonMusicLink= $newStream->cleanStreamsAmazonMusic($_POST["amazonMusicLink"]);
            $soundCloudLink= $newStream->cleanStreamsSoundCloud($_POST["soundCloudLink"]);
            $amazonShopLink= $newStream->cleanStreamsAmazonShop($_POST["amazonShopLink"]);
            $bandCampLink= $newStream->cleanStreamsbandCamp($_POST["bandCampLink"]);
            $qobuzLink= $newStream->cleanStreamsqobuz($_POST["qobuzLink"]);

            if(!empty($this->nazivSingla) AND !empty($this->godinaIzdanjaSingl) AND !empty($this->drzavaSingl))
            {
                if(!empty($this->singleIzvodjaci) || !empty($this->singleFeat))
                {
                $q3= "INSERT INTO singlovi (singlNaziv, singleIzvodjaci, singleFeat, godinaIzdanjaSingl, tacanDatumIzdanja, ostaleNapomeneSingl, tekstSingla, dodaoSingl, drzavaSingl, entitetSingl, youtubeVideo, spotify, deezer, appleMusic, tidal, youtubeMusic, amazonMusic, soundCloud, amazonShop, bandCamp, qobuz) VALUES ('$this->nazivSingla', '$this->singleIzvodjaci', '$this->singleFeat', '$this->godinaIzdanjaSingl', '$this->tacanDatumIzdanja', '$this->ostaleNapomeneSingl', '$this->tekstSingla', '$slid', '$this->drzavaSingl', '$this->entitetSingl', '$youtubeVideoLink', '$spotifyLink', '$deezerLink', '$appleMusicLink', '$tidalLink', '$youtubeMusicLink', '$amazonMusicLink','$soundCloudLink', '$amazonShopLink','$bandCampLink', '$qobuzLink')"; 
                $insert_single= mysqli_query($conn, $q3);
                //print_r($q3. "<hr>");

                    if($insert_single == TRUE)
                    {
                        $this->lastInsertSinglId= mysqli_insert_id($conn);  //hvata zadnji insertovani ID
                        logSingleAdded($this->lastInsertSinglId, $this->nazivSingla);

                        // Uzmi izdavače iz forme
                        $izdavaci = [];
                        if (!empty($_POST['izdavac1'])) $izdavaci[] = $_POST['izdavac1'];
                        if (!empty($_POST['izdavac2'])) $izdavaci[] = $_POST['izdavac2'];

                        // Unesi ih u poveznu tabelu
                        foreach ($izdavaci as $idIzdavac) {
                            $idIzdavac = mysqli_real_escape_string($conn, $idIzdavac);
                            $q4= "INSERT INTO single_izdavaci (idSingle, idIzdavaci) VALUES ('{$this->lastInsertSinglId}', '{$idIzdavac}')";
                            $insert_single_izdavace= mysqli_query($conn, $q4);
                            //print_r("<hr>".$q4);
                        }

                        //--------------------------------------
                        include_once "imageUploader.class.php";
                        $uploader = new ImageUploader();
                        //--------------------------------------

                        
                        if(!empty($_FILES["dodajNaslovnuSlikuSingla"]["name"]))
                        {

                            $res = $uploader->uploadAndUpdateImageField("dodajNaslovnuSlikuSingla", "images/singlovi/", "slika_singla", (int)$this->lastInsertSinglId, $conn,"singlovi", /* tabela*/ "slikaSingla",  /*kolona slike*/ "idSinglovi", /* id kolona*/ 75);

                        }//dodavanje slike singla

                    //echo "<meta http-equiv='refresh' content='0'; url='dodajalbume.php?data=singlovi'>";
                    }else{
                        echo "Greška " . mysqli_error($conn). "<br>";
                    }//end if($insert_single == TRUE)
                }else{
                        echo "Greška " . mysqli_error($conn). "<br>";
                    }//end if(provjera uslova prazan single Izvodjac ili feat.)
            }else{
                echo "Niste unijeli podatke u jedno od polja: Naziv singla, godina izdanja ili Država";
            }//end if(provjera uslova)
        }// end if(isset($_POST["posalji"]))
    }// end insertSingl() 
    //********************************* Metoda pozvana u fajlu insertDataPanel.class.php *********************************//

     //--------------------------------------------------------------------------------------------------------------------------------

     //********************************* Metoda sa kojom unosimo sliku singla *********************************//
     public function unosNaslovneSlikeSingla($naslovnaSlikaSingla, $idAlbum)
    {
        include_once "functions/removeSymbols.func.php";
        global $conn;

        $whitelist = array(".jpg",".jpeg",".gif",".png", ".svg", ".webp");
        //$whitelist= array("image/jpeg", "image/png", "image/gif", "image/svg", "image/webp", "image/apng", "image/avif");

        $maxVelicinaSlike= 2097152; //2mb
        $minVelicinaSlike= 10000; //10kb

        // ✅ NOVO: poruka greške za log (jedno mjesto)
        $errorMessage = null;

        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            // ✅ NOVO: PHP upload error (ini size, partial, itd.)
            $phpErr = $_FILES["dodajNaslovnuSlikuSingla"]["error"] ?? UPLOAD_ERR_OK;
            if ($phpErr !== UPLOAD_ERR_OK) {
                // ako imaš phpUploadErrorText() koristi ga, ako ne – ostavi broj
                $errorMessage = function_exists('phpUploadErrorText')
                    ? phpUploadErrorText((int)$phpErr)
                    : 'PHP upload error code: ' . (int)$phpErr;

                // FAIL LOG
                logUploadFail(
                    'single_song_image',
                    'idSinglovi',
                    $this->lastInsertSinglId,
                    $_FILES['dodajNaslovnuSlikuSingla']['name'] ?? '',
                    $errorMessage,
                    $_FILES['dodajNaslovnuSlikuSingla']['size'] ?? null
                );

                return; // prekid, nema smisla dalje
            }

            $size= $_FILES["dodajNaslovnuSlikuSingla"]["size"];
            //print_r($size) . "<hr>";
            if(($size > $maxVelicinaSlike) || ($size< $minVelicinaSlike))
            {
                echo "<script>
                            document.getElementById('promSlik').innerHTML='Prevelika slika (veća od 2mb) ili je premala slika (manja od 10kb)';
                </script>";

                // ✅ NOVO: FAIL LOG (veličina)
                $errorMessage = 'Prevelika slika (>2MB) ili je premala (<10KB)';
                logUploadFail(
                    'single_song_image',
                    'idSinglovi',
                    $this->lastInsertSinglId,
                    $_FILES['dodajNaslovnuSlikuSingla']['name'] ?? '',
                    $errorMessage,
                    $_FILES['dodajNaslovnuSlikuSingla']['size'] ?? null
                );

            }else
            {
                $putanja = "images/singlovi/";
                $skeniraj= scandir($putanja);
                //print_r($skeniraj);

                /*$provjeraEkstenzije1= pathinfo($putanja.$slikaAlbuma);
                $provjeraEkstenzije2= $provjeraEkstenzije1['extension'];*/

                $imeSlike= removeSimbolsImg($naslovnaSlikaSingla);
                $ukloniEkstenziju= explode(".", $imeSlike);
                $ekstenzija= end($ukloniEkstenziju);
                $vrijeme= "_im".date("dmY_His", time())."_".time().".";
                $slikaVrijeme= $ukloniEkstenziju[0].$vrijeme;
                //$slikaVrijeme= array_shift($ukloniEkstenziju);

                //if provjera ekstenzije
                if (!(in_array(".".$ekstenzija, $whitelist)))
                {
                    // ✅ NOVO: FAIL LOG (format)
                    $errorMessage = 'Nepravilan format slike: .' . $ekstenzija;

                    logUploadFail(
                        'single_song_image',
                        'idSinglovi',
                        $this->lastInsertSinglId,
                        $_FILES['dodajNaslovnuSlikuSingla']['name'] ?? '',
                        $errorMessage,
                        $_FILES['dodajNaslovnuSlikuSingla']['size'] ?? null
                    );

                    // ostavljam tvoj postojeći način prekida
                    die('Nepravilan format slike, pokušajte sa drugom slikom');
                }else
                {
                    $provjeraSlike= $putanja.$slikaVrijeme.$ekstenzija;
                    if(!file_exists(($provjeraSlike)))
                    {
                        $slikaVrijeme= $slikaVrijeme.$ekstenzija;
                        $slikaAlbuma_tmp= $_FILES["dodajNaslovnuSlikuSingla"]["tmp_name"];
                        //move_uploaded_file($slikaAlbuma_tmp, $provjeraSlike);
                    }//end else !file_exists(($provjeraSlike)

                    $putanja = "images/singlovi/";

                    // ✅ NOVO: uradi move pa tek onda UPDATE (da ne upišeš u bazu sliku koja nije snimljena)
                    $movedPicture= move_uploaded_file($slikaAlbuma_tmp, $provjeraSlike);
                    //print_r("<hr>$q");

                    if($movedPicture==TRUE){

                        // ✅ UPDATE samo ako je upload stvarno prošao
                        $q="UPDATE singlovi SET slikaSingla='{$slikaVrijeme}' WHERE idSinglovi='{$this->lastInsertSinglId}'";
                        $promjeniSliku= mysqli_query($conn, $q);

                        // SUCCESS LOG
                        logUploadSuccess(
                            'single_song_image',
                            'idSinglovi',
                            $this->lastInsertSinglId,
                            $_FILES['dodajNaslovnuSlikuSingla']['name'],
                            $_FILES['dodajNaslovnuSlikuSingla']['size']
                        );

                        if($promjeniSliku == TRUE)
                        {
                            echo "<meta http-equiv='refresh' content='0'>";
                        }else{
                            echo "Greška " . mysqli_error($conn). "<br>";

                            // ✅ NOVO: ako DB update padne, zabilježi kao fail (upload je uspio, ali DB nije)
                            $errorMessage = "DB update fail: " . mysqli_error($conn);
                            logUploadFail(
                                'single_song_image',
                                'idSinglovi',
                                $this->lastInsertSinglId,
                                $_FILES['dodajNaslovnuSlikuSingla']['name'] ?? '',
                                $errorMessage,
                                $_FILES['dodajNaslovnuSlikuSingla']['size'] ?? null
                            );
                        }

                    }else{

                        // FAIL LOG (move fail)
                        $errorMessage = 'move_uploaded_file fail';
                        logUploadFail(
                            'single_song_image',
                            'idSinglovi',
                            $this->lastInsertSinglId,
                            $_FILES['dodajNaslovnuSlikuSingla']['name'] ?? '',
                            $errorMessage,
                            $_FILES['dodajNaslovnuSlikuSingla']['size'] ?? null
                        );

                    }

                }//end while loop provjera korisničkog imena i šifre
            }//end provjera ekstenzije
        }// end if provjera REQUEST_METHOD

    }//end function unosPrednjeSlikeAlbuma()
     //********************************* Pozvana metoda u ovom fajlu u metodi insertSingl() *********************************//

     //--------------------------------------------------------------------------------------------------------------------------------
}//end class