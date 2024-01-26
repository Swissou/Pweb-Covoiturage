<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="Style/recherche.css">
    <title>Recherche de Trajet - Covoiturage</title>

</head>
<body>
    <header>
        <h1>Recherche de Trajet</h1>
    </header>

    <nav>
        <a href="page_acc.php">Accueil</a>
        <a href="#">Rechercher un trajet</a>
        <a href="#">Proposer un trajet</a>
        <a href="#">Connexion</a>
        <a href="#">Inscription</a>
    </nav>

    <div id="searchForm">
        <h2>Formulaire de Recherche de Trajet</h2>
        <form method="post">
            <label for="departure">Lieu de départ:</label>
            <input type="text" id="departure" name="departure" required>

            <label for="destination">Destination:</label>
            <input type="text" id="destination" name="destination" required>

            <label for="departureTime">Heure de départ:</label>
            <input type="time" id="departureTime" name="departureTime">

            <button type="submit">Rechercher</button>
        </form>
    </div>
     <div id="rideList">
    
	
        <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projetpweb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $departure = $_POST["departure"];
    $destination = $_POST["destination"];
    $departureTime = $_POST["departureTime"];

    // Construire la requête SQL avec des conditions de recherche
    $sql = "SELECT * FROM trajets WHERE lieu_depart = '$departure' AND destination = '$destination' AND heure_depart = '$departureTime'";


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
    
	</div>
</body>
</html>
