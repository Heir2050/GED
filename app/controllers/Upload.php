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

    public function index($action = null, $id = null)
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


        $uploads = new Uploads();
        $req = new Request();
        $ses = new Session();
        $data = [];

        // Grab all users
		$users = (new User())->findAll();
		$data['users'] = $users;

        if ($ses->user('role') == 'admin') {
        	$data['rows'] = $uploads->findAll();
		} else {
			// Afficher les fichiers creer par l'utilisateur lui meme
			$data['rows'] = $uploads->where(['uploaded_by' => $ses->user('id')]);
		}

        $action = $data['action'] = URL(2) ?? 'view';

       
        $data['uploads'] = $uploads;

        $this->view('upload', $data);

    }

    public function file($action = "", $id = null) {
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


        $uploads = new Uploads();
        $req = new Request();
        $ses = new Session();
        $data = [];

        $action = $data['action'] = URL(2) ?? 'view';

        # Configuration de l'ordre
        // $uploads->setOrder_column('id');

        if (!$ses->is_logged_in()) {
            message('Veuillez vous connecter');
            redirect('login');
        }

        if ($action == 'add') {
            if ($req->posted()) {
                if ($uploads->validate($_FILES, $_POST)) {
                    $file = $req->files();
                    $arr = $req->post();

                    // Récupération de l'id du dossier
                    $arr['folder_id'] = $_POST['folder_id'] ?? null;

                    // Sécurise côté serveur l'utilisateur connecté
                    $arr['uploaded_by'] = $ses->user('id');

                    // Gestion de l'upload du fichier
                    if (!empty($file['file_path']['name'])) {
                        $folder = "uploads/files/";
                        if (!file_exists($folder)) {
                            mkdir($folder, 0777, true);
                        }

                        // Nettoyage du nom du fichier pour éviter les caractères spéciaux
                        $originalName = preg_replace('/[^a-zA-Z0-9_\.\-]/', '_', $file['file_path']['name']);
                        $filename = time() . '_' . $originalName;
                        // $arr['file'] = $originalName; // Nom original du fichier
                        $arr['file_path'] = $folder . $filename;

                        // Déplacement du fichier uploadé
                        if (!move_uploaded_file($file['file_path']['tmp_name'], $arr['file_path'])) {
                            $uploads->errors['file_path'] = "Erreur lors de l'upload du fichier.";
                        }
                    } else {
                        // $arr['file'] = null;
                        $arr['file_path'] = null;
                    }

                    // Si pas d'erreur d'upload, on insère
                    if (empty($uploads->errors)) {
                        $uploads->insert($arr);
                        message("Soumission enregistrée avec succès");
                        redirect('upload');
                    }
                }

                $data['errors'] = $uploads->errors;
                $data['formData'] = $req->post(); // Pour pré-remplir le formulaire
            }
        } elseif ($action == 'edit') {
            $data['row'] = $uploads->first(['id' => $id]);

            if ($req->posted()) {
                if ($uploads->validate($_FILES, $_POST, $id)) {
                    $folder = "uploads/files/";

                    if (!file_exists($folder)) {
                        mkdir($folder, 0777, true);
                    }

                    if (!empty($_FILES['file_path']['name'])) {
                        $originalName = preg_replace('/[^a-zA-Z0-9_\.\-]/', '_', $_FILES['file_path']['name']);
                        $filename = time() . '_' . $originalName;
                        $destination = $folder . $filename;
                        move_uploaded_file($_FILES['file_path']['tmp_name'], $destination);

                        $_POST['file_path'] = $destination;

                        // Supprimer l'ancien fichier s'il existe
                        if (file_exists($data['row']->file_path)) {
                            unlink($data['row']->file_path);
                        }
                    }

                    $uploads->update($id, $_POST, 'id');

                    message("Soumission modifiée avec succès");
                    redirect('upload');
                }
                $data['errors'] = $uploads->errors;
            }
        } elseif ($action == 'delete') {
            $data['row'] = $uploads->first(['id' => $id]);

            if ($req->posted()) {
                $uploads->delete($id, 'id');

                // Supprimer le fichier associé
                if (file_exists($data['row']->file_path)) {
                    unlink($data['row']->file_path);
                }

                message("Supprimée avec succès");
                redirect('upload');
            }
        } else {
            // Vue par défaut - liste des soumissions
            $data['rows'] = $uploads->findAll();
        }

        $this->view('upload', $data);
    }

    
}



