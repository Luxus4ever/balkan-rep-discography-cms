<?php

class adminWorkImages{

    //METODE SADRŽANE U OVOJ KLASI
    //multipleImageUpload (vrši upload više slika albuma odjednom)
    //obrisiPoJednuSliku (briše po jednu sliku od ostalih slika albuma)
    //promjenaPrednjeSlikeAlbuma (mijenja prednu sliku albuma)
    //obrisiPrednjuSlikuAlbuma (briše prednju sliku albuma)
    //adminPromjenaZadnjeSlikeAlbuma (mijenja zadnju sliku albuma)
    //obrisiZadnjuSlikuAlbuma (brise zadnju sliku albuma)
    //obrisiLogoIzdavaca (briše logo izdavača)
    //obrisiSlikuIzvodjaca (briše sliku izvođača)
    //insertAlbumImagesAdmin (ubacuje više slika u bazu odjednom KONVERTOVANO u webp format)
    //convertToWebpAndSave (konvertuje slike u webp format)

    protected $whitelist;
    protected $maxVelicinaSlike;
    protected $minVelicinaSlike;
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
    protected $nazivAlbuma;
    //public 

    protected $unutrasnjeSlikeAlbuma;
    protected $minVelicinaSlikeWebp;
    protected $minVelicinaSlikeSvg;


