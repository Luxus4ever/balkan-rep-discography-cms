<?php
//METODE U OVOM FAJLU
//checkUserStatus (Briše sesisju korisnika iz baze ukokiko mu je promjenjen status)

//********************************* Briše sesisju korisnika iz baze ukokiko mu je promjenjen status *********************************//
function checkUserStatus(mysqli $conn, int $profil): void
{
    // Obrisi sve aktivne sesije korisnika (ako ih ima)
    $stmt = $conn->prepare("DELETE FROM user_sessions WHERE korisnik_id = ?");
    $stmt->bind_param("i", $profil);
    $stmt->execute();
    $stmt->close();

    // Ako se korisnik koji je promenjen nalazi u aktivnoj PHP sesiji, i to je isti kao $profil → unisti i PHP sesiju
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['idKorisnici']) && (int)$_SESSION['idKorisnici'] === $profil) 
    {
        // Uništi PHP sesiju
        session_unset();
        session_destroy();

        // (opciono) obriši session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Redirekcija (korisnik koji sam sebi menja status, što je retko ali moguće)
        header("Location: /sajtovi/albumi/users/login.php?reason=deactivated");
        exit;
    }//end master if()
}//end checkUserStatus()

//********************************* Pozvana metoda u fajlu admineditusers.func.php u metodi formaEditUser()  *********************************//

//--------------------------------------------------------------------------------------------------------------------------------
