<?php
namespace Model;

defined('ROOTPATH') OR exit('Access Denied!');

class Documents
{
    use Model;

    protected $table = 'documents';
    protected $allowedColumns = [
        'id',
        'nom',
        'nom_stockage',
        'dossier_id',
        'uploader_id',
        'date_upload',
        'date_modification',
        
    ];

    public function validate($files_data, $data, $id = null)
	{
		$this->errors = [];

		// if(empty($data['nom']))
		// {
		// 	$this->errors['nom'] = "Le nom est obligatoire";
		// }

		// if(empty($data['prenom']))
		// {
		// 	$this->errors['prenom'] = "Le prenom est obligatoire";
		// }

		// if(empty($data['username']))
		// {
		// 	$this->errors['username'] = "username is required";
		// }

		// if(empty($data['email']))
		// {
		// 	$this->errors['email'] = "L'adresse mail est obligatoire";
		// }else
		// if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL))
		// {
		// 	$this->errors['email'] = "L'adresse mail invalide";
		// } else
		// if ($this->first(['email'=>$data['email']],['id'=>$id])) {
		// 	$this->errors['email'] = "L'adresse mail existe déja";
		// }
		
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

    public function documents($data)
    {
        $data['statut'] = 'en_attente';
        return $this->insert($data); // Doit retourner l'ID inséré
    }

}