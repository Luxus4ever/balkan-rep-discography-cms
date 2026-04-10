<?php
//FUNKCIJE U OVOM FAJLU
//sviAlbumi (Prikaz svih albuma izabranog izvođača)

//********************************* Prikaz svih albuma izabranog izvodjaca *********************************//
function sviAlbumi($idIzvodjacAlbumi, $izvodjacMaster) 
{
    global $conn;
    $q1= "SELECT * FROM albumi
    WHERE idIzvodjacAlbumi='{$idIzvodjacAlbumi}' OR idIzvodjac2='{$idIzvodjacAlbumi}' ORDER BY godinaIzdanja";

    $select_album= mysqli_query($conn, $q1);

    $q2= "SELECT * FROM albumi JOIN izvodjaci ON izvodjaci.idIzvodjaci= albumi.idIzvodjacAlbumi
    WHERE idIzvodjacAlbumi='{$idIzvodjacAlbumi}' OR idIzvodjac2='{$idIzvodjacAlbumi}' OR idIzvodjac3='{$idIzvodjacAlbumi}' ORDER BY godinaIzdanja";

    $select_album= mysqli_query($conn, $q2);
    ?>
    <div class="ostaliAlbumi">
        <div class="slikeAlbuma">
            <hr>
            <?php
            echo "<h3>Svi albumi - <span class='boja'>". $izvodjacMaster . "</span></h3>";//-----
            while($row= mysqli_fetch_array($select_album))
            {
                $idAlbum= $row["idAlbum"];
                $idIzvodjacAlbumi= $row["idIzvodjacAlbumi"];
                $izvodjacMaster= $row["izvodjacMaster"];
                $nazivAlbuma= $row["nazivAlbuma"];
                $godinaIzdanja= $row["godinaIzdanja"];
                $slikaAlbuma= $row["slikaAlbuma"];
                $drzavaAlbumi= $row["drzavaAlbumi"];
                $entitetAlbumi= $row["entitetAlbumi"];
                //data-lightbox="slika-2" // unutar taga da uveća sliku. Svaki broj posebna galerija

                $cleanIzvodjacMaster= konverzijaLatinica($izvodjacMaster);
                $cleanNazivAlbuma= konverzijaLatinica($nazivAlbuma);
                $cleanIzvodjacMaster= removeSerbianLetters(str_replace(" ", "+", removeSpecialLetters($cleanIzvodjacMaster)));
                $cleanNazivAlbuma= removeSerbianLetters(cleanAlbum($cleanNazivAlbuma));
                ?>
                <div class="myCard">
                    <a href="oalbumu.php?izv=<?php echo $idIzvodjacAlbumi."&album=".$idAlbum."&naziv=".$cleanIzvodjacMaster."-".$cleanNazivAlbuma; ?>" >
                        <img loading="lazy" src="images/albumi/<?php echo $slikaAlbuma; ?>" alt="<?php echo $nazivAlbuma; ?>" title="<?php echo $nazivAlbuma; ?>">
                    </a>
                </div><!-- end .myCard -->
                <?php
            }//end while
            ?>
        </div><!-- end .slikeAlbuma -->
    </div><!-- end .ostaliAlbumi -->
    <?php
}// end function sviAlbumi()
//********************************* Pozvana metoda u detaljiIzvodjac.class.php *********************************//
