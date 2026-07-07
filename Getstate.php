<?php
require 'db.php';
header('Content-Type: application/json');

// Sans id -> on renvoie l'état de TOUTES les LEDs (pratique pour le site)
// Avec id -> on renvoie juste celle-là (pratique pour l'ESP32)
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT id, nom, etat FROM leds WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $led = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$led) {
        http_response_code(404);
        echo json_encode(['erreur' => 'LED introuvable']);
        exit;
    }
    echo json_encode($led);
} else {
    $stmt = $pdo->query("SELECT id, nom, etat FROM leds ORDER BY id");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}