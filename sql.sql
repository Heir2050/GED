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
    service_id INT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    createur_id INT NOT NULL,
    
    -- Même si le créateur est supprimé, le dossier reste
    FOREIGN KEY (service_id) REFERENCES Services(id) ON DELETE CASCADE,
    FOREIGN KEY (createur_id) REFERENCES Employes(id) ON DELETE NO ACTION,
    
    INDEX idx_service (service_id),
    INDEX idx_chemin (chemin)
);
ALTER TABLE Dossiers 
ADD COLUMN est_archive BOOLEAN DEFAULT FALSE AFTER createur_id,
ADD COLUMN date_archivage DATETIME NULL AFTER est_archive;

-- Table pour suivre l'état des dossiers par utilisateur
CREATE TABLE EtatDossierUtilisateur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    dossier_id INT NOT NULL,
    employe_id INT NOT NULL,
    etat ENUM('NON_OUVERT', 'TRAITEMENT', 'CLOTURE') DEFAULT 'NON_OUVERT',
    date_ouverture DATETIME NULL,
    date_cloture DATETIME NULL,
    date_derniere_modification DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (dossier_id) REFERENCES Dossiers(id) ON DELETE CASCADE,
    FOREIGN KEY (employe_id) REFERENCES Employes(id) ON DELETE CASCADE,
    
    UNIQUE KEY unique_dossier_employe (dossier_id, employe_id),
    
    INDEX idx_dossier_etat (dossier_id, etat),
    INDEX idx_employe_etat (employe_id, etat)
);
-- Table pour gérer les envois de dossiers
CREATE TABLE EnvoiDossiers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    dossier_id INT NOT NULL,
    type_envoi ENUM('SERVICE', 'ROLE_SERVICE') NOT NULL,
    service_id INT NULL, -- Pour envoi par service
    role_service ENUM('EMPLOYE', 'CHEF_SERVICE', 'ADMIN_SERVICE') NULL, -- Pour envoi par rôle
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    envoyeur_id INT NOT NULL,
    est_actif BOOLEAN DEFAULT TRUE,
    
    FOREIGN KEY (dossier_id) REFERENCES Dossiers(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES Services(id) ON DELETE CASCADE,
    FOREIGN KEY (envoyeur_id) REFERENCES Employes(id) ON DELETE CASCADE,
    
    UNIQUE KEY unique_envoi_dossier (dossier_id, service_id, role_service),
    
    INDEX idx_dossier_type (dossier_id, type_envoi),
    INDEX idx_service_role (service_id, role_service)
);

-- Ajouter une colonne pour suivre l'origine du dossier
ALTER TABLE Dossiers 
ADD COLUMN est_envoye BOOLEAN DEFAULT FALSE AFTER est_archive,
ADD COLUMN origine_envoi ENUM('INTERNE', 'EXTERNE') DEFAULT 'INTERNE' AFTER est_envoye;




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
CREATE TABLE IF NOT EXISTS ConsultationsDocuments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    document_id INT NOT NULL,
    employe_id INT NOT NULL,
    date_consultation DATETIME DEFAULT CURRENT_TIMESTAMP,
    type_consultation ENUM('OUVERTURE', 'TELECHARGEMENT') NOT NULL,
    
    FOREIGN KEY (document_id) REFERENCES Documents(id) ON DELETE CASCADE,
    FOREIGN KEY (employe_id) REFERENCES Employes(id) ON DELETE CASCADE,
    
    INDEX idx_document_employe (document_id, employe_id),
    INDEX idx_date_consultation (date_consultation),
    INDEX idx_type_consultation (type_consultation)
);

