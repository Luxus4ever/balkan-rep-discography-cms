<?php
//METODE U OVOM FAJLU
//promjenaSlikeIzvodjaca (Metoda sa kojom mijenjamo sliku izvođača)


//********************************* NAPOMENA *********************************//

//Ovo je isti kod kao originalnom glavnom functions folderu, osim što sam morao promjeniti $putanja zbog admin foldera

//********************************* NAPOMENA *********************************//


//********************************* Metoda sa kojom mijenjamo sliku izvođača *********************************//
function promjenaSlikeIzvodjaca($slikaIzvodjac, $idIzv, $izvodjacMaster)
{
    global $conn; 
    $whitelist = array(".jpg",".jpeg",".gif",".png", ".svg", ".webp"); 

    $maxVelicinaSlike= 2097152; //2mb
    $size= $_FILES["promjenaSlikeIzvodjaca"]["size"];
    //print_r($size) . "<hr>";
    if(($size > $maxVelicinaSlike) || ($size==0))
    {  
        echo "<script>
                    document.getElementById('promSlik').innerHTML='Prevelika slika';
        </script>"; 
    }else
        {
            $putanja = "../images/izvodjaci/";
            $skeniraj= scandir($putanja);
            //print_r($skeniraj);

            $imeSlike= $slikaIzvodjac;
            $ukloniEkstenziju= explode(".", $imeSlike);
            $ekstenzija= end($ukloniEkstenziju);
            $vrijeme= "_(im".date("dmY_His)", time());

            //if provjera ekstenzije
            if (!(in_array(".".$ekstenzija, $whitelist))) 
            {
                die('Nepravilan format slike, pokušajte sa drugom slikom');
            }else
                {
                    $provjeraSlike= $putanja.$slikaIzvodjac;
                    if(!file_exists(($provjeraSlike)))
                    {
                        $slikaIzvodjac= removeSimbolsImg(str_replace($slikaIzvodjac, "$ukloniEkstenziju[0]$vrijeme.$ekstenzija", str_replace(" ", "_", $_FILES["promjenaSlikeIzvodjaca"]["name"])));
                        $slikaIzvodjac_tmp= $_FILES["promjenaSlikeIzvodjaca"]["tmp_name"];
                        move_uploaded_file($slikaIzvodjac_tmp, $putanja.$slikaIzvodjac);
                    }else
                        {
                            /*Ovaj blok koda predstavlja da ukoliko se ponovi ime slike prilikom unosa da se doda novi broj npr image.jpg, da sledeća bude image(1).jpg.*/
                            /*Brojač u for petlji je postavljen na 100 puta jer je pretpostavka da neće biti više od 2-3 unosa slike sa istim imenom ukoliko sami već ne preimenujemo sliku*/
                            for($i=1; $i<100; $i++)
                            {
                                $imeNoveSlike= "$ukloniEkstenziju[0]($i).$ekstenzija";
                                
                                if(!in_array($imeNoveSlike, $skeniraj))
                                {
                                    $slikaIzvodjac= removeSimbolsImg(str_replace($slikaIzvodjac, "$ukloniEkstenziju[0]($i)$vrijeme.$ekstenzija", str_replace(" ", "_", $_FILES["promjenaSlikeIzvodjaca"]["name"])));
                                    $slikaIzvodjac_tmp= $_FILES["promjenaSlikeIzvodjaca"]["tmp_name"];

                                    move_uploaded_file($slikaIzvodjac_tmp, $putanja.$slikaIzvodjac);
                                    break;
                                }
                            }//end for petlje
                        }//end else !file_exists(($provjeraSlike)


                        //Hvatanje stare slike da bi se mogla obrisati
                        $q_staraSlika="SELECT slikaIzvodjac FROM izvodjaci WHERE idIzvodjaci='{$idIzv}'";
                        $stara_slika=mysqli_query($conn, $q_staraSlika);

                        $row = mysqli_fetch_assoc($stara_slika);
                        $staraSlika = $row["slikaIzvodjac"];


                        //Izmjena tj. ubacivanje nove slike
                        $q="UPDATE izvodjaci SET slikaIzvodjac='{$slikaIzvodjac}' WHERE idIzvodjaci='{$idIzv}'";
                        $promjeniSliku= mysqli_query($conn, $q);
                        move_uploaded_file($slikaIzvodjac_tmp, $putanja.$slikaIzvodjac);

                        if($promjeniSliku == TRUE)
                        {
                            logArtistImageUpdated($idIzv, $izvodjacMaster);
                                // Obriši staru sliku ako postoji
                                $staraSlikaPath = $putanja . $staraSlika;
                                if ($staraSlika && file_exists($staraSlikaPath)) {
                                    unlink($staraSlikaPath);
                                }
                            echo "<meta http-equiv='refresh' content='0'; url='updateartist.php?idIzv={$idIzv}'>";
                        }else{
                            echo "Greška " . mysqli_error($conn). "<br>";

                        }       
                }//if else provjera ekstenzije i ostalih stvari
        }//end provjera veličine slike
}//end promjenaSlikeIzvodjaca()

//********************************* Pozvana metoda u fajlu middlePanel.func.php, metoda updateBiografija() *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

