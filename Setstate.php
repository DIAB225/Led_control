<?php
require 'db.php';
header('Content-Type: application/json');

// On lit les données envoyées en JSON par le site web
$data = json_decode(file_get_contents('php://input'), true);

$id   = $data['id']   ?? null;
$etat = $data['etat'] ?? null; // 0 ou 1

if ($id === null || $etat === null || !in_array((int)$etat, [0, 1], true)) {
    http_response_code(400);
    echo json_encode(['erreur' => 'Paramètres invalides']);
    exit;
}

$stmt = $pdo->prepare("UPDATE leds SET etat = ? WHERE id = ?");
$stmt->execute([(int)$etat, (int)$id]);

echo json_encode(['succes' => true, 'id' => (int)$id, 'etat' => (int)$etat]);