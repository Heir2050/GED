<?php

namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');

use \Core\Session; //Importing namespace
use \Core\Request;
use Model\User;
use \Model\Image;

class Users 
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
        // if (!$ses->is_logged_in() && $ses->user('role') == 'admin') {
        //     message('Accés non autorisé');
        //     redirect('home');
        // }

        $data['rows'] = $users->findAll();
        $data['action'] = "";

        $this->view('users', $data);
    }


    public function user($action = null, $id = null)
    {
        $users = new User();
        $req = new Request();
        $ses = new Session();
        $data = [];

        $action = $data['action'] = URL(2) ?? 'View';

        # After doing anything, we unsure that the user is loged in.
        // if (!$ses->is_logged_in() || $ses->user('role') != 'admin') {
        //     message('Accés non autorisé');
        //     redirect('home');
        // }

        // $users->setOrder_column('user_id');

        if ($action == 'add') {
            if ($req->posted()) { # if ($_SERVER['REQUEST_METHOD'] = 'POST') the same
                if ($users->validate($_FILES, $_POST)) {  #$users->validate($_POST) Same code

                    $file = $req->files();

                    $arr = $req->post();

                    // Definir le mot de passe par défaut
                    if (empty($arr['password'])) {
                        $arr['password'] = password_hash('1234', PASSWORD_DEFAULT);
                    } else {
                        $arr['password'] = password_hash($arr['password'], PASSWORD_DEFAULT);
                    }

                    if (!empty($file['image']['name'])) {
                        $folder = "uploads/users/";
                        if (!file_exists($folder)) {
                            mkdir($folder, 0777, true);
                        }

                        $arr['image'] = $folder . $file['image']['name'];
                        move_uploaded_file($file['image']['tmp_name'], $arr['image']);

                        # For resizing image 
                        $image_class = new \Model\Image();
                        $image_class->resize($arr['image'], 1000); # 1000 is maximum of pixel
                    }

                    $users->insert($arr);

                    message("User added successfully");

                    redirect('users');
                }

                $data['errors'] = $users->errors;
            }

        } elseif ($action == 'edit') {
            $data['row'] = $users->first(['id' => $id]);

            $action = 'edit';

            if (!$data['row']) {
                message("User not found!");
                redirect('users');
            }

            if ($_SERVER['REQUEST_METHOD'] == "POST") {

                $folder = "uploads/";
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }

                if ($users->validate($_FILES, $_POST, $id)) {

                    if (empty($_POST['password'])) {
                        unset($_POST['password']);
                    } else {
                        $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    }

                    if (!empty($_FILES['image']['name'])) {
                        $destination = $folder . time() . $_FILES['image']['name'];
                        move_uploaded_file($_FILES['image']['tmp_name'], $destination);

                        $image_class = new Image;
                        $image_class->resize($destination);

                        $_POST['image'] = $destination;

                        if (file_exists($data['row']->image)) {
                            unlink($data['row']->image);
                        }
                    }

                    $users->update($id, $_POST, 'id');

                    message("User edited successfully");

                    redirect('users');
                } else {
                    $data['errors'] = $users->errors;
                }
            }
        } elseif ($action == 'delete') {

            $data['row'] = $users->first(['id' => $id]);

            if ($_SERVER['REQUEST_METHOD'] == "POST") {

                $users->delete($id, 'id');

                if (file_exists($data['row']->image))
                    unlink($data['row']->image);

                message("A User deleted successfully");

                redirect('users');
            }
        }


        $data['total_users'] = $users->get_row("select count(*) as total from employes");

        $data['rows'] = $users->findAll();

        // $data['services'] = $users->findAll();

        $this->view('users', $data);
    }
}