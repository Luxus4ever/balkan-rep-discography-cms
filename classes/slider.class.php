<?php

class Slider
{
	//METODE SADRŽANE U OVOJ KLASI
	//sliderPocetna (prikaz slajdera na početnoj)

	public $albumId;
	public $idIzvodjacAlbumi;
	public $nazivAlbuma;
	public $godinaIzdanja;
	public $izdavac;
	public $slikaAlbuma;
	public $izvodjacMaster;
	public $idIzvodjac2;
	public $idIzvodjac3;
	public $drzavaAlbumi;
	public $entitetAlbumi;
	public $tacanDatumIzdanja;
	public $nadimci;
	public $idAlbum;

	public $cleanIzvodjacMaster;
	public $cleanNazivAlbuma;


	//********************************* Metoda za prikaz slajdera na početnoj *********************************//

	public function sliderPocetna()
	{
		global $conn;
		?>
		<h1 class="naslov-centar">Top 10 najbolje ocijenjenih albuma</h1> 

		<?php
		$q= "SELECT idIzvodjacAlbumi, idIzvodjac2, idIzvodjac3, izvodjacMaster, albumId, nazivAlbuma, slikaAlbuma, godinaIzdanja, idAlbum, albumId, COUNT(albumId) AS brojGlasova, AVG(ratedIndex) AS total FROM ocjene 
		JOIN albumi ON albumi.idAlbum=ocjene.albumId 
		JOIN izvodjaci ON izvodjaci.idIzvodjaci=albumi.idIzvodjacAlbumi
		GROUP BY ocjene.albumId ORDER BY total DESC LIMIT 10";
        $select_album= mysqli_query($conn, $q);
        while($row= mysqli_fetch_assoc($select_album))
        {
            $this->slikaAlbuma= $row["slikaAlbuma"];
            $this->nazivAlbuma= $row["nazivAlbuma"];
            $this->idAlbum= $row["idAlbum"];
			$this->idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
			$this->albumId= $row["albumId"];
			$this->idIzvodjac2= $row["idIzvodjac2"];
			$this->idIzvodjac3= $row["idIzvodjac3"];
			$this->izvodjacMaster= $row["izvodjacMaster"];
			$this->godinaIzdanja= $row["godinaIzdanja"];

			$this->cleanIzvodjacMaster= konverzijaLatinica($this->izvodjacMaster);
			$this->cleanNazivAlbuma= konverzijaLatinica($this->nazivAlbuma);
			$this->cleanIzvodjacMaster= konverzijaLatinica(removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($this->cleanIzvodjacMaster))));
			$this->cleanNazivAlbuma= konverzijaLatinica(removeSerbianLetters(cleanAlbum($this->cleanNazivAlbuma)));

            $ocjena= $row["total"];
            $brojGlasova= $row["brojGlasova"];
            $prosijek= round($ocjena / $brojGlasova,2);
            ?>
			
            <div class="okvir">
                <div id="slajd" class="w30 slide">
                	<a href="oalbumu.php?izv=<?php echo $this->idIzvodjacAlbumi."&album=".$this->albumId."&naziv=".$this->cleanIzvodjacMaster."-".$this->cleanNazivAlbuma; ?>">
						<img id="sliderPicture" src="images/albumi/<?php echo $this->slikaAlbuma; ?>" alt="alternative">
					</a>
                </div><!-- end #slajd-->

				<div class="boja sliderAlbumText w30">
					<p><?php echo $this->izvodjacMaster . " - " . $this->nazivAlbuma; ?></p>
					<p><?php echo $this->godinaIzdanja; ?></p>
					<div class="sredina">
						<h3>Trenutna ocijena: <span id='trenOcjena'><?php echo round($ocjena,2); ?></span></h3>
					</div><!-- end .sredina-->
				</div><!-- end .boja sliderAlbumText .w30-->
            </div><!-- end .okvir-->
			
			<div class="dots sredina"></div><!-- end .dots-->
			<?php
		}//end while loop
		?>
			<button id="sliderArrowLeft" class="slider__btn slider__btn--left"><img src="images/website/arrow-left.png" alt="left" title="left"></button>
			<button id="sliderArrowRight" class="slider__btn slider__btn--right"><img src="images/website/arrow-right.png" alt="right" title="right"></button>

			<script src="js/sliderscript.js"></script>
		<?php
	}//end function sliderPocetna()

	//********************************* Metoda pozvana u index.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

}//end class Slider