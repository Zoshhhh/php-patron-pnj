<?php

namespace App\Classes;

class ClasseFactory {
    private array $classes = [];

    public function __construct() {
        $this->registerDefaultClasses();
    }

    private function registerDefaultClasses(): void {
        $this->classes = [
            'guerrier' => [
                'name' => 'Guerrier',
                'type' => 'Combat',
                'description' => 'Maître du combat rapproché, expert en armes et en armures lourdes.',
                'baseHP' => 12,
                'hitDie' => 10,
                'baseAC' => 16,
                'proficiencies' => [
                    'Armes de corps à corps',
                    'Armures lourdes',
                    'Boucliers',
                    'Athlétisme',
                    'Intimidation'
                ],
                'stats' => [
                    'force' => 15,
                    'dexterite' => 12,
                    'constitution' => 14,
                    'intelligence' => 8,
                    'sagesse' => 10,
                    'charisme' => 10
                ]
            ],
            'archer' => [
                'name' => 'Archer',
                'type' => 'Distance',
                'description' => 'Expert du combat à distance, maître de la précision et de la mobilité.',
                'baseHP' => 10,
                'hitDie' => 8,
                'baseAC' => 14,
                'proficiencies' => [
                    'Armes à distance',
                    'Armures légères',
                    'Acrobaties',
                    'Furtivité',
                    'Perception'
                ],
                'stats' => [
                    'force' => 10,
                    'dexterite' => 15,
                    'constitution' => 12,
                    'intelligence' => 10,
                    'sagesse' => 14,
                    'charisme' => 8
                ]
            ],
            'mage' => [
                'name' => 'Mage',
                'type' => 'Arcanes',
                'description' => 'Manipulateur des arcanes, capable de lancer des sorts dévastateurs.',
                'baseHP' => 8,
                'hitDie' => 6,
                'baseAC' => 12,
                'proficiencies' => [
                    'Bâtons',
                    'Dagues',
                    'Arcanes',
                    'Histoire',
                    'Investigation'
                ],
                'stats' => [
                    'force' => 8,
                    'dexterite' => 10,
                    'constitution' => 12,
                    'intelligence' => 15,
                    'sagesse' => 14,
                    'charisme' => 10
                ]
            ]
        ];
    }

    public function getAvailableClasses(): array {
        return $this->classes;
    }

    public function getClassDetails(string $classeId): ?array {
        return $this->classes[$classeId] ?? null;
    }

    public function createClasse(string $classeId): ?array {
        if (!isset($this->classes[$classeId])) {
            return null;
        }

        return $this->classes[$classeId];
    }
} 