<?php
namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

class CongeAttachment
{
    use Model;

    protected $table = 'conge_attachments';
    protected $allowedColumns = [
        'conge_id',
        'file_path',
        'user_id',
        'action',
        'comment',
        'created_at'
    ];

    public $errors = [];

    public function validate($data)
    {
        $this->errors = [];
        if (empty($data['conge_id'])) $this->errors[] = "ID congé manquant.";
        if (empty($data['file_path'])) $this->errors[] = "Fichier manquant.";
        if (empty($data['user_id'])) $this->errors[] = "Utilisateur manquant.";
        if (empty($data['action']) || !in_array($data['action'], ['approuve', 'refuse'])) $this->errors[] = "Action invalide.";
        return empty($this->errors);
    }
}