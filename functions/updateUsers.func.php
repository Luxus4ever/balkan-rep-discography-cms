<?php
//Funkcije pomoću kojih se vrši izmjena podataka o korisniku, gdje svaki korisnik za sebe uređuje podatke

//FUNKCIJE U OVOM FAJLU
//bocnaForma (Izmjena profilne slike)
//editUser (Izmjena podataka o profilu)
//formaEditUser (Prikaz forme za izmjenu podataka)


//********************************* Prikaz forme za izmjenu slike profila *********************************//
function bocnaForma($profil, $lid)
{
    //-----------------------------------------------------------------
    include_once "classes/insertData-classes/imageUploader.class.php";
    $uploader = new ImageUploader();
    //-----------------------------------------------------------------
    global $conn;
    if(isset($_POST["promjeniSliku"]))
    {
        if(!empty($_FILES["promjenaProfilneSlike"]))
        {
             $res = $uploader->uploadAndUpdateImageField("promjenaProfilneSlike", "images/profilne/", "nova_slika_profila", (int)$lid, $conn,"korisnici", /* tabela*/ "profilnaSlika",  /*kolona slike*/ "idKorisnici", /* id kolona*/ 75);

              echo "<meta http-equiv='refresh' content='1'; url='profileedit.php?username={$profil}&lid={$lid}'>";
        }else{
            echo "Niste izabrali sliku.";
        }
    }//end master if()

    if(isset($_POST["obrisi"]))
    {
        $q_profilnaSlika = "SELECT * FROM korisnici WHERE username='{$profil}'";
        $select_profilnaSlika = mysqli_query($conn, $q_profilnaSlika);
        while ($row = mysqli_fetch_array($select_profilnaSlika)) 
        {
          $profilnaSlikaTemp = $row["profilnaSlika"];
          $pol= $row["pol"];
          $putanjaDoSlike = 'images/profilne/' . $profilnaSlikaTemp; // Promijenite putanju i naziv slike prema vašim potrebama
          if (file_exists($putanjaDoSlike)) 
          {
              if(unlink($putanjaDoSlike)){
                echo "Slika uspješno obrisana.";
                echo "<meta http-equiv='refresh' content='2'>";
              }

              
          }//end if(file_exists())
        }//end while
        
        if($pol==="Muško"){
            $rezervnaSlika="Lino_Vortex.png";
        }else if($pol==="Žensko"){
            $rezervnaSlika="Lina_Vortex.png";
        }
        $delete_query="UPDATE korisnici SET profilnaSlika='{$rezervnaSlika}' WHERE username='{$profil}'";
        mysqli_query($conn, $delete_query);
        echo "<meta http-equiv='refresh' content='0'; url='profileedit.php?{$profil}'>";
    }//end if(isset($_POST["obrisi"]))
    ?>
        <form action="" method="POST" enctype="multipart/form-data" name="promjenaSlike" id="promjenaSlike">
            <input type="file" name="promjenaProfilneSlike"><br><br>
            <button type="submit" class="btn btn-primary" name="promjeniSliku" value="izmjeni">Izmjeni</button>
            <button type="submit" class="btn btn-danger" name="obrisi" value="obrisi">Obriši</button>
        </form>

        <script>
            document.getElementById('buttonid').addEventListener('click', openDialog);
            function openDialog() {
            document.getElementById('promjenaProfilneSlike').click();
            }
        </script>
    <?php
}//end bocnaForma()
//********************************* Pozvana metoda u ovom fajlu u metodi editUser *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Izmjena podataka o profilu *********************************//
function editUser($profil,$lid)
{
    global $conn;
    if(!isset($_SESSION['username']) && !isset($_SESSION['idKorisnici']))
    {
        echo "<h1>Nemate prava pristupa!</h1>";
    }else
    {
        $q2= "SELECT * FROM korisnici WHERE username='{$profil}'";
        $pregledajPodatke= mysqli_query($conn, $q2);

        while($row= mysqli_fetch_assoc($pregledajPodatke))
        {
            $ime= $row["ime"];
            $prezime= $row["prezime"];
            $email= $row["email"];
            $username= $row["username"];
            $datumRegistracije= $row["datum2"];
            $pol= $row["pol"];
            $tipKorisnika= $row["tipKorisnika"];
            $drzava= $row["drzava"];
            $grad= $row["grad"];
            $profilnaSlika= $row["profilnaSlika"];
            $facebookPr= $row["facebookPr"];
            $instagramPr= $row["instagramPr"];
            $twitterPr= $row["twitterPr"];
            $tiktokPr= $row["tiktokPr"];
            $sajt= $row["websajt"];
            $sifra= $row["sifra"];
            $sifra2= $row["sifra2"];
            
            ?>
            <div id="wrapper">
                <div class="slikeAlbumaPregled">
                    <main>
                        <aside class="profilBocno">
                            <div>
                                <img src="images/profilne/<?php echo $profilnaSlika;?>" class="profilnaSlika" alt="<?php echo $username;?>" title="<?php echo $username;?>">
                                <p id="promSlik">Promjeni sliku</p>
                                <?php
                                    bocnaForma($profil, $lid);
                                ?>
                                <hr class="hrLinija">
                                <a href="profile.php?username=<?php echo $profil."&lid=".$lid; ?>" class="btn btn-success d-flex align-items-center hover-shadow sredina">Pregled profila</a>
                            </div>
                        </aside>
                        <?php
                        formaEditUser($username, $ime, $prezime, $pol, $tipKorisnika, $email, $drzava, $grad, $facebookPr, $instagramPr, $twitterPr, $tiktokPr, $sajt, $profil, $lid);
                        ?>
                    </main>  
                </div> <!-- kraj slikeAlbumaPregled -->
            </div> <!-- kraj #wrapper -->
            <?php
        }//end while
    }//end if else(provjera sesije i id-a)
}//end editUser()

