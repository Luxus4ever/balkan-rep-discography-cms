<?php
//FUNKCIJE U OVOM FAJLU
//checkLinks (provjera unosa sajta da bude https:// ili www i slično)
//removeSimbols (uklanjanje/izmjena svih nepotrebnih simbola za input polja u formama)
//removeSimbolsImg (uklanjanje/izmjena svih nepotrebnih simbola za slike)
//removeLinksSocialMedia (uklanjanje svih nepotrebnih simbola za društvene mreže)
//removeSpecialLetters (uklanja sva naša slova sa kvačicama)
//reverseRemoveSpecialLetters (vraća sva naša slova sa kvačicama) ---------------- OBRISATI
//cleanFeat (Uklanja featuring, feat i čisti od naših slova sa kvačicama)
//cleanAlbum (Čisti nazive albuma od nepotrebnih stvari)
//removeSerbianLetters (Uklanja sva naša slova sa kvačicama)
//reverseSerbianLetters (Vraća ošišana slova na nađaš kao na đ)
//konverzijaLatinica (konvertuje tekst iz ćirilice u latinicu)
//konverzijaCirilica (konvertuje tekst iz latinice u ćirilicu)
//cleanText (uklanja i mijenja nepotrebne simbole)
//sanitizeInt (Provjerava da li je $value cijeli broj (integer)


//********************************* Provjerava da unos sajta bude validan sa https:// ili www i slično *********************************//
function checkLinks($param){
    $expression = '/(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,7}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,7}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,7}|www\.[a-zA-Z0-9]+\.[^\s]{2,7})$/';
    if (!empty(preg_match($expression, $param))) {
        return $param;
    }else if(!empty($param)){
        echo "<h1 class='warning'>Nepravilan link sajta.</h1>";
    }
}//end checkLinks()
//********************************* Pozvana metoda u fajlu updateUsers.func.php u funkciji formaEditUser *********************************//

//--------------------------------------------------------------------------------------------------------------------------------


//********************************* Uklanjanje odnosno izmjena svih nepotrebnih simbola za input polja u formi *********************************//
function removeSimbols($param)
{
    $param= str_replace("  ", " ", $param);
    $param= str_replace("'", "", $param);
    $param= str_replace("<", "", $param);
    $param= str_replace(">", "", $param);
    $param= str_replace("--", "", $param);
    $param= str_replace("#", "", $param);
    $param= str_replace("$", "", $param);
    $param= str_replace("*", "", $param);
    $param= str_replace("/", "", $param);
    $param= str_replace("|", "", $param);
    $param= str_replace("\\", "", $param);
    $param= str_replace("~", "", $param);
    $param= str_replace("ˇ", "", $param);
    $param= str_replace("^", "", $param);
    $param= str_replace("{", "", $param);
    $param= str_replace("}", "", $param);
    $param= str_replace(";", "", $param);
    $param= str_replace("(", "", $param);
    $param= str_replace(")", "", $param);
    $param= str_replace("?", "", $param);
    $param= str_replace(":", "", $param);
    $param= str_replace("`", "", $param);
    


    $slova= "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"; 
    $tekst= ""; 
    for($i=0; $i<5; $i++) 
    { 
        $tekst.= $slova[rand(0, strlen($slova)-1)];
    }
    $param= str_replace(".php", "_".$tekst."_", $param);
    $param= str_replace("script", "_".$tekst."_", $param);
    $param= str_replace(".sql", "_".$tekst."_", $param);
    $param= str_replace(".txt", "_".$tekst."_", $param);
    $param= str_replace(".js", "_".$tekst."_", $param);
    $param= str_replace(".htm", "_".$tekst."_", $param);
    $param= str_replace(".cgi", "_".$tekst."_", $param);
    $param= str_replace(".exe", "_".$tekst."_", $param);
    $param= str_replace(".pl", "_".$tekst."_", $param);
    $param= str_replace(".asp", "_".$tekst."_", $param);
    $param= str_replace(".py", "_".$tekst."_", $param);
    $param= str_replace(".jpeg", "_".$tekst."_", $param);
    $param= str_replace(".jpg", "_".$tekst."_", $param);
    $param= str_replace(".gif", "_".$tekst."_", $param);
    $param= str_replace(".png", "_".$tekst."_", $param);
    $param= str_replace(".svg", "_".$tekst."_", $param);
    $param= str_replace(".webp", "_".$tekst."_", $param);
    $param= str_replace(".jfif", "_".$tekst."_", $param);
    $param= str_replace(".apng", "_".$tekst."_", $param);
    $param= str_replace(".avif", "_".$tekst."_", $param);

    
    

    return $param;
}//end removeSimbols()

