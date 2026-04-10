<?php

class pjesme{

    //METODE SADRŽANE U OVOJ KLASI
    //listaPjesama (ispis pjesama sa albuma)
    //ostaleNapomene (ispis ostalih napomena vezano za album)
    //tabelaPjesme (metoda koja iscrtava tabelu)
    //fit (metoda koja uklanja feat. na pjesmi)
    //fit2 (metoda koja uklanja feat. na mixtapeIzvodjac i još negdje ako treba)
    //mixtape (prikaz mixtape izvođača)
    //tekstPjesme (metoda za prikaz teksta pjesme)

    public $izvodjacId;
    public $izvodjacMaster;
    public $clanoviOveGrupe;
    public $idPjesme;
    public $redniBroj;
    public $album;
    public $nazivPjesme;
    public $saradnici;
    public $tekstPjesme;
    public $trajanjePjesme;
    public $mixtapeIzvodjac;
    public $mixtapeFeat;
    public $feat;
    public $ostaleNapomene;
    public $ostaleNapomeneAlbum;
    public $youtubeJednaPjesma;

    public $albumId;
    public $nazivAlbuma;
    public $idIzvodjacAlbumi;
    public $godinaIzdanja;

    public $cleanIzvodjacMaster;
    public $cleanIzvodjac2;
    public $cleanIzvodjac3;
    public $cleanNazivAlbuma;

    public $idGrupe;
    public $nazivGrupe;

    public $username;


    //*********************************Metoda koja vrši ispis pjesama od albuma *********************************/
    public function listaPjesama($albumId) 
    {
        global $conn;
        $q= "SELECT * FROM pjesme 
        JOIN albumi ON albumi.idAlbum=pjesme.albumId
        JOIN izvodjaci ON izvodjaci.idIzvodjaci=albumi.idIzvodjacAlbumi
        WHERE albumi.idAlbum='{$albumId}'";
	
        $select_songs= mysqli_query($conn, $q);
        if(mysqli_num_rows($select_songs)>0)
        {
            while($row= mysqli_fetch_assoc($select_songs))
            {
                $this->idPjesme= $row["idPjesme"];
                $this->redniBroj= $row["redniBroj"];
                $this->nazivPjesme= $row["nazivPjesme"];
                $this->saradnici= nl2br((string)$row["saradnici"]);
                $this->tekstPjesme= $row["tekstPjesme"];
                $this->trajanjePjesme= $row["trajanjePjesme"];
                $this->album= $row["albumId"];
                $this->feat= $row["feat"];
                $this->izvodjacId= $row["izvodjacId"];
                $this->izvodjacMaster= $row["izvodjacMaster"];
                $this->mixtapeIzvodjac= $row["mixtapeIzvodjac"];
                $this->mixtapeFeat= $row["mixtapeFeat"];
                $this->feat= $row["feat"];
                //$this->ostaleNapomene= nl2br((string)$row["ostaleNapomene"]);

                $this->tabelaPjesme($this->idPjesme, $this->redniBroj, $this->nazivPjesme, $this->saradnici, $this->tekstPjesme, $this->trajanjePjesme, $this->feat, $this->izvodjacId, $this->mixtapeIzvodjac, $this->izvodjacMaster);                
            }//end while
        }else
            {
                echo "<h2 class='sredina'>Trenutno nema unijetih podataka.</h2>";
            }//end if else
    }// end function listaPjesama()
    //********************************* Pozvana metoda u oalbumu.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //*********************************Metoda koja vrši ispis ostalih napomena za album *********************************/
    public function ostaleNapomene($albumId) 
    {
        global $conn;

        $q= "SELECT pjesme.nazivPjesme, pjesme.ostaleNapomene, albumi.ostaleNapomeneAlbum
            FROM pjesme
            JOIN albumi ON pjesme.albumId=albumi.idAlbum
            WHERE pjesme.albumId='{$albumId}'";

        $select_napomene= mysqli_query($conn, $q);

        $napomene = array();
        $napomenaAlbuma = "";

        if(mysqli_num_rows($select_napomene)>0)
        {
            while($row= mysqli_fetch_assoc($select_napomene))
            {
                // album napomena je ista u svim redovima, uzmi je jednom
                if($napomenaAlbuma == ""){
                    $napomenaAlbuma = $row["ostaleNapomeneAlbum"] ?? "";
                }

                // napomena pjesme - dodaj samo ako nije prazno
                $tmp = trim($row["ostaleNapomene"] ?? "");
                if($tmp != ""){
                    // ako želiš i naziv pjesme uz napomenu:
                    // $napomene[] = "<b>".$row["nazivPjesme"].":</b> ".$tmp;

                    // ako želiš samo tekst napomene:
                    $napomene[] = $tmp;
                }
            }//end while
        }

        // 1) ispis napomena pjesama (jedna ispod druge)
        if(!empty($napomene)){
            echo "<p class='sredinaNapomena'>".nl2br(implode("\n", is_array($napomene) ? $napomene : []))."</p>";
        }

        // 2) ispis napomene albuma (posle)
        if(trim($napomenaAlbuma) != ""){
            echo "<p class='sredinaNapomena'>".nl2br((string)$napomenaAlbuma)."</p>";
        }

        // ---- dodao album (tvoj kod) ----
        $q_dodaoAlbum="SELECT korisnici.username FROM albumi
        JOIN korisnici ON korisnici.idKorisnici=albumi.dodaoAlbum 
        WHERE albumi.idAlbum='{$albumId}' LIMIT 1";

        $select_dodao_album=mysqli_query($conn, $q_dodaoAlbum);

        if($row= mysqli_fetch_assoc($select_dodao_album)){
            $this->username= $row["username"];
        }
        echo "<br><h5 class='bg-dark text-warning p-1'>Ovaj album je dodao: $this->username</h5>";
    }// end function ostaleNapomene()

