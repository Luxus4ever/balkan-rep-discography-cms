<?php

//METODE SADRŽANE U OVOJ KLASI
//uploadImageSmart (Metoda za unos fotografija)
//convertToWebpAndSave (metoda koja konvertuje jpg/jpeg, i png slike u webp format)

class ImageUploader
{
    // Dozvoljeni formati (ekstenzije)
    private array $whitelist = [".jpg",".jpeg",".gif",".png",".svg",".webp"];

    // Default limiti
    private int $maxSize = 2097152; // 2MB
    private int $minDefault = 10000; // 10KB
    private int $minWebp = 2000; // 2KB
    private int $minSvg  = 500;  // 0.5KB

    public function __construct()
    {
        // nema potrebe za $conn ovdje (uploader ne radi bazu)
    }

    //--------------------------------------------------------------------------------------------------------------------------------

    /*******************************
     * GLAVNI POZIV (public)
     * - konvertuje JPG/JPEG/PNG u WEBP
     * - GIF/SVG/WEBP ide regular upload
     * - radi MIME provjeru (osim SVG)
     * - vraća rezultat: ok/file/path/error
     *******************************/
    public function uploadImageSmart(string $fileKey, string $targetDir, string $logType, int $entityId, int $quality = 75): array
    {
        $result = ['ok'=>false, 'file'=>'', 'path'=>'', 'error'=>''];

        if (empty($_FILES[$fileKey]['name'])) {
            $result['error'] = "NO_FILE";
            return $result;
        }

        $size = (int)$_FILES[$fileKey]['size'];
        $tmp  = $_FILES[$fileKey]['tmp_name'];
        $originalName = $_FILES[$fileKey]['name'];

        // ime + ekstenzija
        $imeSlike = removeSimbolsImg($originalName);
        $parts = explode(".", $imeSlike);
        $ekstenzija = strtolower(end($parts));

        // whitelist provjera
        if (!in_array(".".$ekstenzija, $this->whitelist)) {
            $result['error'] = "EXT_NOT_ALLOWED";
            logUploadFail($logType, $entityId, $originalName, $result['error']);
            return $result;
        }

        // max
        if ($size > $this->maxSize) {
            $result['error'] = "SIZE_TOO_BIG | size={$size} | max={$this->maxSize}";
            logUploadFail($logType, $entityId, $originalName, $result['error']);
            return $result;
        }

        // min po ekstenziji
        $min = $this->minDefault;
        if ($ekstenzija === "webp") $min = $this->minWebp;
        if ($ekstenzija === "svg")  $min = $this->minSvg;

        if ($size < $min) {
            $result['error'] = "SIZE_TOO_SMALL | size={$size} | min={$min}";
            logUploadFail($logType, $entityId, $originalName, $result['error']);
            return $result;
        }

        // MIME provjera (ne za svg)
        if ($ekstenzija !== "svg") {
            $info = @getimagesize($tmp);
            if ($info === false || empty($info['mime'])) {
                $result['error'] = "NOT_VALID_IMAGE";
                logUploadFail($logType, $entityId, $originalName, $result['error']);
                return $result;
            }

            $mime = $info['mime'];
            $allowedMime = ['image/jpeg','image/png','image/gif','image/webp'];

            if (!in_array($mime, $allowedMime)) {
                $result['error'] = "MIME_NOT_ALLOWED | mime={$mime}";
                logUploadFail($logType, $entityId, $originalName, $result['error']);
                return $result;
            }

            // mismatch zaštita
            if (($ekstenzija === "jpg" || $ekstenzija === "jpeg") && $mime !== "image/jpeg") {
                $result['error'] = "EXT_MIME_MISMATCH | ext={$ekstenzija} | mime={$mime}";
                logUploadFail($logType, $entityId, $originalName, $result['error']);
                return $result;
            }
            if ($ekstenzija === "png" && $mime !== "image/png") {
                $result['error'] = "EXT_MIME_MISMATCH | ext={$ekstenzija} | mime={$mime}";
                logUploadFail($logType, $entityId, $originalName, $result['error']);
                return $result;
            }
            if ($ekstenzija === "gif" && $mime !== "image/gif") {
                $result['error'] = "EXT_MIME_MISMATCH | ext={$ekstenzija} | mime={$mime}";
                logUploadFail($logType, $entityId, $originalName, $result['error']);
                return $result;
            }
            if ($ekstenzija === "webp" && $mime !== "image/webp") {
                $result['error'] = "EXT_MIME_MISMATCH | ext={$ekstenzija} | mime={$mime}";
                logUploadFail($logType, $entityId, $originalName, $result['error']);
                return $result;
            }
        }

        // priprema naziva (tvoj stil)
        $vrijeme= "_im".date("dmY_His", time())."_".time().".";
        $base = $parts[0];

        // osiguraj folder
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0777, true);
        }

        $finalFileName = "";
        $destPath = "";

        // GIF/SVG/WEBP -> regular upload
        if ($ekstenzija === "gif" || $ekstenzija === "svg" || $ekstenzija === "webp") {

            $finalFileName = $base.$vrijeme.$ekstenzija;
            $destPath = rtrim($targetDir, "/")."/".$finalFileName;

            $ok = move_uploaded_file($tmp, $destPath);
            if (!$ok) {
                $result['error'] = "MOVE_FAILED";
                logUploadFail($logType, $entityId, $originalName, $result['error']);
                return $result;
            }

        } else {
            // JPG/JPEG/PNG -> WEBP
            $finalFileName = $base.$vrijeme."webp";
            $destPath = rtrim($targetDir, "/")."/".$finalFileName;

            $ok = $this->convertToWebpAndSave($tmp, $destPath, $quality);

            // fallback na original, ako konverzija ne uspije
            if ($ok !== true) {
                $finalFileName = $base.$vrijeme.$ekstenzija;
                $destPath = rtrim($targetDir, "/")."/".$finalFileName;

                $ok2 = move_uploaded_file($tmp, $destPath);
                if (!$ok2) {
                    $result['error'] = "WEBP_FAIL_AND_MOVE_FAILED";
                    logUploadFail($logType, $entityId, $originalName, $result['error']);
                    return $result;
                }
            }
        }

        // success
        $result['ok'] = true;
        $result['file'] = $finalFileName;
        $result['path'] = $destPath;

        logUploadSuccess($logType, $entityId, $finalFileName, $size);

        return $result;
    }//end uploadImageSmart()
    //********************************* Pozvana u fajlovima gde je potreban upload slika *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    /*******************************
     * KONVERZIJA U WEBP (GD)
     * Podržava samo JPG/JPEG i PNG (ne i GIF)
     *******************************/
    private function convertToWebpAndSave(string $tmpPath, string $destPath, int $quality = 75): bool
    {
        if (!function_exists('imagewebp')) {
            return false;
        }

        $info = @getimagesize($tmpPath);
        if ($info === false || empty($info['mime'])) {
            return false;
        }

        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $img = @imagecreatefromjpeg($tmpPath);
                break;

            case 'image/png':
                $img = @imagecreatefrompng($tmpPath);
                if ($img) {
                    imagepalettetotruecolor($img);
                    imagealphablending($img, true);
                    imagesavealpha($img, true);
                }
                break;

            default:
                return false;
        }

        if (!$img) return false;

        $dir = dirname($destPath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        return @imagewebp($img, $destPath, (int)$quality) ? true : false;
    }//end 
    //********************************* Pozvana u u ovom fajlu u metodi uploadImageSmart() *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    public function uploadAndUpdateImageField(
        string $fileKey,
        string $targetDir,
        string $logType,
        int $entityId,
        mysqli $conn,
        string $table,
        string $imageColumn,
        string $idColumn,
        int $quality = 75
    ): array
    {
        // 1) prvo uradi upload/konverziju
        $res = $this->uploadImageSmart($fileKey, $targetDir, $logType, $entityId, $quality);

        if ($res['ok'] !== true) {
            return $res; // već ima error + log fail
        }

        // 2) SIGURNOSNA provjera: tabela i kolone (da ne može injection)
        // dozvoljavamo samo slova, brojeve i underscore
        foreach ([$table, $imageColumn, $idColumn] as $identifier) {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $identifier)) {
                $res['ok'] = false;
                $res['error'] = "INVALID_IDENTIFIER";
                logUploadFail($logType, $entityId, $_FILES[$fileKey]['name'], $res['error']);
                return $res;
            }
        }

        // 3) UPDATE baze (prepared za vrijednosti)
        $sql = "UPDATE {$table} SET {$imageColumn} = ? WHERE {$idColumn} = ?";

        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            $res['ok'] = false;
            $res['error'] = "DB_PREPARE_FAIL | " . mysqli_error($conn);
            logUploadFail($logType, $entityId, $_FILES[$fileKey]['name'], $res['error']);
            return $res;
        }

        $file = $res['file'];
        $id   = $entityId;

        mysqli_stmt_bind_param($stmt, "si", $file, $id);

        if (!mysqli_stmt_execute($stmt)) {
            $res['ok'] = false;
            $res['error'] = "DB_EXEC_FAIL | " . mysqli_stmt_error($stmt);
            logUploadFail($logType, $entityId, $_FILES[$fileKey]['name'], $res['error']);
            mysqli_stmt_close($stmt);
            return $res;
        }

        mysqli_stmt_close($stmt);

        // ✅ sve prošlo
        return $res;
    }

}//end class