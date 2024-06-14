// Écouteur d'événement pour le chargement du contenu du document
document.addEventListener('DOMContentLoaded', (event) => {
    // Initialisation de la carte avec une vue centrée sur les coordonnées spécifiées
    var map = L.map('map').setView([51.505, -0.09], 13);

    // Ajout d'une couche de tuiles OpenStreetMap à la carte
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    // Récupération des éléments de l'interface utilisateur pour les entrées de départ et de destination
    let departureInput = document.getElementById('departure');
    let arrivalInput = document.getElementById('destination');
    let suggestionsList = document.getElementById('departure-suggestions');

    // Ajout d'un écouteur d'événement pour l'entrée de départ
    departureInput.addEventListener('input', function() {
        const query = departureInput.value;
        // Vérification que la requête a plus de 2 caractères
        if (query.length > 2) {
            // Effectuer une requête à l'API Nominatim pour rechercher des emplacements
            fetch(`https://nominatim.openstreetmap.org/search?q=${query}&format=json`)
                .then(response => response.json())
                .then(data => {
                    // Réinitialiser la liste de suggestions
                    suggestionsList.innerHTML = '';
                    // Parcourir les résultats de la recherche et ajouter des options à la liste de suggestions
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.textContent = item.display_name;
                        // Ajouter un écouteur d'événement pour chaque option de suggestion
                        option.addEventListener('click', function() {
                            departureInput.value = item.display_name;
                            suggestionsList.innerHTML = ''; // Effacer la liste de suggestions
                        });
                        // Ajouter l'option à la liste de suggestions
                        suggestionsList.appendChild(option);
                    });
                });
        }
    });
});

// Fonction pour ajouter un marqueur à la carte
function addMarker(lat, lon) {
    var marker = L.marker([lat, lon]).addTo(map);
    map.setView([lat, lon], 13); // Ajuster la vue de la carte au nouveau marqueur
}
