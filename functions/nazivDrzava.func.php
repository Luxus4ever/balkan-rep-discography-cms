<?php
//FUNKCIJE U OVOM FAJLU
//brojAlbuma (Prikaz broja dodatih albuma na početnoj)
//brojAlbumaEnt (Prikaz broja dodatih albuma po entitetima)
//nazivDrzave (Prikaz naziva države na početnoj)

//********************************* Prikaz broja dodatih albuma po državama na početnoj *********************************//
function brojAlbuma($idDrzaveParam="")
{
    global $conn;
    $q= "SELECT count(drzavaAlbumi) AS ukupnoAl FROM albumi WHERE drzavaAlbumi= '{$idDrzaveParam}'";
    $brAlb= mysqli_query($conn, $q);

    while($row= mysqli_fetch_assoc($brAlb))
    {
        $brojAl= $row["ukupnoAl"];
    }//end while

    return $brojAl;
}//end brojAlbuma()
//********************************* Pozvana metoda u ovom fajlu u metodi nazivDrzave() *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Prikaz broja dodatih albuma po entitetima na početnoj *********************************//

function brojAlbumaEnt($idEntiteti="")
{
    global $conn;
    $q= "SELECT count(entitetAlbumi) AS ukupnoAl FROM albumi WHERE entitetAlbumi= '{$idEntiteti}'";
    $brAlb= mysqli_query($conn, $q);

    while($row= mysqli_fetch_assoc($brAlb))
    {
        $brojAl= $row["ukupnoAl"];
    }//end while
    
    return $brojAl;
}//end brojAlbumaEnt()
//********************************* Pozvana metoda u ovom fajlu u metodi nazivDrzave() *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Prikaz naziva države na početnoj *********************************//

function nazivDrzave()
{
    global $conn;
    $q= "SELECT * FROM drzave";
    $ispis= mysqli_query($conn, $q);
    $sviAlb= new albumDetalji();

    while($row= mysqli_fetch_array($ispis))
    {
        $idDrzave= $row['idDrzave'];
        $nazivDrzave= $row['nazivDrzave'];
        $entitet1= $row['entitet1'];
        $entitet2= $row['entitet2']; 
        $zastavaDrzave= $row['zastavaDrzave'];

        $cleanNazivDrzave= str_replace(" ", "-", removeSpecialLetters($nazivDrzave));

        echo '<div class="naslovna5">';
        ?>
        <h1 class="drzava">
            <span class="boja"></span>
            <div class="zastava"><img class="zastava" src="images/zastave/<?php echo $zastavaDrzave; ?>" alt="<?php echo $nazivDrzave; ?>" title="<?php echo $nazivDrzave; ?>"></div>
            <a class="clickLink" href="drzava.php?nazivdrzave=<?php echo $cleanNazivDrzave; ?>"><?php echo $nazivDrzave; ?></a><br>
        </h1>
        <?php
        if(empty($entitet1) || empty($entitet2))
        {

            $sviAlb->sviAlbumiPocetna($idDrzave);
        }//end if

        $q2= "SELECT idEntiteti, entitetNaziv, zastavaEnt FROM entiteti WHERE entDrzava='{$idDrzave}'";
        $entIspis= mysqli_query($conn, $q2);

        while($row2= mysqli_fetch_array($entIspis))
        {
            $idEntiteti= $row2['idEntiteti'];
            $entitet= $row2['entitetNaziv'];
            $zastavaEnt= $row2['zastavaEnt'];

            $cleanEntitet= str_replace(" ", "-", removeSpecialLetters($entitet));
            echo "<br>";
            ?>
            <h3>
                <div class="zastava"><img class="zastava" src="images/zastave/<?php echo $zastavaEnt; ?>" alt="<?php echo $entitet; ?>" title="<?php echo $entitet; ?>">
                </div><!-- end .zastava -->
                <a class="clickLink" href="drzava.php?ent=<?php echo str_replace(" ", "-", $cleanEntitet); ?>"><?php echo $entitet; ?></a>
            </h3>
            <?php 
            $sviAlb->sviAlbumiEntitet($idEntiteti);
            ?>
            <br>
            <p>Trenutno dodatih albuma za <?php echo $entitet . " je " .  brojAlbumaEnt($idEntiteti);  ?></p><br>
            <br>
            <?php
        }//end while
        ?>
        <br>
        <p>Trenutno dodatih albuma za državu <?php echo $nazivDrzave . " je " .  brojAlbuma($idDrzave);  ?></p><br>
        </div>
        <hr class="hrLinija1">
        <?php
    }//end while
}//end nazivDrzave()

//********************************* Pozvana metoda u index.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------