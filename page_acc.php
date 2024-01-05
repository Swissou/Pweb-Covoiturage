<?php
//include 'connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>



    <title>Page d'accueil - Covoiturage</title>
    <style>
        /* Styles CSS inchangés pour la simplicité */

        body {
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #d3d0e5;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 1em;
            text-align: center;
			background: url('imagecov.png')no-repeat;
			background-position: right;
			background-size:200px;
        }
		h1{
			
			color: #333; 
			text-align: left; 
		}
        nav {
			
            background-color: #000c89;
            padding: 0.5em;
            text-align: center;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            padding: 0.5em 1em;
            margin: 0 1em;
        }

        #rideList {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding: 20px;
        }

        .ride-card {
		
            width: 300px;
            margin: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 15px;
            background-color: #00071d;
			color:#d0d7ff;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
			
        }
		
		button {
			padding: 5px 10px;
			margin:5px;
			font-size: 12px;
			cursor: pointer;
			background-color: #68a0f1; 
			color: #fff;
			border: none; 
			border-radius: 15px; 
			transition: background-color 0.5s;
			
			
			
}

		button:hover {
			background-color: #000c89; 
}
		/* Styles pour le popup */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
		#map {
            height: 300px;
            width: 400px; 
        }
        
    </style>
</head>
<body>
    <header>
        <h1>Covoiturage</h1>
    </header>

    <nav>
        <a href="page_acc.php">Accueil</a>
        <a href="recherche.php">Rechercher un trajet</a>
        <a href="#">Proposer un trajet</a>
        <a href="interface_connexion.php">Connexion</a>
        <a href="#">Inscription</a>
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
				echo "<button onclick='reserveRide(${row["id"]})'>Réserver</button>";
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
            alert(`Trajet ${rideId} réservé !`);
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
