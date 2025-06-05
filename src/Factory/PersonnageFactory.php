<?php

namespace App\Factory;

use App\Model\Personnage\Archer;
use App\Model\Personnage\Guerrier;
use App\Interface\PersonnageInterface;
use App\Model\Strategie\CombatADistance;
use App\Model\Strategie\CombatAuCorpsACorps;

class PersonnageFactory
{
    public function creerPersonnage(string $type, array $data): PersonnageInterface
    {
        $nom = $data['nom'];
        $stats = $data['stats'] ?? [];
        $categorie = $data['categorie'] ?? 'personnage';

        switch ($type) {
            case 'archer':
                return $this->creerArcher($nom, $stats);
            case 'guerrier':
            default:
                return $this->creerGuerrier($nom, $stats);
        }
    }

    public function creerGuerrier(string $nom, array $stats = []): PersonnageInterface
    {
        return new Guerrier($nom, new CombatAuCorpsACorps(), $stats);
    }

    public function creerArcher(string $nom, array $stats = []): PersonnageInterface
    {
        return new Archer($nom, new CombatADistance(), $stats);
    }

    public function creerAllie(string $nom, array $stats = []): PersonnageInterface
    {
        return $this->creerGuerrier($nom, $stats);
    }

    public function creerEnnemi(string $nom, array $stats = []): PersonnageInterface
    {
        return $this->creerArcher($nom, $stats);
    }
} 