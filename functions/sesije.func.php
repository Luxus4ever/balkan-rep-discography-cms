<?php 
//FUNKCIJE U OVOM FAJLU
//snimiSesijuUBazu (Snima sesiju u bazu)
//validirajSesiju (Provjerava sesiju)

//********************************* Snima sesiju u bazu *********************************//
function snimiSesijuUBazu(mysqli $conn, int $userId): void
{
    global $conn;
    $sessionId = session_id();
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

    $stmt = $conn->prepare("
        REPLACE INTO user_sessions (session_id, korisnik_id, ip_address, user_agent)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("siss", $sessionId, $userId, $ip, $ua);
    $stmt->execute();
    $stmt->close();
}//end snimiSesijuUBazu()
//********************************* Metoda pozvana u login.process.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Provjerava sesiju *********************************//
function validirajSesiju(mysqli $conn): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Ako osnovni podaci nisu postavljeni, kraj
    if (
        !isset($_SESSION['idKorisnici']) ||
        !isset($_SESSION['statusKorisnika'])
    ) {
        session_unset();
        session_destroy();
        $host = $_SERVER['HTTP_HOST'];
        $base = '/sajtovi/albumi'; // <- tačan root folder tvoje aplikacije
        $redirect = '/users/login.php?reason=missing_session';

        header("Location: http://{$host}{$base}{$redirect}");
        //header("Location: sajtovi/albumi/users/login.php?reason=missing_session");
        exit;
    }

    $sessionId = session_id();
    $userId = $_SESSION['idKorisnici'];
    $statusKorisnika = $_SESSION['statusKorisnika'];

    // Samo korisnici sa statusom 1 i 2 su dozvoljeni
    if (!in_array($statusKorisnika, [1, 2, 4, 5])) {
        session_unset();
        session_destroy();
        $host = $_SERVER['HTTP_HOST'];
        $base = '/sajtovi/albumi'; // <- tačan root folder tvoje aplikacije
        $redirect = '/users/login.php?reason=unauthorized_status';

        header("Location: http://{$host}{$base}{$redirect}");
        //header("Location: sajtovi/albumi/users/login.php?reason=unauthorized_status");
        exit;
    }

    // Provera da li sesija postoji u bazi
    $stmt = $conn->prepare("SELECT 1 FROM user_sessions WHERE session_id = ? AND korisnik_id = ?");
    $stmt->bind_param("si", $sessionId, $userId);
    $stmt->execute();
    $rez = $stmt->get_result();

    if ($rez->num_rows === 0) {
        session_unset();
        session_destroy();
        $host = $_SERVER['HTTP_HOST'];
        $base = '/sajtovi/albumi'; // <- tačan root folder tvoje aplikacije
        $redirect = '/users/login.php?reason=invalid_session';

        header("Location: http://{$host}{$base}{$redirect}");
        //header("Location: /albumi/users/login.php?reason=invalid_session");
        exit;
    }

    // Osvježavanje vremena aktivnosti
    $stmt = $conn->prepare("UPDATE user_sessions SET last_activity = NOW() WHERE session_id = ?");
    $stmt->bind_param("s", $sessionId);
    $stmt->execute();
    $stmt->close();
}//end validirajSesiju()
//********************************* Metoda pozvana u adminupdateartist.php, adminviewusers.php, showeditlabel.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------