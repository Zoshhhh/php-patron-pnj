<?php
session_start();
header('Content-Type: application/json');

if (!isset($_GET['classe'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de classe manquant']);
    exit;
}

$classeId = $_GET['classe'];
$classe = null;

foreach ($_SESSION['classes'] as $c) {
    if ($c['id'] === $classeId) {
        $classe = $c;
        break;
    }
}

if ($classe === null) {
    http_response_code(404);
    echo json_encode(['error' => 'Classe non trouvée']);
    exit;
}

echo json_encode($classe); 