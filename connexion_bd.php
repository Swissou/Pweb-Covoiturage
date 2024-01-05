<?php

    $nom_serv = "localhost";
    $utilisateur = "root";
    $mot_de_passe = "";
    $nom_base_données = "bdpweb";
    $connexion = mysqli_connect($nom_serv, $utilisateur, $mot_de_passe, $nom_base_données);

    if (!$connexion) {
        echo " Vous n'êtes pas connecté à la base de donnée.";
    }
?>