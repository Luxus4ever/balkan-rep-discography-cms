<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function headerPutanja($putanja1="", $putanja2="")
{
    echo "<!-- Verzija sajta: " . SITE_VERSION . " -->";
    
    ?>
    <!DOCTYPE html>
    <html lang="sr">
    <head>
    <title>Diskografija</title>


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <link href="<?php echo $putanja1; ?>css/style.css" rel="stylesheet">


    <link href="css/lightbox.css" rel="stylesheet">
    <link href="css/swiper.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!--<link href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,600,700,700i&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">-->


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chiron+Sung+HK:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

    <script src="<?php echo $putanja1; ?>js/jquery-3.7.1.min.js"></script>

    <script src="chat/notifications.js"></script>

    <!-- Favicon  -->
    <link rel="icon" href="images/favicon.png">
    </head>

    <body>
    <header class="mainHeader">
        <div id="slogan">
            <a href="<?php echo $putanja1; ?>index.php"><img src="<?php echo $putanja1; ?>images/Balkan-Rep-Diskografija-Logo.png" alt="Balkan Rep Diskografija" title="Balkan Rep Diskografija"></a>
        </div><!-- end .slogan -->
        <div id="logoRadio">
            <a href="https://balkanhiphopradio.com" target="_blank"><img src="<?php echo $putanja1; ?>images/bhhr-logo.jpg" alt="Balkan Hip-Hop Radio" title="Balkan Hip-Hop Radio"></a>
        </div><!-- end .logoRadio -->
    </header>
    <nav class="glavniNav">
        <button class="hamburger" type="button" data-target="#glavniNavMenu" aria-expanded="false" aria-label="Otvori meni">
        <span></span><span></span><span></span>
        </button>
        <ul id="glavniNavMenu">
            <li><a href="<?php echo $putanja1; ?>index.php">Početna</a></li>
            <li><a href="<?php echo $putanja1; ?>pogodinama.php">Albumi po godinama</a></li>
            <li><a href="<?php echo $putanja1; ?>poizvodjacima.php">Albumi po izvođačima</a></li>
            <li><a href="<?php echo $putanja1; ?>svisinglovi.php">Singlovi</a></li>
            <li><a href="<?php echo $putanja1; ?>sviizvodjaci.php">Izvođači</a></li>
            <li><a href="<?php echo $putanja1; ?>search.php">Pretraga</a></li>
            <li><a href="<?php echo $putanja1; ?>onama.php">O nama</a></li>
        </ul>
    </nav><!-- end .glavniNav -->

    <?php
    require "classes/header.class.php";
    $h= new Header($putanja2);
}//end headerPutanja()
?>