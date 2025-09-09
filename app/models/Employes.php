<?php

namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * User class
 */
class Employes
{
	
	use Model;

	protected $table = 'employes';

	protected $allowedColumns = [

		'image',
		'nom',
		'prenom',
		'username',
		'email',
		'password',
		'role',
		'created_at',
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
		$query = "CREATE TABLE IF NOT EXISTS employes(
			id int unsigned PRIMARY KEY AUTO_INCREMENT,
			nom VARCHAR(100) NOT NULL,
			prenom VARCHAR(100) NOT NULL,
			username VARCHAR(30) NULL,
			email VARCHAR(100) NOT NULL,
			password VARCHAR(255) NOT NULL,
			image VARCHAR(1024) NULL,
			role VARCHAR(20) NOT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,

			KEY username(username),
			KEY email(email)
		)";

		$this->query($query);
	}
}