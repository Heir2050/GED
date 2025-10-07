<?php
namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

class Dossiers
{
    use Model;

    protected $table = 'dossiers';
    protected $allowedColumns = [
        'id',
        'nom',
        'chemin',
        'service_id',
        'date_creation',
        'createur_id',
        'est_archive',
        'date_archivage'
    ];

    public function getEtatsUtilisateurs($dossier_id)
    {
        $query = "
            SELECT edu.*, e.nom, e.prenom, e.email 
            FROM etatdossierutilisateur edu
            JOIN employes e ON edu.employe_id = e.id
            WHERE edu.dossier_id = :dossier_id
            ORDER BY e.nom, e.prenom
        ";
        
        return $this->query($query, ['dossier_id' => $dossier_id]);
    }

    public function mettreAJourEtat($dossier_id, $employe_id, $etat)
    {
        // Vérifier d'abord si l'entrée existe
        $existing = $this->query("
            SELECT id, date_ouverture FROM etatdossierutilisateur 
            WHERE dossier_id = :dossier_id AND employe_id = :employe_id
        ", ['dossier_id' => $dossier_id, 'employe_id' => $employe_id]);

        $data = [
            'etat' => $etat,
            'date_derniere_modification' => date('Y-m-d H:i:s')
        ];

        if ($etat === 'TRAITEMENT') {
            // Vérifier si c'est la première ouverture
            if (empty($existing) || empty($existing[0]->date_ouverture)) {
                $data['date_ouverture'] = date('Y-m-d H:i:s');
            }
        }

        if ($etat === 'CLOTURE') {
            $data['date_cloture'] = date('Y-m-d H:i:s');
        }

        if (empty($existing)) {
            // Créer une nouvelle entrée
            $data['dossier_id'] = $dossier_id;
            $data['employe_id'] = $employe_id;
            return $this->insertEtatDossier($data);
        } else {
            // Mettre à jour l'entrée existante
            return $this->updateEtatDossier($dossier_id, $employe_id, $data);
        }
    }

    private function insertEtatDossier($data)
    {
        // Ne pas inclure l'ID pour permettre l'auto-incrémentation
        unset($data['id']);
        
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $query = "INSERT INTO etatdossierutilisateur ($columns) VALUES ($placeholders)";
        
        return $this->query($query, $data);
    }

    private function updateEtatDossier($dossier_id, $employe_id, $data)
    {
        $setParts = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            $setParts[] = "$key = :$key";
            $params[$key] = $value;
        }
        
        $query = "UPDATE etatdossierutilisateur SET " . implode(', ', $setParts) . 
                 " WHERE dossier_id = :dossier_id AND employe_id = :employe_id";
        
        $params['dossier_id'] = $dossier_id;
        $params['employe_id'] = $employe_id;
        
        return $this->query($query, $params);
    }

    public function tousUtilisateursOntCloture($dossier_id)
    {
        $result = $this->query("
            SELECT COUNT(*) as total, 
                   SUM(CASE WHEN etat = 'CLOTURE' THEN 1 ELSE 0 END) as clotures
            FROM etatdossierutilisateur 
            WHERE dossier_id = :dossier_id
        ", ['dossier_id' => $dossier_id]);

        if ($result && $result[0]->total > 0) {
            return $result[0]->total == $result[0]->clotures;
        }
        
        return false;
    }

    public function archiverDossier($dossier_id)
    {
        return $this->update($dossier_id, [
            'est_archive' => true,
            'date_archivage' => date('Y-m-d H:i:s')
        ]);
    }

    public function getDossiersArchives($service_id = null)
    {
        $where = "est_archive = true";
        $params = [];
        
        if ($service_id) {
            $where .= " AND service_id = :service_id";
            $params['service_id'] = $service_id;
        }
        
        return $this->where([$where], $params);
    }

    // Méthode pour initialiser les états pour un dossier existant
    public function initialiserEtatsDossier($dossier_id)
    {
        $dossier = $this->first(['id' => $dossier_id]);
        
        if (!$dossier) {
            return false;
        }

        // Vérifier si des états existent déjà
        $etats_existants = $this->query("
            SELECT COUNT(*) as count 
            FROM etatdossierutilisateur 
            WHERE dossier_id = :dossier_id
        ", ['dossier_id' => $dossier_id]);

        if ($etats_existants && $etats_existants[0]->count > 0) {
            return true; // Les états existent déjà
        }

        // Créer les états pour tous les employés du service (sauf le créateur)
        $query = "
            INSERT INTO etatdossierutilisateur (dossier_id, employe_id, etat)
            SELECT :dossier_id, e.id, 'NON_OUVERT'
            FROM employes e
            WHERE e.service_id = :service_id 
            AND e.est_actif = TRUE
            AND e.id != :createur_id
        ";
        
        return $this->query($query, [
            'dossier_id' => $dossier_id,
            'service_id' => $dossier->service_id,
            'createur_id' => $dossier->createur_id
        ]);
    }
}