-- Table des notifications simplifiée
CREATE TABLE Notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    service_id INT NOT NULL,
    uploader_id INT NOT NULL,
    recipient_id INT NULL, -- destinataire spécifique; NULL = notification diffusée au service
    dossier_id INT NOT NULL, -- référence le dossier concerné
    date_notification DATETIME DEFAULT CURRENT_TIMESTAMP,
    message VARCHAR(500) DEFAULT 'Nouveau document uploadé',
    is_read tinyint(1) DEFAULT 0,
    date_lecture DATETIME,
    
    -- Conserver les notifications même si l'uploader est supprimé
    FOREIGN KEY (service_id) REFERENCES Services(id) ON DELETE CASCADE,
    FOREIGN KEY (uploader_id) REFERENCES Employes(id) ON DELETE NO ACTION,
    FOREIGN KEY (recipient_id) REFERENCES Employes(id) ON DELETE CASCADE,
    FOREIGN KEY (dossier_id) REFERENCES Dossiers(id) ON DELETE CASCADE,
    
    INDEX idx_service_date (service_id, date_notification),
    INDEX idx_recipient (recipient_id),
    INDEX idx_notifications_dossier_read (dossier_id, is_read)
);
ALTER TABLE notifications 
ADD COLUMN employes_ayant_ouvert JSON NULL DEFAULT NULL AFTER message;


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
('NOTIFICATION_SENT', 'Envoi d''une notification'),
('SEND_DOSSIER', 'Envoi d''un dossier'),
('REMOVE_DOSSIER', 'Retrait d''un dossier'),
('ARCHIVE_DOSSIER', 'Archivage d''un dossier'),
('UNARCHIVE_DOSSIER', 'Désarchivage d''un dossier'),
('VIEW_HISTORY', 'Consultation de l''historique'),
('MODIFY_PROFILE', 'Modification du profil'),
('CHANGE_PASSWORD', 'Changement de mot de passe');

ALTER TABLE documents 
ADD COLUMN taille BIGINT NOT NULL DEFAULT 0 AFTER nom_stockage,
ADD COLUMN type VARCHAR(100) NOT NULL DEFAULT 'application/octet-stream' AFTER taille;


-- Trigger pour créer automatiquement les entrées d'état lors de la création d'un dossier
DELIMITER //
CREATE TRIGGER after_dossier_insert
AFTER INSERT ON Dossiers
FOR EACH ROW
BEGIN
    INSERT INTO EtatDossierUtilisateur (dossier_id, employe_id, etat)
    SELECT NEW.id, e.id, 'NON_OUVERT'
    FROM Employes e
    WHERE e.service_id = NEW.service_id 
    AND e.est_actif = TRUE
    AND e.id != NEW.createur_id;
END //
DELIMITER ;

-- Créer le trigger
DELIMITER //

CREATE TRIGGER after_dossier_insert
AFTER INSERT ON Dossiers
FOR EACH ROW
BEGIN
    -- Créer les entrées d'état pour tous les employés du service (sauf le créateur)
    INSERT INTO etatdossierutilisateur (dossier_id, employe_id, etat)
    SELECT NEW.id, e.id, 'NON_OUVERT'
    FROM employes e
    WHERE e.service_id = NEW.service_id 
    AND e.est_actif = TRUE
    AND e.id != NEW.createur_id;
END//

DELIMITER ;








-- Créer un nouveau trigger qui vérifie si le dossier a déjà été notifié
DELIMITER //

