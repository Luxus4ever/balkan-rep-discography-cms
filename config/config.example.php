<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Europe/Belgrade');

/*********** POVEZIVANJE NA BAZU **********/
$conn = new mysqli("localhost", "root", "", "imeBaze");
global $conn;

// OBAVEZNO: koristi utf8mb4 umesto utf8
mysqli_set_charset($conn, "utf8mb4");

if($conn->connect_error){
    die("Greška konekcije: " . $conn->connect_error);
}
/*********** KRAJ POVEZIVANJA NA BAZU **********/

define('SITE_VERSION', 'v4.0 - 2026-02-16');


if (!defined('APP_ENV')) {
    if ($_SERVER['SERVER_NAME'] === 'localhost') {
        define('APP_ENV', 'local');
    } else {
        define('APP_ENV', 'production');
    }
}


if (!defined('SITE_URL')) {
    // LOCAL (WAMP) primjer – prilagodi folderu ako treba
    define('SITE_URL', 'http://localhost/repdiskografija');
}

require_once __DIR__ . '/../functions/log.func.php';

