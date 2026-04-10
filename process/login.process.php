<?php
include "../config/config.php";
include "../functions/master.func.php";
include_once "../functions/removeSymbols.func.php";

include_once "../header.php";

//headerFunction();




$folder = dirname($_SERVER['PHP_SELF']);
//print_r($folder);
headerPutanja("../", "../");
?>
<div class="wrapper">
    <div class="slikeAlbumaPregled sredina">
        <?php
        /*if (defined('APP_ENV') && APP_ENV === 'local') {
            appLog('auth', 'INFO', 'TEST log radi', ['hint' => 'path OK']);
        }*/

        if(isset($_POST["ulogujSe"]))
        {
            $username= strip_tags($_POST["username"]);
            $logSifra= strip_tags($_POST["password"]);

            $cleanUsername= removeSimbols(trim($username));
            $cleanSifra = trim($logSifra);

            $q= "SELECT * FROM korisnici WHERE username='$cleanUsername' LIMIT 1";
            $loginKorisnika= mysqli_query($conn, $q);


            while($row= mysqli_fetch_assoc($loginKorisnika))
            {
                $uName= $row["username"];
                $logSifra= $row["sifra"];
                $ime= $row["ime"];
                $lid= $row["idKorisnici"];
                $statusKorisnika= $row["statusKorisnika"];
                $labelId= $row["label_Id"];
            }//end while

            $loginOk = false;

            // 1) novo: password_hash()
            if (!empty($logSifra) && password_verify($cleanSifra, $logSifra)) {
                $loginOk = true;
            }
            // 2) staro: sha256 fallback (dok ne prebaciš sve naloge)
            elseif (!empty($logSifra) && hash("sha256", $cleanSifra) === $logSifra) {
                $loginOk = true;

                // OPTIONAL (preporuka): automatska migracija na password_hash
                $noviHash = password_hash($cleanSifra, PASSWORD_DEFAULT);
                mysqli_query($conn, "UPDATE korisnici SET sifra='".mysqli_real_escape_string($conn,$noviHash)."' WHERE idKorisnici='{$lid}' LIMIT 1");
            }

            if ($cleanUsername === @$uName && $loginOk)
            {
                $q2= "UPDATE korisnici SET zadnjiLogin=now() WHERE username='{$cleanUsername}'";
                $zl= mysqli_query($conn, $q2);

                @session_start();
                $_SESSION["idKorisnici"]= $lid;
                $_SESSION["username"]= $cleanUsername;
                date_default_timezone_set('Europe/Belgrade');
                $_SESSION["vrijeme"]= date('d-m-Y H:i:s');
                $_SESSION["statusKorisnika"]= $statusKorisnika;
                $_SESSION["label_Id"]= $labelId;
                snimiSesijuUBazu($conn, $lid);

                logLoginSuccess($lid, $statusKorisnika, $labelId);

                
                //$_SESSION["password"]= $sifrovano;
                ?> 
                <meta http-equiv="refresh" content="0; url=../profile.php?username=<?php echo $uName . '&lid=' . $lid; ?>">
                <?php
            }else{
                echo "<h1>Pogrešna šifra</h1>";
                logLoginFail($username);
                ?>        
                <meta http-equiv="refresh" content="3; url=../login.php">
            <?php
            }//end if else
        }//end if
        ?>
    </div><!--  kraj .slikeAlbumaPregled -->
</div><!-- kraj #wrapper -->
<?php



include "../footer.php";
footerPutanja("../");