//********************************* Pozvana metoda u fajlu profileedit.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Funkcija za prikaz forme za izmjenu podataka *********************************//
function formaEditUser($username, $ime, $prezime, $pol, $tipKorisnika, $email, $drzava, $grad, $facebookPr, $instagramPr, $twitterPr, $tiktokPr, $sajt, $profil, $lid)
{
    global $conn;
    if(!isset($_SESSION['username']) && !isset($_SESSION['idKorisnici']))
    {
        echo "<h1>Nemate prava pristupa!</h1>";
    }else
    {
        if(isset($_POST["izmjeni"]))
        {
            if(!empty($_POST["grad"]))
            {
                $email = trim($_POST["email"]);
                $grad= removeSimbols($_POST["grad"]);
                $drzava= $_POST["drzava"];
                $facebookPr= removeLinksSocialMedia($_POST["facebook"]);
                $instagramPr= removeLinksSocialMedia($_POST["instagram"]);
                $twitterPr= removeLinksSocialMedia($_POST["twitter"]);
                $tiktokPr= removeLinksSocialMedia($_POST["tiktok"]);
                $sajt= checkLinks($_POST["sajt"]);

                $regex = '/^[a-zA-Z0-9]+([._+-]?[a-zA-Z0-9]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.[a-zA-Z]{2,}$/';
                if (preg_match($regex, $email)) 
                {
                    $oldQ = "SELECT email FROM korisnici WHERE idKorisnici='{$lid}' LIMIT 1";
                    $oldR = mysqli_query($conn, $oldQ);
                    $old  = $oldR ? mysqli_fetch_assoc($oldR) : null;
                    $oldEmail = $old['email'] ?? '';

                    $emailNew = $email;

                    $emailChanged = ($oldEmail !== $emailNew);

                    if ($emailChanged) {
                        $update_query="UPDATE korisnici SET
                            email='{$emailNew}',
                            email_verified=0,
                            email_verified_at=NULL,
                            email_verify_last_sent_at=NULL,
                            drzava='{$drzava}', grad='{$grad}',
                            facebookPr='{$facebookPr}', instagramPr='{$instagramPr}', twitterPr='{$twitterPr}',
                            tiktokPr='{$tiktokPr}', websajt='{$sajt}'
                            WHERE idKorisnici='{$lid}'";
                    } else {
                        $update_query="UPDATE korisnici SET
                            email='{$emailNew}',
                            drzava='{$drzava}', grad='{$grad}',
                            facebookPr='{$facebookPr}', instagramPr='{$instagramPr}', twitterPr='{$twitterPr}',
                            tiktokPr='{$tiktokPr}', websajt='{$sajt}'
                            WHERE idKorisnici='{$lid}'";
                    }
                    $command_update= mysqli_query($conn, $update_query);

                    if($command_update == TRUE)
                    {
                        echo "<meta http-equiv='refresh' content='1'; url='profileedit.php?{$profil}'>";
                    }else{
                        echo "Greška " . mysqli_error($conn). "<br>";
                    }
                }else { 
                    // Invalid email
                    echo "<h4 class='warning'>Nije dobar format email-a.</h4>";
                }//end provjera emaila

            }//end if(!empty(["grad"]))
        }//end if(isset($_POST["izmjeni"]))
        ?>
        <section class="profilCentar">
            <p><span class="podebljano text-warning">Korisničko me:</span> <?php echo $username;?></p>
            <p><span class="podebljano text-warning">Ime i prezime</span> <?php echo $ime . " " . $prezime;?></p>
            <p><span class="podebljano text-warning">Pol:</span> <?php echo $pol;?></p>
            <p><span class="podebljano text-warning">Tip korisnika:</span> <?php echo $tipKorisnika;?></p>
            
            <p>Da bi ste izmjenili podatke iznad, kontaktirajte administratora</p>

             <?php 
            if(isset($_POST["novaSifra"]))
            {
                if(!empty($_POST["pass1"]) && !empty($_POST["pass2"]))
                {
                    $password1= trim($_POST["pass1"]);
                    $password2= trim($_POST["pass2"]);

                    if($password1 === $password2)
                    {
                        if (mb_strlen($password1, "UTF-8") < 8) 
                            {
                            echo "<h4 class='warning'>Šifra mora imati najmanje 8 karaktera</h4>";
                        } else 
                        {

                            // PRODUKCIONA VERZIJA (preporuka)
                            $sifrovano = password_hash($password1, PASSWORD_DEFAULT);
                            $sifrovano2= hash("gost-crypto", $password2);

                            $update_password = "UPDATE korisnici
                                                SET sifra='{$sifrovano}', sifra2='{$sifrovano2}'
                                                WHERE idKorisnici='{$lid}'";

                            $command_update_password = mysqli_query($conn, $update_password);

                            if($command_update_password == TRUE)
                            {
                                echo "<meta http-equiv='refresh' content='1'; url='profileedit.php?{$profil}'>";
                            }else{
                                echo "Greška " . mysqli_error($conn). "<br>";
                            }
                        }//end if else (dužina šifre)
                    }
                    else{
                        echo "<h4 class='warning'>Šifra nije ista</h4>";
                }//end if else(provera upoređivanja obe šifre)
                }
            }//end if(novaSifra)
            ?>
            <hr class="hrLinija">
        
            <form action="" enctype="multipart/form-data" method="POST" name="editProfile" id="editProfile">
                <div class="form-group col-md-6 mx-auto">
                    <input type="text" name="email" class="form-control" placeholder="Email" value="<?php echo $email;?>" required><br><br>
                </div><!-- end .form-group -->

                <div class="form-group col-md-6 mx-auto">
                    <label for="država" class="text-warning"><strong>Država </strong><img class='adminEditImage' src="../images/zastave/<?php echo "$drzava";?>" alt="<?php ?>"></label><br>
                    <select class="form-control" name="drzava" id="država">
                    <?php 
                    $q= "SELECT * FROM drzave2";
                    $select_drzavu= mysqli_query($conn, $q);

                    while($row= mysqli_fetch_assoc($select_drzavu))
                    {
                        $idDrzave2= $row["idDrzave2"];
                        $drzavaNaziv= $row["drzavaNaziv"];
                        $kodZemljeDugi= $row["kodZemljeDugi"];
                        $zastava= $row["zastava"];

                        if($kodZemljeDugi==$drzava){
                            echo "<option value='{$kodZemljeDugi}' selected>$drzavaNaziv</option>";
                        }else{
                            echo "<option value='{$kodZemljeDugi}'>$drzavaNaziv </option>";
                        }
                        echo "";
                    }//end while
                    ?>
                    </select> <br><br>
                </div><!-- end .form-group col-md-6 mx-auto -->

                <div class="form-group col-md-6 mx-auto">
                    <div class="input-group">
                        <input type="text" class="form-control" name="grad" placeholder="Grad" value="<?php echo $grad; ?>">
                    </div><!-- end .input-group --><br><br>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupPrepend2">https://www.facebook.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="facebook" placeholder="Facebook profil" value="<?php echo $facebookPr; ?>">
                    </div><!-- end .input-group --><br><br>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupPrepend2">https://www.instagram.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="instagram" placeholder="Instagram profil" value="<?php echo $instagramPr; ?>">
                    </div><!-- end .input-group --><br><br>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupPrepend2">https://www.x.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="twitter" placeholder="X profil" value="<?php echo $twitterPr; ?>">
                    </div><!-- end .input-group --><br><br>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupPrepend2">https://www.tiktok.com/@</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="tiktok" placeholder="Tik-tok profil" value="<?php echo $tiktokPr; ?>">
                    </div><!-- end .input-group --><br><br>

                    <label for="sajt"  class="text-warning"><strong>Unesite pun naziv sajta sa početkom kao https:// ili kao www.</strong></label><br>
                    <div class="input-group">
                        <input type="text" name="sajt" class="form-control" placeholder="Vaš sajt" value="<?php echo $sajt; ?>">
                    </div><!-- end .input-group --><br><br>

                    <div class="sredina">
                        <button type="submit" class="btn btn-primary" name="izmjeni" value="izmjeni">Izmjeni</button>
                    </div><!-- end .sredina -->
                </div><!-- end .form-group col-md-6 mx-auto -->
            </form>
            <hr class="hrLinija">
           
            <section class="sredina">
                <div class="form-group col-md-6 mx-auto">
                    <form action="" method="POST" name="editProfile" id="editProfile">
                        <h3>Promjena šifre</h3>
                        <h6 class="sredina">Šifra mora imati najmanje 8 karaktera</h6>
                        <div class="input-group">
                            <input type="password" name="pass1" class="form-control" placeholder="Unesite novu šifru">
                        </div><br><br><!-- end .input-group -->
                        
                        <div class="input-group">
                            <input type="password" name="pass2" class="form-control" placeholder="Ponovite novu šifru">
                        </div><br><br><!-- end .input-group -->
                
                        <button type="submit" class="btn btn-danger" name="novaSifra" value="novaSifra">Nova šifra</button>
                    </form>
                </div><!-- end .form-group -->
            </section><!-- .sredina -->
        </section><!-- .profilCentar -->
        <?php
    }//end if else(provjera sesije i id-a)  
}//end formaEditUser()

//********************************* Pozvana metoda u ovom fajlu u funkciji editUser *********************************//

//--------------------------------------------------------------------------------------------------------------------------------
