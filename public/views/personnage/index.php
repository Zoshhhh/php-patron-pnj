<?php
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/vendor/autoload.php';
session_start();

use App\Factory\PersonnageFactory;

if (!isset($_SESSION['personnages'])) {
    $_SESSION['personnages'] = [];
}

if (isset($_GET['supprimer'])) {
    $index = $_GET['supprimer'];
    if (isset($_SESSION['personnages'][$index])) {
        unset($_SESSION['personnages'][$index]);
        $_SESSION['personnages'] = array_values($_SESSION['personnages']);
    }
    header('Location: /views/personnage/index.php');
    exit;
}

function peutAttaquer($attaquant, $cible) {
    if ($attaquant === $cible) return false;
    
    if (($attaquant['categorie'] ?? 'personnage') === 'allie' && 
        ($cible['categorie'] ?? 'personnage') === 'allie') return false;
    
    if (($attaquant['categorie'] ?? 'personnage') === 'pnj') return false;
    
    return true;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Personnages</title>
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/cards.css">
    <link rel="stylesheet" href="../../css/modal.css">
    <link rel="icon" type="image/png" href="../../images/favicon.png">
</head>
<body>
    <div class="characters-list">
        <div class="character-header">
            <div class="header-content">
                <h1>Mes Personnages</h1>
                <div class="search-filters">
                    <input type="text" id="searchInput" placeholder="Rechercher...">
                    <select id="categoryFilter">
                        <option value="all">Toutes les catégories</option>
                        <option value="personnage">Personnages</option>
                        <option value="allie">Alliés</option>
                        <option value="ennemi">Ennemis</option>
                        <option value="pnj">PNJ</option>
                    </select>
                    <select id="sortBy">
                        <option value="name">Trier par nom</option>
                        <option value="category">Trier par catégorie</option>
                        <option value="level">Trier par niveau</option>
                    </select>
                </div>
            </div>
        </div>

        <?php if (empty($_SESSION['personnages'])): ?>
            <div class="empty-state">
                Aucun personnage créé pour le moment.
            </div>
        <?php else: ?>
            <div class="characters-grid">
                <?php foreach ($_SESSION['personnages'] as $index => $perso): ?>
                    <?php $categorie = $perso['categorie'] ?? 'personnage'; ?>
                    <div class="character-card <?= htmlspecialchars($categorie) ?>">
                        <div class="character-name"><?= htmlspecialchars($perso['nom']) ?></div>
                        <div class="character-category <?= htmlspecialchars($categorie) ?>">
                            <?= ucfirst($categorie) ?>
                        </div>
                        <div class="character-info">
                            <?= ucfirst($perso['classe']) ?> Niveau 1
                        </div>
                        <div class="character-stats">
                            <div class="stat">
                                <span>FOR</span>
                                <?= $perso['stats']['force'] ?? 10 ?>
                            </div>
                            <div class="stat">
                                <span>DEX</span>
                                <?= $perso['stats']['dexterite'] ?? 10 ?>
                            </div>
                            <div class="stat">
                                <span>PV</span>
                                <?= $perso['stats']['pointsDeVie'] ?? 10 ?>
                            </div>
                        </div>
                        <div class="character-actions">
                            <div class="action-dropdown">
                                <button class="button action-button">Actions ▼</button>
                                <div class="dropdown-content">
                                    <a href="#" class="action-item" data-action="attaquer" data-character="<?= $index ?>">Attaquer</a>
                                    <a href="#" class="action-item" data-action="defendre" data-character="<?= $index ?>">Défendre</a>
                                    <a href="#" class="action-item" data-action="combat" data-character="<?= $index ?>">Combat</a>
                                </div>
                            </div>
                            <a href="./show.php?id=<?= $index ?>" class="button">Voir</a>
                            <a href="../../actions/personnage/delete.php?id=<?= $index ?>" class="button danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce personnage ?')">Supprimer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Modal pour l'attaque -->
        <div id="attackModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Choisir une cible</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="target-list">
                    <?php foreach ($_SESSION['personnages'] as $index => $cible): ?>
                        <?php 
                        $isDisabled = !peutAttaquer($perso ?? [], $cible);
                        $categorie = $cible['categorie'] ?? 'personnage';
                        ?>
                        <div class="target-card <?= $isDisabled ? 'disabled' : '' ?>" 
                             data-target="<?= $index ?>" 
                             onclick="<?= $isDisabled ? '' : "selectTarget($index)" ?>">
                            <div class="target-category <?= htmlspecialchars($categorie) ?>">
                                <?= ucfirst($categorie) ?>
                            </div>
                            <h3><?= htmlspecialchars($cible['nom']) ?></h3>
                            <div class="target-info">
                                <div class="target-stat">
                                    <span class="target-stat-label">PV:</span>
                                    <span class="target-stat-value"><?= $cible['stats']['pointsDeVie'] ?? 10 ?></span>
                                </div>
                                <div class="target-stat">
                                    <span class="target-stat-label">CA:</span>
                                    <span class="target-stat-value"><?= $cible['stats']['classeArmure'] ?? 10 ?></span>
                                </div>
                                <div class="target-stat">
                                    <span class="target-stat-label">FOR:</span>
                                    <span class="target-stat-value"><?= $cible['stats']['force'] ?? 10 ?></span>
                                </div>
                                <div class="target-stat">
                                    <span class="target-stat-label">DEX:</span>
                                    <span class="target-stat-value"><?= $cible['stats']['dexterite'] ?? 10 ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
    let currentAttacker = null;

    document.querySelectorAll('[data-action="attaquer"]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            currentAttacker = this.dataset.character;
            document.getElementById('attackModal').style.display = 'block';
        });
    });

    document.querySelector('.close').addEventListener('click', function() {
        document.getElementById('attackModal').style.display = 'none';
    });

    window.onclick = function(event) {
        let modal = document.getElementById('attackModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    function selectTarget(targetId) {
        if (currentAttacker === null) return;
        
        fetch('/actions/combat/attack.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                attacker: currentAttacker,
                target: targetId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.error || 'Une erreur est survenue');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue');
        });

        document.getElementById('attackModal').style.display = 'none';
    }

    // Gestion des dropdowns
    document.querySelectorAll('.action-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = this.nextElementSibling;
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });
    });

    document.addEventListener('click', function() {
        document.querySelectorAll('.dropdown-content').forEach(dropdown => {
            dropdown.style.display = 'none';
        });
    });
    </script>
</body>
</html> 