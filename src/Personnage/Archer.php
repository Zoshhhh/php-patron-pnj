<?php

namespace App\Personnage;

use App\Strategie\ComportementCombatInterface;

class Archer extends PersonnageAbstrait
{
    public function __construct(
        string $nom,
        ComportementCombatInterface $comportementCombat,
        int $pointsDeVie = 15,
        int $force = 2,
        int $dexterite = 4
    ) {
        parent::__construct($nom, $pointsDeVie, $force, $dexterite, $comportementCombat);
    }
} 