<?php
//uploadErrors.func.php (prikazuje greške prilikom uploada)

//********************************* Prikazuje greške prilikom uploada slika *********************************//
function phpUploadErrorText(int $code): string
{
    $map = [
        UPLOAD_ERR_OK         => 'OK',
        UPLOAD_ERR_INI_SIZE   => 'Fajl prelazi upload_max_filesize (php.ini)',
        UPLOAD_ERR_FORM_SIZE  => 'Fajl prelazi MAX_FILE_SIZE (HTML forma)',
        UPLOAD_ERR_PARTIAL    => 'Fajl je djelimično uploadovan',
        UPLOAD_ERR_NO_FILE    => 'Nije odabran fajl',
        UPLOAD_ERR_NO_TMP_DIR => 'Nedostaje privremeni folder (tmp)',
        UPLOAD_ERR_CANT_WRITE => 'Ne mogu upisati fajl na disk',
        UPLOAD_ERR_EXTENSION  => 'PHP ekstenzija je prekinula upload',
    ];

    return $map[$code] ?? ('Nepoznat PHP upload error code: ' . $code);
}
//********************************* Pozvana metoda u ovom fajlu u metodi editUser *********************************//

//--------------------------------------------------------------------------------------------------------------------------------