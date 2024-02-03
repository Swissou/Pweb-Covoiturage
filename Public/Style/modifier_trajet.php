<?php


include_once "connexion_bd.php";

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION["Id_Utilisateur"]) && $_SESSION["Id_Utilisateur"]) {
    // Traitement de la modification des trajets
    $message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupération des données du formulaire
        $trajetId = $_POST["trajetId"];

        // Récupération des données actuelles du trajet depuis la base de données
        $sqlSelect = "SELECT * FROM trajets WHERE id = '$trajetId'";
        $resultSelect = $conn->query($sqlSelect);

        if ($resultSelect !== false && $resultSelect->num_rows > 0) {
            $row = $resultSelect->fetch_assoc();

            // Remplacer uniquement les champs modifiés
            $departure = !empty($_POST["departure"]) ? $_POST["departure"] : $row["lieu_depart"];
            $destination = !empty($_POST["destination"]) ? $_POST["destination"] : $row["destination"];
            $departureTime = !empty($_POST["departureTime"]) ? $_POST["departureTime"] : $row["heure_depart"];
            $driver = !empty($_POST["driver"]) ? $_POST["driver"] : $row["conducteur"];
            $availableSeats = !empty($_POST["availableSeats"]) ? $_POST["availableSeats"] : $row["places_disponibles"];

            // Utilisez les données récupérées pour mettre à jour le trajet dans la base de données
            $sqlUpdate = "UPDATE trajets SET lieu_depart = '$departure', destination = '$destination', heure_depart = '$departureTime', conducteur = '$driver', places_disponibles = '$availableSeats' WHERE id = '$trajetId'";

            if ($conn->query($sqlUpdate) === TRUE) {
                $message = "Le trajet a été mis à jour avec succès.";
            } else {
                $message = "Erreur lors de la mise à jour du trajet : " . $conn->error;
            }
        } else {
            $message = "Trajet non trouvé.";
        }
    }

    // Affichage du formulaire de modification des trajets
    $trajetIdToModify = isset($_GET['trajetId']) ? $_GET['trajetId'] : '';
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="public/style/modifier_trajets.css" />
        <title>Modifier un Trajet</title>
    </head>
    <body>
       

<?php include 'navbar.php';?>

        <div id="searchForm" style="margin-top:90px;">
            <h2>Modifier un Trajet</h2>
            <form method="post" action="">
                <input type="hidden" name="trajetId" value="<?php echo $trajetIdToModify; ?>">
                <label for="departure">Lieu de départ:</label>
                <input type="text" id="departure" name="departure" >

                <label for="destination">Destination:</label>
                <input type="text" id="destination" name="destination" >

                <label for="departureTime">Heure de départ:</label>
                <input type="time" id="departureTime" name="departureTime" >

                <label for="driver">Conducteur:</label>
                <input type="text" id="driver" name="driver" >

                <label for="availableSeats">Nombre de places disponibles:</label>
                <input type="number" id="availableSeats" name="availableSeats"  >

                <button type="submit" name="submit">Modifier</button>
            </form>
            <div class="result-message">
                <?php echo $message; ?>
            </div>
        </div>
    </body>
    </html>
    <?php

    // Fermeture de la connexion à la base de données
    $conn->close();
} else {
    echo "Erreur : Utilisateur non connecté.";
}
?>
