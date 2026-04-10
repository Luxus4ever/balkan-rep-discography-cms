<?php

class insertAlbum{

    //METODE SADRŽANE U OVOJ KLASI
    //insertAboutAlbum (Forma za unos novog albuma)
    //insertAlbumImages (Ubacuje više slika u bazu odjednom)
    //convertToWebpAndSave (Funkcija koja konvertuje jpg/jpeg i png slike u webp format)
    //unosPrednjeSlikeAlbuma (Unos prednje slike albuma)
    //unosZadnjeSlikeAlbuma (unos zadnje slike albuma)

    protected $whitelist;
    protected $maxVelicinaSlike;
    protected $minVelicinaSlike;
    protected $minVelicinaSlikeWebp;
    protected $minVelicinaSlikeSvg;
    protected $targetDir;
    protected $fileName;
    protected $targetPath;
    protected $size;
    protected $imeSlike;
    protected $ekstenzija;
    protected $ukloniEkstenziju;
    protected $idAlb; 
    protected $vrijeme;
    protected $slikaVrijeme;
    protected $originalnaImenaFajlova;
    protected $originalnoImeFajla;
    //public 

    protected $unutrasnjeSlikeAlbuma;

    protected $idIzvodjaci;
    protected $izvodjacMaster;

    protected $idDrzave;
    protected $drzavaNaziv;
    protected $kodZemljeDugi;
    protected $zastava;
    protected $idEntiteti;
    protected $entitetNaziv;
    protected $entDrzava;
    protected $zastavaEnt;
    protected $kodEntiteta;

    protected $izvodjac1;
    protected $izvodjac2;
    protected $izvodjac3;
    protected $nazivAlbuma;
    protected $godinaIzdanja;
    protected $tacanDatumIzdanja;
    protected $drzavaIzvodjac;
    protected $entitetIzvodjac;
    protected $izdavac;
    protected $ostaleNapomeneAlbum;

    protected $nazivSingla;
    protected $ostaleNapomeneSingl;
    
    protected $slikaAlbumaPrednja;
    protected $slikaAlbumaZadnja;
    protected $lastInsertAlbumId;
    protected $izdavac1;
    protected $izdavac2;
    protected $izdavac3;
    protected $izdavac4;

    protected $idIzdavaci;
    protected $izdavaciNaziv;

    protected $brojPjesama;

