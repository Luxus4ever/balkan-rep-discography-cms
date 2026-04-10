<?php
//FUNKCIJE U OVOM FAJLU
//nadjiIzvodjaca (Pronalazi izvođača ukoliko koristi drugo ime)
//dbg ()
/*Debug varijanta ti pomaže da odmah vidiš:
šta tačno porediš, i zašto se ne poklapa.
Kod stringova to je često nevidljivo: dupla razmaka, nevidljiv \r, “Đ” vs “đ”, HTML entiteti, itd.)*/

//normalizeForSearch (Search normalizer je funkcija koja služi isključivo za poređenje, ne za prikaz)
//getIzvodjacIdByMaster (Prinalazi idIzvođača)

//********************************* Pronalazi izvodjaca ukoliko koristi drugo ime *********************************//

function nadjiIzvodjaca($izvodjac) 
{
    global $conn; 

    // ✅ Normalizacija stringa za poređenje:
    // - html entiteti, tagovi, višak razmaka
    // - ćirilica -> latinica (ako koristiš)
    // - dijakritika -> osnovna slova
    // - Đ/đ -> dj  (da "Djunta" == "Đunta")
    // - case-insensitive
    $normalizeIzvodjac = function($s)
    {
        $s = (string)$s;

        // ukloni eventualne html entitete (&#39; itd) + tagove
        $s = html_entity_decode($s, ENT_QUOTES | ENT_HTML5, "UTF-8");
        $s = strip_tags($s);

        // svedi višak razmaka
        $s = trim($s);
        $s = preg_replace('/\s+/', ' ', $s);

        // ćirilica -> latinica (tvoja funkcija, ako postoji)
        if(function_exists("konverzijaLatinica")) {
            $s = konverzijaLatinica($s);
        }

        // mala slova
        /*$s = mb_strtolower($s, "UTF-8");

        // ✅ prvo Đ/đ -> dj (digraf)
        $s = str_replace("Đ", "Dj", $s);*/

        // ✅ ostala dijakritika -> osnovna slova (po potrebi proširi)
        $map = array(
            "č" => "c", "ć" => "c",
            "š" => "s",
            "ž" => "z",
            // ako se pojavi neko strano slovo, možeš dodavati ovde
        );
        $s = strtr($s, $map);

        // ukloni sve što nije slovo/broj/razmak (da poređenje bude stabilno)
        $s = preg_replace('/[^a-z0-9 ]+/u', '', $s);

        // još jedno čišćenje razmaka
        $s = trim($s);
        $s = preg_replace('/\s+/', ' ', $s);

        return $s;
    };

    // normalizovan unos
    $izvodjacNorm = $normalizeIzvodjac($izvodjac);

    // 1. Proveri da li postoji izvođač direktno
    $izvodjac_esc = mysqli_real_escape_string($conn, $izvodjac);
    $q = "SELECT izvodjacMaster FROM izvodjaci WHERE izvodjacMaster='{$izvodjac_esc}'";
    $select_izvodjac = mysqli_query($conn, $q);

    if (mysqli_num_rows($select_izvodjac) > 0) {
        // Izvođač pronađen direktno – nema potrebe za dodatnom akcijom
        return;
    }//end if

    // ✅ dodatna provera: možda nije identično u bazi, ali jeste isto posle normalizacije
    $q0 = "SELECT izvodjacMaster FROM izvodjaci";
    $select_izvodjac0 = mysqli_query($conn, $q0);

    while ($row0 = mysqli_fetch_array($select_izvodjac0)) 
    {
        $izvodjacMaster0 = $row0["izvodjacMaster"];

        if ($normalizeIzvodjac($izvodjacMaster0) === $izvodjacNorm) 
        {
            // Izvođač pronađen direktno (normalizovano) – nema potrebe za dodatnom akcijom
            return;
        }//end if
    }//end while 0

    // 2. Ako nije pronađen, traži po nadimcima
    $q = "SELECT izvodjacMaster, nadimciIzvodjac FROM izvodjaci";
    $select_izvodjac = mysqli_query($conn, $q);

    $izvodjacM2 = "";

    while ($row = mysqli_fetch_array($select_izvodjac)) 
	{
        $izvodjacM = $row["izvodjacMaster"];
        $nadimakIzvodjac = $row["nadimciIzvodjac"];

        // ✅ Ako nema nadimaka preskoči
        if (empty($nadimakIzvodjac)) {
            continue;
        }//end if

        // ✅ Robustno razdvajanje nadimaka:
        // podržava: ",", ";", novi red, "|", "/" i varijacije razmaka
        $nadimakIzvodjacNiz = preg_split('/\s*(?:,|;|\||\/|\r\n|\r|\n)\s*/', $nadimakIzvodjac, -1, PREG_SPLIT_NO_EMPTY);

        foreach($nadimakIzvodjacNiz as $nadimakJedan)
        {
            $nadimakNorm = $normalizeIzvodjac($nadimakJedan);

            if ($nadimakNorm === $izvodjacNorm) 
		    {
                // Ako je nadimak pronađen, uzmi sve podatke za glavnog izvođača
                $izvodjacM_esc = mysqli_real_escape_string($conn, $izvodjacM);
                $q2 = "SELECT * FROM izvodjaci WHERE izvodjacMaster='{$izvodjacM_esc}'";
                $select_izvodjac2 = mysqli_query($conn, $q2);

                while ($row2 = mysqli_fetch_array($select_izvodjac2)) 
			    {
                    $idIzvodjaci = $row2["idIzvodjaci"];
                    $izvodjacM2 = $row2["izvodjacMaster"];

                    // (ostavljam tvoju logiku za link)
                    $cleanIzvodjacMaster = konverzijaLatinica($izvodjacM2);
                    $cleanIzvodjacMaster = removeSerbianLetters($cleanIzvodjacMaster);

                    $idIzv = getIzvodjacIdByMaster($izvodjacM2);
                    ?>
                    <p>Ovaj izvođač je poznatiji pod nazivom: </p>
                    <a class="feat" href="izvodjac.php?idIzv=<?php echo $idIzv?? ''; ?>&izvodjac=<?php echo str_replace(" ", "+", $cleanIzvodjacMaster); ?>">
                        <?php echo $izvodjacM2; ?>
                    </a>
                    <?php
                }//end while 2

                // ✅ Pošto je nađen rezultat, nema potrebe da ide dalje kroz bazu
                break 2; // izlazi iz foreach + while
            }//end if
        }//end foreach
    }//end while 1

    // 3. Ako ništa nije pronađeno
    if (empty($izvodjacM2)) 
	{
        echo "U bazi podataka nema izvođača pod ovim imenom. <br>
        Idite na pretragu i pokušajte da pronađete pod drugim ali sličnim imenom. <br> 
        Na primer: ako ste uneli 'Skajvikler', pokušajte 'Wikluh Sky'.<br><br>";
    }//end if

    /*
    $izvodjacNorm = normalizeForSearch($izvodjac);
    $nadimakNorm  = normalizeForSearch($nadimakJedan);

    dbg("UNOS", $izvodjac);
    dbg("UNOS NORM", normalizeForSearch($izvodjac));

    dbg("NADIMAK", $nadimakJedan);
    dbg("NADIMAK NORM", normalizeForSearch($nadimakJedan));
    */
}//end nadjiIzvodjaca()

