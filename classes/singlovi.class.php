<?php

//METODE SADRŽANE U OVOJ KLASI
//prikazIzabranogSingla (prikaz detalja izabranog singla)
//singlPjesme (ispis teksta izabranog singla)

class singlovi{

    public $idSinglovi;
    public $singlNaziv;
    public $singleFeat;
    public $slikaSingla;
    public $tacanDatumIzdanja;
    public $godinaIzdanjaSingl;
    public $ostaleNapomeneSingl;
    public $youtubeVideo;
    public $spotify;
    public $deezer;
    public $appleMuisc;
    public $tidal;
    public $youtubeMusic;
    public $amazonMusic;
    public $soundCloud;
    public $amazonShop;
    public $dodaoSingl;
    public $singleIzvodjaci;
    public $tekstSingla;

    public $nazivDrzave;
    public $entitetNaziv;

    public $cleansingleFeat;
    public $cleanSinglNaziv;

    public $username;

    public $izdavaciId;
    public $izdavaciNaziv;

    //********************************* Prikaz detalja izabranog singla *********************************//
    
    public function prikazIzabranogSingla($singlId)
    {
        global $conn;
        include_once "pjesme.class.php";
        $newPjesme= new pjesme();

        include_once "detaljiAlbum.class.php";
        $newDetalj= new albumDetalji();

        $q= "SELECT * FROM singlovi 
        JOIN drzave ON drzave.idDrzave = singlovi.drzavaSingl
        LEFT JOIN entiteti ON entiteti.kodEntiteta = singlovi.entitetSingl
        WHERE idSinglovi= '{$singlId}'";
        //JOIN izvodjaci ON izvodjaci.idIzvodjaci= singlovi."
	
        $select_album= mysqli_query($conn, $q);
        
        while($row= mysqli_fetch_assoc($select_album))
        {
            $this->singlNaziv= $row["singlNaziv"];
            $this->tekstSingla= nl2br($row["tekstSingla"]);
            $this->tacanDatumIzdanja= $row["tacanDatumIzdanja"];
            $this->godinaIzdanjaSingl= $row["godinaIzdanjaSingl"];
            $this->singleFeat= $row["singleFeat"];
            $this->singleIzvodjaci= $row["singleIzvodjaci"];
            $this->slikaSingla= $row["slikaSingla"];

            $this->nazivDrzave= $row["nazivDrzave"];
            $this->entitetNaziv= $row["entitetNaziv"];

            $this->cleansingleFeat= konverzijaLatinica($this->singleFeat);
            $this->cleanSinglNaziv= konverzijaLatinica($this->singlNaziv);
            
            $this->cleansingleFeat= removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($this->cleansingleFeat)));
            $this->cleanSinglNaziv= removeSerbianLetters(str_replace(" ", "-", removeSpecialLetters($this->cleanSinglNaziv)));

