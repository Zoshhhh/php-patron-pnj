<?php

namespace App\Strategie;

use App\Personnage\PersonnageInterface;

class CombatADistance implements ComportementCombatInterface
{
    public function attaquer(PersonnageInterface $attaquant, PersonnageInterface $cible): void
    {
        $degats = rand(1, 8) + $attaquant->getDexterite();
        $cible->recevoirDegats($degats);
    }
} 