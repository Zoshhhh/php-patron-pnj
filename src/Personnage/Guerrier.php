<?php

namespace App\Personnage;

use App\Strategie\ComportementCombatInterface;

class Guerrier extends PersonnageAbstrait
{
    public function __construct(
        string $nom,
        ComportementCombatInterface $comportementCombat,
        int $pointsDeVie = 20,
        int $force = 4,
        int $dexterite = 2
    ) {
        parent::__construct($nom, $pointsDeVie, $force, $dexterite, $comportementCombat);
    }
} 