<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

use \Model\User; //Importing namespace
use \Core\Request;
use \Core\Session;

/**
 * Login class
 */
class Login
{
	use MainController; // this importing another class

	public function index()
	{

		$user = new User();
		$req = new Request();
		$ses = new Session();

		$data = [];

		if ($req->posted()) { # if ($_SERVER['REQUEST_METHOD'] = 'POST') the same

			# Check for the email
			$post = $req->post();

			$row = $user->first(['email'=>$post['email']]);

			if ($row) {
				
				if (password_verify($post['password'], $row->password)) {
					# Everything is good
					
					$ses->auth($row);
					redirect('home');
				}
			}

			$data['errors']['email'] = "Wrong email or password";
		}


		$this->view('login', $data);
	}

}
