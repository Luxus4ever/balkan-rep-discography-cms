<?php
require_once 'insertAlbumSongs.class.php';

class insertLabel extends insertSongs
{
    //METODE SADRŽANE U OVOJ KLASI
    //1. insertLabel($slid) - Metoda sa kojom dodajemo naziv i opis labela
    //2. unosLogoLabel($logoLabel, $idIzdavaci) - Metoda sa kojom dodajemo logo labela

    protected $idIzdavaci;
    protected $izdavaciNaziv;
    protected $izdavaciOpis;
    protected $logoLabel;

    protected $lastInsertIzdavacId;


    //********************************* Metoda sa kojom dodajemo naziv i opis labela *********************************//
    //Razlog naziva metode sa brojem 2 je zato što se klasa zove isto. Eventualno je preimenovati u constructor
    public function insertLabel2($slid)
    {
        global $conn;
        ?>
        <br>
        <div id="" class="razmakIzmedju">
            <div class="col-md-3">
                <div class="slikeAlbumaPregled sredina">
                    <div class="editAlbum">
                        <p class="text-light">Logo Izdavača/Label-a</p>
                        <img src="../images/albumi/" alt="" title=" (front)" class=""/>
                        
            <form method="POST" action="" enctype="multipart/form-data" name="insertLabel" id="insertLabel">
                        <input type="file" class="btn btn-light" name="logoLabel">
                        
                        <br><br><br><hr class="hrLinija">
                    </div><!-- end .editAlbum -->
                </div><!-- end .slikeAlbumaPregled .sredina -->
            </div><!-- /.col-md-3 -->
        
            <div class="col-md-7 sredina inline-block"> 
                <label for="izdavacPregled" class="text-light">Pogledajte da li već postoji izdavač/Label u bazi</label><br>
                <select class="form-control" name="izdavacPregled" id="izdavacPregled">
                    <option selected value="" disabled selected>Pregled Izdavaca</option>
                    <?php 
                    $q= "SELECT * FROM izdavaci ORDER BY izdavaciNaziv";
                    $select_izdavaci= mysqli_query($conn, $q);

                    while($red= mysqli_fetch_array($select_izdavaci))
                    {
                        $this->idIzdavaci= $red["idIzdavaci"];
                        $this->izdavaciNaziv= $red["izdavaciNaziv"];
                        ?>
                        <option class="" value="<?php echo $this->idIzdavaci; ?>"><a class="text-decoration-none" href="upadateartist.php?idIzv=<?php echo $this->idIzdavaci; ?>"><?php echo $this->izdavaciNaziv; ?></a></option>
                        <?php
                    }//end while
                    ?>
                </select> <br><br>

                <label for="izdavac" class="text-warning"><strong>Upisati naziv Izdavača (Labela-a) <span class="bg-danger text-light">&nbsp; Obavezno polje &nbsp;</strong></label><br>
                <input type="text" name="izdavacNaziv" class="form-control form-control-sm text-dark" placeholder="Bassivity Music"><br><br>

                <label for="detaljiIzdavac" class="text-light">Detalji o izdavačkoj kući/Label-u (nije obavezno)</label><br>
                <textarea class="dodajTekst" name="detaljiIzdavac" 
                placeholder="Ovaj izdavačaka kuća/label postoji od ...."><?php ; ?></textarea><br><br>
                <button class="btn btn-primary mb-0" type="submit" name="posalji" >Pošalji</button>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input class="btn btn-warning mt-0" type="reset" value="Reset">
            </div><!-- /.col-md-7 -->
            </form>
        </div><!-- end .razmakIzmedju -->
           
        <?php 
        if(isset($_POST["posalji"]))
        {
            $this->izdavaciNaziv= trim(removeSimbols($_POST["izdavacNaziv"]));
            $this->izdavaciOpis= trim(removeSimbols($_POST["detaljiIzdavac"]));

            if(!empty($this->izdavaciNaziv))
            {
                $q_insertIzdavac= "INSERT INTO izdavaci (izdavaciNaziv, izdavaciOpis) VALUES ('$this->izdavaciNaziv', '$this->izdavaciOpis')"; 
                $insert_izdavac= mysqli_query($conn, $q_insertIzdavac);
            }else{
                echo "Morate unijeti naziv izdavača/labela";
            }//end if else()
            
            //--------------------------------------
            include_once "imageUploader.class.php";
            $uploader = new ImageUploader();
            //--------------------------------------
            
            if($insert_izdavac == TRUE)
            {
                $this->lastInsertIzdavacId= mysqli_insert_id($conn);  //hvata zadnji insertovani ID
                //$this->lastInsertIzdavacId= mysqli_insert_id($conn);  //hvata zadnji insertovani ID
                logLabelAdded($this->idIzdavaci, $this->izdavaciNaziv);


                if(!empty($_FILES["logoLabel"]["name"]))
                {

                    $res = $uploader->uploadAndUpdateImageField("logoLabel", "images/labels/", "label_logo", (int)$this->lastInsertIzdavacId, $conn,"izdavaci", /* tabela*/ "izdavaciLogo",  /*kolona slike*/ "idIzdavaci", /* id kolona*/ 75);
                }
            }else
                {
                    echo "Greška " . mysqli_error($conn). "<br>";
                }//end if else()
        }//** end if(isset($_POST["posalji"])) **//
    }//** end function insertLabel2() **//

