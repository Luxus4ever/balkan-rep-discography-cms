<?php

class insertArtist{

    //METODE SADRŽANE U OVOJ KLASI:
    //dodajIzvodjaca (dodaje novog izvođača)
    //dodajSlikuIzvodjaca (dodaje sliku izvođača)

    protected $izvodjacMaster;
    protected $nadimciIzvodjac;
    protected $nadimci2;
    protected $idDrzave;
    protected $drzavaNaziv;
    protected $kodZemljeDugi;
    protected $zastava;
    protected $idEntiteti;
    protected $entitetNaziv;
    protected $entDrzava;
    protected $zastavaEnt;
    protected $kodEntiteta;

    protected $ime;
    protected $prezime;
    protected $slikaIzvodjac;
    protected $tipIzvodjaca;
    protected $drzavaIzvodjac;
    protected $entitetIzvodjac;
    protected $nadimci;
    protected $clanGrupe;
    protected $biografija;

    protected $lastInsertArtistId;

    protected $putanja;
    protected $skeniraj;
    protected $imeSlike;
    protected $ukloniEkstenziju;
    protected $ekstenzija;
    protected $vrijeme;
    protected $slikaVrijeme;
    protected $size;
    protected $maxVelicinaSlike;
    protected $minVelicinaSlike;
    protected $whitelist;


