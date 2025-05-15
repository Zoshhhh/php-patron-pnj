<?php

namespace App\Fabrique;

use App\Personnage\Archer;
use App\Personnage\Guerrier;
use App\Personnage\PersonnageInterface;
use App\Strategie\CombatADistance;
use App\Strategie\CombatAuCorpsACorps;

class FabriquePersonnage
{
    public function creerGuerrier(string $nom): PersonnageInterface
    {
        return new Guerrier($nom, new CombatAuCorpsACorps());
    }

    public function creerArcher(string $nom): PersonnageInterface
    {
        return new Archer($nom, new CombatADistance());
    }

    public function creerAllie(string $nom): PersonnageInterface
    {
        // Par défaut, on crée un guerrier allié
        return $this->creerGuerrier($nom);
    }

    public function creerEnnemi(string $nom): PersonnageInterface
    {
        // Par défaut, on crée un archer ennemi
        return $this->creerArcher($nom);
    }
} 