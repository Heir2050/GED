<?php

namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');

use \Core\Session;
use \Core\Request;
use Model\User;
use \Model\Image;

class Profile 
{
    use MainController;

    public function index()
    {
        $users = new User();
        $req = new Request();
        $ses = new Session();
        $data = [];

        # Vérifier que l'utilisateur est connecté
        if (!$ses->is_logged_in()) {
            message('Please login');
            redirect('login');
        }

        # Récupérer l'utilisateur connecté
        $user_id = $ses->user('id');
        $data['row'] = $users->first(['id' => $user_id]);

        # Traitement de la modification
        if ($req->posted()) {
            $folder = "uploads/users/";
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            if ($users->validate($_FILES, $_POST, $user_id)) {
                $arr = $req->post();

                // Gestion du mot de passe
                if (empty($arr['password'])) {
                    unset($arr['password']);
                } else {
                    $arr['password'] = password_hash($arr['password'], PASSWORD_DEFAULT);
                }

                // Gestion de l'image
                if (!empty($_FILES['image']['name'])) {
                    $destination = $folder . time() . $_FILES['image']['name'];
                    move_uploaded_file($_FILES['image']['tmp_name'], $destination);

                    $image_class = new Image;
                    $image_class->resize($destination);

                    $arr['image'] = $destination;

                    // Supprimer l'ancienne image si elle existe
                    if (!empty($data['row']->image) && file_exists($data['row']->image)) {
                        unlink($data['row']->image);
                    }
                }

                $users->update($user_id, $arr, 'id');
                message("Profile updated successfully");
                redirect('profile');
            } else {
                $data['errors'] = $users->errors;
            }
        }

        $this->view('profile', $data);
    }
}