    //********************************* Metoda za dodavanje novih izvodjaca  *********************************//
    public function dodajIzvodjaca()
    {

        include_once "imageUploader.class.php";
        $uploader = new ImageUploader();
   
        global $conn;
        ?>
        
        <form class="visina slikeAlbumaPregled sredina">
            <div class="form-group col-md-5 mx-auto">
                <h3 class="text-warning">Pogledajte prije dodavanja da li izvođač postoji u listi već dodatih izvođača</h3>
                <select class="form-control" name="sviIzvodjaci" id="sviIzvodjaci">
                    <option id="prazni" value="" disabled selected>Pogledaj izvođače</option>
                    <?php
                    $q_sviIzvodjaci="SELECT * FROM izvodjaci ORDER BY izvodjacMaster";
                    $select_sviIzvodjaci= mysqli_query($conn, $q_sviIzvodjaci);
                    while($row=mysqli_fetch_array($select_sviIzvodjaci)){
                        $this->izvodjacMaster= $row["izvodjacMaster"];
                        $this->nadimciIzvodjac= $row["nadimciIzvodjac"];

                        $this->nadimci2= $this->nadimciIzvodjac= "" ? "" : $this->nadimciIzvodjac;
                        ?>
                        <option><?php echo $this->izvodjacMaster . " (" . $this->nadimci2 . ")"; ?></option>               
                        <?php
                    }
                ?>
                </select>    
        </form>
        <br><hr class="hrLinija">

        <div class="sredina">
        <form method="POST" action="" enctype="multipart/form-data" name="izmjenaBiografije" id="izmjenaBiografije">
            <br>
            <h4 class="text-light bg-danger">&nbsp; Ukoliko nema, nastavite sa unosom novog izvođača &nbsp; </h4><br>

            <label for="nazivIzvodjaca" class="text-warning"><strong>Naziv izvođača ili grupe <br>Ukoliko je mixtape izaberite "Razni Izvođači" <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong></label><br>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Naziv izvođača</span>
                </div><!-- end .input-group-prepend -->
                <input type="text" class="form-control" name="nazivIzvodjaca" class="form-control form-control-sm text-danger" value="">
            </div><!-- end .input-group --><br><br>


            <fieldset>
                <legend class="text-warning"><strong>Tip izvođača <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong></legend>
            <div class="form-check">
            <input type="radio" class="form-check-input" name="tipIzvodjaca" id="solo" value="solo">
            <label for="tipIzvodjaca" class="form-check-label text-white">Solo izvođač</label>
            </div><!-- end .input-group-prepend -->
            <br>
            
            <div class="form-check">
            <input type="radio" class="form-check-input" name="tipIzvodjaca" id="grupa" value="grupa">
            <label for="tipIzvodjaca" class="form-check-label text-white">Grupa</label>
            </div><!-- end .input-group -->
            <br>
            </fieldset>
            <br><br>
        

            <label for="dodajSlikuIzvodjaca"  class="text-warning"><strong>Slika Izvođača</strong></label><br>
            <input type="file" class="btn btn-light" name="dodajSlikuIzvodjaca"><br><br>
            
            <div class="border p-5 rounded">
                <label for="ime" class="text-warning"><strong>&nbsp; Samo ako je solo izvođač &nbsp;</strong></label><br><br>
                <label for="ime" class="text-warning"><strong>Ime</strong></label><br>
                <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Ime</span>
                </div><!-- end .input-group-prepend -->
                <input type="text" class="form-control text-danger" name="ime" value="">
                </div><!-- end .input-group --><br><br>

                <label for="prezime" class="text-warning"><strong>Prezime</strong></label><br>
                <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Prezime</span>
                </div><!-- end .input-group-prepend -->
                <input type="text" class="form-control text-danger" name="prezime" value="">
                </div><!-- end .input-group --><br><br>
            </div><!-- end .border p-5 rounded-->
            <br>

            <label for="nadimci" class="text-warning"><strong>Ostala nazivi / aliases (ako ima)</strong></label><br>
            <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Nadimci</span>
            </div><!-- end .input-group-prepend -->
            <input type="text" name="nadimci" class="form-control" value="" placeholder="npr. Skajvikler, Sky Wikluh, Vikler Skaj">
            </div><!-- end .input-group --><br><br>

            <label for="clanGrupe" class="text-warning"><strong>Član grupe / članovi grupe</strong></label><br>
            <label for="clanGrupe" class="text-warning">Ako je solo izvođač član jedne ili više grupa npr. 
                <span class="text-light"><i>Bad Copy, 43zla</i></span></label><br>
            <label for="clanGrupe" class="text-warning">Ako je grupa dodati ko su članovi te grupe npr.
                <span class="text-light"><i>Rista, Buka</i></span></label><br>
            <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Član grupe</span>
            </div><!-- end .input-group-prepend -->
            <input type="text" class="form-control" name="clanGrupe" value="">
            </div><!-- end .input-group --><br><br>

            <label for="drzava" class="text-warning"><strong>Država (koje mu je prvo/osnovno tržište) <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong></label><br>
            <select class="form-control" name="drzava" id="drzava">
                <option class="form-control" disabled selected value="">Izaberite državu</option>
                <?php 
                    $q= "SELECT * FROM drzave";
                    $select_drzavu= mysqli_query($conn, $q);

                    while($row= mysqli_fetch_assoc($select_drzavu))
                    {
                        $this->idDrzave= $row["idDrzave"];
                        $this->drzavaNaziv= $row["nazivDrzave"];
                        $this->kodZemljeDugi= $row["kodZemljeDugi"];
                        $this->zastava= $row["zastavaDrzave"];
                        
                        echo "<option value='{$this->idDrzave}'>$this->drzavaNaziv </option>";
                    }                     
                    ?>
            </select>
            <br><br>

            <label for="entitet" class="hide text-warning"><strong>Entitet (ako je iz BiH obavezno polje) <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</span></strong></label><br>
            <select class="form-control hide" name="entitet" id="entitet">
                <option class="form-control" disabled selected value="">Izaberite entitet</option>
                <?php 
                    $q= "SELECT * FROM entiteti";
                    $select_drzavu= mysqli_query($conn, $q);

                    while($row= mysqli_fetch_assoc($select_drzavu))
                    {
                        $idEntiteti= $row["idEntiteti"];
                        $entitetNaziv= $row["entitetNaziv"];
                        $entDrzava= $row["entDrzava"];
                        $zastavaEnt= $row["zastavaEnt"];
                        $kodEntiteta= $row["kodEntiteta"];

                        echo "<option value='{$kodEntiteta}'>$entitetNaziv </option>";
                    }                     
                    ?>
            </select><br><br>

            <script>
                // Добијте референцу на оба <select> тага
                const drzavaSelect = document.getElementById('drzava');
                const entitetSelect = document.getElementById('entitet');

                // Додајте слушач на промену вредности првог <select> тага
                drzavaSelect.addEventListener('change', function() {
                // Ако је изабрана вредност "2" (BiH), прикажите други <select> таг
                if (drzavaSelect.value === '2') {
                    entitetSelect.style.display = 'block'; // Прикажи други <select> таг
                } else {
                    entitetSelect.style.display = 'none'; // Сакриј други <select> таг
                }
                });
            </script>

            <label for="biografija" class="text-warning"><strong>Biografija</strong></label><br>
            <textarea class="dodajTekst" name="biografija"></textarea><br><br>
            <button class="btn btn-primary mb-2" type="submit" name="posalji" >Pošalji</button>
            <input class="btn btn-warning mt-2" type="reset" value="Reset">

        </form>
        </div><!-- end .sredina --> 
    <?php 
    if(isset($_POST["posalji"]))
    {
        if(!empty($_POST["nazivIzvodjaca"]) && !empty($_POST["tipIzvodjaca"]))
        {
            if(!empty($_POST["drzava"]))
            {
                $whitelistTipIzvodjaca=array("solo", "grupa");
                
                $this->izvodjacMaster= trim(removeSimbols($_POST["nazivIzvodjaca"]));
                $this->ime= trim(removeSimbols($_POST["ime"]));
                $this->prezime= trim(removeSimbols($_POST["prezime"]));
                $this->slikaIzvodjac= removeSimbolsImg($_FILES["dodajSlikuIzvodjaca"]["name"]);
                $this->tipIzvodjaca= trim(removeSimbols($_POST["tipIzvodjaca"]));
                $this->drzavaIzvodjac= trim(removeSimbols($_POST["drzava"]));
                @$this->entitetIzvodjac= trim(removeSimbols($_POST["entitet"]));
		        $this->nadimci= trim(removeSimbols($_POST["nadimci"]));
                $this->clanGrupe= trim(removeSimbols($_POST["clanGrupe"]));
                $this->biografija= cleanText($_POST["biografija"]);


                    if (!(in_array($this->tipIzvodjaca, $whitelistTipIzvodjaca))) 
                    {
                        echo "Niste izabrali tip izvođača, da li je solo izvođač ili grupa";
                    }//end whitelistTipIzvodjaca
                    else
                    {                    
                        if($this->drzavaIzvodjac==2)
                        {
                            if(!empty($this->entitetIzvodjac))
                            {
                                if (!(in_array($this->tipIzvodjaca, $whitelistTipIzvodjaca))) 
                                {
                                    echo "Niste izabrali tip izvođača, da li je solo izvođač ili grupa";
                                }//end whitelistTipIzvodjaca
                                else
                                {
                                
                                $q1= "INSERT INTO izvodjaci (izvodjacMaster, ime, prezime, tipIzvodjaca, biografija, drzavaIzvodjac, entitetIzvodjac, nadimciIzvodjac, clanoviOveGrupe) VALUES ('$this->izvodjacMaster', '$this->ime', '$this->prezime', '$this->tipIzvodjaca', '$this->biografija', '$this->drzavaIzvodjac', '$this->entitetIzvodjac', '$this->nadimci', '$this->clanGrupe')";
                                $insert_izvodjac= mysqli_query($conn, $q1);   
                                }//end Insert q1

                                if($insert_izvodjac == TRUE){
                                    echo $this->lastInsertArtistId= mysqli_insert_id($conn);  //hvata zadnji insertovani ID
                                     if(!empty($_FILES["dodajSlikuIzvodjaca"]["name"]))
                                    {
                                        $res = $uploader->uploadAndUpdateImageField("dodajSlikuIzvodjaca", "images/izvodjaci/", "artist_photo", (int)$this->lastInsertArtistId, $conn,"izvodjaci", /* tabela*/ "slikaIzvodjac",  /*kolona slike*/ "idIzvodjaci", /* id kolona*/ 80);
                                    }
                                    logArtistAdded($this->lastInsertArtistId,  $this->izvodjacMaster);
                                    //echo "<meta http-equiv='refresh' content='1'; url='dodajalbume.php'>";
                                }else{
                                    echo "Greška " . mysqli_error($conn). "<br>";
                                }//end if($insert_izvodjac == TRUE)
                            }/*end if(!empty($entitetIzvodjac)*/
                            else{
                                echo "Morate izabrati entitet!";
                            }//end else if(!empty($entitetIzvodjac)

                        }//end if($this->drzavaIzvodjac==2)
                        else
                        {
                            if (!(in_array($this->tipIzvodjaca, $whitelistTipIzvodjaca))) 
                            {
                                echo "Niste izabrali tip izvođača, da li je solo izvođač ili grupa";
                            }//end whitelistTipIzvodjaca
                            else{
                                
                            $q2= "INSERT INTO izvodjaci (izvodjacMaster, ime, prezime, tipIzvodjaca, biografija, drzavaIzvodjac, nadimciIzvodjac, clanoviOveGrupe) VALUES ('$this->izvodjacMaster', '$this->ime', '$this->prezime', '$this->tipIzvodjaca', '$this->biografija', '$this->drzavaIzvodjac', '$this->nadimci', '$this->clanGrupe')";
                            $insert_izvodjac= mysqli_query($conn, $q2);
                            }//end insert $q2
                            if($insert_izvodjac == TRUE)
                                {
                                    echo $this->lastInsertArtistId= mysqli_insert_id($conn);  //hvata zadnji insertovani ID
                                    if(!empty($_FILES["dodajSlikuIzvodjaca"]["name"]))
                                    {
                                        $res = $uploader->uploadAndUpdateImageField("dodajSlikuIzvodjaca", "images/izvodjaci/", "artist_photo", (int)$this->lastInsertArtistId, $conn,"izvodjaci", /* tabela*/ "slikaIzvodjac",  /*kolona slike*/ "idIzvodjaci", /* id kolona*/ 80);
                                    }
                                    logArtistAdded($this->lastInsertArtistId, $this->izvodjacMaster);
                                    echo "<meta http-equiv='refresh' content='1'; url='dodajalbume.php'>";
                                }else{
                                    echo "Greška " . mysqli_error($conn). "<br>";
                                }
                        }//end else if($this->drzavaIzvodjac==2)
                }//end else whitelistTipIzvodjaca
            }//end if(!empty($_POST["drzava"])
        }//end if(!empty($_POST["nazivIzvodjaca"] && !empty($_POST["tipIzvodjaca"]))
    }//end if(isset($_POST["posalji"]))

    }//end function dodajIzvodjaca()

