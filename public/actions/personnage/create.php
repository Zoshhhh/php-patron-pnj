<?php
session_start();
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/vendor/autoload.php';

use App\Fabrique\FabriquePersonnage;
use App\Strategie\CombatADistance;
use App\Strategie\CombatAuCorpsACorps;

if (!isset($_SESSION['personnages'])) {
    $_SESSION['personnages'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $fabrique = new FabriquePersonnage();
        
        $nom = $_POST['nom'] ?? '';
        $classe = $_POST['classe'] ?? 'guerrier';
        $categorie = $_POST['categorie'] ?? 'personnage';
        
        if (empty($nom)) {
            throw new \InvalidArgumentException('Le nom est requis');
        }

        // Récupération des stats
        $stats = [];
        foreach (['force', 'dexterite', 'constitution', 'intelligence', 'sagesse', 'charisme', 'pointsDeVie', 'classeArmure', 'vitesse'] as $stat) {
            if (isset($_POST[$stat])) {
                $stats[$stat] = (int)$_POST[$stat];
            }
        }

        // Création du personnage selon sa classe
        $personnage = match($classe) {
            'guerrier' => $fabrique->creerGuerrier($nom, $stats),
            'archer' => $fabrique->creerArcher($nom, $stats),
            default => throw new \InvalidArgumentException('Classe invalide')
        };

        // Stockage en session
        $_SESSION['personnages'][] = [
            'nom' => $nom,
            'classe' => $classe,
            'categorie' => $categorie,
            'stats' => $stats
        ];

        $_SESSION['success'] = 'Personnage créé avec succès !';
        header('Location: /php-patron-pnj/public/views/personnage/index.php');
        exit;

    } catch (\Exception $e) {
        $_SESSION['error'] = 'Erreur lors de la création du personnage : ' . $e->getMessage();
        header('Location: /php-patron-pnj/public/views/personnage/create.php');
        exit;
    }
}

header('Location: /php-patron-pnj/public/views/personnage/create.php');
exit; 