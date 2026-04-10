<?php
session_start();
include_once "../../config/config.php";

if(isset($_SESSION['idKorisnici']))
{
    $outgoing_id = $_SESSION['idKorisnici'];
    $incoming_id = intval($_POST['incoming_id']);

    $sql = "SELECT typing_to FROM korisnici WHERE idKorisnici = {$incoming_id}";
    $result = mysqli_query($conn, $sql);

    if($row = mysqli_fetch_assoc($result)){
        if($row['typing_to'] == $outgoing_id){
            echo "typing";
        }else{
            echo "not_typing";
        }//end if else
    }//end if(2)
}//end if(1)
?>
