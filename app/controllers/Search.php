<?php
namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');

use Core\Session;
use Core\Request;
use Model\Dossiers;
use Model\Documents;
use Model\HistoriqueActions;
use Model\Employes;

class Search
{
    use MainController;

    public function index()
    {
        $ses = new Session();
        $req = new Request();
        
        if (!$ses->is_logged_in()) {
            redirect('login');
        }

        $employe = $this->getEmployeInfo($ses);
        if (!$employe) {
            message("Profil employé non trouvé", 'error');
            redirect('logout');
        }

        $query = $req->get('q');
        $type = $req->get('type') ?: 'all';
        
        $data = [
            'query' => $query,
            'type' => $type,
            'employe' => $employe,
            'results' => [],
            'counts' => [
                'all' => 0,
                'documents' => 0,
                'dossiers' => 0,
                'archives' => 0,
                'historique' => 0
            ]
        ];

        if (!empty($query)) {
            // Calculer tous les résultats une seule fois
            $allResults = $this->calculateAllResults($query, $employe);
            $data['counts'] = $allResults['counts'];
            $data['results'] = $allResults['results'][$type] ?? [];
        }

        $this->view('search', $data);
    }

    /**
     * Calcule tous les résultats et leurs comptes
     */
    private function calculateAllResults($query, $employe)
    {
        $results = [];
        $counts = [
            'all' => 0,
            'documents' => 0,
            'dossiers' => 0,
            'archives' => 0,
            'historique' => 0
        ];

        // Recherche dans les documents
        $documents = $this->searchDocuments($query, $employe);
        $results['documents'] = [
            'title' => 'Documents',
            'items' => is_array($documents) ? $documents : [],
            'count' => is_array($documents) ? count($documents) : 0
        ];
        $counts['documents'] = $results['documents']['count'];

        // Recherche dans les dossiers
        $dossiers = $this->searchDossiers($query, $employe);
        $results['dossiers'] = [
            'title' => 'Dossiers',
            'items' => is_array($dossiers) ? $dossiers : [],
            'count' => is_array($dossiers) ? count($dossiers) : 0
        ];
        $counts['dossiers'] = $results['dossiers']['count'];

        // Recherche dans les archives
        $archives = $this->searchArchives($query, $employe);
        $results['archives'] = [
            'title' => 'Archives',
            'items' => is_array($archives) ? $archives : [],
            'count' => is_array($archives) ? count($archives) : 0
        ];
        $counts['archives'] = $results['archives']['count'];

        // Recherche dans l'historique
        $historique = $this->searchHistorique($query, $employe);
        $results['historique'] = [
            'title' => 'Historique',
            'items' => is_array($historique) ? $historique : [],
            'count' => is_array($historique) ? count($historique) : 0
        ];
        $counts['historique'] = $results['historique']['count'];

        // Total général
        $counts['all'] = $counts['documents'] + $counts['dossiers'] + $counts['archives'] + $counts['historique'];

        // Préparer les résultats par type pour l'affichage
        $formattedResults = [
            'all' => $results,
            'documents' => ['documents' => $results['documents']],
            'dossiers' => ['dossiers' => $results['dossiers']],
            'archives' => ['archives' => $results['archives']],
            'historique' => ['historique' => $results['historique']]
        ];

        return [
            'counts' => $counts,
            'results' => $formattedResults
        ];
    }

    /**
     * Recherche globale dans tous les éléments
     */
    /**
     * Recherche globale dans tous les éléments
     */
    private function searchAll($query, $employe)
    {
        $results = [];
        
        // Recherche dans les documents
        $documents = $this->searchDocuments($query, $employe);
        $results['documents'] = [
            'title' => 'Documents',
            'items' => is_array($documents) ? $documents : [],
            'count' => is_array($documents) ? count($documents) : 0
        ];
        
        // Recherche dans les dossiers
        $dossiers = $this->searchDossiers($query, $employe);
        $results['dossiers'] = [
            'title' => 'Dossiers',
            'items' => is_array($dossiers) ? $dossiers : [],
            'count' => is_array($dossiers) ? count($dossiers) : 0
        ];
        
        // Recherche dans les archives
        $archives = $this->searchArchives($query, $employe);
        $results['archives'] = [
            'title' => 'Archives',
            'items' => is_array($archives) ? $archives : [],
            'count' => is_array($archives) ? count($archives) : 0
        ];
        
        // Recherche dans l'historique
        $historique = $this->searchHistorique($query, $employe);
        $results['historique'] = [
            'title' => 'Historique',
            'items' => is_array($historique) ? $historique : [],
            'count' => is_array($historique) ? count($historique) : 0
        ];
        
        return $results;
    }

