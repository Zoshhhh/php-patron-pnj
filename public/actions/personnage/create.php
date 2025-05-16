<?php
session_start();
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Fabrique\FabriquePersonnage;

if (!isset($_SESSION['personnages'])) {
    $_SESSION['personnages'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $classe = $_POST['classe'] ?? 'guerrier';
    $categorie = $_POST['categorie'] ?? 'personnage';
    
    if ($nom) {
        $stats = [];
        foreach (['force', 'dexterite', 'constitution', 'intelligence', 'sagesse', 'charisme', 'pointsDeVie', 'classeArmure', 'vitesse'] as $stat) {
            if (isset($_POST[$stat])) {
                $stats[$stat] = (int)$_POST[$stat];
            }
        }

        $_SESSION['personnages'][] = [
            'nom' => $nom,
            'classe' => $classe,
            'categorie' => $categorie,
            'stats' => $stats
        ];

        header('Location: /views/personnage/index.php');
        exit;
    }
}

header('Location: /views/personnage/create.php?error=nom_requis');
exit; 