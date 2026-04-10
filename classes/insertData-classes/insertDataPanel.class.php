<?php

class insertDataPanel{

    //METODE SADRŽANE U OVOJ KLASI
    //1. leftSidePanel($idKorisnici, $idIzv="") - Metoda za prikaz lijeve strane admin panela
    //2. prikazUnosPanela($sesId, $nazivPromjeneLinka) - Metoda za prikaz sredine panela za unos
    //3. leftSideAdmin($userAdmin, $idIzv) - Metoda za prikaz opcija za unos albuma

    private $username;

    //********************************* Metoda za prikaz lijeve strane panela za unos *********************************//
    public function leftSideUnosPanel($sesId){
        ?>
        <div class="col-md-2 visina leftPanel">
            <?php
            $this->leftSideAdminInsertAlbum($sesId);
            ?>
        </div><!-- end col-md-2 --> 
        <?php
    }//end leftSideUnosPanel()
    //********************************* Pozvana metoda u ovom fajlu u metodi prikazUnosPanela(), u fajlu insertsongs.php, insertstreams.php,  *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda za prikaz sredine panela za unos *********************************//

    public function prikazUnosPanela($sesId, $nazivPromjeneLinka)
    {
        include "insertAlbum.class.php";
        include "insertSingleSong.class.php";
        include "insertAlbumSongs.class.php";
        include "insertLabel.class.php";
        include "insertArtist.class.php";
        ?>
        <div class="container-fluid slikeAlbumaPregled visina">
            <div class="row">
                <?php
                $this->leftSideUnosPanel($sesId);
                ?>
               
                <div class="col-md-10 panel">        
                    <?php 
                    if($nazivPromjeneLinka===null) 
                    {
                        ?>
                        <div class="" style="padding-top: 200px;"> 
                            <h3 class='text-danger sredina'><strong><span class="bg-danger text-white">&nbsp;Uputstvo za dodavanje albuma: &nbsp;</strong></h3>
                            <h5 class='text-warning sredina'>1. Provjerite da li postoji taj izvođač, ukoliko nema, dodajte ga.</h5>
                            <h5 class='text-warning sredina'>2. Provjerite da li postoji izdavačka kuća / Label, ukoliko nema, dodajte.</h5>
                            <h5 class='text-warning sredina'>3. Izaberite sa strane "Album" i dodajte obavezna polja.</h5>
                            <h5 class='text-warning sredina'>4. Kada dodate album izaberite "Pjesme" i izaberite album, da dodate pjesme na tom albumu.</h5>
                        </div>
                        <?php
                    }else 
                    {
                        $newAlb= new insertAlbum();
                        $newSingl= new insertSingleSong();
                        $newAlbSongs= new insertSongs();
                        $newLabel = new insertLabel();
                        $newArtist= new insertArtist();

                        switch($nazivPromjeneLinka)
                        {
                            case "izvodjaci": $newArtist->dodajIzvodjaca($sesId); break;
                            case "albumi": $newAlb->insertAboutAlbum($sesId); break;
                            case "singlovi": $newSingl->insertSingl($sesId); break;
                            case "strimovi": $newAlbSongs->spisakAlbumaZaStrimove($sesId); break;
                            case "pjesme": $newAlbSongs->spisakPjesamaJednogAlbuma(); break;
                            case "tekstovi": $newAlbSongs->insertLyrics($sesId); break;
                            case "label": $newLabel->insertLabel2($sesId); break;
                            default: ""; break;
                        }
                    }//end if else (provjera GET parametra)
                    ?>
                </div><!-- end col-md-10 --> 
            </div><!-- end row --> 
        </div><!-- end container-fluid --> 
        <?php
    }//end prikazUnosPanela()
    //********************************* Pozvana metoda u fajlu dodajalbume.php  *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda za prikaz opcija za unos albuma *********************************//
    
    private function leftSideAdminInsertAlbum($sesId)
    {
        global $conn;
        $q0= "SELECT * FROM korisnici JOIN status_korisnika ON statusKorisnika=idStatusKorisnika WHERE idKorisnici=$sesId";
        $select_adminUser= mysqli_query($conn, $q0);

        while($row= mysqli_fetch_array($select_adminUser))
        {
            $this->username= $row["username"];
            $statusKorisnika= $row["nazivStatusaKorisnika"];
            tipKorisnika($sesId);
            ?>
            <h4 class="text-center pt-3"><strong><a href="dodajalbume.php" class="text-decoration-none text-light"><?php echo $this->username; ?></a></strong></h4>
            <br>
            <h5 class="text-center text-info">Dodaj: </h5> 
            <hr class="bg-light">
            <?php
        }
        
        ?>
        <a class="text-decoration-none" href="dodajalbume.php?data=izvodjaci"><h5 class="text-center text-warning">Izvođača</h5></a><hr class="bg-light">

        <a class="text-decoration-none" href="dodajalbume.php?data=albumi"><h5 class="text-center text-warning">Album</h5></a><hr class="bg-light">
        <a class="text-decoration-none" href="dodajalbume.php?data=singlovi"><h5 class="text-center text-warning">Singlovi</h5></a><hr class="bg-light">
        <a class="text-decoration-none" href="dodajalbume.php?data=strimovi"><h5 class="text-center text-warning">Strimovi</h5></a><hr class="bg-light">
        <a class="text-decoration-none" href="dodajalbume.php?data=pjesme"><h5 class="text-center text-warning">Pjesme</h5></a><hr class="bg-light">
        <a class="text-decoration-none" href="dodajalbume.php?data=label"><h5 class="text-center text-warning">Izdavačku kuću</h5></a><hr class="bg-light">
        <a class="text-decoration-none" href="dodajalbume.php?data=tekstovi"><h5 class="text-center text-warning">Tekst pjesme</h5></a>
        <hr class="bg-light">
        <?php
    }//end leftSideAdminInsertAlbum()

    //********************************* Pozvana metoda u u ovom fajlu u metodi leftSidePanel()  *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

}//end class insertDataPanel