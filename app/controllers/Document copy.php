<?php
namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');

use Core\Session;
use Core\Request;
use Model\Documents;
use Model\Dossiers;
use Model\Notification;
use Model\User;

class Document
{
    use MainController;

    public function index($dossier_id = null)
    {
        $ses = new Session();
        $req = new Request();
        $document = new Documents();
        $dossier = new Dossiers();
        $notification = new Notification();
        $userModel = new User();

        $data = [];

        // Création de documents
        if ($req->posted() && $ses->is_logged_in()) {
            // Upload des fichiers
            $file = $req->files();
            $arr = $req->post();

            if (!empty($file['files']['name'][0])) {
                // Gestion du dossier
                $dossierName = trim($arr['dossier_name']);
                $dossierId = 1; // Valeur par défaut
                
                // Chercher ou créer le dossier
                if (!empty($dossierName)) {
                    $existingDossier = $dossier->first(['nom' => $dossierName]);
                    
                    if ($existingDossier) {
                        $dossierId = $existingDossier->id;
                        $dossierFolder = "uploads/documents/dossier_" . $dossierId . "/";

                        // creation du dossier sur le serveur
                        $dossierFolder = __DIR__ . "/../uploads/documents/dossier_" . $dossierId . "/";

                        // Création physique du dossier si inexistant
                        if (!is_dir($dossierFolder)) {
                            mkdir($dossierFolder, 0777, true);
                        }

                    } else {
                        // Créer un nouveau dossier
                        $user_id = $ses->user('id');
                        
                        // Vérifier si l'utilisateur a un profil employé
                        $employeModel = new \Model\Employes();
                        $employe = $employeModel->first(['id' => $user_id]);
                        
                        if (!$employe) {
                            $user = $userModel->first(['id' => $user_id]);
                            $employe = $employeModel->first(['email' => $user->email]);
                        }
                        
                        if (!$employe) {
                            $document->errors['dossier'] = "Profil employé non trouvé";
                            $data['errors'] = $document->errors;
                            $this->view('document', $data);
                            return;
                        }
                        
                        $dossierData = [
                            'nom' => $dossierName,
                            'createur_id' => $employe->id,
                            'date_creation' => date('Y-m-d H:i:s')
                        ];
                        
                        $dossier->insert($dossierData);
                        // $dossierId = $dossier->getLastInsertId();
                        
                        // CRÉATION PHYSIQUE DU DOSSIER SUR LE SERVEUR
                        $dossierFolder = "uploads/" . $dossierName . "/";
                        if (!file_exists($dossierFolder)) {
                            mkdir($dossierFolder, 0777, true);
                            // Créer un fichier index.html pour la sécurité
                            file_put_contents($dossierFolder . 'index.html', '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><h1>Directory access forbidden</h1></body></html>');
                        }
                    }
                }

                // TRAITEMENT DE TOUS LES FICHIERS
                $uploadSuccess = true;
                $uploadedFiles = 0;
                $errors = [];
                
                foreach ($file['files']['name'] as $index => $fileName) {
                    if ($file['files']['error'][$index] === UPLOAD_ERR_OK && !empty($fileName)) {
                        $originalName = basename($fileName);
                        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                        $storageName = time() . '_' . uniqid() . '_' . $index . '.' . $extension;
                        
                        $documentData = [
                            'nom' => $originalName,
                            'nom_stockage' => $storageName,
                            'dossier_id' => $dossierId,
                            'uploader_id' => $ses->user('id'),
                            'date_modification' => date('Y-m-d H:i:s')
                        ];

                        $filePath = $dossierFolder . $storageName;
                        
                        if (move_uploaded_file($file['files']['tmp_name'][$index], $filePath)) {
                            if ($document->insert($documentData)) {
                                $uploadedFiles++;
                            } else {
                                $errors[] = "Erreur BD pour: " . $originalName;
                                if (file_exists($filePath)) {
                                    unlink($filePath);
                                }
                            }
                        } else {
                            $errors[] = "Erreur téléversement pour: " . $originalName;
                            $uploadSuccess = false;
                        }
                    }
                }
                
                if ($uploadedFiles > 0) {
                    if (count($errors) > 0) {
                        message("$uploadedFiles document(s) téléversé(s) avec succès, mais certaines erreurs: " . implode(', ', $errors));
                    } else {
                        message("$uploadedFiles document(s) téléversé(s) avec succès dans le dossier '" . $dossierName . "'");
                    }
                    redirect('documents');
                }
            }
        }

        // Affichage des dossiers ou des fichiers d'un dossier spécifique
        if ($dossier_id) {
            // Afficher les fichiers d'un dossier spécifique
            $data['documents'] = $document->where(['dossier_id' => $dossier_id]);
            $data['dossier_courant'] = $dossier->first(['id' => $dossier_id]);
            $data['affichage'] = 'fichiers';
        } else {
            // Afficher tous les dossiers
            $data['dossiers'] = $dossier->findAll();
            $data['affichage'] = 'dossiers';
            
            // Compter le nombre de fichiers par dossier
            $data['counts'] = [];
            foreach ($data['dossiers'] as $d) {
                $data['counts'][$d->id] = $document->query("SELECT COUNT(*) as count FROM documents WHERE dossier_id = :dossier_id", 
                    ['dossier_id' => $d->id])[0]->count;
            }
        }

        $this->view('documents', $data);
    }

    // Méthode pour afficher les fichiers d'un dossier
    public function dossier($dossier_id)
    {
        $this->index($dossier_id);
    }
}