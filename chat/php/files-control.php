<?php
// Ovaj fajl omogućava uključivanje i isključivanje slanja fajlova (slike i video)
// Radi na principu toggle/check i vraća uvek "enabled" ili "disabled" string

session_start();
include_once "../../config/config.php";

if(!isset($_SESSION['idKorisnici'])){
    http_response_code(401);
    exit("Not logged in");
}//end if()

$my_id   = $_SESSION['idKorisnici'];
$other_id = intval($_POST['other_id'] ?? 0);
$action   = $_POST['action'] ?? '';

if(!$other_id){
    exit("Invalid user");
}//end if()

// --- TOGGLE (enable/disable) ---
if($action === "toggle")
{
    $q = "SELECT * FROM disabled_files 
          WHERE (user1_id = {$my_id} AND user2_id = {$other_id})";
    $res = mysqli_query($conn, $q);

    if(mysqli_num_rows($res) > 0){
        // Ako već postoji red, obrni stanje
        $row = mysqli_fetch_assoc($res);
        $new_state = $row['is_disabled'] ? 0 : 1;
        mysqli_query($conn, "UPDATE disabled_files 
                             SET is_disabled = {$new_state} 
                             WHERE id = {$row['id']}");
        echo $new_state ? "disabled" : "enabled";
    }else{
        // Ako prvi put klikće → počni od *enabled* (0)
        // pa tek onda klikom možeš isključiti (1)
        mysqli_query($conn, "INSERT INTO disabled_files (user1_id, user2_id, is_disabled) 
                             VALUES ({$my_id}, {$other_id}, 0)");
        echo "enabled";
    }//end if else
    exit;
}


// --- CHECK STATUS ---
// ovde pitamo: "da li JE ONAJ DRUGI zabranio MENI?"
$q = "SELECT is_disabled FROM disabled_files 
      WHERE user1_id = {$other_id} AND user2_id = {$my_id}";
$res = mysqli_query($conn, $q);

if(mysqli_num_rows($res) > 0){
    $row = mysqli_fetch_assoc($res);
    echo $row['is_disabled'] ? "disabled" : "enabled";
}else{
    echo "disabled";
    //echo "enabled" //for deafult enabled to send files, also change in db in disabled_files on 0
}//end if else
exit;


//********************************************************************************************************************* */
//---------------Ovaj kod ispod je da je po defaultu isključeno slanje fajlova.

/*
session_start();
include_once "../../config/config.php";

if(!isset($_SESSION['idKorisnici'])){
    http_response_code(401);
    exit("Not logged in");
}

$my_id   = $_SESSION['idKorisnici'];
$other_id = intval($_POST['other_id'] ?? 0);
$action   = $_POST['action'] ?? '';

if(!$other_id) exit("Invalid user");

// --- TOGGLE (enable/disable) ---
if($action === "toggle"){
    $q = "SELECT * FROM disabled_files 
          WHERE (user1_id = {$my_id} AND user2_id = {$other_id}) 
             OR (user1_id = {$other_id} AND user2_id = {$my_id})";
    $res = mysqli_query($conn, $q);

    if(mysqli_num_rows($res) > 0){
        // Ako par već postoji u tabeli → menjamo is_disabled
        $row = mysqli_fetch_assoc($res);
        $new_state = $row['is_disabled'] ? 0 : 1;
        mysqli_query($conn, "UPDATE disabled_files 
                             SET is_disabled = {$new_state} 
                             WHERE id = {$row['id']}");
        echo $new_state ? "disabled" : "enabled";
    } else {
        // Ako ne postoji red u tabeli → kreiramo ga sa is_disabled=0 (po defaultu zabranjeno)
        // Znači prvi klik će "uključiti" opciju, jer polazno stanje tretiramo kao disabled
        mysqli_query($conn, "INSERT INTO disabled_files (user1_id, user2_id, is_disabled) 
                             VALUES ({$my_id}, {$other_id}, 0)");
        echo "enabled";
    }
    exit;
}

// --- CHECK STATUS ---
if($action === "check"){
    $q = "SELECT is_disabled FROM disabled_files 
          WHERE (user1_id = {$my_id} AND user2_id = {$other_id}) 
             OR (user1_id = {$other_id} AND user2_id = {$my_id})";
    $res = mysqli_query($conn, $q);

    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_assoc($res);
        echo $row['is_disabled'] ? "disabled" : "enabled";
    } else {
        // 🚩 Po defaultu tretiramo kao "disabled"
        echo "disabled";
    }
    exit;
}

echo "Invalid action";
*/