<?php

namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');

use \Core\Session;
use \Core\Request;
use Model\Profiles;
use \Model\Image;
use \Model\TypesAction;
use \Model\User;

class Profile 
{
    use MainController;

    public function index()
    {
        $users = new Profiles(); 
        $req = new Request();
        $ses = new Session();
        $user = new User();
        $data = [];

        if (!$ses->is_logged_in()) {
            // message('Please login');
            redirect('login');
        }

        $user_id = $ses->user('id');
        $data['row'] = $users->first(['id' => $user_id]);

        $users = new Profiles();
        $user_info = $users->first(['id' => $ses->user('id')]);

        if ($user_info) {
            $nom_complet = esc($user_info->nom ?? '');
        }

        if ($req->posted()) {
            $folder = "uploads/users/";
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            if ($users->validate($_FILES, $_POST, $user_id)) {
                $arr = $req->post();

                if (empty($arr['password'])) {
                    unset($arr['password']);
                } else {
                    $arr['password'] = password_hash($arr['password'], PASSWORD_DEFAULT);
                }

                if (!empty($_FILES['photo']['name'])) {
                    $destination = $folder . time() . $_FILES['photo']['name'];
                    move_uploaded_file($_FILES['photo']['tmp_name'], $destination);

                    $image_class = new \Model\Image;
                    $image_class->resize($destination);
                    $arr['photo'] = $destination;

                    if (!empty($data['row']->photo) && file_exists($data['row']->photo)) {
                        unlink($data['row']->photo);
                    }
                }

                $users->update($user_id, $arr, 'id');

                // ✅ Journaliser l'action
                $this->enregistrerAction(
                    'MODIFICATION_UTILISATEUR',
                    "Modification d'un utilisateur" . " " . $data['row']->nom . " " . $data['row']->prenom
                );

                message("Profile updated successfully");
                redirect('profile');
            } else {
                $data['errors'] = $users->errors;
            }
        }

        $this->view('profile', $data);
    }

    protected function enregistrerAction($type_action, $details = '', $document_id = null, $dossier_id = null, $employe_cible_id = null)
    {
        $ses = new Session();
        if (!$ses->is_logged_in()) return false;

        

        $historiqueModel = new \Model\HistoriqueActions();
        return $historiqueModel->enregistrerAction(
            $ses->user('id'),
            $type_action,
            $details,
            $document_id,
            $dossier_id,
            $employe_cible_id
        );
    }
}