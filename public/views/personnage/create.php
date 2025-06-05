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
                $stats[$stat] = (int)$_POST[$stat];
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
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="character-sheet creation-form">
        <div class="character-header">
            <h1>Créer un Personnage</h1>
        </div>
        
        <?php if ($message): ?>
            <div class="alert"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" action="../../actions/personnage/create.php" class="character-form">
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
                <button type="submit">Créer</button>
                <a href="/views/personnage/index.php" class="button">Retour</a>
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

    // Mettre à jour les stats au chargement de la page
    document.addEventListener('DOMContentLoaded', updateDefaultStats);
    </script>
</body>
</html> 