/********************************* Pozvana metoda u fajlovima updateUsers.func.php funkciji formaEditUser, 
 * insertAlbum.class.php u metodi insertAboutAlbum,
 * insertAlbumSongs.class.php, insertArtist.class.php, insertLabel.class.php *********************************/

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Uklanjanje odnosno izmjena svih nepotrebnih simbola za slike (više nego za unos običnog teksta) *********************************//
function removeSimbolsImg($param)
{
    $param= str_replace(" ", "_", $param);
    $param= str_replace("'", "", $param);
    $param= str_replace("<", "", $param);
    $param= str_replace(">", "", $param);
    $param= str_replace("--", "", $param);
    $param= str_replace("#", "", $param);
    $param= str_replace("$", "", $param);
    $param= str_replace("*", "", $param);
    $param= str_replace("/", "", $param);
    $param= str_replace("|", "", $param);
    $param= str_replace("\\", "", $param);
    $param= str_replace("~", "", $param);
    $param= str_replace("ˇ", "", $param);
    $param= str_replace("^", "", $param);
    $param= str_replace("{", "", $param);
    $param= str_replace("}", "", $param);
    $param= str_replace(";", "", $param);
    $param= str_replace("?", "", $param);
    $param= str_replace(":", "", $param);

    $param= str_replace("ć", "c", $param);
    $param= str_replace("Ć", "C", $param);
    $param= str_replace("č", "c", $param);
    $param= str_replace("Č", "C", $param);
    $param= str_replace("ž", "z", $param);
    $param= str_replace("Ž", "Z", $param);
    $param= str_replace("đ", "dj", $param);
    $param= str_replace("Đ", "Dj", $param);
    $param= str_replace("š", "s", $param);
    $param= str_replace("Š", "S", $param);


    $slova= "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"; 
    $tekst= ""; 
    for($i=0; $i<5; $i++) 
    { 
        $tekst.= $slova[rand(0, strlen($slova)-1)];
    }
    $param= str_replace(".php", "_".$tekst."_", $param);
    $param= str_replace("script", "_".$tekst."_", $param);
    $param= str_replace(".sql", "_".$tekst."_", $param);
    $param= str_replace(".txt", "_".$tekst."_", $param);
    $param= str_replace(".js", "_".$tekst."_", $param);
    $param= str_replace(".htm", "_".$tekst."_", $param);
    $param= str_replace(".cgi", "_".$tekst."_", $param);
    $param= str_replace(".exe", "_".$tekst."_", $param);
    $param= str_replace(".pl", "_".$tekst."_", $param);
    $param= str_replace(".asp", "_".$tekst."_", $param);
    $param= str_replace(".py", "_".$tekst."_", $param);
    
    

    return $param;
}//end removeSimbolsImg()

