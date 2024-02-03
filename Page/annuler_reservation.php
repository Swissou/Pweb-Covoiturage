<?php

include_once "connexion_bd.php";

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION["Id_Utilisateur"]) && $_SESSION["Id_Utilisateur"]) {
    // Vérifier la méthode de requête
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data["reservationId"])) {
            $reservationId = $data["reservationId"];
            $userId = $_SESSION["Id_Utilisateur"];

            // Utiliser une requête préparée pour vérifier la propriété de la réservation
            $sqlCheckOwnership = "SELECT * FROM reservations WHERE id = ? AND id_utilisateur = ?";
            $stmtCheckOwnership = $conn->prepare($sqlCheckOwnership);
            $stmtCheckOwnership->bind_param("ii", $reservationId, $userId);
            $stmtCheckOwnership->execute();
            $resultCheckOwnership = $stmtCheckOwnership->get_result();

            if ($resultCheckOwnership->num_rows > 0) {
                // Réservation trouvée et appartient à l'utilisateur connecté, procéder à l'annulation
                $sqlUpdateReservation = "DELETE FROM reservations WHERE id = ?";
                $stmtUpdateReservation = $conn->prepare($sqlUpdateReservation);
                $stmtUpdateReservation->bind_param("i", $reservationId);

                if ($stmtUpdateReservation->execute()) {
                    echo "Réservation annulée avec succès.";
                } else {
                    echo "Erreur lors de l'annulation de la réservation : " . $stmtUpdateReservation->error;
                }
            } else {
                echo "La réservation n'appartient pas à l'utilisateur connecté.";
            }

            $stmtCheckOwnership->close();
            $stmtUpdateReservation->close();
        } else {
            echo "ID de réservation non fourni.";
        }
    } else {
        echo "Méthode non autorisée.";
    }
} else {
    echo "Utilisateur non connecté.";
}

// Fermeture de la connexion à la base de données
$conn->close();
?>
