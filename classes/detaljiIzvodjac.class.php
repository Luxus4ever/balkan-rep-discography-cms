<?php

class izvodjacDetalji{

    //METODE SADRŽANE U OVOJ KLASI
    //biografija (prikazuje biografiju izvođača)
    //clanoviGrupe (metoda za razdvajanje teksta, ako ima više članova grupe)

    public $idIzvodjaci;
    public $izvodjacMaster;
    public $ime;
    public $prezime;
    public $slikaIzvodjac;
    public $biografija;
    public $nazivDrzave;
    public $nadimci;
    public $clanoviOveGrupe;
    public $tipIzvodjaca;
    protected $izvodjacFacebook;
    protected $izvodjacInstagram;
    protected $izvodjacSajt;
    protected $entitetIzvodjac;

    protected $cleanIzvodjacMaster;
    protected $cleanNazivAlbuma;

    public $albumId;
    public $idIzvodjacAlbumi;
    public $nazivAlbuma;
    public $godinaIzdanja;
    public $izdavac;
    public $slikaAlbuma;
    public $drzavaAlbumi;
    public $entitetAlbumi;

    //********************************* Metoda koja prikazuje biografiju izvođača *********************************//
    public function biografija($artistId)
    {
        
        global $conn;
        $q= "SELECT * FROM izvodjaci JOIN drzave ON drzave.idDrzave=izvodjaci.drzavaIzvodjac 
        LEFT JOIN entiteti ON entiteti.kodEntiteta=izvodjaci.entitetIzvodjac
        WHERE idIzvodjaci=$artistId";
        $select_artist= mysqli_query($conn, $q);
        include "functions/master.func.php";

        
        while($row= mysqli_fetch_assoc($select_artist))
        {
            $this->idIzvodjaci= $row["idIzvodjaci"];
            $this->izvodjacMaster= $row["izvodjacMaster"];
            $this->ime= $row["ime"];
            $this->prezime= $row["prezime"];
            $this->slikaIzvodjac= $row["slikaIzvodjac"];
            $this->biografija= nl2br((string)$row["biografija"]);
            $this->tipIzvodjaca= $row["tipIzvodjaca"];
            $this->izvodjacFacebook= $row["izvodjacFacebook"];
            $this->izvodjacInstagram= $row["izvodjacInstagram"];
            $this->izvodjacSajt= $row["izvodjacSajt"];
            /*$album= $row["albumId"];
            $izvodjac= $row["izvodjacId"];*/
            $this->nazivDrzave= $row["nazivDrzave"];
            $this->nadimci= $row["nadimciIzvodjac"];
            $this->clanoviOveGrupe= $row["clanoviOveGrupe"];
            $this->entitetIzvodjac= $row["entitetNaziv"];
            $idIzvodjacAlbumi= $this->idIzvodjaci;

            
            ?>
			<h1 class="drzavaTekstovi"><span class="boja">Država: </span><a href="drzava.php?nazivdrzave=<?php echo $this->nazivDrzave; ?>"><span class="clickLink"><?php echo $this->nazivDrzave; ?></a></h1>
            <?php
            if(!empty($this->entitetIzvodjac)){
                ?>
                <h4 class="drzavaTekstovi"><span class="boja">Entitet: </span><a href="drzava.php?ent=<?php echo $this->entitetIzvodjac; ?>"><span class="clickLink"><?php echo $this->entitetIzvodjac; ?></a></h4>
                <?php
            }
            
            echo "<h2 class='tekstNaziv'>" . $this->izvodjacMaster . "</h2><br>"; 

            if(!empty($this->ime || $this->prezime)){
                echo "<h2><span class='tekstNaziv'>Ime i prezime: </span>" . $this->ime . " " . $this->prezime . "</h2>";
            }else{echo "";}
            if(!empty($this->clanoviOveGrupe)){
               $this->clanoviGrupe($this->izvodjacMaster, $this->tipIzvodjaca, $this->clanoviOveGrupe);
            }else{echo "";}
            if(!empty($this->nadimci)){
                echo "<h3><span class='tekstNaziv'>Ostala imena: </span>" . $this->nadimci . "</h3><br>";
            }else{echo "";}
            if(!empty($this->slikaIzvodjac)){
                echo "<div class='slikaIzvodjac'><img src='images/izvodjaci/{$this->slikaIzvodjac}'></div>"; 
            }else{echo "";}
            echo "<br><br>";

            if(empty($this->biografija)){
                echo "<p>Biografija:</p><h2>Nema dostupnih informacija!</h2>";
            }else{
                echo "<p class='o-izvodjacu-tekst'>$this->biografija</p>";
            }

            
            if(!empty($this->izvodjacFacebook))
            {
                ?>
                <span class="fa-stack">
                    <a href="<?php echo "https://www.facebook.com/".$this->izvodjacFacebook; ?>" target="_blank">
                        <i class="fa fa-circle fa-2x" aria-hidden="true"></i>
                        <i class="fab fa-facebook-f fa-stack-1x"></i>
                    </a>
                </span>
                <?php
            }else{echo "";}

            if(!empty($this->izvodjacInstagram))
            {
                ?>
                <span class="fa-stack">
                    <a href="<?php echo "https://www.instagram.com/".$this->izvodjacInstagram; ?>" target="_blank">
                        <i class="fas fa-circle fa-stack-2x"></i>
                        <i class="fab fa-instagram fa-stack-1x"></i>
                    </a>
                </span>
            <?php
            }else{echo "";}

            ?>
            <p><a href="<?php echo $this->izvodjacSajt; ?>" target="_blank" class="clickLink"><?php echo $this->izvodjacSajt; ?></a></p>
            <?php

            sviAlbumi($this->idIzvodjaci, $this->izvodjacMaster);

        }
        
    }//end biografija()
    //********************************* Pozvana metoda u izvodjac.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //*********************************Metoda za razdvajanje ako ima više izdavača *********************************/
    public function clanoviGrupe($izvodjacMaster, $tipIzvodjaca, $clanoviOveGrupe)
    {
        global $conn;
        $nizIzd= explode(", ", $clanoviOveGrupe);
        $niz4= explode(" & ", $clanoviOveGrupe);

        if($tipIzvodjaca=="solo")
        {
            echo "<h4><span class='bojaClanGrupe'>$this->izvodjacMaster je član jedne ili više grupa: </span>"; 
            foreach($nizIzd as $ime)
            {
                $idIzv = getIzvodjacIdByMaster($ime);
                ?>
                <a href="izvodjac.php?idIzv=<?php echo ($idIzv ?? '');?>&izvodjac=<?php echo str_replace(" ", "+", removeSpecialLetters($ime)); ?>"><?php echo $ime; ?></a><?php
                if(next($nizIzd))
                {
                    echo ", ";
                }// Dodaje zarez (tj. neki simbol), nakon svakog člana niza, osim zadnjeg
            }// end foreach loop
        }else{
            echo "<h4><span class='bojaClanGrupe'>Članovi ove grupe su: </span>"; 
            foreach($nizIzd as $ime)
            {
                $idIzv = getIzvodjacIdByMaster($ime);
                ?>
                <a class="clickLink" href="izvodjac.php?idIzv=<?php echo ($idIzv ?? '');?>&izvodjac=<?php echo str_replace(" ", "+", removeSpecialLetters($ime)); ?>"><?php echo $ime; ?></a><?php
                if(next($nizIzd))
                {
                    echo ", ";
                }// Dodaje zarez (tj. neki simbol), nakon svakog člana niza, osim zadnjeg
            }// end foreach loop
        }//end if else()
        echo "</h4><br>";
    }// end function clanoviGrupe()
    //******************************* Pozvana metoda u ovom fajlu u metodi biografija() *******************************//

    //--------------------------------------------------------------------------------------------------------------------------------
    
}//end class izvodjacDetalji