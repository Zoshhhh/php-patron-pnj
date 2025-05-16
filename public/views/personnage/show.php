<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once ROOT_PATH . '/vendor/autoload.php';

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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche de Personnage - <?= htmlspecialchars($personnage->getNom()) ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/details.css">
</head>
<body>
    <div class="character-details">
        <div class="character-header">
            <div class="character-title">
                <h1><?= htmlspecialchars($personnage->getNom()) ?></h1>
                <div class="character-subtitle"><?= ucfirst($persoData['classe']) ?> Niveau 1</div>
            </div>
            <?php $categorie = $persoData['categorie'] ?? 'personnage'; ?>
            <div class="character-category-badge <?= htmlspecialchars($categorie) ?>">
                <?= ucfirst($categorie) ?>
            </div>
        </div>

        <div class="abilities-section">
            <div class="ability-box">
                <div class="ability-name">Force</div>
                <div class="ability-score"><?= $personnage->getForce() ?></div>
                <div class="ability-modifier">+<?= $personnage->getModificateur($personnage->getForce()) ?></div>
            </div>
            <div class="ability-box">
                <div class="ability-name">Dextérité</div>
                <div class="ability-score"><?= $personnage->getDexterite() ?></div>
                <div class="ability-modifier">+<?= $personnage->getModificateur($personnage->getDexterite()) ?></div>
            </div>
            <div class="ability-box">
                <div class="ability-name">Constitution</div>
                <div class="ability-score"><?= $personnage->getConstitution() ?></div>
                <div class="ability-modifier">+<?= $personnage->getModificateur($personnage->getConstitution()) ?></div>
            </div>
            <div class="ability-box">
                <div class="ability-name">Intelligence</div>
                <div class="ability-score"><?= $personnage->getIntelligence() ?></div>
                <div class="ability-modifier">+<?= $personnage->getModificateur($personnage->getIntelligence()) ?></div>
            </div>
            <div class="ability-box">
                <div class="ability-name">Sagesse</div>
                <div class="ability-score"><?= $personnage->getSagesse() ?></div>
                <div class="ability-modifier">+<?= $personnage->getModificateur($personnage->getSagesse()) ?></div>
            </div>
            <div class="ability-box">
                <div class="ability-name">Charisme</div>
                <div class="ability-score"><?= $personnage->getCharisme() ?></div>
                <div class="ability-modifier">+<?= $personnage->getModificateur($personnage->getCharisme()) ?></div>
            </div>
        </div>

        <div class="stats-section">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-label">Points de vie</div>
                    <div class="stat-value"><?= $personnage->getPointsDeVie() ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Classe d'armure</div>
                    <div class="stat-value"><?= $personnage->getClasseArmure() ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Initiative</div>
                    <div class="stat-value">+<?= $personnage->getModificateur($personnage->getDexterite()) ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Vitesse</div>
                    <div class="stat-value"><?= $personnage->getVitesse() ?> ft.</div>
                </div>
            </div>
        </div>

        <div class="actions-section">
            <a href="/views/personnage/index.php" class="button">Retour à la liste</a>
            <a href="/views/personnage/create.php" class="button">Créer un nouveau personnage</a>
        </div>
    </div>
</body>
</html> 