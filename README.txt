
*** Projet de TP Programation Web Avancée ***

Nom de l'équipe : RoadBuddy

Membres : 
	-BENCHIKH Kenza
	-HAZI Riad
	-BENALI Melissa
	-MORSLI Yacine


Etapes à suivre pour lancer l'application :

1- Installez WampServer 3.3.2 (lien : https://www.wampserver.com/).
2- Lancez le serveur Wamp et copiez le projet dans le dossier wamp64/www.
3- Cliquez gauche sur l'icône de WampServer en bas à droite de l'écran -> Apache -> httpd.conf.
4- Cherchez la ligne "AllowOverride none", changez-la en => "AllowOverride All" ; sauvegardez.
5- Cliquez gauche sur l'icône de WampServer en bas à droite de l'écran -> VirtualHost -> Gestion VirtualHost.
6- Donnez un nom à votre virtual host et le chemin du projet (Exemple : C:/wamp/www/projet/), puis confirmez.
7- Créez une base de données et importez le fichier table.sql présent dans le projet.
8- Redémarrez le serveur.
9- Lancez l'application via le VirtualHost.
