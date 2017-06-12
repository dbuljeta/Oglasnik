<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('spoj.php');
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $proizvod = $data['proizvod'];
        $ocjenjivac = $data['ocjenjivac'];
        $korisnik = $data['korisnik'];
        $stmt = $veza->prepare("DELETE FROM ocjene WHERE korisnik_id=:korisnik AND ocjenivac_id=:ocjenjivac AND proizvod_id=:proizvod");
        $stmt->bindParam(':proizvod', $proizvod);
        $stmt->bindParam(':ocjenjivac', $ocjenjivac);
        $stmt->bindParam(':korisnik', $korisnik);
        $stmt->execute();

        echo json_encode(array('stauts' => 1, "akcije" => "neocjeni"));
    } catch(Exception $e) {
        echo json_encode(array('stauts' => 0));
    }
}