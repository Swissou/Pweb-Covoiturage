<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="Public/Style/list_reservations.css">
    <title>list de reservations - Covoiturage</title>

</head>
<body>

    <?php include 'navbar.php';?>

<div id="rideList" style="margin-top:50px;">
<?php


include_once "connexion_bd.php";
// Vérifier si l'utilisateur est connecté
if (isset($_SESSION["Id_Utilisateur"]) && $_SESSION["Id_Utilisateur"]) {
    // Récupération de l'ID de l'utilisateur connecté
    $userId = $_SESSION["Id_Utilisateur"];


    $sqlRoleCheck = "SELECT * FROM utilisateurs WHERE id_utilisateur = '$userId'";
    $resultRoleCheck = $conn->query($sqlRoleCheck);

    if ($resultRoleCheck->num_rows > 0) {
        //récupérons les réservations associées à son ID
        $sqlReservations = "SELECT * FROM reservations WHERE id_utilisateur = '$userId'";
        $resultReservations = $conn->query($sqlReservations);

        // Affichage des réservations
        if ($resultReservations->num_rows > 0) {
            
            while ($row = $resultReservations->fetch_assoc()) {
                // Affichez les détails de chaque réservation
				echo "<div class='ride-card'>";
                echo "<p><strong>Réservation ID :</strong>  " . $row["id"] . "</p>";
                echo "<p><strong> Date de réservation : </strong> " . $row["heur_reservation"] . "</p>";
				echo "<p><strong> l'etat de trajet : </strong> " . $row["etat"] . "</p>";
                echo"<button onclick='deleteRide({$row["id"]})'>Annuler </button>";
				echo '</div>';
            }
        } else {
            echo "Aucune réservation trouvée.";
        }
    } else {
        echo "Vous n'avez pas la permission d'accéder à cette page.";
    }
} else {
    echo "Erreur : Utilisateur non connecté.";
}

// Fermeture de la connexion à la base de données
$conn->close();


?>
<script>
   function deleteRide(reservationId) {
            console.log("Function deleteRide is called.");
            if (confirm("Voulez-vous vraiment annuler cette réservation?")) {
                const xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            // Supprimez les balises HTML de la réponse
                            const responseText = xhr.responseText.replace(/<[^>]*>/g, '');
                            alert(responseText);
                            // Rafraîchir la liste des réservations après l'annulation
                            location.reload();
                        } else {
                            console.error("Erreur lors de l'annulation de la réservation:", xhr.status, xhr.statusText);
                        }
                    }
                };

                xhr.open("POST", "<?php echo $router->generate('annuler_reservation'); ?>", true);
                xhr.setRequestHeader("Content-type", "application/json");
                xhr.send(JSON.stringify({ reservationId: reservationId }));
            }
        }</script>
</div>
</body>
</html>