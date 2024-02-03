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
    // ... (votre code JavaScript reste inchangé)
</script>

</body>
</html>
