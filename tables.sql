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

/*table utilisateur*/
CREATE TABLE utilisateurs(
	id INT AUTO_INCREMENT PRIMARY KEY,
	Email VARCHAR(255) NOT NULL,
	mdp VARCHAR(255) NOT NULL
);
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
