<?php
//FUNKCIJE SADRŽANE U OVOM FAJLU
//zabranjenPristupLeftPanel (Zabranjen pristup za lijevi panel)
//zabranjenPristupBezValidacije (Zabranjen pristup preko čitave stranice)
//zabranjenPristup1 (Zabranjen pristup za lijevi panel sa unosom teksta i bojom teksta)
//zabranjenPristup2 (Zabranjen pristup za srednji panel sa unosom teksta i bojom teksta)
//zabranjenPristup3 (Zabranjen pristup preko čitavog wrappera sa unosom boje teksta i porukom)


//********************************* Zabranjen pristup za lijevi panel *********************************//
function zabranjenPristupLeftPanel($statusKorisnika)
{ 
    global $conn;
    ?>
    <div class='col-md-2 visina leftPanel'>
        <div class="visina sredina">
            <h1 style="color:red">Nemate prava pristupa!</h1><br>
            ?>
        </div><!-- .visina sredina -->
    </div><!-- .col-md-2 visina leftPanel -->
    <?php
}//zabranjenPristupLeftPanel()
//********************************* Pozvana funkcija u... *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Zabranjen pristup preko čitave stranice *********************************//
function zabranjenPristupBezValidacije($statusKorisnika)
{ 
    global $conn;
    ?>
    <!--<div class='container-fluid slikeAlbumaPregled sredina panel visina'>-->
    <div class='container-fluid slikeAlbumaPregled sredina visina'>
        <div class="visina sredina">
            <h1 style="color:gold">Ne možete da pristupite ovom dijelu bez validnih podataka!</h1><br>
        </div><!-- .visina sredina -->
    </div><!-- .container-fluid slikeAlbumaPregled sredina panel visina -->
    <?php
}//zabranjenPristupBezValidacije()
/********************************* Pozvana funkcija u detailsUser.func.php, dodajalbume.php, insertsongs.php, insertstreams.php, profile.php, profileedit.php *********************************/

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Zabranjen pristup za lijevi panel sa unosom teksta i bojom teksta *********************************//
function zabranjenPristup1($bojaTeksta, $poruka)
{ 
    global $conn;
    ?>
    <div class='col-md-2 visina leftPanel'>
        <div class="visina sredina">
            <h2 style="color:<?php echo $bojaTeksta; ?>"><?php echo $poruka; ?></h2>
        </div><!-- .visina sredina -->
    </div><!-- .col-md-2 visina leftPanel -->
    <?php
}//end zabranjenPristup1()
//********************************* Pozvana funkcija u... *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Zabranjen pristup za srednji panel sa unosom teksta i bojom teksta *********************************//
function zabranjenPristup2($bojaTeksta, $poruka)
{ 
    global $conn;
    ?>
    <div class='col-md-10 panel'>
        <div class="visina sredina">
            <h1 class="sredina" style="color:<?php echo $bojaTeksta; ?>"><?php echo $poruka; ?></h1>
        </div><!-- .visina sredina -->
    </div><!-- .col-md-2 visina leftPanel -->
    <?php
}//end zabranjenPristup2()
//********************************* Pozvana funkcija u fajlu oalbumu.php, tekstovi.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Zabranjen pristup preko čitavog wrappera sa unosom teksta i bojom teksta *********************************//
function zabranjenPristup3($bojaTeksta, $poruka)
{ 
    global $conn;
    ?>
        <div class="visina sredina">
            <h1 class="sredina" style="color:<?php echo $bojaTeksta; ?>"><?php echo $poruka; ?></h1>
        </div><!-- .visina sredina -->

    <?php
}//end zabranjenPristup2()
//********************************* Pozvana funkcija u fajlu search.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------