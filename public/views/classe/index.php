<?php
session_start();
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/vendor/autoload.php';

use App\Classes\ClasseFactory;

if (!isset($_SESSION['classes'])) {
    $classeFactory = new ClasseFactory();
    $_SESSION['classes'] = array_map(function($classe) {
        return [
            'id' => array_search($classe, (new ClasseFactory())->getAvailableClasses()),
            'nom' => $classe['nom'],
            'description' => $classe['description'],
            'stats_base' => $classe['stats_base'],
            'competences' => $classe['competences'],
            'equipement_initial' => $classe['equipement_initial']
        ];
    }, $classeFactory->getAvailableClasses());
}

$selectedClass = null;
if (isset($_GET['classe'])) {
    foreach ($_SESSION['classes'] as $classe) {
        if ($classe['id'] === $_GET['classe']) {
            $selectedClass = $classe;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Classes</title>
    <link rel="stylesheet" href="/php-patron-pnj/public/css/style.css">
    <link rel="stylesheet" href="/php-patron-pnj/public/css/cards.css">
    <link rel="stylesheet" href="/php-patron-pnj/public/css/classes.css">
</head>
<body>
    <div class="classes-container">
        <div class="character-header">
            <div class="header-content">
                <h1>Classes de Personnages</h1>
                <div class="header-actions">
                    <a href="/php-patron-pnj/public/views/classe/create.php" class="button primary">Créer une classe</a>
                </div>
            </div>
        </div>

        <div class="classes-grid">
            <?php foreach ($_SESSION['classes'] as $classe): ?>
                <div class="class-card" data-class-id="<?= htmlspecialchars($classe['id']) ?>">
                    <div class="class-name"><?= htmlspecialchars($classe['nom']) ?></div>
                    <div class="class-description"><?= htmlspecialchars($classe['description']) ?></div>
                    <div class="class-actions">
                        <button class="button view-details" data-class="<?= htmlspecialchars($classe['id']) ?>">
                            Voir les détails
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Modal pour les détails de classe -->
        <div id="classModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div id="classDetails"></div>
            </div>
        </div>

        <div class="actions">
            <a href="/php-patron-pnj/public/views/personnage/create.php" class="button">Créer un personnage</a>
            <a href="/php-patron-pnj/public/views/personnage/index.php" class="button">Voir tous les personnages</a>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('classModal');
        const closeBtn = document.querySelector('.close');
        const classDetails = document.getElementById('classDetails');

        document.querySelectorAll('.view-details').forEach(button => {
            button.addEventListener('click', async function() {
                const classeId = this.dataset.class;
                const response = await fetch(`/actions/classe/get.php?id=${classeId}`);
                const details = await response.json();
                
                let html = `
                    <h2>${details.nom}</h2>
                    <p>${details.description}</p>
                    <div class="stats-section">
                        <h3>Statistiques de base</h3>
                        <div class="stats-grid">
                `;
                
                for (const [stat, value] of Object.entries(details.stats_base)) {
                    html += `
                        <div class="stat-block">
                            <div class="stat-label">${stat.charAt(0).toUpperCase() + stat.slice(1)}</div>
                            <div class="stat-value">${value}</div>
                        </div>
                    `;
                }
                
                html += `
                    </div>
                    <div class="details-section">
                        <h3>Compétences</h3>
                        <ul>
                            ${details.competences.map(comp => `<li>${comp}</li>`).join('')}
                        </ul>
                        
                        <h3>Équipement initial</h3>
                        <ul>
                            ${details.equipement_initial.map(equip => `<li>${equip}</li>`).join('')}
                        </ul>
                    </div>
                `;
                
                classDetails.innerHTML = html;
                modal.style.display = 'block';
            });
        });

        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
    </script>
</body>
</html> 