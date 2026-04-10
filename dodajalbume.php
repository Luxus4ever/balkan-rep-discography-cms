<?php
require_once "config/bootstrap.php";
include "functions/master.func.php";
include "header.php";
include "classes/insertData-classes/insertDataPanel.class.php";

headerPutanja();

@$profil= $_GET["username"];
@$lid=$_GET["lid"];
@$sesId= $_SESSION["idKorisnici"];
@$sesVrijeme= $_SESSION["vrijeme"];
@$statusKorisnika= $_SESSION["statusKorisnika"];
@$usernameSession= $_SESSION["username"];

@$nazivPromjeneLinka= $_GET['data'];
@$lid=$_GET["lid"];
@$statusKorisnika= $_SESSION["statusKorisnika"];
@$slid= $_SESSION["idKorisnici"];

if(empty($sesId) || empty($usernameSession) || empty($sesVrijeme) || empty($statusKorisnika)){

    zabranjenPristupBezValidacije($sesId);
}else{
        // ================= GLOBAL DIE CATCHER =================
    register_shutdown_function(function () {
        $err = error_get_last();

        if ($err && $err['type'] === E_USER_ERROR) {
            $msg = trim($err['message']);

            // sačuvaj poruku u session da se prikaže na vrhu
            $_SESSION['GLOBAL_UPLOAD_ERROR'] = $msg;

            // očisti buffer da se ne prikaže sirovi tekst
            if (ob_get_length()) ob_clean();

            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }
    });

        $adEdPan= new insertDataPanel();
        $adEdPan->prikazUnosPanela($slid, $nazivPromjeneLinka); 
}//end if()
include "footer.php";
footerPutanja();