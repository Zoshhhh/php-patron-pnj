<?php
require_once __DIR__ . '/../../../autoload.php';
require_once __DIR__ . '/../../../src/Item/ItemFactory.php';
require_once __DIR__ . '/../../../src/Item/ItemInterface.php';
require_once __DIR__ . '/../../../src/Item/AbstractItem.php';
require_once __DIR__ . '/../../../src/Item/CombatItem.php';
require_once __DIR__ . '/../../../src/Item/ConsumableItem.php';
require_once __DIR__ . '/../../../src/Item/EquipmentItem.php';

use App\Item\ItemFactory;

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /views/item/create.php');
    exit;
}

if (!isset($_SESSION['items'])) {
    $_SESSION['items'] = [];
}

$factory = new ItemFactory();
$item = null;

try {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $value = (int)($_POST['value'] ?? 0);
    $type = $_POST['itemType'] ?? '';

    switch ($type) {
        case 'combat':
            $damage = (int)($_POST['damage'] ?? 0);
            $durability = (int)($_POST['durability'] ?? 0);
            $item = $factory->createCombatItem($name, $description, $value, $damage, $durability);
            break;

        case 'consommable':
            $healAmount = (int)($_POST['healAmount'] ?? 0);
            $isStackable = isset($_POST['isStackable']);
            $item = $factory->createConsumableItem($name, $description, $value, $healAmount, $isStackable);
            break;

        case 'equipement':
            $defense = (int)($_POST['defense'] ?? 0);
            $slot = $_POST['slot'] ?? '';
            $item = $factory->createEquipmentItem($name, $description, $value, $defense, $slot);
            break;

        default:
            throw new \InvalidArgumentException('Type d\'item invalide');
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

} catch (\Exception $e) {
    $_SESSION['error'] = 'Erreur lors de la création de l\'item : ' . $e->getMessage();
    header('Location: /views/item/create.php');
    exit;
} 