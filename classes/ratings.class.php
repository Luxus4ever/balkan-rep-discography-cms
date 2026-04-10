<?php
class ocjene{

    //METODE SADRŽANE U OVOJ KLASI
    //ocjeni (ocjenjivanje albuma)
    //trenutnaOcjena (prikaz trenutne ocjene)
    //lastRatingUser (prikaz zadnje ocjene izabranog albuma trenutnog korisnika)
    //trenutniRezultat (prikaz ocjene albuma bez logina)
    //zvjezdice (prikaz ocjenjivanja (zvjezdica /mikrofona)
    //spremiOcjenuAPI (методе за API које враћају JSON)
    //dohvatiOcjenuAPI (prikaz trenutne ocjene korisnika za taj album)
    //obrisiOcjenuAPI (brisanje ocjene)

    //*********************************Metoda za ocjenjivanje albuma *********************************/
    public function ocjeni($albumId, $sesId)
    {
        global $conn; 
        if(isset($_POST["odabrano"]))
        {
            $broj= $_POST["odabrano"];
            $albumId= $_POST["album"];
            $izvodjac= $_POST["izvodjac"];
            $lid= $sesId;
            $whiteList= array(1,2,3,4,5,6,7,8,9,10);
            
            if(is_numeric($broj) && in_array($broj,$whiteList) && is_numeric($albumId))
            {
                $qCheck = "SELECT * FROM ocjene WHERE albumId='{$albumId}' AND korisniciId='{$lid}'";
                $result = mysqli_query($conn, $qCheck);
                
                if(mysqli_num_rows($result) > 0)
                {
                    // Update existing rating
                    $qUpdate = "UPDATE ocjene SET ratedIndex='{$broj}' WHERE albumId='{$albumId}' AND korisniciId='{$lid}'";
                    mysqli_query($conn, $qUpdate);
                }
                else
                {
                    // Insert new rating
                    $qInsert = "INSERT INTO ocjene (ratedIndex, vrijeme, albumId, izvodjacId, korisniciId) VALUES ('$broj', now(), $albumId, $izvodjac, $lid)";
                    mysqli_query($conn, $qInsert);
                }
            }else {
                die("NIJE BROJ!!!!");
            }
        }
        $this->trenutnaOcjena($albumId);
        //$this->lastRatingUser($albumId, $sesId);

        $korisnikovaOcjena = $this->lastRatingUser($albumId, $sesId);
    
    // Prikaz teksta van funkcije pomoću echo-a (izbjeći miješanje prikaza unutar funkcije)
    //echo "<h3>Trenutna vaša ocjena za ovaj album je: <span id='trenOcjena'> $korisnikovaOcjena </span></h3>";
    }
    //********************************* Pozvana metoda u oalbumu.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //*********************************Metoda za prikaz trenutne ocjene *********************************/
    public function trenutnaOcjena($albumId)
    {
        global $conn;
        $q2= "SELECT SUM(ratedIndex) AS total FROM ocjene WHERE albumId='{$albumId}'";
        $trenutno= mysqli_query($conn, $q2);

        while($row= mysqli_fetch_array($trenutno))
        {
            $ukupno= $row["total"];
        }
        $q3= "SELECT COUNT(albumId) AS brgl FROM ocjene WHERE albumId='{$albumId}'";
        $brojgl= mysqli_query($conn, $q3);

        while($row= mysqli_fetch_array($brojgl))
        {
            $zbir= $row["brgl"];
        }
    
        $this->zvjezdice();
        if(!empty($ukupno))
        {
            echo "<h3 class='sredina'>Trenutna ukupna ocijena: <span id='trenOcjena'>" . round($ukupno / $zbir,2) . "</span></h3> <br>";
            //echo "Trenutni broj glasova: " . $zbir;
        }else
            {
                //echo "<h3>Trenutna ocijena: 0 </h3>  <br>";
                //echo "Trenutni broj glasova: " . $zbir;
            }//end if else
            return;
    }//trenutnaOcjena()
    //********************************* Pozvana metoda u ovom fajlu u metodi ocjeni() *********************************//
    
