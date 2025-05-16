<?php
session_start();
require_once __DIR__ . '/../../config.php';

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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Personnages</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/cards.css">
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
                            <a href="/views/personnage/show.php?id=<?= $index ?>" class="button">Voir</a>
                            <a href="/actions/personnage/delete.php?id=<?= $index ?>" class="button danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce personnage ?')">Supprimer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

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
        // Gérer l'affichage/masquage des menus déroulants
        document.querySelectorAll('.action-button').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const dropdown = this.nextElementSibling;
                // Fermer tous les autres dropdowns
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
                console.log(`Action ${action} pour le personnage ${characterId}`);
                this.closest('.dropdown-content').classList.remove('show');
            });
        });

        document.addEventListener('click', function(e) {
            if (!e.target.matches('.action-button')) {
                document.querySelectorAll('.dropdown-content').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });
    });
    </script>
    <script src="js/main.js"></script>
</body>
</html> 