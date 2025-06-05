<?php
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/vendor/autoload.php';

use App\Factory\ItemFactory;
use App\Model\Item\Item;

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /views/item/create.php');
    exit;
}

if (!isset($_SESSION['items'])) {
    $_SESSION['items'] = [];
}

$factory = new ItemFactory();

try {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $value = (int)($_POST['value'] ?? 0);
    $type = $_POST['itemType'] ?? '';

    $itemData = [
        'nom' => $name,
        'description' => $description,
        'type' => $type,
        'rarete' => 'Commun',
        'poids' => 1.0,
        'valeur' => $value
    ];

    // Ajout des propriétés spécifiques selon le type
    switch ($type) {
        case 'combat':
            $itemData['degats'] = (int)($_POST['damage'] ?? 0);
            $itemData['durabilite'] = (int)($_POST['durability'] ?? 100);
            break;

        case 'consommable':
            $itemData['soin'] = (int)($_POST['healAmount'] ?? 0);
            $itemData['empilable'] = isset($_POST['isStackable']);
            break;

        case 'equipement':
            $itemData['defense'] = (int)($_POST['defense'] ?? 0);
            $itemData['emplacement'] = $_POST['slot'] ?? 'main';
            break;

        default:
            throw new \InvalidArgumentException('Type d\'item invalide');
    }

    if (count($_SESSION['items']) >= MAX_INVENTORY_SIZE) {
        $_SESSION['error'] = ERROR_MESSAGES['inventory_full'];
        header('Location: /views/item/create.php');
        exit;
    }

    // Stocker directement le tableau de données
    $_SESSION['items'][] = $itemData;
    
    $_SESSION['success'] = 'Item créé avec succès !';
    header('Location: /views/item/show.php');
    exit;

} catch (\Exception $e) {
    $_SESSION['error'] = 'Erreur lors de la création de l\'item : ' . $e->getMessage();
    header('Location: /views/item/create.php');
    exit;
} 