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
    <title><?= htmlspecialchars($personnage->getNom()) ?> - Fiche de personnage</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/details.css">
</head>
<body>
    <div class="character-sheet">
        <nav class="character-nav">
            <div class="nav-group">
                <a href="/views/personnage/index.php" class="nav-link">← Retour</a>
                <span class="nav-separator">/</span>
                <a href="/views/personnage/create.php" class="nav-link">Nouveau</a>
            </div>
            <div class="nav-category <?= htmlspecialchars($persoData['categorie'] ?? 'personnage') ?>">
                <?= ucfirst($persoData['categorie'] ?? 'personnage') ?>
            </div>
        </nav>

        <header class="character-header">
            <div class="header-main">
                <div class="header-title">
                    <h1><?= htmlspecialchars($personnage->getNom()) ?></h1>
                    <div class="character-meta">
                        <span class="class-badge"><?= ucfirst($persoData['classe']) ?></span>
                        <span class="level-badge">Niveau 1</span>
                    </div>
                </div>
                <div class="quick-actions">
                    <button type="button" class="action-button edit-button" onclick="window.location.href='/views/personnage/edit.php?id=<?= $index ?>'">
                        ✏️ Modifier
                    </button>
                    <button type="button" class="action-button delete-button" onclick="confirmDelete(<?= $index ?>)">
                        🗑️ Supprimer
                    </button>
                </div>
            </div>
            
            <div class="vital-stats">
                <div class="vital-stat" data-tooltip="Points de vie actuels">
                    <span class="stat-icon">❤️</span>
                    <span class="stat-value"><?= $personnage->getPointsDeVie() ?></span>
                    <span class="stat-label">PV</span>
                </div>
                <div class="vital-stat" data-tooltip="Classe d'armure - Plus c'est élevé, plus le personnage est difficile à toucher">
                    <span class="stat-icon">🛡️</span>
                    <span class="stat-value"><?= $personnage->getClasseArmure() ?></span>
                    <span class="stat-label">CA</span>
                </div>
                <div class="vital-stat" data-tooltip="Bonus d'initiative - Détermine l'ordre d'action en combat">
                    <span class="stat-icon">⚡</span>
                    <span class="stat-value">+<?= $personnage->getModificateur($personnage->getDexterite()) ?></span>
                    <span class="stat-label">Initiative</span>
                </div>
            </div>
        </header>

        <div class="character-content">
            <section class="abilities-section">
                <h2>Caractéristiques</h2>
                <div class="abilities-grid">
                    <?php
                    $abilities = [
                        'Force' => $personnage->getForce(),
                        'Dextérité' => $personnage->getDexterite(),
                        'Constitution' => $personnage->getConstitution(),
                        'Intelligence' => $personnage->getIntelligence(),
                        'Sagesse' => $personnage->getSagesse(),
                        'Charisme' => $personnage->getCharisme()
                    ];
                    $abilityDescriptions = [
                        'Force' => 'Force physique, capacité à porter et frapper',
                        'Dextérité' => 'Agilité, réflexes et équilibre',
                        'Constitution' => 'Endurance, résistance et vitalité',
                        'Intelligence' => 'Mémoire, raisonnement et apprentissage',
                        'Sagesse' => 'Perception, intuition et volonté',
                        'Charisme' => 'Force de personnalité et leadership'
                    ];
                    foreach ($abilities as $name => $score): ?>
                        <div class="ability-card" data-tooltip="<?= $abilityDescriptions[$name] ?>">
                            <div class="ability-name"><?= $name ?></div>
                            <div class="ability-score"><?= $score ?></div>
                            <div class="ability-modifier">+<?= $personnage->getModificateur($score) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="combat-section">
                <h2>Combat</h2>
                <div class="combat-grid">
                    <div class="combat-stat" data-tooltip="Distance de déplacement par tour">
                        <span class="combat-icon">🏃</span>
                        <div class="combat-value"><?= $personnage->getVitesse() ?></div>
                        <div class="combat-label">Vitesse</div>
                    </div>
                    <div class="combat-stat" data-tooltip="Bonus aux attaques de corps à corps">
                        <span class="combat-icon">⚔️</span>
                        <div class="combat-value">+<?= $personnage->getModificateur($personnage->getForce()) ?></div>
                        <div class="combat-label">Attaque</div>
                    </div>
                    <div class="combat-stat" data-tooltip="Bonus aux attaques à distance">
                        <span class="combat-icon">🎯</span>
                        <div class="combat-value">+<?= $personnage->getModificateur($personnage->getDexterite()) ?></div>
                        <div class="combat-label">Précision</div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
    function confirmDelete(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce personnage ? Cette action est irréversible.')) {
            window.location.href = `/actions/personnage/delete.php?id=${id}`;
        }
    }

    // Animation des cartes au survol
    document.querySelectorAll('.ability-card, .combat-stat, .vital-stat').forEach(card => {
        card.addEventListener('mouseover', function() {
            this.style.transform = 'translateY(-2px)';
        });
        card.addEventListener('mouseout', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    </script>
</body>
</html> 