<?php

class albumDetalji{

    //METODE SADRŽANE U OVOJ KLASI
    //sviAlbumiPocetna (prikazuje 6 albuma po državama na početnoj)
    //sviAlbumiEntitet (prikazuje 6 albuma po entitetima na početnoj)
    //prikazAlbuma (prikaz detalja izabranog albuma)
    //ostaliAlbumi (prikaz ostalih/svih albuma izabranog izvođača)
    //poGodini (prikaz svih albuma objavljenih u nekoj godini)
    //poGodinama (prikaz svih albuma objavljenih po godinama)
    //poIzvodjacima (prikaz svih albuma objavljenih abecedno po izvođačima)
    //omiljeniAlbumi (dodavanje albuma u favorite listu)
    //unesiOmiljeniAlbum (unos albuma iz favorite liste u bazu)
    //obrisiOmiljeniAlbum (brisanje omiljenog albuma iz liste)
    //prikazPrednjegZadnjegCovera (prikaz prednje i zadnje slike albuma)
    //prikazSvihSlikaAlbuma (prikaz svih ostalih (unutrašnjih) slika albuma
    //izabraniIzvodjacGrupa (Prikazuje naziv izabranog izvođača (kao link), u fajlu oalbumu)
    //izabraniIzvodjacNaslov (Prikazuje ko je izvođač albuma kada se nanese strelicom preko albuma na početnoj, bez opisnog teksta, razlog za ovu funkciju je jer može biti više izvođača)
    //prikazSinglovaIzvodjaca (prikaz svih singlova izabranog izvođača ako je unešen u bazu)


    public $albumId;
    public $idIzvodjacAlbumi;
    public $idIzvodjaci;
    public $idIzvodjac2;
    public $idIzvodjac3;
    public $idAlbum;
    public $nazivAlbuma;
    public $pseudonimIzvodjacaAlbuma;
    public $godinaIzdanja;
    public $izdavac;
    public $slikaAlbuma;
    public $drzavaAlbumi;
    public $entitetAlbumi;
    public $tacanDatumIzdanja;
    public $izvodjacMaster;
    public $nadimci;
    public $entitet1;
    public $entitet2;
    public $idIzdavaci;

    protected $slikaAlbumaZadnja; 
    protected $slikeAlbumaOstale;

    public $lid;
    public $idOmiljeniAlbumi;
    public $userIdFavorite;
    public $albumiFavorite;
    public $idKorisnici;
    public $cleanIzvodjacMaster;
    public $cleanIzvodjac2;
    public $cleanIzvodjac3;
    public $cleanNazivAlbuma;

    public $zastavaDrzave;
    public $zastavEnt;

    public $idDrzave;
    public $nazivDrzave;
    public $clanoviOveGrupe;

    public $izdavaciId;
    public $izdavaciNaziv;
    public $idKategorijeAlbuma;
    public $nazivKategorijeAlbuma;

    public $idGrupe;
    public $nazivGrupe;

    //*********************************Metoda za prikaz 6 albuma na početnoj *********************************/
    public function sviAlbumiPocetna($idDrzaveParam)
    {
        global $conn;

        $q= "SELECT * FROM albumi JOIN izvodjaci ON izvodjaci.idIzvodjaci= albumi.idIzvodjacAlbumi
            JOIN drzave ON drzave.idDrzave= izvodjaci.drzavaIzvodjac
            WHERE drzavaAlbumi='{$idDrzaveParam}' ORDER BY RAND() LIMIT 6";

        $select_album= mysqli_query($conn, $q);
        ?>
        <div class="slikeAlbuma noBoxSizing">
            <?php
            while($row= mysqli_fetch_assoc($select_album))
            {
                $this->albumId= $row["idAlbum"];
                $this->idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
                $this->idIzvodjac2= $row["idIzvodjac2"];
                $this->idIzvodjac3= $row["idIzvodjac3"];
                $this->nazivAlbuma= $row["nazivAlbuma"];
                $this->godinaIzdanja= $row["godinaIzdanja"];
                $this->slikaAlbuma= $row["slikaAlbuma"];
                $this->drzavaAlbumi= $row["drzavaAlbumi"];
                $this->entitetAlbumi= $row["entitetAlbumi"];
                $this->tacanDatumIzdanja= $row["tacanDatumIzdanja"];
                $this->izvodjacMaster= $row["izvodjacMaster"];
                $this->nadimci= $row["nadimciIzvodjac"];
                $this->entitet1= $row["entitet1"];
                $this->entitet2= $row["entitet2"];
                

                $this->cleanIzvodjacMaster= konverzijaLatinica($this->izvodjacMaster);
                $this->cleanNazivAlbuma= konverzijaLatinica($this->nazivAlbuma);
                $this->cleanIzvodjacMaster= removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($this->cleanIzvodjacMaster)));
                $this->cleanNazivAlbuma= removeSerbianLetters(cleanAlbum($this->cleanNazivAlbuma));

