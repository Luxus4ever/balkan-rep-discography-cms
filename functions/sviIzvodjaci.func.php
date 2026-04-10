<?php
//METODE U OVOM FAJLU
//izvodjaciAbecedno (Spisak svih izvođača na sajtu, abecedno poredano)
//PREPRAVITI DA I SLOVO Đ IDE IDE ODMAH POSLE D, TJ. SE KORISTI SRPSKA LATINIČNA ABECEDA


/************************ Spisak svih izvođača ************************/

function izvodjaciAbecedno(){
    global $conn;
    $q= "SELECT * FROM izvodjaci 
    JOIN drzave ON drzave.idDrzave=izvodjaci.drzavaIzvodjac
    ORDER BY izvodjacMaster";
    $q_Izvodjaci= mysqli_query($conn, $q);

    ?>
    <div class="albumPrikaz">
        <br>
        <h2>Spisak svih izvođača</h2>
        <?php
            while($row= mysqli_fetch_assoc($q_Izvodjaci))
            {
            $idIzvodjaci= $row["idIzvodjaci"];
            $izvodjacMaster= $row["izvodjacMaster"];
            $nazivDrzave= $row["nazivDrzave"];

            $cleanIzvodjacMaster= konverzijaLatinica($izvodjacMaster);

            $idIzv = getIzvodjacIdByMaster($izvodjacMaster);

            //removeSerbianLetters($izvodjacMaster)
            ?>
            
                <div class="slikeAlbuma">
                    <a class="clickLink" href="izvodjac.php?idIzv=<?php echo $idIzv; ?>&izvodjac=<?php echo removeSpecialLetters($izvodjacMaster); ?>">
                    <?php echo "$izvodjacMaster ($nazivDrzave)"; ?>
                    </a>
            </div><!-- end .slikeALbuma -->
            <?php
            }
            ?>
    </div><!-- end .albumPrikaz -->
    <?php
}

