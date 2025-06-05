# Gestionnaire de PNJ avec Patrons de Conception

Ce projet est un gestionnaire de PNJ (Personnages Non Joueurs) utilisant plusieurs patrons de conception pour une architecture propre et maintenable.

## 🏗 Architecture

```
src/
├── Factory/         # Patrons Fabrication
├── Interface/       # Contrats
└── Model/           # Classes métier
    ├── Item/
    ├── Personnage/
    └── Strategie/
```

## 🎨 Patrons de Conception Utilisés

### 1. Patron Fabrication (Factory)
**Où :** `src/Factory/`
- `PersonnageFactory.php`
- `ItemFactory.php`
- `ClasseFactory.php`

**Pourquoi :** 
- Encapsule la logique de création des objets
- Permet de changer facilement l'implémentation des objets créés
- Centralise la création des objets complexes

**Comment :**
```php
// Exemple avec PersonnageFactory
$factory = new PersonnageFactory();
$guerrier = $factory->creerGuerrier("Aragorn");
$archer = $factory->creerArcher("Legolas");
```

### 2. Patron Stratégie (Strategy)
**Où :** `src/Model/Strategie/`
- `ComportementCombatInterface.php`
- `CombatADistance.php`
- `CombatAuCorpsACorps.php`

**Pourquoi :**
- Permet de changer dynamiquement le comportement d'un personnage
- Isole les différents algorithmes de combat
- Facilite l'ajout de nouveaux comportements

**Comment :**
```php
class Archer extends PersonnageAbstrait {
    public function __construct(string $nom) {
        parent::__construct($nom, new CombatADistance());
    }
}
```

### 3. Patron Composite
**Où :** `src/Model/Item/`
- `ItemInterface.php`
- `Item.php` (classe de base)
- Classes spécialisées : `CombatItem`, `ConsumableItem`, `EquipmentItem`

**Pourquoi :**
- Structure hiérarchique claire des items
- Traitement uniforme des différents types d'items
- Extensibilité facile pour nouveaux types d'items

**Comment :**
```php
// Tous les items partagent la même interface
$item->getNom();
$item->getDescription();
$item->getType();
```

## 🚀 Comment utiliser

1. **Créer un personnage :**
```php
$factory = new PersonnageFactory();
$personnage = $factory->creerPersonnage('guerrier', [
    'nom' => 'Aragorn',
    'stats' => [
        'force' => 15,
        'dexterite' => 14
    ]
]);
```

2. **Créer un item :**
```php
$factory = new ItemFactory();
$item = $factory->createItem('epee_courte');
```

3. **Gérer les classes :**
```php
$factory = new ClasseFactory();
$classe = $factory->createClasse('guerrier');
```

4. **Système d'attaque :**
Le système d'attaque fournit une API REST simple pour récupérer les données des personnages impliqués :

```javascript
// Exemple de requête d'attaque
fetch('/actions/personnage/attack.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        attacker: "0", // ID du personnage attaquant
        target: "1"    // ID du personnage cible
    })
})
.then(response => response.json())
.then(data => {
    console.log('Attaquant:', data.attacker); // Données complètes du personnage attaquant
    console.log('Cible:', data.target);       // Données complètes du personnage cible
});
```

Réponse JSON :
```json
{
    "success": true,
    "attacker": {
        "nom": "Aragorn",
        "classe": "guerrier",
        "categorie": "personnage",
        "stats": {
            "force": 15,
            "dexterite": 14,
            "pointsDeVie": 20
        }
    },
    "target": {
        "nom": "Gobelin",
        "classe": "guerrier",
        "categorie": "ennemi",
        "stats": {
            "force": 12,
            "dexterite": 12,
            "pointsDeVie": 15
        }
    }
}
```

## 🌟 Points forts de l'architecture

1. **Extensibilité :**
   - Facile d'ajouter de nouveaux types de personnages
   - Facile d'ajouter de nouveaux comportements de combat
   - Facile d'ajouter de nouveaux types d'items

2. **Maintenabilité :**
   - Séparation claire des responsabilités
   - Code organisé par domaine
   - Interfaces bien définies

3. **Réutilisabilité :**
   - Les comportements sont interchangeables
   - Les factories sont réutilisables
   - Les interfaces garantissent la cohérence

## 📁 Structure des dossiers

```
.
├── public/                # Interface utilisateur
│   ├── actions/           # Points d'entrée des actions
│   ├── css/               # Styles
│   └── views/             # Templates PHP
│
└── src/                   # Code source
    ├── Factory/           # Création d'objets
    ├── Interface/         # Contrats
    └── Model/             # Classes métier
        ├── Item/          # Gestion des items
        ├── Personnage/    # Gestion des personnages
        └── Strategie/     # Comportements de combat
```

## 🔄 Flux de données

1. L'utilisateur interagit avec les vues (`public/views/`)
2. Les actions (`public/actions/`) reçoivent les requêtes
3. Les factories (`src/Factory/`) créent les objets nécessaires
4. Les modèles (`src/Model/`) gèrent la logique métier
5. Les stratégies (`src/Model/Strategie/`) définissent les comportements

## 🛠 Installation

1. Cloner le repository
2. Installer les dépendances : `composer install`
3. Configurer le serveur web pour pointer vers le dossier `public/`
4. Accéder à l'application via le navigateur

## 🤝 Contribution

1. Fork le projet
2. Créer une branche pour votre fonctionnalité
3. Commiter vos changements
4. Pousser vers la branche
5. Créer une Pull Request

## 📝 Notes

- Les patrons de conception sont utilisés de manière pragmatique
- L'architecture privilégie la clarté et la maintenabilité
- Le code suit les principes SOLID