CREATE TRIGGER after_document_insert
AFTER INSERT ON Documents
FOR EACH ROW
BEGIN
    DECLARE dossier_service_id INT;
    DECLARE uploader_employe_id INT;
    DECLARE dossier_nom VARCHAR(255);
    DECLARE notification_exists INT;
    DECLARE done INT DEFAULT FALSE;
    DECLARE employe_id INT;
    DECLARE employe_cursor CURSOR FOR 
        SELECT id FROM Employes 
        WHERE service_id = dossier_service_id 
        AND est_actif = TRUE 
        AND id != uploader_employe_id;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    -- Récupérer les informations du dossier
    SELECT service_id, createur_id, nom INTO dossier_service_id, uploader_employe_id, dossier_nom
    FROM Dossiers WHERE id = NEW.dossier_id;
    
    -- Vérifier si une notification existe déjà pour ce dossier aujourd'hui
    SELECT COUNT(*) INTO notification_exists
    FROM Notifications 
    WHERE dossier_id = NEW.dossier_id 
    AND DATE(date_notification) = CURDATE()
    AND uploader_id = uploader_employe_id;
    
    -- Si aucune notification n'existe pour ce dossier aujourd'hui, en créer une
    IF notification_exists = 0 THEN
        -- Parcourir tous les employés du même service (sauf l'uploader)
        OPEN employe_cursor;
        employe_loop: LOOP
            FETCH employe_cursor INTO employe_id;
            IF done THEN
                LEAVE employe_loop;
            END IF;
            
            -- Créer une notification pour chaque employé
            INSERT INTO Notifications (dossier_id, service_id, uploader_id, recipient_id, message)
            VALUES (
                NEW.dossier_id, 
                dossier_service_id, 
                uploader_employe_id, 
                employe_id,
                CONCAT('Nouveaux documents ajoutés dans le dossier "', dossier_nom, '"')
            );
        END LOOP;
        CLOSE employe_cursor;
    END IF;
END//

DELIMITER ;



















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



-- Notification lors d'un envoi de dossier: notifie les destinataires
DELIMITER //
CREATE TRIGGER after_envoi_dossier_insert
AFTER INSERT ON EnvoiDossiers
FOR EACH ROW
BEGIN
	DECLARE v_dossier_nom VARCHAR(255);
	DECLARE v_done INT DEFAULT 0;
	DECLARE v_recipient_id INT;
	DECLARE cur_recipients CURSOR FOR
		-- Cas 1: envoi par service → tous les employés actifs du service destinataire
		SELECT e.id FROM Employes e
		WHERE NEW.type_envoi = 'SERVICE'
		  AND e.service_id = NEW.service_id
		  AND e.est_actif = TRUE
		  AND e.id <> NEW.envoyeur_id
		UNION
		-- Cas 2: envoi par rôle → employés du service du dossier avec ce rôle
		SELECT e2.id FROM Employes e2
		JOIN Dossiers d2 ON d2.id = NEW.dossier_id
		WHERE NEW.type_envoi = 'ROLE_SERVICE'
		  AND e2.service_id = d2.service_id
		  AND e2.role_service = NEW.role_service
		  AND e2.est_actif = TRUE
		  AND e2.id <> NEW.envoyeur_id;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done = 1;

	-- Nom du dossier pour le message
	SELECT nom INTO v_dossier_nom FROM Dossiers WHERE id = NEW.dossier_id;

	OPEN cur_recipients;
	recipients_loop: LOOP
		FETCH cur_recipients INTO v_recipient_id;
		IF v_done = 1 THEN
			LEAVE recipients_loop;
		END IF;
		INSERT INTO Notifications (dossier_id, service_id, uploader_id, recipient_id, message)
		VALUES (
			NEW.dossier_id,
			COALESCE(NEW.service_id, (SELECT service_id FROM Dossiers WHERE id = NEW.dossier_id)),
			NEW.envoyeur_id,
			v_recipient_id,
			CONCAT('Vous avez reçu le dossier "', v_dossier_nom, '"')
		);
	END LOOP;
	CLOSE cur_recipients;
END //
DELIMITER ;






-- Trigger pour créer automatiquement les entrées NotificationsConsultees quand une notification est créée
DELIMITER //
CREATE TRIGGER after_notification_insert
AFTER INSERT ON Notifications
FOR EACH ROW
BEGIN
    -- Si la notification cible un destinataire précis, créer une seule entrée
    IF NEW.recipient_id IS NOT NULL THEN
        INSERT INTO NotificationsConsultees (notification_id, employe_id)
        VALUES (NEW.id, NEW.recipient_id);
    ELSE
        -- Sinon, diffusion au service: créer pour chaque employé du service (sauf l'uploader)
        INSERT INTO NotificationsConsultees (notification_id, employe_id)
        SELECT NEW.id, e.id
        FROM Employes e
        WHERE e.service_id = NEW.service_id 
        AND e.est_actif = TRUE
        AND e.id != NEW.uploader_id;
    END IF;
END //
DELIMITER ;

-- ===========================================
-- TRIGGERS DE TRAÇABILITÉ ET HISTORIQUE
-- ===========================================

-- Trigger pour tracer les connexions (mise à jour derniere_connexion)
DELIMITER //
CREATE TRIGGER after_employe_login
AFTER UPDATE ON Employes
FOR EACH ROW
BEGIN
    -- Si la dernière connexion a été mise à jour
    IF NEW.derniere_connexion != OLD.derniere_connexion THEN
        INSERT INTO HistoriqueActions (employe_id, type_action_id, details, date_action)
        VALUES (
            NEW.id,
            (SELECT id FROM TypesAction WHERE code = 'LOGIN'),
            CONCAT('Connexion de ', NEW.prenom, ' ', NEW.nom, ' (', NEW.email, ')'),
            NOW()
        );
    END IF;
END //
DELIMITER ;

-- Trigger pour tracer la création d'utilisateurs
DELIMITER //
CREATE TRIGGER after_employe_insert
AFTER INSERT ON Employes
FOR EACH ROW
BEGIN
    INSERT INTO HistoriqueActions (employe_id, type_action_id, employe_cible_id, details, date_action)
    VALUES (
        NEW.id, -- L'utilisateur qui a créé (sera mis à jour par l'application)
        (SELECT id FROM TypesAction WHERE code = 'CREATE_USER'),
        NEW.id, -- L'utilisateur créé
        CONCAT('Création de l\'utilisateur ', NEW.prenom, ' ', NEW.nom, ' (', NEW.email, ') - Service: ', 
               (SELECT nom FROM Services WHERE id = NEW.service_id), ' - Rôle: ', NEW.role_service),
        NOW()
    );
END //
DELIMITER ;

-- Trigger pour tracer les modifications d'utilisateurs
DELIMITER //
CREATE TRIGGER after_employe_update
AFTER UPDATE ON Employes
FOR EACH ROW
BEGIN
    DECLARE v_changes TEXT DEFAULT '';
    
    -- Détecter les changements
    IF NEW.nom != OLD.nom THEN
        SET v_changes = CONCAT(v_changes, 'Nom: ', OLD.nom, ' → ', NEW.nom, '; ');
    END IF;
    
    IF NEW.prenom != OLD.prenom THEN
        SET v_changes = CONCAT(v_changes, 'Prénom: ', OLD.prenom, ' → ', NEW.prenom, '; ');
    END IF;
    
    IF NEW.email != OLD.email THEN
        SET v_changes = CONCAT(v_changes, 'Email: ', OLD.email, ' → ', NEW.email, '; ');
    END IF;
    
    IF NEW.service_id != OLD.service_id THEN
        SET v_changes = CONCAT(v_changes, 'Service: ', 
            (SELECT nom FROM Services WHERE id = OLD.service_id), ' → ',
            (SELECT nom FROM Services WHERE id = NEW.service_id), '; ');
    END IF;
    
    IF NEW.role_service != OLD.role_service THEN
        SET v_changes = CONCAT(v_changes, 'Rôle: ', OLD.role_service, ' → ', NEW.role_service, '; ');
    END IF;
    
    IF NEW.est_actif != OLD.est_actif THEN
        SET v_changes = CONCAT(v_changes, 'Statut: ', 
            CASE WHEN OLD.est_actif THEN 'Actif' ELSE 'Inactif' END, ' → ',
            CASE WHEN NEW.est_actif THEN 'Actif' ELSE 'Inactif' END, '; ');
    END IF;
    
    -- Si des changements ont été détectés
    IF LENGTH(v_changes) > 0 THEN
        INSERT INTO HistoriqueActions (employe_id, type_action_id, employe_cible_id, details, date_action)
        VALUES (
            NEW.id, -- L'utilisateur qui a modifié (sera mis à jour par l'application)
            (SELECT id FROM TypesAction WHERE code = 'MODIFY_USER'),
            NEW.id, -- L'utilisateur modifié
            CONCAT('Modification de ', NEW.prenom, ' ', NEW.nom, ': ', v_changes),
            NOW()
        );
    END IF;
