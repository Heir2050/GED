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
        // S'assurer que l'état est en majuscules pour correspondre à l'ENUM
        $etat = strtoupper($etat);
        
        error_log("=== DEBUT mettreAJourEtat ===");
        error_log("Params - Dossier: $dossier_id, Employé: $employe_id, État: $etat");
        
        // Vérifier d'abord si l'entrée existe
        $existing = $this->query("
            SELECT id, etat, date_ouverture FROM etatdossierutilisateur 
            WHERE dossier_id = :dossier_id AND employe_id = :employe_id
        ", ['dossier_id' => $dossier_id, 'employe_id' => $employe_id]);

        error_log("Entrée existante: " . print_r($existing, true));

        $data = [
            'etat' => $etat,
            'date_derniere_modification' => date('Y-m-d H:i:s')
        ];

        if ($etat === 'TRAITEMENT') {
            // Vérifier si c'est la première ouverture
            if (empty($existing) || empty($existing[0]->date_ouverture)) {
                $data['date_ouverture'] = date('Y-m-d H:i:s');
                error_log("Date d'ouverture définie");
            }
        }

        if ($etat === 'CLOTURE') {
            $data['date_cloture'] = date('Y-m-d H:i:s');
            error_log("Date de clôture définie - État à définir: CLOTURE");
        }

        if (empty($existing)) {
            error_log("Création nouvelle entrée");
            // Créer une nouvelle entrée
            $data['dossier_id'] = $dossier_id;
            $data['employe_id'] = $employe_id;
            $result = $this->insertEtatDossier($data);
        } else {
            error_log("Mise à jour entrée existante - ID: " . $existing[0]->id . ", État actuel: " . $existing[0]->etat);
            // Mettre à jour l'entrée existante
            $result = $this->updateEtatDossier($dossier_id, $employe_id, $data);
        }
        
        error_log("Résultat final: " . ($result ? 'SUCCÈS' : 'ÉCHEC'));
        
        // Vérifier l'état après mise à jour
        $verification = $this->query("
            SELECT etat FROM etatdossierutilisateur 
            WHERE dossier_id = :dossier_id AND employe_id = :employe_id
        ", ['dossier_id' => $dossier_id, 'employe_id' => $employe_id]);
        
        error_log("Vérification après mise à jour: " . print_r($verification, true));
        error_log("=== FIN mettreAJourEtat ===");
        
        return $result;
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
        error_log("=== DEBUT updateEtatDossier ===");
        error_log("Data à mettre à jour: " . print_r($data, true));
        
        $setParts = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            $setParts[] = "$key = :$key";
            $params[$key] = $value;
            error_log("Champ à mettre à jour: $key = $value");
        }
        
        $query = "UPDATE etatdossierutilisateur SET " . implode(', ', $setParts) . 
                " WHERE dossier_id = :dossier_id AND employe_id = :employe_id";
        
        $params['dossier_id'] = $dossier_id;
        $params['employe_id'] = $employe_id;
        
        error_log("Requête SQL: " . $query);
        error_log("Paramètres: " . print_r($params, true));
        
        $result = $this->query($query, $params);
        
        error_log("Résultat update: " . ($result ? 'SUCCÈS' : 'ÉCHEC'));
        error_log("=== FIN updateEtatDossier ===");
        
        return $result;
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
    if ($service_id) {
        return $this->query(
            "SELECT * FROM dossiers WHERE est_archive = true AND service_id = :service_id ORDER BY date_archivage DESC",
            ['service_id' => $service_id]
        );
    } else {
        return $this->query(
            "SELECT * FROM dossiers WHERE est_archive = true ORDER BY date_archivage DESC"
        );
    }
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



    

//     public function test_cloture_direct($dossier_id, $employe_id)
// {
//     $dossier = new Dossiers();
    
//     echo "<h1>Test Clôture Direct</h1>";
//     echo "Dossier ID: $dossier_id<br>";
//     echo "Employé ID: $employe_id<br>";
    
//     // Test 1: Vérifier l'état actuel
//     $etat_actuel = $dossier->query("
//         SELECT etat, date_cloture FROM etatdossierutilisateur 
//         WHERE dossier_id = :dossier_id AND employe_id = :employe_id
//     ", ['dossier_id' => $dossier_id, 'employe_id' => $employe_id]);
    
//     echo "État actuel: <pre>" . print_r($etat_actuel, true) . "</pre>";
    
//     // Test 2: Mise à jour directe avec la bonne casse
//     $query = "UPDATE etatdossierutilisateur SET etat = 'CLOTURE', date_cloture = NOW() WHERE dossier_id = :dossier_id AND employe_id = :employe_id";
//     $result = $dossier->query($query, ['dossier_id' => $dossier_id, 'employe_id' => $employe_id]);
    
//     echo "Résultat mise à jour directe: " . ($result ? 'SUCCÈS' : 'ÉCHEC') . "<br>";
    
//     // Test 3: Vérifier après mise à jour
//     $etat_apres = $dossier->query("
//         SELECT etat, date_cloture FROM etatdossierutilisateur 
//         WHERE dossier_id = :dossier_id AND employe_id = :employe_id
//     ", ['dossier_id' => $dossier_id, 'employe_id' => $employe_id]);
    
//     echo "État après mise à jour directe: <pre>" . print_r($etat_apres, true) . "</pre>";
    
//     // Test 4: Utiliser la méthode mettreAJourEtat
//     echo "<h2>Test avec mettreAJourEtat</h2>";
//     $result2 = $dossier->mettreAJourEtat($dossier_id, $employe_id, 'CLOTURE');
//     echo "Résultat mettreAJourEtat: " . ($result2 ? 'SUCCÈS' : 'ÉCHEC') . "<br>";
    
//     // Vérifier final
//     $etat_final = $dossier->query("
//         SELECT etat, date_cloture FROM etatdossierutilisateur 
//         WHERE dossier_id = :dossier_id AND employe_id = :employe_id
//     ", ['dossier_id' => $dossier_id, 'employe_id' => $employe_id]);
    
//     echo "État final: <pre>" . print_r($etat_final, true) . "</pre>";
    
//     die();
// }


}