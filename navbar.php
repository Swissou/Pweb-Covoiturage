<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Public/style/nav.css">
    <title>NavBar</title>
</head>
<body>
    
    <nav>
            <a href="" class="logo">RoadBuddy</a>
        <div class="link-bar">   
        <ul>
            <li><a href="<?php echo $router->generate('home'); ?>" class="link">Accueil</a></li>
            <li><a href="<?php echo $router->generate('recherche'); ?>" class="link">Rechercher</a></li>
			<?php
                if(isset($_SESSION['chauffeur'])&& $_SESSION['chauffeur']==1){
            ?>
            <li><a href="<?php echo $router->generate('trajet'); ?>" class="link">Publier un trajet</a></li>
			<li><a href="<?php echo $router->generate('gerer_trajets'); ?>" class="link">Gérer Mes Trajets</a></li>
			<?php
				}
                if(isset($_SESSION['client'])&& $_SESSION['client']==1){
            ?>
			<li><a href="<?php echo $router->generate('list_reservations'); ?>" class="link">Mes Réservations</a></li>
            <?php
				}
                if(!isset($_SESSION['Id_Utilisateur'])){
            ?>
            <li><a href="<?php echo $router->generate('connexion'); ?>" class="link" id="hbg">Connexion</a></li>
            <?php        
                }else{
            ?>  
            <li><a href="<?php echo $router->generate('deconnexion'); ?>" class="link" id="hbg">Déconnexion</a></li>      
            <?php            
                }
            ?>
            
        </ul>
        </div> 

        <?php
                if(!isset($_SESSION['Id_Utilisateur'])){
            ?>
            <a href="<?php echo $router->generate('connexion'); ?>" class="btn"><button type="button">Connexion</button></a>
            <?php        
                }else{
            ?>  
            <a href="<?php echo $router->generate('deconnexion'); ?>" class="btn"><button type="button">Déconnexion</button></a>              
            <?php            
                }
            ?>
                    

                
        <img src="Public/assets/icon-menu-hamburger.png" alt="" class="menu-hbg">
    </nav>

    <script>
            const menuHamburger = document.querySelector(".menu-hbg")
            const navLinks = document.querySelector(".link-bar")
    
            menuHamburger.addEventListener('click',()=>{
            navLinks.classList.toggle('mobile-menu')
            })
    </script>

</body>
</html>
