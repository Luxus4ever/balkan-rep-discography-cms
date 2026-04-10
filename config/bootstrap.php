<?php
// Pokreni sesiju za guest i ulogovane
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* STRANICA KOJA POZIVA config.php GDJE SE NALAZI I SESIJA. UKOLIKO JE PROBLEM SA config.php FAJLOM PRIKAZUJE GREŠKU DA SE ZNA U ČEMU JE PROBLEM.  *********************************//
//Pošto sam ovu stranicu uključio svuda, uklonio sam inkludovanje config.php kao i session_start jer se nalazi u config fajlu
//********************************* STRANICA POZVANA NA MNOGO MJESTA  *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

// Putanja do configa
$configPath = __DIR__ . "/config.php";

if (!file_exists($configPath)) {
    http_response_code(500);
    ?>
    <!DOCTYPE html>
    <html lang="sr">
    <head>
        <meta charset="UTF-8">
        <title>Greška konfiguracije</title>
        <link href="/css/style.css" rel="stylesheet">
        <style>
            body {
                background: #121212;
                color: #fff;
                font-family: 'Source Sans Pro', sans-serif;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
                text-align: center;
            }
            .error-box {
                background: #1e1e1e;
                padding: 40px;
                border-radius: 12px;
                box-shadow: 0 0 20px rgba(0,0,0,0.5);
            }
            .error-box h1 {
                color: #ff4c4c;
                margin-bottom: 15px;
            }
        </style>
    </head>
    <body>
        <div class="error-box">
            <h1>Greška pri učitavanju sajta</h1>
            <p>Konfiguracioni fajl nije pronađen ili je došlo do interne greške.</p>
            <p>Pokušajte ponovo kasnije.</p>
        </div>
    </body>
    </html>
    <?php
    exit;
}

require_once $configPath;


// 5) SADA možeš koristiti APP_ENV
if (defined('APP_ENV') && APP_ENV === 'production') {
    ini_set('display_errors', 0);
} else {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}