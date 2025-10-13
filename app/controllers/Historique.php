<?php
namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

use Core\Session;
use Core\Request;
use Model\HistoriqueActions;
use Model\Employes;
use Model\User;

class Historique
{
    use MainController;

    public function index()
    {
        $ses = new Session();
        $req = new Request();
        $historiqueModel = new HistoriqueActions();
        $employeModel = new Employes();

        if (!$ses->is_logged_in()) {
            redirect('login');
        }

        $data = [];

        // Récupérer l'employé connecté
        $employe = $employeModel->first(['id' => $ses->user('id')]);
        
        if (!$employe) {
            $userModel = new User();
            $user = $userModel->first(['id' => $ses->user('id')]);
            $employe = $employeModel->first(['email' => $user->email]);
        }

        if ($employe) {
            $data['employe'] = $employe;
            
            // Déterminer si c'est un administrateur
            $is_admin = ($employe->role == 'ADMIN');
            $data['is_admin'] = $is_admin;

            if ($is_admin) {
                // Admin voit tout l'historique
                $query = "
                    SELECT ha.*, 
                           ta.code as type_action_code,
                           e.nom as employe_nom, 
                           e.prenom as employe_prenom,
                           e_cible.nom as employe_cible_nom,
                           e_cible.prenom as employe_cible_prenom,
                           d.nom as dossier_nom,
                           doc.nom as document_nom
                    FROM historiqueactions ha
                    LEFT JOIN typesaction ta ON ha.type_action_id = ta.id
                    LEFT JOIN employes e ON ha.employe_id = e.id
                    LEFT JOIN employes e_cible ON ha.employe_cible_id = e_cible.id
                    LEFT JOIN dossiers d ON ha.dossier_id = d.id
                    LEFT JOIN documents doc ON ha.document_id = doc.id
                    ORDER BY ha.date_action DESC
                    LIMIT 100
                ";
                $data['historique'] = $historiqueModel->query($query);
            } else {
                // Utilisateur normal voit seulement son historique
                $query = "
                    SELECT ha.*, 
                           ta.code as type_action_code,
                           e.nom as employe_nom, 
                           e.prenom as employe_prenom,
                           e_cible.nom as employe_cible_nom,
                           e_cible.prenom as employe_cible_prenom,
                           d.nom as dossier_nom,
                           doc.nom as document_nom
                    FROM historiqueactions ha
                    LEFT JOIN typesaction ta ON ha.type_action_id = ta.id
                    LEFT JOIN employes e ON ha.employe_id = e.id
                    LEFT JOIN employes e_cible ON ha.employe_cible_id = e_cible.id
                    LEFT JOIN dossiers d ON ha.dossier_id = d.id
                    LEFT JOIN documents doc ON ha.document_id = doc.id
                    WHERE ha.employe_id = :employe_id
                    ORDER BY ha.date_action DESC
                    LIMIT 100
                ";
                $data['historique'] = $historiqueModel->query($query, ['employe_id' => $employe->id]);
            }

            // Préparer les données pour la vue
            $data['action_labels'] = $this->getActionLabels();
            $data['action_classes'] = $this->getActionClasses();
        }

        $this->view('historique', $data);
    }

    /**
     * Helper pour les labels d'actions
     */
    private function getActionLabels()
    {
        return [
            'ENVOI_DOSSIER' => 'Envoi Dossier',
            'RETRAIT_ENVOI_DOSSIER' => 'Retrait Envoi',
            'CLOTURE_DOSSIER' => 'Clôture Dossier',
            'ARCHIVAGE_DOSSIER' => 'Archivage Dossier',
            'CREATION_DOSSIER' => 'Création Dossier',
            'CREATION_UTILISATEUR' => 'Création Utilisateur',
            'MODIFICATION_UTILISATEUR' => 'Modification Utilisateur',
            'MODIFICATION_PROFIL' => 'Modification Profil',
            'LOGIN' => 'Connexion',
            'LOGOUT' => 'Déconnexion'
        ];
    }

    /**
     * Helper pour les classes CSS des badges
     */
    private function getActionClasses()
    {
        return [
            'ENVOI_DOSSIER' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'RETRAIT_ENVOI_DOSSIER' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
            'CLOTURE_DOSSIER' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'ARCHIVAGE_DOSSIER' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
            'CREATION_DOSSIER' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
            'CREATION_UTILISATEUR' => 'bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-200',
            'MODIFICATION_UTILISATEUR' => 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200',
            'MODIFICATION_PROFIL' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200',
            'LOGIN' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200',
            'LOGOUT' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
        ];
    }
}