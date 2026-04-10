<?php
//METODE U OVOM FAJLU
//promjenaSlikeKorisnika (Metoda sa kojom mijenjamo profilnu sliku korisnika ADMINISTRATOR, MODERATOR)

//********************************* NAPOMENA *********************************//

//Ovo je isti kod kao originalnom glavnom functions folderu, osim što sam morao promjeniti $putanja zbog admin foldera

//********************************* NAPOMENA *********************************//

//********************************* Metoda sa kojom mijenjamo profilnu sliku korisnika ADMINISTRATOR, MODERATOR *********************************//
function promjenaSlikeKorisnika($profilnaSlika, $profil, $lid)
{
    global $conn; 
    $whitelist = array(".jpg",".jpeg",".gif",".png", ".svg", ".webp"); 

    $maxVelicinaSlike= 2097152; //2mb
    $size= $_FILES["promjenaProfilneSlike"]["size"];
    //print_r($size) . "<hr>";
    if(($size > $maxVelicinaSlike) || ($size==0))
    {  
        echo "<script>
                    document.getElementById('promSlik').innerHTML='Prevelika slika ili niste dodali profilnu sliku koja je obavezna 2222';
        </script>"; 
    }else
        {
            $putanja = "../images/profilne/";
            $skeniraj= scandir($putanja);
            //print_r($skeniraj);

            /*$provjeraEkstenzije1= pathinfo($putanja.$profilnaSlika);
            $provjeraEkstenzije2= $provjeraEkstenzije1['extension'];*/

            $imeSlike= $profilnaSlika;
            $ukloniEkstenziju= explode(".", $imeSlike);
            $ekstenzija= end($ukloniEkstenziju);
            $vrijeme= "_im".date("dmY_His", time());

            //if provjera ekstenzije
            if (!(in_array(".".$ekstenzija, $whitelist))) 
            {
                die('Nepravilan format slike, pokušajte sa drugom slikom');
            }else
                {
                    $provjeraSlike= $putanja.$profilnaSlika;
                    if(!file_exists(($provjeraSlike)))
                    {
                        $profilnaSlika= removeSimbolsImg(str_replace($profilnaSlika, "$ukloniEkstenziju[0]$vrijeme.$ekstenzija", str_replace(" ", "_", $_FILES["promjenaProfilneSlike"]["name"])));
                        $profilnaSlika_tmp= $_FILES["promjenaProfilneSlike"]["tmp_name"];
                        move_uploaded_file($profilnaSlika_tmp, $putanja.$profilnaSlika);
                    }else
                        {
                            /*Ovaj blok koda predstavlja da ukoliko se ponovi ime slike prilikom unosa da se doda novi broj npr image.jpg, da sledeća bude image(1).jpg.*/
                            /*Brojač u for petlji je postavljen na 100 puta jer je pretpostavka da neće biti više od 2-3 unosa slike sa istim imenom ukoliko sami već ne preimenujemo sliku*/
                            for($i=1; $i<100; $i++)
                            {
                                $imeNoveSlike= "$ukloniEkstenziju[0]($i).$ekstenzija";
                                
                                if(!in_array($imeNoveSlike, $skeniraj))
                                {
                                    $profilnaSlika= removeSimbolsImg(str_replace($profilnaSlika, "$ukloniEkstenziju[0]($i)$vrijeme.$ekstenzija", str_replace(" ", "_", $_FILES["promjenaProfilneSlike"]["name"])));
                                    $profilnaSlika_tmp= $_FILES["promjenaProfilneSlike"]["tmp_name"];

                                    move_uploaded_file($profilnaSlika_tmp, $putanja.$profilnaSlika);
                                    break;
                                }
                            }//end for petlje
                        }//end else !file_exists(($provjeraSlike)

                        $q="UPDATE korisnici SET profilnaSlika='{$profilnaSlika}' WHERE idKorisnici='{$lid}'";
                        $promjeniSliku= mysqli_query($conn, $q);
                        move_uploaded_file($profilnaSlika_tmp, $putanja.$profilnaSlika);

                        if($promjeniSliku == TRUE)
                        {
                            echo "<meta http-equiv='refresh' content='0'; url='profileedit.php?{$profil}'>";
                        }else{
                            echo "Greška " . mysqli_error($conn). "<br>";

                        }       
                }//if else provjera ekstenzije i ostalog
        }//end provjera veličine slike
}//end promjenaSlikeKorisnika()

/********************************* Pozvana metoda u fajlu admineditusers.func.php metoda adminIzmenaSlikeKorisnika() *********************************/

//--------------------------------------------------------------------------------------------------------------------------------

