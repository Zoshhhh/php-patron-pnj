<?php
session_start();
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Classes\ClasseFactory;

if (!isset($_SESSION['classes'])) {
    $_SESSION['classes'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $description = $_POST['description'] ?? '';
    $stats = $_POST['stats'] ?? [];
    $competences = $_POST['competences'] ?? [];
    $equipement = $_POST['equipement'] ?? [];

    if ($nom && $description) {
        $nouvelleClasse = [
            'id' => strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $nom)),
            'nom' => $nom,
            'description' => $description,
            'stats_base' => $stats,
            'competences' => array_filter($competences),
            'equipement_initial' => array_filter($equipement)
        ];

        $_SESSION['classes'][] = $nouvelleClasse;
        header('Location: /views/classe/index.php');
        exit;
    }
}

header('Location: /views/classe/create.php?error=champs_requis');
exit; 