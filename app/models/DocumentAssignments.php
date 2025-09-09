<?php
namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

class DocumentAssignments
{

    use Model;

    protected $table = 'document_assignments';
    protected $allowedColumns = [
        'document_id',
        'assigned_to',
        'assigned_by',
        'assigned_at',
        'status',
        'type' // Ajout pour distinguer les types d'assignations
    ];

    public function validate($data) {
        
    }

    // Assigner automatiquement aux admins/secrétaires
    public function assignToAdmins($document_id, $assigned_by, $type = 'conge')
    {
        $userModel = new \Model\User();
        $admins = $userModel->where(['role' => ['admin', 'secretaire']]);
        
        foreach ($admins as $admin) {
            $this->insert([
                'document_id' => $document_id,
                'assigned_to' => $admin->id,
                'assigned_by' => $assigned_by,
                'assigned_at' => date('Y-m-d H:i:s'),
                'status' => 'non_lu',
                'type' => $type
            ]);
        }
    }

    // Récupérer les utilisateurs assignés à un document
    public function getAssignedUsers($document_id) {
        return $this->where(['document_id' => $document_id]);
    }

    // Récupérer les documents assignés à un utilisateur
    public function getAssignedDocuments($user_id) {
        return $this->where(['assigned_to' => $user_id]);
    }
}