                if($this->idIzvodjac2==null)
                {
                    ?>
                    <a href="oalbumu.php?izv=<?php echo $this->idIzvodjacAlbumi."&album=".$this->albumId."&naziv=".$this->cleanIzvodjacMaster."-".$this->cleanNazivAlbuma; ?>" >
                <img loading="lazy" src="images/albumi/<?php echo $this->slikaAlbuma; ?>" alt="<?php echo $this->nazivAlbuma; ?>" title="<?php echo $this->izabraniIzvodjacNaslov($this->idIzvodjac2, $this->idIzvodjac3) . " - " . $this->nazivAlbuma; ?>">
                </a>
                <?php
                }else{
                    ?>
                    <a href="oalbumu.php?izv=<?php echo $this->idIzvodjacAlbumi."&album=".$this->albumId."&naziv=".$this->cleanIzvodjacMaster."-".$this->cleanNazivAlbuma; ?>" >
                    <img loading="lazy" src="images/albumi/<?php echo $this->slikaAlbuma; ?>" alt="<?php echo $this->nazivAlbuma; ?>" title="<?php echo $this->izabraniIzvodjacNaslov($this->idIzvodjac2, $this->idIzvodjac3) . " - " . $this->nazivAlbuma; ?>">
                    </a>
                    <?php
                }//end if else()   
            }/// end While 
            ?>
        </div><!-- end slikeAlbuma noBoxSizing -->
        <?php
    }//end sviAlbumiPocetna()

    /********************************* Metoda pozvana u fajlu nazivDrzava.func.php u metodi nazivDrzave() *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Prikazuje 5 albuma na početnoj po entitetima *********************************//
    public function sviAlbumiEntitet($idEnt)
    {
        global $conn;
        $q= "SELECT * FROM albumi JOIN izvodjaci ON izvodjaci.idIzvodjaci= albumi.idIzvodjacAlbumi
        WHERE entitetAlbumi='{$idEnt}' ORDER BY RAND() LIMIT 6";
        
        $select_album= mysqli_query($conn, $q);
        ?>
        <div class="slikeAlbuma noBoxSizing">
            <?php
            while($row= mysqli_fetch_assoc($select_album))
            {
                $this->albumId= $row["idAlbum"];
                $this->idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
                $this->idIzvodjac2= $row["idIzvodjac2"];
                $this->idIzvodjac3= $row["idIzvodjac3"];
                $this->nazivAlbuma= $row["nazivAlbuma"];
                $this->godinaIzdanja= $row["godinaIzdanja"];
                $this->slikaAlbuma= $row["slikaAlbuma"];
                $this->drzavaAlbumi= $row["drzavaAlbumi"];
                $this->entitetAlbumi= $row["entitetAlbumi"];
                $this->tacanDatumIzdanja= $row["tacanDatumIzdanja"];
                $this->izvodjacMaster= $row["izvodjacMaster"];
                $this->nadimci= $row["nadimciIzvodjac"];

                $this->cleanIzvodjacMaster= konverzijaLatinica($this->izvodjacMaster);
                $this->cleanNazivAlbuma= konverzijaLatinica($this->nazivAlbuma);
                $this->cleanIzvodjacMaster= konverzijaLatinica(removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($this->cleanIzvodjacMaster))));
                $this->cleanNazivAlbuma= konverzijaLatinica(removeSerbianLetters(cleanAlbum($this->cleanNazivAlbuma)));

                if($this->idIzvodjac2==null)
                {
                    ?>
                    <a href="oalbumu.php?izv=<?php echo $this->idIzvodjacAlbumi."&album=".$this->albumId."&naziv=".$this->cleanIzvodjacMaster."-".$this->cleanNazivAlbuma; ?>" >
                <img loading="lazy" src="images/albumi/<?php echo $this->slikaAlbuma; ?>" alt="<?php echo $this->nazivAlbuma; ?>" title="<?php echo $this->izabraniIzvodjacNaslov($this->idIzvodjac2, $this->idIzvodjac3) . " - " . $this->nazivAlbuma; ?>">
                </a>
                <?php
                }else{
                    ?>
                    <a href="oalbumu.php?izv=<?php echo $this->idIzvodjacAlbumi."&album=".$this->albumId."&naziv=".$this->cleanIzvodjacMaster."-".$this->cleanNazivAlbuma; ?>" >
                    <img loading="lazy" src="images/albumi/<?php echo $this->slikaAlbuma; ?>" alt="<?php echo $this->nazivAlbuma; ?>" title="<?php echo $this->izabraniIzvodjacNaslov($this->idIzvodjac2, $this->idIzvodjac3) . " - " . $this->nazivAlbuma; ?>">
                    </a>
                    <?php
                }//end if else()
                
            }/// end While
                ?>
        </div><!-- end slikeAlbuma noBoxSizing -->
        <?php
    }//end sviAlbumiEntitet()

    /********************************* Metoda pozvana u fajlu nazivDrzava.func.php u metodi nazivDrzave() *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------
    
    //********************************* Prikaz detalja albuma izabranog izvodjaca *********************************//
    
    public function prikazAlbuma($izvodjacId, $albumId)
    {
        global $conn;

        $q= "SELECT albumi.*, izvodjaci.*, drzave.*, entiteti.entitetNaziv 
        FROM albumi
        JOIN izvodjaci ON izvodjaci.idIzvodjaci = albumi.idIzvodjacAlbumi
        JOIN drzave ON drzave.idDrzave = albumi.drzavaAlbumi
        LEFT JOIN entiteti ON entiteti.idEntiteti = albumi.entitetAlbumi
        WHERE albumi.idIzvodjacAlbumi = '{$izvodjacId}' AND albumi.idAlbum = '{$albumId}'";
	
        $select_album= mysqli_query($conn, $q);
        
        while($row= mysqli_fetch_assoc($select_album))
        {
            $this->idIzvodjaci= $row["idIzvodjaci"];
            $this->idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
            $this->idIzvodjac2= $row["idIzvodjac2"];
            $this->idIzvodjac3= $row["idIzvodjac3"];
            $this->nazivAlbuma= $row["nazivAlbuma"];
            $this->godinaIzdanja= $row["godinaIzdanja"];
            $this->pseudonimIzvodjacaAlbuma= $row["pseudonimIzvodjacaAlbuma"];

            //$this->slikaAlbuma= $row["slikaAlbuma"];
            $this->drzavaAlbumi= $row["drzavaAlbumi"];
            $this->entitetAlbumi= $row["entitetAlbumi"];
            $this->tacanDatumIzdanja= $row["tacanDatumIzdanja"];
            $this->izvodjacMaster= $row["izvodjacMaster"];
            $this->idIzvodjac2= $row["idIzvodjac2"];
            $this->idIzvodjac3= $row["idIzvodjac3"];
            $this->idDrzave= $row["idDrzave"];
            $this->clanoviOveGrupe= $row["clanoviOveGrupe"];
            $this->nazivDrzave= $row["nazivDrzave"];
            $entitetNaziv= $row["entitetNaziv"];
            /*$this->zastavaDrzave= $row["zastavaDrzave"];
            $this->zastavEnt= $row["zastavEnt"];*/

            //$this->izdavaciId= $row["idIzdavaci"];
            //$this->izdavaciNaziv= $row["izdavaciNaziv"];

            $this->cleanIzvodjacMaster= konverzijaLatinica($this->izvodjacMaster);
            $this->cleanNazivAlbuma= konverzijaLatinica($this->nazivAlbuma);
            $this->cleanIzvodjac2= konverzijaLatinica($this->idIzvodjac2);  //?????????????????????????????
            $this->cleanIzvodjac3= konverzijaLatinica($this->idIzvodjac3);  //?????????????????????????????

            $this->cleanIzvodjacMaster= removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($this->cleanIzvodjacMaster)));
            $this->cleanIzvodjac2= removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($this->cleanIzvodjac2)));
            $this->cleanIzvodjac3= konverzijaLatinica(removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($this->cleanIzvodjac3))));
            ?>
            <!-- Prikaz albuma -->
			<h1 class="drzava"><span class="boja">Država: </span><a href="drzava.php?nazivdrzave=<?php echo $this->nazivDrzave; ?>"><span class="clickLink"><?php echo $this->nazivDrzave; ?></a></h1>
            <?php
            if(!empty($this->entitetAlbumi)){
                ?>
                <h3 class="drzava"><span class="boja">Entitet: </span><a href="drzava.php?ent=<?php echo $entitetNaziv; ?>"><span class="clickLink"><?php echo $entitetNaziv; ?></a></h3>
                <?php
            }
            ?>
			<!-- Celarfix -->
			<div class="pregledAlbuma">
				<!-- Info albuma -->
                <div class="info">
                    <h2><span class="boja">Izvođač:</span>
                    <?php $this->izabraniIzvodjacGrupa($this->cleanIzvodjac2, $this->cleanIzvodjac3); ?>
                    </h2> 

                    <h2><span class="boja">Album:</span> <?php echo $this->nazivAlbuma; ?></h2>
                    <?php
                    if(empty($this->tacanDatumIzdanja))
                    {
                        ?>
                        <h3><span class="boja">Godina izdanja: </span><a href="pogodini.php?godina=<?php echo $this->godinaIzdanja; ?>"><span class="clickLink"><?php echo $this->godinaIzdanja; ?></span></a></h3>
                        <?php
                    }else
                        {
                            ?>
                            <h3><span class="boja">Godina izdanja: </span><a href="pogodini.php?godina=<?php echo $this->godinaIzdanja; ?>"><span class="clickLink"><?php echo $this->tacanDatumIzdanja; ?></span></a></h3>
                            <?php
                        }//end if else()

                    /*-------------------- Početak Žanrovi/kategorije --------------------*/
                    $qKategorije = "SELECT * FROM kategorije_albuma 
                    JOIN albumi_kategorije ON albumi_kategorije.idKategorijeAlbuma = kategorije_albuma.idKategorijeAlbuma
                    WHERE albumi_kategorije.idAlbum = '{$albumId}'";

                    $rezKategorije = mysqli_query($conn, $qKategorije);

                    $kategorijeHTML = [];

                    while ($izd = mysqli_fetch_assoc($rezKategorije)) 
                    {
                        $this->nazivKategorijeAlbuma = $izd['nazivKategorijeAlbuma'];
                        $this->idKategorijeAlbuma = $izd['idKategorijeAlbuma'];
                        $link = "zanrovi.php?kategorija=" . str_replace(" ", "-", removeSpecialLetters($this->nazivKategorijeAlbuma)) . "&idKategorije=" . $this->idKategorijeAlbuma;
                        $kategorijeHTML[] = "<a href=\"$link\"><span class=\"clickLink\">$this->nazivKategorijeAlbuma</span></a>";
                        //print_r($this->nazivKategorijeAlbuma);
                    }//end while
                    ?>
                    <h3><span class="boja">Kategorija:</span> <?php  ?>
                    <a href="zanrovi.php?kategorija=<?php echo str_replace(" ", "-", removeSpecialLetters($this->nazivKategorijeAlbuma)); ?>&idKategorije=<?php echo  $this->idKategorijeAlbuma; ?>"><span class="clickLink"><?php echo implode(", ", $kategorijeHTML); ?></span></a></h3>

                    <?php
                    /*-------------------- end Žanrovi/kategorije --------------------*/

                    /*-------------------- Početak Izdavači --------------------*/
                    $qIzdavaci = "SELECT * FROM izdavaci 
                    JOIN albumi_izdavaci ON albumi_izdavaci.idIzdavaci = izdavaci.idIzdavaci
                    WHERE albumi_izdavaci.idAlbum = '{$albumId}'";

                    $rezIzdavaci = mysqli_query($conn, $qIzdavaci);

                    $izdavaciHTML = [];

                    while ($izd = mysqli_fetch_assoc($rezIzdavaci)) 
                    {
                        $this->izdavaciNaziv = $izd['izdavaciNaziv'];
                        $this->izdavaciId = $izd['idIzdavaci'];
                        $link = "label.php?izdavac=" . str_replace(" ", "-", removeSpecialLetters($this->izdavaciNaziv)) . "&idIzdavac=" . $this->izdavaciId;
                        $izdavaciHTML[] = "<a href=\"$link\"><span class=\"clickLink\">$this->izdavaciNaziv</span></a>";
                        //print_r($this->izdavaciNaziv);
                    }//end while
                    ?>
                    <h3><span class="boja">Izdavač:</span> <?php  ?>
                    <a href="label.php?izdavac=<?php echo str_replace(" ", "-", removeSpecialLetters($this->izdavaciNaziv)); ?>&idIzdavac=<?php echo  $this->izdavaciId; ?>"><span class="clickLink"><?php echo implode(", ", $izdavaciHTML); ?></span></a></h3>
                    <?php
                    /*-------------------- end izdavači --------------------*/
                    ?>
                </div> <!-- kraj info -->
                <?php
        }//end while loop
        ?>
                <div class="slikeIzabranogAlbuma sredina2">
                    <?php 
                    $this->prikazPrednjegZadnjegCovera($albumId);
                    echo "<br>";
                    ?>
                    
                    <div class="sredina2">
                        <?php
                        $this->prikazSvihSlikaAlbuma($albumId);
                        @$this->lid= $_SESSION["idKorisnici"];
                        ?>
                    </div><!-- end .sredina For cover image -->
                    
                                    <?php
                    $ses2= ($this->lid===null) ? "" : $this->omiljeniAlbumi($albumId, $this->lid);
                ?>
                </div> <!-- kraj slikeIzabranogAlbuma -->

            </div><!-- /.pregledAlbuma -->
            <?php
    }// end function prikazAlbuma()
    //********************************* Pozvana metoda u oalbumu.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Prikaz ostalih albuma izabranog izvodjaca *********************************//
    public function ostaliAlbumi() 
    {
        global $conn;

         $q1= "SELECT * FROM albumi
        WHERE idIzvodjacAlbumi='{$this->idIzvodjacAlbumi}' OR idIzvodjac2='{$this->idIzvodjacAlbumi}' ORDER BY godinaIzdanja";

        $select_album= mysqli_query($conn, $q1);

        $q2= "SELECT * FROM albumi JOIN izvodjaci ON izvodjaci.idIzvodjaci= albumi.idIzvodjacAlbumi
        WHERE idIzvodjacAlbumi='{$this->idIzvodjacAlbumi}' OR idIzvodjac2='{$this->idIzvodjacAlbumi}' OR idIzvodjac3='{$this->idIzvodjacAlbumi}' ORDER BY godinaIzdanja";

        $select_album= mysqli_query($conn, $q2);
        ?>
        <div class="ostaliAlbumi">
            <div class="slikeAlbuma">
                <?php
                echo "<hr>";
                echo "<h3>Svi albumi - <span class='boja'>". $this->izvodjacMaster . "</span></h3>";
                while($row= mysqli_fetch_array($select_album))
                {
                    $this->idAlbum= $row["idAlbum"];
                    $this->idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
                    $this->izvodjacMaster= $row["izvodjacMaster"];
                    $this->nazivAlbuma= $row["nazivAlbuma"];
                    $godinaIzdanja= $row["godinaIzdanja"];
                    $slikaAlbuma= $row["slikaAlbuma"];
                    $drzavaAlbumi= $row["drzavaAlbumi"];
                    $entitetAlbumi= $row["entitetAlbumi"];
                    //$idDrzave= $row["idDrzave"];
                    //data-lightbox="slika-2" // unutar taga da uveća sliku. Svaki broj posebna galerija

                    $cleanIzvodjacMaster= konverzijaLatinica($this->izvodjacMaster);
                    $cleanNazivAlbuma= konverzijaLatinica($this->nazivAlbuma);
                    $cleanIzvodjacMaster= str_replace(" ", "+", removeSpecialLetters($cleanIzvodjacMaster));
                    $cleanNazivAlbuma= str_replace(" ", "-", removeSpecialLetters($cleanNazivAlbuma));
                    //$idDrzave= $row["idDrzave"];
                    //data-lightbox="slika-2" // unutar taga da uveća sliku. Svaki broj posebna galerija
                    ?>
                    <div class="myCard">
                        <a href="oalbumu.php?izv=<?php echo $this->idIzvodjacAlbumi."&album=".$this->idAlbum."&naziv=".$cleanIzvodjacMaster."-".$cleanNazivAlbuma; ?>" >
                        
                            <img loading="lazy" src="images/albumi/<?php echo $slikaAlbuma; ?>" alt="<?php echo $this->nazivAlbuma; ?>" title="<?php echo $this->nazivAlbuma; ?>">
                        </a>
                    </div><!-- end .myCard -->
                    
                    <?php
                }//end while
                ?>
            </div><!-- end .slikeAlbuma -->
        </div><!-- end .O -->
        <hr>
        <?php
    }// end function ostaliAlbumi()
    //********************************* Pozvana metoda u oalbumu.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Prikaz svih albuma objavljenih u nekoj godini *********************************//
    public function poGodini($godinaIzdanjaParametar)
    {
        global $conn;
        include "./functions/removeSymbols.func.php";
        $q= "SELECT godinaIzdanja FROM albumi WHERE godinaIzdanja='{$godinaIzdanjaParametar}' ORDER BY albumi.godinaIzdanja ASC";
        $select_godinu= mysqli_query($conn, $q);
        
        while($row= mysqli_fetch_assoc($select_godinu))
        {
            $this->godinaIzdanja= $row["godinaIzdanja"];

            
            ?>
            <div class="albumPrikaz">
                <h1><?php echo $this->godinaIzdanja; ?></h1>
                
                <?php
                $q2= "SELECT * FROM albumi 
                JOIN izvodjaci ON izvodjaci.idIzvodjaci= albumi.idIzvodjacAlbumi 
                WHERE godinaIzdanja='{$godinaIzdanjaParametar}'
                ORDER BY albumi.idAlbum ASC";
                $select_godinu= mysqli_query($conn, $q2);
                ?> 
                <div class="slikeAlbumaPregled">
                    <?php
                    while($row= mysqli_fetch_assoc($select_godinu))
                    {
                        $this->albumId= $row["idAlbum"];
                        $this->idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
                        $this->idIzvodjac2= $row["idIzvodjac2"];
                        $this->idIzvodjac3= $row["idIzvodjac3"];
                        $this->nazivAlbuma= $row["nazivAlbuma"];
                        $this->godinaIzdanja= $row["godinaIzdanja"];
                        $this->slikaAlbuma= $row["slikaAlbuma"];
                        $this->drzavaAlbumi= $row["drzavaAlbumi"];
                        $this->entitetAlbumi= $row["entitetAlbumi"];
                        $this->izvodjacMaster= $row["izvodjacMaster"];
                        
                        /*-------------------- Početak Izdavači --------------------*/
                        $qIzdavaci = "SELECT izdavaciNaziv FROM izdavaci 
                        JOIN albumi_izdavaci ON albumi_izdavaci.idIzdavaci= izdavaci.idIzdavaci
                        JOIN albumi ON albumi.idAlbum= albumi_izdavaci.idAlbum
                        WHERE godinaIzdanja = '{$godinaIzdanjaParametar}' AND albumi_izdavaci.idAlbum='{$this->albumId}'";

                        $rezIzdavaci = mysqli_query($conn, $qIzdavaci);

                        $izdavaciHTML = [];

                        while ($izd = mysqli_fetch_assoc($rezIzdavaci)) 
                        {
                            $this->izdavaciNaziv = $izd['izdavaciNaziv'];
                            $izdavaciHTML[]= $this->izdavaciNaziv;
                        
                        }//end while
                        /*-------------------- end izdavači --------------------*/
                        

                        $this->cleanIzvodjacMaster= konverzijaLatinica($this->izvodjacMaster);
                        $this->cleanNazivAlbuma= konverzijaLatinica($this->nazivAlbuma);
                        $this->cleanIzvodjacMaster= removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($this->cleanIzvodjacMaster)));
                        $this->cleanNazivAlbuma= removeSerbianLetters(cleanAlbum($this->cleanNazivAlbuma));
                        
                         //print_r($izdavaciHTML);
                        ?>
                        <div class="myCard myCard-size1">
                            <a href="oalbumu.php?izv=<?php echo $this->idIzvodjacAlbumi."&album=".$this->albumId."&naziv=". $this->cleanIzvodjacMaster."-".$this->cleanNazivAlbuma; ?>" >
                            <img loading="lazy" src="images/albumi/<?php echo $this->slikaAlbuma; ?>" alt="<?php echo $this->nazivAlbuma; ?>" title="<?php echo $this->izabraniIzvodjacNaslov($this->idIzvodjac2, $this->idIzvodjac3) . " - " . $this->nazivAlbuma; ?>">
                            <div class="myCard-body">
                                <h5 class="myCard-title"><?php echo $this->izvodjacMaster . " - " . $this->nazivAlbuma; ?></h5>
                                <p class="myCard-text2"><?php echo implode(", ", $izdavaciHTML); ?></p>
                            </div><!-- end .myCard-body -->   
                            </a>
                        </div><!-- end .myCard -->
                        <?php
                    }/**** end while 2 ****/
                    ?> 
                </div><!-- end .slikeAlbuma -->
            </div><!-- end .albumPrikaz -->
         <?php
        }/**** end while 1 ****/
    }//end poGodini()

    //********************************* Pozvana metoda u pogodini.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Prikaz svih albuma objavljenih po godinama *********************************//
    public function poGodinama()
    {
        global $conn;
        $q= "SELECT godinaIzdanja, count(godinaIzdanja) AS brAlb FROM albumi GROUP BY godinaIzdanja 
        HAVING count(godinaIzdanja) ORDER BY albumi.godinaIzdanja ASC";
        $select_godinu_izdanja= mysqli_query($conn, $q);
        
        while($row= mysqli_fetch_assoc($select_godinu_izdanja))
        {
            $this->godinaIzdanja= $row["godinaIzdanja"];
            $brojAlbuma= $row["brAlb"];
            ?>
            <div class="albumiPoGodini drzava">
                <h1><?php echo $this->godinaIzdanja; ?></h1>
                <?php
                $q2= "SELECT * FROM albumi 
                JOIN izvodjaci ON izvodjaci.idIzvodjaci= albumi.idIzvodjacAlbumi 
                WHERE godinaIzdanja='{$this->godinaIzdanja}'
                ORDER BY albumi.idAlbum ASC";
                $select_godinu= mysqli_query($conn, $q2);

                while($row= mysqli_fetch_assoc($select_godinu))
                {
                    $this->albumId= $row["idAlbum"];
                    $this->idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
                    $this->idIzvodjac2= $row["idIzvodjac2"];
                    $this->idIzvodjac3= $row["idIzvodjac3"];
                    $this->nazivAlbuma= $row["nazivAlbuma"];
                    $this->godinaIzdanja= $row["godinaIzdanja"];
                    $this->slikaAlbuma= $row["slikaAlbuma"];
                    $this->drzavaAlbumi= $row["drzavaAlbumi"];
                    $this->entitetAlbumi= $row["entitetAlbumi"];
                    $this->izvodjacMaster= $row["izvodjacMaster"];

                    $qIzdavaci = "SELECT * FROM izdavaci 
                    JOIN albumi_izdavaci ON albumi_izdavaci.idIzdavaci = izdavaci.idIzdavaci
                    WHERE albumi_izdavaci.idAlbum = '{$this->albumId}'";

                    $rezIzdavaci = mysqli_query($conn, $qIzdavaci);

                    $izdavaciHTML = [];

                    while ($izd = mysqli_fetch_assoc($rezIzdavaci)) 
                    {
                        $izdavaciNaziv = $izd['izdavaciNaziv'];
                        $izdavaciId = $izd['idIzdavaci'];
                        $link = "label.php?izdavac=" . str_replace(" ", "-", removeSpecialLetters($izdavaciNaziv)) . "&idIzdavac=" . $izdavaciId;
                        $izdavaciHTML[] = "<a href=\"$link\"><span class=\"clickLink\">$izdavaciNaziv</span></a>";
                        //print_r($izdavaciNaziv);
                    }//end while
                    

                    $this->cleanIzvodjacMaster= konverzijaLatinica($this->izvodjacMaster);
                    $this->cleanNazivAlbuma= konverzijaLatinica($this->nazivAlbuma);
                    $this->cleanIzvodjacMaster= removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($this->cleanIzvodjacMaster)));
                    $this->cleanNazivAlbuma= removeSerbianLetters(cleanAlbum($this->cleanNazivAlbuma));
                    
                    ?>
                    <div class="myCard myCard-size1">
                        <a href="oalbumu.php?izv=<?php echo $this->idIzvodjacAlbumi."&album=".$this->albumId."&naziv=".$this->cleanIzvodjacMaster."-".$this->cleanNazivAlbuma; ?>" >
                        <img loading="lazy" src="images/albumi/<?php echo $this->slikaAlbuma; ?>" alt="<?php echo $this->nazivAlbuma; ?>" title="<?php echo $this->izabraniIzvodjacNaslov($this->idIzvodjac2, $this->idIzvodjac3) . " - " . $this->nazivAlbuma; ?>">
                        <div class="myCard-body">
                            <h5 class="myCard-title"><?php echo $this->izvodjacMaster . " - " . $this->nazivAlbuma; ?></h5>
                            <p class="myCard-text2"><?php echo $izdavaciNaziv; ?></p>
                        </div><!-- end .myCard-body -->   
                        </a>
                    </div><!-- end .myCard -->
                    <?php
                }/**** end while 2 ****/
                ?> 
                <br>
                <p>Trenutno dodatih albuma za <?php echo $this->godinaIzdanja . ". godinu je " .  $brojAlbuma;  ?></p><br>
            </div><!-- end .albumiPoGodini -->
         <hr class="hrLinija1">
         <?php
        }/**** end while 1 ****/
    }//poGodinama()

    //********************************* Pozvana metoda u pogodinama.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Prikaz svih albuma objavljenih po izvođačima *********************************//
    public function poIzvodjacima()
    {
        global $conn;
        $q= "SELECT * FROM drzave 
        JOIN albumi ON albumi.drzavaAlbumi=drzave.idDrzave
        JOIN izvodjaci ON izvodjaci.idIzvodjaci= albumi.idIzvodjacAlbumi
        ORDER BY izvodjacMaster";
        $select_albumi= mysqli_query($conn, $q);

        ?>
        <div class="albumPrikaz">
            <h1 class="drzava">Prikaz po izvođačima</h1>
            <div class="slikeAlbuma">
                <?php 
                while($row=mysqli_fetch_array($select_albumi))
                {
                    $this->idAlbum= $row["idAlbum"];
                    $this->idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
                    $this->idIzvodjac2= $row["idIzvodjac2"];
                    $this->idIzvodjac3= $row["idIzvodjac3"];
                    $this->nazivAlbuma= $row["nazivAlbuma"];
                    $this->godinaIzdanja= $row["godinaIzdanja"];
                    $this->slikaAlbuma= $row["slikaAlbuma"];
                    $this->drzavaAlbumi= $row["drzavaAlbumi"];
                    $this->entitetAlbumi= $row["entitetAlbumi"];
                    $this->izvodjacMaster= $row["izvodjacMaster"];
                    $this->idDrzave= $row["idDrzave"];
                    $this->nazivDrzave= $row["nazivDrzave"];
                    $this->entitet1= $row["entitet1"];
                    $this->entitet2= $row["entitet2"];
                    //$this->entitet= $row["entitet"];

                    $qIzdavaci = "SELECT * FROM izdavaci 
                    JOIN albumi_izdavaci ON albumi_izdavaci.idIzdavaci = izdavaci.idIzdavaci
                    WHERE albumi_izdavaci.idAlbum = '{$this->albumId}'";

                    $rezIzdavaci = mysqli_query($conn, $qIzdavaci);

                    $izdavaciHTML = [];

                    while ($izd = mysqli_fetch_assoc($rezIzdavaci)) 
                    {
                        $izdavaciNaziv = $izd['izdavaciNaziv'];
                        $izdavaciId = $izd['idIzdavaci'];
                        $link = "label.php?izdavac=" . str_replace(" ", "-", removeSpecialLetters($izdavaciNaziv)) . "&idIzdavac=" . $izdavaciId;
                        $izdavaciHTML[] = "<a href=\"$link\"><span class=\"clickLink\">$izdavaciNaziv</span></a>";
                        //print_r($izdavaciNaziv);
                    }//end while

                    $cleanIzvodjacMaster= konverzijaLatinica(removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($this->izvodjacMaster))));
                    $cleanNazivAlbuma= konverzijaLatinica(removeSerbianLetters(cleanAlbum($this->nazivAlbuma)));
                    ?>
                    <div class="myCard myCard-size1">
                        <a href="oalbumu.php?izv=<?php echo $this->idIzvodjacAlbumi."&album=".$this->idAlbum."&naziv=".$cleanIzvodjacMaster."-".$cleanNazivAlbuma; ?>" >
                            <img loading="lazy" class="myCard-img-top" src="images/albumi/<?php echo $this->slikaAlbuma; ?>" alt="<?php echo izabraniIzvodjac($this->izvodjacMaster, $this->idIzvodjac2, $this->idIzvodjac3) . " - " . $this->nazivAlbuma; ?>" title="<?php echo izabraniIzvodjac($this->izvodjacMaster, $this->idIzvodjac2, $this->idIzvodjac3) . " - " . $this->nazivAlbuma; ?>">
                            <div class="myCard-body">
                                <h5 class="myCard-title"><?php echo izabraniIzvodjac($this->izvodjacMaster, $this->idIzvodjac2, $this->idIzvodjac3) . " - " . $this->nazivAlbuma; ?></h5>
                                <p class="myCard-text1"><span class="godinaIzd"><?php echo $this->godinaIzdanja . "</span>"; ?></p>
                                <p class="myCard-text2"><?php echo $izdavaciNaziv; ?></p>
                            </div><!-- end .myCard-body -->    
                        </a>
                    </div><!-- end .myCard -->
                    <?php
                }//end while
                ?>
            </div><!-- end .slikeAlbuma -->
        </div><!-- end .albumPrikaz -->
        <?php
    }//end poIzvodjacima()
    //********************************* Pozvana metoda u poizvodjacima.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Dodavanje albuma u favorite listu *********************************//
    public function omiljeniAlbumi($albumId, $lid)
    {
        global $conn;
        ?>
        <div class="omiljeni">
            <form id="favForm">
                <input 
                    type="checkbox" 
                    name="favAlbum" 
                    id="favAlbum" 
                    class="favAlbum"
                    value="<?php echo $albumId; ?>"
                    <?php 
                    // označi ako je već u bazi
                    $q = "SELECT 1 FROM omiljeni_albumi WHERE userIdFavorite=$lid AND albumiFavorite=$albumId";
                    $res = mysqli_query($conn, $q);
                    if (mysqli_num_rows($res) > 0) echo "checked";
                    ?>
                >
                <label for="favAlbum">Omiljeni Album</label>
                <p id="favMsg" style="display:none" class="text-success mt-2"></p>
            </form>
            <script src="./js/favoriteAlbum.js"></script>
        </div><!-- end .omiljeni -->
        <?php
    }//end omiljeniAlbumi()
    //********************************* Pozvana metoda u ovom fajlu u metodi prikazAlbuma() *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Unos albuma u favorite listu *********************************//
    public function unesiOmiljeniAlbum($albumId)
    {
        global $conn;
        $lid = (int)$_SESSION["idKorisnici"] ?? null;
        $albumId = (int)$albumId;
        $lid = (int)$lid;
        if (!$lid || !$albumId) { echo "Nema korisnika ili albuma"; return; }

        $provjera = mysqli_query($conn, "SELECT 1 FROM omiljeni_albumi WHERE userIdFavorite=$lid AND albumiFavorite=$albumId");
        if (mysqli_num_rows($provjera) == 0) {
            $q = "INSERT INTO omiljeni_albumi (userIdFavorite, albumiFavorite) VALUES ($lid, $albumId)";
            mysqli_query($conn, $q);
            echo "Dodano u bazu";
        } else {
            echo "Već postoji";
        }
    }//end unesiOmiljeniAlbum()
    //********************************* Pozvana metoda u fajlu o albumu.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------
    
    //********************************* Brisanje albuma iz favorite liste *********************************//
    public function obrisiOmiljeniAlbum($albumId)
    {
        global $conn;
        $lid = $_SESSION["idKorisnici"] ?? null;
        if (!$lid || !$albumId) return;

        $q = "DELETE FROM omiljeni_albumi WHERE userIdFavorite=$lid AND albumiFavorite=$albumId";
        mysqli_query($conn, $q);
    }//end obrisiOmiljeniAlbum
    //********************************* Pozvana metoda u fajlu o albumu.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //--------------------------------------------------------------------------------------------------------------------------------
    //********************************* Metoda za prikaz prednje slike (cover-a) i zadnje slike albuma *********************************//
    public function prikazPrednjegZadnjegCovera($albumId)
    {
        global $conn;
        $q= "SELECT slikaAlbuma, slikaAlbumaZadnja FROM albumi  
        WHERE albumi.idAlbum='{$albumId}'";

        $select_prednjeZadnje_slike= mysqli_query($conn, $q);
        
        while($row= mysqli_fetch_assoc($select_prednjeZadnje_slike))
        {
            $this->slikaAlbuma= $row["slikaAlbuma"];
            $this->slikaAlbumaZadnja= $row["slikaAlbumaZadnja"];
            ?>
            <a href="images/albumi/<?php echo $this->slikaAlbuma; ?>" data-lightbox="slika-1">
                <img src="images/albumi/<?php echo $this->slikaAlbuma; ?>" alt="<?php echo $this->nazivAlbuma; ?>" title="<?php echo $this->nazivAlbuma ." (Front)"; ?>">
            </a>
            
            <?php 
            if(!empty($this->slikaAlbumaZadnja))
            {
                ?>
                <a href="images/albumi/back/<?php echo $this->slikaAlbumaZadnja; ?>" data-lightbox="slika-1">
                    <img src="images/albumi/back/<?php echo $this->slikaAlbumaZadnja; ?>" alt="<?php echo $this->nazivAlbuma; ?>" title="<?php echo $this->nazivAlbuma." (Back)"; ?>">
                </a>
                <?php
            }else{
                echo "";
            }//end if else (!empty($this->slikaAlbumaZadnja))
        }//end while
    }//end prikazPrednjegZadnjegCovera()

    //********************************* Pozvana metoda u ovom fajlu u metodi prikazAlbuma() *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda za prikaz dodatnih slika albuma tj. onih koje se nalaze unutar albuma *********************************//

    public function prikazSvihSlikaAlbuma($albumId)
    {
        global $conn;
        $q= "SELECT unutrasnjeSlikeAlbuma FROM slike_albuma_ostale WHERE slike_albuma_ostale.albumId='{$albumId}'";
	
        $select_sve_slike= mysqli_query($conn, $q);
        $br= 0;
        while($row= mysqli_fetch_assoc($select_sve_slike))
        {
            $this->slikeAlbumaOstale= $row["unutrasnjeSlikeAlbuma"];
            if(!empty($this->slikeAlbumaOstale))
            {
                ?>
                <a href="images/albumi/inside/<?php echo $this->slikeAlbumaOstale; ?>" data-lightbox="slika-1">
                    <img class="dodatneSlikeAlbuma" src="images/albumi/inside/<?php echo $this->slikeAlbumaOstale; ?>" alt="<?php echo $this->nazivAlbuma; ?>" title="<?php echo $this->nazivAlbuma.' (inside '.++$br . ')'; ?>">
                </a>
                <?php
            }else{
                echo "";
            }//end if else(!empty($this->slikeAlbumaOstale))
        }//end while
    }//end prikazSvihSlikaAlbuma()

    //********************************* Pozvana metoda u ovom fajlu u metodi prikazAlbuma() *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Prikazuje naziv izabranog izvođača (kao link), prilikom otvaranja albuma *********************************//

    public function izabraniIzvodjacGrupa($idIzvodjac2="", $idIzvodjac3="")
    {
        global $conn;
        $q2= "SELECT * FROM izvodjaci WHERE idIzvodjaci='{$this->idIzvodjac2}'";
        $select_izvodjace=mysqli_query($conn, $q2);

        while($row2= mysqli_fetch_array($select_izvodjace))
        {
            $izvodjac2= $row2["izvodjacMaster"];
            $idIzvodjac2= $row2["idIzvodjaci"];
            $clanoviOveGrupe= $row2["clanoviOveGrupe"];

            $q3= "SELECT * FROM izvodjaci WHERE idIzvodjaci='{$this->idIzvodjac3}'";
            $select_izvodjace3=mysqli_query($conn, $q3);

            if(mysqli_num_rows($select_izvodjace3)>0)
            {

                while($row3= mysqli_fetch_array($select_izvodjace3))
                {
                    $izvodjac3= $row3["izvodjacMaster"];
                    $idIzvodjac3= $row3["idIzvodjaci"];
                }
            }//end if($select_izvodjace3)
        }//end while
            
        $proba= konverzijaLatinica($this->izvodjacMaster);
        $this->cleanNazivAlbuma= konverzijaLatinica($this->nazivAlbuma);
        $this->cleanIzvodjacMaster= removeSpecialLetters($proba);

        if(!empty($izvodjac3))
        {   $idIzv = getIzvodjacIdByMaster($this->izvodjacMaster);
            $idIzv2 = getIzvodjacIdByMaster($izvodjac2);
            $idIzv3 = getIzvodjacIdByMaster($izvodjac3);

            ?> 
            <a href="izvodjac.php?idIzv=<?php echo $idIzv ?? '';?>&izvodjac=<?php echo $this->cleanIzvodjacMaster; ?>"><span class="clickLink"> <?php echo $this->izvodjacMaster?></span></a>, <a href="izvodjac.php?idIzv=<?php echo $idIzv2 ?? '';?>&izvodjac=<?php echo str_replace(" ", "+", $izvodjac2)?>"><span class="clickLink"><?php echo $izvodjac2; ?></span></a>, <a href="izvodjac.php?idIzv=<?php echo $idIzv3 ?? '';?>&izvodjac=<?php echo str_replace(" ", "+", $izvodjac3)?>"><span class="clickLink"> <?php echo $izvodjac3; ?> </span></a>
            <?php
        }else if(!empty($izvodjac2)){
            $idIzv = getIzvodjacIdByMaster($this->izvodjacMaster);
            $idIzv2 = getIzvodjacIdByMaster($izvodjac2);
            ?> 
            <a href="izvodjac.php?idIzv=<?php echo $idIzv ?? '';?>&izvodjac=<?php echo $this->cleanIzvodjacMaster; ?>"><span class="clickLink"> <?php echo $this->izvodjacMaster?> </span></a> & <a href="izvodjac.php?idIzv=<?php echo $idIzv2 ?? '';?>&izvodjac=<?php echo str_replace(" ", "+", $izvodjac2)?>"><span class="clickLink"> <?php echo $izvodjac2; ?> </span></a>
            <?php
        }else{
            $idIzv = getIzvodjacIdByMaster($this->izvodjacMaster);
            $pseudonim= ($this->pseudonimIzvodjacaAlbuma==null) ? null : "&nbsp;&nbsp;&nbsp;($this->pseudonimIzvodjacaAlbuma)";
            ?> 
            <a href="izvodjac.php?idIzv=<?php echo $idIzv ?? '';?>&izvodjac=<?php echo $this->cleanIzvodjacMaster; ?>"><span class="clickLink"> <?php echo   $this->izvodjacMaster . $pseudonim ; ?> </span></a>
            <?php
        }
    }//end izabraniIzvodjacGrupa()
    //********************************* Pozvana metoda u ovom fajlu u metodi prikazAlbuma() *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------
    
    //********************************* Prikazuje ko je izvođač albuma kada se nanese strelicom preko albuma na početnoj, bez opisnog teksta, razlog za ovu funkciju je jer može biti više izvođača *********************************//

    public function izabraniIzvodjacNaslov($idIzvodjac2="", $idIzvodjac3="")
    {
        global $conn;
        $q2= "SELECT * FROM izvodjaci WHERE idIzvodjaci='{$this->idIzvodjac2}'";
        $select_izvodjace=mysqli_query($conn, $q2);

        while($row2= mysqli_fetch_array($select_izvodjace))
        {
            $izvodjac2= $row2["izvodjacMaster"];
            $clanoviOveGrupe= $row2["clanoviOveGrupe"];

            $q3= "SELECT * FROM izvodjaci WHERE idIzvodjaci='{$this->idIzvodjac3}'";
            $select_izvodjace3=mysqli_query($conn, $q3);

            if(mysqli_num_rows($select_izvodjace3)>0)
            {
                while($row3= mysqli_fetch_array($select_izvodjace3))
                {
                    $izvodjac3= $row3["izvodjacMaster"];
                }
            }//end if($select_izvodjace3)

            $this->cleanIzvodjacMaster= konverzijaLatinica($this->izvodjacMaster);
            $this->cleanNazivAlbuma= konverzijaLatinica($this->nazivAlbuma);
            $izvodjac2= konverzijaLatinica($row2["izvodjacMaster"]);
            $izvodjac3= konverzijaLatinica($row2["izvodjacMaster"]); //????????????????????????
        }//end while
            
        if(!empty($izvodjac3))
        {
            echo $this->izvodjacMaster . ", " . $izvodjac2 . ", " . $izvodjac3;
            
        }else if(!empty($izvodjac2)){
            echo $this->izvodjacMaster . " & " . $izvodjac2;
        }else{
            echo $this->izvodjacMaster; 
        }//end if(!empty($izvodjac3))
    }//end izabraniIzvodjacNaslov()
    
    /********************************* Pozvana metoda u ovom fajlu u metodi sviAlbumiPocetna(), sviAlbumiEntitet(), poGodini, poGodinama() *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Prikaz svih singlova izabranog izvodjaca ako je unešen u bazu *********************************//
    public function prikazSinglovaIzvodjaca($artist3) 
    {
        global $conn;
        $q= "SELECT * 
        FROM singlovi 
        WHERE (
            singleFeat LIKE '%$artist3%' 
            OR singleIzvodjaci LIKE '%$artist3%'
        )
        AND EXISTS (
            SELECT 1 FROM izvodjaci 
            WHERE izvodjacMaster LIKE '%$artist3%' 
            OR nadimciIzvodjac LIKE '%$artist3%'
        ) ";
	
        $select_singl= mysqli_query($conn, $q);

        $q_izvodjMaster= "SELECT * FROM izvodjaci 
        WHERE izvodjacMaster LIKE '$artist3%'";
        $select_izvodjacMaster= mysqli_query($conn, $q_izvodjMaster);
        while($row= mysqli_fetch_array($select_izvodjacMaster))
        {

        
        $izvodjacMaster = $row['izvodjacMaster'] ?? null;
        
        ?>
        <div class="ostaliAlbumi">
            <div class="slikeAlbuma">
                <?php
                echo "<hr>";
                echo "<h3>Svi singlovi - <span class='boja'>". $izvodjacMaster ."</span></h3>";
                while($row= mysqli_fetch_array($select_singl))
                {
                    $idSinglovi= $row["idSinglovi"];
                    $singleFeat= $row["singleFeat"];
                    $singleIzvodjaci= $row["singleIzvodjaci"];
                    $singlNaziv= $row["singlNaziv"];
                    //echo $this->izvodjacMaster= $row["izvodjacMaster"];
                    
                    ?>
                    <div class="">
                        <p><a class="clickLink" href="singlovi.php?singl=<?php echo $idSinglovi; ?>" >
                            <?php echo "$singleIzvodjaci - $singlNaziv $singleFeat"; ?>
                        </a></p>
                    </div><!-- end .myCard -->
                    
                    <?php
                }//end while
                ?>
            </div><!-- end .slikeAlbuma -->
        </div><!-- end .ostaliAlbumi -->
        <hr>
        <?php
        }
    }// end function prikazSinglovaIzvodjaca()
    //********************************* Pozvana metoda u oalbumu.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

}// end class albumDetalji

