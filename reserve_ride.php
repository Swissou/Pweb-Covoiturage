<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rideId = $_POST["rideId"];
    $reservedSeats = 1; // Réserver une place par défaut
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "projetpweb";

	$conn = new mysqli($servername, $username, $password, $dbname);
    

    if ($conn->connect_error) {
        die("Échec de la connexion à la base de données : " . $conn->connect_error);
    }

    // Vérifier si l'utilisateur est connecté 
    if (isset($_SESSION['Email'])) {
        $Idutilisateur = $_SESSION['id_utilisateur'];
        // Vérifier si au moins une place est disponible
        $sqlCheckSeats = "SELECT places_disponibles FROM trajets WHERE id = $rideId";
        $resultCheckSeats = $conn->query($sqlCheckSeats);

        if ($resultCheckSeats->num_rows > 0) {
            $rowCheckSeats = $resultCheckSeats->fetch_assoc();
            $availableSeats = $rowCheckSeats["places_disponibles"];

            if ($availableSeats >= $reservedSeats) {
                // Effectuer la réservation
                $sqlReservation = "INSERT INTO reservations (id_utilisateur, id_trajet) VALUES ($Idutilisateur,$rideId)";
                $resultReservation = $conn->query($sqlReservation);

                if ($resultReservation === TRUE) {
                    // Mettre à jour le nombre de places disponibles dans la table trajets
                    $newAvailableSeats = $availableSeats - $reservedSeats;
                    $sqlUpdateSeats = "UPDATE trajets SET places_disponibles = $newAvailableSeats WHERE id = $rideId";
                    $conn->query($sqlUpdateSeats);

                    echo "Réservation réussie. Places disponibles mises à jour.";
                } else {
                    echo "Erreur lors de la réservation : " . $conn->error;
                }
            } else {
                echo "Aucune place disponible.";
            }
        } else {
            echo "Trajet non trouvé.";
        }
    } else {
        echo "Utilisateur non connecté.";
    }

    $conn->close();
} else {
    echo "Méthode non autorisée.";
}
?>
