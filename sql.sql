-- Structure simplifiée pour système de gestion électronique de documents

-- Table des services
CREATE TABLE Services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_nom (nom)
);

-- Table des employés avec rôles simplifiés
CREATE TABLE Employes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    photo VARCHAR(255),
    service_id INT NOT NULL,
    est_actif BOOLEAN DEFAULT TRUE,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_suppression DATETIME NULL,
    role ENUM('ADMIN', 'USER') DEFAULT 'USER',
    role_service ENUM('EMPLOYE', 'CHEF_SERVICE', 'ADMIN_SERVICE') DEFAULT 'EMPLOYE',
    derniere_connexion DATETIME NULL,
    
    FOREIGN KEY (service_id) REFERENCES Services(id) ON DELETE CASCADE,
    
    INDEX idx_service_actif (service_id, est_actif),
    INDEX idx_email (email)
);

-- Table des dossiers simplifiée
CREATE TABLE Dossiers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    chemin VARCHAR(500) NOT NULL,
    service_id INT NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    createur_id INT NOT NULL,
    
    -- Même si le créateur est supprimé, le dossier reste
    FOREIGN KEY (service_id) REFERENCES Services(id) ON DELETE CASCADE,
    FOREIGN KEY (createur_id) REFERENCES Employes(id) ON DELETE NO ACTION,
    
    INDEX idx_service (service_id),
    INDEX idx_chemin (chemin)
);

-- Table des documents simplifiée
CREATE TABLE Documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    nom_stockage VARCHAR(255) NOT NULL UNIQUE,
    dossier_id INT NOT NULL,
    uploader_id INT NOT NULL,
    date_upload DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    -- Même si l'uploader est supprimé, le document reste
    FOREIGN KEY (dossier_id) REFERENCES Dossiers(id) ON DELETE CASCADE,
    FOREIGN KEY (uploader_id) REFERENCES Employes(id) ON DELETE NO ACTION,
    
    INDEX idx_dossier (dossier_id),
    INDEX idx_uploader (uploader_id),
    INDEX idx_date_upload (date_upload)
);

-- Table pour traquer qui a ouvert les documents
CREATE TABLE ConsultationsDocuments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    document_id INT NOT NULL,
    employe_id INT NOT NULL,
    date_consultation DATETIME DEFAULT CURRENT_TIMESTAMP,
    type_consultation ENUM('OUVERTURE', 'TELECHARGEMENT') NOT NULL,
    
    -- Conserver l'historique même si l'employé est supprimé
    FOREIGN KEY (document_id) REFERENCES Documents(id) ON DELETE CASCADE,
    FOREIGN KEY (employe_id) REFERENCES Employes(id) ON DELETE NO ACTION,
    
    INDEX idx_document_employe (document_id, employe_id),
    INDEX idx_date_consultation (date_consultation)
);

-- Table des notifications simplifiée
CREATE TABLE Notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    document_id INT NOT NULL,
    service_id INT NOT NULL,
    uploader_id INT NOT NULL,
    date_notification DATETIME DEFAULT CURRENT_TIMESTAMP,
    message VARCHAR(500) DEFAULT 'Nouveau document uploadé',
    
    -- Conserver les notifications même si l'uploader est supprimé
    FOREIGN KEY (document_id) REFERENCES Documents(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES Services(id) ON DELETE CASCADE,
    FOREIGN KEY (uploader_id) REFERENCES Employes(id) ON DELETE NO ACTION,
    
    INDEX idx_service_date (service_id, date_notification),
    INDEX idx_document (document_id)
);

-- Table pour savoir qui a consulté le document suite à une notification
CREATE TABLE NotificationsConsultees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    notification_id INT NOT NULL,
    employe_id INT NOT NULL,
    a_ouvert_document BOOLEAN DEFAULT FALSE,
    date_ouverture DATETIME NULL,
    
    -- Conserver l'historique des consultations
    FOREIGN KEY (notification_id) REFERENCES Notifications(id) ON DELETE CASCADE,
    FOREIGN KEY (employe_id) REFERENCES Employes(id) ON DELETE NO ACTION,
    
    UNIQUE KEY unique_notif_employe (notification_id, employe_id),
    
    INDEX idx_employe_ouvert (employe_id, a_ouvert_document),
    INDEX idx_notification (notification_id)
);

-- Table des types d'actions
CREATE TABLE TypesAction (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) UNIQUE NOT NULL,
    description VARCHAR(255) NOT NULL
);

-- Table d'historique des actions
CREATE TABLE HistoriqueActions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employe_id INT NOT NULL,
    type_action_id INT NOT NULL,
    document_id INT NULL,
    dossier_id INT NULL,
    employe_cible_id INT NULL,
    details TEXT,
    date_action DATETIME DEFAULT CURRENT_TIMESTAMP,
    adresse_ip VARCHAR(45),
    
    -- Conserver l'historique complet même si les employés sont supprimés
    FOREIGN KEY (employe_id) REFERENCES Employes(id) ON DELETE NO ACTION,
    FOREIGN KEY (type_action_id) REFERENCES TypesAction(id) ON DELETE CASCADE,
    FOREIGN KEY (document_id) REFERENCES Documents(id) ON DELETE SET NULL,
    FOREIGN KEY (dossier_id) REFERENCES Dossiers(id) ON DELETE SET NULL,
    FOREIGN KEY (employe_cible_id) REFERENCES Employes(id) ON DELETE NO ACTION,
    
    INDEX idx_employe_date (employe_id, date_action),
    INDEX idx_type_action (type_action_id),
    INDEX idx_document_action (document_id, type_action_id),
    INDEX idx_date_action (date_action)
);

