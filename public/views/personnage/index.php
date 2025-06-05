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
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/cards.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        .target-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .target-card {
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .target-card:hover {
            background-color: #f5f5f5;
            transform: translateY(-2px);
        }

        .target-card.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
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
                <span class="close">&times;</span>
                <h2>Choisir une cible</h2>
                <div class="target-list">
                    <?php foreach ($_SESSION['personnages'] as $index => $cible): ?>
                        <div class="target-card" data-target-id="<?= $index ?>" data-category="<?= htmlspecialchars($cible['categorie'] ?? 'personnage') ?>">
                            <h3><?= htmlspecialchars($cible['nom']) ?></h3>
                            <p>Classe: <?= ucfirst($cible['classe']) ?></p>
                            <p>Catégorie: <?= ucfirst($cible['categorie'] ?? 'personnage') ?></p>
                            <p>PV: <?= $cible['stats']['pointsDeVie'] ?? 10 ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="actions">
            <a href="/views/personnage/create.php" class="button primary">Créer un nouveau personnage</a>
            <a href="/views/item/create.php" class="button primary">Créer un nouvel item</a>
            <a href="/views/item/show.php" class="button">Voir les items</a>
            <a href="/views/classe/create.php" class="button">Créer une classe</a>
            <a href="/views/classe/index.php" class="button">Voir les classes</a>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('attackModal');
        const closeBtn = modal.querySelector('.close');
        let currentAttacker = null;

        // Gérer l'affichage/masquage des menus déroulants
        document.querySelectorAll('.action-button').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const dropdown = this.nextElementSibling;
                document.querySelectorAll('.dropdown-content').forEach(d => {
                    if (d !== dropdown) d.classList.remove('show');
                });
                dropdown.classList.toggle('show');
            });
        });

        // Gérer les clics sur les actions
        document.querySelectorAll('.action-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const action = this.dataset.action;
                const characterId = this.dataset.character;
                
                if (action === 'attaquer') {
                    currentAttacker = characterId;
                    showAttackModal(characterId);
                }
                
                this.closest('.dropdown-content').classList.remove('show');
            });
        });

        // Fermer la modale
        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Gérer la sélection des cibles
        document.querySelectorAll('.target-card').forEach(card => {
            card.addEventListener('click', function() {
                const targetId = this.dataset.targetId;
                if (targetId === currentAttacker) return; // Ne peut pas s'attaquer soi-même
                
                // Envoyer la requête d'attaque
                fetch('/actions/personnage/attack.php', {
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
                        console.log('Attaquant:', data.attacker);
                        console.log('Cible:', data.target);
                        modal.style.display = 'none';
                    } else {
                        alert(data.error || 'Une erreur est survenue');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Une erreur est survenue');
                });
            });
        });

        function showAttackModal(attackerId) {
            // Désactiver les cibles non valides
            document.querySelectorAll('.target-card').forEach(card => {
                const targetId = card.dataset.targetId;
                const targetCategory = card.dataset.category;
                
                if (targetId === attackerId) {
                    card.classList.add('disabled');
                } else if (targetCategory === 'allie' && document.querySelector(`[data-target-id="${attackerId}"]`).dataset.category === 'allie') {
                    card.classList.add('disabled');
                } else {
                    card.classList.remove('disabled');
                }
            });
            
            modal.style.display = 'block';
        }

        // Fermer les dropdowns quand on clique ailleurs
        document.addEventListener('click', function(e) {
            if (!e.target.matches('.action-button')) {
                document.querySelectorAll('.dropdown-content').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });
    });
    </script>
    <script src="../../js/main.js"></script>
</body>
</html> 