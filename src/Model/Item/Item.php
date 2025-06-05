<?php

namespace App\Model\Item;

use App\Interface\ItemInterface;

class Item implements ItemInterface
{
    protected string $nom;
    protected string $description;
    protected string $type;
    protected string $rarete;
    protected float $poids;
    protected int $valeur;
    protected array $effets;

    public function __construct(array $data)
    {
        $this->nom = $data['nom'];
        $this->description = $data['description'];
        $this->type = $data['type'];
        $this->rarete = $data['rarete'];
        $this->poids = $data['poids'];
        $this->valeur = $data['valeur'];
        $this->effets = $data['effets'];
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getRarete(): string
    {
        return $this->rarete;
    }

    public function getPoids(): float
    {
        return $this->poids;
    }

    public function getValeur(): int
    {
        return $this->valeur;
    }

    public function getEffets(): array
    {
        return $this->effets;
    }
} 