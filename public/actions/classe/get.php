<?php
session_start();
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/vendor/autoload.php';

use App\Factory\ClasseFactory;

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de classe manquant']);
    exit;
}

$classeId = $_GET['id'];
$classeFactory = new ClasseFactory();
$classe = $classeFactory->getClassDetails($classeId);

if ($classe === null) {
    http_response_code(404);
    echo json_encode(['error' => 'Classe non trouvée']);
    exit;
}

echo json_encode($classe); 