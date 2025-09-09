<?php

namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');

use \Core\Session; //Importing namespace

/**
 * Logout class
 */
class Logout
{
    use MainController; // this importing another class

    public function index()
    {

        $ses = new Session();
        $ses->logout();

        redirect('login');
    }
}
