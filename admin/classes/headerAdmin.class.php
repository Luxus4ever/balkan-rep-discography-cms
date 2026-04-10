<?php
require_once __DIR__ . '/../../config/bootstrap.php';

class header{

    //METODE SADRŽANE U OVOJ KLASI
    //construct (daje validaciju i pristup)

    protected $sesId;
    protected $lid;
    protected $username;
    protected $statusK;

    //********************************* Metoda koja provjerava validaciju korisnika i dajue mu određeni pristup  *********************************//
    public function __construct()
    {
        global $conn;
        date_default_timezone_set('Europe/Belgrade');
        $_SESSION["vrijeme"]= date('H:i:s');
        @$this->sesId= $_SESSION["idKorisnici"];
        @$statusKorisnika= $_SESSION["statusKorisnika"];
        
        $trenutnoVrijeme = date('Y-m-d H:i:s');

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

        if (isset($_SESSION['idKorisnici']))
        {
            $this->lid= $_SESSION["idKorisnici"];
            $this->username= $_SESSION["username"];
            //$this->statusK= $_SESSION["statusK"];
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
                                    <a href="../kontaktAdmin.php">Kontaktiraj Administratora
                                        <?php if ($nove_poruke > 0): ?>
                                        <span class="unread-badge-contact"><?= $nove_poruke ?></span>
                                        <?php endif; ?>
                                    </a>
                                </li>

                                <li>
                                    <a href='../korisnik_obavjestenja.php' style="color:aliceblue">
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
                </div><!--end .kontaktNav -->
                <nav class='loginNav'>
                <ul>
                    <li><a href='../dodajalbume.php'>Dodaj albume/izvođače</a></li>
                    <li>
                        <a href='../chat/users.php?username=<?php echo $this->username; ?>&lid=<?php echo $this->lid; ?>'>Poruke <span id="unread-counter" class="unread-badge" style="display:none;">0</span>
                        </a>
                    </li>
                    <li><a href='../profile.php?username=<?php echo $this->username; ?>&lid=<?php echo $this->lid; ?>'>Moj Profil</a></li>
                    <li><a href='../process/logout.process.php'>Odjava</a></li>
                </ul>
            </nav><!-- end .loginNav -->
            </div><!-- end.pomocniNavPanel -->
            </div><!-- end .pomocniNav -->
            <?php
        }else
            {
                ?>
                <nav class='loginNav'>
                    <ul>
                        <li><a href='../login.php'>Prijava</a></li>
                    </ul>
                </nav><!-- end .loginNav -->
                <?php
            }
    }//end construct function

    //********************************* Pozvana metoda u headerAdmin.php *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    //*********************************  *********************************//
}//end class