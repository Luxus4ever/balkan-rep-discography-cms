<?php

class detaljiLabel{

    //METODE SADRŽANE U OVOJ KLASI
    //izdavaci (svi albumi izabranog izdavača)
    //aboutLabel (prikaz tekst i slike o izdavaču)
    //aboutKategorije (prikaz teksta o kategorijama)
    //albumiKategorije (svi albumi po jednoj kategoriji/žanru)

    
    public $albumId;
    public $idIzvodjacAlbumi;
    public $izvodjacMaster;
    public $nazivAlbuma;
    public $godinaIzdanja;
    public $izdavac;
    public $slikaAlbuma;
    public $drzavaAlbumi;
    public $entitetAlbumi;

    protected $label;
    protected $cleanIzvodjacMaster;
    protected $cleanNazivAlbuma;

    protected $idIzdavaci;
    protected $izdavaciNaziv;
    protected $izdavaciOpis;
    protected $logoLabel;

    protected $idKategorijeAlbuma;
    protected $nazivKategorijeAlbuma;
    protected $opisKategorijeAlbuma;

    public $izdavaciId;

    //******************************* Metoda za prikaz svih albuma izabranog izdavača *******************************//
    public function izdavaci($izdavacId)
    {
        global $conn;
        include_once "./functions/master.func.php";
       
        $q= "SELECT * FROM albumi 
        JOIN izvodjaci ON izvodjaci.idIzvodjaci = albumi.idIzvodjacAlbumi 
        JOIN albumi_izdavaci ON albumi.idAlbum = albumi_izdavaci.idAlbum 
        JOIN izdavaci ON izdavaci.idIzdavaci = albumi_izdavaci.idIzdavaci
        WHERE izdavaci.idIzdavaci = '{$izdavacId}' ORDER BY godinaIzdanja";
        $select_izdavac=$conn->query($q);
        
        if(mysqli_num_rows($select_izdavac)>0)
        {
            ?>
            <div class="slikeAlbumaPregled">
                <?php
                while ($row= mysqli_fetch_array($select_izdavac))
                {
                    
                    $this->albumId= $row["idAlbum"];
                    $this->idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
                    $this->nazivAlbuma= $row["nazivAlbuma"];
                    $this->godinaIzdanja= $row["godinaIzdanja"];
                    $this->slikaAlbuma= $row["slikaAlbuma"];
                    $this->drzavaAlbumi= $row["drzavaAlbumi"];
                    $this->entitetAlbumi= $row["entitetAlbumi"];
                    $this->izvodjacMaster= $row["izvodjacMaster"];

                    $this->cleanIzvodjacMaster= konverzijaLatinica($this->izvodjacMaster);
                    $this->cleanNazivAlbuma= konverzijaLatinica($this->nazivAlbuma);
                    $this->cleanIzvodjacMaster= removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($this->cleanIzvodjacMaster)));
                    $this->cleanNazivAlbuma= removeSerbianLetters(cleanAlbum($this->cleanNazivAlbuma));
                    ?>
                    <div class="myCard myCard-size1">
                        <a href="oalbumu.php?izv=<?php echo $this->idIzvodjacAlbumi."&album=".$this->albumId."&naziv=".$this->cleanIzvodjacMaster."-".$this->cleanNazivAlbuma; ?>" >
                            <img loading="lazy" src="images/albumi/<?php echo $this->slikaAlbuma; ?>" alt="<?php echo $this->slikaAlbuma; ?>" title="<?php echo $this->nazivAlbuma; ?>">
                            <div class="myCard-body">
                                <h5 class="myCard-title"><?php echo $this->izvodjacMaster . " - " . $this->nazivAlbuma; ?></h5>
                                <p class="myCard-text1"><?php echo $this->godinaIzdanja; ?></p>
                            </div><!-- end .myCard-body -->   
                        </a>
                    </div><!-- end .myCard -->
                    <?php
                }//end while
                ?>
            </div><!-- end .slikeAlbuma -->
            <?php
        }else{
            echo "Nema podataka";
        }//end if else()
    }// end function izdavaci()

    //********************************* Metoda pozvana u label.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda za prikaz logoa i teksta o izdavaču *********************************/

    public function aboutLabel($izdavacId)
    {
        global $conn;
        $q= "SELECT * FROM izdavaci WHERE idIzdavaci= '{$izdavacId}'";
        $select_izdavac=$conn->query($q);
        $row= mysqli_fetch_array($select_izdavac);
        $this->label= $row["izdavaciNaziv"];
        $this->logoLabel= $row["izdavaciLogo"];
        $this->izdavaciOpis= nl2br($row["izdavaciOpis"]);
        ?>
        <div class="sredina slikaIzvodjac">
            <img src="images/labels/<?php echo $this->logoLabel; ?>" alt="<?php echo $this->label; ?>" title="<?php echo $this->label; ?>">
        </div><!-- end .sredina -->
        <div class="sredina">
            <p class="o-izvodjacu-tekst tekstP"><?php echo $this->izdavaciOpis; ?></p>
        </div><!-- end .sredina -->
        <?php
    }//end aboutLabel()

    //******************************* Pozvana metoda u fajlu label.php *******************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda za prikaz teksta o kategoriji *********************************/

    public function aboutKategorija($kategorijaId)
    {
        global $conn;
        $q= "SELECT * FROM kategorije_albuma WHERE idKategorijeAlbuma= '{$kategorijaId}'";
        $select_kategorija=$conn->query($q);
        $row= mysqli_fetch_array($select_kategorija);
        $this->idKategorijeAlbuma= $row["idKategorijeAlbuma"];
        $this->nazivKategorijeAlbuma= $row["nazivKategorijeAlbuma"];
        $this->opisKategorijeAlbuma=$row["opisKategorijeAlbuma"];
        ?>
        <div class="sredina slikaIzvodjac">
            
        </div><!-- end .sredina -->
        <div class="sredina">
            <p class="o-izvodjacu-tekst tekstP"><?php echo $this->opisKategorijeAlbuma; ?></p>
        </div><!-- end .sredina -->
        <?php
    }//end aboutKategorija()

    //******************************* Pozvana metoda u fajlu zanrovi.php *******************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //******************************* Metoda za prikaz svih albuma izabrane kategorije *******************************//
    public function albumiKategorije($idKategorije)
    {
        global $conn;
        include_once "./functions/master.func.php";

        $q= "SELECT * FROM albumi 
        JOIN izvodjaci ON izvodjaci.idIzvodjaci = albumi.idIzvodjacAlbumi 
        JOIN albumi_kategorije ON albumi_kategorije.idAlbum=albumi.idAlbum
        JOIN kategorije_albuma ON kategorije_albuma.idKategorijeAlbuma=albumi_kategorije.idKategorijeAlbuma
        WHERE kategorije_albuma.idKategorijeAlbuma = '{$idKategorije}' ORDER BY godinaIzdanja";

        $select_izdavac=$conn->query($q);
        
        if(mysqli_num_rows($select_izdavac)>0)
        {
            ?>
            <div class="slikeAlbumaPregled">
                <?php
                while ($row= mysqli_fetch_array($select_izdavac))
                {
                    $this->albumId= $row["idAlbum"];
                    $this->idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
                    $this->nazivAlbuma= $row["nazivAlbuma"];
                    $this->godinaIzdanja= $row["godinaIzdanja"];
                    $this->slikaAlbuma= $row["slikaAlbuma"];
                    $this->drzavaAlbumi= $row["drzavaAlbumi"];
                    $this->entitetAlbumi= $row["entitetAlbumi"];
                    $this->izvodjacMaster= $row["izvodjacMaster"];

                    $this->cleanIzvodjacMaster= konverzijaLatinica($this->izvodjacMaster);
                    $this->cleanNazivAlbuma= konverzijaLatinica($this->nazivAlbuma);
                    $this->cleanIzvodjacMaster= removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($this->cleanIzvodjacMaster)));
                    $this->cleanNazivAlbuma= removeSerbianLetters(cleanAlbum($this->cleanNazivAlbuma));
                    ?>
                    <div class="myCard myCard-size1">
                        <a href="oalbumu.php?izv=<?php echo $this->idIzvodjacAlbumi."&album=".$this->albumId."&naziv=".$this->cleanIzvodjacMaster."-".$this->cleanNazivAlbuma; ?>" >
                            <img loading="lazy" src="images/albumi/<?php echo $this->slikaAlbuma; ?>" alt="<?php echo $this->slikaAlbuma; ?>" title="<?php echo $this->nazivAlbuma; ?>">
                            <div class="myCard-body">
                                <h5 class="myCard-title"><?php echo $this->izvodjacMaster . " - " . $this->nazivAlbuma; ?></h5>
                                <p class="myCard-text1"><?php echo $this->godinaIzdanja; ?></p>
                            </div><!-- end .myCard-body -->   
                        </a>
                    </div><!-- end .myCard -->
                    <?php
                }//end while
                ?>
            </div><!-- end .slikeAlbuma -->
            <?php
        }else{
            echo "Nema podataka";
        }//end if else()
    }// end function izdavaci()

    //********************************* Metoda pozvana u label.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

}//end class detaljiLabel