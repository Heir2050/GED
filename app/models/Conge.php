<?php
namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

class Conge
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

    public function createConge($data)
    {
        $data['statut'] = 'en_attente';
        return $this->insert($data); // Doit retourner l'ID inséré
    }

}