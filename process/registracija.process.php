<?php
include "../config/config.php";
include "../header.php";
include "../functions/removeSymbols.func.php";
include_once "../functions/zabranjenPristup.func.php";
require_once __DIR__ . "../../functions/welcome.func.php"; 
require_once __DIR__ . "../../functions/emailverify.func.php";

//--------------------------------------------------------------------
include_once "../classes/insertData-classes/imageUploader.class.php";
$uploader = new ImageUploader(); 
//--------------------------------------------------------------------

$putanja= "header.php";
//echo basename($putanja);
headerPutanja("../", "../");
?>
<div class="slikeAlbumaPregled sredina">
<?php
if(isset($_POST["posalji"]))
{
    if(!empty($_POST["ime"]) && !empty($_POST["prezime"]) && !empty($_POST["email"]) && !empty($_POST["username"]) && !empty($_POST["pol"]) && !empty($_POST["drzava"]) && !empty($_POST["tipKorisnika"]) && !empty($_POST["password"]) && !empty($_POST["password2"]) )
    {
        if($_POST["password"]===$_POST["password2"])
        { 
            
            $ime= strip_tags($_POST["ime"]);
            $prezime= strip_tags($_POST["prezime"]);
            $cleanEmail= strip_tags($_POST["email"]);
            $username= strip_tags($_POST["username"]);
            $pol= strip_tags($_POST["pol"]);
            $drzava= strip_tags($_POST["drzava"]);
            @$entitet= trim(removeSimbols($_POST["entitet"]));
            $tipKorisnika= strip_tags($_POST["tipKorisnika"]);
            $sifra= strip_tags($_POST["password"]);
            $sifra2= removeSimbols($_POST["password2"]);
            $grad= removeSimbols($_POST["grad"]);
            $facebookPr= removeLinksSocialMedia($_POST["facebookLog"]);
            $instagramPr= removeLinksSocialMedia($_POST["instagramLog"]);
            $twitterPr= removeLinksSocialMedia($_POST["twitterLog"]);
            $tiktokPr= removeLinksSocialMedia($_POST["tiktokLog"]);
            $sajt= checkLinks($_POST["sajtLog"]);
            $profilnaSlika= removeSimbolsImg($_FILES["profilnaSlika"]["name"]);

            // ✅ Ako je izabrana Bosna i Hercegovina (BIH), entitet je obavezan
            if ($drzava === "BIH" && empty($entitet)) {
                echo "<h3 class='text-danger'>Morate izabrati entitet za Bosnu i Hercegovinu.</h3>";
                exit; // prekida dalju obradu forme
            }


            $regex = '/^[a-zA-Z0-9]+([._+-]?[a-zA-Z0-9]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.[a-zA-Z]{2,}$/';
            if (preg_match($regex, $cleanEmail)) 
            {
                $cleanIme= removeSimbols($ime);
                $cleanPrezime= removeSimbols($prezime);
                $cleanUsername= removeSimbols($username);
                $cleanPol= removeSimbols($pol);
                $cleanTipKorisnika= removeSimbols($tipKorisnika);
                $cleanDrzava= removeSimbols($drzava);
                $cleanEntitet= removeSimbols($entitet);
                $cleanGrad= removeSimbols($grad);
                $cleanFacebookPr= removeSimbols($facebookPr);
                $cleanInstagramPr= removeSimbols($instagramPr);
                $cleanTwitterPr= removeSimbols($twitterPr);
                $cleanTiktokPr= removeSimbols($tiktokPr);
                
                $cleanSifra= trim($sifra);
                $cleanSifra2= trim($sifra2);

                $sifrovano = password_hash($cleanSifra, PASSWORD_DEFAULT);
                $sifrovano2= hash("gost-crypto", $cleanSifra2);

                $q2 = "SELECT username, email FROM korisnici";
                $provjera = mysqli_query($conn, $q2);

                // -------------------------------
                // KOD ISPOD JE PROVJERA DUPLIKATA (ispravljena logika)
                // -------------------------------
                $zauzetoUsername = false;
                $zauzetoEmail = false;

                while ($row = mysqli_fetch_assoc($provjera)) {
                    if ($row["username"] === $cleanUsername) {
                        $zauzetoUsername = true;
                        break;
                    }
                    if ($row["email"] === $cleanEmail) {
                        $zauzetoEmail = true;
                        break;
                    }
                }

                // Nakon while-a odlučujemo šta dalje
                if ($zauzetoUsername) {
                    echo "<h1>Korisničko ime je zauzeto, probajte sa nekim drugim</h1>";
                }
                else if ($zauzetoEmail) {
                    echo "<h1>Email adresa je zauzeta, probajte sa nekom drugom</h1>";
                }
                else {

                
                    if($pol==="Muško"){
                        $rezervnaSlika="Lino_Vortex.png";
                    }else if($pol==="Žensko"){
                        $rezervnaSlika="Lina_Vortex.png";
                    }
                    
                    // -------------------------------
                    // KOD ISPOD JE INSERT (izvršava se samo jednom)
                    // -------------------------------
                    $q = "INSERT INTO korisnici (ime, prezime, email, username, pol, tipKorisnika, drzava, entitet, sifra, sifra2, grad, facebookPr, instagramPr, twitterPr, tiktokPr, websajt, profilnaSlika, datumRegistracije)
                        VALUES ('$cleanIme', '$cleanPrezime', '$cleanEmail', '$cleanUsername', '$cleanPol', '$cleanTipKorisnika', '$cleanDrzava', '$cleanEntitet', '$sifrovano', '$sifrovano2', '$cleanGrad', '$cleanFacebookPr', '$cleanInstagramPr', '$cleanTwitterPr', '$cleanTiktokPr', '$sajt', '$rezervnaSlika', NOW())";

                    $ubaciKorisnike = mysqli_query($conn, $q);

                                if($ubaciKorisnike == TRUE)
                                {
                                    $newUserId = mysqli_insert_id($conn);  //hvata zadnji insertovani ID

                                     if (!empty($_FILES["profilnaSlika"]["name"]) && $_FILES["profilnaSlika"]["error"] === UPLOAD_ERR_OK) 
                                    {
                                        $targetDir = __DIR__ . "/../images/profilne/";
                                    $res = $uploader->uploadAndUpdateImageField("profilnaSlika", $targetDir, "novi_korisnik_slika_profila", (int)$newUserId, $conn,"korisnici", /* tabela*/ "profilnaSlika",  /*kolona slike*/ "idKorisnici", /* id kolona*/ 75);
                                    }

                                    sendWelcomeMail($email, $username);
                                    emailVerify_sendOrResend($newUserId, $email, $username);
                                    ?>
                                    <div class="slikeAlbumaPregled sredina">
                                        <h1>Registracija uspješna!<br><br>
                                        Bićete presumjerni na stranicu da se ulogujete.<h1>
                                    </div><!--end .slikeAlbumaPregled .sredina -->
                                    
                                    <script>
                                            setTimeout(function () {
                                            window.location.href = "../login.php"; //will redirect to your blog page (an ex: blog.html)
                                            }, 5000)
                                        
                                        </script>
                                    <?php
                                }else if($ubaciKorisnike == FALSE){
                                    /********** Sakriti u produkciji **********/
                                    //echo "Greška " . mysqli_error($conn).  $i++ ."<br>";
                                    /********** U produkcionoj verziji sakriti ovaj else blok koda sa mysqli_error(). Razlog, prikazuje grešku ukoliko je email adresa zauzeta pored tog teksta onoliko puta koliko ima korisnika **********/
                                }//end if else
                                
                            }//end else INSERT
                //}//end while loop provjera korisničkog imena i šifre
            }//end provjera ekstenzije
        }else{
            zabranjenPristup2("gold", "Nije vam ista šifra u polju jedan i u polju za ponovni unos šifre");
        }//end provjera šifre $password===$password2
    }else{
        zabranjenPristup2("gold", "Niste unijeli sve podatke!");
        echo '<meta http-equiv="refresh" content="5; url=../registracija.php">';
    }//end if(!empty([])) provjera unosa svih polja forme
}//end if(isset($_POST["posalji"]))
?>
    </main>
</div><!-- end. slikeAlbumaPregled sredina -->
<?php

include "../footer.php";
footerPutanja("../");