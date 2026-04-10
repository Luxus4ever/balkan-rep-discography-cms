<!DOCTYPE html>
<html lang="sr">
<head>
<title>Diskografija - Admin panel</title>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<link href="css/lightbox.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<!-- Fontovi -->
<!--<link href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,600,700,700i&display=swap" rel="stylesheet">-->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">

<!-- jQuery -->
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.6.0.js"></script>

<!-- Ako je folder na serveru u koji smještamo sajt www onda stavljamo putanju /mojwebsajt/chat/php... -->
<!-- Definiši apsolutnu putanju ka PHP fajlu -->
<script>
  const UNREAD_COUNT_URL = "/sajtovi/albumi/chat/php/unread-count.php";
</script>

<!-- Uključi glavni skript -->
<script src="/sajtovi/albumi/chat/notifications.js"></script>

<!-- Favicon  -->
<link rel="icon" href="images2/favicon.png">
</head>

<body>
<header class="mainHeader">
    <div id="slogan">
         <a href="../index.php"><img src="../images/Balkan-Rep-Diskografija-Logo.png" alt="Balkan Rep Diskografija" title="Balkan Rep Diskografija"></a>
    </div><!-- end .slogan -->
    <div id="logoRadio">
    <a href="../index.php"><img src="../images/bhhr-logo.jpg" alt="Balkan Hip-Hop Radio" title="Balkan Hip-Hop Radio"></a>
    </div><!-- end .logoRadio -->
</header>
<nav class="glavniNav">
    <button class="hamburger" type="button" data-target="#glavniNavMenu" aria-expanded="false" aria-label="Otvori meni">
        <span></span><span></span><span></span>
        </button>
    <ul id="glavniNavMenu">
        <li><a href="../index.php">Početna</a></li>
        <li><a href="../pogodinama.php">Albumi po godinama</a></li>
        <li><a href="../poizvodjacima.php">Albumi po izvođačima</a></li>
        <li><a href="../svisinglovi.php">Singlovi</a></li>
        <li><a href="../sviizvodjaci.php">Izvođači</a></li>
        <li><a href="../search.php">Pretraga</a></li>
        <li><a href="../onama.php">O nama</a></li>
    </ul>
</nav><!-- end .glavniNav -->

<?php
require "classes/headerAdmin.class.php";
$h= new Header;

include_once "../functions/sesije.func.php";
validirajSesiju($conn);

@$idIzv= $_GET['idIzv'];
@$idAlb= $_GET['idAlb'];
@$nazivPromjeneLinka= $_GET['data'];
@$idKorisnici= $_SESSION["idKorisnici"];
@$username= $_SESSION["username"];
?>
