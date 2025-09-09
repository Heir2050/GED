<?php

namespace Model;

defined('ROOTPATH') or exit('Access Denied!');

/**
 * Category class
 */
class DetailConge
{

    use Model;

    protected $table = 'demandes_conges';

    protected $allowedColumns = [
        'user_id',
        'date_debut',
        'date_fin',
        'raison',
        'document_path',
        'statut',
        'date_demande',
        'date_traitement',
        'traite_par'
    ];

    public function validate($data)
    {

        $this->errors = [];

        

        if (empty($this->errors)) {
            return true;
        }

        return false;
    }
}
