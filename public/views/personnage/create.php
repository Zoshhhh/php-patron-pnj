<?php
session_start();
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/vendor/autoload.php';

use App\Fabrique\FabriquePersonnage;
use App\Classes\ClasseFactory;

if (!isset($_SESSION['personnages'])) {
    $_SESSION['personnages'] = [];
}

$fabrique = new FabriquePersonnage();
$classeFactory = new ClasseFactory();

// Récupération des classes disponibles
$classes = $classeFactory->getAvailableClasses();

// Récupération des messages
$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

// Stats par défaut pour chaque classe
$statsDefaut = [
    'guerrier' => [
        'force' => 16, 'dexterite' => 14, 'constitution' => 12,
        'intelligence' => 14, 'sagesse' => 14, 'charisme' => 12,
        'pointsDeVie' => 20, 'classeArmure' => 13, 'vitesse' => 30
    ],
    'archer' => [
        'force' => 12, 'dexterite' => 16, 'constitution' => 12,
        'intelligence' => 14, 'sagesse' => 14, 'charisme' => 12,
        'pointsDeVie' => 15, 'classeArmure' => 14, 'vitesse' => 35
    ]
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Personnage</title>
    <link rel="stylesheet" href="/php-patron-pnj/public/css/style.css">
    <link rel="stylesheet" href="/php-patron-pnj/public/css/create.css">
</head>
<body>
    <div class="creation-form">
        <nav class="creator-nav">
            <a href="/php-patron-pnj/public/views/personnage/index.php" class="nav-link">← Retour</a>
            <h1>Créer un Personnage</h1>
        </nav>

        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="/php-patron-pnj/public/actions/personnage/create.php" class="character-form">
            <div class="form-group">
                <label for="nom">Nom du personnage</label>
                <input type="text" id="nom" name="nom" required>
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

            <div class="form-actions">
                <button type="submit" class="button primary">Créer le personnage</button>
                <button type="reset" class="button secondary">Réinitialiser</button>
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
    </script>
</body>
</html> 