<?php
// Initialiser la session


include_once "connexion_bd.php";

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION["Id_Utilisateur"]) && $_SESSION["Id_Utilisateur"]) {
    // Vérifier la méthode de requête
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["rideId"])) {
        $rideId = $_POST["rideId"];
        $conducteurId = $_SESSION["Id_Utilisateur"];

        // Utiliser une requête préparée pour vérifier la propriété du trajet
        $sqlCheckOwnership = "SELECT * FROM trajets WHERE id = ? AND id_utilisateur = ?";
        $stmtCheckOwnership = $conn->prepare($sqlCheckOwnership);
        $stmtCheckOwnership->bind_param("ii", $rideId, $conducteurId);
        $stmtCheckOwnership->execute();
        $resultCheckOwnership = $stmtCheckOwnership->get_result();

        if ($resultCheckOwnership->num_rows > 0) {
            // Trajet trouvé et appartient au conducteur connecté, procéder à la suppression
            $sqlDeleteRide = "DELETE FROM trajets WHERE id = ?";
            $stmtDeleteRide = $conn->prepare($sqlDeleteRide);
            $stmtDeleteRide->bind_param("i", $rideId);

            if ($stmtDeleteRide->execute()) {
                // Mise à jour de l'état des réservations liées à ce trajet
                $sqlUpdateReservations = "UPDATE reservations SET etat = 'annule' WHERE id_trajet = ?";
                $stmtUpdateReservations = $conn->prepare($sqlUpdateReservations);
                $stmtUpdateReservations->bind_param("i", $rideId);
                
                if ($stmtUpdateReservations->execute()) {
                    echo "Trajet supprimé avec succès et réservations annulées.";
                } else {
                    echo "Erreur lors de la mise à jour de l'état des réservations : " . $stmtUpdateReservations->error;
                }

                $stmtUpdateReservations->close();
            } else {
                echo "Erreur lors de la suppression du trajet : " . $stmtDeleteRide->error;
            }
        } else {
            echo "Le trajet n'appartient pas à l'utilisateur connecté.";
        }

        $stmtCheckOwnership->close();
        $stmtDeleteRide->close();
    } else {
        echo "Méthode non autorisée ou ID de trajet non fourni.";
    }
} else {
    echo "Utilisateur non connecté.";
}

// Fermeture de la connexion à la base de données
$conn->close();
?>
