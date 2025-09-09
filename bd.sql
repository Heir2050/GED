CREATE TABLE Services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Employes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    service_id INT NOT NULL,
    est_actif BOOLEAN DEFAULT TRUE,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_suppression DATETIME NULL,
    FOREIGN KEY (service_id) REFERENCES Services(id)
);

CREATE TABLE Dossiers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    chemin VARCHAR(500) NOT NULL,
    service_id INT NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    createur_id INT NOT NULL,
    FOREIGN KEY (service_id) REFERENCES Services(id),
    FOREIGN KEY (createur_id) REFERENCES Employes(id)
);

CREATE TABLE Documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom_original VARCHAR(255) NOT NULL,
    nom_stockage VARCHAR(255) NOT NULL,
    taille BIGINT NOT NULL,
    type VARCHAR(100) NOT NULL,
    dossier_id INT NOT NULL,
    uploader_id INT NOT NULL,
    date_upload DATETIME DEFAULT CURRENT_TIMESTAMP,
    est_actif BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (dossier_id) REFERENCES Dossiers(id),
    FOREIGN KEY (uploader_id) REFERENCES Employes(id)
);

CREATE TABLE TypesAction (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) UNIQUE NOT NULL,
    description VARCHAR(255) NOT NULL
);

CREATE TABLE HistoriqueActions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employe_id INT NOT NULL,
    type_action_id INT NOT NULL,
    document_id INT NULL,
    dossier_id INT NULL,
    employe_cible_id INT NULL, -- Pour les actions sur d'autres employés
    details TEXT, -- Informations supplémentaires sur l'action
    date_action DATETIME DEFAULT CURRENT_TIMESTAMP,
    adresse_ip VARCHAR(45), -- Pour le suivi de sécurité
    FOREIGN KEY (employe_id) REFERENCES Employes(id),
    FOREIGN KEY (type_action_id) REFERENCES TypesAction(id),
    FOREIGN KEY (document_id) REFERENCES Documents(id),
    FOREIGN KEY (dossier_id) REFERENCES Dossiers(id),
    FOREIGN KEY (employe_cible_id) REFERENCES Employes(id)
);

CREATE TABLE Notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    document_id INT NOT NULL,
    service_id INT NOT NULL,
    uploader_id INT NOT NULL,
    date_notification DATETIME DEFAULT CURRENT_TIMESTAMP,
    message VARCHAR(500) DEFAULT 'Nouveau document uploadé',
    FOREIGN KEY (document_id) REFERENCES Documents(id),
    FOREIGN KEY (service_id) REFERENCES Services(id),
    FOREIGN KEY (uploader_id) REFERENCES Employes(id)
);

CREATE TABLE NotificationsLues (
    id INT PRIMARY KEY AUTO_INCREMENT,
    notification_id INT NOT NULL,
    employe_id INT NOT NULL,
    est_lue BOOLEAN DEFAULT FALSE,
    date_lecture DATETIME NULL,
    FOREIGN KEY (notification_id) REFERENCES Notifications(id),
    FOREIGN KEY (employe_id) REFERENCES Employes(id)
);

INSERT INTO TypesAction (code, description) VALUES
('UPLOAD_DOC', 'Upload d''un document'),
('VIEW_DOC', 'Consultation d''un document'),
('DOWNLOAD_DOC', 'Téléchargement d''un document'),
('MODIFY_DOC', 'Modification d''un document'),
('DELETE_DOC', 'Suppression d''un document'),
('CREATE_FOLDER', 'Création d''un dossier'),
('DELETE_FOLDER', 'Suppression d''un dossier'),
('CREATE_USER', 'Création d''un utilisateur'),
('MODIFY_USER', 'Modification d''un utilisateur'),
('DELETE_USER', 'Désactivation d''un utilisateur'),
('LOGIN', 'Connexion au système'),
('LOGOUT', 'Déconnexion du système'),
('NOTIFICATION_SENT', 'Envoi d''une notification');