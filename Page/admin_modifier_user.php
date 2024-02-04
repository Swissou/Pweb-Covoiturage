<?php 

    include_once "connexion_bd.php";
    $id = $params['id'];

    $req = mysqli_query($conn, "SELECT * FROM utilisateurs WHERE Id_Utilisateur = '$id'");
    $row = mysqli_fetch_assoc($req);

    if(isset($_POST['btn-modifier'])){
        
        if (isset($_POST['Nom']) && isset($_POST['Prenom']) && isset($_POST['Email']) && isset($_POST['Admin']) && isset($_POST['Matricule']) && isset($_POST['Telephone'])) {
            
            $Nom = $_POST['Nom'];
            $Prenom = $_POST['Prenom'];
            $Email = $_POST['Email'];
            $Admin = $_POST['Admin'];
            $Matricule = $_POST['Matricule'];
            $Telephone = $_POST['Telephone'];
            $erreur = "";

            $req1 = mysqli_query($conn,"SELECT * FROM utilisateurs WHERE Email='$Email'");
            $cpt_ligne = mysqli_num_rows($req1);
            if($cpt_ligne > 1){
                $erreur = " Addresse mail déjà utilisé, Veuillez réessayer .";
            }else{
                $req = mysqli_query($conn, "UPDATE utilisateurs SET Nom='$Nom', Prenom='$Prenom', Telephone='$Telephone',Matricule='$Matricule' WHERE Id_Utilisateur = '$id'");
                
                if ($req) {
                    header("Location:". $router->generate('Admin'));
                }else {
                    $erreur = " Utilisateur non Modifié !";
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
    <title>Modifier Utilisateur</title>
</head>
<body>
    
    <?php include 'navbar.php';?>

    <section class="card">

        <div class="retour">
            <a href="<?php echo $router->generate('Admin'); ?>" class="btn-link" id="retour">Retour</a>
        </div>

        <h1>Modifier</h1>

        <?php 
            if (isset($erreur)) {
                echo '<p class="message_erreur">' . $erreur . '</p>';
            }    
        ?>

        <div class="formulaire-inscription">
            <form action="" method="post">
                <div class="champ">
                    <label for="Nom">Nom :</label>
                    <input type="Nom" name="Nom" required value="<?=$row['Nom']?>">
                </div>

                <div class="champ">
                    <label for="Prenom">Prénom :</label>
                    <input type="Prenom" name="Prenom" required value="<?=$row['Prenom']?>">
                </div>

                <div class="champ">
                    <label for="Email">E-mail :</label>
                    <input type="Email" name="Email" required value="<?=$row['Email']?>">
                </div>

                <div class="champ">
                    <label for="Matricule">Matricule :</label>
                    <input type="Matricule" name="Matricule" required value="<?=$row['Matricule']?>">
                </div>

                <div class="champ">
                    <label for="Telephone">Telephone :</label>
                    <input type="number" name="Telephone" required value="<?=$row['Telephone']?>">
                </div>

                <div class="champ">
                    <label for="MDP">Admin :</label>
                    <input type="number" name="Admin" required value="<?=$row['Admin']?>">
                </div>

                <div class="btn">
                    <button type="submit" value="inscription" name="btn-modifier">Confirmer</button> 
                </div> 

            </form>
        </div>

        

    </section>
    
</body>
</html>