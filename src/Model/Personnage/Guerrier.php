<?php

namespace App\Model\Personnage;

use App\Interface\ComportementCombatInterface;

class Guerrier extends PersonnageAbstrait
{
    public function __construct(string $nom, ComportementCombatInterface $comportementCombat, array $stats = [])
    {
        parent::__construct($nom, $comportementCombat);
        
        // Stats par défaut pour un guerrier
        $this->force = $stats['force'] ?? 15;
        $this->dexterite = $stats['dexterite'] ?? 12;
        $this->constitution = $stats['constitution'] ?? 14;
        $this->intelligence = $stats['intelligence'] ?? 8;
        $this->sagesse = $stats['sagesse'] ?? 10;
        $this->charisme = $stats['charisme'] ?? 10;
        
        // Stats de combat
        $this->pointsDeVie = $stats['pointsDeVie'] ?? 20;
        $this->classeArmure = $stats['classeArmure'] ?? 15;
        $this->vitesse = $stats['vitesse'] ?? 30;
    }
} 