    //********************************* Metoda koja vrši upload ostalih slika albuma (više slika)  *********************************//
    public function multipleImageUpload($nazivAlbuma, $idIzv, $idAlb)
    {
        global $conn;
        
        $this->whitelist = array(".jpg",".jpeg",".gif",".png", ".svg", ".webp"); 
        $this->maxVelicinaSlike= 2097152; //2mb
        $this->minVelicinaSlike= 10000; //10kb 
    
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            $this->targetDir = "../images/albumi/inside/"; // Direktorij za pohranu slika
    
            $this->originalnaImenaFajlova = $_FILES['images']['name'];

            foreach ($this->originalnaImenaFajlova as $key => $originalnoImeFajla) 
            {
                $this->fileName = basename(removeSimbolsImg($originalnoImeFajla));
                $this->size = $_FILES["images"]["size"][$key]; // Veličina slike u bajtima

                if ($this->size > $this->maxVelicinaSlike) {
                    die("Slika je prevelika. Maksimalna veličina je 2MB.");
                } elseif ($this->size < $this->minVelicinaSlike) {
                    die("Slika je prazna ili premala. Minimalna veličina je 10KB.");
                }else{
                    $this->imeSlike= $this->fileName;
                    $this->ukloniEkstenziju= explode(".", $this->imeSlike);
                    $this->ekstenzija= end($this->ukloniEkstenziju);
                    $this->vrijeme= "_im".date("dmY_His", time())."_".time().".";
                    //$this->slikaVrijeme= $this->ukloniEkstenziju[0].$this->ukloniEkstenziju[1].$this->vrijeme;
                    $this->slikaVrijeme= ($this->ukloniEkstenziju[1]=="jpg" || $this->ukloniEkstenziju[1]=="jpeg" || $this->ukloniEkstenziju[1]=="gif" || $this->ukloniEkstenziju[1]=="png" || $this->ukloniEkstenziju[1]=="svg" || $this->ukloniEkstenziju[1]=="webp") ? $this->ukloniEkstenziju[0].$this->vrijeme: $this->ukloniEkstenziju[0].$this->ukloniEkstenziju[1].$this->vrijeme;
                    $newFileName= $this->slikaVrijeme.$this->ekstenzija;

                    //if provjera ekstenzije
                    if (!(in_array(".".$this->ekstenzija, $this->whitelist))) 
                    {
                        die('Nepravilan format slike ili je slika prevelika, pokušajte sa drugom slikom');
                    }else
                        {
                        move_uploaded_file($_FILES['images']['tmp_name'][$key], $this->targetDir . $newFileName);

                        if(isset($_POST["promjeniSlikeAlbumaOstale"]))
                        {
                            $q2= "INSERT INTO slike_albuma_ostale (albumId, unutrasnjeSlikeAlbuma) VALUES ('{$idAlb}', '{$newFileName}')";
                            //print_r($q2);
                            $update_inside_images= mysqli_query($conn, $q2);
                            if($update_inside_images == TRUE)
                            {
                                echo "<meta http-equiv='refresh' content='0'>";
                            }else{
                                echo "Greška " . mysqli_error($conn). "<br>";
                            }
                        }//end INSERT IN DB
                    }// end if else provjera ekstenzije
                }// end if else provjera veličine slike
            }//end foreach petlje   
            echo "Slike su uspješno postavljene!";
        }// end if provjera REQUEST_METHOD
    }//end multipleImageUpload()
    
    //********************************* Pozvana metoda u fajlu adminFunkcije.func.php u metodi updateAboutAlbum  *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* funkcija koja briše po jednu sliku (od ostalih) prilikom uređivanja albuma (admin)  *********************************//

    public function obrisiPoJednuSliku($idAlb){
    global $conn;
    ?>
    <!--<p>Ostale (unutrašnje) slike albuma</p>
    <label for="files" class="text-warning"><strong>Izaberi ostale slike za upload <br> (moguće više fajlova odjednom)</strong></label>-->
    <br>
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
    <form action="" method="post" enctype="multipart/form-data">
        <?php
        $q_ostSlk = "SELECT * FROM slike_albuma_ostale JOIN albumi ON albumi.idAlbum=slike_albuma_ostale.albumId WHERE albumId='{$idAlb}'";
        $select_ostaleSlike = mysqli_query($conn, $q_ostSlk);
        $br=0;
        while ($row = mysqli_fetch_array($select_ostaleSlike)) 
        {
            $idUnutrasnjeSlikeAlbuma = $row["idUnutrasnjeSlikeAlbuma"];
            $albumId = $row["albumId"];
            $this->unutrasnjeSlikeAlbuma = $row["unutrasnjeSlikeAlbuma"];
            $this->nazivAlbuma = $row["nazivAlbuma"];
            ?>

            <img src="../images/albumi/inside/<?php echo $this->unutrasnjeSlikeAlbuma; ?>" alt="<?php echo $this->nazivAlbuma; ?>" title="<?php echo $this->nazivAlbuma; ?> (inside <?php echo ++$br; ?>)" class=""/>
            <input type="hidden" name="idUnutrasnjeSlikeAlbuma" value="<?php echo $idUnutrasnjeSlikeAlbuma; ?>"><br>
            <button type="submit" class="btn btn-danger" name="obrisiPoJednu" value="<?php echo $idUnutrasnjeSlikeAlbuma; ?>">Obriši</button><br><br>
            <?php
        }//end while

        if (isset($_POST["obrisiPoJednu"])) 
        {
            $idUnutrasnjeSlikeAlbuma = $_POST["obrisiPoJednu"];
            $q_ostSlk = "SELECT * FROM slike_albuma_ostale WHERE idUnutrasnjeSlikeAlbuma='{$idUnutrasnjeSlikeAlbuma}'";
            $select_ostaleSlike = mysqli_query($conn, $q_ostSlk);
            while ($row = mysqli_fetch_array($select_ostaleSlike)) 
            {
                $unutrasnjeSlikeAlbumaTemp = $row["unutrasnjeSlikeAlbuma"];
                $imagePath = '../images/albumi/inside/' . $unutrasnjeSlikeAlbumaTemp; // Promijenite putanju i naziv slike prema vašim potrebama
                if (file_exists($imagePath)) 
                {
                    if(unlink($imagePath)){
                        echo "Slika uspješno obrisana.";

                        echo "<meta http-equiv='refresh' content='0'>";

                    }
                } else {
                    echo "Slika nije pronađena.";
                }//end if else(file_exists)
            }//end while
            $delete_query = "DELETE FROM slike_albuma_ostale WHERE idUnutrasnjeSlikeAlbuma='{$idUnutrasnjeSlikeAlbuma}'";
            mysqli_query($conn, $delete_query);
        }//end if()
        ?>
    </form>
    <?php
    }//end obrisiPoJednuSliku()

    //********************************* Pozvana metoda fajlu adminFunkcije.func.php u metodi updateAboutAlbum i updateAboutAlbumLabel  *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda sa kojom mijenjamo PREDNJU sliku albuma *********************************//
    public function promjenaPrednjeSlikeAlbuma($slikaAlbumaPrednja, $idAlbum)
    {
        global $conn; 

        $whitelist = array(".jpg",".jpeg",".gif",".png", ".svg", ".webp"); 
        //$whitelist= array("image/jpeg", "image/png", "image/gif", "image/svg", "image/webp", "image/apng", "image/avif");

        $maxVelicinaSlike= 2097152; //2mb
        $minVelicinaSlike= 10000; //10kb 
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $size= $_FILES["promjeniSlikuAlbumaPrednja"]["size"];
        //print_r($size) . "<hr>";
        if(($size > $maxVelicinaSlike) || ($size< $minVelicinaSlike))
        {  
            echo "<script>
                        document.getElementById('promSlik').innerHTML='Prevelika slika (veća od 2mb) ili je premala slika (manja od 10kb)';
            </script>"; 
        }else
            {
                $putanja = "../images/albumi/";
                $skeniraj= scandir($putanja);
                //print_r($skeniraj);

                /*$provjeraEkstenzije1= pathinfo($putanja.$slikaAlbuma);
                $provjeraEkstenzije2= $provjeraEkstenzije1['extension'];*/

                $imeSlike= removeSimbolsImg($slikaAlbumaPrednja);
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
                            //$slikaAlbuma= removeSimbolsImg(str_replace($slikaAlbuma, "$ukloniEkstenziju[0]$vrijeme.$ekstenzija", str_replace(" ", "_", $_FILES["promjenaProfilneSlike"]["name"])));
                            $slikaAlbuma_tmp= $_FILES["promjeniSlikuAlbumaPrednja"]["tmp_name"];
                            move_uploaded_file($slikaAlbuma_tmp, $provjeraSlike);
                        }//end else !file_exists(($provjeraSlike)

                            $putanja = "../images/albumi/";
                            $q="UPDATE albumi SET slikaAlbuma='{$slikaVrijeme}' WHERE idAlbum='{$idAlbum}'";
                            $promjeniSliku= mysqli_query($conn, $q);
                            move_uploaded_file($slikaAlbuma_tmp, $provjeraSlike);

                            if($promjeniSliku == TRUE)
                            {
                                echo "<meta http-equiv='refresh' content='0'>";
                            }else{
                                echo "Greška " . mysqli_error($conn). "<br>";

                            }       
                    }//end while loop provjera korisničkog imena i šifre 
            }//end provjera ekstenzije
            }// end if provjera REQUEST_METHOD
    }//end promjenaPrednjeSlikeAlbuma()

    //********************************* Pozvana metoda  fajlu adminFunkcije.func.php u metodi updateAboutAlbum i updateAboutAlbumLabel *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda sa kojom brišemo prednju sliku albuma *********************************//
    public function obrisiPrednjuSlikuAlbuma($idAlbum)
    {
        global $conn; 
    
        $q_slikaAlbuma = "SELECT * FROM albumi WHERE idAlbum='{$idAlbum}'";
        $select_slikaAlbuma = mysqli_query($conn, $q_slikaAlbuma);
        while ($row = mysqli_fetch_array($select_slikaAlbuma)) 
        {
            $slikaAlbumaTemp = $row["slikaAlbuma"];
            $putanjaDoSlike = '../images/albumi/' . $slikaAlbumaTemp; // Promijenite putanju i naziv slike prema vašim potrebama
            if (file_exists($putanjaDoSlike)) 
            {
                if(unlink($putanjaDoSlike)){
                    echo "Slika uspješno obrisana.";
                    echo "<meta http-equiv='refresh' content='0'>";
                }
            }//end if() 
        }//end while
        $delete_query="UPDATE albumi SET slikaAlbuma='' WHERE idAlbum='{$idAlbum}'";
        mysqli_query($conn, $delete_query);
        //echo "<meta http-equiv='refresh' content='0'; url='profileedit.php?{$profil}'>";
    }//end obrisiPrednjuSlikuAlbuma(
    //********************************* Pozvana metoda  fajlu adminFunkcije.func.php u metodi updateAboutAlbum i updateAboutAlbumLabel *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda sa kojom mijenjamo ZADNJU sliku albuma *********************************//
    public function adminPromjenaZadnjeSlikeAlbuma($slikaAlbumaZadnja, $idAlbum)
    {
        global $conn; 

        $whitelist = array(".jpg",".jpeg",".gif",".png", ".svg", ".webp"); 
        //$whitelist= array("image/jpeg", "image/png", "image/gif", "image/svg", "image/webp", "image/apng", "image/avif");

        $maxVelicinaSlike= 2097152; //2mb
        $minVelicinaSlike= 10000; //10kb 
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            $size= $_FILES["promjenaSlikeAlbumaZadnja"]["size"];
            //print_r($size) . "<hr>";
            if(($size > $maxVelicinaSlike) || ($size< $minVelicinaSlike))
            {  
                echo "<script>
                            document.getElementById('promSlik').innerHTML='Prevelika slika (veća od 2mb) ili je premala slika (manja od 10kb)';
                </script>"; 
            }else
            {
                $putanja = "../images/albumi/back/";
                $skeniraj= scandir($putanja);
                //print_r($skeniraj);

                /*$provjeraEkstenzije1= pathinfo($putanja.$slikaAlbuma);
                $provjeraEkstenzije2= $provjeraEkstenzije1['extension'];*/

                $imeSlike= removeSimbolsImg($slikaAlbumaZadnja);
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
                            $slikaAlbuma_tmp= $_FILES["promjenaSlikeAlbumaZadnja"]["tmp_name"];
                            move_uploaded_file($slikaAlbuma_tmp, $provjeraSlike);
                        }//end if(!file_exists(($provjeraSlike))

                            $putanja = "../images/albumi/back/";
                            $q="UPDATE albumi SET slikaAlbumaZadnja='{$slikaVrijeme}' WHERE idAlbum='{$idAlbum}'";
                            $promjeniSliku= mysqli_query($conn, $q);
                            move_uploaded_file($slikaAlbuma_tmp, $provjeraSlike);

                            if($promjeniSliku == TRUE)
                            {
                                echo "<meta http-equiv='refresh' content='0'>";
                            }else{
                                echo "Greška " . mysqli_error($conn). "<br>";

                            }       
                    }//end if else provjera ekstenzije 
            }//end if else dodavanje datuma i vremena
        }// end if provjera REQUEST_METHOD
    }//end adminPromjenaZadnjeSlikeAlbuma()

    //********************************* Pozvana metoda fajlu adminFunkcije.func.php u metodi updateAboutAlbum, updateAboutAlbumLabel *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda koja briše zadnju sliku albuma *********************************//

    public function obrisiZadnjuSlikuAlbuma($idAlbum)
    {
        global $conn; 
    
        $q_slikaAlbumaZadnja = "SELECT * FROM albumi WHERE idAlbum='{$idAlbum}'";
        $select_zadnjaSlikaAlbuma = mysqli_query($conn, $q_slikaAlbumaZadnja);
        while ($row = mysqli_fetch_array($select_zadnjaSlikaAlbuma)) 
        {
            $zadnjaSlikaAlbumaTemp = $row["slikaAlbumaZadnja"];
            $putanjaDoSlike = '../images/albumi/back/' . $zadnjaSlikaAlbumaTemp;
            if (file_exists($putanjaDoSlike)) 
            {
                if(unlink($putanjaDoSlike)){
                    echo "Slika uspješno obrisana.";

                    echo "<meta http-equiv='refresh' content='0'>";

                }
            }//end if(file_exists($putanjaDoSlike)) 
        }//end while
        $delete_query="UPDATE albumi SET slikaAlbumaZadnja='' WHERE idAlbum='{$idAlbum}'";
        mysqli_query($conn, $delete_query);
        //echo "<meta http-equiv='refresh' content='0'; url='profileedit.php?{$profil}'>";
    
    }//end obrisiZadnjuSlikuAlbuma()

    //********************************* Pozvana metoda fajlu adminFunkcije.func.php u metodi updateAboutAlbum, updateAboutAlbumLabel *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda sa kojom brišemo logo izdavača *********************************//
    public function obrisiLogoIzdavaca($idLab){
        global $conn; 
    
        $q_slikaIzdavac = "SELECT * FROM izdavaci WHERE idIzdavaci='{$idLab}'";
        $select_slikaIzdavac = mysqli_query($conn, $q_slikaIzdavac);
        while ($row = mysqli_fetch_array($select_slikaIzdavac)) 
        {
            $slikaIzdavacTemp = $row["izdavaciLogo"];
            $putanjaDoSlike = '../images/labels/' . $slikaIzdavacTemp; 
            if (file_exists($putanjaDoSlike)) 
            {
                if(unlink($putanjaDoSlike)){
                    echo "Slika uspješno obrisana.";
                    echo "<meta http-equiv='refresh' content='0'>";
                }
            }//end if(file_exists($putanjaDoSlike)) 
        }//end while
        $delete_query="UPDATE izdavaci SET izdavaciLogo='' WHERE idIzdavaci='{$idLab}'";
        mysqli_query($conn, $delete_query);
        //echo "<meta http-equiv='refresh' content='0'; url='profileedit.php?{$profil}'>";
    }//end obrisiLogoIzdavaca()
    /********************************* Pozvana metoda  fajlu adminEditPanel.class.php u metodi adminUpdateLabel *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda sa kojom brišemo sliku izvođača *********************************//
    public function obrisiSlikuIzvodjaca($idIzvodjaci){
        global $conn; 
    
        $q_slikaIzvodjac = "SELECT * FROM izvodjaci WHERE idIzvodjaci='{$idIzvodjaci}'";
        $select_slikaIzvodjac = mysqli_query($conn, $q_slikaIzvodjac);
        while ($row = mysqli_fetch_array($select_slikaIzvodjac)) 
        {
            $slikaIzvodjacTemp = $row["slikaIzvodjac"];
            $putanjaDoSlike = '../images/izvodjaci/' . $slikaIzvodjacTemp; 
            if (file_exists($putanjaDoSlike)) 
            {
                if(unlink($putanjaDoSlike)){
                    echo "Slika uspješno obrisana.";
                    echo "<meta http-equiv='refresh' content='0'>";
                }
            }//end if(file_exists($putanjaDoSlike)) 
        }//end while
        $delete_query="UPDATE izvodjaci SET slikaIzvodjac='' WHERE idIzvodjaci='{$idIzvodjaci}'";
        mysqli_query($conn, $delete_query);
        //echo "<meta http-equiv='refresh' content='0'; url='profileedit.php?{$profil}'>";
    }//end obrisiLogoIzdavaca()
    /********************************* Pozvana metoda u fajlu middlePanel.func.php u metodi adminUpdateLabel *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------


    //********************************* Metoda koja ubacuje više slika u bazu odjednom  *********************************//
    public function insertAlbumImagesAdmin($idAlb)
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
            $this->targetDir = "../images/albumi/inside/"; // Direktorij za pohranu slika

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
    /********************************* Pozvana metoda u fajlu adminFunkcije.func.php *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------


    //********************************* Metoda koja konvertuje slike u webp format  *********************************//

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
    }//end convertToWebpAndSave()
    
    //********************************* Pozvana metoda u ovom fajlu u metodi insertAlbumImagesAdmin()  *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------



} //end class
