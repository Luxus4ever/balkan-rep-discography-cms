<?php
require_once "config/bootstrap.php";
include "header.php";
include "classes/singlovi.class.php";
include_once "classes/streams.class.php";
include_once "functions/master.func.php";

headerPutanja();

@$sesId = (int)$_SESSION['idKorisnici'];
@$sesStatusKorisnika= (int) $_SESSION["statusKorisnika"];
@$sesUsername= (string) $_SESSION["username"];
?>
<div id="wrapper">
	<div class="bojaTekstovi">
		<article id="pocetnaUvod">
			<h1 class="naslov-centar">O nama</h1>
			<div class="uvodTekst">
				<p>Ovaj sajt je kreiran sa idejom da mi posluži kao referenca na određene web projekte, iako sam godinama imao ideju da napravim nešto slično. Kao što možete pretpostaviti ovaj kompletan projekat od početka do kraja je izgurala jedna osoba koja je dodala i 545 albuma i 8767 naziva pjesama (za početak) uglavnom iz svoje privatne kolekcije koja se skupljala godinama.</p> 
				
				<p>Kako je sam projekat odmicao sa radom i bio bliže realizaciji ideje, stalno sam dodavao nove funkcionalnosti, proširio sam svoju viziju da ovo postane jedan oblik društvene mreže gde korisnici mogu da komuniciraju međusobno vezano za muziku, ili čak da eventualno razgovaraju i sa samim (verifikovanim) izvođačima. Ipak same članove neće biti moguće pronaći pomoću pretrage, a kako da ih pronađete to ću prepustiti vama da otkrijete.</p>

				<p>Sa druge strane ovaj projekat jeste nastao i iz ljubavi prema hip-hopu koji sam mnogo više slušao u mlađim danima, a u jednom dijelu života mogu slobodno da kažem da mi je Hip-Hop spasao i život. Takođe mislim da je potrebna da postoji arhiva albuma i izvođača. Za sada je predviđeno samo za hip-hop ali nije isključeno da u budućnosti otvorim mogućnost i za ostale žanrove muzike kao i da proširim mogućnost dodavanja arhive na neke druge države.</p>

				<p>Ovaj sajt mi se čini nekako kao logičan nastavak priče <i><b>Balkan Hip-Hop radija</b></i> koji je pokrenut 01.03.2011., a ono što je interesatno da je ovaj sajt predstavljen 15 godina kasnije, odnosno sredinom Marta/Ožujka 2026. godine. Sam radio je pokrenut sa idejom da se promoviše domaća Hip-Hop muzika tj. sa prostora bivše Jugoslavije jer su jezici kojim se govori na tim prostorima veoma slični i lako razumljivi, kao i cjelokupne ekipe koja je činila taj radio kroz godine. Ovo nije neki novi pokušaj bratstva i jedinstva ovo je samo pokušaj da ostanu upamćena Rep izdanja kao i kvalitetni izvođači na ovim prostorima zbog sličnosti jezika. </p>
				
				<p>Na sajtu nije omogućeno preuzimanje (download) muzike i nemamo to u planu da uradimo, a ako budemo uradili nešto slično jedina opcija će biti omogućena kao kupovina muzike, gde će to što kupite biti vaše, bez mjesečne pretplate kao na nekim striming servisima, ali o tome će biti potrebno da se izjasne i sami izvođači.</p>

				<p>Da bi ste podržali naš rad i pomogli održavanju i opstanku ovog sajta možete nam platiti barem jednu "virtuelnu" kafu (ili više) klikom na sličicu ispod ili u donjem desnom uglu ekrana sa slikom šoljice kafe</p>

				<div class="sredina"> <a href="https://www.buymeacoffee.com/balkanhiphopr" target="_blank"><img src="https://cdn.buymeacoffee.com/buttons/v2/default-violet.png" alt="Buy Me A Coffee" style="height: 60px !important;width: 217px !important;" ></a>
    			</div><!-- Kraj kafa -->

				<br><br>
				<h2 class="naslov-centar">Kontakt</h2>

				<?php 
				if(empty($sesId) || empty($sesUsername) || empty($sesStatusKorisnika))
					{
						?>
						<p>Da bi nas kontaktirali u vezi saradnje ili bilo kog drugog pitanja, na sajtu postoji opcija da se pošalje poruka administratorima, ali je to vidljivo samo registrovanim korisnicma. Takođe nam možete poslati e-mail koji je vidljiv u ovom odeljku, ali samo registrovanim korisnicima.</p>
						<?php
					}else{
						?>
						<p>Da bi nas kontaktirali putem e-maila možete nam pisati na adresu "kontakt@diskografija.com".
						<?php
					}
					?>
			</div><!-- end .uvod-tekst -->
		</article>
		<?php 
		
		?>
   </div><!-- end .bojaTekstovi -->
   <!-- Početak šoljice za kafu u donjem desnom uglu ekrana -->
    <script data-name="BMC-Widget" data-cfasync="false" src="https://cdnjs.buymeacoffee.com/1.0.0/widget.prod.min.js" data-id="balkanhiphopr" data-description="Support me on Buy me a coffee!" data-message="" data-color="#BD5FFF" data-position="Right" data-x_margin="18" data-y_margin="18"></script>
    <!-- Kraj šoljice za kafu u donjem desnom uglu ekrana -->
	<?php
include "footer.php";
footerPutanja();