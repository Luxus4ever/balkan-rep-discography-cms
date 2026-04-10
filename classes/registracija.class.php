<?php

class registracija{

	//METODE SADRŽANE U OVOJ KLASI
	//__construct (prikaz forme za registraciju)

	protected $idDrzave2;
    protected $drzavaNaziv;
    protected $kodZemljeDugi;
    protected $zastava;
    protected $idEntiteti;
    protected $entitetNaziv;
    protected $entDrzava;
    protected $zastavaEnt;
    protected $kodEntiteta;

	//********************************* Metoda za prikaz forme za registraciju *********************************//
	public function __construct()
	{
		global $conn;
		?>
		<form method="POST" action="process/registracija.process.php" enctype="multipart/form-data" name="registracija" id="registracija">
					
			<fieldset class="border p-5 rounded">
				<legend class="w-auto px-2"><span class="podebljano bg-white text-danger">&nbsp; Obavezna polja &nbsp;</span></legend>


				<div class="col-auto">
					<div class="form-group">
						<input type="text"  class="form-control" name="username" id="username" placeholder="Korisničko ime" required>
					</div><!-- end .form-group -->
				</div><!-- end .col-auto --><br><br>

				<div class="col-auto">
					<div class="form-group">
						<input type="text"  class="form-control" name="ime" id="ime" placeholder="Ime" required>
					</div><!-- end .form-group -->
				</div><!-- end .col-auto --><br><br>

				<div class="col-auto">
					<div class="form-group">
						<input type="text"  class="form-control" name="prezime" id="prezime" placeholder="Prezime" required>
					</div><!-- end .form-group -->
				</div><!-- end .col-auto --><br><br>

				<div class="col-auto">
					<div class="form-group">
						<input type="email"  class="form-control" name="email" id="email" placeholder="Email" required>
					</div><!-- end .form-group -->
				</div><!-- end .col-auto --><br><br>
							
				<div class="custom-control custom-radio">	
					<input type="radio" class="form-check-input" name="pol" id="musko" value="Muško">
					<label class="form-check-label text-warning" for="musko">Muško&nbsp;</label><br>
				</div><!-- .custom-contorl .custom-radio -->

				<div class="custom-control custom-radio">
					<input type="radio" class="form-check-input" name="pol" id="zensko" value="Žensko" required>
					<label class="form-check-label text-warning" for="zensko">Žensko</label><br><br>
				</div><!-- .custom-contorl .custom-radio -->


				<label for="drzava" class="text-warning">Država</label><br>
				<div class="col-auto">
					<select class="form-control" name="drzava" id="drzava">
					<option class="form-control" disabled selected value="">Izaberite državu</option>
					<?php 
					$q= "SELECT * FROM drzave2";
					$select_drzavu= mysqli_query($conn, $q);

					while($row= mysqli_fetch_assoc($select_drzavu))
					{
						$this->idDrzave2= $row["idDrzave2"];
						$this->drzavaNaziv= $row["drzavaNaziv"];
						$this->kodZemljeDugi= $row["kodZemljeDugi"];
						$this->zastava= $row["zastava"];

						if($this->kodZemljeDugi==$this->drzavaNaziv){
							echo "<option value='{$this->kodZemljeDugi}' selected>$this->drzavaNaziv</option>";
						}else{
							echo "<option value='{$this->kodZemljeDugi}'>$this->drzavaNaziv </option>";
						}
						echo "";
					}//end while
					?>			
					</select> 
				</div><!-- end .col-auto --><br><br>

				<label for="entitet" class="hide text-warning">Entitet <span class="bg-danger text-light">&nbsp; (ako je iz BiH obavezno polje) &nbsp;</span></label><br>
                    <select class="form-control hide" name="entitet" id="entitet">
                        <option class="form-control" disabled selected value="">Izaberite entitet</option>
                        <?php 
                            $q= "SELECT * FROM entiteti";
                            $select_drzavu= mysqli_query($conn, $q);

                            while($row= mysqli_fetch_assoc($select_drzavu))
                            {
                                $this->idEntiteti= $row["idEntiteti"];
                                $this->entitetNaziv= $row["entitetNaziv"];
                                $this->entDrzava= $row["entDrzava"];
                                $this->zastavaEnt= $row["zastavaEnt"];
                                $this->kodEntiteta= $row["kodEntiteta"];

                                echo "<option value='{$this->kodEntiteta}'>$this->entitetNaziv </option>";
                            }                     
                            ?>
                    </select><br><br>
				
				<script>
                        // Dobijte referencu na oba <select> taga
                        const drzavaSelect = document.getElementById('drzava');
                        const entitetSelect = document.getElementById('entitet');

                        // Dodajte slušač na promenu vrednosti prvog <select> taga
                        drzavaSelect.addEventListener('change', function() {
                        // Ako je izabrana vrednost (BiH), prikažite drugi <select> tag
                        if (drzavaSelect.value === 'BIH') {
                            entitetSelect.style.display = 'block'; // Prikaži drugi <select> tag
                        } else {
                            entitetSelect.style.display = 'none'; // Sakrij drugi <select> tag
                        }
                        });
                    </script>

				<div class="col-auto">
					<label for="tip" class="text-warning">Tip korisnika</label><br>
					<label for="tip"><span class="text-white">Slušalac</span> - Ako ste samo slušalac muzike, običan korisnik</label><br>
					<label for="tip"><span class="text-white">Izvođač</span> - Ako ste izvođač muzike</label><br>
					<label for="tip"><span class="text-white">Izdavačka kuća</span> - Ukoliko ste predstavnik izdavačke kuće/Labela</label><br>
					<select  class="form-select" name="tipKorisnika" id="tip">
						<option disabled selected value="">Izaberite opciju</option>
						<option>Slušalac</option>
						<option>Izvođač</option>
						<option>Izdavačka kuća</option>
					</select> <br>
					<label for="tip"><span class="text-info">Nakon registracije ukoliko ste Izvođač ili Izdavačka kuća / Label pošaljite poruku administratoru</label><br>
				</div><!-- end .col-auto --><br><br>

				<input type="password"  class="form-control" name="password" id="password" placeholder="Šifra" required><br><br>
				<input type="password"  class="form-control" name="password2" placeholder="Ponovi šifru" required><br><br>
			</fieldset><br>

			<div class="col-auto">
				<label for="grad">Grad</label><br>
				<input type="text"  class="form-control" name="grad" id="grad" placeholder="Grad">
			</div><!-- end .col-auto --><br><br>

			<fieldset class="border p-5 rounded">
				<legend class="w-auto px-2"><span class="podebljano" style="color: yellow;">Unesite samo naziv profila (nakon imena sajta)</span></legend>
					
				<div class="col-auto">
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text">https://www.facebook.com/</div><!-- end .input-group-text -->
						</div><!-- end .input-group-prepend -->
					<input type="text"  class="form-control" name="facebookLog" id="facebookLog" placeholder="Facebook profil">
					</div><!-- .input-group mb-2 -->
				</div><!-- end .col-auto -->
				
					<br><br>

				<div class="col-auto">
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text">https://www.instagram.com/</div><!-- end .input-group-text -->
						</div><!-- end .input-group-prepend -->
						<input type="text"  class="form-control" name="instagramLog" id="instagramLog" placeholder="Instagram profil">
					</div><!-- .input-group mb-2 -->
				</div><!-- end .col-auto --><br><br>

				<div class="col-auto">
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text">https://www.x.com/</div><!-- end .input-group-text -->
						</div><!-- end .input-group-prepend -->
					<input type="text"  class="form-control" name="twitterLog" id="twitterLog" placeholder="X (Twitter) profil">
					</div><!-- .input-group mb-2 -->
				</div><!-- end .col-auto --><br><br>

				<div class="col-auto">
					<div class="input-group mb-2">
						<div class="input-group-prepend">
							<div class="input-group-text">https://www.tiktok.com/@</div><!-- end .input-group-text -->
						</div><!-- end .input-group-prepend -->
					<input type="text"  class="form-control" name="tiktokLog" id="tiktokLog" placeholder="Tik-Tok profil">
					</div><!-- .input-group mb-2 -->
				</div><!-- end .col-auto --><br><br>

				<div class="col-auto">
					<label for="sajtLog">Vaš sajt</label><br>
					<label for="sajt">Unesite pun naziv sajta sa početkom kao https:// ili kao www.</label><br>
					<input type="text"  class="form-control" name="sajtLog" id="sajtLog" placeholder="Vaš sajt">
				</div><!-- end .col-auto --><br><br>
			</fieldset><br>
			
			<div class="mb-3">
				<label for="profilnaSlika" class="form-label">Profilna slika (max veličina 2mb)</label>
				<input class="form-control" type="file" name="profilnaSlika" id="profilnaSlika">
			</div><!-- end .mb-3 --><br><br>
					
			<div class="col-auto">
				<input type="submit"  class="btn btn-primary btn-sm" name="posalji" value="Pošalji">
			</div><!-- end .col-auto -->
			<br>
			<div class="col-auto">
				<input type="reset"  class="btn btn-danger btn-sm" value="Reset">
			</div><!-- end .col-auto -->
		</form>
	<?php
	}//end __construct()

	//********************************* Metoda pozvana u fajlu registracija.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

}//end class registracija