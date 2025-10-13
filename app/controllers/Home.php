<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

use \Model\User;
use \Core\Session;
use \Core\Request;
use \Model\Employes;
use \Model\Dossiers;
use \Model\Services;

/**
 * home class
 */
class Home
{
	use MainController;
	

	public function index()
	{
		$ses = new Session();
		$req = new Request();
		
		if (!$ses->is_logged_in()) {
			redirect('login');
		}

		// Récupérer les informations de l'employé connecté
		$employeModel = new Employes();
		$userModel = new User();
		$dossierModel = new Dossiers();
		$serviceModel = new Services();
		
		$employe = $employeModel->first(['id' => $ses->user('id')]);
		
		// if (!$employe) {
		// 	// Si pas de lien direct, chercher par email
		// 	$user = $userModel->first(['id' => $ses->user('id')]);
		// 	$employe = $employeModel->first(['email' => $user->email]);
		// }

		$data = [];

		if ($employe) {
			$data['employe'] = $employe;
			$service_id = $employe->service_id;
			
			// Déterminer si c'est un administrateur
			$data['if_is_admin'] = ($employe->role == 'ADMIN');
			
			// Récupérer les statistiques selon le service de l'utilisateur
			if ($data['if_is_admin']) {
				// Pour l'admin: statistiques globales
				$data['folders_count'] = $dossierModel->query("
					SELECT COUNT(*) as count FROM dossiers WHERE est_archive = false
				")[0]->count ?? 0;

				$data['archives_count'] = $dossierModel->query("
					SELECT COUNT(*) as count FROM dossiers WHERE est_archive = true
				")[0]->count ?? 0;

				$data['received_count'] = $dossierModel->query("
					SELECT COUNT(DISTINCT ed.dossier_id) as count 
					FROM envoidossiers ed 
					WHERE ed.est_actif = true
				")[0]->count ?? 0;

				$data['users_count'] = $userModel->query("
					SELECT COUNT(*) as count FROM employes
				")[0]->count ?? 0;

				// $data['users_count'] = $userModel->query("
				// 	SELECT COUNT(*) as count FROM employes WHERE role != 'ADMIN'
				// ")[0]->count ?? 0;

				// Statistiques par service pour l'admin
				$data['stats_by_service'] = $dossierModel->query("
					SELECT 
						s.id as service_id,
						s.nom as service_nom,
						COUNT(DISTINCT CASE WHEN d.est_archive = false THEN d.id END) as dossiers_actifs,
						COUNT(DISTINCT CASE WHEN d.est_archive = true THEN d.id END) as dossiers_archives,
						COUNT(DISTINCT CASE WHEN ed.est_actif = true THEN ed.dossier_id END) as dossiers_recus
					FROM services s
					LEFT JOIN dossiers d ON s.id = d.service_id
					LEFT JOIN envoidossiers ed ON s.id = ed.service_id AND ed.est_actif = true
					GROUP BY s.id, s.nom
					ORDER BY s.nom
				");

			} else {
				// Pour les utilisateurs normaux: statistiques de leur service seulement
				$data['folders_count'] = $dossierModel->query("
					SELECT COUNT(*) as count FROM dossiers 
					WHERE service_id = :service_id AND est_archive = false
				", ['service_id' => $service_id])[0]->count ?? 0;
			
				$data['archives_count'] = $dossierModel->query("
					SELECT COUNT(*) as count FROM dossiers 
					WHERE service_id = :service_id AND est_archive = true
				", ['service_id' => $service_id])[0]->count ?? 0;
			
				$data['received_count'] = $dossierModel->query("
					SELECT COUNT(DISTINCT ed.dossier_id) as count 
					FROM envoidossiers ed 
					WHERE ed.service_id = :service_id AND ed.est_actif = true
				", ['service_id' => $service_id])[0]->count ?? 0;
				
				// AJOUTEZ CETTE LIGNE :
				$data['stats_by_service'] = []; // Tableau vide pour les non-admin
			}

			// Récupérer le nom du service pour l'affichage
			$service_info = $serviceModel->first(['id' => $service_id]);
			$data['service_nom'] = $service_info ? $service_info->nom : null;

		} else {
			// Utilisateur sans profil employé
			$data['folders_count'] = 0;
			$data['archives_count'] = 0;
			$data['received_count'] = 0;
			$data['service_nom'] = null;
		}

		$this->view('home', $data);
	}
}