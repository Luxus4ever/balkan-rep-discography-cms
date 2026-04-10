<?php 
session_start();
if(isset($_SESSION['idKorisnici']))
{
    include_once "../../config/config.php";
    include_once "../functions.php";
    $outgoing_id = $_SESSION['idKorisnici'];
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $message = cleanMessage(mysqli_real_escape_string($conn, $_POST['message']));
    $imageName = "";
    $videoName = "";

    // ✅ UPLOAD SLIKE
    if(isset($_FILES['chat_image']) && $_FILES['chat_image']['error'] === UPLOAD_ERR_OK)
    {
        $img_name = $_FILES['chat_image']['name'];
        $tmp_name = $_FILES['chat_image']['tmp_name'];
        $img_ext  = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));

        // ✅ Dozvoljene ekstenzije (JPG/JPEG/PNG se konvertuju u WEBP, GIF se NE konvertuje, WEBP ostaje WEBP)
        $allowed_ext = ['jpg','jpeg','png','gif','webp'];

        // ✅ Maksimalna veličina 5 MB
        $maxSize = 5 * 1024 * 1024;
        if((int)$_FILES['chat_image']['size'] > $maxSize){

            // ✅ LOGUJ BLOK (prevelika slika)
            logChatUploadBlocked(
                'image',
                $outgoing_id,
                $incoming_id,
                $_FILES['chat_image']['name'],
                $_FILES['chat_image']['size'],
                'file_too_large'
            );

            die("Slika je prevelika. Maksimalno 5 MB.");
        }//end if()

        if(!in_array($img_ext, $allowed_ext)){

            // ✅ LOGUJ BLOK (nepodržan tip)
            logChatUploadBlocked(
                'image',
                $outgoing_id,
                $incoming_id,
                $_FILES['chat_image']['name'],
                $_FILES['chat_image']['size'],
                'unsupported_type'
            );

            die("Nepodržan tip slike. Dozvoljeni formati: jpg, jpeg, png, gif, webp.");
        }//end if()

        // ✅ MIME provjera (stvarni format fajla po sadržaju)
        // - ne vjerujemo samo ekstenziji
        $info = @getimagesize($tmp_name);
        if ($info === false || empty($info['mime'])) {

            // ✅ LOGUJ BLOK (nije validna slika)
            logChatUploadBlocked(
                'image',
                $outgoing_id,
                $incoming_id,
                $_FILES['chat_image']['name'],
                $_FILES['chat_image']['size'],
                'not_valid_image'
            );

            die("Fajl nije validna slika.");
        }

        $mime = $info['mime'];

        // ✅ Dodatna zaštita: dozvoljeni MIME tipovi
        $allowed_mime = ['image/jpeg','image/png','image/gif','image/webp'];
        if (!in_array($mime, $allowed_mime)) {

            logChatUploadBlocked(
                'image',
                $outgoing_id,
                $incoming_id,
                $_FILES['chat_image']['name'],
                $_FILES['chat_image']['size'],
                'mime_not_allowed'
            );

            die("Nepodržan MIME tip slike.");
        }

        // ✅ Ekstenzija ↔ MIME mismatch zaštita (sprječava lažne ekstenzije)
        if (($img_ext === "jpg" || $img_ext === "jpeg") && $mime !== "image/jpeg") {
            logChatUploadBlocked('image', $outgoing_id, $incoming_id, $img_name, $_FILES['chat_image']['size'], 'ext_mime_mismatch');
            die("Ekstenzija i MIME se ne poklapaju (očekivan JPEG).");
        }
        if ($img_ext === "png" && $mime !== "image/png") {
            logChatUploadBlocked('image', $outgoing_id, $incoming_id, $img_name, $_FILES['chat_image']['size'], 'ext_mime_mismatch');
            die("Ekstenzija i MIME se ne poklapaju (očekivan PNG).");
        }
        if ($img_ext === "gif" && $mime !== "image/gif") {
            logChatUploadBlocked('image', $outgoing_id, $incoming_id, $img_name, $_FILES['chat_image']['size'], 'ext_mime_mismatch');
            die("Ekstenzija i MIME se ne poklapaju (očekivan GIF).");
        }
        if ($img_ext === "webp" && $mime !== "image/webp") {
            logChatUploadBlocked('image', $outgoing_id, $incoming_id, $img_name, $_FILES['chat_image']['size'], 'ext_mime_mismatch');
            die("Ekstenzija i MIME se ne poklapaju (očekivan WEBP).");
        }

        // ime bez ekstenzije
        $baseName = pathinfo($img_name, PATHINFO_FILENAME);
        $baseName = str_replace(' ', '_', $baseName); // zameni razmake sa _
        $baseName = preg_replace('/[^А-Яа-яЁёA-Za-z0-9_\-]/u', '', $baseName); // ukloni nedozvoljene karaktere

        // sufiks za slike
        $suffix = "_im_" . date("dmY_His") . "_" . time();

        // ✅ Chat resize + webp parametri
        $maxWidth = 2000;
        $quality  = 75; // webp quality 0-100

        // ✅ Stabilna putanja (da ne zavisi od foldera skripte)
        $uploadDir = __DIR__ . "/../images/";
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        // ===============================
        // KONVERTOVANJE U WEBP:
        // - JPG/JPEG/PNG => WEBP (resize max 2000px)
        // - GIF => regular upload (bez konverzije)
        // - WEBP => regular upload (bez konverzije)
        // ===============================

        // ✅ Ako konvertujemo, finalni fajl treba da bude .webp
        $final_ext = $img_ext;
        if ($img_ext === "jpg" || $img_ext === "jpeg" || $img_ext === "png") {
            $final_ext = "webp";
        }

        $new_img_name = $baseName . $suffix . "." . $final_ext;
        $upload_path  = $uploadDir . $new_img_name;

        // ✅ Helper: resize (maxWidth) + save webp
        $convertToWebpWithResize = function(string $tmp, string $dest, string $mime, int $quality, int $maxWidth): bool {

            if (!function_exists('imagewebp')) return false;

            $info = @getimagesize($tmp);
            if ($info === false) return false;

            $w = (int)$info[0];
            $h = (int)$info[1];

            // 1) učitaj sliku
            if ($mime === 'image/jpeg') {
                $src = @imagecreatefromjpeg($tmp);
            } else if ($mime === 'image/png') {
                $src = @imagecreatefrompng($tmp);
                if ($src) {
                    // PNG transparentnost -> sačuvaj alfa (iako webp podržava)
                    imagepalettetotruecolor($src);
                    imagealphablending($src, true);
                    imagesavealpha($src, true);
                }
            } else {
                return false;
            }

            if (!$src) return false;

            // 2) resize samo ako je šire od maxWidth
            if ($w > $maxWidth) {
                $newW = $maxWidth;
                $newH = (int)round(($h / $w) * $newW);

                $dst = imagecreatetruecolor($newW, $newH);

                // webp može da ima transparentnost
                imagealphablending($dst, true);
                imagesavealpha($dst, true);

                imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);

                // 3) snimi webp
                $ok = @imagewebp($dst, $dest, $quality);

                // PHP 8.5+ ne traži imagedestroy (deprecated), ali nije problem da ga nema
                // imagedestroy($src);
                // imagedestroy($dst);

                return $ok ? true : false;
            }

            // 2b) ako je već manja od maxWidth, snimi direktno webp bez resize
            $ok = @imagewebp($src, $dest, $quality);
            // imagedestroy($src);

            return $ok ? true : false;
        };

        // ✅ 1) GIF / WEBP: regular upload (bez konverzije)
        if ($img_ext === "gif" || $img_ext === "webp") {

            if(move_uploaded_file($tmp_name, $upload_path)){
                $imageName = $new_img_name;

                logChatImageSent(
                    $outgoing_id,
                    $incoming_id,
                    $imageName,
                    $_FILES['chat_image']['size']
                );
            } else {

                logChatUploadBlocked(
                    'image',
                    $outgoing_id,
                    $incoming_id,
                    $_FILES['chat_image']['name'],
                    $_FILES['chat_image']['size'],
                    'move_failed'
                );

                die("Greška prilikom uploada slike.");
            }//end else

        }
        // ✅ 2) JPG/JPEG/PNG: konvertuj u WEBP + resize max 2000px
        else {

            $ok = $convertToWebpWithResize($tmp_name, $upload_path, $mime, $quality, $maxWidth);

            if ($ok === true) {
                $imageName = $new_img_name;

                // ✅ LOG: slika poslana (u bazi ćeš upisati $imageName)
                // Napomena: size koji logujemo je upload size (original), a webp size može biti manji.
                logChatImageSent(
                    $outgoing_id,
                    $incoming_id,
                    $imageName,
                    $_FILES['chat_image']['size']
                );
            } else {

                // ✅ LOGUJ BLOK (neuspjela konverzija)
                logChatUploadBlocked(
                    'image',
                    $outgoing_id,
                    $incoming_id,
                    $_FILES['chat_image']['name'],
                    $_FILES['chat_image']['size'],
                    'webp_convert_failed'
                );

                die("Greška prilikom obrade slike (WEBP konverzija).");
            }

        }//end else (konverzija)

    }//end if()

    // ✅ UPLOAD VIDEO
    if(isset($_FILES['chat_video']) && $_FILES['chat_video']['error'] === UPLOAD_ERR_OK)
    {
        $vid_name = $_FILES['chat_video']['name'];
        $tmp_name = $_FILES['chat_video']['tmp_name'];
        $vid_ext = strtolower(pathinfo($vid_name, PATHINFO_EXTENSION));
        $allowed_vid_ext = ['mp4','avi','mov','mkv','webm'];

        // ✅ Maksimalna veličina 20 MB
        $maxSize = 20 * 1024 * 1024;
        if($_FILES['chat_video']['size'] > $maxSize){
            // ✅ LOGUJ BLOK (prevelik video)
            logChatUploadBlocked(
                'video',
                $outgoing_id,
                $incoming_id,
                $_FILES['chat_video']['name'],
                $_FILES['chat_video']['size'],
                'file_too_large'
            );
            die("Video je prevelik. Maksimalno 20 MB.");
        }//end if()

        if(!in_array($vid_ext, $allowed_vid_ext)){
            // ✅ LOGUJ BLOK (prevelik video)
            logChatUploadBlocked(
                'video',
                $outgoing_id,
                $incoming_id,
                $_FILES['chat_video']['name'],
                $_FILES['chat_video']['size'],
                'file_too_large'
            );

            die("Nepodržan tip videa. Dozvoljeni formati: mp4, avi, mov, mkv, webm.");
        }//end if()

        // ime bez ekstenzije
        $baseName = pathinfo($vid_name, PATHINFO_FILENAME);
        $baseName = str_replace(' ', '_', $baseName); // zameni razmake sa _
        $baseName = preg_replace('/[^А-Яа-яЁёA-Za-z0-9_\-]/u', '', $baseName); // ukloni nedozvoljene karaktere

        // sufiks za video
        $suffix = "_vid_" . date("dmY_His") . "_" . time();

        $new_vid_name = $baseName . $suffix . "." . $vid_ext;
        $upload_path = "../videos/" . $new_vid_name;

        if(move_uploaded_file($tmp_name, $upload_path)){
            $videoName = $new_vid_name;

            logChatVideoSent(
                $outgoing_id,
                $incoming_id,
                $videoName,
                $_FILES['chat_video']['size']
            );
        }//end if()
    }//end if()


    // Unesi bar jednu vrednost (tekst, slika ili video)
    if (!empty($message) || !empty($imageName) || !empty($videoName)) 
    {
        $sql = "INSERT INTO messages 
                (incoming_msg_id, outgoing_msg_id, msg, imageChat, videoChat, vrijemePoruke, is_seen, is_read) 
                VALUES (?, ?, ?, ?, ?, NOW(), 0, 0)";

        if ($stmt = mysqli_prepare($conn, $sql))
        {
            // "iisss" znači:
            // i = integer, i = integer, s = string, s = string, s = string
            mysqli_stmt_bind_param($stmt, "iisss", 
                $incoming_id, 
                $outgoing_id, 
                $message, 
                $imageName, 
                $videoName
            );

            if (!mysqli_stmt_execute($stmt)) {
                die("Greška pri unosu poruke: " . mysqli_stmt_error($stmt));
            }//end if()

            mysqli_stmt_close($stmt);
        }else{
            die("Greška u pripremi upita: " . mysqli_error($conn));
        }//end if else
    }//end if()

}else{
    header("location: ../../login.php");
}//end if else
?>
