<?php

namespace App\Classes;

class ClasseFactory {
    private array $classes = [];
    private string $dataFile;

    public function __construct() {
        $this->dataFile = __DIR__ . '/../../data/classes.json';
        $this->loadClasses();
        if (empty($this->classes)) {
            $this->registerDefaultClasses();
            $this->saveClasses();
        }
    }

    private function loadClasses(): void {
        if (file_exists($this->dataFile)) {
            $data = file_get_contents($this->dataFile);
            $this->classes = json_decode($data, true) ?? [];
        }
    }

    private function saveClasses(): void {
        $dir = dirname($this->dataFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($this->dataFile, json_encode($this->classes, JSON_PRETTY_PRINT));
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

    public function saveClasse(array $classeData): void {
        // Génère un ID unique basé sur le nom
        $id = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $classeData['name']));
        
        // Vérifie si l'ID existe déjà et ajoute un suffixe si nécessaire
        $baseId = $id;
        $counter = 1;
        while (isset($this->classes[$id])) {
            $id = $baseId . $counter;
            $counter++;
        }

        // Ajoute la classe
        $this->classes[$id] = $classeData;
        
        // Sauvegarde dans le fichier
        $this->saveClasses();
    }
} 