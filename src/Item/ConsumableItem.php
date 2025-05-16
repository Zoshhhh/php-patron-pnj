<?php

namespace App\Item;

class ConsumableItem extends AbstractItem
{
    private int $healAmount;
    private bool $isStackable;

    public function __construct(string $name, string $description, int $value, int $healAmount, bool $isStackable = true)
    {
        parent::__construct($name, $description, $value);
        $this->type = 'consommable';
        $this->healAmount = $healAmount;
        $this->isStackable = $isStackable;
    }

    public function getHealAmount(): int
    {
        return $this->healAmount;
    }

    public function isStackable(): bool
    {
        return $this->isStackable;
    }
} 