<?php
require_once __DIR__ . '/../../../autoload.php';

use App\Classes\ClasseFactory;

session_start();

$factory = new ClasseFactory();

try {
    // Validation des données
    if (!isset($_POST['name'], $_POST['type'], $_POST['description'], 
               $_POST['baseHP'], $_POST['hitDie'], $_POST['baseAC'],
               $_POST['stats'], $_POST['proficiencies'])) {
        throw new Exception('Tous les champs requis doivent être remplis');
    }

    // Nettoyage et validation des données
    $name = trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
    $type = trim(filter_var($_POST['type'], FILTER_SANITIZE_STRING));
    $description = trim(filter_var($_POST['description'], FILTER_SANITIZE_STRING));
    $baseHP = filter_var($_POST['baseHP'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 20]]);
    $hitDie = filter_var($_POST['hitDie'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 6, 'max_range' => 12]]);
    $baseAC = filter_var($_POST['baseAC'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 10, 'max_range' => 18]]);

    if (!$name || !$type || !$description || $baseHP === false || $hitDie === false || $baseAC === false) {
        throw new Exception('Les valeurs fournies sont invalides');
    }

    // Validation des stats
    $stats = $_POST['stats'];
    $requiredStats = ['force', 'dexterite', 'constitution', 'intelligence', 'sagesse', 'charisme'];
    foreach ($requiredStats as $stat) {
        if (!isset($stats[$stat])) {
            throw new Exception("La statistique $stat est manquante");
        }
        $stats[$stat] = filter_var($stats[$stat], FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 8, 'max_range' => 16]
        ]);
        if ($stats[$stat] === false) {
            throw new Exception("La valeur de $stat est invalide");
        }
    }

    // Validation des maîtrises
    $proficiencies = array_filter(array_map('trim', $_POST['proficiencies']));
    if (empty($proficiencies)) {
        throw new Exception('Au moins une maîtrise est requise');
    }

    // Création de la classe
    $classeData = [
        'name' => $name,
        'type' => $type,
        'description' => $description,
        'baseHP' => $baseHP,
        'hitDie' => $hitDie,
        'baseAC' => $baseAC,
        'stats' => $stats,
        'proficiencies' => $proficiencies
    ];

    // Sauvegarde de la classe (à implémenter dans ClasseFactory)
    $factory->saveClasse($classeData);

    // Redirection avec succès
    $_SESSION['success_message'] = 'La classe a été créée avec succès';
    header('Location: /views/classe/index.php');
    exit;

} catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
    header('Location: /views/classe/create.php');
    exit;
} 