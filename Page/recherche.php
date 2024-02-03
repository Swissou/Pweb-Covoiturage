<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="Public/Style/recherche.css">
    <title>Recherche de Trajet - Covoiturage</title>
</head>
<body>

<?php include 'navbar.php';?>

<div class="test">
    <div id="searchForm">
        <h2>Formulaire de Recherche de Trajet</h2>
        <form method="post">
            <label for="departure">Lieu de départ:</label>
            <input type="text" id="departure" name="departure" >

            <label for="destination">Destination:</label>
            <input type="text" id="destination" name="destination" >

            <label for="departureTime">Heure de départ:</label>
            <input type="time" id="departureTime" name="departureTime">

            <button type="submit">Rechercher</button>
        </form>
    </div>
</div>

<div id="rideList">

<?php

//connexion à la base de données
include_once "connexion_bd.php";


// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $departure = $_POST["departure"];
    $destination = $_POST["destination"];
    $departureTime = $_POST["departureTime"];

    // Construire la requête SQL avec des conditions de recherche
    $sql = "SELECT * FROM trajets WHERE lieu_depart LIKE '%$departure%' AND destination LIKE '%$destination%'";
    
    if ($departureTime != "") {
        $sql .= " AND heure_depart = '$departureTime'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='ride-card'>";
            echo "<p><strong>Lieu de départ:</strong> " . $row["lieu_depart"] . "</p>";
            echo "<p><strong>Destination:</strong> " . $row["destination"] . "</p>";
            echo "<p><strong>Heure de départ:</strong> " . $row["heure_depart"] . "</p>";
            echo "<button onclick='reserveRide({$row["id"]})'>Réserver</button>";
            echo "<button onclick='showMap({$row["lieu_depart_latitude"]}, {$row["lieu_depart_longitude"]}, {$row["destination_latitude"]}, {$row["destination_longitude"]})'>Voir les détails</button>";
            echo "</div>";
        }
    } else {
        echo "Aucun trajet disponible.";
    }

    $conn->close();
}
?>

<div class="overlay" id="overlay"></div>
<div class="popup" id="popup">
    <div id="map"></div>
    <button onclick="hidePopup()">Fermer</button>
</div>

<script>
          var map;

 function showMap(lieuDepartLat, lieuDepartLng, destLat, destLng) {
    if (map) {
        map.remove();
    }

    document.getElementById('overlay').style.display = 'block';
    document.getElementById('popup').style.display = 'block';

    map = L.map('map').setView([lieuDepartLat, lieuDepartLng], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    L.Routing.control({
        waypoints: [
            L.latLng(lieuDepartLat, lieuDepartLng),
            L.latLng(destLat, destLng)
        ],
        routeWhileDragging: true,
        show: false
    }).addTo(map);

    L.marker([lieuDepartLat, lieuDepartLng]).addTo(map)
        .bindPopup('Lieu de départ');

    L.marker([destLat, destLng]).addTo(map)
        .bindPopup('Destination');

    // Ajout de la ligne suivante pour forcer le recalcul des dimensions de la carte
    map.invalidateSize();

    // Supprimer ou ajuster le code suivant pour masquer ou personnaliser la description de l'itinéraire
    var routeDescription = document.createElement("div");
    routeDescription.innerHTML = "Votre itinéraire détaillé ici.";
    document.getElementById('map').appendChild(routeDescription);
}


        function hidePopup() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('popup').style.display = 'none';
        }
///reservation
        function reserveRide(rideId) {
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert(xhr.responseText);
                    location.reload();
                }
            };

            xhr.open("POST", "reserve_ride.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send(`rideId=${rideId}`);
        }
///proximité
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

    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
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