    //********************************* Pozvana metoda u oalbumu.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //*********************************Metoda koja iscrtava tabelu *********************************/
    public function tabelaPjesme($idPjesme, $redniBroj, $nazivPjesme, $saradnici, $tekstPjesme, $trajanjePjesme, $feat, $izvodjacId, $mixtapeIzvodjac, $izvodjacMaster)
    {
        // Priprema promenljivih (radi kraćeg koda dole)
        $saradnici       = trim($saradnici ?? '');
        $trajanjePjesme  = ($trajanjePjesme == "00:00:00") ? "" : trim($trajanjePjesme ?? '');
        //$mixtapeIzvodjac1 = konverzijaLatinica($mixtapeIzvodjac);
        $mixtapeIzvodjac = trim($mixtapeIzvodjac ?? '');
        $feat = html_entity_decode(trim($feat ?? ''), ENT_QUOTES, 'UTF-8');
        //$izvodjac        = $mixtapeIzvodjac ?: $izvodjacMaster; // koristi mixtape izvođača ako postoji

        echo "<tr>";

        // Redni broj
        echo "<td>{$redniBroj}</td>";

        // Izvođač / Mixtape
        if (!empty($mixtapeIzvodjac)) {
            echo "<td>" . $this->fit2($mixtapeIzvodjac) . "</td>";
        } else {
            echo "<td>" . $this->fit2($izvodjacMaster) . "</td>";
        }

        // Naziv pjesme
        echo "<td>";
        echo "<a href='tekstovi.php?tekst={$idPjesme}'><span class='clickLink'>{$nazivPjesme}</span></a>";

        // Feat prikaz
        if (!empty($feat)) {
            echo " ";
            $this->fit($feat);
        }

        // Saradnici (ako postoje)
        if (!empty($saradnici)) {
            echo "<br><span class='listaPjesama'>{$saradnici}</span>";
        }

        echo "</td>";

        // Trajanje pjesme (ako postoji)
        if (!empty($trajanjePjesme)) {
            echo "<td>{$trajanjePjesme}</td>";
        }

        echo "</tr>";
    }//end tabelaPjesme()

    //********************************* Pozvana metoda u u ovom fajlu u metodi listaPjesama() *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------
    