    /**
     * Recherche dans les documents
     */
    private function searchDocuments($query, $employe)
    {
        $documentModel = new \Model\Documents();
        $dossierModel = new \Model\Dossiers();
        
        try {
            $searchQuery = "
                SELECT d.*, dos.nom as dossier_nom, dos.service_id as dossier_service_id
                FROM documents d
                JOIN dossiers dos ON d.dossier_id = dos.id
                WHERE (d.nom LIKE :query OR d.nom_stockage LIKE :query)
                AND dos.est_archive = false
                AND (
                    -- Dossiers internes du service
                    (dos.service_id = :service_id AND dos.est_envoye = false)
                    OR 
                    -- Dossiers reçus
                    EXISTS (
                        SELECT 1 FROM envoidossiers ed 
                        WHERE ed.dossier_id = dos.id 
                        AND ed.est_actif = true
                        AND ed.service_id = :service_id2
                        AND (
                            ed.type_envoi = 'SERVICE' 
                            OR 
                            (ed.type_envoi = 'ROLE_SERVICE' AND ed.role_service = :role_service)
                        )
                    )
                )
                ORDER BY d.date_upload DESC
            ";
        
        return $documentModel->query($searchQuery, [
            'query' => '%' . $query . '%',
            'service_id' => $employe->service_id,
            'service_id2' => $employe->service_id,
            'role_service' => $employe->role_service
        ]);

        $result = $documentModel->query($searchQuery, $params);
        } catch (\Exception $e) {
            error_log("Erreur recherche documents: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Recherche dans les dossiers
     */
    private function searchDossiers($query, $employe)
    {
        $dossierModel = new \Model\Dossiers();
        
        $searchQuery = "
            SELECT d.*, 
                   s.nom as service_nom,
                   e.nom as createur_nom,
                   e.prenom as createur_prenom
            FROM dossiers d
            LEFT JOIN services s ON d.service_id = s.id
            LEFT JOIN employes e ON d.createur_id = e.id
            WHERE d.nom LIKE :query
            AND d.est_archive = false
            AND (
                -- Dossiers internes du service
                (d.service_id = :service_id AND d.est_envoye = false)
                OR 
                -- Dossiers reçus
                EXISTS (
                    SELECT 1 FROM envoidossiers ed 
                    WHERE ed.dossier_id = d.id 
                    AND ed.est_actif = true
                    AND ed.service_id = :service_id2
                    AND (
                        ed.type_envoi = 'SERVICE' 
                        OR 
                        (ed.type_envoi = 'ROLE_SERVICE' AND ed.role_service = :role_service)
                    )
                )
            )
            ORDER BY d.date_creation DESC
        ";
        
        return $dossierModel->query($searchQuery, [
            'query' => '%' . $query . '%',
            'service_id' => $employe->service_id,
            'service_id2' => $employe->service_id,
            'role_service' => $employe->role_service
        ]);
    }

    /**
     * Recherche dans les archives
     */
    private function searchArchives($query, $employe)
    {
        $dossierModel = new \Model\Dossiers();
        
        $searchQuery = "
            SELECT d.*, 
                   s.nom as service_nom,
                   e.nom as createur_nom,
                   e.prenom as createur_prenom
            FROM dossiers d
            LEFT JOIN services s ON d.service_id = s.id
            LEFT JOIN employes e ON d.createur_id = e.id
            WHERE d.nom LIKE :query
            AND d.est_archive = true
            AND d.service_id = :service_id
            ORDER BY d.date_archivage DESC
        ";
        
        return $dossierModel->query($searchQuery, [
            'query' => '%' . $query . '%',
            'service_id' => $employe->service_id
        ]);
    }

    /**
     * Recherche dans l'historique
     */
    private function searchHistorique($query, $employe)
    {
        $historiqueModel = new \Model\HistoriqueActions();
        
        $searchQuery = "
            SELECT h.*, 
                   e.nom as employe_nom,
                   e.prenom as employe_prenom,
                   d.nom as document_nom,
                   dos.nom as dossier_nom
            FROM historiqueactions h
            LEFT JOIN employes e ON h.employe_id = e.id
            LEFT JOIN documents d ON h.document_id = d.id
            LEFT JOIN dossiers dos ON h.dossier_id = dos.id
            WHERE (h.type_action_id LIKE :query 
                   OR h.details LIKE :query2
                   OR d.nom LIKE :query3
                   OR dos.nom LIKE :query4)
            AND h.employe_id = :employe_id
            ORDER BY h.date_action DESC
            LIMIT 50
        ";
        
        return $historiqueModel->query($searchQuery, [
            'query' => '%' . $query . '%',
            'query2' => '%' . $query . '%',
            'query3' => '%' . $query . '%',
            'query4' => '%' . $query . '%',
            'employe_id' => $employe->id
        ]);
    }

    /**
     * Récupérer les informations de l'employé
     */
    private function getEmployeInfo($ses)
    {
        $employeModel = new \Model\Employes();
        $employe = $employeModel->first(['id' => $ses->user('id')]);
        
        if (!$employe) {
            $userModel = new \Model\User();
            $user = $userModel->first(['id' => $ses->user('id')]);
            if ($user) {
                $employe = $employeModel->first(['email' => $user->email]);
            }
        }
        
        return $employe;
    }

    /**
     * Méthode pour afficher un résultat de recherche (accessible depuis la vue)
     */
    public function displaySearchResultItem($item, $type, $query)
    {
        ob_start();
        ?>
        <div class="result-item" onclick="openResult('<?= $type ?>', <?= $item->id ?>)">
            <div class="result-title">
                <span class="result-type type-<?= $type ?>"><?= strtoupper(substr($type, 0, 1)) ?></span>
                <?= $this->highlightSearchText($item->nom ?? $item->details ?? 'Sans titre', $query) ?>
            </div>
            <div class="result-meta">
                <?php if ($type === 'documents'): ?>
                    <span>Dossier: <?= $item->dossier_nom ?? 'Inconnu' ?></span>
                    <span>Ajouté: <?= date('d/m/Y H:i', strtotime($item->date_upload)) ?></span>
                <?php elseif ($type === 'dossiers'): ?>
                    <span>Service: <?= $item->service_nom ?? 'Inconnu' ?></span>
                    <span>Créé: <?= date('d/m/Y H:i', strtotime($item->date_creation)) ?></span>
                    <span>Par: <?= $item->createur_prenom ?? '' ?> <?= $item->createur_nom ?? '' ?></span>
                <?php elseif ($type === 'archives'): ?>
                    <span>Archivé: <?= date('d/m/Y H:i', strtotime($item->date_archivage)) ?></span>
                    <span>Service: <?= $item->service_nom ?? 'Inconnu' ?></span>
                <?php elseif ($type === 'historique'): ?>
                    <span>Action: <?= $item->type_action_id ?></span>
                    <span>Date: <?= date('d/m/Y H:i', strtotime($item->date_action)) ?></span>
                    <?php if ($item->document_nom): ?>
                        <span>Document: <?= $item->document_nom ?></span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Fonction pour surligner le texte (accessible depuis la vue)
     */
    public function highlightSearchText($text, $query)
    {
        if (empty($query) || empty($text)) return htmlspecialchars($text ?? '');
        $pattern = '/(' . preg_quote($query, '/') . ')/i';
        return preg_replace($pattern, '<span class="highlight">$1</span>', htmlspecialchars($text));
    }

    
}