<?php
session_start();
include_once "connexion_bd.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rideId = $_POST["rideId"];
    $reservedSeats = 1; // Réserver une place par défaut


    // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['Email'])) {
        $Idutilisateur = $_SESSION['Id_Utilisateur'];

        // Vérifier si au moins une place est disponible
        $sqlCheckSeats = "SELECT places_disponibles FROM trajets WHERE id = $rideId";
        $resultCheckSeats = $conn->query($sqlCheckSeats);

        if ($resultCheckSeats === FALSE) {
            echo "Erreur lors de la vérification des places disponibles : " . $conn->error;
        } else {
            if ($resultCheckSeats->num_rows > 0) {
                $rowCheckSeats = $resultCheckSeats->fetch_assoc();
                $availableSeats = $rowCheckSeats["places_disponibles"];

                if ($availableSeats >= $reservedSeats) {
                    // Effectuer la réservation
                    $sqlReservation = "INSERT INTO reservations (id_utilisateur, id_trajet) VALUES ($Idutilisateur, $rideId)";
                    $resultReservation = $conn->query($sqlReservation);

                    if ($resultReservation === TRUE) {
                        // Mettre à jour le nombre de places disponibles dans la table trajets
                        $newAvailableSeats = $availableSeats - $reservedSeats;
                        $sqlUpdateSeats = "UPDATE trajets SET places_disponibles = $newAvailableSeats WHERE id = $rideId";
                        $resultUpdateSeats = $conn->query($sqlUpdateSeats);

                        if ($resultUpdateSeats === TRUE) {
                            echo "Réservation réussie. Places disponibles mises à jour.";
                        } else {
                            echo "Erreur lors de la mise à jour des places disponibles : " . $conn->error;
                        }
                    } else {
                        echo "Erreur lors de la réservation : " . $conn->error;
                    }
                } else {
                    echo "Aucune place disponible.";
                }
            } else {
                echo "Trajet non trouvé.";
            }
        }
    } else {
        echo "Utilisateur non connecté.";
    }

    $conn->close();
} else {
    echo "Méthode non autorisée.";
}
?>
