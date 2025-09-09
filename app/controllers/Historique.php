<?php

namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');

use \Core\Session; //Importing namespace
use \Core\Request;
use Model\User;
use \Model\Image;

class Historique 
{
    use MainController; // this importing another class
    /**
     * This function is used to manage users in the admin panel.
     * It allows adding, editing, and viewing users.
     *
     * @param string|null $action The action to perform (add, edit, view).
     * @param int|null $id The ID of the user to edit.
     */

    public function index()
    {
        $users = new User();
        $req = new Request();
        $ses = new Session();

        # After doing anything, we unsure that the user is loged in.
        if (!$ses->is_logged_in()) {
            message('Please login as admin');
            redirect('login');
        }

        $this->view('historique');
    }
}