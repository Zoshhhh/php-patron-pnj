<?php
require_once __DIR__ . '/../../config.php';
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title style="color: #000;">Créer un nouvel item</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/item-form.css">
</head>
<body>
    <div class="item-creator">
        <nav class="creator-nav">
            <a href="/views/personnage/index.php" class="nav-link">← Retour</a>
            <h1>Créer un nouvel item</h1>
        </nav>

        <form id="itemForm" action="/actions/item/create.php" method="POST" class="item-form">
            <div class="form-section main-info">
                <div class="form-group type-selector">
                    <label for="itemType">Type d'item</label>
                    <div class="type-options">
                        <?php foreach (ITEM_TYPES as $type => $config): ?>
                            <label class="type-option">
                                <input type="radio" name="itemType" value="<?= htmlspecialchars($type) ?>" required <?= $type === 'combat' ? 'checked' : '' ?>>
                                <span class="type-card">
                                    <span class="type-icon"><?= $config['icon'] ?? '📦' ?></span>
                                    <span class="type-name"><?= htmlspecialchars($config['name']) ?></span>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-row" style="gap: 4rem;">
                    <div class="form-group" style="width: 70%;">
                        <label for="name">Nom</label>
                        <input type="text" id="name" name="name" required 
                               placeholder="Ex: Épée longue, Potion..."
                               class="form-input">
                    </div>
                    <div class="form-group" style="width: 100px;">
                        <label for="value">Valeur</label>
                        <div class="input-with-icon">
                            <input type="number" id="value" name="value" required min="0"
                                   class="form-input" placeholder="0">
                            <span class="input-icon">💰</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required
                              placeholder="Décrivez les caractéristiques de l'item..."
                              class="form-input"></textarea>
                </div>
            </div>

            <!-- Champs spécifiques pour les items de Combat -->
            <div id="combatFields" class="form-section specific-fields" style="display: none;">
                <h2>Propriétés de combat</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="damage">Dégâts</label>
                        <div class="input-with-icon">
                            <input type="number" id="damage" name="damage" 
                                   min="0" max="<?= MAX_DAMAGE ?>"
                                   class="form-input" placeholder="0">
                            <span class="input-icon">⚔️</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="durability">Durabilité</label>
                        <div class="input-with-icon">
                            <input type="number" id="durability" name="durability"
                                   min="0" max="<?= MAX_DURABILITY ?>"
                                   class="form-input" placeholder="100">
                            <span class="input-icon">🛡️</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Champs spécifiques pour les Consommables -->
            <div id="consommableFields" class="form-section specific-fields" style="display: none;">
                <h2>Propriétés de consommable</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="healAmount">Montant de soin</label>
                        <div class="input-with-icon">
                            <input type="number" id="healAmount" name="healAmount"
                                   min="0" max="<?= MAX_HEAL_AMOUNT ?>"
                                   class="form-input" placeholder="0">
                            <span class="input-icon">❤️</span>
                        </div>
                    </div>
                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="isStackable" name="isStackable" checked>
                            <span class="checkbox-text">Empilable</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Champs spécifiques pour l'Équipement -->
            <div id="equipementFields" class="form-section specific-fields" style="display: none;">
                <h2>Propriétés d'équipement</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="defense">Défense</label>
                        <div class="input-with-icon">
                            <input type="number" id="defense" name="defense"
                                   min="0" max="<?= MAX_DEFENSE ?>"
                                   class="form-input" placeholder="0">
                            <span class="input-icon">🛡️</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="slot">Emplacement</label>
                        <select id="slot" name="slot" class="form-input">
                            <?php foreach (EQUIPMENT_SLOTS as $value => $label): ?>
                                <option value="<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="button primary">
                    <span class="button-icon">✨</span>
                    Créer l'item
                </button>
                <button type="reset" class="button secondary">
                    <span class="button-icon">🔄</span>
                    Réinitialiser
                </button>
            </div>
        </form>

        <div class="item-preview">
            <div class="preview-card">
                <div class="preview-header">
                    <h3 id="previewName">Nom de l'item</h3>
                    <span id="previewType" class="preview-type">Type</span>
                </div>
                <p id="previewDescription" class="preview-description">Description de l'item...</p>
                <div id="previewStats" class="preview-stats"></div>
                <div class="preview-footer">
                    <span id="previewValue" class="preview-value">0 💰</span>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Gestion de l'affichage des champs spécifiques
    document.querySelectorAll('input[name="itemType"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Désactiver tous les champs spécifiques
            document.querySelectorAll('.specific-fields').forEach(fields => {
                fields.classList.remove('active');
                fields.querySelectorAll('input, select').forEach(input => {
                    input.disabled = true;
                    input.required = false;
                });
            });

            if (this.value) {
                const fieldsElement = document.getElementById(this.value + 'Fields');
                if (fieldsElement) {
                    fieldsElement.classList.add('active');
                    fieldsElement.querySelectorAll('input, select').forEach(input => {
                        input.disabled = false;
                        input.required = true;
                    });
                }
            }
            
            previewType.textContent = this.closest('.type-option').querySelector('.type-name').textContent;
            updatePreviewStats();
        });
    });

    // Mise à jour de la prévisualisation en temps réel
    const previewName = document.getElementById('previewName');
    const previewDescription = document.getElementById('previewDescription');
    const previewType = document.getElementById('previewType');
    const previewValue = document.getElementById('previewValue');
    const previewStats = document.getElementById('previewStats');

    document.getElementById('name').addEventListener('input', e => {
        previewName.textContent = e.target.value || 'Nom de l\'item';
    });

    document.getElementById('description').addEventListener('input', e => {
        previewDescription.textContent = e.target.value || 'Description de l\'item...';
    });

    document.getElementById('value').addEventListener('input', e => {
        previewValue.textContent = `${e.target.value || 0} 💰`;
    });

    // Mise à jour des stats spécifiques
    function updatePreviewStats() {
        const stats = [];
        const type = document.querySelector('input[name="itemType"]:checked')?.value;

        if (type === 'combat') {
            const damage = document.getElementById('damage').value;
            const durability = document.getElementById('durability').value;
            if (damage) stats.push(`⚔️ ${damage} dégâts`);
            if (durability) stats.push(`🛡️ ${durability} durabilité`);
        } else if (type === 'consommable') {
            const heal = document.getElementById('healAmount').value;
            const stackable = document.getElementById('isStackable').checked;
            if (heal) stats.push(`❤️ +${heal} PV`);
            if (stackable) stats.push(`📦 Empilable`);
        } else if (type === 'equipement') {
            const defense = document.getElementById('defense').value;
            const slot = document.getElementById('slot');
            if (defense) stats.push(`🛡️ ${defense} défense`);
            if (slot.value) stats.push(`📍 ${slot.options[slot.selectedIndex].text}`);
        }

        previewStats.innerHTML = stats.map(stat => `<div class="preview-stat">${stat}</div>`).join('');
    }

    // Écouter les changements sur tous les champs pour mettre à jour la prévisualisation
    document.querySelectorAll('input, select, textarea').forEach(input => {
        input.addEventListener('input', updatePreviewStats);
        input.addEventListener('change', updatePreviewStats);
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
            
            // Réinitialiser la prévisualisation
            previewName.textContent = 'Nom de l\'item';
            previewDescription.textContent = 'Description de l\'item...';
            previewValue.textContent = '0 💰';
            previewStats.innerHTML = '';
            previewType.textContent = 'Type';
        }, 0);
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Déclencher l'événement change sur le type par défaut
        const defaultType = document.querySelector('input[name="itemType"]:checked');
        if (defaultType) {
            defaultType.dispatchEvent(new Event('change'));
            previewType.textContent = defaultType.closest('.type-option').querySelector('.type-name').textContent;
        }
    });

    // Amélioration de la validation du formulaire
    document.getElementById('itemForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Vérifier les champs requis
        const requiredFields = this.querySelectorAll('input[required]:not(:disabled), select[required]:not(:disabled), textarea[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('error');
                
                // Ajouter un message d'erreur
                const errorMsg = field.parentNode.querySelector('.error-message');
                if (!errorMsg) {
                    const msg = document.createElement('div');
                    msg.className = 'error-message';
                    msg.textContent = 'Ce champ est requis';
                    field.parentNode.appendChild(msg);
                }
            }
        });
        
        if (isValid) {
            this.submit();
        }
    });

    // Supprimer les messages d'erreur lors de la saisie
    document.querySelectorAll('.form-input').forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('error');
            const errorMsg = this.parentNode.querySelector('.error-message');
            if (errorMsg) {
                errorMsg.remove();
            }
        });
    });
    </script>
</body>
</html> 