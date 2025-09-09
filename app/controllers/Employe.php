<?php

namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');

use \Core\Session; //Importing namespace
use \Core\Request;
use Model\Employes;
use \Model\Image;

class Employe
{
    use MainController; // this importing another class
    /**
     * This function is used to manage employes in the admin panel.
     * It allows adding, editing, and viewing employes.
     *
     * @param string|null $action The action to perform (add, edit, view).
     * @param int|null $id The ID of the employe to edit.
     */

    public function index()
    {
        $employes = new Employes();
        $req = new Request();
        $ses = new Session();

        # After doing anything, we unsure that the user is loged in.
        // if (!$ses->is_logged_in() && $ses->user('role') == 'admin') {
        //     message('Accés non autorisé');
        //     redirect('home');
        // }

        $data['rows'] = $employes->findAll();
        $data['action'] = "";

        $this->view('employe', $data);
    }


    public function user($action = null, $id = null)
    {
        $employes = new Employes();
        $req = new Request();
        $ses = new Session();
        $data = [];

        $action = $data['action'] = URL(2) ?? 'View';

        # After doing anything, we unsure that the user is loged in.
        // if (!$ses->is_logged_in() || $ses->user('role') != 'admin') {
        //     message('Accés non autorisé');
        //     redirect('home');
        // }

        // $employes->setOrder_column('user_id');

        if ($action == 'add') {
            if ($req->posted()) { # if ($_SERVER['REQUEST_METHOD'] = 'POST') the same
                if ($employes->validate($_FILES, $_POST)) {  #$employes->validate($_POST) Same code

                    $file = $req->files();

                    $arr = $req->post();

                    // Definir le mot de passe par défaut
                    if (empty($arr['password'])) {
                        $arr['password'] = password_hash('1234', PASSWORD_DEFAULT);
                    } else {
                        $arr['password'] = password_hash($arr['password'], PASSWORD_DEFAULT);
                    }

                    if (!empty($file['image']['name'])) {
                        $folder = "uploads/employes/";
                        if (!file_exists($folder)) {
                            mkdir($folder, 0777, true);
                        }

                        $arr['image'] = $folder . $file['image']['name'];
                        move_uploaded_file($file['image']['tmp_name'], $arr['image']);

                        # For resizing image 
                        $image_class = new \Model\Image();
                        $image_class->resize($arr['image'], 1000); # 1000 is maximum of pixel
                    }

                    $employes->insert($arr);

                    message("Employé ajouté avec succès");

                    redirect('employe');
                }

                $data['errors'] = $employes->errors;
            }

        } elseif ($action == 'edit') {
            $data['row'] = $employes->first(['id' => $id]);

            $action = 'edit';

            if (!$data['row']) {
                message("Employé non trouvé!");
                redirect('employe');
            }

            if ($_SERVER['REQUEST_METHOD'] == "POST") {

                $folder = "uploads/";
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }

                if ($employes->validate($_FILES, $_POST, $id)) {

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

                    $employes->update($id, $_POST, 'id');

                    message("Employé modifié avec succès");

                    redirect('employe');
                } else {
                    $data['errors'] = $employes->errors;
                }
            }
        } elseif ($action == 'delete') {

            $data['row'] = $employes->first(['id' => $id]);

            if ($_SERVER['REQUEST_METHOD'] == "POST") {

                $employes->delete($id, 'id');

                if (file_exists($data['row']->image))
                    unlink($data['row']->image);

                message("Un employé a été supprimé avec succès");

                redirect('employe');
            }
        }


        $data['total_employes'] = $employes->get_row("select count(*) as total from employes");

        $data['rows'] = $employes->findAll();

        $this->view('employe', $data);
    }
}