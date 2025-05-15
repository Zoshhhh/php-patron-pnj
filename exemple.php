<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Fabrique\FabriquePersonnage;

$fabrique = new FabriquePersonnage();

// Création des personnages
$joueur = $fabrique->creerGuerrier("Sheila");
$allie = $fabrique->creerAllie("Robin");
$ennemi = $fabrique->creerEnnemi("Gobelin");

// Exemple de combat
echo $joueur->getNom() . " attaque " . $ennemi->getNom() . "\n";
$joueur->attaquer($ennemi);
echo $ennemi->getNom() . " a maintenant " . $ennemi->getPointsDeVie() . " points de vie\n";

echo $allie->getNom() . " attaque " . $ennemi->getNom() . "\n";
$allie->attaquer($ennemi);
echo $ennemi->getNom() . " a maintenant " . $ennemi->getPointsDeVie() . " points de vie\n";

if (!$ennemi->estVivant()) {
    echo $ennemi->getNom() . " a été vaincu !\n";
} 