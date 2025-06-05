<?php
session_start();
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/vendor/autoload.php';

use App\Factory\PersonnageFactory;
use App\Factory\ItemFactory;
use App\Model\Item\CombatItem;

$index = isset($_GET['id']) ? (int)$_GET['id'] : null;

if ($index === null || !isset($_SESSION['personnages'][$index])) {
    header('Location: /views/personnage/index.php');
    exit;
}

$persoData = $_SESSION['personnages'][$index];
$fabrique = new PersonnageFactory();

$personnage = match($persoData['classe']) {
    'guerrier' => $fabrique->creerGuerrier($persoData['nom'], $persoData['stats']),
    'archer' => $fabrique->creerArcher($persoData['nom'], $persoData['stats']),
    default => $fabrique->creerGuerrier($persoData['nom'], $persoData['stats']),
};

$itemsDisponibles = [];
if (isset($_SESSION['items']) && is_array($_SESSION['items'])) {
    foreach ($_SESSION['items'] as $id => $itemData) {
        if (is_array($itemData)) {
            $itemsDisponibles[$id] = $itemData;
        } elseif (is_object($itemData)) {
            $itemArray = [];
            foreach (get_object_vars($itemData) as $key => $value) {
                $itemArray[$key] = $value;
            }
            $itemsDisponibles[$id] = $itemArray;
        }
    }
}

