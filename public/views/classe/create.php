<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once ROOT_PATH . '/vendor/autoload.php';

use App\Classes\ClasseFactory;

if (!isset($_SESSION['classes'])) {
    $_SESSION['classes'] = [];
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $description = $_POST['description'] ?? '';
    $stats = $_POST['stats'] ?? [];
    $competences = $_POST['competences'] ?? [];
    $equipement = $_POST['equipement'] ?? [];

    if ($nom && $description) {
        $nouvelleClasse = [
            'id' => strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $nom)),
            'nom' => $nom,
            'description' => $description,
            'stats_base' => $stats,
            'competences' => array_filter($competences),
            'equipement_initial' => array_filter($equipement)
        ];

        $_SESSION['classes'][] = $nouvelleClasse;
        header('Location: classes.php');
        exit;
    } else {
        $error = 'Le nom et la description sont requis';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Classe</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/classes.css">
</head>
<body>
    <div class="creation-form">
        <div class="character-header">
            <div class="header-content">
                <h1>Créer une Classe</h1>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="alert success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="/actions/classe/create.php" class="character-form">
            <div class="form-group">
                <label for="nom">Nom de la classe</label>
                <input type="text" id="nom" name="nom" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3" required></textarea>
            </div>

            <div class="form-section">
                <h3>Statistiques de base</h3>
                <div class="stats-grid">
                    <div class="form-group">
                        <label for="force">Force</label>
                        <input type="number" id="force" name="stats[force]" min="8" max="15" value="10">
                    </div>
                    <div class="form-group">
                        <label for="dexterite">Dextérité</label>
                        <input type="number" id="dexterite" name="stats[dexterite]" min="8" max="15" value="10">
                    </div>
                    <div class="form-group">
                        <label for="constitution">Constitution</label>
                        <input type="number" id="constitution" name="stats[constitution]" min="8" max="15" value="10">
                    </div>
                    <div class="form-group">
                        <label for="intelligence">Intelligence</label>
                        <input type="number" id="intelligence" name="stats[intelligence]" min="8" max="15" value="10">
                    </div>
                    <div class="form-group">
                        <label for="sagesse">Sagesse</label>
                        <input type="number" id="sagesse" name="stats[sagesse]" min="8" max="15" value="10">
                    </div>
                    <div class="form-group">
                        <label for="charisme">Charisme</label>
                        <input type="number" id="charisme" name="stats[charisme]" min="8" max="15" value="10">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Compétences</h3>
                <div class="competences-container" id="competences-container">
                    <div class="competence-entry">
                        <input type="text" name="competences[]" placeholder="Nom de la compétence" required>
                        <button type="button" class="button remove-competence" onclick="removeCompetence(this)">×</button>
                    </div>
                </div>
                <button type="button" class="button" onclick="addCompetence()">Ajouter une compétence</button>
            </div>

            <div class="form-section">
                <h3>Équipement initial</h3>
                <div class="equipement-container" id="equipement-container">
                    <div class="equipement-entry">
                        <input type="text" name="equipement[]" placeholder="Pièce d'équipement" required>
                        <button type="button" class="button remove-equipement" onclick="removeEquipement(this)">×</button>
                    </div>
                </div>
                <button type="button" class="button" onclick="addEquipement()">Ajouter un équipement</button>
            </div>

            <div class="form-actions">
                <a href="/views/classe/index.php" class="button">Annuler</a>
                <button type="submit" class="button primary">Créer la classe</button>
            </div>
        </form>
    </div>

    <script>
    function addCompetence() {
        const container = document.getElementById('competences-container');
        const div = document.createElement('div');
        div.className = 'competence-entry';
        div.innerHTML = `
            <input type="text" name="competences[]" placeholder="Nom de la compétence" required>
            <button type="button" class="button remove-competence" onclick="removeCompetence(this)">×</button>
        `;
        container.appendChild(div);
    }

    function removeCompetence(button) {
        const container = document.getElementById('competences-container');
        if (container.children.length > 1) {
            button.parentElement.remove();
        }
    }

    function addEquipement() {
        const container = document.getElementById('equipement-container');
        const div = document.createElement('div');
        div.className = 'equipement-entry';
        div.innerHTML = `
            <input type="text" name="equipement[]" placeholder="Pièce d'équipement" required>
            <button type="button" class="button remove-equipement" onclick="removeEquipement(this)">×</button>
        `;
        container.appendChild(div);
    }

    function removeEquipement(button) {
        const container = document.getElementById('equipement-container');
        if (container.children.length > 1) {
            button.parentElement.remove();
        }
    }
    </script>
</body>
</html> 