            $this->cleansingleFeat= removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($this->cleansingleFeat)));

            ?>
            <!-- Prikaz albuma -->
			<h1 class="drzava"><span class="boja">Država: </span><?php echo $this->nazivDrzave; ?></h1>
            <?php
            
            if(!empty($this->entitetNaziv)){
                ?>
                <h3 class="drzava"><span class="boja">Entitet: </span><?php echo $this->entitetNaziv; ?></h3>
                <?php
            }
            ?>
			<!-- Celarfix -->
			<div class="pregledAlbuma">
				<!-- Info albuma -->
                <div class="info">
                    <h3><span class="boja">Izvođač:</span>
                    <?php 
                    echo '<a class="" href="izvodjac.php?izvodjac=' . $this->singleIzvodjaci . '">' . $newPjesme->fit($this->singleIzvodjaci) . '</a> ';
                    echo $newPjesme->fit($this->singleFeat);
                     ?>
                    </h3> 

                    <h3><span class="boja">Singl:</span> <?php echo $this->singlNaziv; ?></h3>
                    <?php
                    if(empty($this->tacanDatumIzdanja))
                    {
                        ?>
                        <h3><span class="boja">Godina izdanja: </span><a href="pogodini.php?godina=<?php echo $this->godinaIzdanjaSingl; ?>"><span class="clickLink"><?php echo $this->godinaIzdanjaSingl; ?></span></a></h3>
                        <?php
                    }else
                        {
                            ?>
                            <h3><span class="boja">Datum izdanja: </span><a href="pogodini.php?godina=<?php echo $this->godinaIzdanjaSingl; ?>"><span class="clickLink"><?php echo $this->tacanDatumIzdanja; ?></span></a></h3>
                            <?php
                        }//end if else()

                    $qIzdavaci = "SELECT * FROM izdavaci 
                    JOIN single_izdavaci ON single_izdavaci.idIzdavaci = izdavaci.idIzdavaci
                    WHERE single_izdavaci.idSingle = '{$singlId}'";

                    $rezIzdavaci = mysqli_query($conn, $qIzdavaci);

                    $izdavaciHTML = [];

                    while ($izd = mysqli_fetch_assoc($rezIzdavaci)) 
                    {
                        $this->izdavaciNaziv = $izd['izdavaciNaziv'];
                        $this->izdavaciId = $izd['idIzdavaci'];
                        $link = "label.php?izdavac=" . str_replace(" ", "-", removeSpecialLetters($this->izdavaciNaziv)) . "&idIzdavac=" . $this->izdavaciId;
                        $izdavaciHTML[] = "<a href=\"$link\"><span class=\"clickLink\">$this->izdavaciNaziv</span></a>";
                    }//end while
                    ?>
                    <h3><span class="boja">Izdavač:</span> 
                    <a href="label.php?izdavac=<?php echo str_replace(" ", "-", removeSpecialLetters($this->izdavaciNaziv)); ?>&idIzdavac=<?php echo  $this->izdavaciId; ?>"><span class="clickLink"><?php echo implode(", ", $izdavaciHTML); ?></span></a></h3>
                </div> <!-- kraj info -->
                
                
                
                <?php
        }//end while loop
        if(!empty($this->slikaSingla))
        {
            ?>
            <div class="slikeIzabranogAlbuma">
                <a href="images/singlovi/<?php echo $this->slikaSingla; ?>" data-lightbox="slika-1">
                    <img src="images/singlovi/<?php echo $this->slikaSingla; ?>" alt="<?php echo $this->singlNaziv; ?>" title="<?php echo $this->singlNaziv ." (Front)"; ?>">
                </a>
            </div> <!-- kraj slikeIzabranogAlbuma -->
            <?php
        }else{echo "";}
        ?>
            
            </div><!-- /.pregledAlbuma -->
            <?php
    }// end function prikazIzabranogSingla()
    //********************************* Pozvana metoda u singlovi.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda za prikaz teksta singla *********************************/
    public function singlPjesme($singlId) 
    {
        include "./functions/master.func.php";
        global $conn;

        $q= "SELECT * FROM singlovi 
        JOIN korisnici ON korisnici.idKorisnici= singlovi.dodaoSingl
        WHERE idSinglovi='{$singlId}'";

        $select_singlovi= mysqli_query($conn, $q);
        
        while($row= mysqli_fetch_assoc($select_singlovi))
        {
            $this->singlNaziv= $row["singlNaziv"];
            $this->tekstSingla= nl2br($row["tekstSingla"]);
            $this->tacanDatumIzdanja= $row["tacanDatumIzdanja"];
            $this->godinaIzdanjaSingl= $row["godinaIzdanjaSingl"];
            $this->singleFeat= $row["singleFeat"];
            $this->dodaoSingl= $row["dodaoSingl"];

            $this->username= $row["username"];

            $this->cleansingleFeat= konverzijaLatinica($this->singleFeat);
            $cleanNazivSingla= konverzijaLatinica($this->singlNaziv);
            $cleansingleFeat= removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($this->cleansingleFeat)));
            $cleanNazivSingla= removeSerbianLetters(str_replace(" ", "-", removeSpecialLetters($cleanNazivSingla)));

            $datumIzdanja=($this->tacanDatumIzdanja==null) ? $this->godinaIzdanjaSingl."." : $this->tacanDatumIzdanja;
            
            if(empty($this->tekstSingla)){
                echo "<h2>Nema dostupnih informacija!</h2>";
            }else{
                echo "<div class='tekstP'><p>$this->tekstSingla</p></div>";
            }
            echo "</div> <!-- kraj tekstP -->";
            
        }//end while

        echo "<h5 class='bg-dark text-warning p-1'>Ovaj singl je dodao: $this->username</h5>";
        
    }// end function singlPjesme()
    //********************************* Pozvana metoda u singlovi.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------
}//end class