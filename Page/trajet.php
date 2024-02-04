<?php

//connexion à la base de données 
include_once "connexion_bd.php";

$opencageApiKey = "77207f924a3f4ec8ae0bda69a9258e27"; 

$message = "";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $departure = $_POST["departure"];
    $destination = $_POST["destination"];
    $departureTime = $_POST["departureTime"];
    $driver = $_POST["driver"];
    $availableSeats = $_POST["availableSeats"];

    // Utiliser l'API OpenCage pour obtenir les coordonnées de la destination
    $geocodeUrl = "https://api.opencagedata.com/geocode/v1/json?q=" . urlencode($destination) . "&key=$opencageApiKey";
    $geocodeResponse = json_decode(file_get_contents($geocodeUrl), true);

    if ($geocodeResponse && $geocodeResponse["results"] && count($geocodeResponse["results"]) > 0) {
        $latitude_d = $geocodeResponse["results"][0]["geometry"]["lat"];
        $longitude_d = $geocodeResponse["results"][0]["geometry"]["lng"];

        // Utiliser l'API OpenCage pour obtenir les coordonnées du lieu de départ
        $geocodeDepartureUrl = "https://api.opencagedata.com/geocode/v1/json?q=" . urlencode($departure) . "&key=$opencageApiKey";
        $geocodeDepartureResponse = json_decode(file_get_contents($geocodeDepartureUrl), true);

        if ($geocodeDepartureResponse && $geocodeDepartureResponse["results"] && count($geocodeDepartureResponse["results"]) > 0) {
            $latitude = $geocodeDepartureResponse["results"][0]["geometry"]["lat"];
            $longitude = $geocodeDepartureResponse["results"][0]["geometry"]["lng"];

            // Construire la requête SQL pour l'insertion du trajet proposé
            $sql = "INSERT INTO trajets (lieu_depart, destination, heure_depart, conducteur, places_disponibles, lieu_depart_latitude, lieu_depart_longitude, destination_latitude, destination_longitude) 
                    VALUES ('$departure', '$destination', '$departureTime', '$driver', '$availableSeats', '$latitude', '$longitude', '$latitude_d', '$longitude_d')";

          if ($conn->query($sql) === TRUE) {
                $message = "Trajet proposé avec succès.";
            } else {
                $message ="Erreur lors de la proposition du trajet : " . $conn->error;
            }
        } else {
            $message ="Erreur lors de la récupération des coordonnées du lieu de départ.";
        }
    } else {
        $message ="Erreur lors de la récupération des coordonnées de la destination.";
    }
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposition de Trajet - Covoiturage</title>
    <link rel="stylesheet" href="Public/Style/trajet.css">
    <head>


</head>
<body>
<header>
       
    </header>

    <?php include 'navbar.php';?>

 <div id="successMessage">
        <?php echo $message; ?>
    </div>


<div class="test">   
    <div id="searchForm">
    <h2>Formulaire de Proposition de Trajet</h2>
    <form method="post" onsubmit="return validateForm()">
        <label for="departure">Lieu de départ:</label>
        <input type="text" id="departure" name="departure" required>

        <button type="button" onclick="getCurrentLocation()">Obtenir ma géolocalisation</button>
        <span id="currentCity"></span>

        <label for="destination">Destination:</label>
        <input type="text" id="destination" name="destination" required>

        <label for="departureTime">Heure de départ:</label>
        <input type="time" id="departureTime" name="departureTime">

        <label for="driver">Conducteur:</label>
        <input type="text" id="driver" name="driver" required>

        <label for="availableSeats">Nombre de places disponibles:</label>
        <input type="number" id="availableSeats" name="availableSeats" required>

        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">

        <button type="submit">Proposer Trajet</button>
    </form>
</div> 


    <script>
        
        function validateForm() {
            var availableSeats = document.getElementById("availableSeats").value;

            if (availableSeats > 0 && availableSeats < 5) {
                return true; // Soumettre le formulaire si la condition est satisfaite
            } else {
                alert("Le nombre de places disponibles doit être supérieur à 0 et inférieur à 5.");
                return false; // Empêcher la soumission du formulaire
            }
        }
      // Fonction pour obtenir la position géographique actuelle
      function getCurrentLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            // Mettez à jour la valeur du champ "Lieu de départ" avec les coordonnées actuelles
                            document.getElementById('departure').value = position.coords.latitude + ', ' + position.coords.longitude;

                            // Ajoutez des champs cachés pour stocker les coordonnées dans le formulaire
                            // Ces champs seront inclus dans la soumission du formulaire
                            document.getElementById('latitude').value = position.coords.latitude;
                            document.getElementById('longitude').value = position.coords.longitude;

                            // Appel à la fonction pour obtenir la ville
                            getCityFromCoordinates(position.coords.latitude, position.coords.longitude);
                        },
                        function(error) {
                            console.error('Erreur de géolocalisation : ' + error.message);
                        }
                    );
                } else {
                    console.error('La géolocalisation n\'est pas prise en charge par ce navigateur.');
                }
            }

            // Fonction pour obtenir la ville à partir des coordonnées
            function getCityFromCoordinates(latitude, longitude) {
                // Utilisation de l'API OpenCage pour obtenir la ville
                var geocodeUrl = "https://api.opencagedata.com/geocode/v1/json?q=" + latitude + "+" + longitude + "&key=77207f924a3f4ec8ae0bda69a9258e27";
                fetch(geocodeUrl)
                    .then(response => response.json())
                    .then(data => {
                        // Mettez à jour le contenu de l'élément span avec la ville
                        var city = data.results[0].components.city;
                        document.getElementById('currentCity').innerText = "Ville actuelle : " + city;
                    })
                    .catch(error => {
                        console.error('Erreur lors de la récupération de la ville : ' + error.message);
                    });
            }



    </script>
 
</body>
</html>