END //
DELIMITER ;

-- Trigger pour tracer la désactivation d'utilisateurs
DELIMITER //
CREATE TRIGGER after_employe_delete
AFTER UPDATE ON Employes
FOR EACH ROW
BEGIN
    -- Si l'utilisateur a été désactivé (est_actif = FALSE et date_suppression définie)
    IF OLD.est_actif = TRUE AND NEW.est_actif = FALSE AND NEW.date_suppression IS NOT NULL THEN
        INSERT INTO HistoriqueActions (employe_id, type_action_id, employe_cible_id, details, date_action)
        VALUES (
            NEW.id, -- L'utilisateur qui a désactivé (sera mis à jour par l'application)
            (SELECT id FROM TypesAction WHERE code = 'DELETE_USER'),
            NEW.id, -- L'utilisateur désactivé
            CONCAT('Désactivation de l\'utilisateur ', NEW.prenom, ' ', NEW.nom, ' (', NEW.email, ')'),
            NOW()
        );
    END IF;
END //
DELIMITER ;

-- Trigger pour tracer la création de dossiers
DELIMITER //
CREATE TRIGGER after_dossier_insert_trace
AFTER INSERT ON Dossiers
FOR EACH ROW
BEGIN
    INSERT INTO HistoriqueActions (employe_id, type_action_id, dossier_id, details, date_action)
    VALUES (
        NEW.createur_id,
        (SELECT id FROM TypesAction WHERE code = 'CREATE_FOLDER'),
        NEW.id,
        CONCAT('Création du dossier "', NEW.nom, '" dans le service ',
               COALESCE((SELECT nom FROM Services WHERE id = NEW.service_id), 'Aucun service')),
        NOW()
    );
