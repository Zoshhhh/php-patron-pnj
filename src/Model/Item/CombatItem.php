<?php

namespace App\Model\Item;

class CombatItem extends Item
{
    private int $degats;
    private int $durabilite;

    public function __construct(string $nom, string $description, int $valeur, int $degats, int $durabilite = 100)
    {
        parent::__construct($nom, $description, $valeur);
        $this->type = 'combat';
        $this->degats = $degats;
        $this->durabilite = $durabilite;
    }

    public function getDegats(): int
    {
        return $this->degats;
    }

    public function getDurabilite(): int
    {
        return $this->durabilite;
    }

    public function setDurabilite(int $durabilite): void
    {
        $this->durabilite = max(0, min(100, $durabilite));
    }

    public function utiliser(): void
    {
        $this->durabilite = max(0, $this->durabilite - 5);
    }

    public function estUtilisable(): bool
    {
        return $this->durabilite > 0;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'degats' => $this->degats,
            'durabilite' => $this->durabilite
        ]);
    }
} 