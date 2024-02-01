<?php
require 'Altorouter/Altorouter.php';

$router = new AltoRouter();

$router->map('GET|POST', '/', 'page_acc' ,'home');
$router->map('GET|POST', '/connexion', 'interface_connexion' ,'connexion');
$router->map('GET|POST', '/inscription', 'interface_inscription' ,'inscription');
$router->map('GET|POST', '/recherche', 'recherche' ,'recherche');
$router->map('GET', '/deconnexion', 'deconnexion' ,'deconnexion');

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