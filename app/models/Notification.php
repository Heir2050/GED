<?php
namespace Model;

defined('ROOTPATH') or exit('Access Denied!');

class Notification
{

    use  Model;

    protected $table = 'Notifications';
    protected $primaryKey = 'id';
    protected $allowedColumns = [
        'dossier_id', 'service_id', 'uploader_id', 'recipient_id',
        'date_notification', 'message', 'is_read', 'date_lecture'
    ];

    public function getUserNotifications($employe_id)
    {
        $query = "SELECT n.*, e.nom as uploader_nom, e.prenom as uploader_prenom, 
                         d.nom as dossier_nom, COUNT(doc.id) as nb_documents
                  FROM Notifications n 
                  LEFT JOIN Employes e ON n.uploader_id = e.id 
                  LEFT JOIN Dossiers d ON n.dossier_id = d.id
                  LEFT JOIN Documents doc ON doc.dossier_id = n.dossier_id 
                    AND DATE(doc.date_upload) = DATE(n.date_notification)
                  WHERE n.recipient_id = :employe_id 
                  GROUP BY n.id
                  ORDER BY n.date_notification DESC";
        
        return $this->query($query, ['employe_id' => $employe_id]);
    }

    public function getUnreadNotifications($employe_id, $limit = 10)
    {
        try {
            // Requête de base sans le COUNT qui peut causer des problèmes
            $query = "SELECT n.*, e.nom as uploader_nom, e.prenom as uploader_prenom, 
                            d.nom as dossier_nom
                    FROM Notifications n 
                    LEFT JOIN Employes e ON n.uploader_id = e.id 
                    LEFT JOIN Dossiers d ON n.dossier_id = d.id
                    WHERE n.recipient_id = :employe_id 
                    AND n.is_read = FALSE
                    ORDER BY n.date_notification DESC";
            
            $results = $this->query($query, ['employe_id' => $employe_id]);
            
            // Si la requête échoue, retourner un tableau vide
            if ($results === false) {
                error_log("Erreur SQL dans getUnreadNotifications: ");
                return [];
            }
            
            // Pour chaque notification, compter le nombre de documents ajoutés ce jour-là
            if (is_array($results)) {
                foreach ($results as &$notification) {
                    $doc_count_query = "SELECT COUNT(*) as nb_documents 
                                    FROM Documents 
                                    WHERE dossier_id = :dossier_id 
                                    AND DATE(date_upload) = DATE(:notification_date)";
                    
                    $doc_count = $this->query($doc_count_query, [
                        'dossier_id' => $notification->dossier_id,
                        'notification_date' => $notification->date_notification
                    ]);
                    
                    $notification->nb_documents = $doc_count[0]->nb_documents ?? 0;
                }
            }
            
            // Appliquer la limite
            if ($limit > 0 && is_array($results)) {
                return array_slice($results, 0, $limit);
            }
            
            return $results;
            
        } catch (Exception $e) {
            error_log("Exception dans getUnreadNotifications: " . $e->getMessage());
            return [];
        }
    }

    public function getUnreadCount($employe_id)
    {
        $query = "SELECT COUNT(*) as count 
                  FROM Notifications 
                  WHERE recipient_id = :employe_id AND is_read = FALSE";
        
        $result = $this->query($query, ['employe_id' => $employe_id]);
        return $result[0]->count ?? 0;
    }

    public function markAsRead($notification_id, $employe_id)
    {
        $query = "UPDATE Notifications 
                  SET is_read = TRUE, date_lecture = NOW()
                  WHERE id = :id AND recipient_id = :employe_id";
        
        return $this->query($query, ['id' => $notification_id, 'employe_id' => $employe_id]);
    }

    public function markAllAsRead($employe_id)
    {
        $query = "UPDATE Notifications 
                  SET is_read = TRUE, date_lecture = NOW()
                  WHERE recipient_id = :employe_id AND is_read = FALSE";
        
        return $this->query($query, ['employe_id' => $employe_id]);
    }

    public function getNotificationStats($employe_id)
    {
        $query = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN is_read = TRUE THEN 1 ELSE 0 END) as lus,
                    SUM(CASE WHEN is_read = FALSE THEN 1 ELSE 0 END) as non_lus
                  FROM Notifications 
                  WHERE recipient_id = :employe_id";
        