/********************************* Pozvana metoda u fajlovima promjenaSlike.func.php, updateUsers.func.php, insertAlbum.class.php, insertArtist.class.php, insertLabel.class.php, adminEditPanel.class.php, admin/images.class.php, adminEditUsers.func, adminPromjenaSlikeIzvodjaca.func.php, adminPromjenaSlikeKorisnika.func.php, middlePanel.func.php *********************************/

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Uklanjanje svih nepotrebnih simbola za društvene mreže *********************************//
function removeLinksSocialMedia($param)
{
    $param= str_replace("https://www.facebook.com/", "", $param);
    $param= str_replace("www.facebook.com/", "", $param);
    $param= str_replace("https://www.facebook.com", "", $param);
    $param= str_replace("www.facebook.com", "", $param);
    $param= str_replace("https://www.instagram.com/", "", $param);
    $param= str_replace("www.instagram.com/", "", $param);
    $param= str_replace("https://www.instagram.com", "", $param);
    $param= str_replace("www.instagram.com", "", $param);
    $param= str_replace("https://twitter.com/", "", $param);
    $param= str_replace("https://twitter.com", "", $param);
    $param= str_replace("https://www.tiktok.com/", "", $param);
    $param= str_replace("www.tiktok.com/", "", $param);
    $param= str_replace("https://www.tiktok.com", "", $param);
    $param= str_replace("www.tiktok.com", "", $param);

    $param= str_replace(" ", "_", $param);
    $param= str_replace("'", "", $param);
    $param= str_replace("<", "", $param);
    $param= str_replace(">", "", $param);
    $param= str_replace("--", "", $param);
    $param= str_replace("#", "", $param);
    $param= str_replace("$", "", $param);
    $param= str_replace("*", "", $param);
    $param= str_replace("/", "", $param);
    $param= str_replace("|", "", $param);
    $param= str_replace("\\", "", $param);
    $param= str_replace("~", "", $param);
    $param= str_replace("ˇ", "", $param);
    $param= str_replace("^", "", $param);
    $param= str_replace("{", "", $param);
    $param= str_replace("}", "", $param);
    $param= str_replace(";", "", $param);
    $param= str_replace("@", "", $param);
    $param= str_replace("?", "", $param);
    $param= str_replace(":", "", $param);

    $param= str_replace("ć", "c", $param);
    $param= str_replace("Ć", "C", $param);
    $param= str_replace("č", "c", $param);
    $param= str_replace("Č", "C", $param);
    $param= str_replace("ž", "z", $param);
    $param= str_replace("Ž", "Z", $param);
    $param= str_replace("đ", "dj", $param);
    $param= str_replace("Đ", "Dj", $param);
    $param= str_replace("š", "s", $param);
    $param= str_replace("Š", "S", $param);


    $slova= "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"; 
    $tekst= ""; 
    for($i=0; $i<5; $i++) 
    { 
        $tekst.= $slova[rand(0, strlen($slova)-1)];
    }
    $param= str_replace(".php", "_".$tekst."_", $param);
    $param= str_replace("script", "_".$tekst."_", $param);
    $param= str_replace(".sql", "_".$tekst."_", $param);
    $param= str_replace(".txt", "_".$tekst."_", $param);
    $param= str_replace(".js", "_".$tekst."_", $param);
    $param= str_replace(".htm", "_".$tekst."_", $param);
    $param= str_replace(".cgi", "_".$tekst."_", $param);
    $param= str_replace(".exe", "_".$tekst."_", $param);
    $param= str_replace(".pl", "_".$tekst."_", $param);
    $param= str_replace(".asp", "_".$tekst."_", $param);
    $param= str_replace(".py", "_".$tekst."_", $param);
    
    

    return $param;
}//end removeLinksSocialMedia()

