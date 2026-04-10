<?php
require_once "config/bootstrap.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['idObav']) && isset($_SESSION['idKorisnici'])) {
    $idKor = (int)$_SESSION['idKorisnici'];
    $idObav = (int)$_POST['idObav'];

    $q = "UPDATE korisnik_obavjestenja 
          SET procitano=1 
          WHERE idKorisnik=$idKor AND idObavjestenje=$idObav";
          
    if(mysqli_query($conn, $q)) {
        echo "ok";
    } else {
        echo "Greška";
    }
}//end if(isset())
