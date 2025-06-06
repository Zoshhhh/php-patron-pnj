<?php
session_start();
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/vendor/autoload.php';

use App\Factory\PersonnageFactory;
use App\Factory\ClasseFactory;

if (!isset($_SESSION['personnages'])) {
    $_SESSION['personnages'] = [];
}

$classeFactory = new ClasseFactory();
$classes = $classeFactory->getAvailableClasses();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fabrique = new PersonnageFactory();

    $nom = $_POST['nom'] ?? '';
    $classe = $_POST['classe'] ?? 'guerrier';
    $categorie = $_POST['categorie'] ?? 'personnage';

    if ($nom) {
        $stats = [];
        foreach (['force', 'dexterite', 'constitution', 'intelligence', 'sagesse', 'charisme', 'pointsDeVie', 'classeArmure', 'vitesse'] as $stat) {
            if (isset($_POST[$stat])) {
                $stats[$stat] = (int) $_POST[$stat];
            }
        }

        $personnage = $fabrique->creerPersonnage($classe, [
            'nom' => $nom,
            'categorie' => $categorie,
            'stats' => $stats
        ]);

        $_SESSION['personnages'][] = $personnage;
        header('Location: index.php');
        exit;
    } else {
        $message = 'Le nom est requis';
    }
}

// Récupérer les stats par défaut depuis la ClasseFactory
$statsDefaut = [];
foreach ($classes as $classe) {
    $details = $classeFactory->getClassDetails($classe['id']);
    if ($details) {
        $statsDefaut[$classe['id']] = array_merge(
            $details['stats_base'],
            [
                'pointsDeVie' => 20,
                'classeArmure' => 13,
                'vitesse' => 30
            ]
        );
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Personnage</title>
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/creation-form.css">
</head>

<body>
    <div class="creation-form">
        <nav class="back-nav">
            <a href="index.php" class="back-button">
                <span class="icon">←</span>
                <span>Retour</span>
            </a>
        </nav>

        <div class="creator-nav">
            <h1>Créer un Personnage</h1>
        </div>

        <?php if ($message): ?>
            <div class="alert error"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" action="../../actions/personnage/create.php">
            <div class="form-section">
                <h3>Informations de base</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="nom">Nom du personnage</label>
                        <input type="text" id="nom" name="nom" required placeholder="Entrez le nom du personnage"
                            class="input-large">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="classe">Classe</label>
                        <select id="classe" name="classe" onchange="updateDefaultStats()">
                            <?php foreach ($classes as $classe): ?>
                                <option value="<?= htmlspecialchars($classe['id']) ?>">
                                    <?= htmlspecialchars($classe['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="categorie">Catégorie</label>
                        <select id="categorie" name="categorie">
                            <option value="personnage">Personnage</option>
                            <option value="allie">Allié</option>
                            <option value="ennemi">Ennemi</option>
                            <option value="pnj">PNJ</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Caractéristiques</h3>
                <div class="form-group">
                    <button type="button" class="button secondary" onclick="apiRollDiceAsync('d20', 6, (data) => {
                            updateUi(['force', 'dexterite', 'constitution', 'intelligence', 'sagesse', 'charisme'], data.results);
                        })">
                        <span class="button-icon">🎲</span>
                        Lancer un dé pour toutes les stats
                    </button>
                </div>
                <div class="stats-grid">

                    <div class="form-group">
                        <label for="force">Force</label>
                        <input type="number" id="force" name="force" min="1" max="20" value="16">
                    </div>

                    <div class="form-group">
                        <label for="dexterite">Dextérité</label>
                        <input type="number" id="dexterite" name="dexterite" min="1" max="20" value="14">
                    </div>

                    <div class="form-group">
                        <label for="constitution">Constitution</label>
                        <input type="number" id="constitution" name="constitution" min="1" max="20" value="12">
                    </div>

                    <div class="form-group">
                        <label for="intelligence">Intelligence</label>
                        <input type="number" id="intelligence" name="intelligence" min="1" max="20" value="14">
                    </div>

                    <div class="form-group">
                        <label for="sagesse">Sagesse</label>
                        <input type="number" id="sagesse" name="sagesse" min="1" max="20" value="14">
                    </div>

                    <div class="form-group">
                        <label for="charisme">Charisme</label>
                        <input type="number" id="charisme" name="charisme" min="1" max="20" value="12">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Statistiques de combat</h3>
                <div class="stats-grid">
                    <div class="form-group">
                        <label for="pointsDeVie">Points de vie</label>
                        <input type="number" id="pointsDeVie" name="pointsDeVie" min="1" value="20">
                    </div>

                    <div class="form-group">
                        <label for="classeArmure">Classe d'armure</label>
                        <input type="number" id="classeArmure" name="classeArmure" min="1" value="13">
                    </div>

                    <div class="form-group">
                        <label for="vitesse">Vitesse</label>
                        <input type="number" id="vitesse" name="vitesse" min="1" value="30">
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="/views/personnage/index.php" class="button">Annuler</a>
                <button type="submit" class="button primary">
                    <span class="button-icon">✨</span>
                    Créer le personnage
                </button>
            </div>
        </form>
    </div>

    <script>
        const statsDefaut = <?= json_encode($statsDefaut) ?>;

        function updateDefaultStats() {
            const classe = document.getElementById('classe').value;
            const stats = statsDefaut[classe];

            if (stats) {
                for (const [stat, value] of Object.entries(stats)) {
                    const input = document.getElementById(stat);
                    if (input) {
                        input.value = value;
                    }
                }
            }
        }

        function apiRollDiceAsync(diceType, diceQuantity, callback) { 
            fetch('/api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `action=roll&dice=${diceType}&count=${diceQuantity}&save=0&saveHistory=0`
            })
            .then(res => res.json())
            .then(data => {
                console.log('Réponse API:', data);
                if (data.success && data.results) {
                    callback({
                        success: true,
                        results: data.results
                    });
                } else {
                    console.error('Format de réponse invalide:', data);
                    callback({
                        success: false,
                        results: Array(diceQuantity).fill(10)
                    });
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                callback({
                    success: false,
                    results: Array(diceQuantity).fill(10)
                });
            });
        }

        function updateUi(stats, results) {
            if (!results || !Array.isArray(results)) {
                console.error('Résultats invalides');
                return;
            }
            
            stats.forEach((stat, index) => {
                const input = document.getElementById(stat);
                if (input && results[index] !== undefined) {
                    input.value = results[index];
                }
            });
        }

        document.addEventListener('DOMContentLoaded', updateDefaultStats);
    </script>
</body>

</html>