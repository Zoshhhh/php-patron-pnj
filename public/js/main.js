document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const sortBy = document.getElementById('sortBy');
    const charactersGrid = document.querySelector('.characters-grid');

    function filterAndSortCharacters() {
        const searchTerm = searchInput.value.toLowerCase();
        const category = categoryFilter.value;
        const sortValue = sortBy.value;
        const cards = Array.from(document.querySelectorAll('.character-card'));

        cards.forEach(card => {
            const name = card.querySelector('.character-name').textContent.toLowerCase();
            const cardCategory = card.classList.contains(category);
            const shouldShow = (category === 'all' || cardCategory) && 
                             name.includes(searchTerm);
            card.classList.toggle('hidden', !shouldShow);
        });

        // Tri
        const visibleCards = cards.filter(card => !card.classList.contains('hidden'));
        visibleCards.sort((a, b) => {
            switch(sortValue) {
                case 'name':
                    return a.querySelector('.character-name').textContent
                        .localeCompare(b.querySelector('.character-name').textContent);
                case 'category':
                    return a.classList[1].localeCompare(b.classList[1]);
                case 'level':
                    const levelA = parseInt(a.querySelector('.character-info').textContent.match(/Niveau (\d+)/)[1]);
                    const levelB = parseInt(b.querySelector('.character-info').textContent.match(/Niveau (\d+)/)[1]);
                    return levelB - levelA;
                default:
                    return 0;
            }
        });

        visibleCards.forEach(card => charactersGrid.appendChild(card));
    }

    searchInput.addEventListener('input', filterAndSortCharacters);
    categoryFilter.addEventListener('change', filterAndSortCharacters);
    sortBy.addEventListener('change', filterAndSortCharacters);
}); 