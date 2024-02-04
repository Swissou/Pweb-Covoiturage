<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Public/Style/admin.css">
    <title>Admin</title>
</head>
<body>

    <?php include 'navbar.php';?>

    <section class="dashboard-bar">

        <ul>
            <li><a href="">Utilisateurs</a></li>
            <li><a href="">Trajets</a></li>

        </ul>

    </section>

    <section class="container">
        <a href="<?php echo $router->generate('ajout_user'); ?>"class="link-a"> <img src="../Public/assets/icon-ajouter.png" id="t"> Ajouter</a>

        <div class="tt">
        <table>
            <tr id="items">
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Matricule</th>
                <th>Telephone</th>
                <th>Admin</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>

            <?php
                include_once "connexion_bd.php";

                $req = mysqli_query($conn , "SELECT * FROM utilisateurs");
                if (mysqli_num_rows($req) == 0) {
                    echo "Table vide !";
                }else{
                    while ($row = mysqli_fetch_assoc($req)) {
                        ?>

                        <tr>
                            <td><?=$row['Nom']?></td>
                            <td><?=$row['Prenom']?></td>
                            <td><?=$row['Email']?></td>
                            <td><?=$row['Matricule']?></td>
                            <td><?=$row['Telephone']?></td>
                            <td><?=$row['Admin']?></td>
                            <td><a href="<?php echo $router->generate('modifier_user',['id'=> $row['Id_Utilisateur']]); ?>"><img src="../Public/assets/icon-modifier.png" id="t"></a></td>
                            <td><a href="<?php echo $router->generate('supprimer_user',['id'=> $row['Id_Utilisateur']]); ?>"><img src="../Public/assets/icon-supprimer.png" id="t"></a></td>
                        </tr>

                        <?php
                    }
                }

            ?>     
        </table>
        </div>       

    </section>
    
</body>
</html>