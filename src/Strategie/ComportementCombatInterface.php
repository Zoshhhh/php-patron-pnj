<?php

namespace App\Strategie;

use App\Personnage\PersonnageInterface;

interface ComportementCombatInterface
{
    public function attaquer(PersonnageInterface $attaquant, PersonnageInterface $cible): void;
} 