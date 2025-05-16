<?php
session_start();
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Fabrique\FabriquePersonnage;

$index = isset($_GET['id']) ? (int)$_GET['id'] : null;

if ($index === null || !isset($_SESSION['personnages'][$index])) {
    header('Location: /views/personnage/index.php');
    exit;
}

$persoData = $_SESSION['personnages'][$index];
$fabrique = new FabriquePersonnage();

$personnage = match($persoData['classe']) {
    'guerrier' => $fabrique->creerGuerrier($persoData['nom'], $persoData['stats']),
    'archer' => $fabrique->creerArcher($persoData['nom'], $persoData['stats']),
    default => $fabrique->creerGuerrier($persoData['nom'], $persoData['stats']),
};

header('Location: /views/personnage/show.php?id=' . $index);
exit; 