<?php

namespace App\Personnage;

use App\Strategie\ComportementCombatInterface;

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

    public function __construct(
        string $nom,
        array $stats = []
    ) {
        $this->nom = $nom;
        $this->force = $stats['force'] ?? 10;
        $this->dexterite = $stats['dexterite'] ?? 10;
        $this->constitution = $stats['constitution'] ?? 10;
        $this->intelligence = $stats['intelligence'] ?? 10;
        $this->sagesse = $stats['sagesse'] ?? 10;
        $this->charisme = $stats['charisme'] ?? 10;
        $this->pointsDeVie = $stats['pointsDeVie'] ?? 10;
        $this->classeArmure = $stats['classeArmure'] ?? 10;
        $this->vitesse = $stats['vitesse'] ?? 30;
        $this->comportementCombat = $stats['comportementCombat'];
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

    public function getModificateur(int $valeur): int
    {
        return floor(($valeur - 10) / 2);
    }

    public function setComportementCombat(ComportementCombatInterface $comportementCombat): void
    {
        $this->comportementCombat = $comportementCombat;
    }
} 