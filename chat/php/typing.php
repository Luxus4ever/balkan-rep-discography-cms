<?php
session_start();
include_once "../../config/config.php";

if(isset($_SESSION['idKorisnici'])){
    $outgoing_id = $_SESSION['idKorisnici'];
    $typing_to   = isset($_POST['typing_to']) ? intval($_POST['typing_to']) : 0;

    $sql = "UPDATE korisnici SET typing_to = {$typing_to} WHERE idKorisnici = {$outgoing_id}";
    mysqli_query($conn, $sql);
}//end if()
?>
