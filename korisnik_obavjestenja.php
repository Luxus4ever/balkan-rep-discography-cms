<?php
require_once "config/bootstrap.php";
include "functions/master.func.php";
include "header.php";
include_once "classes/kontaktAdmin.class.php";

headerPutanja();

@$profil= $_GET["username"];
@$lid=$_GET["lid"];
@$sesId= $_SESSION["idKorisnici"];
@$sesVrijeme= $_SESSION["vrijeme"];
@$statusKorisnika= $_SESSION["statusKorisnika"];
@$usernameSession= $_SESSION["username"];

@$nazivPromjeneLinka= $_GET['data'];
@$lid=$_GET["lid"];
@$statusKorisnika= $_SESSION["statusKorisnika"];
@$slid= $_SESSION["idKorisnici"];

if(empty($sesId) || empty($usernameSession) || empty($sesVrijeme) || empty($statusKorisnika)){

    zabranjenPristupBezValidacije($sesId);
}else
    {
        $idKor = $_SESSION['idKorisnici'];

        $q = "SELECT o.idObavjestenje, o.naslov, o.tekst, o.slika, o.datum, ko.procitano
        FROM korisnik_obavjestenja ko
        JOIN obavjestenja o ON o.idObavjestenje = ko.idObavjestenje
        WHERE ko.idKorisnik = $idKor
        ORDER BY o.datum DESC";

        $rezultat = mysqli_query($conn, $q);
        ?>
        <div class="slikeAlbumaPregled">
        <h4 class="text-warning">Obaveštenja</h4><hr>
        <?php
        if (mysqli_num_rows($rezultat) > 0) 
        {
            echo '<ul class="list-group">';
            while ($r = mysqli_fetch_assoc($rezultat)) {
                $id     = $r["idObavjestenje"];
                $naslov = htmlspecialchars($r["naslov"]);
                $tekst  = nl2br(htmlspecialchars($r["tekst"]));
                $slika  = $r["slika"];
                $datum  = date("H:i:s - d.m.Y", strtotime($r["datum"]));
                $procitano = $r["procitano"];

                if ($procitano) {
                    // ✅ PROČITANO
                    echo "<li id='obav$id' class='list-group-item d-flex flex-column obav-procitano'>";
                    echo "<h5 class='naslov-obav'>$naslov</h5>";
                    echo "<p class='tekst-obav'>$tekst</p>";

                    if (!empty($slika)) {
                        echo "<a href='images/uploads_obavjestenja/$slika' data-lightbox='obav-$naslov'>
                                <img src='images/uploads_obavjestenja/$slika' width='150'>
                            </a>";
                    }

                    echo "<small class='datum-obav'>$datum</small>";
                    echo "<span class='badge badge-success mt-2 text-dark'>Pročitano</span>";
                    echo "</li>";
                } else {
                    // ⚠️ NEPROČITANO
                    echo "<li id='obav$id' class='list-group-item d-flex flex-column obav-neprocitano'>";
                    echo "<h5 class='naslov-obav font-weight-bold'>$naslov</h5>";
                    echo "<p class='tekst-obav'>$tekst</p>";

                    if (!empty($slika)) {
                        echo "<a href='images/uploads_obavjestenja/$slika' data-lightbox='obav-$naslov'>
                                <img src='images/uploads_obavjestenja/$slika' width='150'>
                            </a>";
                    }

                    echo "<small class='datum-obav'>$datum</small>";
                    echo "<button class='btn btn-sm btn-dark mt-2 oznaci' data-id='$id'>✅ Označi kao pročitano</button>";
                    echo "</li>";
                }
            }
            echo '</ul>';
        } else {
            echo "<div class='alert alert-info'>Nemate nova obaveštenja.</div>";
        }//end if else

        echo '</div>';



    }//end if else()
    ?>
    </div><!-- end .slikeAlbumaPregled sredina -->
</div><!-- end .wrapper -->
<?php
include "footer.php";
footerPutanja();