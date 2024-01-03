<?php 

    session_start();
    if (isset($_POST['boutton-Se-connecter'])) {

        // vérifier si y'a des informations écrite dans le formulaire 
        if (isset($_POST['Email']) && isset($_POST['MDP'])) {
            //vérification si les informations entrées sont correctes

            // recuperer les informations dans des variables :
            $Email = $_POST['Email'];
            $MDP = $_POST['MDP'] ;
            $erreur = "";
            
            // connexion a la base de données :
            $nom_serv = "localhost";
            $utilisateur = "root";
            $mot_de_passe = "";
            $nom_base_données = "bdpweb";
            $connexion = new mysqli($nom_serv, $utilisateur, $mot_de_passe, $nom_base_données);


            // requete pour selectionner les utilisateurs de la bd
            $req = mysqli_query($connexion, "SELECT * FROM utilisateurs WHERE Email='$Email' AND MDP='$MDP' ");
            $cpt_ligne = mysqli_num_rows($req);
            
            if($cpt_ligne > 0){
                header("Location:confirmation_connexion.php");
                // créer une variable de type session qui vas contenir l'email de l'utilisateur 
                $_SESSION['Email'] = $Email;
            }else{
                $erreur = " E-mail ou Mot de passe incorectes !";
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="Style/connexion.css">
</head>
<body>
    <section class="block">
        <h1>Connexion </h1>
        <div class="input">
            <?php 
                if (isset($erreur)) {
                    echo '<p class="message_erreur">' . $erreur . '</p>';
                }
            ?>
            <form id="connexionForm" action="" method="post">
                <div class="input-Email">
                    <label for="Email">E-mail :</label>
                    <input type="email" id="Email" name="Email" required>
                </div>
                <div class="input-MDP">
                    <label for="MDP">Mot de passe :</label>
                    <input type="password" id="MDP" name="MDP" required>
                </div>
                <div class="bouton">
                    <button type="submit" value="Se connecter" name="boutton-Se-connecter">Se connecter</button> 
                </div> 
                <a href="#" class="btn-link">Vous n'avez pas de compte ?</a>   
            </form>
        </div>
    </section>
</body>
</html>