$inventaire = [];
if (isset($persoData['inventaire'])) {
    foreach ($persoData['inventaire'] as $item) {
        if (is_array($item)) {
            $inventaire[] = $item;
        } elseif (is_object($item)) {
            $itemArray = [];
            foreach (get_object_vars($item) as $key => $value) {
                $itemArray[$key] = $value;
            }
            $inventaire[] = $itemArray;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($personnage->getNom()) ?> - Fiche de personnage</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/details.css">
    <style>
        .inventory-section {
            margin-top: 2rem;
            padding: 1rem;
            background-color: #F5E6D3;
            border-radius: 8px;
        }

        .inventory-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .inventory-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .inventory-item {
            background: white;
            padding: 1rem;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .item-stats {
            font-size: 0.9em;
            color: #666;
        }

        .add-item-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .add-item-button:hover {
            background-color: #45a049;
        }

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
            background-color: #F5E6D3;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
        }

        .close {
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
            max-height: 400px;
            overflow-y: auto;
        }

        .item-card {
            background: white;
            padding: 1rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .item-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
    <script>
        const openItemModal = () => {
            document.getElementById('itemModal').style.display = 'block';
        };

        const closeItemModal = () => {
            document.getElementById('itemModal').style.display = 'none';
        };

        const addItemToInventory = (personnageId, itemId) => {
            fetch('/actions/personnage/add_item.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    personnageId: personnageId,
                    itemId: itemId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.error || 'Une erreur est survenue');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue');
            });
        };

        const confirmDelete = (id) => {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce personnage ? Cette action est irréversible.')) {
                window.location.href = `/actions/personnage/delete.php?id=${id}`;
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.ability-card, .combat-stat, .vital-stat').forEach(card => {
                card.addEventListener('mouseover', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                card.addEventListener('mouseout', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            window.onclick = function(event) {
                const modal = document.getElementById('itemModal');
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            };
        });
    </script>
</head>
<body>
    <div class="character-sheet">
        <nav class="character-nav">
            <div class="nav-group">
                <a href="/views/personnage/index.php" class="nav-link">← Retour</a>
                <span class="nav-separator">/</span>
                <a href="/views/personnage/create.php" class="nav-link">Nouveau</a>
            </div>
            <div class="nav-category <?= htmlspecialchars($persoData['categorie'] ?? 'personnage') ?>">
                <?= ucfirst($persoData['categorie'] ?? 'personnage') ?>
            </div>
        </nav>

        <header class="character-header">
            <div class="header-main">
                <div class="header-title">
                    <h1><?= htmlspecialchars($personnage->getNom()) ?></h1>
                    <div class="character-meta">
                        <span class="class-badge"><?= ucfirst($persoData['classe']) ?></span>
                        <span class="level-badge">Niveau 1</span>
            </div>
            </div>
                <div class="quick-actions">
                    <button type="button" class="action-button edit-button" onclick="window.location.href='/views/personnage/edit.php?id=<?= $index ?>'">
                        ✏️ Modifier
                    </button>
                    <button type="button" class="action-button delete-button" onclick="confirmDelete(<?= $index ?>)">
                        🗑️ Supprimer
                    </button>
            </div>
        </div>

            <div class="vital-stats">
                <div class="vital-stat" data-tooltip="Points de vie actuels">
                    <span class="stat-icon">❤️</span>
                    <span class="stat-value"><?= $personnage->getPointsDeVie() ?></span>
                    <span class="stat-label">PV</span>
                </div>
                <div class="vital-stat" data-tooltip="Classe d'armure - Plus c'est élevé, plus le personnage est difficile à toucher">
                    <span class="stat-icon">🛡️</span>
                    <span class="stat-value"><?= $personnage->getClasseArmure() ?></span>
                    <span class="stat-label">CA</span>
                </div>
                <div class="vital-stat" data-tooltip="Bonus d'initiative - Détermine l'ordre d'action en combat">
                    <span class="stat-icon">⚡</span>
                    <span class="stat-value">+<?= $personnage->getModificateur($personnage->getDexterite()) ?></span>
                    <span class="stat-label">Initiative</span>
                </div>
            </div>
        </header>

        <div class="character-content">
            <section class="abilities-section">
                <h2>Caractéristiques</h2>
                <div class="abilities-grid">
                    <?php
                    $abilities = [
                        'Force' => $personnage->getForce(),
                        'Dextérité' => $personnage->getDexterite(),
                        'Constitution' => $personnage->getConstitution(),
                        'Intelligence' => $personnage->getIntelligence(),
                        'Sagesse' => $personnage->getSagesse(),
                        'Charisme' => $personnage->getCharisme()
                    ];
                    $abilityDescriptions = [
                        'Force' => 'Force physique, capacité à porter et frapper',
                        'Dextérité' => 'Agilité, réflexes et équilibre',
                        'Constitution' => 'Endurance, résistance et vitalité',
                        'Intelligence' => 'Mémoire, raisonnement et apprentissage',
                        'Sagesse' => 'Perception, intuition et volonté',
                        'Charisme' => 'Force de personnalité et leadership'
                    ];
                    foreach ($abilities as $name => $score): ?>
                        <div class="ability-card" data-tooltip="<?= $abilityDescriptions[$name] ?>">
                            <div class="ability-name"><?= $name ?></div>
                            <div class="ability-score"><?= $score ?></div>
                            <div class="ability-modifier">+<?= $personnage->getModificateur($score) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="combat-section">
                <h2>Combat</h2>
                <div class="combat-grid">
                    <div class="combat-stat" data-tooltip="Distance de déplacement par tour">
                        <span class="combat-icon">🏃</span>
                        <div class="combat-value"><?= $personnage->getVitesse() ?></div>
                        <div class="combat-label">Vitesse</div>
                    </div>
                    <div class="combat-stat" data-tooltip="Bonus aux attaques de corps à corps">
                        <span class="combat-icon">⚔️</span>
                        <div class="combat-value">+<?= $personnage->getModificateur($personnage->getForce()) ?></div>
                        <div class="combat-label">Attaque</div>
                    </div>
                    <div class="combat-stat" data-tooltip="Bonus aux attaques à distance">
                        <span class="combat-icon">🎯</span>
                        <div class="combat-value">+<?= $personnage->getModificateur($personnage->getDexterite()) ?></div>
                        <div class="combat-label">Précision</div>
            </div>
        </div>
            </section>

            <section class="inventory-section">
                <div class="inventory-header">
                    <h2>Inventaire</h2>
                    <button type="button" class="add-item-button" onclick="openItemModal()">Ajouter un item</button>
                </div>
                <div class="inventory-grid">
                    <?php if (!empty($inventaire)): ?>
                        <?php foreach ($inventaire as $item): ?>
                            <div class="inventory-item">
                                <div class="item-name"><?= htmlspecialchars($item['name'] ?? $item['nom']) ?></div>
                                <div class="item-stats">
                                    <?php if (isset($item['type']) && $item['type'] === 'combat'): ?>
                                        <div>Type: Combat</div>
                                        <div>Dégâts: <?= $item['degats'] ?? $item['damage'] ?? 0 ?></div>
                                        <div>Durabilité: <?= $item['durabilite'] ?? $item['durability'] ?? 100 ?></div>
                                    <?php endif; ?>
                                    <div>Valeur: <?= $item['valeur'] ?? $item['value'] ?? 0 ?> pièces</div>
                                    <?php if (isset($item['description']) && !empty($item['description'])): ?>
                                        <div class="item-description"><?= htmlspecialchars($item['description']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Aucun item dans l'inventaire</p>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>

    <!-- Modal pour ajouter un item -->
    <div id="itemModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeItemModal()">&times;</span>
            <h2>Ajouter un item</h2>
            <div class="items-grid">
                <?php if (!empty($itemsDisponibles)): ?>
                    <?php foreach ($itemsDisponibles as $itemId => $item): ?>
                        <div class="item-card" onclick="addItemToInventory(<?= $index ?>, <?= $itemId ?>)">
                            <div class="item-name"><?= htmlspecialchars($item['name'] ?? $item['nom']) ?></div>
                            <div class="item-stats">
                                <?php if (isset($item['type']) && $item['type'] === 'combat'): ?>
                                    <div>Type: Combat</div>
                                    <div>Dégâts: <?= $item['degats'] ?? $item['damage'] ?? 0 ?></div>
                                    <div>Durabilité: <?= $item['durabilite'] ?? $item['durability'] ?? 100 ?></div>
                                <?php endif; ?>
                                <div>Valeur: <?= $item['valeur'] ?? $item['value'] ?? 0 ?> pièces</div>
                                <?php if (isset($item['description']) && !empty($item['description'])): ?>
                                    <div class="item-description"><?= htmlspecialchars($item['description']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun item disponible</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html> 