END //
DELIMITER ;

-- Trigger pour tracer l'archivage/désarchivage de dossiers
DELIMITER //
CREATE TRIGGER after_dossier_archive_trace
AFTER UPDATE ON Dossiers
FOR EACH ROW
BEGIN
    -- Archivage
    IF OLD.est_archive = FALSE AND NEW.est_archive = TRUE THEN
        INSERT INTO HistoriqueActions (employe_id, type_action_id, dossier_id, details, date_action)
        VALUES (
            NEW.createur_id, -- Sera mis à jour par l'application avec l'utilisateur qui archive
            (SELECT id FROM TypesAction WHERE code = 'ARCHIVE_DOSSIER'),
            NEW.id,
            CONCAT('Archivage du dossier "', NEW.nom, '"'),
            NOW()
        );
    END IF;
    
    -- Désarchivage
    IF OLD.est_archive = TRUE AND NEW.est_archive = FALSE THEN
        INSERT INTO HistoriqueActions (employe_id, type_action_id, dossier_id, details, date_action)
        VALUES (
            NEW.createur_id, -- Sera mis à jour par l'application avec l'utilisateur qui désarchive
            (SELECT id FROM TypesAction WHERE code = 'UNARCHIVE_DOSSIER'),
            NEW.id,
            CONCAT('Désarchivage du dossier "', NEW.nom, '"'),
            NOW()
        );
    END IF;
END //
DELIMITER ;

