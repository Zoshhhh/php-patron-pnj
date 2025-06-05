<?php

namespace App\Personnage;

use App\Strategie\ComportementCombatInterface;

class Archer extends PersonnageAbstrait
{
    public function __construct(
        string $nom,
        ComportementCombatInterface $comportementCombat,
        array $stats = []
    ) {
        $statsParDefaut = [
            'force' => 12,
            'dexterite' => 16,
            'constitution' => 12,
            'intelligence' => 14,
            'sagesse' => 14,
            'charisme' => 12,
            'pointsDeVie' => 15,
            'classeArmure' => 14,
            'vitesse' => 35,
            'comportementCombat' => $comportementCombat
        ];

        $statsFinal = array_merge($statsParDefaut, $stats);
        
        parent::__construct($nom, $statsFinal);
    }
} 