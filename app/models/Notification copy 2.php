<?php

namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

class Notification
{

    use Model;

    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $allowedColumns = [
        'recipient_user_id', 'actor_user_id', 'document_id', 
        'service_id', 'action', 'message', 'is_read', 'created_at'
    ];

    // ... (méthodes existantes)
    public function getUnreadCount($user_id)
    {
        $query = "SELECT COUNT(*) as total FROM notifications WHERE recipient_user_id = :uid AND is_read = 0";
        $row = $this->get_row($query, ['uid' => $user_id]);
        return $row ? $row->total : 0;
    }

    public function getUserNotifications($user_id, $limit = 10)
    {
        $query = "SELECT n.*, u.nom as actor_name, u.prenom 
                FROM notifications n
                LEFT JOIN users u ON n.actor_user_id = u.id
                WHERE n.recipient_user_id = :uid 
                ORDER BY n.created_at DESC 
                LIMIT $limit";
        
        return $this->query($query, ['uid' => $user_id]);
    }

    public function markAsRead($id, $user_id)
    {
        $query = "UPDATE notifications SET is_read = 1, read_at = NOW() WHERE id = :id AND recipient_user_id = :uid";
        return $this->query($query, ['id' => $id, 'uid' => $user_id]);
    }
    // ... (méthodes existantes)

    /**
     * Crée des notifications pour tous les employés d'un service
     */
    public function notifyServiceEmployees($document_id, $service_id, $actor_id, $document_name, $dossier_name)
    {
        $employeModel = new \Model\Employes();
        $actor = $employeModel->first(['id' => $actor_id]);
        $actor_name = $actor ? $actor->prenom . ' ' . $actor->nom : 'Un collègue';

        // Récupérer tous les employés du service (sauf l'acteur)
        $employees = $employeModel->where(['service_id' => $service_id, 'est_actif' => 1]);
        
        $notifications_created = 0;
        
        foreach ($employees as $employee) {
            // Ne pas notifier l'utilisateur qui a uploadé le document
            if ($employee->id == $actor_id) {
                continue;
            }

            $message = "📄 $actor_name a téléversé un nouveau document : \"$document_name\" dans le dossier \"$dossier_name\"";

            $notification_data = [
                'recipient_user_id' => $employee->id,
                'actor_user_id' => $actor_id,
                'document_id' => $document_id,
                'service_id' => $service_id,
                'action' => 'document_uploaded',
                'message' => $message,
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];

            if ($this->insert($notification_data)) {
                $notifications_created++;
            }
        }

        return $notifications_created;
    }

    /**
     * Marque une notification comme lue basée sur le document et l'utilisateur
     */
    public function markAsOpenedByDocument($document_id, $user_id)
    {
        $query = "UPDATE notifications 
                  SET is_read = 1, date_ouverture = NOW() 
                  WHERE document_id = :document_id 
                  AND recipient_user_id = :user_id 
                  AND is_read = 0";
        
        return $this->query($query, [
            'document_id' => $document_id,
            'user_id' => $user_id
        ]);
    }

    /**
     * Récupère les notifications non lues pour un utilisateur
     */
    public function getUnreadNotifications($user_id, $limit = 10)
    {
        $query = "SELECT n.*, u.nom as actor_name, u.prenom, d.nom as document_name
                  FROM notifications n 
                  LEFT JOIN users u ON n.actor_user_id = u.id 
                  LEFT JOIN documents d ON n.document_id = d.id
                  WHERE n.recipient_user_id = :user_id 
                  AND n.is_read = 0
                  ORDER BY n.created_at DESC 
                  LIMIT :limit";
        
        return $this->query($query, ['user_id' => $user_id, 'limit' => $limit]);
    }

    /**
     * Marque toutes les notifications comme lues pour un utilisateur
     */
    public function markAllAsRead($user_id)
    {
        $query = "UPDATE notifications 
                  SET is_read = 1, date_ouverture = NOW() 
                  WHERE recipient_user_id = :user_id 
                  AND is_read = 0";
        
        return $this->query($query, ['user_id' => $user_id]);
    }
}