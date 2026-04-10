<?php
//FUNKCIJE U OVOM FAJLU
//promjenaSlike (Metoda sa kojom mijenjamo profilnu sliku)

//********************************* Metoda sa kojom mijenjamo profilnu sliku *********************************//
function promjenaSlike($profilnaSlika, $profil, $lid)
{
    global $conn; 
    $whitelist = array(".jpg",".jpeg",".gif",".png", ".svg", ".webp"); 

    $maxVelicinaSlike= 4097152; //2mb
    $size= $_FILES["promjenaProfilneSlike"]["size"];
    //print_r($size) . "<hr>";
    if(($size > $maxVelicinaSlike) || ($size==0))
    {  
        ?>
        <script>
            document.getElementById('promSlik').innerHTML='Prevelika slika ili niste dodali profilnu sliku koja je obavezna 2222';
        </script>
        <?php
    }else
        {
            $putanja = "images/profilne/";
            $skeniraj= scandir($putanja);
            //print_r($skeniraj);

            $imeSlike= $profilnaSlika;
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
                        $slikaVrijeme= removeSimbolsImg($slikaVrijeme.$ekstenzija);
                        $profilnaSlika_tmp= $_FILES["promjenaProfilneSlike"]["tmp_name"];
                        move_uploaded_file($profilnaSlika_tmp, $provjeraSlike);
                    }//end if(!file_exists(($provjeraSlike))

                    $putanja = "../images/profilne/";
                    $q="UPDATE korisnici SET profilnaSlika='{$slikaVrijeme}' WHERE idKorisnici='{$lid}'";
                    $promjeniSliku= mysqli_query($conn, $q);
                    move_uploaded_file($profilnaSlika_tmp, $provjeraSlike);

                    if($promjeniSliku == TRUE)
                    {
                        echo "<meta http-equiv='refresh' content='0'; url='profileedit.php?{$profil}'>";
                    }else{
                        echo "Greška " . mysqli_error($conn). "<br>";
                    }       
                }//end while loop provjera korisničkog imena i šifre 
        }//end else (provjera ekstenzije)
}//end promjenaSlike()

//********************************* Pozvana metoda u fajlu updateUsers.func.php, metoda bocnaForma() *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

