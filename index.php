<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        gap: 30px;
    }
    h1 {
        color: #fff;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        font-size: 2.5em;
    }
    .led-card {
        background: white;
        border-radius: 25px;
        padding: 25px 40px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        gap: 25px;
        width: 320px;
    }
    .indicateur {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #ccc;
        transition: all 0.3s;
        flex-shrink: 0;
    }
    .indicateur.allumee {
        background: #ffd700;
        box-shadow: 0 0 20px #ffd700;
    }
    .nom-led {
        font-size: 1.4em;
        font-weight: bold;
        color: #333;
        flex-grow: 1;
    }
    button {
        font-size: 1.1em;
        font-weight: bold;
        padding: 12px 20px;
        border: none;
        border-radius: 15px;
        cursor: pointer;
        transition: transform 0.15s;
        color: white;
    }
    button:active {
        transform: scale(0.92);
    }
    button.eteindre { background: #ff6b6b; }
    button.allumer  { background: #51cf66; }
</style>
</head>
<body>

<h1>💡 Contrôle des LEDs</h1>

<div id="conteneur-leds"></div>

<script>
async function chargerEtats() {
    const reponse = await fetch('getState.php');
    const leds = await reponse.json();

    const conteneur = document.getElementById('conteneur-leds');
    conteneur.innerHTML = '';

    leds.forEach(led => {
        const allumee = led.etat == 1;
        const carte = document.createElement('div');
        carte.className = 'led-card';
        carte.style.marginBottom = '20px';
        carte.innerHTML = `
            <div class="indicateur ${allumee ? 'allumee' : ''}"></div>
            <div class="nom-led">${led.nom}</div>
            <button class="${allumee ? 'eteindre' : 'allumer'}"
                    onclick="basculer(${led.id}, ${allumee ? 0 : 1})">
                ${allumee ? '⏻ Éteindre' : '⏻ Allumer'}
            </button>
        `;
        conteneur.appendChild(carte);
    });
}

async function basculer(id, nouvelEtat) {
    await fetch('setState.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id, etat: nouvelEtat })
    });
    chargerEtats(); // on rafraîchit tout de suite pour un feedback instantané
}

chargerEtats();
setInterval(chargerEtats, 2000); // resynchro toutes les 2s (si un autre appareil a cliqué)
</script>

</body>
</html>