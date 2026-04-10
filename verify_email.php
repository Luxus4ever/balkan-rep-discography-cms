<?php
require_once __DIR__ . "/config/config.php";
require_once __DIR__ . "/functions/log.func.php";

if (!isset($_GET['token']) || trim($_GET['token']) === '') {
    die("Neispravan token.");
}
$token = trim($_GET['token']);

global $conn;

// Nađi sve aktivne (ne used) tokene koji nisu istekli, pa provjeri password_verify
$q = "SELECT id, korisnikId, token_hash, expires_at
      FROM email_verifications
      WHERE used_at IS NULL AND expires_at > NOW()
      ORDER BY id DESC
      LIMIT 200"; // limit radi performansi
$r = mysqli_query($conn, $q);

$match = null;
while ($row = mysqli_fetch_assoc($r)) {
    if (password_verify($token, $row['token_hash'])) {
        $match = $row;
        break;
    }
}

if (!$match) {
    die("Link je nevažeći ili je istekao. Pošaljite novi verifikacioni link iz banera na sajtu.");
}

$evId = (int)$match['id'];
$userId = (int)$match['korisnikId'];

// Označi token kao iskorišten
mysqli_query($conn, "UPDATE email_verifications SET used_at=NOW() WHERE id={$evId}");

// Verifikuj korisnika
mysqli_query($conn, "UPDATE korisnici
                     SET email_verified=1, email_verified_at=NOW()
                     WHERE idKorisnici={$userId}");

$seconds = 6;
$redirectUrl = "login.php";

echo '<!doctype html><html lang="sr"><head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="refresh" content="'.$seconds.';url='.$redirectUrl.'">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<title>Email potvrđen</title>
</head><body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
      <div class="card shadow-sm">
        <div class="card-body text-center p-4">
          <h2 class="h5 mb-3">Email je uspješno potvrđen ✅</h2>
          <p class="mb-0">Možete nastaviti koristiti sajt.</p>
          <hr>
          <p class="text-muted mb-0">Bićete preusmjereni na <b>login</b> za '.$seconds.' sekundi.</p>
        </div>
      </div>
    </div>
  </div>
</div>
</body></html>';
exit;