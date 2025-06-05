<?php

namespace App\Model\Strategie;

use App\Interface\ComportementCombatInterface;

class CombatAuCorpsACorps implements ComportementCombatInterface
{
    public function attaquer(): int
    {
        return rand(1, 10) + 3; // 1d10 + 3
    }
} 