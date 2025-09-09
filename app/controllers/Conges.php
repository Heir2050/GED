<?php
namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');

use Core\Session;
use Core\Request;
use Model\Conge;
use Model\Notification;
use Model\User;

class Conges
{
    use MainController;

    public function index()
    {
        $ses = new Session();
        $req = new Request();
        $congeModel = new Conge();
        $notification = new Notification();
        $userModel = new User();

        $data = [];

        // Création d'une demande de congé
        if ($req->posted() && $ses->is_logged_in()) {
        // Upload du fichier
        $file = $req->files('document');
        $filePath = null;
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/conges/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $filePath = $uploadDir . time() . '_' . basename($file['name']);
            move_uploaded_file($file['tmp_name'], $filePath);
        }

        // Préparation des données
        $formData = [
            'user_id' => $ses->user('id'),
            'date_debut' => $req->post('date_debut'),
            'date_fin' => $req->post('date_fin'),
            'raison' => $req->post('raison'),
            'document_path' => $filePath,
            'statut' => 'en_attente', // Ajout du statut par défaut
            'date_demande' => date('Y-m-d H:i:s') // Ajout de la date de demande
        ];

        // Création de la demande et récupération de l'ID
        $congeId = $congeModel->insert($formData);

        message("Demande de congé soumise avec succès");
        redirect('conges');
    }

        // Affichage des congés
        $data['user'] = $ses->user();
        if ($ses->user('role') === 'admin') {
            $data['conges'] = $congeModel->findAll();
        } else {
            $data['conges'] = $congeModel->where(['user_id' => $ses->user('id')]);
        }

        $this->view('conge', $data);
    }

    
}