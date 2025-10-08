<?php

namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * User class
 */
class Services
{
	
	use Model;

	protected $table = 'services';

	protected $allowedColumns = [

		'id',
		'nom',
		'description',
		'date_creation',
	];

	public function validate($files_data, $data, $id = null)
	{
		$this->errors = [];

		if(empty($data['id']))
		{
			$this->errors['id'] = "L'id est obligatoire";
		}

		if(empty($data['description']))
		{
			$this->errors['description'] = "La description est obligatoire";
		}

		if(empty($this->errors))
		{
			return true;
		}

		return false;
	}


}



// ALTER TABLE Notifications
// DROP COLUMN recipient_user_id,
// DROP COLUMN actor_user_id,
// DROP COLUMN action,
// DROP COLUMN is_read,
// DROP COLUMN created_at;
