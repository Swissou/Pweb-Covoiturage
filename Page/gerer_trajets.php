<?php
//session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="Public/Style/gerer_trajets.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>



    <title>	Gérer Mes Trajets - Covoiturage</title>
    
</head>
<body>


<?php include 'navbar.php';?>

<div id="rideList">
	<?php


include_once "connexion_bd.php";



// Vérifier si l'utilisateur est connecté
if (isset($_SESSION["Id_Utilisateur"]) && $_SESSION["Id_Utilisateur"]) {
    $conducteurId = $_SESSION["Id_Utilisateur"];

    // Récupération des trajets du conducteur connecté depuis la base de données
    $sql = "SELECT * FROM trajets WHERE  id_utilisateur= $conducteurId";
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
            echo "<button onclick='modifierTrajet({$row["id"]})'>modifier</button>";
			echo "<button onclick='deleteRide({$row["id"]})'>supprimer</button>";
			echo "</div>";
        }
    } else {
        echo "Aucun trajet disponible.";
    }
} else {
    echo "Utilisateur non connecté.";
}

// Fermeture de la connexion à la base de données
$conn->close();
?>

	</div>
<script>
    function deleteRide(rideId) {
        if (confirm("Voulez-vous vraiment supprimer ce trajet?")) {
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert(xhr.responseText);
                    // Rafraîchir la liste des trajets après la suppression
                    location.reload();
                }
            };

            xhr.open("POST", "supprimer_trajet.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send(`rideId=${rideId}`);
        }
    }
	
	
function modifierTrajet(trajetId) {
    // Redirigez l'utilisateur vers la page de modification du trajet en utilisant le trajetId
    window.location.replace("modifier_trajet.php?trajetId=" + trajetId);
}


</script>

</body>
</html>