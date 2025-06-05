<?php

namespace App\Model\Personnage;

use App\Interface\ComportementCombatInterface;

class Archer extends PersonnageAbstrait
{
    public function __construct(string $nom, ComportementCombatInterface $comportementCombat, array $stats = [])
    {
        parent::__construct($nom, $comportementCombat);
        
        // Stats par défaut pour un archer
        $this->force = $stats['force'] ?? 10;
        $this->dexterite = $stats['dexterite'] ?? 15;
        $this->constitution = $stats['constitution'] ?? 12;
        $this->intelligence = $stats['intelligence'] ?? 10;
        $this->sagesse = $stats['sagesse'] ?? 14;
        $this->charisme = $stats['charisme'] ?? 8;
        
        // Stats de combat
        $this->pointsDeVie = $stats['pointsDeVie'] ?? 15;
        $this->classeArmure = $stats['classeArmure'] ?? 14;
        $this->vitesse = $stats['vitesse'] ?? 35;
    }
} 