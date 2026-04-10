<?php
session_start();
/*unset ($_SESSION['idKorisnici']);
session_destroy();
header("Location: ../index.php");
*/
include_once "../config/config.php";

if(isset($_SESSION['idKorisnici'])){
    $id = $_SESSION['idKorisnici'];
    // Odmah postavi status offline
    $sql = "UPDATE korisnici SET last_activity = NULL WHERE idKorisnici = {$id}";
    mysqli_query($conn, $sql);

    session_unset();
    session_destroy();
}
header("Location: ../index.php");
exit;