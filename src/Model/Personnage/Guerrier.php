<?php

namespace App\Personnage;

use App\Strategie\ComportementCombatInterface;

class Guerrier extends PersonnageAbstrait
{
    public function __construct(
        string $nom,
        ComportementCombatInterface $comportementCombat,
        array $stats = []
    ) {
        $statsParDefaut = [
            'force' => 16,
            'dexterite' => 14,
            'constitution' => 12,
            'intelligence' => 14,
            'sagesse' => 14,
            'charisme' => 12,
            'pointsDeVie' => 20,
            'classeArmure' => 13,
            'vitesse' => 30,
            'comportementCombat' => $comportementCombat
        ];

        $statsFinal = array_merge($statsParDefaut, $stats);
        
        parent::__construct($nom, $statsFinal);
    }
} 