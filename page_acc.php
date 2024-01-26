<?php 
    // demarage de la session
    session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="Style/page_acc.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>



    <title>Page d'accueil - Covoiturage</title>

</head>
<body>
    <header>
        <h1>Covoiturage</h1>
    </header>

    <nav>
        <a href="page_acc.php">Accueil</a>
        <a href="recherche.php">Rechercher un trajet</a>
        <a href="#">Proposer un trajet</a>
        <?php
    // Vérifiez si l'utilisateur est connecté
    if (isset($_SESSION["id_utilisateur"]) && $_SESSION["id_utilisateur"]){
        echo '<a href="mon_compte.php">Mon Compte</a>';
        echo '<a href="deconnexion.php">Déconnexion</a>';
    } else {
		echo '<a href="interface_inscription.php">Inscription</a>';
        echo '<a href="interface_connexion.php">Connexion</a>';
        
    }
    ?>
    </nav>
 <button onclick="searchNearby()">Trouver les trajets à proximité</button>

    <div id="rideList">
	<?php
		$servername = "localhost";   
		$username = "root";  
		$password = "";  
		$dbname = "projetpweb";  
		// Créer une connexion à la base de données
		$conn = new mysqli($servername, $username, $password, $dbname);

		// Vérifier la connexion
		if ($conn->connect_error) {
			die("Échec de la connexion à la base de données : " . $conn->connect_error);
		}

		// Définir le jeu de caractères de la connexion en utf8
		$conn->set_charset("utf8");
        // Récupération des trajets depuis la base de données
		
		/*CREATE TABLE trajets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lieu_depart VARCHAR(255) NOT NULL,
    destination VARCHAR(255) NOT NULL,
    heure_depart TIME,
    conducteur VARCHAR(255),
    places_disponibles INT,
    CONSTRAINT check_heure CHECK (heure_depart BETWEEN '00:00:00' AND '23:59:59')
);

ALTER TABLE trajets
ADD COLUMN lieu_depart_latitude DOUBLE,
ADD COLUMN lieu_depart_longitude DOUBLE,
ADD COLUMN destination_latitude DOUBLE,
ADD COLUMN destination_longitude DOUBLE;

*/
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
				echo "<button onclick='reserveRide(${row["id"]}, ${row["places_disponibles"]})'>Réserver</button>";
				 echo "<button onclick='showMap(${row["lieu_depart_latitude"]}, ${row["lieu_depart_longitude"]}, ${row["destination_latitude"]}, ${row["destination_longitude"]})'>Voir les détails</button>";
				echo "</div>";
               
            }
        } else {
            echo "Aucun trajet disponible.";
        }

        // Fermeture de la connexion à la base de données
       // $conn->close();

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
