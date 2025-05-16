<?php

namespace App\Item;

class EquipmentItem extends AbstractItem
{
    private int $defense;
    private string $slot;

    public function __construct(string $name, string $description, int $value, int $defense, string $slot)
    {
        parent::__construct($name, $description, $value);
        $this->type = 'equipement';
        $this->defense = $defense;
        $this->slot = $slot;
    }

    public function getDefense(): int
    {
        return $this->defense;
    }

    public function getSlot(): string
    {
        return $this->slot;
    }
} 