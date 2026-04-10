<?php
require_once "config/bootstrap.php";
include "classes/detaljiAlbum.class.php";
include "classes/userOnline.class.php";
include "functions/master.func.php";
include "header.php";

headerPutanja();
?>
<div id="wrapper">
    <div class="slikeAlbumaPregled sredina">
        <main>
            <?php

            @$profil= $_GET["username"];
            @$lid= (int) $_GET["lid"];
            @$sesId= (int) $_SESSION["idKorisnici"];
            @$sesVrijeme= $_SESSION["vrijeme"];
            @$statusKorisnika= (int) $_SESSION["statusKorisnika"];  
            $d= time();

            
                       
            if($lid===$sesId){
                if (isset($_SESSION['idKorisnici'])) {
                    $uid = (int)$_SESSION['idKorisnici'];
                    $q = "SELECT email, email_verified FROM korisnici WHERE idKorisnici='{$uid}' LIMIT 1";
                    $r = mysqli_query($conn, $q);
                    $u = $r ? mysqli_fetch_assoc($r) : null;

                    if ($u && (int)$u['email_verified'] === 0) {
                        $emailMasked = htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8');
                        ?>
                        <div class="alert alert-warning d-flex justify-content-between align-items-center mb-0 rounded-0" role="alert">
                            <div>
                                <strong>Email nije potvrđen.</strong>
                                Potvrdite email da biste omogućili email obavještenja i lakši oporavak naloga.
                                <span class="ms-2 text-muted">(<?php echo $emailMasked; ?>)</span>
                            </div>
                            <div class="d-flex gap-2">
                                <a class="btn btn-dark btn-sm" href="resend_verify.php">Pošalji verifikacioni link</a>
                                <a class="btn btn-outline-dark btn-sm" href="profileedit.php?username=<?php echo htmlspecialchars($_SESSION['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>&lid=<?php echo $uid; ?>">Promijeni email</a>
                            </div>
                        </div>
                        <?php
                    }
                }
            }//end if($lid===$sesId)

            if(empty($profil) || empty($lid)){
                zabranjenPristupBezValidacije($sesId);
            }else{
                if(!empty($profil) && !empty($lid)){
                    detailsUser($profil, $lid, $sesId);
                }
            }//end if else
            ?>
        </main>
    </div> <!-- kraj .slikeAlbumaPregled -->
</div> <!-- kraj #wrapper -->
<?php
include "footer.php";
footerPutanja();


