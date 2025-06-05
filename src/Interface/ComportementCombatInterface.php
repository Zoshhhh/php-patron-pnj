<?php

namespace App\Interface;

interface ComportementCombatInterface
{
    /**
     * Calcule et retourne les dégâts infligés par une attaque
     * @return int Le montant des dégâts
     */
    public function attaquer(): int;
} 