    //********************************* Metoda pozvana u fajlu insertDataPanel.class.php  *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda za unos slike izvodjaca  *********************************//

    protected function dodajSlikuIzvodjaca($slikaIzvodjaca, $idIzvodjaci)
    {
        //-----------------------------------------------------------------------------------
        //NAPOMENA: ZBOG KONVERZIJE SLIKA U WEBP FORMAT, OVA FUNKCIJA VIŠE NIJE U U POTREBI
        //-----------------------------------------------------------------------------------
        
        global $conn; 

        $this->whitelist = array(".jpg",".jpeg",".gif",".png", ".svg", ".webp"); 
        //$whitelist= array("image/jpeg", "image/png", "image/gif", "image/svg", "image/webp", "image/apng", "image/avif");

        $this->maxVelicinaSlike= 2097152; //2mb
        $this->minVelicinaSlike= 10000; //10kb 
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            $size= $_FILES["dodajSlikuIzvodjaca"]["size"];
            //print_r($size) . "<hr>";
            if(($size > $this->maxVelicinaSlike) || ($size< $this->minVelicinaSlike))
            {  
                echo "<script>
                            document.getElementById('promSlik').innerHTML='Prevelika slika (veća od 2mb) ili je premala slika (manja od 10kb)';
                </script>"; 
            }else
                {
                    $this->putanja = "images/izvodjaci/";
                    $this->skeniraj= scandir($this->putanja);

                    //print_r($skeniraj);
                    /*$provjeraEkstenzije1= pathinfo($putanja.$slikaAlbuma);
                    $provjeraEkstenzije2= $provjeraEkstenzije1['extension'];*/

                    $this->imeSlike= removeSimbolsImg($slikaIzvodjaca);
                    $this->ukloniEkstenziju= explode(".", $this->imeSlike);
                    $this->ekstenzija= end($this->ukloniEkstenziju);
                    $this->vrijeme= "_im".date("dmY_His", time())."_".time().".";
                    $this->slikaVrijeme= $this->ukloniEkstenziju[0].$this->vrijeme;

                    //if provjera ekstenzije
                    if (!(in_array(".".$this->ekstenzija, $this->whitelist))) 
                    {
                        die('Nepravilan format slike, pokušajte sa drugom slikom');
                    }else
                        {
                            $provjeraSlike= $this->putanja.$this->slikaVrijeme.$this->ekstenzija;
                            if(!file_exists(($provjeraSlike)))
                            {
                                $this->slikaVrijeme= $this->slikaVrijeme.$this->ekstenzija;
                                $slikaAlbuma_tmp= $_FILES["dodajSlikuIzvodjaca"]["tmp_name"];
                                move_uploaded_file($slikaAlbuma_tmp, $provjeraSlike);
                            }//end else !file_exists(($provjeraSlike)

                                $putanja = "../images/izvodjaci/";
                                move_uploaded_file($slikaAlbuma_tmp, $provjeraSlike);

                                return $this->slikaVrijeme;
                        }//end while loop provjera korisničkog imena i šifre 
                }//end provjera ekstenzije
        }// end if provjera REQUEST_METHOD==POST
    }//end dodajSlikuIzvodjaca()

    //********************************* Metoda pozvana u ovom fajlu u metodi dodajIzvodjaca()  *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------






}//end class insertArtist