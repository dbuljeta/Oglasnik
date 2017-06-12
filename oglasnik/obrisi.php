<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('spoj.php');
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'];
        $stmt = $veza->prepare("DELETE FROM proizvodi WHERE id=:ladida");
        $stmt->bindParam(':ladida', $id);
        $stmt->execute();

        echo json_encode(array('stauts' => 1, 'id'=> $id));
    } catch(Exception $e) {
        echo json_encode(array('stauts' => 0));
    }
}