    //********************************* Metoda u koju je smještena forma za unos novog albuma  *********************************//
    function insertAboutAlbum($slid)
    {
        global $conn;

        include "insertStreams.class.php";
        $newStream= new insertStreaming();
        
        ?>
        <br>
        

        <div id="" class="razmakIzmedju">
            <div class="col-md-3">
                <div class="slikeAlbumaPregled sredina">
                    <div class="editAlbum">
                        <p class="text-warning"><strong>Prednja slika albuma (max 2mb)</strong></p>
                        <div class="border">
                        <label class="text-light bg-secondary">Naziv <strong>prednje</strong> slike albuma</label><br>
                        <label class="text-light opisDodavanjaSlike">Izvođač - Ime Albuma (godina izdanja) - prednja/front</label><br>
                        </div><!-- end .border -->
                        <img src="../images/albumi/" alt="" title=" (front)" class=""/>
                        <form method="POST" action="" enctype="multipart/form-data" name="izmjenaAlbuma" id="izmjenaAlbuma">
                            <input type="file" class="btn btn-light" name="dodajSlikuAlbumaPrednja"><br><br>
                            <br><hr class="hrLinija">
                        
                            <p class="text-warning"><strong>Zadnja slika albuma (max 2mb)</strong></p>
                            <div class="border">
                            <label class="text-light bg-secondary">Naziv <strong>zadnje</strong> slike albuma</label><br>
                            <label class="text-light opisDodavanjaSlike">Izvođač - Ime Albuma (godina izdanja) - zadnja/back</label><br>
                            </div><!-- end .border -->
                            <input type="file" class="btn btn-light" name="dodajSlikuAlbumaZadnja"><br><br>
                            <br><hr class="hrLinija">
                        
                                <label for="files" class="text-warning"><strong>Izaberi ostale slike za upload <br> (moguće više fajlova odjednom)<br>(max 2mb po slici)</strong></label>
                                <div class="border">
                                <label for="files" class="text-light bg-secondary">Naziv <strong>ostalih</strong> slika albuma</label><br>
                                <label for="files" class="text-light opisDodavanjaSlike">Naziv ne smije da sarži više tačaka kao npr: <br>
                                    <i>BSSST... Tišinčina</i><br>
                                    <i>Shorty - 1.68 Metaršezdesetosam</i><br>
                                    <i>V.I.P.</i><br>
                                </label><br>
                                <label for="files" class="text-light opisDodavanjaSlike">Dozvoljeni načini:<br>
                                    <i>Izvođač - Ime Albuma (godina izdanja) - CD</i><br>
                                    <i>Izvođač - Ime Albuma (godina izdanja) - unutra1</i><br>
                                    <i>Izvođač - Ime Albuma (godina izdanja) - unutra2</i></label><br>
                                    </div><!-- end .border -->
                                <input type="file" class="btn btn-light" name="images[]" id="files" multiple /><br><br>
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
                <label for="nazivAlbuma" class="text-warning"><strong>Naziv albuma <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong></label><br>
                <input type="text" name="nazivAlbuma" class="form-control form-control-sm text-danger" value="" required><br><br>

                <?php 
                /*------------------------ početak padajućeg menija izvođači ------------------------*/
                ?>
                <fieldset class="border p-5 rounded">
                    <h4 class="podebljano bg-dark text-warning sredina">Izvođači</h4>
                    <?php
                    $q = "SELECT * FROM izvodjaci ORDER BY izvodjacMaster";
                    $select_izvodjaci = mysqli_query($conn, $q);

                    $izvodjaci = [];
                    while ($red = mysqli_fetch_assoc($select_izvodjaci)) {
                        $izvodjaci[] = $red; // čuvamo rezultate u niz
                    }

                    function ispisiOpcijeIzvodjaci($izvodjaci) {
                        foreach ($izvodjaci as $red) {
                            echo '<option value="'.$red["idIzvodjaci"].'">'.$red["izvodjacMaster"].'</option>';
                        }
                    }//end function ispisiOpcije
                    ?>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const selects = document.querySelectorAll(".izvodjac-select");

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
                                    alert("Ne možete izabrati istog izvođača više puta!");
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

                <label for="izvodjac1" class="text-warning">
                    <strong>Izvođač 1 <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong>
                </label><br>
                <select class="form-control izvodjac-select" name="izvodjac1" id="izvodjac1" required>
                    <option value="" disabled selected>Izaberi izvođača</option>
                    <?php ispisiOpcijeIzvodjaci($izvodjaci); ?>
                </select><br><br>

                <?php 
                
                for($i=2; $i<=3; $i++)
                {
                    ?>
                    <label for="izvodjac<?php echo $i; ?>" class="text-warning"><strong>Izvođač <?php echo $i; ?></strong></label><br>
                    <label for="izvodjac2" class="text-light bg-secondary"><strong>(ukoliko ima još jedan izvođač na albumu npr. <i>Rimski & Corona</i> ili <i>Frenkie, Contra, Indigo</i>)</strong></label><br>
                    <label for="izvodjac2" class="text-light">Ukoliko nema izvođača u listi, idite u opciju <b>dodaj novog izvođača</b></label><br>
                    <select class="form-control izvodjac-select" name="izvodjac<?php echo $i; ?>" id="izvodjac<?php echo $i; ?>">
                        <option value="" disabled selected>Izaberi izvođača</option>
                    <?php ispisiOpcijeIzvodjaci($izvodjaci); ?>
                    </select><br><br>
                    <?php
                }
                
                ?>
            </fieldset><br>
            <?php 
            /*------------------------ end padajućeg menija izvođači ------------------------*/
            ?>

                <label for="godinaIzdanja" class="text-warning"><strong>Godina izdanja <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong></label><br>
                <label for="godinaIzdanja" class="text-light">Upisati samo godinu bez tačke na kraju</label><br>
                <b><input type="number" name="godinaIzdanja" class="form-control form-control-sm text-dark" placeholder="1995"></b><br><br>

                <label for="tacanDatumIzdanja" class="text-warning"><strong>Tačan datum izdanja (ukoliko je poznat)</strong></label><br>
                <label for="tacanDatumIzdanja" class="text-light">Upisati u formatu 01.02.2023. (dd.mm.gggg.)</label><br>
                <b><input type="text" name="tacanDatumIzdanja" class="form-control form-control-sm text-dark" placeholder="01.02.2023."></b><br><br>

                <label for="drzava" class="text-warning"><strong>Država (za čije glavno tržište je album) <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong></label><br>
                <select class="form-control" name="drzava" id="drzava" required>
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

                        echo "<option value='{$this->idEntiteti}'>$this->entitetNaziv </option>";
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

                <?php 
                /*------------------------ Početak padajućeg menija izdavači ------------------------*/
                ?>
                <fieldset class="border p-5 rounded">
                    <h4 class="podebljano bg-dark text-warning sredina">&nbsp;Izdavači</h4>
                     <?php
                        $q = "SELECT * FROM izdavaci ORDER BY izdavaciNaziv";
                        $select_izdavaci = mysqli_query($conn, $q);

                        $izdavaci = [];
                        while ($red = mysqli_fetch_assoc($select_izdavaci)) {
                            $izdavaci[] = $red; // čuvamo rezultate u niz
                        }

                        function ispisiOpcije($izdavaci) {
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
                    <label for="izdavac" class="text-light">Ako je album postavljen na net ili nije zvanično objavljen izaberite <b>Samoizdanje</b></label><br>
                        <strong>Izdavač 1 <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong>
                    </label><br>
                    <select class="form-control izdavac-select" name="izdavac1" id="izdavac1" required>
                        <option value="" disabled selected>Izaberi izdavača</option>
                        <?php ispisiOpcije($izdavaci); ?>
                    </select><br><br>

                    <label for="izdavac2" class="text-warning"><strong>Izdavač 2</strong> (Ukoliko ima više izdavača)</label><br>
                    <label for="izdavac" class="text-light">Ako nema na listi idite na <b>dodaj Izdavačku kuću</b></i></label><br>
                    <select class="form-control izdavac-select" name="izdavac2" id="izdavac2">
                        <option value="" disabled selected>Izaberi izdavača</option>
                        <?php ispisiOpcije($izdavaci); ?>
                    </select><br><br>

                    <label for="izdavac3" class="text-warning"><strong>Izdavač 3</strong> (Ukoliko ima više izdavača)</label><br>
                    <label for="izdavac" class="text-light">Ako nema na listi idite na <b>dodaj Izdavačku kuću</b></i></label><br>
                    <select class="form-control izdavac-select" name="izdavac3" id="izdavac3">
                        <option value="" disabled selected>Izaberi izdavača</option>
                        <?php ispisiOpcije($izdavaci); ?>
                    </select><br><br>

                    <label for="izdavac4" class="text-warning"><strong>Izdavač 4</strong> (Ukoliko ima više izdavača)</label><br>
                    <label for="izdavac" class="text-light">Ako nema na listi idite na <b>dodaj Izdavačku kuću</b></i></label><br>
                    <select class="form-control izdavac-select" name="izdavac4" id="izdavac4">
                        <option value="" disabled selected>Izaberi izdavača</option>
                        <?php ispisiOpcije($izdavaci); ?>
                    </select><br><br>

                    <label for="izdavac5" class="text-warning"><strong>Izdavač 5</strong> (Ukoliko ima više izdavača)</label><br>
                    <label for="izdavac" class="text-light">Ako nema na listi idite na <b>dodaj Izdavačku kuću</b></i></label><br>
                    <select class="form-control izdavac-select" name="izdavac5" id="izdavac5">
                        <option value="" disabled selected>Izaberi izdavača</option>
                        <?php ispisiOpcije($izdavaci); ?>
                    </select>
                </fieldset><br>
                <?php 
                /*------------------------ end padajućeg menija izdavači ------------------------*/



                /*------------------------ početak padajućeg menija kategorije ------------------------*/
                ?>

                <fieldset class="border p-5 rounded">
                    <h4 class="podebljano bg-dark text-warning sredina">Kategorije</h4>
                     <?php
                        $q = "SELECT * FROM kategorije_albuma";
                        $select_kategorije = mysqli_query($conn, $q);

                        $kategorije = [];
                        while ($red = mysqli_fetch_assoc($select_kategorije)) {
                            $kategorije[] = $red; // čuvamo rezultate u niz
                        }

                        function ispisiOpcijeKategorije($kategorije) {
                            foreach ($kategorije as $red) {
                                echo '<option value="'.$red["idKategorijeAlbuma"].'">'.$red["nazivKategorijeAlbuma"].'</option>';
                            }
                        }//end function ispisiOpcije
                        ?>

                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const selects = document.querySelectorAll(".kategorija-select");

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
                                        alert("Ne možete izabrati istu kategoriju više puta!");
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

                    <label for="kategorija1" class="text-warning">
                    <label for="kategorija" class="text-light">Unesite bar jednu vrstu kategorije/podkategorije za album</label><br>
                        <strong>Kategorija 1 <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong>
                    </label><br>
                    <select class="form-control kategorija-select" name="kategorija1" id="kategorija1" required>
                        <option value="" disabled selected>Izaberi kategoriju</option>
                        <?php ispisiOpcijeKategorije($kategorije); ?>
                    </select><br><br>

                    <label for="kategorija2" class="text-warning"><strong>Kategorija 2</strong> (Ukoliko je potrebno)</label><br>
                    <select class="form-control kategorija-select" name="kategorija2" id="kategorija2">
                        <option value="" disabled selected>Izaberi kategoriju</option>
                        <?php ispisiOpcijeKategorije($kategorije); ?>
                    </select><br><br>

                    <label for="kategorija3" class="text-warning"><strong>Kategorija 3</strong> (Ukoliko je potrebno)</label><br>
                    <select class="form-control kategorija-select" name="kategorija3" id="kategorija3">
                        <option value="" disabled selected>Izaberi kategoriju</option>
                        <?php ispisiOpcijeKategorije($kategorije); ?>
                    </select><br><br>
                </fieldset><br>

                <?php 
                /*------------------------ end padajućeg menija kategorije ------------------------*/
                ?>


                <label for="brojPjesama" class="text-warning"><strong>Broj pjesama na albumu <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong></label><br>
                <label for="brojPjesama" class="text-light">Ukoliko je više CD-ova navedite ukupno pjesama. Možete unijeti samo broj</label><br>
                <input type="number" name="brojPjesama" class="form-control form-control-sm" placeholder="npr. 15" required><br><br>

                <label for="ostaleNapomeneAlbum" class="text-warning"><strong>Ostale napomene vezane za album</strong></label><br>
                <label for="ostaleNapomeneAlbum" class="text-light">Nije obavezno polje, primjeri podataka koji se tiču za čitav album</label><br>
                <textarea class="dodajTekstNapomene" name="ostaleNapomeneAlbum" 
                placeholder="Snimano u studiju: ***
                Phonographic Copyright ℗ – **** 
                Copyright © – *** 
                Printed By – ***
                Design – ***
                Music By – ***
                Barcode: ****
                Other (ISBN): ***
                Other (COBISS.SR-ID): ****
                Rights Society: ***"><?php ; ?></textarea><br><br>
                
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
                </fieldset>


                <button class="btn btn-primary mb-0" type="submit" name="posalji" >Pošalji</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="btn btn-warning mt-0" type="reset" value="Reset">
            </div><!-- /.col-md-7 -->

                        </form>
        </div><!-- end .razmakIzmedju -->
                
        <?php 
        if(isset($_POST["posalji"]))
        {

            $this->izvodjac1= trim(removeSimbols($_POST["izvodjac1"]));
            @$this->izvodjac2= trim(removeSimbols($_POST["izvodjac2"]));
            @$this->izvodjac3= trim(removeSimbols($_POST["izvodjac3"]));
            $this->nazivAlbuma= trim(removeSimbols($_POST["nazivAlbuma"]));
            $this->godinaIzdanja= trim(removeSimbols($_POST["godinaIzdanja"]));
            $this->tacanDatumIzdanja= cleanText($_POST["tacanDatumIzdanja"]);
            $this->drzavaIzvodjac= trim(removeSimbols($_POST["drzava"]));
            @$this->entitetIzvodjac= trim(removeSimbols($_POST["entitet"]));
            //$this->izdavac= trim(removeSimbols($_POST["izdavac"]));
            @$this->ostaleNapomeneAlbum= trim(removeSimbols($_POST["ostaleNapomeneAlbum"]));
            $this->izdavac1= $_POST["izdavac1"];

            $this->izdavac1= ($this->izdavac=="") ? $this->idIzdavaci=10 : $this->izdavac;
            $this->brojPjesama= trim($_POST["brojPjesama"]);

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

            if(!empty($this->brojPjesama))
            {
                if(empty($izvodjac2) && empty($izvodjac3)){
                    $q3= "INSERT INTO albumi (idIzvodjacAlbumi, nazivAlbuma, godinaIzdanja, tacanDatumIzdanja, drzavaAlbumi, entitetAlbumi, ostaleNapomeneAlbum, brojPjesama, dodaoAlbum) VALUES ('$this->izvodjac1', '$this->nazivAlbuma', '$this->godinaIzdanja', '$this->tacanDatumIzdanja', '$this->drzavaIzvodjac', '$this->entitetIzvodjac', '$this->ostaleNapomeneAlbum', '$this->brojPjesama', '$slid')"; 
                    $insert_album= mysqli_query($conn, $q3);
                    //print_r($q3);

                }else if(empty($izvodjac3))
                {
                    $q2= "INSERT INTO albumi (idIzvodjacAlbumi, idIzvodjac2, nazivAlbuma, godinaIzdanja, tacanDatumIzdanja, drzavaAlbumi, entitetAlbumi, ostaleNapomeneAlbum, brojPjesama, dodaoAlbum) VALUES ('$this->izvodjac1', '$this->izvodjac2', '$this->nazivAlbuma', '$this->godinaIzdanja', '$this->tacanDatumIzdanja', '$this->drzavaIzvodjac', '$this->entitetIzvodjac',  '$this->ostaleNapomeneAlbum', '$this->brojPjesama', '$slid')"; 
                    $insert_album= mysqli_query($conn, $q2);
                    //print_r($q2);
                }else if(!empty($izvodjac2) && !empty($izvodjac3))
                {
                    $q1= "INSERT INTO albumi (idIzvodjacAlbumi, idIzvodjac2, idIzvodjac3, nazivAlbuma, godinaIzdanja, tacanDatumIzdanja, drzavaAlbumi, entitetAlbumi, ostaleNapomeneAlbum, brojPjesama, dodaoAlbum) VALUES ('$this->izvodjac1', '$this->izvodjac2', '$izvodjac3', '$this->nazivAlbuma', '$this->godinaIzdanja', '$this->tacanDatumIzdanja', '$this->drzavaIzvodjac', '$this->entitetIzvodjac', '$this->ostaleNapomeneAlbum', '$this->brojPjesama', '$slid')"; 
                    $insert_album= mysqli_query($conn, $q1);
                    //print_r($q1);


                }
                if($insert_album == TRUE)
                {
                    $this->lastInsertAlbumId= mysqli_insert_id($conn);  //hvata zadnji insertovani ID

                    $youtubeVideoSql = ($youtubeVideoLink === null) ? "NULL" : "'" . mysqli_real_escape_string($conn, $youtubeVideoLink) . "'";
                    $spotifySql      = ($spotifyLink === null) ? "NULL" : "'" . mysqli_real_escape_string($conn, $spotifyLink) . "'";
                    $deezerSql       = ($deezerLink === null) ? "NULL" : "'" . mysqli_real_escape_string($conn, $deezerLink) . "'";
                    $appleMusicSql   = ($appleMusicLink === null) ? "NULL" : "'" . mysqli_real_escape_string($conn, $appleMusicLink) . "'";
                    $tidalSql        = ($tidalLink === null) ? "NULL" : "'" . mysqli_real_escape_string($conn, $tidalLink) . "'";
                    $youtubeMusicSql = ($youtubeMusicLink === null) ? "NULL" : "'" . mysqli_real_escape_string($conn, $youtubeMusicLink) . "'";
                    $amazonMusicSql  = ($amazonMusicLink === null) ? "NULL" : "'" . mysqli_real_escape_string($conn, $amazonMusicLink) . "'";
                    $soundCloudSql   = ($soundCloudLink === null) ? "NULL" : "'" . mysqli_real_escape_string($conn, $soundCloudLink) . "'";
                    $amazonShopSql   = ($amazonShopLink === null) ? "NULL" : "'" . mysqli_real_escape_string($conn, $amazonShopLink) . "'";
                    $bandCampSql     = ($bandCampLink === null) ? "NULL" : "'" . mysqli_real_escape_string($conn, $bandCampLink) . "'";
                    $qobuzSql        = ($qobuzLink === null) ? "NULL" : "'" . mysqli_real_escape_string($conn, $qobuzLink) . "'";


                    //Dodavanje strimova u posebnu tabelu
                    $q1stream1= "INSERT INTO streamovi (albumId, youtubeVideo, spotify, deezer, appleMusic, tidal, youtubeMusic, amazonMusic, soundCloud, amazonShop, bandCamp, qobuz) VALUES ({$this->lastInsertAlbumId},
                    $youtubeVideoSql,
                    $spotifySql,
                    $deezerSql,
                    $appleMusicSql,
                    $tidalSql,
                    $youtubeMusicSql,
                    $amazonMusicSql,
                    $soundCloudSql,
                    $amazonShopSql,
                    $bandCampSql,
                    $qobuzSql)"; 
                    $insert_album= mysqli_query($conn, $q1stream1);

                    logAlbumAdded($this->lastInsertAlbumId, $this->nazivAlbuma);

                     // ✅ Ako nije dodata prednja slika albuma, postavi default "NoCover1.png"
                    if (empty($_FILES["dodajSlikuAlbumaPrednja"]["name"])) {
                        $q_default = "UPDATE albumi SET slikaAlbuma='NoCover1.webp' WHERE idAlbum='{$this->lastInsertAlbumId}'";
                        mysqli_query($conn, $q_default);
                    }

                    // Uzmi izdavače iz forme
                    $izdavaci = [];
                    if (!empty($_POST['izdavac1'])) $izdavaci[] = $_POST['izdavac1'];
                    if (!empty($_POST['izdavac2'])) $izdavaci[] = $_POST['izdavac2'];
                    if (!empty($_POST['izdavac3'])) $izdavaci[] = $_POST['izdavac3'];
                    if (!empty($_POST['izdavac4'])) $izdavaci[] = $_POST['izdavac4'];
                    if (!empty($_POST['izdavac5'])) $izdavaci[] = $_POST['izdavac5'];

                    // Unesi ih u poveznu tabelu
                    foreach ($izdavaci as $idIzdavac) {
                        $idIzdavac = mysqli_real_escape_string($conn, $idIzdavac);
                        $q_izdavac= "INSERT INTO albumi_izdavaci (idAlbum, idIzdavaci) VALUES ('{$this->lastInsertAlbumId}', '{$idIzdavac}')";
                        mysqli_query($conn, $q_izdavac);
                        //print_r($q_izdavac);
                    }


                    // Uzmi kategorije iz forme
                    $kategorije = [];
                    if (!empty($_POST['kategorija1'])) $kategorije[] = $_POST['kategorija1'];
                    if (!empty($_POST['kategorija2'])) $kategorije[] = $_POST['kategorija2'];
                    if (!empty($_POST['kategorija3'])) $kategorije[] = $_POST['kategorija3'];

                    // Unesi ih u poveznu tabelu kategorije_albuma
                    foreach ($kategorije as $idKategorijeAlbuma) {
                        $idKategorijeAlbuma = mysqli_real_escape_string($conn, $idKategorijeAlbuma);
                        $q_kategorija= "INSERT INTO albumi_kategorije (idAlbum, idKategorijeAlbuma) VALUES ('{$this->lastInsertAlbumId}', '{$idKategorijeAlbuma}')";
                        mysqli_query($conn, $q_kategorija);
                        //print_r($q_kategorija);
                    }

                    //----------------------------------------
                    include_once "imageUploader.class.php";
                    $uploader = new ImageUploader();
                    //----------------------------------------

                    if(!empty($_FILES["dodajSlikuAlbumaPrednja"]["name"]) && $_FILES["dodajSlikuAlbumaPrednja"]["error"] === UPLOAD_ERR_OK)
                    {
                        $res = $uploader->uploadAndUpdateImageField("dodajSlikuAlbumaPrednja", "images/albumi/", "album_front", (int)$this->lastInsertAlbumId, $conn,"albumi", /* tabela*/ "slikaAlbuma",  /*kolona slike*/ "idAlbum", /* id kolona*/ 80);
                    }//promjena prednje slike albuma

                    if(!empty($_FILES["dodajSlikuAlbumaZadnja"]["name"]) && $_FILES["dodajSlikuAlbumaZadnja"]["error"] === UPLOAD_ERR_OK)
                    {
                        $res = $uploader->uploadAndUpdateImageField("dodajSlikuAlbumaZadnja", "images/albumi/back/", "album_back", (int)$this->lastInsertAlbumId, $conn,"albumi", /* tabela*/ "slikaAlbumaZadnja",  /*kolona slike*/ "idAlbum", /* id kolona*/ 80);
                    }//promjena zadnje slike albuma
                    
                    if (!empty($_FILES["images"]["name"][0]))
                    {
                        
                        $this->insertAlbumImages($this->lastInsertAlbumId);   

                        $ok = true;
                        if ($ok) {
                            logUploadSuccess('album_ostale',  $this->lastInsertAlbumId, $_FILES['images']['name'], $_FILES['images']['size']);
                        } else {
                            logUploadFail('album_ostale',  $this->lastInsertAlbumId, $_FILES['images']['name'], $error);
                        }

                    }
                    echo "<meta http-equiv='refresh' content='0'; url='adminalbumi.php'>";
                }else{
                    echo "Greška " . mysqli_error($conn). "<br>";
                }//end if else
            }//end if(!empty($brojPjesama))
        }// end if(isset($_POST["posalji"]))
    }// end insertAboutAlbum() 