        $result = $this->query($query, ['employe_id' => $employe_id]);
        return $result[0] ?? null;
    }

    public function getDossierViewers($dossier_id)
    {
        $query = "SELECT n.*, e.nom, e.prenom, e.email, s.nom as service_nom
                  FROM Notifications n
                  JOIN Employes e ON n.recipient_id = e.id
                  JOIN Services s ON e.service_id = s.id
                  WHERE n.dossier_id = :dossier_id
                  AND n.is_read = TRUE
                  ORDER BY n.date_lecture DESC";
        
        return $this->query($query, ['dossier_id' => $dossier_id]);
    }

    public function getDossierNotifications($dossier_id)
    {
        $query = "SELECT n.*, e.nom as uploader_nom, e.prenom as uploader_prenom,
                         COUNT(doc.id) as nb_documents_ajoutes
                  FROM Notifications n
                  LEFT JOIN Employes e ON n.uploader_id = e.id
                  LEFT JOIN Documents doc ON doc.dossier_id = n.dossier_id 
                    AND DATE(doc.date_upload) = DATE(n.date_notification)
                  WHERE n.dossier_id = :dossier_id
                  GROUP BY n.id
                  ORDER BY n.date_notification DESC";
        
        return $this->query($query, ['dossier_id' => $dossier_id]);
    }




    public function getUserActionsForDossier($dossier_id)
    {
        $query = "SELECT 
                    e.id, 
                    e.nom, 
                    e.prenom, 
                    e.email, 
                    e.photo,
                    s.nom as service_nom,
                    MAX(n.date_lecture) as derniere_consultation,
                    COUNT(DISTINCT CASE WHEN n.is_read = TRUE THEN n.id END) as notifications_vues,
                    COUNT(DISTINCT cd.id) as documents_consultes,
                    COUNT(DISTINCT CASE WHEN cd.type_consultation = 'TELECHARGEMENT' THEN cd.id END) as documents_telecharges
                FROM Employes e
                INNER JOIN Services s ON e.service_id = s.id
                LEFT JOIN Notifications n ON e.id = n.recipient_id AND n.dossier_id = :dossier_id
                LEFT JOIN ConsultationsDocuments cd ON e.id = cd.employe_id 
                    AND cd.document_id IN (SELECT id FROM Documents WHERE dossier_id = :dossier_id2)
                WHERE e.service_id = (SELECT service_id FROM Dossiers WHERE id = :dossier_id3)
                AND e.est_actif = TRUE
                GROUP BY e.id
                ORDER BY e.nom, e.prenom";
        
        return $this->query($query, [
            'dossier_id' => $dossier_id,
            'dossier_id2' => $dossier_id,
            'dossier_id3' => $dossier_id
        ]);
    }










    // public function getDossierViewers($dossier_id)
    // {
    //     $query = "SELECT DISTINCT e.id, e.nom, e.prenom, e.email, e.photo,
    //                     s.nom as service_nom, n.date_lecture
    //             FROM Notifications n
    //             INNER JOIN Employes e ON n.recipient_id = e.id
    //             INNER JOIN Services s ON e.service_id = s.id
    //             WHERE n.dossier_id = :dossier_id
    //             AND n.is_read = TRUE
    //             AND n.date_lecture IS NOT NULL
    //             ORDER BY n.date_lecture DESC";
        
    //     return $this->query($query, ['dossier_id' => $dossier_id]);
    // }

    /**
     * Récupère les statistiques de consultation d'un dossier
     */
    public function getDossierViewStats($dossier_id)
    {
        $query = "SELECT 
                    COUNT(DISTINCT n.recipient_id) as total_viewers,
                    COUNT(*) as total_notifications,
                    SUM(CASE WHEN n.is_read = TRUE THEN 1 ELSE 0 END) as notifications_lues,
                    MIN(n.date_lecture) as premiere_consultation,
                    MAX(n.date_lecture) as derniere_consultation
                FROM Notifications n
                WHERE n.dossier_id = :dossier_id";
        
        $result = $this->query($query, ['dossier_id' => $dossier_id]);
        return $result[0] ?? null;
    }

    /**
     * Récupère les employés qui n'ont pas encore consulté le dossier
     */
    public function getDossierNonViewers($dossier_id, $service_id)
    {
        $query = "SELECT e.*, s.nom as service_nom
                FROM Employes e
                INNER JOIN Services s ON e.service_id = s.id
                WHERE e.service_id = :service_id
                AND e.est_actif = TRUE
                AND e.id NOT IN (
                    SELECT DISTINCT n.recipient_id
                    FROM Notifications n
                    WHERE n.dossier_id = :dossier_id
                    AND n.is_read = TRUE
                )
                ORDER BY e.nom, e.prenom";
        
        return $this->query($query, [
            'dossier_id' => $dossier_id,
            'service_id' => $service_id
        ]);
    }

    /**
     * Créer des notifications pour tous les employés d'un service quand un dossier leur est envoyé
     */
    public function notifierEnvoiDossier($dossier_id, $service_id, $envoyeur_id, $type_envoi, $role_service = null)
    {
        $employeModel = new \Model\Employes();
        $dossierModel = new \Model\Dossiers();
        
        // Récupérer les informations du dossier et de l'envoyeur
        $dossier = $dossierModel->first(['id' => $dossier_id]);
        $envoyeur = $employeModel->first(['id' => $envoyeur_id]);
        
        if (!$dossier || !$envoyeur) {
            return false;
        }
        
        $envoyeur_nom = $envoyeur->prenom . ' ' . $envoyeur->nom;
        $dossier_nom = $dossier->nom;
        
        // Construire le message selon le type d'envoi
        if ($type_envoi == 'SERVICE') {
            $message = "$envoyeur_nom vous a envoyé le dossier '$dossier_nom'";
        } else if ($type_envoi == 'ROLE_SERVICE') {
            $message = "$envoyeur_nom vous a envoyé le dossier '$dossier_nom' (pour votre rôle: $role_service)";
        } else {
            $message = "$envoyeur_nom vous a envoyé le dossier '$dossier_nom'";
        }
        
        // Récupérer les employés du service destinataire
        $where_conditions = [
            'service_id' => $service_id,
            'est_actif' => true
        ];
        
        // Si c'est un envoi par rôle, filtrer par rôle
        if ($type_envoi == 'ROLE_SERVICE' && $role_service) {
            $where_conditions['role_service'] = $role_service;
        }
        
        $employes = $employeModel->where($where_conditions);
        
        if (!$employes) {
            return false;
        }
        
        $notifications_creees = 0;
        
        // Créer une notification pour chaque employé du service
        foreach ($employes as $employe) {
            $notification_data = [
                'dossier_id' => $dossier_id,
                'service_id' => $service_id,
                'uploader_id' => $envoyeur_id,
                'recipient_id' => $employe->id,
                'date_notification' => date('Y-m-d H:i:s'),
                'message' => $message,
                'is_read' => false,
                'date_lecture' => null
            ];
            
            if ($this->insert($notification_data)) {
                $notifications_creees++;
            }
        }
        
        error_log("Notifications d'envoi de dossier créées : $notifications_creees pour le dossier $dossier_id vers le service $service_id");
        
        return $notifications_creees;
    }

    /**
     * Récupérer les notifications d'envoi de dossiers pour un employé
     */
    public function getNotificationsEnvoiDossier($employe_id)
    {
        $query = "SELECT n.*, e.nom as envoyeur_nom, e.prenom as envoyeur_prenom, 
                         d.nom as dossier_nom, s.nom as service_envoyeur_nom
                  FROM Notifications n 
                  LEFT JOIN Employes e ON n.uploader_id = e.id 
                  LEFT JOIN Dossiers d ON n.dossier_id = d.id
                  LEFT JOIN Services s ON e.service_id = s.id
                  WHERE n.recipient_id = :employe_id 
                  AND n.message LIKE '%vous a envoyé le dossier%'
                  ORDER BY n.date_notification DESC";
        
        return $this->query($query, ['employe_id' => $employe_id]);
    }
    
}