//********************************* Pozvana funkcija u izvodjac.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Debug pomaže da se poredi šta se tačno vidi *********************************//
function dbg($label, $value)
{
    echo "<pre style='background:#111;color:#0f0;padding:10px;border-radius:6px;'>";
    echo $label . ":\n";
    echo "RAW: " . $value . "\n";
    echo "HEX: " . bin2hex($value) . "\n";
    echo "</pre>";
}//end dbg()

//********************************* Pozvana funkcija u ovom fajlu u funckiji nadjiIzvodjaca *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Search normalizer služi isključivo za poređenje, ne za prikaz *********************************//
function normalizeForSearch($s)
{
    // sigurnost
    $s = (string)$s;

    // html entiteti + tagovi
    $s = html_entity_decode($s, ENT_QUOTES | ENT_HTML5, "UTF-8");
    $s = strip_tags($s);

    // ćirilica → latinica (ako postoji)
    if (function_exists("konverzijaLatinica")) {
        $s = konverzijaLatinica($s);
    }

    // višak razmaka
    $s = trim($s);
    $s = preg_replace('/\s+/', ' ', $s);

    // mala slova (radi stabilnosti)
    $s = mb_strtolower($s, "UTF-8");

    // Đ → Dj (SAMO to)
    $s = str_replace("đ", "dj", $s);

    return $s;
}//end normalizeForSearch()

//********************************* Pozvana funkcija u ovom fajlu u funckiji nadjiIzvodjaca *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Pronalazi id Izvođača *********************************//
function getIzvodjacIdByMaster($imePrikaz) {
    global $conn;

    $imePrikaz = mysqli_real_escape_string($conn, $imePrikaz);

    $q = "SELECT idIzvodjaci
          FROM izvodjaci
          /*WHERE izvodjacMaster LIKE '%{$imePrikaz}%'*/
          WHERE izvodjacMaster='{$imePrikaz}' OR nadimciIzvodjac LIKE '%{$imePrikaz}%'
          LIMIT 1";

    $res = mysqli_query($conn, $q);
    if ($res && ($row = mysqli_fetch_assoc($res))) {
        return (int)$row['idIzvodjaci'];
    }

    return null;
}

//********************************* Pozvana funkcija u pjesme.class.php, detaljiAlbum.class.php, tj. svuda gde je potrebno da se nabavi id izvođača *********************************//


//--------------------------------------------------------------------------------------------------------------------------------