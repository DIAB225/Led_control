<?php
require 'db.php';

// ---- 1) Si un enfant a cliqué sur un bouton ----
// Le lien du bouton ressemble à : index.php?toggle=2
// On regarde donc si ce paramètre existe dans l'URL
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];

    // On regarde l'état actuel de CETTE led
    $stmt = $pdo->prepare("SELECT etat FROM leds WHERE id = ?");
    $stmt->execute([$id]);
    $led = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($led) {
        // On inverse : si elle était allumée (1) elle devient éteinte (0), et inversement
        $nouvelEtat = $led['etat'] ? 0 : 1;

        $maj = $pdo->prepare("UPDATE leds SET etat = ? WHERE id = ?");
        $maj->execute([$nouvelEtat, $id]);
    }

    // Important : on redirige vers index.php SANS le ?toggle=
    // Sinon, si l'enfant appuie sur "actualiser" dans son navigateur,
    // ça re-déclencherait le toggle en boucle !
    header("Location: index.php");
    exit;
}

// ---- 2) On récupère l'état de toutes les LEDs pour les afficher ----
$leds = $pdo->query("SELECT * FROM leds ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Recharge la page automatiquement toutes les 3 secondes            -->
<!-- Comme ça, si un autre enfant clique depuis un autre appareil,     -->
<!-- ton écran se met à jour tout seul, sans JavaScript !              -->
<meta http-equiv="refresh" content="3">
<title>💡 Contrôle des LEDs</title>
<style>
    body {
        font-family: 'Comic Sans MS', 'Trebuchet MS', sans-serif;
        background: linear-gradient(135deg, #74ebd5, #ACB6E5);
        margin: 0;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 25px;
        padding: 20px;
    }
    h1 {
        color: #fff;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        font-size: 2.2em;
        margin-bottom: 10px;
    }
    .grid {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .card {
        background: white;
        border-radius: 25px;
        padding: 20px 35px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        width: 280px;
        text-align: center;
    }
    .card h2 {
        margin: 0 0 12px 0;
        color: #333;
        font-size: 1.5em;
    }
    .statut {
        font-weight: bold;
        padding: 8px;
        border-radius: 10px;
        margin-bottom: 15px;
    }
    .statut.on {
        background: #fff9c4;
        color: #a67c00;
    }
    .statut.off {
        background: #eee;
        color: #888;
    }
    .btn {
        display: inline-block;
        text-decoration: none;
        font-size: 1.2em;
        font-weight: bold;
        padding: 15px 25px;
        border-radius: 15px;
        color: white;
        width: 100%;
        box-sizing: border-box;
    }
    .btn-on  { background: #51cf66; }
    .btn-off { background: #ff6b6b; }
</style>
</head>
<body>

<h1>💡 Contrôle des LEDs</h1>

<div class="grid">
    <?php foreach ($leds as $led): ?>
        <div class="card">
            <h2><?= htmlspecialchars($led['nom']) ?></h2>
            <div class="statut <?= $led['etat'] ? 'on' : 'off' ?>">
                Statut : <?= $led['etat'] ? 'ALLUMÉE' : 'ÉTEINTE' ?>
            </div>
            <a href="?toggle=<?= (int)$led['id'] ?>"
               class="btn <?= $led['etat'] ? 'btn-off' : 'btn-on' ?>">
                <?= $led['etat'] ? '⏻ ÉTEINDRE' : '⏻ ALLUMER' ?>
            </a>
        </div>
    <?php endforeach; ?>

    <?php if (empty($leds)): ?>
        <p style="color:white;">Aucune LED trouvée. Vérifie la table <code>leds</code> dans ta base.</p>
    <?php endif; ?>
</div>

</body>
</html>