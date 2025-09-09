<?php
namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');

use Core\Session;
use Model\Folders;

class Folder
{
    use MainController;

    // Afficher la liste des dossiers (et sous-dossiers d'un dossier donné)
    public function index($parent_id = null)
    {
        $ses = new Session();
        $folderModel = new Folders();

        if (!$ses->is_logged_in()) {
            message('Veuillez vous connecter');
            redirect('login');
        }

        // Changer ID
        // $folderModel->setOrder_column('folder_id');

        // Récupère les dossiers du niveau courant
        $folders = $folderModel->where(['parent_id' => $parent_id]);
        if ($folders === false) $folders = [];

        $home_folders = $folderModel->query("SELECT * FROM folders WHERE parent_id IS NULL OR parent_id = 0");

        // Récupère les fichiers de ce dossier
        $uploadsModel = new \Model\Uploads();
        $files = $uploadsModel->where(['folder_id' => $parent_id]);
        if ($files === false) $files = [];

        // if ($ses->user('role') == 'admin') {
        // 	$data['own_folder'] = $folderModel->findAll();
		// } else {
		// 	// Afficher les fichiers creer par l'utilisateur lui meme
		// 	$data['own_folder'] = $folderModel->where(['created_by' => $ses->user('id')]);
		// }

        $data = [
            'folders' => $folders,
            'home_folders' => $home_folders,
            'files' => $files,
            'parent_id' => $parent_id
        ];

        $this->view('folders', $data);
    }

    // Créer un dossier ou sous-dossier
    public function add($parent_id = null)
    {
        $ses = new Session();
        $folderModel = new Folders();

        if (!$ses->is_logged_in()) {
            message('Veuillez vous connecter');
            redirect('login');
        }

        // Changer ID
        // $folderModel->setOrder_column('folder_id');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($folderModel->validate($_POST)) {

                $name = trim($_POST['name'] ?? '');
                $created_by = $ses->user('id');
                $parent_id = $_POST['parent_id'] ?? $parent_id; // Priorité au champ POST

                if ($name) {
                    // Insertion en base
                    $folder_id = $folderModel->insert([
                        'name' => $name,
                        'parent_id' => $parent_id,
                        'created_by' => $created_by
                    ]);

                    // Création physique du dossier dans uploads/
                    $basePath = "uploads/";
                    if ($parent_id) {
                        // Récupérer le chemin du parent récursivement
                        $parent = $folderModel->first(['folder_id' => $parent_id]);
                        $parentPath = $basePath . $parent->name . "/";
                    } else {
                        $parentPath = $basePath;
                    }

                    $folderPath = $parentPath . $name . "/";
                    if (!file_exists($folderPath)) {
                        mkdir($folderPath, 0777, true);
                    }

                    message('Dossier créé avec succès');
                    redirect('folder' . ($parent_id ? "/$parent_id" : ''));
                } else {
                    $data['error'] = "Le nom du dossier est requis.";
                }
            }
        }

        $data['parent_id'] = $parent_id;
        $this->view('folders', $data ?? []);
    }
}