<?php
session_start();
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/vendor/autoload.php';

use App\Model\Item\CombatItem;

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['personnageId']) || !isset($data['itemId'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Données manquantes'
    ]);
    exit;
}

$personnage = $_SESSION['personnages'][$data['personnageId']] ?? null;
$item = $_SESSION['items'][$data['itemId']] ?? null;

if (!$personnage || !$item) {
    echo json_encode([
        'success' => false,
        'error' => 'Personnage ou item non trouvé'
    ]);
    exit;
}

$itemToAdd = [];
if (is_array($item)) {
    $itemToAdd = $item;
} elseif (is_object($item)) {
    foreach (get_object_vars($item) as $key => $value) {
        $itemToAdd[$key] = $value;
    }
}

if (!isset($personnage['inventaire'])) {
    $personnage['inventaire'] = [];
}

if (!is_array($personnage['inventaire'])) {
    $personnage['inventaire'] = [];
}

$personnage['inventaire'][] = $itemToAdd;
$_SESSION['personnages'][$data['personnageId']] = $personnage;

echo json_encode([
    'success' => true,
    'message' => 'Item ajouté à l\'inventaire',
    'personnage' => $personnage
]); 