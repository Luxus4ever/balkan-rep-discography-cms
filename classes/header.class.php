<?php

class header{

    //METODE SADRŽANE U OVOJ KLASI
    //__construct (prikaz drugog dodatnog menija)


    //********************************* Prikaz drugog dodatnog menija, kao i vrijeme logina *********************************//
    public function __construct($putanja="")
    {
        global $conn;
        

        date_default_timezone_set('Europe/Belgrade');
        $_SESSION["vrijeme"]= date('H:i:s');
        $sesId = $_SESSION['idKorisnici'] ?? null;
        @$statusKorisnika= (int) $_SESSION["statusKorisnika"];

        if (isset($_SESSION['idKorisnici'])) 
        {
            $userId = (int)$_SESSION['idKorisnici'];
            $qUnread = "SELECT COUNT(*) AS neprocitana 
                        FROM korisnik_obavjestenja 
                        WHERE idKorisnik = $userId AND procitano = 0";
            $rUnread = mysqli_query($conn, $qUnread);
            $neprocitana = 0;
            if ($r = mysqli_fetch_assoc($rUnread)) {
                $neprocitana = (int)$r['neprocitana'];
            }
        } else {
            $neprocitana = 0;
        }

        if(empty($sesId))
        {
            ?>
            <nav class='loginNav'>
                
                <ul id="loginNavMenu">
                    <li><a href='<?php echo $putanja; ?>login.php'>Prijava</a></li>
                    <li><a href='<?php echo $putanja; ?>registracija.php'>Registracija</a></li>
                </ul>
            </nav><!-- end .loginNav -->
            <?php
        }else
        {

            if ($sesId) {
                $sesId = (int)$sesId;
                $q1 = "UPDATE korisnici SET zadnjiLogin=NOW() WHERE idKorisnici=$sesId";
                mysqli_query($conn, $q1);
            }

            if (isset($_SESSION['idKorisnici']))
            {
                $lid= $_SESSION["idKorisnici"];
                $username= $_SESSION["username"];
                ?>
                <div class="pomocniNav">
                    <button class="hamburger" type="button" data-target="#pomocniNavPanel" aria-expanded="false" aria-label="Otvori korisnički meni">
                    <span></span><span></span><span></span>
                </button>

                <div id="pomocniNavPanel" class="pomocniNavPanel">
                    <div class="kontaktNav">
                        <ul>
                            <?php
                            if($statusKorisnika!=0)
                            {
                                $q = "SELECT COUNT(*) AS nove_poruke FROM poruke_admin 
                                    WHERE idPosiljalac = '{$_SESSION['idKorisnici']}' 
                                    AND odgovorAdmin IS NOT NULL 
                                    AND procitanoKorisnik = 0";

                                $res = mysqli_query($conn, $q);
                                $nove_poruke = 0;
                                if ($row = mysqli_fetch_assoc($res)) {
                                    $nove_poruke = (int)$row['nove_poruke'];
                                }

                                ?>
                                <li>
                                    <a href="kontaktAdmin.php">Kontaktiraj Administratora
                                        <?php if ($nove_poruke > 0): ?>
                                        <span class="unread-badge-contact"><?= $nove_poruke ?></span>
                                        <?php endif; ?>
                                    </a>
                                </li>

                                <li>
                                    <a href='korisnik_obavjestenja.php' style="color:aliceblue">
                                        Obaveštenja
                                        <?php if ($neprocitana > 0): ?>
                                            <span class="unread-badge-obav"><?= $neprocitana ?></span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <?php
                            }//end if
                            ?>
                        </ul>
                    </div><!-- end .kontaktNav -->

                    <nav class='loginNav'>
                        <ul>
                            <?php
                            if($statusKorisnika!=0)
                            {
                                ?>
                                <li><a href='dodajalbume.php'>Dodaj albume/izvođače</a></li>
                                <?php
                            }
                            ?>
                            <?php
                            if($statusKorisnika!==0)
                            {
                                ?>
                                <li>
                                    <a href='chat/users.php?username=<?php echo $username ?>&lid=<?php echo $lid; ?>'>Poruke <span id="unread-counter" class="unread-badge" style="display:none;">0</span>
                                    </a>
                                </li>
                                <?php
                            }else{ echo "";}
                            ?>
                            <li><a href='profile.php?username=<?php echo $username ?>&lid=<?php echo $lid; ?>'>Moj Profil</a></li>
                            <li><a href='process/logout.process.php'>Odjava</a></li>
                        </ul>
                    </nav><!-- end .loginNav -->
                    </div><!-- end.pomocniNavPanel -->
                </div><!-- end .pomocniNav -->
                <?php
            }//end if(isset($_SESSION['idKorisnici']))
        }//end if(empty($sesId))
    }//end __construct
    //********************************* Pozvana metoda u header.php, headerAdmin.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------


}//end class header