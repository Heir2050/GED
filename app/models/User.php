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
		
		// if(!$id && empty($data['password']))
		// {
		// 	$this->errors['password'] = "Le mot de passe est obligatoire";
		// }

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
}



// ALTER TABLE Notifications
// DROP COLUMN recipient_user_id,
// DROP COLUMN actor_user_id,
// DROP COLUMN action,
// DROP COLUMN is_read,
// DROP COLUMN created_at;
