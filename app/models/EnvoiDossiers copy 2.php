<?php
namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

class EnvoiDossiers
{
    use Model;

    protected $table = 'envoidossiers';
    protected $allowedColumns = [
        'id',
        'dossier_id',
        'type_envoi',
        'service_id',
        'role_service',
        'date_envoi',
        'envoyeur_id',
        'est_actif'
    ];

    /**
     * Envoyer un dossier à un service ou rôle spécifique
     */
    // Dans EnvoiDossiers.php, méthode envoyerDossier()
    /**
     * Envoyer un dossier à un service ou rôle spécifique - Version avec requête directe
     */
    public function envoyerDossier($dossier_id, $type_envoi, $service_id = null, $role_service = null, $envoyeur_id)
    {
        // CORRECTION : Vérifier l'existence avec une requête SQL précise
        $query = "
            SELECT COUNT(*) as count 
            FROM envoidossiers 
            WHERE dossier_id = :dossier_id 
            AND type_envoi = :type_envoi 
            AND service_id = :service_id 
            AND est_actif = true
        ";
        
        $params = [
            'dossier_id' => $dossier_id,
            'type_envoi' => $type_envoi,
            'service_id' => $service_id
        ];
        
        // CORRECTION : Ajouter la condition pour le rôle selon le type d'envoi
        if ($type_envoi == 'ROLE_SERVICE') {
            $query .= " AND role_service = :role_service";
            $params['role_service'] = $role_service;
        } else {
            $query .= " AND role_service IS NULL";
        }
        
        $result = $this->query($query, $params);
        
        if ($result && $result[0]->count > 0) {
            error_log("Envoi déjà existant - Dossier: $dossier_id, Type: $type_envoi, Service: $service_id, Rôle: " . ($role_service ?? 'NULL'));
            return false;
        }
        
        // Créer l'envoi
        $data = [
            'dossier_id' => $dossier_id,
            'type_envoi' => $type_envoi,
            'service_id' => $service_id,
            'role_service' => ($type_envoi == 'SERVICE') ? null : $role_service,
            'envoyeur_id' => $envoyeur_id,
            'date_envoi' => date('Y-m-d H:i:s'),
            'est_actif' => true
        ];
        
        $insertResult = $this->insert($data);
        
        if ($insertResult) {
            error_log("Envoi créé avec succès - Dossier: $dossier_id, Type: $type_envoi, Service: $service_id, Rôle: " . ($role_service ?? 'NULL'));
            
            // Ajouter automatiquement les utilisateurs du service destinataire dans etatdossierutilisateur
            $this->ajouterUtilisateursServiceDestinataire($dossier_id, $service_id, $type_envoi, $role_service);
        }
        
        return $insertResult;
    }

    /**
     * Ajouter les utilisateurs du service destinataire dans etatdossierutilisateur
     */
    public function ajouterUtilisateursServiceDestinataire($dossier_id, $service_id, $type_envoi, $role_service)
    {
        // Construire la requête selon le type d'envoi
        if ($type_envoi == 'SERVICE') {
            // Ajouter tous les employés du service
            $query = "
                INSERT IGNORE INTO etatdossierutilisateur (dossier_id, employe_id, etat)
                SELECT :dossier_id, e.id, 'NON_OUVERT'
                FROM employes e
                WHERE e.service_id = :service_id 
                AND e.est_actif = TRUE
            ";
            $params = [
                'dossier_id' => $dossier_id,
                'service_id' => $service_id
            ];
        } else if ($type_envoi == 'ROLE_SERVICE') {
            // Ajouter seulement les employés avec le rôle spécifique
            $query = "
                INSERT IGNORE INTO etatdossierutilisateur (dossier_id, employe_id, etat)
                SELECT :dossier_id, e.id, 'NON_OUVERT'
                FROM employes e
                WHERE e.service_id = :service_id 
                AND e.role_service = :role_service
                AND e.est_actif = TRUE
            ";
            $params = [
                'dossier_id' => $dossier_id,
                'service_id' => $service_id,
                'role_service' => $role_service
            ];
        } else {
            return false; // Type d'envoi non supporté
        }
        
        $result = $this->query($query, $params);
        
        if ($result) {
            error_log("Utilisateurs du service destinataire ajoutés dans etatdossierutilisateur - Dossier: $dossier_id, Service: $service_id, Type: $type_envoi");
        } else {
            error_log("Erreur lors de l'ajout des utilisateurs du service destinataire - Dossier: $dossier_id, Service: $service_id, Type: $type_envoi");
        }
        
        return $result;
    }

