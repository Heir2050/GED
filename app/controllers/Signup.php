<?php

namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');

use \Model\User; //Importing namespace
use \Core\Request;

/**
 * Signup class
 */
class Signup
{
    use MainController; // this importing another class

    public function index()
    {
        $user = new User();
        // $user->create_table();  // function used to create a table 
        $req = new Request();

        $data = [];

        if ($req->posted()) { # if ($_SERVER['REQUEST_METHOD'] = 'POST') the same
            if ($user->validate($_FILES, $_POST)) {  #$user->validate($_POST) Same code
                
                $arr = $req->post();
                $arr['password'] = password_hash($arr['password'], PASSWORD_DEFAULT);
                $arr['date'] = date("Y-m-d H:i:s");
                $arr['role'] = "user";

                $user->insert($arr);

                message("Account created! Please login to continue");

                redirect('login');
            }

            $data['errors'] = $user->errors;
        }

        $this->view('signup', $data);
    }
}
