<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <link rel="stylesheet" href="Public/Style/page_acc.css">

    <title>Accueil</title>

</head>
<body>

    <?php include 'navbar.php';?>

<div class="test">
    <button onclick="searchNearby()">Trouver les trajets à proximité</button>
</div>

<div id="rideList">

<?php

    // connexion à la base de données
    include_once "connexion_bd.php";

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Échec de la connexion à la base de données : " . $conn->connect_error);
    }

    // Définir le jeu de caractères de la connexion en utf8
    $conn->set_charset("utf8");

    // Récupération des trajets depuis la base de données
    $sql = "SELECT * FROM trajets";
    $result = $conn->query($sql);

    // Affichage des trajets
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='ride-card'>";
            echo "<p><strong>Lieu de départ:</strong> " . $row["lieu_depart"] . "</p>";
            echo "<p><strong>Destination:</strong> " . $row["destination"] . "</p>";
            echo "<p><strong>Heure de départ:</strong> " . $row["heure_depart"] . "</p>";
            echo "<p><strong>Conducteur:</strong> " . $row["conducteur"] . "</p>";
            echo "<p><strong>Places disponibles:</strong> " . $row["places_disponibles"] . "</p>";
            echo "<button onclick='reserveRide({$row["id"]}, {$row["places_disponibles"]})'>Réserver</button>";
             echo "<button onclick='showMap({$row["lieu_depart_latitude"]}, {$row["lieu_depart_longitude"]}, {$row["destination_latitude"]}, {$row["destination_longitude"]})'>Voir les détails</button>";
            echo "</div>";
           
        }
    } else {
        echo "Aucun trajet disponible.";
    }

?>

</div>

<div class="overlay" id="overlay"></div>

<div class="popup" id="popup">
    <div id="map"></div>
    <button onclick="hidePopup()">Fermer</button>
</div>

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
        // Envoyer la réservation au serveur via AJAX
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert(xhr.responseText);
                // Rafraîchir la liste des trajets après la réservation
                location.reload();
            }
        };

        xhr.open("POST", "reserve_ride.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(`rideId=${rideId}`);
    }
    //proximite 

    function searchNearby() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
    } else {
        console.error("La géolocalisation n'est pas prise en charge par votre navigateur.");
    }
    }

    function successCallback(position) {
    const userLat = position.coords.latitude;
    const userLng = position.coords.longitude;

    // Envoyez la position de l'utilisateur au serveur via AJAX
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Mettez à jour la liste des trajets avec les résultats du serveur
            const rideList = document.getElementById('rideList');
            rideList.innerHTML = xhr.responseText;
        }
    };

    xhr.open("GET", `search_nearby.php?userLat=${userLat}&userLng=${userLng}`, true);
    xhr.send();
    }

    function errorCallback(error) {
    console.error("Erreur lors de la récupération de la position : ", error);
    }

</script>

</body>
</html>
