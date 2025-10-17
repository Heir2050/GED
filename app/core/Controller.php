<?php 

namespace Controller;

use Core\FileHelper;
use Core\Session;

defined('ROOTPATH') OR exit('Access Denied!');

Trait MainController
{

	public function view($name, $data = [])
	{
		if (!empty($data))
			$data['fileHelper'] = new FileHelper(); // Ensure FileHelper is available in the view
			extract($data);

		$filename = "../app/views/".$name.".view.php";
		if(file_exists($filename))
		{
			require $filename;
		}else{

			$filename = "../app/views/404.view.php";
			require $filename;
		}
	}


	/**
	 * Helper pour enregistrer les actions
	 */
	protected function enregistrerAction($type_action, $details = '', $document_id = null, $dossier_id = null, $employe_cible_id = null)
	{
		$ses = new Session();
		if (!$ses->is_logged_in()) return false;

		$historiqueModel = new \Model\HistoriqueActions();
		return $historiqueModel->enregistrerAction(
			$ses->user('id'),
			$type_action,
			$details,
			$document_id,
			$dossier_id,
			$employe_cible_id
		);
	}

	/**
	 * Mettre à jour la dernière connexion - Version avec query()
	 */
	protected function updateLastLogin($employe_id)
	{
		$employeModel = new \Model\Employes();
		
		// Utiliser query() au lieu de update()
		$result = $employeModel->query(
			"UPDATE employes SET derniere_connexion = :derniere_connexion WHERE id = :id",
			[
				'derniere_connexion' => date('Y-m-d H:i:s'),
				'id' => $employe_id
			]
		);
		
		error_log("Dernière connexion mise à jour - Employé: $employe_id, Résultat: " . ($result ? 'SUCCÈS' : 'ÉCHEC'));
		
		// Enregistrer dans l'historique (désactivé temporairement)
		$this->enregistrerAction('LOGIN', 'Connexion au système');
		
		return $result;
	}

	public function getEnumValues($columnName)
	{
		$query = "SHOW COLUMNS FROM {$this->table} WHERE Field = ?";
		$result = $this->query($query, [$columnName]);
		
		if ($result && !empty($result[0]->Type)) {
			$type = $result[0]->Type;
			// Extraction des valeurs ENUM
			preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
			if (!empty($matches[1])) {
				return explode("','", $matches[1]);
			}
		}
		
		return [];
	}


	
}