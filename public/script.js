document.addEventListener('DOMContentLoaded', (event) => {
    // Initialisation de la carte centrée sur Paris
    var map = L.map('map').setView([48.8566, 2.3522], 16);

    // Ajout des tuiles OpenStreetMap à la carte
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    // Déclaration des variables pour les marqueurs de départ et de destination
    let departureMarker;
    let destinationMarker;
    let routingControl; // Variable pour garder une référence au contrôle de l'itinéraire

    // Récupération des éléments d'interface pour les entrées de texte et les suggestions
    let departureInput = document.getElementById('departure');
    let destinationInput = document.getElementById('destination');
    let departureSuggestionsList = document.getElementById('departure-suggestions');
    let destinationSuggestionsList = document.getElementById('destination-suggestions');


    // Ajouter des écouteurs pour les changements de valeur
    departureInput.addEventListener('change', () => handleMarkerRemoval(departureInput, 'departure'));// enlever le marqueur si la valeur est vide
    destinationInput.addEventListener('change', () => handleMarkerRemoval(destinationInput, 'destination'));//

    function handleMarkerRemoval(inputElement, type) {
        //trim => permet de supprimer les espaces avant et après la valeur
        if (inputElement.value.trim() === '') {
            if (type === 'departure' && departureMarker) {
                map.removeLayer(departureMarker);
                departureMarker = null;
            } else if (type === 'destination' && destinationMarker) {
                map.removeLayer(destinationMarker);
                destinationMarker = null;
            }
            if (routingControl) {// Si le contrôle d'itinéraire existe
                map.removeControl(routingControl);
                routingControl = null;
            }
        }
    }

    // Fonction pour ajouter des suggestions dans la liste
    function addSuggestions(inputElement, suggestionsElement, data, isDeparture) {
        // Vider les suggestions précédentes
        suggestionsElement.innerHTML = '';
        // Ajouter les nouvelles suggestions
        data.forEach(item => {
            const li = document.createElement('li');
            li.textContent = item.display_name;
            li.addEventListener('click', function() {
                inputElement.value = item.display_name;
                suggestionsElement.innerHTML = ''; // Effacer les suggestions

                // Ajouter un marqueur à la carte
                const latLng = [item.lat, item.lon];
                if (isDeparture) {
                    if (departureMarker) map.removeLayer(departureMarker);
                    departureMarker = L.marker(latLng).addTo(map);
                    map.flyTo(latLng, 15); // Centrer la carte sur le nouveau marqueur
                } else {
                    if (destinationMarker) map.removeLayer(destinationMarker);
                    destinationMarker = L.marker(latLng).addTo(map);
                }

                // Calculer l'itinéraire si les deux marqueurs sont définis
                if (departureMarker && destinationMarker) {
                    calculateRoute(departureMarker.getLatLng(), destinationMarker.getLatLng());
                }
            });
            suggestionsElement.appendChild(li);
        });
    }

    // Écouteurs d'événements pour les entrées de texte de départ et de destination
    departureInput.addEventListener('input', function() {
        handleInput(this, departureSuggestionsList, true);
    });

    destinationInput.addEventListener('input', function() {
        handleInput(this, destinationSuggestionsList, false);
    });

    // Fonction pour gérer les entrées de texte et afficher les suggestions
    function handleInput(inputElement, suggestionsElement, isDeparture) {
        const query = inputElement.value;
        if (query.length > 2) {
            fetch(`https://nominatim.openstreetmap.org/search?q=${query}&format=json`)
                .then(response => response.json())
                .then(data => {
                    addSuggestions(inputElement, suggestionsElement, data, isDeparture);
                });
        }
    }

    // Fonction pour calculer et afficher l'itinéraire
    function calculateRoute(start, end) {
        if (routingControl) {
            map.removeControl(routingControl); // Supprimez l'ancien contrôle de l'itinéraire
        }
        routingControl = L.Routing.control({
            waypoints: [
                L.latLng(start),
                L.latLng(end)
            ],
            routeWhileDragging: true,
            createMarker: function() { return null; } // Désactiver les marqueurs par défaut
        }).on('routesfound', function(e) {
            var routes = e.routes;
            var summary = routes[0].summary;

            // Convertir le temps total de secondes en heures et minutes
            var totalTime = summary.totalTime;
            var hours = Math.floor(totalTime / 3600);
            var minutes = Math.floor((totalTime % 3600) / 60);

            // Mettre à jour l'élément des étapes de l'itinéraire avec la distance et le temps formatés
            //toFixed(nb de chiffres apres la vergule)
            document.getElementById('itinerary-steps').innerHTML = `
                <div>Distance : ${(summary.totalDistance / 1000).toFixed(1)} km</div> 
                <div>Temps de trajet estimé : ${hours} heures et ${minutes} minutes</div>
            `;

            // Ajuster la carte pour afficher les deux marqueurs avec une marge
            var group = L.featureGroup([departureMarker, destinationMarker]);
            map.fitBounds(group.getBounds(), { padding: [50, 50] }); 
        }).addTo(map);
    }
    let tarifTest = 0.5;
    let calculatedValue = ((summary.totalDistance / 1000) * tarifTest).toFixed(1);
    console.log(calculatedValue);  // Vérifiez cette sortie dans la console du navigateur
    document.getElementById('chauffeurCard_btn').innerHTML = `<a href=''>${calculatedValue}</a>`;
    
});
