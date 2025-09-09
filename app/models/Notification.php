<?php

namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

class Notification
{
    use Model;

    protected $table = 'notifications';

    protected $allowedColumns = [
        'recipient_user_id',
        'actor_user_id',
        'document_id',
        'conge_id',
        'action',
        'message',
        'is_read',
        'created_at',
        'read_at'
    ];

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
}