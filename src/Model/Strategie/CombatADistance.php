<?php

namespace App\Model\Strategie;

use App\Interface\ComportementCombatInterface;

class CombatADistance implements ComportementCombatInterface
{
    public function attaquer(): int
    {
        return rand(1, 8) + 2; // 1d8 + 2
    }
} 