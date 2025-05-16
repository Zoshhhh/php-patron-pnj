<?php

namespace App\Item;

class ItemFactory
{
    public function createCombatItem(string $name, string $description, int $value, int $damage, int $durability): CombatItem
    {
        return new CombatItem($name, $description, $value, $damage, $durability);
    }

    public function createConsumableItem(string $name, string $description, int $value, int $healAmount, bool $isStackable = true): ConsumableItem
    {
        return new ConsumableItem($name, $description, $value, $healAmount, $isStackable);
    }

    public function createEquipmentItem(string $name, string $description, int $value, int $defense, string $slot): EquipmentItem
    {
        return new EquipmentItem($name, $description, $value, $defense, $slot);
    }
} 