<?php
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/vendor/autoload.php';

use App\Model\Item\Item;

session_start();

if (!isset($_SESSION['items'])) {
    $_SESSION['items'] = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Items</title>
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/item-list.css">
</head>
<body>
    <div class="items-container">
        <nav class="back-nav">
            <a href="/views/personnage/index.php" class="back-button">
                <span class="icon">←</span>
                <span>Retour</span>
            </a>
        </nav>

        <div class="items-header">
            <h1>Liste des Items</h1>
            <div class="filter-section">
                <select id="typeFilter" class="filter-select">
                    <option value="">Tous les types</option>
                    <?php foreach (ITEM_TYPES as $type => $config): ?>
                        <option value="<?= $type ?>"><?= $config['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" id="searchInput" class="filter-input" placeholder="Rechercher un item...">
            </div>
        </div>

        <?php if (empty($_SESSION['items'])): ?>
            <div class="empty-state">
                <p>Aucun item n'a été créé pour le moment.</p>
                <a href="/views/item/create.php" class="button primary">Créer un item</a>
            </div>
        <?php else: ?>
            <div class="items-grid">
                <?php foreach ($_SESSION['items'] as $index => $item): ?>
                    <div class="item-card" data-type="<?= htmlspecialchars($item->getType()) ?>">
                        <div class="item-header">
                            <h2 class="item-name"><?= htmlspecialchars($item->getNom()) ?></h2>
                            <span class="item-type <?= htmlspecialchars($item->getType()) ?>">
                                <?= ITEM_TYPES[$item->getType()]['name'] ?? $item->getType() ?>
                            </span>
                        </div>
                        
                        <p class="item-description"><?= htmlspecialchars($item->getDescription()) ?></p>
                        
                        <div class="item-properties">
                            <?php 
                            $effets = $item->getEffets();
                            foreach ($effets as $nom => $valeur):
                                if (is_bool($valeur)):
                                    echo '<span class="item-property">' . htmlspecialchars($nom) . ': ' . ($valeur ? 'Oui' : 'Non') . '</span>';
                                else:
                                    echo '<span class="item-property">' . htmlspecialchars($nom) . ': ' . htmlspecialchars($valeur) . '</span>';
                                endif;
                            endforeach;
                            ?>
                        </div>
                        
                        <div class="item-value">
                            <span class="value-icon">💰</span>
                            <?= $item->getValeur() ?> pièces
                        </div>
                        
                        <div class="item-actions">
                            <a href="/actions/item/delete.php?id=<?= $index ?>" 
                               class="button danger"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet item ?')">
                                Supprimer
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="actions">
                <a href="/views/item/create.php" class="button primary">Créer un nouvel item</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
    document.getElementById('typeFilter').addEventListener('change', filterItems);
    document.getElementById('searchInput').addEventListener('input', filterItems);

    function filterItems() {
        const typeFilter = document.getElementById('typeFilter').value;
        const searchFilter = document.getElementById('searchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.item-card');

        cards.forEach(card => {
            const type = card.dataset.type;
            const text = card.textContent.toLowerCase();
            const matchesType = !typeFilter || type === typeFilter;
            const matchesSearch = !searchFilter || text.includes(searchFilter);

            card.style.display = matchesType && matchesSearch ? '' : 'none';
        });
    }
    </script>
</body>
</html> 