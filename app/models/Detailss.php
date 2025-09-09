<?php

namespace Model;

defined('ROOTPATH') or exit('Access Denied!');

/**
 * Category class
 */
class Detailss
{

    use Model;

    protected $table = 'document_attachments';

    protected $allowedColumns = [
        'document_id',
        'file_path',
        'uploaded_by',
        'action',
        'comment',
        'created_at'
    ];

    public function validate($data)
    {

        $this->errors = [];

        // if (empty($data['document_id'])) {
        //     $this->errors['document_id'] = "ID du document requis";
        // }

        // if (empty($data['file_path'])) {
        //     $this->errors['file_path'] = "Chemin du fichier requis";
        // }

        // if (empty($data['action']) || !in_array($data['action'], ['approval', 'rejection'])) {
        //     $this->errors['action'] = "Action invalide";
        // }
        

        if (empty($this->errors)) {
            return true;
        }

        return false;
    }
}
