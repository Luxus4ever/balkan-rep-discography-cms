<?php
session_start();
require_once __DIR__ . "/config/config.php";
require_once __DIR__ . "/functions/emailverify.func.php";

if (!isset($_SESSION['idKorisnici'])) {
    die("Nemate prava pristupa.");
}

$uid = (int)$_SESSION['idKorisnici'];

global $conn;
$q = "SELECT email, username, email_verified FROM korisnici WHERE idKorisnici='{$uid}' LIMIT 1";
$r = mysqli_query($conn, $q);
$u = $r ? mysqli_fetch_assoc($r) : null;

if (!$u) die("Korisnik ne postoji.");

if ((int)$u['email_verified'] === 1) {
    die("Email je već potvrđen.");
}

[$ok, $msg] = emailVerify_sendOrResend($uid, $u['email'], $u['username']);

$redirectUrl = "profile.php?username=" . urlencode($_SESSION['username'] ?? '') . "&lid=" . $uid;
$seconds = 4;

echo '<!doctype html><html lang="sr"><head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="refresh" content="'.$seconds.';url='.$redirectUrl.'">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<title>Verifikacija emaila</title>
</head><body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body text-center p-4">
          <h2 class="h5 mb-3">Verifikacioni link je poslat</h2>
          <p class="mb-0">'.htmlspecialchars($msg, ENT_QUOTES, 'UTF-8').'</p>
          <hr>
          <p class="text-muted mb-0">Bićete preusmjereni na profil za '.$seconds.' sekundi.</p>
        </div>
      </div>
    </div>
  </div>
</div>
</body></html>';
exit;