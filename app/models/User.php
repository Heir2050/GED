<?php

namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * User class
 */
class User
{
	
	use Model;

	protected $table = 'employes';

	protected $allowedColumns = [

		'photo',
		'nom',
		'prenom',
		'email',
		'uploader_id',
		'password',
		'role',
		'role_service',
		'est_actif',
		'service_id',
		'date_creation',
	];

	public function validate($files_data, $data, $id = null)
	{
		$this->errors = [];

		if(empty($data['nom']))
		{
			$this->errors['nom'] = "Le nom est obligatoire";
		}

		if(empty($data['prenom']))
		{
			$this->errors['prenom'] = "Le prenom est obligatoire";
		}

		// if(empty($data['username']))
		// {
		// 	$this->errors['username'] = "username is required";
		// }

		if(empty($data['email']))
		{
			$this->errors['email'] = "L'adresse mail est obligatoire";
		}else
		if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL))
		{
			$this->errors['email'] = "L'adresse mail invalide";
		} else
		if ($this->first(['email'=>$data['email']],['id'=>$id])) {
			$this->errors['email'] = "L'adresse mail existe déja";
		}
		
		if(empty($data['service_id']))
		{
			$this->errors['service_id'] = "Le service est obligatoire";
		}

		if(empty($this->errors))
		{
			return true;
		}

		return false;
	}

	public function create_table() {
		$query = "CREATE TABLE IF NOT EXISTS users(
			id int unsigned PRIMARY KEY AUTO_INCREMENT,
			username VARCHAR(30) NOT NULL,
			email VARCHAR(100) NOT NULL,
			password VARCHAR(255) NOT NULL,
			image VARCHAR(1024) NULL,
			role VARCHAR(10) NOT NULL,
			date datetime NOT NULL,

			KEY username(username),
			KEY email(email)
		)";

		$this->query($query);
	}



	
	/**
	 * Récupérer les valeurs disponibles de l'ENUM role_service
	 */
	public function getRoleServiceValues()
	{
		try {
			// Méthode 1: Requête SQL directe pour récupérer les valeurs ENUM
			$query = "SHOW COLUMNS FROM employes WHERE Field = 'role_service'";
			$result = $this->query($query);
			
			if ($result && !empty($result[0])) {
				$type = $result[0]->Type;
				// Extraire les valeurs de l'ENUM
				preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
				if (!empty($matches[1])) {
					$enum_values = explode("','", $matches[1]);
					return $enum_values;
				}
			}
			
			// Méthode 2: Valeurs par défaut si la requête échoue
			// return ['EMPLOYE', 'CHEF_SERVICE', 'ADMIN_SERVICE'];
			
		} catch (\Exception $e) {
			return("Erreur récupération ENUM: " . $e->getMessage());
			// Valeurs par défaut en cas d'erreur
			// return ['EMPLOYE', 'CHEF_SERVICE', 'ADMIN_SERVICE'];
		}
	}
}



// ALTER TABLE Notifications
// DROP COLUMN recipient_user_id,
// DROP COLUMN actor_user_id,
// DROP COLUMN action,
// DROP COLUMN is_read,
// DROP COLUMN created_at;
