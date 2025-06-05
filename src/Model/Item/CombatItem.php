<?php

namespace App\Item;

class CombatItem extends AbstractItem
{
    private int $damage;
    private int $durability;

    public function __construct(string $name, string $description, int $value, int $damage, int $durability)
    {
        parent::__construct($name, $description, $value);
        $this->type = 'combat';
        $this->damage = $damage;
        $this->durability = $durability;
    }

    public function getDamage(): int
    {
        return $this->damage;
    }

    public function getDurability(): int
    {
        return $this->durability;
    }
} 