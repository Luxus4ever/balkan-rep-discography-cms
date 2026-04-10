<?php
session_start();
include_once "../../config/config.php";

$my_id = $_SESSION['idKorisnici'];
$other_id = intval($_POST['other_id'] ?? 0);

$q = "SELECT * FROM blocked_users WHERE blocker_id = {$my_id} AND blocked_id = {$other_id}";
$res = mysqli_query($conn, $q);

if(mysqli_num_rows($res) > 0){
    echo "blocked"; // ti si blokirao drugog
    exit;
}//end if()

$q2 = "SELECT * FROM blocked_users WHERE blocker_id = {$other_id} AND blocked_id = {$my_id}";
$res2 = mysqli_query($conn, $q2);

if(mysqli_num_rows($res2) > 0){
    echo "blocked_by_other"; // drugi je blokirao tebe
    exit;
}//end if()

echo "none";
