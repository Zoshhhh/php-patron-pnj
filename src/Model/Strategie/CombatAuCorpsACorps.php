<?php

namespace App\Strategie;

use App\Personnage\PersonnageInterface;

class CombatAuCorpsACorps implements ComportementCombatInterface
{
    public function attaquer(PersonnageInterface $attaquant, PersonnageInterface $cible): void
    {
        $degats = rand(1, 6) + $attaquant->getForce();
        $cible->recevoirDegats($degats);
    }
} 