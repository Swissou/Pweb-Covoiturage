<?php 
    // demarage de la session
    session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    echo '<h1>Vous êtes connecté avec succès '  . $_SESSION['Email'] . '!</h1>';
    ?>
</body>
</html>
