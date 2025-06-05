<?php

namespace App\Interface;

use App\Personnage\PersonnageInterface;

interface ComportementCombatInterface
{
    public function attaquer(PersonnageInterface $attaquant, PersonnageInterface $cible): void;
} 