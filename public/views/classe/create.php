<?php
<<<<<<< HEAD
session_start();
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/vendor/autoload.php';
=======
require_once __DIR__ . '/../../../autoload.php';
>>>>>>> main

use App\Classes\ClasseFactory;

session_start();

$factory = new ClasseFactory();

<<<<<<< HEAD
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
        header('Location: /views/classe/index.php');
        exit;
    } else {
        $error = 'Le nom et la description sont requis';
    }
}
=======
// Types de classe disponibles
$types = [
    'Combat' => '⚔️',
    'Distance' => '🏹',
    'Arcanes' => '🔮',
    'Support' => '💖',
    'Tank' => '🛡️'
];
>>>>>>> main
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <title>Créer une Classe</title>
    <link rel="stylesheet" href="/php-patron-pnj/public/css/style.css">
    <link rel="stylesheet" href="/php-patron-pnj/public/css/classes.css">
=======
    <title>Créer une nouvelle classe</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/cards.css">
>>>>>>> main
</head>
<body>
    <div class="class-creator">
        <div class="header-content">
            <h1>Créer une nouvelle classe</h1>
        </div>

        <div class="creator-layout">
            <form id="classForm" action="/actions/classe/create.php" method="POST" class="class-form">
                <div class="form-section">
                    <div class="form-group">
                        <label for="name">Nom de la classe</label>
                        <input type="text" id="name" name="name" required 
                               placeholder="Ex: Paladin, Druide..."
                               class="form-input">
                    </div>

                    <div class="form-group">
                        <label>Type de classe</label>
                        <div class="type-options">
                            <?php foreach ($types as $type => $icon): ?>
                                <label class="type-option">
                                    <input type="radio" name="type" value="<?= htmlspecialchars($type) ?>" required>
                                    <span class="type-card">
                                        <span class="type-icon"><?= $icon ?></span>
                                        <span class="type-name"><?= htmlspecialchars($type) ?></span>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

<<<<<<< HEAD
        <form method="POST" action="/php-patron-pnj/public/actions/classe/create.php" class="character-form">
            <div class="form-group">
                <label for="nom">Nom de la classe</label>
                <input type="text" id="nom" name="nom" required>
            </div>
=======
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" required
                                  placeholder="Décrivez les caractéristiques de la classe..."
                                  class="form-input"></textarea>
                    </div>
                </div>
>>>>>>> main

                <div class="form-section">
                    <h2>Statistiques de base</h2>
                    <div class="stats-grid">
                        <div class="form-group">
                            <label for="baseHP">PV de base</label>
                            <input type="number" id="baseHP" name="baseHP" required
                                   min="1" max="20" value="10"
                                   class="form-input">
                        </div>
                        <div class="form-group">
                            <label for="hitDie">Dé de vie</label>
                            <select id="hitDie" name="hitDie" required class="form-input">
                                <option value="6">d6</option>
                                <option value="8">d8</option>
                                <option value="10">d10</option>
                                <option value="12">d12</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="baseAC">CA de base</label>
                            <input type="number" id="baseAC" name="baseAC" required
                                   min="10" max="18" value="10"
                                   class="form-input">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2>Statistiques</h2>
                    <div class="stats-grid">
                        <div class="form-group">
                            <label for="force">Force</label>
                            <input type="number" id="force" name="stats[force]" required
                                   min="8" max="16" value="10"
                                   class="form-input">
                        </div>
                        <div class="form-group">
                            <label for="dexterite">Dextérité</label>
                            <input type="number" id="dexterite" name="stats[dexterite]" required
                                   min="8" max="16" value="10"
                                   class="form-input">
                        </div>
                        <div class="form-group">
                            <label for="constitution">Constitution</label>
                            <input type="number" id="constitution" name="stats[constitution]" required
                                   min="8" max="16" value="10"
                                   class="form-input">
                        </div>
                        <div class="form-group">
                            <label for="intelligence">Intelligence</label>
                            <input type="number" id="intelligence" name="stats[intelligence]" required
                                   min="8" max="16" value="10"
                                   class="form-input">
                        </div>
                        <div class="form-group">
                            <label for="sagesse">Sagesse</label>
                            <input type="number" id="sagesse" name="stats[sagesse]" required
                                   min="8" max="16" value="10"
                                   class="form-input">
                        </div>
                        <div class="form-group">
                            <label for="charisme">Charisme</label>
                            <input type="number" id="charisme" name="stats[charisme]" required
                                   min="8" max="16" value="10"
                                   class="form-input">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2>Maîtrises</h2>
                    <div id="proficienciesContainer" class="proficiencies-list">
                        <div class="proficiency-item">
                            <input type="text" name="proficiencies[]" required
                                   placeholder="Ex: Épées longues"
                                   class="form-input">
                            <button type="button" class="remove-proficiency">×</button>
                        </div>
                    </div>
                    <button type="button" id="addProficiency" class="button secondary">
                        + Ajouter une maîtrise
                    </button>
                </div>

                <div class="form-actions">
                    <button type="submit" class="button primary">
                        <span class="button-icon">✨</span>
                        Créer la classe
                    </button>
                    <button type="reset" class="button secondary">
                        <span class="button-icon">🔄</span>
                        Réinitialiser
                    </button>
                    <a href="/views/classe/index.php" class="button">Retour</a>
                </div>
            </form>

            <div class="preview-section">
                <div class="class-card">
                    <div class="class-header">
                        <h2 id="previewName">Nom de la classe</h2>
                        <span id="previewType" class="class-type">Type</span>
                    </div>
                    <div class="class-stats">
                        <div class="stat">
                            <span class="stat-label">PV de base</span>
                            <span id="previewHP" class="stat-value">10</span>
                        </div>
                        <div class="stat">
                            <span class="stat-label">Dé de vie</span>
                            <span id="previewHitDie" class="stat-value">d6</span>
                        </div>
                        <div class="stat">
                            <span class="stat-label">CA de base</span>
                            <span id="previewAC" class="stat-value">10</span>
                        </div>
                    </div>
                    <div class="class-proficiencies">
                        <h3>Maîtrises</h3>
                        <ul id="previewProficiencies"></ul>
                    </div>
                    <div id="previewDescription" class="class-description">
                        Description de la classe...
                    </div>
                </div>
            </div>
