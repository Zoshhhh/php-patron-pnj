<?php

namespace App\Model\Personnage;

use App\Interface\PersonnageInterface;
use App\Interface\ComportementCombatInterface;

abstract class PersonnageAbstrait implements PersonnageInterface
{
    protected string $nom;
    protected int $pointsDeVie;
    protected int $force;
    protected int $dexterite;
    protected int $constitution;
    protected int $intelligence;
    protected int $sagesse;
    protected int $charisme;
    protected int $classeArmure;
    protected int $vitesse;
    protected ComportementCombatInterface $comportementCombat;

    public function __construct(string $nom, ComportementCombatInterface $comportementCombat)
    {
        $this->nom = $nom;
        $this->comportementCombat = $comportementCombat;
    }

    public function attaquer(PersonnageInterface $cible): void
    {
        $degats = $this->comportementCombat->attaquer();
        $cible->recevoirDegats($degats);
    }

    public function recevoirDegats(int $degats): void
    {
        $this->pointsDeVie = max(0, $this->pointsDeVie - $degats);
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPointsDeVie(): int
    {
        return $this->pointsDeVie;
    }

    public function estVivant(): bool
    {
        return $this->pointsDeVie > 0;
    }

    public function getForce(): int
    {
        return $this->force;
    }

    public function getDexterite(): int
    {
        return $this->dexterite;
    }

    public function getConstitution(): int
    {
        return $this->constitution;
    }

    public function getIntelligence(): int
    {
        return $this->intelligence;
    }

    public function getSagesse(): int
    {
        return $this->sagesse;
    }

    public function getCharisme(): int
    {
        return $this->charisme;
    }

    public function getClasseArmure(): int
    {
        return $this->classeArmure;
    }

    public function getVitesse(): int
    {
        return $this->vitesse;
    }

    public function getModificateur(int $valeurCaracteristique): int
    {
        return (int) floor(($valeurCaracteristique - 10) / 2);
    }
} 