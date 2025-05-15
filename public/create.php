<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Fabrique\FabriquePersonnage;

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fabrique = new FabriquePersonnage();
    
    $nom = $_POST['nom'] ?? '';
    $classe = $_POST['classe'] ?? 'guerrier';
    
    if ($nom) {
        $personnage = match($classe) {
            'guerrier' => $fabrique->creerGuerrier($nom),
            'archer' => $fabrique->creerArcher($nom),
            default => $fabrique->creerGuerrier($nom),
        };
        
        header('Location: index.php?nom=' . urlencode($nom) . '&classe=' . urlencode($classe));
        exit;
    } else {
        $message = 'Le nom est requis';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Personnage</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="character-sheet creation-form">
        <div class="character-header">
            <h1>Créer un Personnage</h1>
        </div>
        
        <?php if ($message): ?>
            <div class="alert"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" class="character-form">
            <div class="form-group">
                <label for="nom">Nom du personnage</label>
                <input type="text" id="nom" name="nom" required>
            </div>

            <div class="form-group">
                <label for="classe">Classe</label>
                <select id="classe" name="classe">
                    <option value="guerrier">Guerrier</option>
                    <option value="archer">Archer</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit">Créer</button>
                <a href="index.php" class="button">Retour</a>
            </div>
        </form>
    </div>
</body>
</html> 