<?php

define('ROOT_PATH', __DIR__);
define('PUBLIC_PATH', __DIR__ . '/public');
define('VIEWS_PATH', __DIR__ . '/public/views');

define('ITEM_TYPES', [
    'combat' => [
        'name' => 'Combat',
        'icon' => '⚔️',
        'fields' => ['damage', 'durability']
    ],
    'consommable' => [
        'name' => 'Consommable',
        'icon' => '🧪',
        'fields' => ['healAmount', 'isStackable']
    ],
    'equipement' => [
        'name' => 'Équipement',
        'icon' => '🛡️',
        'fields' => ['defense', 'slot']
    ]
]);

define('EQUIPMENT_SLOTS', [
    'tete' => 'Tête',
    'torse' => 'Torse',
    'jambes' => 'Jambes',
    'pieds' => 'Pieds',
    'mains' => 'Mains'
]);

define('DEFAULT_ITEM_VALUES', [
    'damage' => 10,
    'durability' => 100,
    'healAmount' => 50,
    'isStackable' => true,
    'defense' => 5
]);

define('MAX_INVENTORY_SIZE', 20);
define('MAX_STACK_SIZE', 99);
define('MAX_DURABILITY', 1000);
define('MAX_DAMAGE', 100);
define('MAX_DEFENSE', 100);
define('MAX_HEAL_AMOUNT', 100);

define('ERROR_MESSAGES', [
    'item_creation_failed' => 'La création de l\'item a échoué',
    'invalid_item_type' => 'Type d\'item invalide',
    'invalid_slot' => 'Emplacement d\'équipement invalide',
    'inventory_full' => 'Inventaire plein',
    'invalid_value' => 'Valeur invalide'
]); 