//********************************* Pozvana metoda u fajlu updateUsers.func.php, admineditusers.func.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Uklanjanje sva naša slova sa kvačicama *********************************//
function removeSpecialLetters($param)
{
    /*$param= str_replace("ć", "c", $param);
    $param= str_replace("Ć", "C", $param);
    $param= str_replace("č", "c", $param);
    $param= str_replace("Č", "C", $param);
    $param= str_replace("ž", "z", $param);
    $param= str_replace("Ž", "Z", $param);
    $param= str_replace("đ", "dj", $param);
    $param= str_replace("Đ", "Dj", $param);
    $param= str_replace("š", "s", $param);
    $param= str_replace("Š", "S", $param);*/

    /*$param= str_replace("ć", "tsj", $param);
    $param= str_replace("Ć", "TSJ", $param);
    $param= str_replace("č", "ccs", $param);
    $param= str_replace("Č", "CCS", $param);
    $param= str_replace("ž", "z", $param);
    $param= str_replace("Ž", "Z", $param);
    $param= str_replace("đ", "djj", $param);
    $param= str_replace("Đ", "Djj", $param);
    $param= str_replace("š", "scch", $param);
    $param= str_replace("Š", "SCCH", $param);*/

    //$param= str_replace("'", "", $param);
    $param= str_replace(" ", "+", $param);
    $param = str_replace("&#39;", "()", $param);  // numerički entitet
    $param = str_replace("&apos;", "()", $param); // named entitet
    $param = str_replace("&#8217;", "()", $param); // rsquo kao numerički entitet
    $param = str_replace("’", "()", $param);      // U+2019 tipografski apostrof
    $param = str_replace("'", "()", $param);      // običan ASCII apostrof

    
    
    return $param;
}//end removeSpecialLetters()

/********************************* Pozvana metoda u fajlovima albumiPoDrzavi.func.php, albumiPoEntitetima.func.php, detailsUser.func.php, dodajKomentar.func.php, nazivDrzava.func.php, pretraga.func.php, sviAlbumim.func.php, detaljiAlbum.class.php, detaljiIzvodjac.class.php, izdavaci.class.php, pjesme.class.php, slider.class.php, izvodjac.php, adminEditPanel.class.php *********************************/

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Vraćanje naših slova sa kvačicama *********************************//
function reverseRemoveSpecialLetters($param)
{

    $param= str_replace("tsj", "ć", $param);
    $param= str_replace("TSJ", "Ć", $param);
    $param= str_replace("ccs", "č", $param);
    $param= str_replace("CCS", "Č", $param);
    $param= str_replace("z", "ž", $param);
    $param= str_replace("Z", "Ž", $param);
    $param= str_replace("djj", "đ", $param);
    $param= str_replace("Djj", "Đ", $param);
    $param= str_replace("scch", "š", $param);
    $param= str_replace("SCCH", "Š", $param);
    
    $param= str_replace("+", " ", $param);
    $param= str_replace("()", "&#39;", $param);
    

    return $param;
}//end reverseRemoveSpecialLetters()

//********************************* Pozvana metoda u fajlu ... *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Uklanja featuring, feat i čisti od naših slova sa kvačicama *********************************//

function cleanFeat($param)
{
    $param= str_replace("featuring ", "", $param);
    $param= str_replace("Featuring ", "", $param);
    $param= str_replace("feat. ", "", $param);
    $param= str_replace("(", "", str_replace(")", "", $param));

    $param= str_replace("ć", "tsj", $param);
    $param= str_replace("Ć", "TSJ", $param);
    $param= str_replace("č", "ccs", $param);
    $param= str_replace("Č", "CCS", $param);
    $param= str_replace("ž", "z", $param);
    $param= str_replace("Ž", "Z", $param);
    $param= str_replace("đ", "djj", $param);
    $param= str_replace("Đ", "Djj", $param);
    $param= str_replace("š", "s", $param);
    $param= str_replace("Š", "S", $param);

    /*$param= str_replace("ć", "c", $param);
    $param= str_replace("Ć", "C", $param);
    $param= str_replace("č", "c", $param);
    $param= str_replace("Č", "C", $param);
    $param= str_replace("ž", "z", $param);
    $param= str_replace("Ž", "Z", $param);
    $param= str_replace("đ", "dj", $param);
    $param= str_replace("Đ", "Dj", $param);
    $param= str_replace("š", "s", $param);
    $param= str_replace("Š", "S", $param);*/

    $param= str_replace(" ", "+", $param);
    removeSpecialLetters($param);
    return $param;
}//end cleanFeat()

//********************************* Pozvana metoda u fajlu pjesme.class.php u funkciji fit  *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Čisti nazive albuma od nepotrebnih stavki *********************************//

function cleanAlbum($param)
{
    $param= str_replace(" ", "-", $param);
    $param= str_replace("&#39;", "+", $param);
    
    $param= str_replace("'", "+", $param);
    removeSpecialLetters($param);
    return $param;
}//end cleanAlbum()

