<?php
require_once "config/bootstrap.php";
include "header.php";

$token = trim($_GET['token'] ?? '');
$tokenHash = $token ? hash('sha256', $token) : '';

// ================================
// DEBUG (samo u LOCAL / WAMP)
// ================================
/*if (defined('APP_ENV') && APP_ENV === 'local' && isset($_GET['debug'])) {
    $token_dbg = trim($_GET['token'] ?? '');
    $tokenHash_dbg = $token_dbg ? hash('sha256', $token_dbg) : '';

    echo "<pre>";
    echo "TOKEN: " . htmlspecialchars($token_dbg) . "\n";
    echo "HASH:  " . htmlspecialchars($tokenHash_dbg) . "\n";
    echo "</pre>";

    $stmt = mysqli_prepare($conn, "SELECT idPasswordResets, korisnikId, expires_at, used_at FROM password_resets WHERE token_hash = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $tokenHash_dbg);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    echo "<pre>";
    var_dump($row);
    echo "</pre>";
    exit;
}*/
// ================================
// KRAJ DEBUG BLOKA
// ================================

headerPutanja();

$poruka = "";
$greska = "";

$token = trim($_GET['token'] ?? '');
$tokenHash = $token ? hash('sha256', $token) : '';

$resetId = null;
$korisnikId = null;

// 1) Validacija tokena
if (empty($token)) {
    $greska = "Nedostaje token. Provjerite link iz emaila.";
} else {
    $stmt = mysqli_prepare($conn, "
        SELECT idPasswordResets, korisnikId
        FROM password_resets
        WHERE token_hash = ?
          AND used_at IS NULL
          AND expires_at > NOW()
        LIMIT 1
    ");
    mysqli_stmt_bind_param($stmt, "s", $tokenHash);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $resetId, $korisnikId);
    $ok = mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if (!$ok) {
        $greska = "Link nije validan ili je istekao. Zatražite novi reset šifre.";
    }
}

// 2) Obrada nove šifre
if (empty($greska) && isset($_POST['novaSifra'])) {

    $pass1 = trim($_POST['password']) ?? '';
    $pass2 = trim($_POST['password2']) ?? '';

    if ($pass1 !== $pass2) {
        $greska = "Šifre se ne poklapaju.";
    } elseif (mb_strlen($pass1, 'UTF-8') < 8) {
        $greska = "Šifra mora imati najmanje 8 karaktera.";
    } else {
        // Hash lozinke
        $newHash = password_hash($pass1, PASSWORD_DEFAULT);
        $sifrovano2= hash("gost-crypto", $pass2);

        // Update korisnika
        $stmt = mysqli_prepare($conn, "UPDATE korisnici SET sifra = ?, sifra2 = ? WHERE idKorisnici = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "ssi", $newHash, $sifrovano2, $korisnikId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Označi token iskorišten
        $stmt = mysqli_prepare($conn, "UPDATE password_resets SET used_at = NOW() WHERE idPasswordResets = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "i", $resetId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $poruka = "Šifra je uspješno promijenjena. Sada se možete prijaviti.";
    }
}
?>
<div class="slikeAlbumaPregled sredina">
    <fieldset class="border p-5 rounded">
        <legend class="w-auto px-2">Unesite novu šifru</legend>
        <h6 class="sredina">Šifra mora imati najmanje 8 karaktera.</h6>

        <?php if (!empty($poruka)) { ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($poruka); ?></div>
        <?php } ?>

        <?php if (!empty($greska)) { ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($greska); ?></div>
        <?php } ?>

        <?php if (empty($greska) && empty($poruka)) { ?>
        <form method="POST" action="">
            <div class="col-auto">
                <div class="form-group">
                    <input type="password" class="form-control form-control-sm" name="password" id="password"
                           placeholder="Šifra" required="required">
                </div>
            </div><br><br>

            <div class="col-auto">
                <div class="form-group">
                    <input type="password" class="form-control form-control-sm" name="password2" id="password2"
                           placeholder="Ponovite šifru" required="required">
                </div>
            </div><br><br>

            <div class="col-auto">
                <input type="submit" class="btn btn-primary btn-sm" name="novaSifra" value="Nova šifra">
            </div>
        </form>
        <?php } ?>

    </fieldset>
</div>
<?php
include "footer.php";
footerPutanja();
