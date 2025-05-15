<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Fabrique\FabriquePersonnage;

$fabrique = new FabriquePersonnage();

// Récupération des paramètres
$nom = $_GET['nom'] ?? 'Sheila';
$classe = $_GET['classe'] ?? 'guerrier';

// Création du personnage
$personnage = match($classe) {
    'guerrier' => $fabrique->creerGuerrier($nom),
    'archer' => $fabrique->creerArcher($nom),
    default => $fabrique->creerGuerrier($nom),
};
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartes de Personnages</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="character-sheet">
        <div class="character-header">
            <h1><?= htmlspecialchars($personnage->getNom()) ?></h1>
            <div class="character-class"><?= ucfirst($classe) ?> Niveau 1</div>
        </div>

        <div class="ability-scores">
            <div class="ability">
                <div class="ability-name">Force</div>
                <div class="ability-score"><?= $personnage->getForce() ?></div>
                <div class="ability-modifier">+<?= floor(($personnage->getForce() - 10) / 2) ?></div>
            </div>
            <div class="ability">
                <div class="ability-name">Dextérité</div>
                <div class="ability-score"><?= $personnage->getDexterite() ?></div>
                <div class="ability-modifier">+<?= floor(($personnage->getDexterite() - 10) / 2) ?></div>
            </div>
            <div class="ability">
                <div class="ability-name">Constitution</div>
                <div class="ability-score">12</div>
                <div class="ability-modifier">+1</div>
            </div>
            <div class="ability">
                <div class="ability-name">Intelligence</div>
                <div class="ability-score">14</div>
                <div class="ability-modifier">+2</div>
            </div>
            <div class="ability">
                <div class="ability-name">Sagesse</div>
                <div class="ability-score">14</div>
                <div class="ability-modifier">+2</div>
            </div>
            <div class="ability">
                <div class="ability-name">Charisme</div>
                <div class="ability-score">12</div>
                <div class="ability-modifier">+1</div>
            </div>
        </div>

        <div class="stats">
            <div class="stat-block">
                <div class="stat-label">Points de vie</div>
                <div class="stat-value"><?= $personnage->getPointsDeVie() ?></div>
            </div>
            <div class="stat-block">
                <div class="stat-label">Classe d'armure</div>
                <div class="stat-value">13</div>
            </div>
            <div class="stat-block">
                <div class="stat-label">Initiative</div>
                <div class="stat-value">+<?= floor(($personnage->getDexterite() - 10) / 2) ?></div>
            </div>
            <div class="stat-block">
                <div class="stat-label">Vitesse</div>
                <div class="stat-value">30 ft.</div>
            </div>
        </div>

        <div class="actions">
            <a href="create.php" class="button">Créer un nouveau personnage</a>
        </div>
    </div>
</body>
</html> 