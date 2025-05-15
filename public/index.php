<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Fabrique\FabriquePersonnage;

$fabrique = new FabriquePersonnage();

$nom = $_GET['nom'] ?? 'Sheila';
$classe = $_GET['classe'] ?? 'guerrier';

$stats = [];
foreach (['force', 'dexterite', 'constitution', 'intelligence', 'sagesse', 'charisme', 'pointsDeVie', 'classeArmure', 'vitesse'] as $stat) {
    if (isset($_GET[$stat])) {
        $stats[$stat] = (int)$_GET[$stat];
    }
}

$personnage = match($classe) {
    'guerrier' => $fabrique->creerGuerrier($nom, $stats),
    'archer' => $fabrique->creerArcher($nom, $stats),
    default => $fabrique->creerGuerrier($nom, $stats),
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
                <div class="ability-modifier">+<?= $personnage->getModificateur($personnage->getForce()) ?></div>
            </div>
            <div class="ability">
                <div class="ability-name">Dextérité</div>
                <div class="ability-score"><?= $personnage->getDexterite() ?></div>
                <div class="ability-modifier">+<?= $personnage->getModificateur($personnage->getDexterite()) ?></div>
            </div>
            <div class="ability">
                <div class="ability-name">Constitution</div>
                <div class="ability-score"><?= $personnage->getConstitution() ?></div>
                <div class="ability-modifier">+<?= $personnage->getModificateur($personnage->getConstitution()) ?></div>
            </div>
            <div class="ability">
                <div class="ability-name">Intelligence</div>
                <div class="ability-score"><?= $personnage->getIntelligence() ?></div>
                <div class="ability-modifier">+<?= $personnage->getModificateur($personnage->getIntelligence()) ?></div>
            </div>
            <div class="ability">
                <div class="ability-name">Sagesse</div>
                <div class="ability-score"><?= $personnage->getSagesse() ?></div>
                <div class="ability-modifier">+<?= $personnage->getModificateur($personnage->getSagesse()) ?></div>
            </div>
            <div class="ability">
                <div class="ability-name">Charisme</div>
                <div class="ability-score"><?= $personnage->getCharisme() ?></div>
                <div class="ability-modifier">+<?= $personnage->getModificateur($personnage->getCharisme()) ?></div>
            </div>
        </div>

        <div class="stats">
            <div class="stat-block">
                <div class="stat-label">Points de vie</div>
                <div class="stat-value"><?= $personnage->getPointsDeVie() ?></div>
            </div>
            <div class="stat-block">
                <div class="stat-label">Classe d'armure</div>
                <div class="stat-value"><?= $personnage->getClasseArmure() ?></div>
            </div>
            <div class="stat-block">
                <div class="stat-label">Initiative</div>
                <div class="stat-value">+<?= $personnage->getModificateur($personnage->getDexterite()) ?></div>
            </div>
            <div class="stat-block">
                <div class="stat-label">Vitesse</div>
                <div class="stat-value"><?= $personnage->getVitesse() ?> ft.</div>
            </div>
        </div>

        <div class="actions">
            <a href="create.php" class="button">Créer un nouveau personnage</a>
        </div>
    </div>
</body>
</html> 