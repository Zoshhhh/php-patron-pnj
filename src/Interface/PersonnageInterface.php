<?php

namespace App\Interface;

interface PersonnageInterface
{
    public function attaquer(PersonnageInterface $cible): void;
    public function recevoirDegats(int $degats): void;
    public function getNom(): string;
    public function getPointsDeVie(): int;
    public function estVivant(): bool;
} 