    //********************************* Pozvana u fajlu insertDataPanel u metodi prikazUnosPanela() *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda sa kojom dodajemo logo labela  *********************************//
    protected function unosLogoLabel($logoLabel, $idIzdavaci)
    {
        global $conn; 

        $whitelist = array(".jpg",".jpeg",".gif",".png", ".svg", ".webp"); 
        //$whitelist= array("image/jpeg", "image/png", "image/gif", "image/svg", "image/webp", "image/apng", "image/avif");

        $maxVelicinaSlike= 2097152; //2mb
        $minVelicinaSlike= 8000; //8kb 
        if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
            $size= $_FILES["logoLabel"]["size"];
            //print_r($size) . "<hr>";
            if(($size > $maxVelicinaSlike) || ($size< $minVelicinaSlike))
            {  
                ?>
                <script>
                    document.getElementById('promSlik').innerHTML='Prevelika slika (veća od 2mb) ili je premala slika (manja od 10kb)';
                </script>
                <?php 
            }else
                {
                    $putanja = "images/labels/";
                    $skeniraj= scandir($putanja);

                    $imeSlike= removeSimbolsImg($logoLabel);
                    $ukloniEkstenziju= explode(".", $imeSlike);
                    $ekstenzija= end($ukloniEkstenziju);
                    $vrijeme= "_im".date("dmY_His", time())."_".time().".";
                    $slikaVrijeme= $ukloniEkstenziju[0].$vrijeme;

                    //if provjera ekstenzije
                    if (!(in_array(".".$ekstenzija, $whitelist))) 
                    {
                        die('Nepravilan format slike, pokušajte sa drugom slikom');
                    }else
                        {
                            $provjeraSlike= $putanja.$slikaVrijeme.$ekstenzija;
                            if(!file_exists(($provjeraSlike)))
                            {
                                $slikaVrijeme= $slikaVrijeme.$ekstenzija;
                                $slikaAlbuma_tmp= $_FILES["logoLabel"]["tmp_name"];
                                move_uploaded_file($slikaAlbuma_tmp, $provjeraSlike);
                            }//end else !file_exists(($provjeraSlike)

                                
                                $q_insertSliku="UPDATE izdavaci SET izdavaciLogo='{$slikaVrijeme}' WHERE idIzdavaci='{$this->idIzdavaci}'";
                                $dodajSlikuLabel= mysqli_query($conn, $q_insertSliku);
                                move_uploaded_file($slikaAlbuma_tmp, $provjeraSlike);

                                            

                                if($dodajSlikuLabel == TRUE)
                                {
                                    echo "<meta http-equiv='refresh' content='0'>";
                                }else{
                                    echo "Greška " . mysqli_error($conn). "<br>";

                                }       
                        }//end if else (provjera ekstenzije u nizu)
                }//end provjera ekstenzije
        }// end if provjera REQUEST_METHOD
    }//end function unosLogoLabel()

    //********************************* Pozvana metoda u ovom fajlu u metodi insertLabel() *********************************//

}//end class insertLabel