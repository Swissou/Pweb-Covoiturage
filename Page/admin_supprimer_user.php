<?php
    include_once "connexion_bd.php";
    
    $id = $params['id'];

    $req = mysqli_query($conn, "DELETE FROM utilisateurs WHERE Id_Utilisateur = '$id'");
    header("Location:". $router->generate('Admin'));

?>