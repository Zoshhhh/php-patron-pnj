<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['attacker']) || !isset($data['target'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Données manquantes'
    ]);
    exit;
}

$attacker = $_SESSION['personnages'][$data['attacker']] ?? null;
$target = $_SESSION['personnages'][$data['target']] ?? null;

if (!$attacker || !$target) {
    echo json_encode([
        'success' => false,
        'error' => 'Personnage non trouvé'
    ]);
    exit;
}

// Convertir les données en tableau avec l'inventaire
$attackerData = is_array($attacker) ? $attacker : $attacker->toArray();
$targetData = is_array($target) ? $target : $target->toArray();

echo json_encode([
    'success' => true,
    'attacker' => $attackerData,
    'target' => $targetData
]); 