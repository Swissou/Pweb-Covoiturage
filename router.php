<?php
require 'Altorouter/Altorouter.php';

$router = new AltoRouter();

$router->map('GET|POST', '/', 'page_acc' ,'home');
$router->map('GET|POST', '/connexion', 'interface_connexion' ,'connexion');
$router->map('GET|POST', '/inscription', 'interface_inscription' ,'inscription');
$router->map('GET|POST', '/recherche', 'recherche' ,'recherche');
$router->map('GET', '/deconnexion', 'deconnexion' ,'deconnexion');
$router->map('GET|POST', '/trajet', 'trajet' ,'trajet');
$router->map('GET', '/gerer_trajets', 'gerer_trajets' ,'gerer_trajets');
$router->map('GET', '/list_reservations', 'list_reservations' ,'list_reservations');
$router->map('GET|POST', '/modifier_trajet', 'modifier_trajet' ,'modifier_trajet');
$router->map('GET|POST', '/supprimer_trajet', 'supprimer_trajet' ,'supprimer_trajet');
$router->map('GET|POST', '/annuler_reservation', 'annuler_reservation' ,'annuler_reservation');
$router->map('GET|POST', '/Admin', 'admin_accueil' ,'Admin');
$router->map('GET|POST', '/Adimin-Ajout-Utilisateur', 'admin_ajout_user' ,'ajout_user');
$router->map('GET|POST', '/Adimin-Modifier-Utilisateur-[i:id]', 'admin_modifier_user' ,'modifier_user');
$router->map('GET|POST', '/Adimin-Supprimer-Utilisateur-[i:id]', 'admin_supprimer_user' ,'supprimer_user');

$match = $router->match();



if ($match) {
    
    if (is_callable($match['target'])) {
        call_user_func_array($match['target'], $match['params']);

    }else {
        $params = $match['params'];
        $Page_Dir = __DIR__ . "/Page/{$match['target']}.php";
        $Global_Dir = __DIR__ . "/{$match['target']}.php";
        

        if(file_exists($Page_Dir)){
            require $Page_Dir;
        }
        else if(file_exists($Global_Dir)){
            require $Global_Dir;
        }    
    }    
}else{
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
}


?>