    //*********************************Metoda za uklanjanje riječi featuring ili feat. na pjesmi *********************************/
    public function fit($param)
    {
        $orig = trim($param ?? '');

        // Mali tokenizer koji čuva zagrade u prikazu, ali ih ne gura u ime/link
        $tokenize = function(string $s): array {
            // normalizacija razmaka oko zareza i &
            $s = preg_replace('/\s*,\s*/', ', ', $s);
            $s = preg_replace('/\s*&\s*/', ' & ', $s);
            $s = preg_replace('/\s+/', ' ', $s);

            $tokens = [];
            $buf = '';
            $len = strlen($s);

            $flushName = function() use (&$tokens, &$buf) {
                $name = trim($buf);
                if ($name !== '') {
                    $tokens[] = ['t' => 'name', 'v' => $name];
                }
                $buf = '';
            };

            for ($i = 0; $i < $len; $i++) {
                $ch = $s[$i];

                if ($ch === '(') {
                    $flushName();

                    // ✅ ako prethodno nije separator, dodaj razmak prije (
                    if (!empty($tokens)) {
                        $prev = end($tokens);
                        if ($prev['t'] === 'name' || ($prev['t'] === 'sep' && $prev['v'] !== ' ')) {
                            $tokens[] = ['t' => 'text', 'v' => ' '];
                        }
                    }

                    $tokens[] = ['t' => 'text', 'v' => '('];
                    continue;
                }

                if ($ch === ')') {
                    $flushName();
                    $tokens[] = ['t' => 'text', 'v' => ')'];
                    continue;
                }

                if ($ch === ',') {
                    $flushName();
                    $tokens[] = ['t' => 'sep', 'v' => ', '];
                    continue;
                }

                if ($ch === '&') {
                    $flushName();
                    $tokens[] = ['t' => 'sep', 'v' => ' & '];
                    continue;
                }

                $buf .= $ch;
            }

            $flushName();
            return $tokens;
        };

        // ===== FEAT/FT =====
        if (preg_match('/\b(feat(?:uring)?|ft)\s*\.?\s*(.+)$/i', $orig, $m)) {

            $label = rtrim($m[1], '.') . '.';
            $names = trim($m[2]);

            echo $label . ' ';

            $tokens = $tokenize($names);

            foreach ($tokens as $tok) {
                if ($tok['t'] === 'name') {
                    $imePrikaz = $tok['v'];

                    // za link: ukloni zagrade (i ostale "rubne" znakove)
                    $imeLink = trim($imePrikaz, " \t\n\r\0\x0B.\"'");
                    $imeLink = str_replace(['(', ')'], '', $imeLink);
                    $imeLink = trim($imeLink);
                
                    $idIzv = getIzvodjacIdByMaster($imePrikaz);
                    echo '<a class="clickLink" href="izvodjac.php?idIzv=' . ($idIzv ?? '') . '&izvodjac=' . urlencode($imeLink) . '">'.$imePrikaz .'</a>';

                } else {
                    // sep ili običan tekst (zagrade)
                    echo $tok['v'];
                }
            }
            return;
        }

        // ===== NEMA FEAT (fallback) =====
        $param = preg_replace('/\s*,\s*/', ', ', $param);
        $param = preg_replace('/\s*&\s*/', ' & ', $param);
        $param = preg_replace('/\s+/', ' ', $param);
        $param = trim($param);

        $tokens = $tokenize($param);

        foreach ($tokens as $tok) {
            if ($tok['t'] === 'name') {
                $imePrikaz = $tok['v'];

                $imeLink = trim($imePrikaz, " \t\n\r\0\x0B.\"'");
                $imeLink = str_replace(['(', ')'], '', $imeLink);
                $imeLink = trim($imeLink);

                $idIzv = getIzvodjacIdByMaster($imePrikaz);
                echo '<a class="clickLink" href="izvodjac.php?idIzv=' . ($idIzv ?? '') . '&izvodjac=' . urlencode($imeLink) . '">'.$imePrikaz .'</a>';

            } else {
                echo $tok['v'];
            }
        }
    }//end fit()

