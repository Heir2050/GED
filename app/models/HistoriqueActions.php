<?php
namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

class HistoriqueActions
{
    use Model;

    protected $table = 'historiqueactions';
    protected $allowedColumns = [
        'id',
        'employe_id',
        'type_action_id', 
        'document_id',
        'dossier_id',
        'employe_cible_id',
        'details',
        'date_action',
        'adresse_ip'
    ];

    /**
     * Enregistrer une action dans l'historique
     */
    public function enregistrerAction($employe_id, $type_action, $details = '', $document_id = null, $dossier_id = null, $employe_cible_id = null)
    {
        // Récupérer l'ID du type d'action
        $typeActionModel = new TypesAction();
        $type_action_obj = $typeActionModel->first(['code' => $type_action]);
        
        if (!$type_action_obj) {
            error_log("Type d'action non trouvé: $type_action");
            return false;
        }

        $data = [
            'employe_id' => $employe_id,
            'type_action_id' => $type_action_obj->id,
            'document_id' => $document_id,
            'dossier_id' => $dossier_id,
            'employe_cible_id' => $employe_cible_id,
            'details' => $details,
            'date_action' => date('Y-m-d H:i:s'),
            'adresse_ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
        ];

        return $this->insert($data);
    }

    /**
     * Récupérer l'historique d'un employé
     */
    public function getHistoriqueEmploye($employe_id, $limit = 50)
    {
        $query = "
            SELECT ha.*, ta.code, ta.description, 
                   e.nom, e.prenom, e.photo,
                   d.nom as document_nom,
                   dos.nom as dossier_nom,
                   ec.nom as employe_cible_nom, ec.prenom as employe_cible_prenom
            FROM historiqueactions ha
            JOIN typesaction ta ON ha.type_action_id = ta.id
            JOIN employes e ON ha.employe_id = e.id
            LEFT JOIN documents d ON ha.document_id = d.id
            LEFT JOIN dossiers dos ON ha.dossier_id = dos.id
            LEFT JOIN employes ec ON ha.employe_cible_id = ec.id
            WHERE ha.employe_id = :employe_id
            ORDER BY ha.date_action DESC
            LIMIT $limit
        ";

        return $this->query($query, ['employe_id' => $employe_id]);
    }

    /**
     * Récupérer tout l'historique (pour les admins)
     */
    public function getHistoriqueComplet($limit = 100)
    {
        $query = "
            SELECT ha.*, ta.code, ta.description, 
                   e.nom, e.prenom, e.photo,
                   d.nom as document_nom,
                   dos.nom as dossier_nom,
                   ec.nom as employe_cible_nom, ec.prenom as employe_cible_prenom
            FROM historiqueactions ha
            JOIN typesaction ta ON ha.type_action_id = ta.id
            JOIN employes e ON ha.employe_id = e.id
            LEFT JOIN documents d ON ha.document_id = d.id
            LEFT JOIN dossiers dos ON ha.dossier_id = dos.id
            LEFT JOIN employes ec ON ha.employe_cible_id = ec.id
            ORDER BY ha.date_action DESC
            LIMIT $limit
        ";

        return $this->query($query);
    }

    /**
     * Récupérer l'historique par type d'action
     */
    public function getHistoriqueParType($type_action, $limit = 50)
    {
        $query = "
            SELECT ha.*, ta.code, ta.description, 
                   e.nom, e.prenom, e.photo,
                   d.nom as document_nom,
                   dos.nom as dossier_nom
            FROM historiqueactions ha
            JOIN typesaction ta ON ha.type_action_id = ta.id
            JOIN employes e ON ha.employe_id = e.id
            LEFT JOIN documents d ON ha.document_id = d.id
            LEFT JOIN dossiers dos ON ha.dossier_id = dos.id
            WHERE ta.code = :type_action
            ORDER BY ha.date_action DESC
            LIMIT $limit
        ";

        return $this->query($query, ['type_action' => $type_action]);
    }
}