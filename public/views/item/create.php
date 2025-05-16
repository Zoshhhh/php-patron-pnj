<?php
require_once __DIR__ . '/../../config.php';
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un nouvel item</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Créer un nouvel item</h1>
        
        <form id="itemForm" action="/actions/item/create.php" method="POST">
            <div class="form-group">
                <label for="itemType">Type d'item</label>
                <select name="itemType" id="itemType" required>
                    <option value="">Sélectionnez un type</option>
                    <?php foreach (ITEM_TYPES as $type => $config): ?>
                        <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($config['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="value">Valeur</label>
                <input type="number" id="value" name="value" required min="0">
            </div>

            <!-- Champs dynamiques pour Combat -->
            <div id="combatFields" class="dynamic-fields" style="display: none;">
                <div class="form-group">
                    <label for="damage">Dégâts</label>
                    <input type="number" id="damage" name="damage" min="0" max="<?= MAX_DAMAGE ?>">
                </div>
                <div class="form-group">
                    <label for="durability">Durabilité</label>
                    <input type="number" id="durability" name="durability" min="0" max="<?= MAX_DURABILITY ?>">
                </div>
            </div>

            <div id="consommableFields" class="dynamic-fields" style="display: none;">
                <div class="form-group">
                    <label for="healAmount">Montant de soin</label>
                    <input type="number" id="healAmount" name="healAmount" min="0" max="<?= MAX_HEAL_AMOUNT ?>">
                </div>
                <div class="form-group">
                    <label for="isStackable">Empilable</label>
                    <input type="checkbox" id="isStackable" name="isStackable" checked>
                </div>
            </div>

            <div id="equipementFields" class="dynamic-fields" style="display: none;">
                <div class="form-group">
                    <label for="defense">Défense</label>
                    <input type="number" id="defense" name="defense" min="0" max="<?= MAX_DEFENSE ?>">
                </div>
                <div class="form-group">
                    <label for="slot">Emplacement</label>
                    <select id="slot" name="slot">
                        <?php foreach (EQUIPMENT_SLOTS as $value => $label): ?>
                            <option value="<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="button primary">Créer l'item</button>
                <a href="/views/personnage/index.php" class="button">Retour</a>
            </div>
        </form>
    </div>

    <script>
    document.getElementById('itemType').addEventListener('change', function() {
        document.querySelectorAll('.dynamic-fields').forEach(fields => {
            fields.style.display = 'none';
        });

        const selectedType = this.value;
        if (selectedType) {
            const fieldsElement = document.getElementById(selectedType + 'Fields');
            if (fieldsElement) {
                fieldsElement.style.display = 'block';
            }
        }
    });

    const defaultValues = <?= json_encode(DEFAULT_ITEM_VALUES) ?>;
    
    document.getElementById('itemForm').addEventListener('reset', function() {
        setTimeout(() => {
            Object.keys(defaultValues).forEach(field => {
                const element = document.getElementById(field);
                if (element) {
                    if (element.type === 'checkbox') {
                        element.checked = defaultValues[field];
                    } else {
                        element.value = defaultValues[field];
                    }
                }
            });
        }, 0);
    });
    </script>
</body>
</html> 