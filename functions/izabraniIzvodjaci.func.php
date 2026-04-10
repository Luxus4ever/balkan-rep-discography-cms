<?php
//FUNKCIJE U OVOM FAJLU
//izabraniIzvodjac (Prikazuje izabrane izvođače u sklopu albuma po državama)

//********************************* Prikazuje izvođače odnosno albume, kada se izabere opcija po državama *********************************//
function izabraniIzvodjac($izvodjacMaster, $idIzvodjac2="", $idIzvodjac3="")
{
    global $conn;
    include "master.func.php";
    $q2= "SELECT * FROM izvodjaci WHERE idIzvodjaci='{$idIzvodjac2}'";
    $select_izvodjace=mysqli_query($conn, $q2);

    while($row2= mysqli_fetch_array($select_izvodjace))
    {
        $izvodjac2= $row2["izvodjacMaster"];
        $clanoviOveGrupe= $row2["clanoviOveGrupe"];

        $q3= "SELECT * FROM izvodjaci WHERE idIzvodjaci='{$idIzvodjac3}'";
        $select_izvodjace3=mysqli_query($conn, $q3);

        if(mysqli_num_rows($select_izvodjace3)>0)
        {
            while($row3= mysqli_fetch_array($select_izvodjace3))
            {
                $izvodjac3= $row3["izvodjacMaster"];
            }//end while 2
            
        }//end if($select_izvodjace3)
    }//end while 1
        
    if(!empty($izvodjac3))
    {
        echo $izvodjacMaster . ", " . $izvodjac2 . ", " . $izvodjac3;
        
    }else if(!empty($izvodjac2)){
        echo $izvodjacMaster . " & " . $izvodjac2;
    }else{
        echo $izvodjacMaster; 
    }//end if else
}//end izabraniIzvodjac()

//********************************* Pozvana funkcija u fajlovima albumiPoDrzavi.func.php i albumiPoEntitetima.func.php *********************************//


//--------------------------------------------------------------------------------------------------------------------------------