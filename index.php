<?php
require_once "config/bootstrap.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "classes/detaljiAlbum.class.php";
include "functions/master.func.php";
include "header.php";
include "classes/slider.class.php";

headerPutanja();
?>


<div id="wrapper" class="">
	<article class="slider-pocetna">
		<?php 
		$sldr= new Slider();
		$sldr->sliderPocetna();
		?>
	</article><!-- kraj .slider-pocetna -->

	<section id="uvod" class="">
		<article id="pocetnaUvod" class="">
			<h1 class="naslov-centar">Par uvodnih riječi</h1>

			<div class="uvod-tekst">
			<p>
				<br>Ovaj projekat je nastao kao prirodni nastavak <strong><a class="clickLink" href="https://balkanhiphopradio.com" target="_blank">Balkan Hip-Hop radio-a</a></strong> iako je izrada samog projekta krenula iz sasvim drugog razloga. Projekat je ogroman i sa tehničke strane izgurala ga jedna osoba, ali je nemoguće da jedna osoba popuni svu diskografiju. Očekujemo od vas da doprinesete unošenjem detaljnih podataka, kao i dodavanjem novih albuma.
			</p>
			</div><!-- kraj .uvod-tekst -->
		</article><!-- kraj #pocetnaUvod -->
	</section><!-- kraj #uvod -->

	<section class="dodatni-tekst-uvod">
		<div class="uvod-tekst">
			<p>Nadamo se da će izvođači i izdavačke kuće doprineti razvoju ovog sajta koji će se nadograđivati novim idejama. <br>Širite dobru muziku.</p><br>
			<h4 class="citatPocetna"><em>Od Repa nisam uz'o rePa al' znam da nešto to vredi!</em> <br><a class="clickLink2" href="tekstovi.php?tekst=1898">Ziplok feat. Furio Đunta i Tijana Zvezdanović - Moram da uspem</a></h4>
		</div><!-- kraj .uvod-tekst -->
	</section>

	<!-- Prikaz albuma -->
	<div class="albumPrikaz">
	<?php 
	nazivDrzave();
	?>
	</div> <!-- kraj albumPrikaz -->
</div><!-- kraj #wrapper -->
<?php
include "footer.php";
footerPutanja();

