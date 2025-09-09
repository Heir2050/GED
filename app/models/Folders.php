<?php

namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

class Folders
{

    use Model;

    protected $table = 'dossiers';
    protected $allowedColumns = [
        'name',
        'parent_id',
        'created_by',
        'created_at'
    ];

    public function validate($data, $id = null)
	{
		$this->errors = [];

		if(empty($data['name']))
		{
			$this->errors['name'] = "Le nom est obligatoire";
		}

		if(empty($this->errors))
		{
			return true;
		}

		return false;
	}

    // Récupérer tous les sous-dossiers d'un dossier
    public function getSubfolders($parent_id = null) {
        return $this->where(['parent_id' => $parent_id]);
    }
}