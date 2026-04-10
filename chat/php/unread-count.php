<?php
session_start();
include_once "../../config/config.php";

if(!isset($_SESSION['idKorisnici'])) {
    echo 0;
    exit;
}//end if()

$outgoing_id = $_SESSION['idKorisnici'];

// Prebroj sve nepročitane poruke za ovog korisnika
$sql = "SELECT COUNT(*) AS unread_count 
        FROM messages 
        WHERE incoming_msg_id = {$outgoing_id} 
        AND is_read = 0";
$query = mysqli_query($conn, $sql);

if($query && $row = mysqli_fetch_assoc($query)){
    echo intval($row['unread_count']);
}else{
    echo 0;
}//end if else
