<?php
// Railway injecte automatiquement ces variables quand tu ajoutes
// le service MySQL à ton projet (visibles dans l'onglet "Variables" du service PHP,
// à condition de les avoir référencées comme ${{MySQL.MYSQLHOST}}, etc.
// -> Voir l'onglet "Connect" de ton service MySQL pour les noms exacts)
$host = getenv('MYSQLHOST');
$port = getenv('MYSQLPORT');
$db   = getenv('MYSQLDATABASE');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    http_response_code(500);
    // MODE DEBUG TEMPORAIRE : affiche le vrai message d'erreur
    // À retirer une fois le problème réglé (ne pas laisser en prod)
    die(json_encode([
        'erreur' => 'Connexion BD impossible',
        'details' => $e->getMessage(),
        'host_utilise' => $host,
        'port_utilise' => $port,
        'db_utilisee' => $db
    ]));
}
