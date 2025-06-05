<?php

namespace App\Factory;

use App\Interface\ItemInterface;
use App\Model\Item\Item;

class ItemFactory
{
    private array $items = [];

    public function __construct()
    {
        $this->registerDefaultItems();
    }

    private function registerDefaultItems(): void
    {
        $this->items = [
            'epee_courte' => [
                'nom' => 'Épée courte',
                'description' => 'Une épée courte bien équilibrée',
                'type' => 'Arme',
                'rarete' => 'Commun',
                'poids' => 2.0,
                'valeur' => 10,
                'effets' => [
                    'degats' => '1d6',
                    'type_degats' => 'perforant'
                ]
            ],
            'potion_soin' => [
                'nom' => 'Potion de soin',
                'description' => 'Une potion qui restaure les points de vie',
                'type' => 'Consommable',
                'rarete' => 'Commun',
                'poids' => 0.5,
                'valeur' => 50,
                'effets' => [
                    'soin' => '2d4+2'
                ]
            ]
        ];
    }

    public function getAvailableItems(): array
    {
        return array_map(function($item) {
            return [
                'id' => array_search($item, $this->items),
                'nom' => $item['nom'],
                'description' => $item['description'],
                'type' => $item['type']
            ];
        }, $this->items);
    }

    public function getItemDetails(string $itemId): ?array
    {
        return $this->items[$itemId] ?? null;
    }

    public function createItem(string $itemId): ?array
    {
        if (!isset($this->items[$itemId])) {
            return null;
        }

        return $this->items[$itemId];
    }

    public function addCustomItem(array $itemData): string
    {
        $id = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $itemData['nom']));
        $this->items[$id] = $itemData;
        return $id;
    }
} 