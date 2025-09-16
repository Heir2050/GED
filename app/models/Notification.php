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
}