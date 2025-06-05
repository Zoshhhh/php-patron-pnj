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
    <link rel="stylesheet" href="../../css/style.css">
    <style>
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .items-table th {
            background-color: #f5f5f5;
        }
        .items-table tr:nth-child(even) {
            background-color: #fafafa;
        }
        .type-combat { color: #ff4444; }
        .type-consommable { color: #44ff44; }
        .type-equipement { color: #4444ff; }
        .actions {
            display: flex;
            gap: 10px;
        }
        .filter-section {
            margin-bottom: 20px;
            display: flex;
            gap: 20px;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Liste des Items</h1>

        <div class="filter-section">
            <select id="typeFilter">
                <option value="">Tous les types</option>
                <?php foreach (ITEM_TYPES as $type => $config): ?>
                    <option value="<?= $type ?>"><?= $config['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" id="searchInput" placeholder="Rechercher un item...">
        </div>

        <?php if (empty($_SESSION['items'])): ?>
            <div class="empty-state">
                Aucun item n'a été créé pour le moment.
                <br><br>
                <a href="/views/item/create.php" class="button primary">Créer un item</a>
            </div>
        <?php else: ?>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Valeur</th>
                        <th>Propriétés spécifiques</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['items'] as $index => $item): ?>
                        <tr data-type="<?= htmlspecialchars($item->getType()) ?>">
                            <td><?= htmlspecialchars($item->getNom()) ?></td>
                            <td class="type-<?= htmlspecialchars($item->getType()) ?>">
                                <?= ITEM_TYPES[$item->getType()]['name'] ?? $item->getType() ?>
                            </td>
                            <td><?= htmlspecialchars($item->getDescription()) ?></td>
                            <td><?= $item->getValeur() ?> pièces</td>
                            <td>
                                <?php 
                                $effets = $item->getEffets();
                                foreach ($effets as $nom => $valeur):
                                    if (is_bool($valeur)):
                                        echo htmlspecialchars($nom) . ': ' . ($valeur ? 'Oui' : 'Non') . '<br>';
                                    else:
                                        echo htmlspecialchars($nom) . ': ' . htmlspecialchars($valeur) . '<br>';
                                    endif;
                                endforeach;
                                ?>
                            </td>
                            <td class="actions">
                                <a href="/actions/item/delete.php?id=<?= $index ?>" 
                                   class="button danger"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet item ?')">
                                    Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="actions" style="margin-top: 20px;">
                <a href="/views/item/create.php" class="button primary">Créer un nouvel item</a>
                <a href="/views/personnage/index.php" class="button">Retour</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
    document.getElementById('typeFilter').addEventListener('change', filterItems);
    document.getElementById('searchInput').addEventListener('input', filterItems);

    function filterItems() {
        const typeFilter = document.getElementById('typeFilter').value;
        const searchFilter = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('.items-table tbody tr');

        rows.forEach(row => {
            const type = row.dataset.type;
            const text = row.textContent.toLowerCase();
            const matchesType = !typeFilter || type === typeFilter;
            const matchesSearch = !searchFilter || text.includes(searchFilter);

            row.style.display = matchesType && matchesSearch ? '' : 'none';
        });
    }
    </script>
</body>
</html> 