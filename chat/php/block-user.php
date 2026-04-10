<?php
session_start();
include_once "../../config/config.php";

if(!isset($_SESSION['idKorisnici'])){
    http_response_code(401);
    exit("Not logged in");
}//end if()

if(!isset($_POST['blocked_id'])){
    exit("No blocked_id in request");
}//end if()

file_put_contents("debug_block.txt", print_r($_POST, true), FILE_APPEND);

$blocker_id = $_SESSION['idKorisnici'];
$blocked_id = intval($_POST['blocked_id'] ?? 0);

if(!$blocked_id) exit("Invalid user");

$q = "SELECT * FROM blocked_users WHERE blocker_id = {$blocker_id} AND blocked_id = {$blocked_id}";
$res = mysqli_query($conn, $q);

if(mysqli_num_rows($res) > 0){
    // već blokiran -> odblokiraj
    mysqli_query($conn, "DELETE FROM blocked_users WHERE blocker_id = {$blocker_id} AND blocked_id = {$blocked_id}");
    // ✅ LOG: UNBLOCK
    logChatBlockAction($blocked_id, $otherUsername ?? '', 'unblock');
    echo "unblocked";
}else{
    // blokiraj
    mysqli_query($conn, "INSERT INTO blocked_users (blocker_id, blocked_id) VALUES ({$blocker_id}, {$blocked_id})");
    // ✅ LOG: BLOCK
    logChatBlockAction($blocked_id, $otherUsername ?? '', 'block');
    echo "blocked";
}//end if else
