<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

if (!isset($_SESSION['personnages'])) {
    $_SESSION['personnages'] = [];
}

use App\Fabrique\FabriquePersonnage;

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fabrique = new FabriquePersonnage();
    
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

        $_SESSION['personnages'][] = [
            'nom' => $nom,
            'classe' => $classe,
            'categorie' => $categorie,
            'stats' => $stats
        ];

        // Rediriger vers la liste des personnages
        header('Location: personnages.php');
        exit;
    } else {
        $message = 'Le nom est requis';
    }
}

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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="character-sheet creation-form">
        <div class="character-header">
            <h1>Créer un Personnage</h1>
        </div>
        
        <?php if ($message): ?>
            <div class="alert"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" class="character-form">
            <div class="form-group">
                <label for="nom">Nom du personnage</label>
                <input type="text" id="nom" name="nom" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="classe">Classe</label>
                    <select id="classe" name="classe" onchange="updateDefaultStats()">
                        <option value="guerrier">Guerrier</option>
                        <option value="archer">Archer</option>
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
                <a href="index.php" class="button">Retour</a>
            </div>
        </form>
    </div>

    <script>
    const statsDefaut = <?= json_encode($statsDefaut) ?>;

    function updateDefaultStats() {
        const classe = document.getElementById('classe').value;
        const stats = statsDefaut[classe];
        
        for (const [stat, value] of Object.entries(stats)) {
            const input = document.getElementById(stat);
            if (input) {
                input.value = value;
            }
        }
    }
    </script>
</body>
</html> 