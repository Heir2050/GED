<?php

namespace Model;

defined('ROOTPATH') or exit('Access Denied!');

/**
 * Uploads class
 */
class Uploads
{

    use Model;

    protected $table = 'uploads';

    protected $allowedColumns = [
        'type',
        'file', // This is the file name, not the path
        'title',
        'objet',
        'description',
        'file_path',

        'folder_id',
        'uploaded_by',
        'status_id',

        'numero_interne',
        'numero_reception',
        'date_envoye',
        'date_recu',
        'date_signature',
        'numero_reference',
        'envoyeur',

        'numero_expedie',
        'numero_expedition',
        'date_expedition',
        'institution_destinataire',

        'created_at',
    ];

    public function validate($files_data, $data, $id = null)
    {
        $this->errors = [];

        if (empty($data['title'])) {
            $this->errors['title'] = "Title is required";
        }

        if (empty($data['objet'])) {
            $this->errors['objet'] = "Objet is required";
        }
        if (empty($data['description'])) {
            $this->errors['description'] = "Description is required";
        }

        if (empty($this->errors)) {
            return true;
        }

        return false;
    }

    public function create_table()
    {
        $query = "CREATE TABLE IF NOT EXISTS categories(
			
		)";

        $this->query($query);
    }
}
