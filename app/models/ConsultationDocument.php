<?php
namespace Model;

defined('ROOTPATH') or exit('Access Denied!');

class ConsultationDocument
{

    use Model;

    protected $table = 'ConsultationsDocuments';
    protected $primaryKey = 'id';
    protected $allowedColumns = [
        'document_id', 'employe_id', 'date_consultation', 
        'type_consultation', 'adresse_ip', 'user_agent'
    ];

    /**
     * Enregistre une action sur un document
     */
    public function trackAction($document_id, $employe_id, $action_type, $ip = null, $user_agent = null)
    {
        $data = [
            'document_id' => $document_id,
            'employe_id' => $employe_id,
            'type_consultation' => $action_type,
            'date_consultation' => date('Y-m-d H:i:s'),
            'adresse_ip' => $ip,
            'user_agent' => $user_agent
        ];
        
        return $this->insert($data);
    }

    /**
     * Récupère toutes les actions d'un utilisateur sur un dossier
     */
    public function getUserActionsForDossier($employe_id, $dossier_id)
    {
        $query = "SELECT 
                    cd.*,
                    d.nom as document_nom,
                    d.type as document_type,
                    d.taille as document_taille,
                    cd.type_consultation as action,
                    cd.date_consultation as date_action
                  FROM ConsultationsDocuments cd
                  INNER JOIN Documents d ON cd.document_id = d.id
                  WHERE cd.employe_id = :employe_id
                  AND d.dossier_id = :dossier_id
                  ORDER BY cd.date_consultation DESC";
        
        return $this->query($query, [
            'employe_id' => $employe_id,
            'dossier_id' => $dossier_id
        ]);
    }

    /**
     * Récupère le détail des actions d'un utilisateur spécifique
     */
    public function getUserActionDetails($employe_id, $dossier_id)
    {
        $query = "SELECT 
                    COUNT(*) as total_actions,
                    COUNT(CASE WHEN cd.type_consultation = 'OUVERTURE' THEN 1 END) as ouvertures,
                    COUNT(CASE WHEN cd.type_consultation = 'TELECHARGEMENT' THEN 1 END) as telechargements,
                    COUNT(CASE WHEN cd.type_consultation = 'MODIFICATION' THEN 1 END) as modifications,
                    COUNT(CASE WHEN cd.type_consultation = 'SUPPRESSION' THEN 1 END) as suppressions,
                    MIN(cd.date_consultation) as premiere_action,
                    MAX(cd.date_consultation) as derniere_action,
                    COUNT(DISTINCT cd.document_id) as documents_différents
                  FROM ConsultationsDocuments cd
                  INNER JOIN Documents d ON cd.document_id = d.id
                  WHERE cd.employe_id = :employe_id
                  AND d.dossier_id = :dossier_id";
        
        $result = $this->query($query, [
            'employe_id' => $employe_id,
            'dossier_id' => $dossier_id
        ]);
        
        return $result[0] ?? null;
    }

    /**
     * Récupère l'historique complet des actions d'un utilisateur
     */
    public function getUserActionHistory($employe_id, $limit = 50)
    {
        $query = "SELECT 
                    cd.*,
                    d.nom as document_nom,
                    d.dossier_id,
                    dos.nom as dossier_nom,
                    CASE cd.type_consultation 
                        WHEN 'OUVERTURE' THEN 'Ouverture'
                        WHEN 'TELECHARGEMENT' THEN 'Téléchargement'
                        WHEN 'MODIFICATION' THEN 'Modification'
                        WHEN 'SUPPRESSION' THEN 'Suppression'
                        ELSE cd.type_consultation
                    END as action_type
                  FROM ConsultationsDocuments cd
                  INNER JOIN Documents d ON cd.document_id = d.id
                  INNER JOIN Dossiers dos ON d.dossier_id = dos.id
                  WHERE cd.employe_id = :employe_id
                  ORDER BY cd.date_consultation DESC
                  LIMIT :limit";
        
        return $this->query($query, [
            'employe_id' => $employe_id,
            'limit' => $limit
        ]);
    }
}