<<<<<<< HEAD

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
                <a href="/php-patron-pnj/public/views/classe/index.php" class="button">Annuler</a>
                <button type="submit" class="button primary">Créer la classe</button>
            </div>
        </form>
=======
        </div>
>>>>>>> main
    </div>

    <style>
    .creator-layout {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 2rem;
        padding: 2rem;
    }

    .class-form {
        background: var(--card-bg);
        border-radius: 8px;
        padding: 2rem;
    }

    .form-section {
        margin-bottom: 2rem;
    }

    .form-section h2 {
        color: var(--text-muted);
        font-size: 1.2em;
        margin-bottom: 1rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }

    .type-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .type-option {
        cursor: pointer;
        position: relative;
    }

    .type-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 1px;
        height: 1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    .type-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1rem;
        background: var(--bg-light);
        border: 2px solid transparent;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .type-option input[type="radio"]:focus-visible + .type-card {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px var(--primary-color);
    }

    .type-option input[type="radio"]:checked + .type-card {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .type-icon {
        font-size: 1.5em;
        margin-bottom: 0.5rem;
    }

    .proficiencies-list {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .proficiency-item {
        display: flex;
        gap: 0.5rem;
    }

    .remove-proficiency {
        background: var(--danger-color);
        color: white;
        border: none;
        border-radius: 4px;
        width: 30px;
        height: 30px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2em;
    }

    .preview-section {
        position: sticky;
        top: 2rem;
    }

    @media (max-width: 1024px) {
        .creator-layout {
            grid-template-columns: 1fr;
        }

        .preview-section {
            position: static;
        }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('classForm');
        const addProficiencyBtn = document.getElementById('addProficiency');
        const proficienciesContainer = document.getElementById('proficienciesContainer');

        // Prévisualisation
        const previewName = document.getElementById('previewName');
        const previewType = document.getElementById('previewType');
        const previewHP = document.getElementById('previewHP');
        const previewHitDie = document.getElementById('previewHitDie');
        const previewAC = document.getElementById('previewAC');
        const previewDescription = document.getElementById('previewDescription');
        const previewProficiencies = document.getElementById('previewProficiencies');

        // Mise à jour de la prévisualisation
        function updatePreview() {
            previewName.textContent = document.getElementById('name').value || 'Nom de la classe';
            
            const selectedType = document.querySelector('input[name="type"]:checked');
            previewType.textContent = selectedType ? selectedType.value : 'Type';
            
            previewHP.textContent = document.getElementById('baseHP').value;
            previewHitDie.textContent = 'd' + document.getElementById('hitDie').value;
            previewAC.textContent = document.getElementById('baseAC').value;
            previewDescription.textContent = document.getElementById('description').value || 'Description de la classe...';

            // Mise à jour des maîtrises
            const proficiencies = Array.from(document.querySelectorAll('input[name="proficiencies[]"]'))
                .map(input => input.value)
                .filter(value => value.trim() !== '');

            previewProficiencies.innerHTML = proficiencies
                .map(prof => `<li>${prof}</li>`)
                .join('');
        }

        // Écouter les changements
        form.addEventListener('input', updatePreview);
        form.addEventListener('change', updatePreview);

        // Gestion des maîtrises
        addProficiencyBtn.addEventListener('click', function() {
            const item = document.createElement('div');
            item.className = 'proficiency-item';
            item.innerHTML = `
                <input type="text" name="proficiencies[]" required
                       placeholder="Ex: Épées longues"
                       class="form-input">
                <button type="button" class="remove-proficiency">×</button>
            `;
            proficienciesContainer.appendChild(item);

            item.querySelector('.remove-proficiency').addEventListener('click', function() {
                item.remove();
                updatePreview();
            });
        });

        // Supprimer une maîtrise
        proficienciesContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-proficiency')) {
                e.target.parentElement.remove();
                updatePreview();
            }
        });

        // Validation des nombres
        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('input', function(e) {
                let value = this.value;
                value = value.replace(/[^0-9]/g, '');
                const min = parseInt(this.getAttribute('min')) || 0;
                const max = parseInt(this.getAttribute('max')) || Infinity;
                value = Math.max(min, Math.min(max, parseInt(value) || 0));
                this.value = value;
            });
        });
    });
    </script>
</body>
</html> 