    //--------------------------------------------------------------------------------------------------------------------------------

    //*********************************Metoda za prikaz zadnje ocjene trenutnog korisnika *********************************/
    public function lastRatingUser($albumId, $sesId)
    {
         global $conn;
    $q3= "SELECT ratedIndex FROM ocjene WHERE albumId='{$albumId}' AND korisniciId='{$sesId}'";
    $korisnik_ocjena= mysqli_query($conn, $q3);
    $trenutna_ocjena_korisnik = 0;
    if($row= mysqli_fetch_array($korisnik_ocjena))
    {
        $trenutna_ocjena_korisnik= $row["ratedIndex"];
    }
    // Vratiti samo broj, ne echo HTML
    return $trenutna_ocjena_korisnik;
    }//end lastRatingUser()
    //********************************* Pozvana metoda u ovom fajlu u metodi ocjeni() *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //*********************************Metoda za prikaz za korisnike koji nisu ulogovani *********************************/
    public function trenutniRezultat($albumId)
    {
        global $conn;
        $q2= "SELECT SUM(ratedIndex) AS total FROM ocjene WHERE albumId='{$albumId}'";
        $trenutno= mysqli_query($conn, $q2);
        while($row= mysqli_fetch_array($trenutno)) {
            $ukupno= $row["total"];
        }
        $q3= "SELECT COUNT(albumId) AS brgl FROM ocjene WHERE albumId='{$albumId}'";
        $brojgl= mysqli_query($conn, $q3);
        while($row= mysqli_fetch_array($brojgl)) {
            $zbir= $row["brgl"];
        }
        ?>
        <div class="ratings sredina">
            <p>Ocijeni album:</p>
            <?php
            dodajNapomenu("Morate biti ulogovani da ocijenite album!");
            if(!empty($ukupno)){
                echo "<h3>Trenutna ocjena: <span id='trenOcjena'>" . round($ukupno / $zbir,2) . "</span></h3> <br>";
                echo "<p>Trenutni broj glasova: <span id='brojGlasova'>" . $zbir . "</span></p>";
            }else{
                echo "<h3>Trenutna ocjena: 0 </h3>  <br>";
                echo "<p>Trenutni broj glasova: <span id='brojGlasova'>0</span></p>";
            }
            ?>
        </div>
        <?php
    }//end function trenutniRezultat()
    //********************************* Pozvana metoda u ovom fajlu u metodi trenutnaOcjena() *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //*********************************Metoda za prikaz ocjenjivanja *********************************/
    public function zvjezdice()
    {
        ?>
        <!-- Ratings/ocjene -->
		<div class="ratings">
				<p>Ocijeni album:</p>
        <div class="container">
            <div class="post">
                <div class="text">Hvala na ocjeni!</div>
                <div class="edit">Izmeni ocjenu</div>
            </div><!-- end .post -->
            <div class="star-widget" id="proba">
                <input type="radio" name="rate" class="ocena" id="rate-10" value="10">
                <label for="rate-10" class="fas fa-microphone"></label>
                <input type="radio" name="rate" class="ocena" id="rate-9" value="9">
                <label for="rate-9" class="fas fa-microphone"></label>
                <input type="radio" name="rate" class="ocena" id="rate-8" value="8">
                <label for="rate-8" class="fas fa-microphone"></label>
                <input type="radio" name="rate" class="ocena" id="rate-7" value="7">
                <label for="rate-7" class="fas fa-microphone"></label>
                <input type="radio" name="rate" class="ocena" id="rate-6" value="6">
                <label for="rate-6" class="fas fa-microphone"></label>
                <input type="radio" name="rate" class="ocena" id="rate-5" value="5">
                <label for="rate-5" class="fas fa-microphone"></label>
                <input type="radio" name="rate" class="ocena" id="rate-4" value="4">
                <label for="rate-4" class="fas fa-microphone"></label>
                <input type="radio" name="rate" class="ocena" id="rate-3" value="3">
                <label for="rate-3" class="fas fa-microphone"></label>
                <input type="radio" name="rate" class="ocena" id="rate-2" value="2">
                <label for="rate-2" class="fas fa-microphone"></label>
                <input type="radio" name="rate" class="ocena" id="rate-1" value="1">
                <label for="rate-1" class="fas fa-microphone"></label>
                <form id="obrisiOcjenuForm" action="#">
                <header></header>
                <div class="btn">
                    <button type="submit" name="obrisiOcjenuBtn">Obriši ocjenu</button>
                </div>
                </form>
            </div><!-- end .star-widget -->
        </div><!-- end .container -->

        <script src="./js/ratings.js"></script>
        <?php
    }//end function zvjezdice()
    //********************************* Pozvana metoda u ovom fajlu u metodi trenutnaOCjena() *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda za API koja vraća JSON *********************************//
    public function spremiOcjenuAPI($albumId, $korisnikId, $ocjena, $izvodjac) {
        global $conn;
        $whiteList = range(1, 10);
        if (is_numeric($ocjena) && in_array($ocjena, $whiteList) && is_numeric($albumId)) {
            $qCheck = "SELECT * FROM ocjene WHERE albumId='{$albumId}' AND korisniciId='{$korisnikId}'";
            $result = mysqli_query($conn, $qCheck);
            if (mysqli_num_rows($result) > 0) {
                $qUpdate = "UPDATE ocjene SET ratedIndex='{$ocjena}' WHERE albumId='{$albumId}' AND korisniciId='{$korisnikId}'";
                mysqli_query($conn, $qUpdate);
            } else {
                $qInsert = "INSERT INTO ocjene (ratedIndex, vrijeme, albumId, izvodjacId, korisniciId) VALUES ('$ocjena', now(), $albumId, $izvodjac, $korisnikId)";
                mysqli_query($conn, $qInsert);
            }
            return $this->dohvatiOcjenuAPI($albumId, $korisnikId);
        } else {
            return ['error' => 'Neispravni podaci za ocjenu'];
        }
    }//end spremiOcjenuAPI()
    //********************************* Pozvana metoda u fajlu ratings_api.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Prikazuje trenutnu ocjenu korisnika za taj album *********************************//
    public function dohvatiOcjenuAPI($albumId, $korisnikId) {
        global $conn;
        $res = ['average' => 0, 'votes' => 0, 'userRating' => 0];
        $q = "SELECT AVG(ratedIndex) AS avgRating, COUNT(*) AS brgl FROM ocjene WHERE albumId='{$albumId}'";
        $result = mysqli_query($conn, $q);
        if ($row = mysqli_fetch_assoc($result)) {
            $res['average'] = round($row['avgRating'], 2);
            $res['votes'] = $row['brgl'];
        }
        $q2 = "SELECT ratedIndex FROM ocjene WHERE albumId='{$albumId}' AND korisniciId='{$korisnikId}'";
        $result2 = mysqli_query($conn, $q2);
        if ($row2 = mysqli_fetch_assoc($result2)) {
            $res['userRating'] = (int)$row2['ratedIndex'];
        }
        return $res;
    }//end function dohvatiOcjenuAPI()
    //********************************* Pozvana metoda u fajlu ratings_api.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda za brisanje ocjene *********************************//
    public function obrisiOcjenuAPI($albumId, $korisnikId) {
        global $conn;
        if (!is_numeric($albumId) || !is_numeric($korisnikId)) {
            return ['error' => 'Neispravan album ili korisnik'];
        }

        $qDelete = "DELETE FROM ocjene WHERE albumId = '{$albumId}' AND korisniciId = '{$korisnikId}'";
        if (mysqli_query($conn, $qDelete)) {
            // Vratiti osvježene podatke ocjene nakon brisanja
            return $this->dohvatiOcjenuAPI($albumId, $korisnikId);
        } else {
            return ['error' => 'Greška pri brisanju ocjene'];
        }
    }//end function obrisiOcjenuAPI()
    //********************************* Pozvana metoda u fajlu ratings_api.php *********************************//

}//end class ocjene
?>
