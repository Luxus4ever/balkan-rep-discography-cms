<?php
require_once "config/bootstrap.php";
include "classes/ratings.class.php";

header('Content-Type: application/json');

$rt = new ocjene();
$action = $_POST['action'] ?? null;
$sesId = $_SESSION['idKorisnici'] ?? null;

if (!$sesId) {
    echo json_encode(['error' => 'Morate biti ulogovani da biste ocijenjivali.']);
    exit;
}

switch ($action) {
    case 'save_rating':
        $odabrano = $_POST['odabrano'] ?? null;
        $album = $_POST['album'] ?? null;
        $izvodjac = $_POST['izvodjac'] ?? null;

        if (is_numeric($odabrano) && is_numeric($album) && is_numeric($izvodjac)) {
            $response = $rt->spremiOcjenuAPI($album, $sesId, $odabrano, $izvodjac);
            echo json_encode($response);
        } else {
            echo json_encode(['error' => 'Neispravni podaci']);
        }
        break;

    case 'get_rating':
        $album = $_POST['album'] ?? null;
        if (is_numeric($album)) {
            $response = $rt->dohvatiOcjenuAPI($album, $sesId);
            echo json_encode($response);
        } else {
            echo json_encode(['error' => 'Neispravan ID albuma']);
        }
        break;

    case 'delete_rating':
    $album = $_POST['album'] ?? null;
    $izvodjac = $_POST['izvodjac'] ?? null;
    $korisnikId = $sesId;
    if (is_numeric($album) && is_numeric($izvodjac)) {
        $response = $rt->obrisiOcjenuAPI($album, $korisnikId);
        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'Neispravni podaci za brisanje']);
    }
    break;

    default:
        echo json_encode(['error' => 'Nepoznata akcija']);
        break;
}
?>
