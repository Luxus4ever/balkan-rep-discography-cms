<?php
/*session_start();
include_once "../../config/config.php";

$outgoing_id = $_SESSION['idKorisnici'];
$searchTerm = mysqli_real_escape_string($conn, $_POST['searchTerm']);

$sql = "SELECT * FROM korisnici 
        WHERE idKorisnici != {$outgoing_id} 
        AND (ime LIKE '%{$searchTerm}%' OR prezime LIKE '%{$searchTerm}%' OR username LIKE '%{$searchTerm}%')";
$output = "";
$query = mysqli_query($conn, $sql);

if(mysqli_num_rows($query) > 0){
    include_once "chat-users-list.php";
}else{
    $output .= 'Nema korisnika vezanih za pojam pretrage';
}//end if else
echo $output;*/
?>


<?php
session_start();
include_once "../../config/config.php";

if(!isset($_SESSION['idKorisnici'])){
    exit("Nema sesije");
}

$outgoing_id = $_SESSION['idKorisnici'];
$searchTerm  = mysqli_real_escape_string($conn, $_POST['searchTerm']);

$output = "";

/*
    Tražimo samo one korisnike:
    - koji NISU ja
    - čije ime/prezime/username odgovara pretrazi
    - i sa kojima VEĆ imam bar jednu poruku (kao posiljalac ili primalac)
*/
$sql = "
    SELECT DISTINCT k.*
    FROM korisnici k
    INNER JOIN messages m
        ON (
               (m.outgoing_msg_id = {$outgoing_id} AND m.incoming_msg_id = k.idKorisnici)
            OR (m.incoming_msg_id = {$outgoing_id} AND m.outgoing_msg_id = k.idKorisnici)
           )
    WHERE k.idKorisnici != {$outgoing_id}
      AND (
            k.ime      LIKE '%{$searchTerm}%'
         OR k.prezime  LIKE '%{$searchTerm}%'
         OR k.username LIKE '%{$searchTerm}%'
      )
    ORDER BY k.username ASC
";

$query = mysqli_query($conn, $sql);

if(mysqli_num_rows($query) > 0){
    // ovo je tvoj fajl koji crta <a> linkove
    include_once "chat-users-list.php";
}else{
    $output .= '<div class="details" style="color: red;">Nema korisnika sa kojima već imaš razgovor za ovaj pojam.</div>';
}

echo $output;
?>

