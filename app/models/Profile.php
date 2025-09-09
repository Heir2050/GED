<?php

namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * User class
 */
class Profile
{
	
	use Model;

	protected $table = 'users';

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

		if(empty($this->errors))
		{
			return true;
		}

		return false;
	}

}