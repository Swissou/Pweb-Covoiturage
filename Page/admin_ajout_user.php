<?php 

    
    if(isset($_POST['btn-inscription'])){
        
        if (isset($_POST['Nom']) && isset($_POST['Prenom']) && isset($_POST['Email']) && isset($_POST['MDP']) && isset($_POST['Matricule']) && isset($_POST['Telephone'])) {
            
            $Nom = $_POST['Nom'];
            $Prenom = $_POST['Prenom'];
            $Email = $_POST['Email'];
            $MDP = $_POST['MDP'];
            $Matricule = $_POST['Matricule'];
            $Telephone = $_POST['Telephone'];
            $erreur = "";
            
            // hashage du mot de passe 
            $hash = password_hash($MDP, PASSWORD_DEFAULT); 
            
            include_once "connexion_bd.php";

            $req1 = mysqli_query($conn,"SELECT * FROM utilisateurs WHERE Email='$Email'");
            $cpt_ligne = mysqli_num_rows($req1);
            if($cpt_ligne > 0){
                $erreur = " Addresse mail déjà utilisé, Veuillez réessayer .";
            }else{
                $req = mysqli_query($conn, "INSERT INTO utilisateurs VALUES (NULL,'$Nom','$Prenom','$Telephone','$Email','$hash','$Matricule','1')");

                if ($req) {
                    header("Location:". $router->generate('Admin'));
                }else {
                    $erreur = " Utilisateur non ajouté !";
                }
            }
        }else {
            $erreur = " Veuillez remplir tous les champs ";
        }  
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Public/Style/inscription.css">
    <title>Ajout Utilisateur</title>
</head>
<body>
    
    <?php include 'navbar.php';?>

    <section class="card">

        <div class="retour">
            <a href="<?php echo $router->generate('Admin'); ?>" class="btn-link" id="retour">Retour</a>
        </div>

        <h1>Ajouter</h1>

        <?php 
            if (isset($erreur)) {
                echo '<p class="message_erreur">' . $erreur . '</p>';
            }    
        ?>

        <div class="formulaire-inscription">
            <form action="" method="post">
                <div class="champ">
                    <label for="Nom">Nom :</label>
                    <input type="Nom" name="Nom" required>
                </div>

                <div class="champ">
                    <label for="Prenom">Prénom :</label>
                    <input type="Prenom" name="Prenom" required>
                </div>

                <div class="champ">
                    <label for="Email">E-mail :</label>
                    <input type="Email" name="Email" required>
                </div>

                <div class="champ">
                    <label for="MDP">Mot de passe :</label>
                    <input type="password" name="MDP" required>
                </div>

                <div class="champ">
                    <label for="Matricule">Matricule :</label>
                    <input type="Matricule" name="Matricule" required>
                </div>

                <div class="champ">
                    <label for="Telephone">Telephone :</label>
                    <input type="Telephone" name="Telephone" required>
                </div>

                <div class="btn">
                    <button type="submit" value="inscription" name="btn-inscription">Confirmer</button> 
                </div> 

            </form>
        </div>

        

    </section>
    
</body>
</html>