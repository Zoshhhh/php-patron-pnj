<?php

namespace App\Personnage;

use App\Strategie\ComportementCombatInterface;

abstract class PersonnageAbstrait implements PersonnageInterface
{
    protected string $nom;
    protected int $pointsDeVie;
    protected int $force;
    protected int $dexterite;
    protected ComportementCombatInterface $comportementCombat;

    public function __construct(
        string $nom,
        int $pointsDeVie,
        int $force,
        int $dexterite,
        ComportementCombatInterface $comportementCombat
    ) {
        $this->nom = $nom;
        $this->pointsDeVie = $pointsDeVie;
        $this->force = $force;
        $this->dexterite = $dexterite;
        $this->comportementCombat = $comportementCombat;
    }

    public function attaquer(PersonnageInterface $cible): void
    {
        $this->comportementCombat->attaquer($this, $cible);
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

    public function setComportementCombat(ComportementCombatInterface $comportementCombat): void
    {
        $this->comportementCombat = $comportementCombat;
    }
} 