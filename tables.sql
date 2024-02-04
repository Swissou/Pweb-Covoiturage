/*table utilisateur*/
CREATE TABLE utilisateurs (
    `Id_Utilisateur` INT NOT NULL AUTO_INCREMENT ,
    `Nom` TEXT NOT NULL ,
    `Prenom` TEXT NOT NULL ,
    `Telephone` INT NOT NULL ,
    `Email` VARCHAR(100) NOT NULL ,
    `MDP` VARCHAR(255) NOT NULL , 
    `Matricule` VARCHAR(20) NOT NULL , 
    `Admin` TINYINT(1) NOT NULL DEFAULT '0' , 
    PRIMARY KEY (`Id_Utilisateur`), 
    UNIQUE `Email` (`Email`))
    ENGINE = MyISAM;
    
    
/*table trajets*/

CREATE TABLE trajets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lieu_depart VARCHAR(255) NOT NULL,
    destination VARCHAR(255) NOT NULL,
    heure_depart TIME,
    conducteur VARCHAR(255),
    places_disponibles INT,
    CONSTRAINT check_heure CHECK (heure_depart BETWEEN '00:00:00' AND '23:59:59')
);

ALTER TABLE trajets
ADD COLUMN lieu_depart_latitude DOUBLE,
ADD COLUMN lieu_depart_longitude DOUBLE,
ADD COLUMN destination_latitude DOUBLE,
ADD COLUMN destination_longitude DOUBLE;

ALTER TABLE trajets ADD COLUMN id_utilisateur INT, ADD FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id);



/*table resarvation*/
CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    id_trajet INT,
    heur_reservation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id),
    FOREIGN KEY (id_trajet) REFERENCES trajets(id)
);


/**/
