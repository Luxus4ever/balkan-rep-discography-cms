<?php
require_once "config/bootstrap.php";
include "functions/master.func.php";
include "header.php";
include_once "classes/kontaktAdmin.class.php";

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
        $konAdmin= new adminKontakt();
}//end if()
include "footer.php";
footerPutanja();