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
                'nom' => 'Guerrier',
                'description' => 'Maître du combat rapproché, expert en armes et en armures lourdes.',
                'stats_base' => [
                    'force' => 15,
                    'dexterite' => 12,
                    'constitution' => 14,
                    'intelligence' => 8,
                    'sagesse' => 10,
                    'charisme' => 10
                ],
                'competences' => ['Athlétisme', 'Intimidation', 'Corps à corps'],
                'equipement_initial' => ['Épée longue', 'Bouclier', 'Cotte de mailles']
            ],
            'archer' => [
                'nom' => 'Archer',
                'description' => 'Expert du combat à distance, maître de la précision et de la mobilité.',
                'stats_base' => [
                    'force' => 10,
                    'dexterite' => 15,
                    'constitution' => 12,
                    'intelligence' => 10,
                    'sagesse' => 14,
                    'charisme' => 8
                ],
                'competences' => ['Acrobaties', 'Furtivité', 'Tir à l\'arc'],
                'equipement_initial' => ['Arc long', 'Carquois', 'Dague', 'Armure de cuir']
            ],
            'mage' => [
                'nom' => 'Mage',
                'description' => 'Manipulateur des arcanes, capable de lancer des sorts dévastateurs.',
                'stats_base' => [
                    'force' => 8,
                    'dexterite' => 10,
                    'constitution' => 12,
                    'intelligence' => 15,
                    'sagesse' => 14,
                    'charisme' => 10
                ],
                'competences' => ['Arcanes', 'Histoire', 'Investigation'],
                'equipement_initial' => ['Bâton', 'Grimoire', 'Robe de mage']
            ]
        ];
    }

    public function getAvailableClasses(): array {
        return array_map(function($class) {
            return [
                'id' => array_search($class, $this->classes),
                'nom' => $class['nom'],
                'description' => $class['description']
            ];
        }, $this->classes);
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