//********************************* Pozvana metoda u fajlu detaljiAlbum.class.php, izdavaci.class.php, slider.class.php, albumiPoDrzavi.func.php, albumiPoEntitetima.func.php, detailsUser.func.php, pretraga.func.php, sviAlbumi.func.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Uklanjanje sva naša slova sa kvačicama *********************************//
function removeSerbianLetters($param)
{

    $param= str_replace("ć", "c", $param);
    $param= str_replace("Ć", "C", $param);
    $param= str_replace("č", "c", $param);
    $param= str_replace("Č", "C", $param);
    $param= str_replace("ž", "z", $param);
    $param= str_replace("Ž", "Z", $param);
    $param= str_replace("đ", "dj", $param);
    $param= str_replace("Đ", "Dj", $param);
    $param= str_replace("š", "s", $param);
    $param= str_replace("Š", "S", $param);

    $param= str_replace("'", "", $param);
    $param= str_replace("&#39;", "()", $param);

    $param= str_replace(" ", "+", $param);

    return $param;
}//end removeSerbianLetters()
/********************************* Pozvana metoda u detaljiAlbum.class.php, izdavaci.class.php, pjesme.class.php, slider.class.php, albumiPoDrzavi.func.php, albumiPoEntitetima.func.php, detailsUser.func.php, nadjiIzvodjaca.func.php, pretraga.func.php, sviAlbumi.func.php,  *********************************/

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Vraćanje naših slova *********************************//
//********************************* VIDJETI DA LI SE PONAVLJA FUNKCIJA SA DRUGIM IMENOM *********************************//
function reverseSerbianLetters($param){
    $param= str_replace("dj", "đ", $param);
    $param= str_replace("Dj", "Đ", $param);
    $param= str_replace("Dj", "Đ", $param);
    $param= str_replace("Dj", "Đ", $param);
    $param= str_replace("Dj", "Đ", $param);
    $param= str_replace("Dj", "Đ", $param);

    $param= str_replace("+", " ", $param);
    $param= str_replace("()", "&#39;", $param);
    $param= str_replace("+", "&#39;", $param);

    return $param;
}//end reverseSerbianLetters()
//********************************* Pozvana metoda u fajlu izvodjac.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Konverzija iz ćirilice u latinicu *********************************//
function konverzijaLatinica($string)
{
    if ($string === null) {
        return '';
    }
	
	$cirilica= array("А", "Б", "В", "Г", "Д", "Ђ", "Е", "Ж", "З", "И", "Ј", "К", "Л", "Љ", "М", "Н", "Њ", "О", "П", "Р", "С", "Т", "Ћ", "У", "Ф", "Х", "Ц", "Ч", "Џ", "Ш",
	"а", "б", "в", "г", "д", "ђ", "е", "ж", "з", "и", "ј", "к", "л", "љ", "м", "н", "њ", "о", "п", "р", "с", "т", "ћ", "у", "ф", "х", "ц", "ч", "џ", "ш");
	
	$latinica= array("A", "B", "V", "G", "D", "Đ", "E", "Ž", "Z", "I", "J", "K", "L", "Lj", "M", "N", "Nj", "O", "P", "R", "S", "T", "Ć", "U", "F", "H", "C", "Č", "Dž", "Š",
	"a", "b", "v", "g", "d", "đ", "e", "ž", "z", "i", "j", "k", "l", "lj", "m", "n", "nj", "o", "p", "r", "s", "t", "ć", "u", "f", "h", "c", "č", "dž", "š");

		$string = str_replace($cirilica, $latinica, $string);
		return $string;
}//end konverzijaLatinica()
//********************************* Pozvana metoda u fajlovima detaljiAlbum.class.php, izdavaci.class.php, pjesme.class.php, slider.class.php,albumiPoDrzavi.func.php, albumiPoEntitetima.func.php, detailsUser.func.php, nadjiIzvodjaca.func.php, pretraga.func.php, sviAlbumi.func.php, upload.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Konverzija iz latinice u ćirilicu *********************************//
function konverzijaCirilica($string)
{
	if ($string === null) {
        return '';
    }
	
	$latinica  = [
        'Dž','DŽ','dž','Lj','LJ','lj','Nj','NJ','nj',
        'A','B','V','G','D','Đ','E','Ž','Z','I','J','K','L','M','N','O','P','R','S','T','Ć','U','F','H','C','Č','Š',
        'a','b','v','g','d','đ','e','ž','z','i','j','k','l','m','n','o','p','r','s','t','ć','u','f','h','c','č','š'
    ];
    $cirilica  = [
        'Џ','Џ','џ','Љ','Љ','љ','Њ','Њ','њ',
        'А','Б','В','Г','Д','Ђ','Е','Ж','З','И','Ј','К','Л','М','Н','О','П','Р','С','Т','Ћ','У','Ф','Х','Ц','Ч','Ш',
        'а','б','в','г','д','ђ','е','ж','з','и','ј','к','л','м','н','о','п','р','с','т','ћ','у','ф','х','ц','ч','ш'
    ];
	
    $string = str_replace($latinica, $cirilica, $string);
    return $string;
}//end konverzijaCirilica
//********************************* Pozvana metoda u... (TRENUTNO NIJE NIGDJE POZVANA) *********************************//