    //********************************* Pozvana u fajlu insertDataPanel.class u metodi prikazUnosPanela() *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda koja ubacuje više slika u bazu odjednom  *********************************//
    public function insertAlbumImages($idAlb)
    {
        //include "functions/removeSymbols.func.php";
        global $conn;

        // Dozvoljeni formati (ekstenzije)
        $this->whitelist = array(".jpg",".jpeg",".gif",".png", ".svg", ".webp");

        // Max veličina (za sve)
        $this->maxVelicinaSlike= 2097152; //2mb

        // Min veličine po formatu (jer webp i svg mogu biti dosta manji)
        $this->minVelicinaSlike      = 10000; //10kb (default za jpg/png/gif)
        $this->minVelicinaSlikeWebp  = 2000;  //2kb  (webp može biti manji)
        $this->minVelicinaSlikeSvg   = 500;   //0.5kb (svg je tekst i zna biti baš mali)

        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $this->targetDir = "images/albumi/inside/"; // Direktorij za pohranu slika

            $this->originalnaImenaFajlova = $_FILES['images']['name'];

            foreach ($this->originalnaImenaFajlova as $key => $originalnoImeFajla)
            {
                // ===============================
                // 1) OSNOVNI PODACI (ime/size/tmp)
                // ===============================
                $this->fileName = basename(removeSimbolsImg($originalnoImeFajla));
                $this->size = $_FILES["images"]["size"][$key]; // Veličina slike u bajtima
                $tmpName = $_FILES['images']['tmp_name'][$key];

                // ===============================
                // 2) PRIPREMA: ekstenzija + novo ime fajla
                // ===============================
                $this->imeSlike= $this->fileName;
                $this->ukloniEkstenziju= explode(".", $this->imeSlike);
                $this->ekstenzija= strtolower(end($this->ukloniEkstenziju));
                $this->vrijeme= "_im".date("dmY_His", time())."_".time().".";

                //$this->slikaVrijeme= $this->ukloniEkstenziju[0].$this->vrijeme;
                $this->slikaVrijeme = (isset($this->ukloniEkstenziju[1]) && in_array(strtolower($this->ukloniEkstenziju[1]), ["jpg","jpeg","gif","png","svg","webp"]))
                ? $this->ukloniEkstenziju[0].$this->vrijeme
                : $this->ukloniEkstenziju[0].$this->vrijeme;

                $newFileName= $this->slikaVrijeme.$this->ekstenzija;

                // ===============================
                // 3) PROVJERA: ekstenzija (whitelist)
                // ===============================
                if (!(in_array(".".$this->ekstenzija, $this->whitelist)))
                {
                    die('Nepravilan format slike ili je slika prevelika, pokušajte sa drugom slikom');
                }

                // ===============================
                // 4) PROVJERA: veličina slike (max + min po ekstenziji)
                // ===============================
                if ($this->size > $this->maxVelicinaSlike) {
                    die("Slika je prevelika. Maksimalna veličina je 2MB.");
                }

                // ✅ MIN veličina zavisi od ekstenzije
                $min = $this->minVelicinaSlike; // default 10kb

                if ($this->ekstenzija === "webp") {
                    $min = $this->minVelicinaSlikeWebp; // 2kb
                }
                if ($this->ekstenzija === "svg") {
                    $min = $this->minVelicinaSlikeSvg; // 0.5kb
                }

                if ($this->size < $min) {
                    die("Slika je premala. Minimalna veličina je " . round($min/1024, 1) . "KB.");
                }

                // ===============================
                // 5) PROVJERA: MIME (stvarni format fajla po sadržaju)
                // - ne vjerujemo samo ekstenziji
                // - MIME dobijamo iz getimagesize() (za raster slike)
                // - SVG je tekstualni fajl, njega ne provjeravamo preko getimagesize
                // ===============================
                $mime = "";

                if ($this->ekstenzija !== "svg") {

                    $info = @getimagesize($tmpName);
                    if ($info === false || empty($info['mime'])) {
                        die("Fajl nije validna slika: " . htmlspecialchars($originalnoImeFajla));
                    }

                    $mime = $info['mime'];

                    // Dozvoljeni MIME tipovi (u skladu sa whitelist ekstenzijama)
                    $allowedMime = array(
                        'image/jpeg', // jpg/jpeg
                        'image/png',  // png
                        'image/gif',  // gif
                        'image/webp'  // webp
                    );

                    if (!in_array($mime, $allowedMime)) {
                        die("Nepodržan format slike (MIME): " . htmlspecialchars($mime));
                    }

                    // Dodatna zaštita: npr. ako neko stavi ekstenziju .jpg a MIME ispadne png/webp itd.
                    // (možeš ostaviti ili izbaciti - ja bih ostavio)
                    if (($this->ekstenzija === "jpg" || $this->ekstenzija === "jpeg") && $mime !== "image/jpeg") {
                        die("Ekstenzija i MIME se ne poklapaju (očekivan JPEG).");
                    }
                    if ($this->ekstenzija === "png" && $mime !== "image/png") {
                        die("Ekstenzija i MIME se ne poklapaju (očekivan PNG).");
                    }
                    if ($this->ekstenzija === "gif" && $mime !== "image/gif") {
                        die("Ekstenzija i MIME se ne poklapaju (očekivan GIF).");
                    }
                    if ($this->ekstenzija === "webp" && $mime !== "image/webp") {
                        die("Ekstenzija i MIME se ne poklapaju (očekivan WEBP).");
                    }
                }

                // ===============================
                // 6) UPLOAD + (po potrebi) KONVERZIJA U WEBP
                // Konvertujemo SAMO: JPG/JPEG i PNG
                // GIF i SVG idu regularan upload (bez konvertovanja)
                // WEBP već jeste WEBP (samo upload)
                // ===============================
                $finalFileName = $newFileName; // default

                // 1) Ako je već webp -> samo upload
                if ($this->ekstenzija === "webp") {

                    move_uploaded_file($tmpName, $this->targetDir . $newFileName);
                    $finalFileName = $newFileName;

                }
                // 2) SVG i GIF ne konvertujemo (ali dozvoljavamo upload)
                else if ($this->ekstenzija === "svg" || $this->ekstenzija === "gif") {

                    move_uploaded_file($tmpName, $this->targetDir . $newFileName);
                    $finalFileName = $newFileName;

                }
                // 3) JPG/JPEG/PNG -> WEBP (ako server podržava)
                else {

                    // Uvijek želimo da rezultat u bazi bude .webp
                    $finalFileName = $this->slikaVrijeme . "webp";

                    $ok = $this->convertToWebpAndSave($tmpName, $this->targetDir . $finalFileName, 75);

                    // Ako konverzija ne uspije (nema GD/WebP podrške itd) -> fallback na normalan upload
                    if ($ok !== true) {
                        // fallback: snimi original
                        move_uploaded_file($tmpName, $this->targetDir . $newFileName);
                        $finalFileName = $newFileName;
                    }
                }

                // ===============================
                // 7) UPIS U BAZU
                // ===============================
                $q2= "INSERT INTO slike_albuma_ostale (albumId, unutrasnjeSlikeAlbuma)
                    VALUES ('{$idAlb}', '{$finalFileName}')";

                $update_inside_images= mysqli_query($conn, $q2);
                if($update_inside_images == TRUE)
                {
                    //echo "<meta http-equiv='refresh' content='1'>";
                }else{
                    echo "Greška " . mysqli_error($conn). "<br>";
                }

            }//end foreach petlje   */
            echo "Slike su uspješno postavljene!";
        }// end if provjera REQUEST_METHOD
    }//end insertAlbumImages()


    /*******************************
     * KONVERZIJA U WEBP (GD)
     * - prima tmp upload sliku
     * - pravi .webp na destinaciji
     * - vraća TRUE ako uspije, inače FALSE
     *
     * Podržavamo samo JPG/JPEG i PNG (ne i GIF)
     *******************************/
    public function convertToWebpAndSave($tmpPath, $destPath, $quality = 75)
    {
        // Ako server nema webp podršku u GD-u
        if (!function_exists('imagewebp')) {
            return false;
        }

        $info = @getimagesize($tmpPath);
        if ($info === false || empty($info['mime'])) {
            return false;
        }

        $mime = $info['mime'];

        switch ($mime) {

            case 'image/jpeg':
                $img = @imagecreatefromjpeg($tmpPath);
                break;

            case 'image/png':
                $img = @imagecreatefrompng($tmpPath);

                // PNG često ima transparentnost - WebP može, ali treba alfa
                if ($img) {
                    imagepalettetotruecolor($img);
                    imagealphablending($img, true);
                    imagesavealpha($img, true);
                }
                break;

            // GIF NAMJERNO NE KONVERTUJEMO
            default:
                return false;
        }

        if (!$img) return false;

        // Kreiraj folder ako ne postoji (za svaki slučaj)
        $dir = dirname($destPath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        $ok = @imagewebp($img, $destPath, (int)$quality);
        //imagedestroy($img);

        return $ok ? true : false;
    }
    
    //********************************* Pozvana metoda u ovom fajlu u metodi insertAboutAlbum()  *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

     //********************************* Metoda sa kojom mijenjamo PREDNJU sliku albuma *********************************//
    //********************************* Metoda sa kojom mijenjamo PREDNJU sliku albuma *********************************//
    public function unosPrednjeSlikeAlbuma($slikaAlbumaPrednja, $idAlbum) 
    { 
        $error=""; 
        //include "./functions/removeSymbols.func.php"; 
        global $conn; 
        
        $whitelist = array(".jpg",".jpeg",".gif",".png", ".svg", ".webp"); 
        //$whitelist= array("image/jpeg", "image/png", "image/gif", "image/svg", "image/webp", "image/apng", "image/avif"); 
        
        $maxVelicinaSlike= 2097152; //2mb 
        
        // Min veličine po formatu (jer webp i svg mogu biti dosta manji)
        $minVelicinaSlike= 10000;     //10kb (default za jpg/png/gif)
        $minVelicinaSlikeWebp= 2000;  //2kb  (webp može biti manji)
        $minVelicinaSlikeSvg= 500;    //0.5kb (svg zna biti baš mali)

        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        { 
            $size= $_FILES["dodajSlikuAlbumaPrednja"]["size"]; 
            $slikaAlbuma_tmp= $_FILES["dodajSlikuAlbumaPrednja"]["tmp_name"]; 
            $originalName = $_FILES["dodajSlikuAlbumaPrednja"]["name"];

            $putanja = "images/albumi/"; 

            /*$provjeraEkstenzije1= pathinfo($putanja.$slikaAlbuma); 
            $provjeraEkstenzije2= $provjeraEkstenzije1['extension'];*/ 

            $imeSlike= removeSimbolsImg($slikaAlbumaPrednja); 
            $ukloniEkstenziju= explode(".", $imeSlike); 

            // ✅ ekstenzija uvijek mala slova (da whitelist radi pouzdano)
            $ekstenzija= strtolower(end($ukloniEkstenziju)); 

            $vrijeme= "_im".date("dmY_His", time())."_".time()."."; 
            $slikaVrijeme= $ukloniEkstenziju[0].$vrijeme; 
            //$slikaVrijeme= array_shift($ukloniEkstenziju); 

            //if provjera ekstenzije 
            if (!(in_array(".".$ekstenzija, $whitelist))) 
            { 
                die('Nepravilan format slike, pokušajte sa drugom slikom'); 
            }

            // ===============================
            // PROVJERA: veličina slike (max + min po ekstenziji)
            // ===============================
            if($size > $maxVelicinaSlike) 
            {  
                // ✅ LOG FAIL (da piše i veličina slike)
                logUploadFail(
                    'album_front', 
                    $idAlbum, 
                    $originalName, 
                    "SIZE_TOO_BIG | size={$size} | max={$maxVelicinaSlike}"
                );

                echo "<script>
                        document.getElementById('promSlik').innerHTML='Prevelika slika (veća od 2mb)';
                    </script>"; 

                return; // ✅ bitno da ne nastavlja dalje
            }

            // ✅ MIN veličina zavisi od ekstenzije
            $min = $minVelicinaSlike; // default 10kb

            if ($ekstenzija === "webp") {
                $min = $minVelicinaSlikeWebp; // 2kb
            }
            if ($ekstenzija === "svg") {
                $min = $minVelicinaSlikeSvg; // 0.5kb
            }

            if($size < $min) 
            {  
                // ✅ LOG FAIL (da piše i veličina slike)
                logUploadFail(
                    'album_front', 
                    $idAlbum, 
                    $originalName, 
                    "SIZE_TOO_SMALL | size={$size} | min={$min}"
                );

                echo "<script>
                        document.getElementById('promSlik').innerHTML='Premala slika (manja od ".round($min/1024, 1)."kb)';
                    </script>"; 

                return; // ✅ bitno da ne nastavlja dalje
            }

            // ===============================
            // PROVJERA: MIME (stvarni format fajla po sadržaju)
            // - ne vjerujemo samo ekstenziji
            // - SVG je tekstualni fajl, njega ne provjeravamo preko getimagesize
            // ===============================
            $mime = "";

            if ($ekstenzija !== "svg") {

                $info = @getimagesize($slikaAlbuma_tmp);
                if ($info === false || empty($info['mime'])) {
                    logUploadFail('album_front', $idAlbum, $originalName, "NOT_VALID_IMAGE");
                    die("Fajl nije validna slika: " . htmlspecialchars($originalName));
                }

                $mime = $info['mime'];

                // Dozvoljeni MIME tipovi (u skladu sa whitelist ekstenzijama)
                $allowedMime = array(
                    'image/jpeg', // jpg/jpeg
                    'image/png',  // png
                    'image/gif',  // gif
                    'image/webp'  // webp
                );

                if (!in_array($mime, $allowedMime)) {
                    logUploadFail('album_front', $idAlbum, $originalName, "MIME_NOT_ALLOWED | mime={$mime}");
                    die("Nepodržan format slike (MIME): " . htmlspecialchars($mime));
                }

                // Dodatna zaštita: ako ekstenzija i MIME nisu usklađeni
                if (($ekstenzija === "jpg" || $ekstenzija === "jpeg") && $mime !== "image/jpeg") {
                    logUploadFail('album_front', $idAlbum, $originalName, "EXT_MIME_MISMATCH | ext={$ekstenzija} | mime={$mime}");
                    die("Ekstenzija i MIME se ne poklapaju (očekivan JPEG).");
                }
                if ($ekstenzija === "png" && $mime !== "image/png") {
                    logUploadFail('album_front', $idAlbum, $originalName, "EXT_MIME_MISMATCH | ext={$ekstenzija} | mime={$mime}");
                    die("Ekstenzija i MIME se ne poklapaju (očekivan PNG).");
                }
                if ($ekstenzija === "gif" && $mime !== "image/gif") {
                    logUploadFail('album_front', $idAlbum, $originalName, "EXT_MIME_MISMATCH | ext={$ekstenzija} | mime={$mime}");
                    die("Ekstenzija i MIME se ne poklapaju (očekivan GIF).");
                }
                if ($ekstenzija === "webp" && $mime !== "image/webp") {
                    logUploadFail('album_front', $idAlbum, $originalName, "EXT_MIME_MISMATCH | ext={$ekstenzija} | mime={$mime}");
                    die("Ekstenzija i MIME se ne poklapaju (očekivan WEBP).");
                }
            }

            // ===============================
            // UPLOAD + (po potrebi) KONVERZIJA U WEBP
            // Konvertujemo SAMO: JPG/JPEG i PNG
            // GIF i SVG idu regularan upload (bez konvertovanja)
            // WEBP već jeste WEBP (samo upload)
            // ===============================
            $finalFileName = "";   // ono što ide u bazu
            $destPath = "";        // putanja gdje se snima

            // ✅ 1) Ako je SVG ili GIF -> normalan upload (bez konverzije)
            if ($ekstenzija === "svg" || $ekstenzija === "gif") {

                $provjeraSlike= $putanja.$slikaVrijeme.$ekstenzija; 
                if(!file_exists(($provjeraSlike))) 
                { 
                    $slikaVrijeme= $slikaVrijeme.$ekstenzija; 
                    $provjeraSlike= $putanja.$slikaVrijeme;
                }

                $finalFileName = $slikaVrijeme;
                $destPath = $provjeraSlike;

                $prebacenaPrednja = move_uploaded_file($slikaAlbuma_tmp, $destPath);

            }
            // ✅ 2) Ako je WEBP -> normalan upload
            else if ($ekstenzija === "webp") {

                $provjeraSlike= $putanja.$slikaVrijeme.$ekstenzija; 
                if(!file_exists(($provjeraSlike))) 
                { 
                    $slikaVrijeme= $slikaVrijeme.$ekstenzija; 
                    $provjeraSlike= $putanja.$slikaVrijeme;
                }

                $finalFileName = $slikaVrijeme;
                $destPath = $provjeraSlike;

                $prebacenaPrednja = move_uploaded_file($slikaAlbuma_tmp, $destPath);

            }
            // ✅ 3) JPG/JPEG/PNG -> WEBP
            else {

                // Uvijek želimo da rezultat u bazi bude .webp
                $finalFileName = $slikaVrijeme . "webp";
                $destPath = $putanja . $finalFileName;

                // Poziv postojeće metode (ne kreiramo ponovo)
                $ok = $this->convertToWebpAndSave($slikaAlbuma_tmp, $destPath, 75);

                // Ako konverzija ne uspije -> fallback na normalan upload originala
                if ($ok !== true) {

                    // fallback: snimi original
                    $provjeraSlike= $putanja.$slikaVrijeme.$ekstenzija; 
                    if(!file_exists(($provjeraSlike))) 
                    { 
                        $slikaVrijeme= $slikaVrijeme.$ekstenzija; 
                        $provjeraSlike= $putanja.$slikaVrijeme;
                    }

                    $finalFileName = $slikaVrijeme;
                    $destPath = $provjeraSlike;

                    $prebacenaPrednja = move_uploaded_file($slikaAlbuma_tmp, $destPath);

                } else {
                    // Konverzija uspješna -> tretiramo kao uspješan upload
                    $prebacenaPrednja = true;
                }
            }

            // ===============================
            // UPDATE baze (snimi finalno ime fajla)
            // ===============================
            $q="UPDATE albumi SET slikaAlbuma='{$finalFileName}' WHERE idAlbum='{$idAlbum}'"; 
            $promjeniSliku= mysqli_query($conn, $q); 

            if($prebacenaPrednja == TRUE) 
            { 
                //echo "<meta http-equiv='refresh' content='0'>"; 
                logUploadSuccess('album_front', $idAlbum, $originalName, $size); 
            }
            else if($prebacenaPrednja == FALSE)
            { 
                logUploadFail('album_front', $idAlbum, $originalName, $error); 
                echo "Greška " . mysqli_error($conn). "<br>"; 
            } 

        }// end if provjera REQUEST_METHOD 
    }//end function unosPrednjeSlikeAlbuma()

     //********************************* Pozvana metoda u ovom fajlu u metodi insertAboutAlbum() *********************************//

     //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda sa kojom mijenjamo ZADNJU sliku albuma *********************************//
    public function unosZadnjeSlikeAlbuma($slikaAlbumaZadnja, $idAlbum)
    {
        $error=""; 
        //include "./functions/removeSymbols.func.php"; 
        global $conn; 
        
        $whitelist = array(".jpg",".jpeg",".gif",".png", ".svg", ".webp"); 
        //$whitelist= array("image/jpeg", "image/png", "image/gif", "image/svg", "image/webp", "image/apng", "image/avif"); 
        
        $maxVelicinaSlike= 2097152; //2mb 
        
        // Min veličine po formatu (jer webp i svg mogu biti dosta manji)
        $minVelicinaSlike= 10000;     //10kb (default za jpg/png/gif)
        $minVelicinaSlikeWebp= 2000;  //2kb  (webp može biti manji)
        $minVelicinaSlikeSvg= 500;    //0.5kb (svg zna biti baš mali)

        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        { 
            $size= $_FILES["dodajSlikuAlbumaZadnja"]["size"]; 
            $slikaAlbuma_tmp= $_FILES["dodajSlikuAlbumaZadnja"]["tmp_name"]; 
            $originalName = $_FILES["dodajSlikuAlbumaZadnja"]["name"];

            $putanja = "images/albumi/back/"; 
            $skeniraj= scandir($putanja);

            /*$provjeraEkstenzije1= pathinfo($putanja.$slikaAlbuma); 
            $provjeraEkstenzije2= $provjeraEkstenzije1['extension'];*/ 

            $imeSlike= removeSimbolsImg($slikaAlbumaZadnja); 
            $ukloniEkstenziju= explode(".", $imeSlike); 

            // ✅ ekstenzija uvijek mala slova (da whitelist radi pouzdano)
            $ekstenzija= strtolower(end($ukloniEkstenziju)); 

            $vrijeme= "_im".date("dmY_His", time())."_".time()."."; 
            $slikaVrijeme= $ukloniEkstenziju[0].$vrijeme; 
            //$slikaVrijeme= array_shift($ukloniEkstenziju); 

            //if provjera ekstenzije 
            if (!(in_array(".".$ekstenzija, $whitelist))) 
            { 
                die('Nepravilan format slike, pokušajte sa drugom slikom'); 
            }

            // ===============================
            // PROVJERA: veličina slike (max + min po ekstenziji)
            // ===============================
            if($size > $maxVelicinaSlike) 
            {  
                // ✅ LOG FAIL (da piše i veličina slike)
                logUploadFail(
                    'album_back', 
                    $idAlbum, 
                    $originalName, 
                    "SIZE_TOO_BIG | size={$size} | max={$maxVelicinaSlike}"
                );

                echo "<script>
                        document.getElementById('promSlik').innerHTML='Prevelika slika (veća od 2mb)';
                    </script>"; 

                return; // ✅ bitno da ne nastavlja dalje
            }

            // ✅ MIN veličina zavisi od ekstenzije
            $min = $minVelicinaSlike; // default 10kb

            if ($ekstenzija === "webp") {
                $min = $minVelicinaSlikeWebp; // 2kb
            }
            if ($ekstenzija === "svg") {
                $min = $minVelicinaSlikeSvg; // 0.5kb
            }

            if($size < $min) 
            {  
                // ✅ LOG FAIL (da piše i veličina slike)
                logUploadFail(
                    'album_back', 
                    $idAlbum, 
                    $originalName, 
                    "SIZE_TOO_SMALL | size={$size} | min={$min}"
                );

                echo "<script>
                        document.getElementById('promSlik').innerHTML='Premala slika (manja od ".round($min/1024, 1)."kb)';
                    </script>"; 

                return; // ✅ bitno da ne nastavlja dalje
            }

            // ===============================
            // PROVJERA: MIME (stvarni format fajla po sadržaju)
            // - ne vjerujemo samo ekstenziji
            // - SVG je tekstualni fajl, njega ne provjeravamo preko getimagesize
            // ===============================
            $mime = "";

            if ($ekstenzija !== "svg") {

                $info = @getimagesize($slikaAlbuma_tmp);
                if ($info === false || empty($info['mime'])) {
                    logUploadFail('album_back', $idAlbum, $originalName, "NOT_VALID_IMAGE");
                    die("Fajl nije validna slika: " . htmlspecialchars($originalName));
                }

                $mime = $info['mime'];

                // Dozvoljeni MIME tipovi (u skladu sa whitelist ekstenzijama)
                $allowedMime = array(
                    'image/jpeg', // jpg/jpeg
                    'image/png',  // png
                    'image/gif',  // gif
                    'image/webp'  // webp
                );

                if (!in_array($mime, $allowedMime)) {
                    logUploadFail('album_back', $idAlbum, $originalName, "MIME_NOT_ALLOWED | mime={$mime}");
                    die("Nepodržan format slike (MIME): " . htmlspecialchars($mime));
                }

                // Dodatna zaštita: ako ekstenzija i MIME nisu usklađeni
                if (($ekstenzija === "jpg" || $ekstenzija === "jpeg") && $mime !== "image/jpeg") {
                    logUploadFail('album_back', $idAlbum, $originalName, "EXT_MIME_MISMATCH | ext={$ekstenzija} | mime={$mime}");
                    die("Ekstenzija i MIME se ne poklapaju (očekivan JPEG).");
                }
                if ($ekstenzija === "png" && $mime !== "image/png") {
                    logUploadFail('album_back', $idAlbum, $originalName, "EXT_MIME_MISMATCH | ext={$ekstenzija} | mime={$mime}");
                    die("Ekstenzija i MIME se ne poklapaju (očekivan PNG).");
                }
                if ($ekstenzija === "gif" && $mime !== "image/gif") {
                    logUploadFail('album_back', $idAlbum, $originalName, "EXT_MIME_MISMATCH | ext={$ekstenzija} | mime={$mime}");
                    die("Ekstenzija i MIME se ne poklapaju (očekivan GIF).");
                }
                if ($ekstenzija === "webp" && $mime !== "image/webp") {
                    logUploadFail('album_back', $idAlbum, $originalName, "EXT_MIME_MISMATCH | ext={$ekstenzija} | mime={$mime}");
                    die("Ekstenzija i MIME se ne poklapaju (očekivan WEBP).");
                }
            }

            // ===============================
            // UPLOAD + (po potrebi) KONVERZIJA U WEBP
            // Konvertujemo SAMO: JPG/JPEG i PNG
            // GIF i SVG idu regularan upload (bez konvertovanja)
            // WEBP već jeste WEBP (samo upload)
            // ===============================
            $finalFileName = "";   // ono što ide u bazu
            $destPath = "";        // putanja gdje se snima

            // ✅ 1) Ako je SVG ili GIF -> normalan upload (bez konverzije)
            if ($ekstenzija === "svg" || $ekstenzija === "gif") {

                $provjeraSlike= $putanja.$slikaVrijeme.$ekstenzija; 
                if(!file_exists(($provjeraSlike))) 
                { 
                    $slikaVrijeme= $slikaVrijeme.$ekstenzija; 
                    $provjeraSlike= $putanja.$slikaVrijeme;
                }

                $finalFileName = $slikaVrijeme;
                $destPath = $provjeraSlike;

                $prebacenaZadnjay = move_uploaded_file($slikaAlbuma_tmp, $destPath);

            }
            // ✅ 2) Ako je WEBP -> normalan upload
            else if ($ekstenzija === "webp") {

                $provjeraSlike= $putanja.$slikaVrijeme.$ekstenzija; 
                if(!file_exists(($provjeraSlike))) 
                { 
                    $slikaVrijeme= $slikaVrijeme.$ekstenzija; 
                    $provjeraSlike= $putanja.$slikaVrijeme;
                }

                $finalFileName = $slikaVrijeme;
                $destPath = $provjeraSlike;

                $prebacenaZadnjay = move_uploaded_file($slikaAlbuma_tmp, $destPath);

            }
            // ✅ 3) JPG/JPEG/PNG -> WEBP
            else {

                // Uvijek želimo da rezultat u bazi bude .webp
                $finalFileName = $slikaVrijeme . "webp";
                $destPath = $putanja . $finalFileName;

                // Poziv postojeće metode (ne kreiramo ponovo)
                $ok = $this->convertToWebpAndSave($slikaAlbuma_tmp, $destPath, 75);

                // Ako konverzija ne uspije -> fallback na normalan upload originala
                if ($ok !== true) {

                    // fallback: snimi original
                    $provjeraSlike= $putanja.$slikaVrijeme.$ekstenzija; 
                    if(!file_exists(($provjeraSlike))) 
                    { 
                        $slikaVrijeme= $slikaVrijeme.$ekstenzija; 
                        $provjeraSlike= $putanja.$slikaVrijeme;
                    }

                    $finalFileName = $slikaVrijeme;
                    $destPath = $provjeraSlike;

                    $prebacenaZadnjay = move_uploaded_file($slikaAlbuma_tmp, $destPath);

                } else {
                    // Konverzija uspješna -> tretiramo kao uspješan upload
                    $prebacenaZadnjay = true;
                }
            }

            // ===============================
            // UPDATE baze (snimi finalno ime fajla)
            // ===============================
            $q="UPDATE albumi SET slikaAlbumaZadnja='{$finalFileName}' WHERE idAlbum='{$idAlbum}'"; 
            $promjeniSliku= mysqli_query($conn, $q); 

            if($prebacenaZadnjay == TRUE) 
            { 
                //echo "<meta http-equiv='refresh' content='0'>"; 
                logUploadSuccess('album_back', $idAlbum, $originalName, $size); 
            }
            else if($prebacenaZadnjay == FALSE)
            { 
                logUploadFail('album_back', $idAlbum, $originalName, $error); 
                echo "Greška " . mysqli_error($conn). "<br>"; 
            } 

        }// end if provjera REQUEST_METHOD 
    }//end function unosZadnjeSlikeAlbuma()

    //********************************* Pozvana metoda u ovom fajlu u metodi insertAboutAlbum() *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

   

}//end class insertAlbum