    //******************************* Pozvana metoda u ovom fajlu (više puta) u metodi tabelaPjesme() *******************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //*********************************Metoda za uklanjanje riječi featuring ili feat. mixtapeIzvodjac (i ako treba još negdje) *********************************/
    public function fit2($mixtapeIzvodjac)
    {
        $raw = (string)($mixtapeIzvodjac ?? '');
        if ($raw === '') return '';

        // Prikaz – dekodiraj entitete, sačuvaj naša slova
        $display = html_entity_decode($raw, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Normalizuj separatore u prikazu
        $display = preg_replace('/\s*,\s*/', ', ', $display);
        $display = preg_replace('/\s*&\s*/', ' & ', $display);
        $display = preg_replace('/\s+/', ' ', $display);
        $display = trim($display);

        // Tokenizer: imena -> link, zagrade i feat/ft -> običan tekst
        $tokenize = function(string $s): array {
            $tokens = [];
            $buf = '';
            $len = strlen($s);

            $flushName = function() use (&$tokens, &$buf) {
                $name = trim($buf);
                if ($name !== '') {
                    $tokens[] = ['t' => 'name', 'v' => $name];
                }
                $buf = '';
            };

            $addTextWithSpacing = function(string $text) use (&$tokens) {
                // dodaj razmak prije text-a ako prethodni token nije separator/razmak
                if (!empty($tokens)) {
                    $prev = end($tokens);
                    $prevVal = $prev['v'] ?? '';
                    if (!($prev['t'] === 'sep') && $prevVal !== ' ' && $prevVal !== ', ' && $prevVal !== ' & ' && $prevVal !== '(') {
                        $tokens[] = ['t' => 'text', 'v' => ' '];
                    }
                }
                $tokens[] = ['t' => 'text', 'v' => $text];
                $tokens[] = ['t' => 'text', 'v' => ' ']; // razmak poslije
            };

            for ($i = 0; $i < $len; $i++) {
                $ch = $s[$i];

                // ✅ prepoznaj feat/featuring/ft kao običan tekst (ne link)
                // radi i kad dođe odmah poslije ')', npr: ") feat. Neko"
                if (preg_match('/\G\b(feat(?:uring)?|ft)\b\.?/i', $s, $m, 0, $i)) {
                    $flushName();

                    $label = $m[0]; // uzmi TAČNO kako piše u tekstu

                    $addTextWithSpacing($label);

                    $i += strlen($m[0]) - 1; // pomjeri indeks preko pogodjenog labela
                    continue;
                }

                if ($ch === '(') {
                    $flushName();

                    // ✅ razmak prije '(' ako dolazi odmah nakon imena
                    if (!empty($tokens)) {
                        $prev = end($tokens);
                        if (($prev['t'] === 'name') || ($prev['t'] === 'text' && $prev['v'] === ')')) {
                            $tokens[] = ['t' => 'text', 'v' => ' '];
                        }
                    }

                    $tokens[] = ['t' => 'text', 'v' => '('];
                    continue;
                }

                if ($ch === ')') {
                    $flushName();
                    $tokens[] = ['t' => 'text', 'v' => ')'];
                    continue;
                }

                if ($ch === ',') {
                    $flushName();
                    $tokens[] = ['t' => 'sep', 'v' => ', '];
                    continue;
                }

                if ($ch === '&') {
                    $flushName();
                    $tokens[] = ['t' => 'sep', 'v' => ' & '];
                    continue;
                }

                $buf .= $ch;
            }

            $flushName();

            // očisti višestruke razmake koje smo možda dodali (npr. prije/poslije labela)
            $clean = [];
            foreach ($tokens as $t) {
                if ($t['t'] === 'text' && $t['v'] === ' ') {
                    if (!empty($clean) && end($clean)['t'] === 'text' && end($clean)['v'] === ' ') {
                        continue; // preskoči dupli razmak
                    }
                }
                $clean[] = $t;
            }

            // trim na kraju ako zadnji token ispadne razmak
            if (!empty($clean) && end($clean)['t'] === 'text' && end($clean)['v'] === ' ') {
                array_pop($clean);
            }

            return $clean;
        };

        $tokens = $tokenize($display);

        $out = '';
        foreach ($tokens as $tok) {
            if ($tok['t'] === 'name') {
                $imePrikaz = $tok['v'];

                // ✅ Link: ukloni zagrade + rubne znakove, pa kroz removeSpecialLetters
                $imeZaLink = str_replace(['(', ')'], '', $imePrikaz);
                $imeZaLink = trim($imeZaLink, " \t\n\r\0\x0B.\"'");
                $imeZaLink0= konverzijaLatinica($imeZaLink);
                $imeLink   = removeSpecialLetters($imeZaLink0);
                $imeLink2   = reverseRemoveSpecialLetters($imeLink);

                $idIzv = getIzvodjacIdByMaster($imeZaLink);

                $out .= '<a class="clickLink" href="izvodjac.php?idIzv=' . ($idIzv ?? '') . '&izvodjac=' . $imeLink . '">'
                    . htmlspecialchars($imePrikaz, ENT_QUOTES, 'UTF-8')
                    . '</a>';
            } else {
                $out .= $tok['v']; // sep ili text (zagrade, feat., razmaci)
            }
        }

        return $out;
    }//end fit2()

    //******************************* Pozvana metoda u ovom fajlu (više puta) u metodi tabelaPjesme() *******************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //*********************************Metoda za prikaz mixtape izvođača *********************************/
    public function mixtape($param)
    {
        global $conn;
        $q= "SELECT izvodjacMaster FROM izvodjaci";
        $upit= mysqli_query($conn, $q);
        $nizMx= explode(", ", $param);
        
        foreach($nizMx as $mxIme)
        {
            ?>
            <a class="feat" href="izvodjac.php?idIzv<?php echo $idIzv?? ''; ?>&izvodjac=<?php echo $mxIme; ?>"><?php echo $mxIme; ?></a><?php
            if(next($nizMx))
            {
                echo ", ";
            }// Dodaje zarez (tj. neki simbol), nakon svakog člana niza, osim zadnjeg
        }// end foreach loop
    }// end function mixtape()

    //******************************* Pozvana metoda u ovom fajlu par puta u metodi tabelaPjesme() *******************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //*********************************Metoda za prikaz teksta pjesme *********************************/
    public function tekstPjesme($tekstId) 
    {
        include "./functions/master.func.php";
        global $conn;

        $q= "SELECT * FROM pjesme 
        JOIN izvodjaci ON izvodjaci.idIzvodjaci = pjesme.izvodjacId
        JOIN albumi ON albumi.idAlbum = pjesme.albumId WHERE idPjesme='{$tekstId}'";

        $select_songs= mysqli_query($conn, $q);
        
        while($row= mysqli_fetch_assoc($select_songs))
        {
            $this->nazivPjesme= $row["nazivPjesme"];
            $this->tekstPjesme= nl2br((string)$row["tekstPjesme"]);
            $this->albumId= $row["idAlbum"];
            $this->nazivAlbuma= $row["nazivAlbuma"];
            $this->idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
            $this->izvodjacMaster= $row["izvodjacMaster"];
            $this->mixtapeIzvodjac= $row["mixtapeIzvodjac"];
            $this->godinaIzdanja= $row["godinaIzdanja"];
            $this->feat= $row["feat"];
            $this->youtubeJednaPjesma= $row["youtubeJednaPjesma"];

            $nazivIzvodjac= ($this->mixtapeIzvodjac==null) ? $this->izvodjacMaster : $this->mixtapeIzvodjac;

            $this->cleanIzvodjacMaster= konverzijaLatinica($this->izvodjacMaster);
            $this->cleanNazivAlbuma= konverzijaLatinica($this->nazivAlbuma);
            $cleanIzvodjacMaster= removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($this->cleanIzvodjacMaster)));
            $cleanNazivAlbuma= removeSerbianLetters(str_replace(" ", "-", removeSpecialLetters($this->cleanNazivAlbuma)));
            ?>
            <h3 class="tekstovi-prikaz mt-0"><span class="">Album:</span>
            <a class="clickLink" href="oalbumu.php?izv=<?php echo $this->idIzvodjacAlbumi; ?>&album=<?php echo $this->albumId; ?>&naziv=<?php echo $cleanIzvodjacMaster . "-" .$cleanNazivAlbuma; ?>"> <?php echo $this->nazivAlbuma ." (".$this->godinaIzdanja . ". )"; ?> </a></h3>
            <?php
            echo "<!-- TekstP -->
			<div class='tekstP'>";
            echo "<h2 class='tekstNaziv'>" .  $nazivIzvodjac . " - " . $this->nazivPjesme . " $this->feat" . "</h2>"; 
            ?>
            <br>
            <div class="streams">
                <?php
                if(!empty($this->youtubeJednaPjesma)){
                    ?>
                    <a href="<?php echo $this->youtubeJednaPjesma; ?>" target="_blank"><img src="images/streams/Youtube-icon.png" title="YouTube" alt="YouTube"></a>
                    <?php
                }//end if(!empty($youtubeVideoL)))
                ?>
            </div><!--end streams-->
            <?php
            echo "<br><br>";
            
            if(empty($this->tekstPjesme)){
                echo "<h2>Nema dostupnih informacija!</h2>";
            }else{
                echo "<p>$this->tekstPjesme</p>";
            }
            echo "</div> <!-- kraj tekstP -->";
        }//end while

        $q_dodaoTekst = "SELECT dodaoTekst FROM pjesme WHERE idPjesme = $tekstId";

		$select_dodaoTekst = mysqli_query($conn, $q_dodaoTekst);

		while($row = mysqli_fetch_array($select_dodaoTekst)) {
			$dodaoTekst = array($row['dodaoTekst']);
			//print_r($dodaoTekst);
		}//end while
    }// end function tekstPjesme()
    //********************************* Pozvana metoda u tekstovi.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    
    

}//end class pjesme