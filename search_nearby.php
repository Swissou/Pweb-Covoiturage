<?php

// connexion à la base de données 
include_once "connexion_bd.php";

if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

if (isset($_GET['userLat']) && isset($_GET['userLng'])) {
    $userLat = $_GET['userLat'];
    $userLng = $_GET['userLng'];

    // Utilisez la formule de Haversine pour calculer la distance
    $sql = "SELECT *,
                (6371 * acos(
                    cos(radians($userLat)) * cos(radians(lieu_depart_latitude)) * cos(radians(lieu_depart_longitude) - radians($userLng)) +
                    sin(radians($userLat)) * sin(radians(lieu_depart_latitude))
                )) AS distance_lieu_depart,
                (6371 * acos(
                    cos(radians($userLat)) * cos(radians(destination_latitude)) * cos(radians(destination_longitude) - radians($userLng)) +
                    sin(radians($userLat)) * sin(radians(destination_latitude))
                )) AS distance_destination
            FROM trajets
            HAVING distance_lieu_depart < 50 OR distance_destination < 50
            ORDER BY distance_lieu_depart, distance_destination";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='ride-card'>";
            echo "<p><strong>Lieu de départ:</strong> " . $row["lieu_depart"] . "</p>";
            echo "<p><strong>Destination:</strong> " . $row["destination"] . "</p>";
            echo "<p><strong>Heure de départ:</strong> " . $row["heure_depart"] . "</p>";
            echo "<button onclick='reserveRide(${row["id"]})'>Réserver</button>";
            echo "<button onclick='showMap(${row["lieu_depart_latitude"]}, ${row["lieu_depart_longitude"]}, ${row["destination_latitude"]}, ${row["destination_longitude"]})'>Voir les détails</button>";
            echo "</div>";
        }
    } else {
        echo "Aucun trajet à proximité.";
    }
} else {
    echo "Position de l'utilisateur non fournie.";
}

$conn->close();
?>
<script>
    var map; // Déclarer la variable de carte en dehors de la fonction showMap

function showMap(lieuDepartLat, lieuDepartLng, destLat, destLng) {
    // Détruire la carte existante si elle a été initialisée
    if (map) {
        map.remove();
    }

    // Afficher la popup avec la carte
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('popup').style.display = 'block';

    // Création de la carte
    map = L.map('map').fitBounds([
        [lieuDepartLat, lieuDepartLng],
        [destLat, destLng]
    ]);

    // Ajout d'une couche de tuiles OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Ajout de marqueurs pour les positions
    L.marker([lieuDepartLat, lieuDepartLng]).addTo(map)
        .bindPopup('Lieu de départ');

    L.marker([destLat, destLng]).addTo(map)
        .bindPopup('Destination');

    // Ajout d'une ligne reliant les deux positions
    var polyline = L.polyline([
        [lieuDepartLat, lieuDepartLng],
        [destLat, destLng]
    ], { color: 'blue' }).addTo(map);
}

function hidePopup() {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('popup').style.display = 'none';
}

// Fonction de réservation de trajet
        function reserveRide(rideId) {
            alert(`Trajet ${rideId} réservé !`);
        }
</script>