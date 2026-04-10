<?php
session_start();
include_once "../../config/config.php";

// postavi vremensku zonu
date_default_timezone_set('Europe/Belgrade');

if(isset($_SESSION['idKorisnici'])){
    $id = $_SESSION['idKorisnici'];
    echo $now = date("Y-m-d H:i:s");
    echo $sql = "UPDATE korisnici SET last_activity = '{$now}' WHERE idKorisnici = {$id}";
    mysqli_query($conn, $sql);
}//end if()
?>
