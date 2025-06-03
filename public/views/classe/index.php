<?php
require_once __DIR__ . '/../../../autoload.php';

use App\Classes\ClasseFactory;

session_start();

if (!isset($_SESSION['classes'])) {
    $_SESSION['classes'] = [];
}

$factory = new ClasseFactory();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Classes</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/cards.css">
</head>
<body>
    <div class="classes-list">
        <div class="header-content">
            <h1>Classes disponibles</h1>
            <div class="search-filters">
                <input type="text" id="searchInput" placeholder="Rechercher une classe...">
                <select id="sortBy">
                    <option value="name">Trier par nom</option>
                    <option value="type">Trier par type</option>
                </select>
            </div>
        </div>

        <div class="classes-grid">
            <?php foreach ($factory->getAvailableClasses() as $classe): ?>
                <div class="class-card">
                    <div class="class-header">
                        <h2><?= htmlspecialchars($classe['name']) ?></h2>
                        <span class="class-type"><?= htmlspecialchars($classe['type']) ?></span>
                    </div>
                    <div class="class-stats">
                        <div class="stat">
                            <span class="stat-label">PV de base</span>
                            <span class="stat-value"><?= $classe['baseHP'] ?></span>
                        </div>
                        <div class="stat">
                            <span class="stat-label">Dé de vie</span>
                            <span class="stat-value">d<?= $classe['hitDie'] ?></span>
                        </div>
                        <div class="stat">
                            <span class="stat-label">CA de base</span>
                            <span class="stat-value"><?= $classe['baseAC'] ?></span>
                        </div>
                    </div>
                    <div class="class-proficiencies">
                        <h3>Maîtrises</h3>
                        <ul>
                            <?php foreach ($classe['proficiencies'] as $prof): ?>
                                <li><?= htmlspecialchars($prof) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="class-description">
                        <?= htmlspecialchars($classe['description']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="actions">
            <a href="/views/personnage/create.php" class="button primary">Créer un personnage</a>
            <a href="/views/personnage/index.php" class="button">Retour</a>
        </div>
    </div>

    <style>
    .classes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        padding: 2rem;
    }

    .class-card {
        background: var(--card-bg);
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .class-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .class-header h2 {
        margin: 0;
        color: var(--primary-color);
    }

    .class-type {
        background: var(--accent-color);
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 0.9em;
    }

    .class-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat {
        text-align: center;
    }

    .stat-label {
        display: block;
        font-size: 0.8em;
        color: var(--text-muted);
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1.2em;
        font-weight: bold;
        color: var(--primary-color);
    }

    .class-proficiencies {
        margin-bottom: 1.5rem;
    }

    .class-proficiencies h3 {
        margin: 0 0 0.5rem 0;
        font-size: 1em;
        color: var(--text-muted);
    }

    .class-proficiencies ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .class-proficiencies li {
        background: var(--bg-light);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.9em;
    }

    .class-description {
        font-size: 0.9em;
        line-height: 1.5;
        color: var(--text-color);
    }

    .header-content {
        padding: 2rem;
        background: var(--bg-dark);
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .search-filters {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .search-filters input,
    .search-filters select {
        padding: 0.5rem;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        background: var(--bg-light);
        color: var(--text-color);
    }

    .search-filters input {
        flex: 1;
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const sortSelect = document.getElementById('sortBy');
        const classCards = document.querySelectorAll('.class-card');

        function updateDisplay() {
            const searchTerm = searchInput.value.toLowerCase();
            const sortBy = sortSelect.value;

            const cards = Array.from(classCards);

            // Filtrage
            cards.forEach(card => {
                const name = card.querySelector('h2').textContent.toLowerCase();
                const type = card.querySelector('.class-type').textContent.toLowerCase();
                const description = card.querySelector('.class-description').textContent.toLowerCase();
                
                const matches = name.includes(searchTerm) || 
                              type.includes(searchTerm) || 
                              description.includes(searchTerm);
                
                card.style.display = matches ? '' : 'none';
            });

            // Tri
            const visibleCards = cards.filter(card => card.style.display !== 'none');
            visibleCards.sort((a, b) => {
                if (sortBy === 'name') {
                    return a.querySelector('h2').textContent.localeCompare(
                        b.querySelector('h2').textContent
                    );
                } else {
                    return a.querySelector('.class-type').textContent.localeCompare(
                        b.querySelector('.class-type').textContent
                    );
                }
            });

            const container = document.querySelector('.classes-grid');
            visibleCards.forEach(card => container.appendChild(card));
        }

        searchInput.addEventListener('input', updateDisplay);
        sortSelect.addEventListener('change', updateDisplay);
    });
    </script>
</body>
</html> 