//--------------------------------------------------------------------------------------------------------------------------------
















//********************************* Uklanja i mijenja nepotrebne simbole *********************************//
function cleanText($param){

    $param = str_replace(";", "&semi;", $param);
    $param = str_replace("(", "&lpar;", $param);
    $param = str_replace(")", "&rpar;", $param);
    $param = str_replace(":", "&colon;", $param);
    $param = str_replace("-", "&#8211; ", $param);
    $param = str_replace("*", "&#42;", $param);
    //$param= str_replace('&', "&#38;", $param);
    $param= str_replace("$", "&dollar;", $param);
    $param= str_replace("+", "&plus;", $param);

    $param = str_replace("'", "&#39;", $param);
    $param = str_replace('"', "&#34;", $param);

    $param= str_replace("  ", " ", $param);
    $param = str_replace("<", "", $param);
    $param = str_replace(">", "", $param);
    $param = str_replace("/", "", $param);
    $param = str_replace("|", "", $param);
    $param = str_replace("\\", "", $param);
    $param= str_replace("~", "", $param);
    $param= str_replace("ˇ", "", $param);
    $param= str_replace("^", "", $param);
    $param= str_replace("{", "", $param);
    $param= str_replace("}", "", $param);
    $param= str_replace("@", "", $param);
    $param= str_replace("?", "", $param);

    //$param= str_replace(" ", "_", $param);

    //$param= str_replace("#", "", $param);
    



    return $param;

    // Uklanjanje nevidljivih (kontrolnih) karaktera
    $param = preg_replace('/[\x00-\x1F\x7F]/u', '', $param);

    return $param;
}//end cleanText()


//********************************* Pozvana metoda u insertAlbum.class.php, insertAlbumSongs.class.php, insertArtist.class.php, adminEditPAnel.class.php, artistEditPanel.class.php, labelEditPanel.class.php, adminFunkcije.func.php, middlePanel.func.php *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* provjerava da li je $value validan cijeli broj (npr. "123" je OK, "abc" nije), ako jeste – vraća ga kao int  *********************************//

function sanitizeInt($value) {
    return filter_var($value, FILTER_VALIDATE_INT) ?: 0;
}//end sanitizeInt()
//********************************* Pozvana metoda u adminEditPanel.class.php  *********************************//

//--------------------------------------------------------------------------------------------------------------------------------

//********************************* Validacija emaila  *********************************//
function validacijaEmail(string $email): bool
{
    $email = trim($email);

    $pattern = '/^[a-zA-Z0-9]+([._+-]?[a-zA-Z0-9]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.[a-zA-Z]{2,}$/';

    return preg_match($pattern, $email) === 1;
}