<?php 

    
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
            $req = mysqli_query($conn, "SELECT * FROM utilisateurs WHERE Email='$Email' ");
            $cpt_ligne = mysqli_num_rows($req);


            // on vérifie si l'utilisateurs avec l'email donné existe 
            if($cpt_ligne > 0){
                // si il existe on réqupere ses informations pour vérifier le mot de passe
                $row = mysqli_fetch_assoc($req);
                $hash = $row['MDP'];
                $verify = password_verify($MDP, $hash); 
  
                // si le mot de passe est correcte alors on créer une variable de type session qui vas contenir le prenom de l'utilisateur 
                if ($verify) { 
                    $_SESSION['Email'] = $row['Email'];
                    $_SESSION['Id_Utilisateur'] = $row['Id_Utilisateur'];
					$_SESSION['admin'] = $row['Admin'];
 
                    header("Location:". $router->generate('home'));
                } else { 
                    $erreur = " Mot de passe incorrect !";
                }     
            }else{
                $erreur = " Adresse E-mail inexistante !";
            }
        }
    }
?> 



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Public/Style/connexion.css">
    
    <title>Connexion</title>
</head>

<body>

    <?php include 'navbar.php';?>

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
        <a href="<?php echo $router->generate('inscription'); ?>" class="btn-link">Vous n'avez pas de compte ?</a>
    </section>

</body>
</html>
