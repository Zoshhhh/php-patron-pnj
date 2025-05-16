<?php
require_once __DIR__ . '/../../config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /views/item/create.php');
    exit;
}

// Initialiser le stockage des items si nécessaire
if (!isset($_SESSION['items'])) {
    $_SESSION['items'] = [];
}

// Validation du type d'item
$itemType = $_POST['itemType'] ?? '';
if (!array_key_exists($itemType, ITEM_TYPES)) {
    $_SESSION['error'] = ERROR_MESSAGES['invalid_item_type'];
    header('Location: /views/item/create.php');
    exit;
}

// Création de l'item de base
$item = [
    'type' => $itemType,
    'name' => $_POST['name'] ?? '',
    'description' => $_POST['description'] ?? '',
    'value' => (int)($_POST['value'] ?? 0)
];

// Ajout des propriétés spécifiques selon le type
switch ($itemType) {
    case 'combat':
        $item['damage'] = min((int)($_POST['damage'] ?? DEFAULT_ITEM_VALUES['damage']), MAX_DAMAGE);
        $item['durability'] = min((int)($_POST['durability'] ?? DEFAULT_ITEM_VALUES['durability']), MAX_DURABILITY);
        break;

    case 'consommable':
        $item['healAmount'] = min((int)($_POST['healAmount'] ?? DEFAULT_ITEM_VALUES['healAmount']), MAX_HEAL_AMOUNT);
        $item['isStackable'] = isset($_POST['isStackable']) ? true : false;
        break;

    case 'equipement':
        $item['defense'] = min((int)($_POST['defense'] ?? DEFAULT_ITEM_VALUES['defense']), MAX_DEFENSE);
        $slot = $_POST['slot'] ?? '';
        if (!array_key_exists($slot, EQUIPMENT_SLOTS)) {
            $_SESSION['error'] = ERROR_MESSAGES['invalid_slot'];
            header('Location: /views/item/create.php');
            exit;
        }
        $item['slot'] = $slot;
        break;
}

if (count($_SESSION['items']) >= MAX_INVENTORY_SIZE) {
    $_SESSION['error'] = ERROR_MESSAGES['inventory_full'];
    header('Location: /views/item/create.php');
    exit;
}

$_SESSION['items'][] = $item;
$_SESSION['success'] = 'Item créé avec succès !';

header('Location: /views/item/show.php');
exit; 