    /**
     * Retirer un envoi de dossier
     */
    public function retirerEnvoi($envoi_id)
    {
        // Récupérer les informations de l'envoi avant de le désactiver
        $envoi = $this->first(['id' => $envoi_id]);
        
        if (!$envoi) {
            return false;
        }
        
        // Désactiver l'envoi
        $result = $this->update($envoi_id, ['est_actif' => false]);
        
        if ($result) {
            // Supprimer les utilisateurs du service destinataire de etatdossierutilisateur
            $this->supprimerUtilisateursServiceDestinataire($envoi->dossier_id, $envoi->service_id, $envoi->type_envoi, $envoi->role_service);
        }
        
        return $result;
    }

    /**
     * Supprimer les utilisateurs du service destinataire de etatdossierutilisateur
     */
    private function supprimerUtilisateursServiceDestinataire($dossier_id, $service_id, $type_envoi, $role_service)
    {
        // Construire la requête selon le type d'envoi
        if ($type_envoi == 'SERVICE') {
            // Supprimer tous les employés du service
            $query = "
                DELETE FROM etatdossierutilisateur 
                WHERE dossier_id = :dossier_id 
                AND employe_id IN (
                    SELECT e.id 
                    FROM employes e 
                    WHERE e.service_id = :service_id 
                    AND e.est_actif = TRUE
                )
            ";
            $params = [
                'dossier_id' => $dossier_id,
                'service_id' => $service_id
            ];
        } else if ($type_envoi == 'ROLE_SERVICE') {
            // Supprimer seulement les employés avec le rôle spécifique
            $query = "
                DELETE FROM etatdossierutilisateur 
                WHERE dossier_id = :dossier_id 
                AND employe_id IN (
                    SELECT e.id 
                    FROM employes e 
                    WHERE e.service_id = :service_id 
                    AND e.role_service = :role_service
                    AND e.est_actif = TRUE
                )
            ";
            $params = [
                'dossier_id' => $dossier_id,
                'service_id' => $service_id,
                'role_service' => $role_service
            ];
        } else {
            return false; // Type d'envoi non supporté
        }
        
        $result = $this->query($query, $params);
        
        if ($result) {
            error_log("Utilisateurs du service destinataire supprimés de etatdossierutilisateur - Dossier: $dossier_id, Service: $service_id, Type: $type_envoi");
        } else {
            error_log("Erreur lors de la suppression des utilisateurs du service destinataire - Dossier: $dossier_id, Service: $service_id, Type: $type_envoi");
        }
        
        return $result;
    }

    /**
     * Récupérer les dossiers envoyés à un utilisateur
     */
    public function getDossiersRecus($employe_id, $service_id, $role_service)
    {
        $query = "
            SELECT DISTINCT d.*, ed.type_envoi, ed.date_envoi, 
                   e.nom as envoyeur_nom, e.prenom as envoyeur_prenom
            FROM dossiers d
            JOIN envoidossiers ed ON d.id = ed.dossier_id
            JOIN employes e ON ed.envoyeur_id = e.id
            WHERE ed.est_actif = true 
            AND d.est_archive = false
            AND (
                -- Envoi par service
                (ed.type_envoi = 'SERVICE' AND ed.service_id = :service_id)
                OR 
                -- Envoi par rôle
                (ed.type_envoi = 'ROLE_SERVICE' AND ed.role_service = :role_service AND ed.service_id = :service_id2)
            )
            ORDER BY ed.date_envoi DESC
        ";

        return $this->query($query, [
            'service_id' => $service_id,
            'role_service' => $role_service,
            'service_id2' => $service_id
        ]);
    }

    /**
     * Vérifier si un dossier est déjà envoyé à une destination
     */
    public function estDejaEnvoye($dossier_id, $type_envoi, $service_id = null, $role_service = null)
    {
        $where = [
            'dossier_id' => $dossier_id,
            'type_envoi' => $type_envoi,
            'est_actif' => true
        ];

        if ($service_id) {
            $where['service_id'] = $service_id;
        }

        if ($role_service) {
            $where['role_service'] = $role_service;
        }

        return $this->first($where) !== false;
    }

    /**
     * Récupérer les envois actifs d'un dossier
     */
    public function getEnvoisActifs($dossier_id)
    {
        return $this->where([
            'dossier_id' => $dossier_id,
            'est_actif' => true
        ]);
    }
}