-- Trigger pour tracer l'envoi de dossiers
DELIMITER //
CREATE TRIGGER after_envoi_dossier_insert_trace
AFTER INSERT ON EnvoiDossiers
FOR EACH ROW
BEGIN
    DECLARE v_dossier_nom VARCHAR(255);
    DECLARE v_destinataire VARCHAR(255);
    
    -- Récupérer le nom du dossier
    SELECT nom INTO v_dossier_nom FROM Dossiers WHERE id = NEW.dossier_id;
    
    -- Déterminer le destinataire
    IF NEW.type_envoi = 'SERVICE' THEN
        SET v_destinataire = CONCAT('Service: ', (SELECT nom FROM Services WHERE id = NEW.service_id));
    ELSE
        SET v_destinataire = CONCAT('Rôle: ', NEW.role_service, ' du service du dossier');
    END IF;
    
    INSERT INTO HistoriqueActions (employe_id, type_action_id, dossier_id, details, date_action)
    VALUES (
        NEW.envoyeur_id,
        (SELECT id FROM TypesAction WHERE code = 'SEND_DOSSIER'),
        NEW.dossier_id,
        CONCAT('Envoi du dossier "', v_dossier_nom, '" à ', v_destinataire),
        NOW()
    );
END //
DELIMITER ;

-- Trigger pour tracer la suppression/désactivation d'envois de dossiers
DELIMITER //
CREATE TRIGGER after_envoi_dossier_update_trace
AFTER UPDATE ON EnvoiDossiers
FOR EACH ROW
BEGIN
    DECLARE v_dossier_nom VARCHAR(255);
    DECLARE v_destinataire VARCHAR(255);
    
    -- Si l'envoi a été désactivé
    IF OLD.est_actif = TRUE AND NEW.est_actif = FALSE THEN
        -- Récupérer le nom du dossier
        SELECT nom INTO v_dossier_nom FROM Dossiers WHERE id = NEW.dossier_id;
        
        -- Déterminer le destinataire
        IF NEW.type_envoi = 'SERVICE' THEN
            SET v_destinataire = CONCAT('Service: ', (SELECT nom FROM Services WHERE id = NEW.service_id));
        ELSE
            SET v_destinataire = CONCAT('Rôle: ', NEW.role_service, ' du service du dossier');
        END IF;
        
        INSERT INTO HistoriqueActions (employe_id, type_action_id, dossier_id, details, date_action)
        VALUES (
            NEW.envoyeur_id,
            (SELECT id FROM TypesAction WHERE code = 'REMOVE_DOSSIER'),
            NEW.dossier_id,
            CONCAT('Retrait de l\'envoi du dossier "', v_dossier_nom, '" à ', v_destinataire),
            NOW()
        );
    END IF;
END //
DELIMITER ;

-- Trigger pour tracer l'upload de documents
DELIMITER //
CREATE TRIGGER after_document_insert_trace
AFTER INSERT ON Documents
FOR EACH ROW
BEGIN
    DECLARE v_dossier_nom VARCHAR(255);
    
    -- Récupérer le nom du dossier
    SELECT nom INTO v_dossier_nom FROM Dossiers WHERE id = NEW.dossier_id;
    
    INSERT INTO HistoriqueActions (employe_id, type_action_id, document_id, dossier_id, details, date_action)
    VALUES (
        NEW.uploader_id,
        (SELECT id FROM TypesAction WHERE code = 'UPLOAD_DOC'),
        NEW.id,
        NEW.dossier_id,
        CONCAT('Upload du document "', NEW.nom, '" dans le dossier "', v_dossier_nom, '"'),
        NOW()
    );
END //
DELIMITER ;

-- Trigger pour tracer la suppression de documents
DELIMITER //
CREATE TRIGGER after_document_delete_trace
AFTER DELETE ON Documents
FOR EACH ROW
BEGIN
    DECLARE v_dossier_nom VARCHAR(255);
    
    -- Récupérer le nom du dossier
    SELECT nom INTO v_dossier_nom FROM Dossiers WHERE id = OLD.dossier_id;
    
    INSERT INTO HistoriqueActions (employe_id, type_action_id, document_id, dossier_id, details, date_action)
    VALUES (
        OLD.uploader_id, -- Sera mis à jour par l'application avec l'utilisateur qui supprime
        (SELECT id FROM TypesAction WHERE code = 'DELETE_DOC'),
        OLD.id,
        OLD.dossier_id,
        CONCAT('Suppression du document "', OLD.nom, '" du dossier "', v_dossier_nom, '"'),
        NOW()
    );
END //
DELIMITER ;