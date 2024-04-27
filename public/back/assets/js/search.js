// Sélectionner le champ de recherche
const searchInput = document.getElementById('searchInput');

// Ajouter un événement de saisie pour détecter les changements dans le champ de recherche
searchInput.addEventListener('input', function() {
    // Récupérer la valeur saisie dans le champ de recherche
    const searchValue = this.value.trim();

    // Effectuer une requête AJAX si la valeur de recherche n'est pas vide
    if (searchValue !== '') {
        // Déclencher la requête AJAX
        fetch(`/search?email=${searchValue}`)
            .then(response => response.json())
            .then(data => {
                // Mettre à jour l'interface utilisateur avec les résultats de la recherche
                updateUI(data);
            })
            .catch(error => {
                console.error('Error fetching search results:', error);
            });
    }
});

// Fonction pour mettre à jour l'interface utilisateur avec les résultats de la recherche
function updateUI(data) {
    // Sélectionner le conteneur des résultats de la recherche
    const searchResultsContainer = document.getElementById('searchResults');

    // Vider le conteneur des résultats de la recherche
    searchResultsContainer.innerHTML = '';

    // Parcourir les résultats et les ajouter au conteneur
    data.forEach(result => {
        // Créer un élément de résultat
        const resultElement = document.createElement('div');
        resultElement.textContent = result.email;

        // Ajouter le résultat à la liste des résultats
        searchResultsContainer.appendChild(resultElement);
    });
}
