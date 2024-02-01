<?php

    $nom_serv = "localhost";
    $utilisateur = "root";
    $mot_de_passe = "";
    $nom_base_données = "bdpweb";
    $conn = mysqli_connect($nom_serv, $utilisateur, $mot_de_passe, $nom_base_données);


    // !!!!!!!!!!!!!!!!!!! important !!!!!!!!!!!!!!!!!!!!!!!!!!

    // pour se connecter à la base de données on utilise la commande : 
    // include_once "connexion_bd.php";

    if (!$conn) {
        echo " Vous n'êtes pas connecté à la base de donnée.";
    }
?>