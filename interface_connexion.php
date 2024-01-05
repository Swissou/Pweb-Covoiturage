<?php 

    session_start();
    if (isset($_POST['btn-Se-connecter'])) {

        // vérifier si y'a des informations écrite dans le formulaire 
        if (isset($_POST['Email']) && isset($_POST['MDP'])) {
            //vérification si les informations entrées sont correctes

            // recuperer les informations dans des variables :
            $Email = $_POST['Email'];
            $MDP = $_POST['MDP'] ;
            $erreur = "";
            
            // connexion a la base de données :
           include_once "connexion_bd.php";


            // requete pour selectionner les utilisateurs de la bd
            $req = mysqli_query($connexion, "SELECT * FROM utilisateurs WHERE Email='$Email' AND MDP='$MDP' ");
            $cpt_ligne = mysqli_num_rows($req);
            
            if($cpt_ligne > 0){
                // créer une variable de type session qui vas contenir le prenom de l'utilisateur 
                $row = mysqli_fetch_assoc($req);
                $PrenomUser = $row['Prenom'];
                $_SESSION['PrenomUser'] = $PrenomUser;

                header("Location:confirmation_connexion.php");
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
    <link rel="stylesheet" href="Style/connexion.css">
   
    <title>Connexion</title>
</head>
<body>
    <section class="card">
        <h1 ">Connexion </h1>
        <div class="formulaire-connexion">
            <?php 
                if (isset($erreur)) {
                    echo '<p class="message_erreur">' . $erreur . '</p>';
                }
            ?>
            <form id="connexionForm" action="" method="post">
                <div class="champ">
                    <label for="Email">E-mail :</label>
                    <input type="email" id="Email" name="Email" required>
                </div>
                <div class="champ">
                    <label for="MDP">Mot de passe :</label>
                    <input type="password" id="MDP" name="MDP" required>
                </div>
                <div class="btn">
                    <button type="submit" value="Se connecter" name="btn-Se-connecter">Se connecter</button> 
                </div> 
                   
            </form>
        </div>
        <a href="Interface_inscription.php" class="btn-link">Vous n'avez pas de compte ?</a>
    </section>


</body>
</html>