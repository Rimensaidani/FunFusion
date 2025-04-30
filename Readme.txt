CREATE TABLE Activite_Reelle (
    id_activite INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    lieu VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    catégorie VARCHAR(100) NOT NULL
);

CREATE TABLE Inscription_Activite (
    id_inscription INT AUTO_INCREMENT PRIMARY KEY,
    id_activite INT,
    id_utilisateur INT,
    statut ENUM('inscrit', 'annule', 'termine'),
    FOREIGN KEY (id_activite) REFERENCES Activité_Réelle(id_activite),
    FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id_utilisateur) -- Adjust if 'Utilisateur' table has a different name
);