-- Insertion des types d'actions de base
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





-- Vue pour voir les documents avec les statistiques d'ouverture
CREATE VIEW VueDocumentsAvecConsultations AS
SELECT 
    d.id,
    d.nom_original,
    d.taille,
    d.type,
    d.date_upload,
    dos.nom AS dossier_nom,
    dos.chemin AS dossier_chemin,
    e.nom AS uploader_nom,
    e.prenom AS uploader_prenom,
    s.nom AS service_nom,
    -- Nombre total d'employés dans le service
    (SELECT COUNT(*) FROM Employes emp WHERE emp.service_id = s.id AND emp.est_actif = TRUE) AS nb_employes_service,
    -- Nombre d'employés qui ont ouvert le document
    (SELECT COUNT(DISTINCT cd.employe_id) 
     FROM ConsultationsDocuments cd 
     JOIN Employes emp ON cd.employe_id = emp.id 
     WHERE cd.document_id = d.id 
     AND cd.type_consultation = 'OUVERTURE' 
     AND emp.service_id = s.id 
     AND emp.est_actif = TRUE) AS nb_employes_ont_ouvert,
    -- Liste des employés qui ont ouvert le document
    (SELECT GROUP_CONCAT(CONCAT(emp.prenom, ' ', emp.nom) SEPARATOR ', ')
     FROM ConsultationsDocuments cd 
     JOIN Employes emp ON cd.employe_id = emp.id 
     WHERE cd.document_id = d.id 
     AND cd.type_consultation = 'OUVERTURE' 
     AND emp.service_id = s.id 
     AND emp.est_actif = TRUE) AS employes_ont_ouvert,
    -- Liste des employés qui N'ont PAS ouvert le document
    (SELECT GROUP_CONCAT(CONCAT(emp.prenom, ' ', emp.nom) SEPARATOR ', ')
     FROM Employes emp 
     WHERE emp.service_id = s.id 
     AND emp.est_actif = TRUE
     AND emp.id NOT IN (
         SELECT DISTINCT cd.employe_id 
         FROM ConsultationsDocuments cd 
         WHERE cd.document_id = d.id 
         AND cd.type_consultation = 'OUVERTURE'
     )) AS employes_nont_pas_ouvert,
    -- Dernière consultation
    (SELECT MAX(cd.date_consultation) 
     FROM ConsultationsDocuments cd 
     WHERE cd.document_id = d.id) AS derniere_consultation
FROM Documents d
JOIN Dossiers dos ON d.dossier_id = dos.id
JOIN Employes e ON d.uploader_id = e.id
JOIN Services s ON dos.service_id = s.id;

-- Trigger pour enregistrer automatiquement les consultations quand on consulte un document
DELIMITER //
CREATE TRIGGER after_document_view_action 
AFTER INSERT ON HistoriqueActions
FOR EACH ROW
BEGIN
    -- Si l'action est "consultation de document"
    IF NEW.type_action_id = (SELECT id FROM TypesAction WHERE code = 'VIEW_DOC') THEN
        INSERT INTO ConsultationsDocuments (document_id, employe_id, type_consultation)
        VALUES (NEW.document_id, NEW.employe_id, 'OUVERTURE');
        
        -- Mettre à jour la table NotificationsConsultees si il y a une notification pour ce document
        UPDATE NotificationsConsultees nc
        JOIN Notifications n ON nc.notification_id = n.id
        SET nc.a_ouvert_document = TRUE, nc.date_ouverture = NOW()
        WHERE n.document_id = NEW.document_id 
        AND nc.employe_id = NEW.employe_id
        AND nc.a_ouvert_document = FALSE;
    END IF;
    
    -- Si l'action est "téléchargement de document"
    IF NEW.type_action_id = (SELECT id FROM TypesAction WHERE code = 'DOWNLOAD_DOC') THEN
        INSERT INTO ConsultationsDocuments (document_id, employe_id, type_consultation)
        VALUES (NEW.document_id, NEW.employe_id, 'TELECHARGEMENT');
    END IF;
END //
DELIMITER ;

-- Trigger pour créer automatiquement les entrées NotificationsConsultees quand une notification est créée
DELIMITER //
CREATE TRIGGER after_notification_insert
AFTER INSERT ON Notifications
FOR EACH ROW
BEGIN
    -- Créer une entrée pour chaque employé du service concerné
    INSERT INTO NotificationsConsultees (notification_id, employe_id)
    SELECT NEW.id, e.id
    FROM Employes e
    WHERE e.service_id = NEW.service_id 
    AND e.est_actif = TRUE
    AND e.id != NEW.uploader_id; -- Exclure celui qui a uploadé le document
END //
DELIMITER ;