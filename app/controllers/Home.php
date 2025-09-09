<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

use \Model\User; //Importing namespace
use \Core\Session;
use \Core\Model;
use \Model\Uploads; // Importing the Upload model with an alias
use \Model\Folders;

/**
 * home class
 */
class Home
{
	use MainController; // this importing another class
	

	public function index()
	{
		$ses = new Session();
		$uploads = new Uploads(); // Importing the Upload model with an alias
		$folders = new Folders();
		$assignmentsModel = new \Model\DocumentAssignments();
		$userss = new User();

		// Grab all users
		$users = (new User())->findAll();
		$data['users'] = $users;

		// if (!$ses->is_logged_in()) {
        //     message('Please login');
        //     redirect('login');
        // }

		// if ($ses->user('role') == 'admin') {
        // 	$data['rows'] = $uploads->findAll();
		// } else {
		// 	// Afficher les fichiers creer par l'utilisateur lui meme
		// 	$data['rows'] = $uploads->where(['uploaded_by' => $ses->user('id')]);
		// }



		$data['uploads'] = $uploads;

		$this->view('home', $data);
	}

}
