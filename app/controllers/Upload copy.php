<?php

namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');

use \Core\Session; //Importing namespace
use \Core\Request;
use Model\User;
use \Model\Image;
use Model\Uploads; // Importing the Upload model with an alias

class Upload 
{
    use MainController; // this importing another class

    public function index()
    {
        $users = new User();
        $req = new Request();
        $ses = new Session();
        $uploads = new Uploads();
        $data = [];

        # After doing anything, we unsure that the user is loged in.
        if (!$ses->is_logged_in()) {
            message('Please login as admin');
            redirect('login');
        }

        // UPLOADING FILES
        if ($req->posted()) {
            // 1. Valide le formulaire (POST + FILES)
            if ($uploads->validate($_FILES, $_POST)) {

                // 2. Si tout est valide, alors tu traites les fichiers
                $files = $req->files();
                $arr = $req->post();

                $folder = "uploads/files/";
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }

                foreach ($files as $key => $file) {
                    if (!empty($file['name'])) {
                        $filePath = $folder . $file['name'];
                        move_uploaded_file($file['tmp_name'], $filePath);

                        // Traitement spécial pour les images
                        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                            $image_class = new \Model\Image();
                            $image_class->resize($filePath, 1000);
                        }

                        $arr[$key] = $filePath;
                    }
                }

                $uploads->insert($arr);
                message("Product added successfully");
                redirect('home');
            }

            // 3. Si la validation échoue, rien n'est enregistré
            $data['errors'] = $uploads->errors;
        }

        $this->view('upload', $data);
    }
}