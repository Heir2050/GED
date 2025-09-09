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

    public function index()
    {
        $ses = new Session();
        $req = new Request();
        $document = new Documents();
        $dossier = new Dossiers();
        $notification = new Notification();
        $userModel = new User();

        $data = [];

        // Création d'un document
        if ($req->posted() && $ses->is_logged_in()) {
            // Upload du fichier
            $file = $req->files();
            $arr = $req->post();

            if (!empty($file['files']['name'][0])) {
                $folder = "uploads/documents/";
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }

                // Gestion du dossier
                $dossierName = trim($arr['dossier_name']);
                $dossierId = 1; // Valeur par défaut
                
                // Chercher ou créer le dossier
                if (!empty($dossierName)) {
                    $existingDossier = $dossier->first(['nom' => $dossierName]);
                    
                    if ($existingDossier) {
                        $dossierId = $existingDossier->id;
                    } else {
                        // Créer un nouveau dossier
                        $user_id = $ses->user('id');
                        
                        // Vérifier si l'utilisateur a un profil employé
                        $employeModel = new \Model\Employes();
                        $employe = $employeModel->first(['id' => $user_id]);
                        
                        if (!$employe) {
                            // Si pas de lien direct, chercher par email ou autre champ commun
                            $user = $userModel->first(['id' => $user_id]);
                            $employe = $employeModel->first(['email' => $user->email]);
                        }
                        
                        if (!$employe) {
                            $document->errors['dossier'] = "Profil employé non trouvé";
                            $data['errors'] = $document->errors;
                            $this->view('documents', $data);
                            return;
                        }
                        
                        $dossierData = [
                            'nom' => $dossierName,
                            'createur_id' => $employe->id, // ID employé valide
                            'date_creation' => date('Y-m-d H:i:s')
                        ];
                        
                        $dossier->insert($dossierData);
                        
                        // Récupérer l'ID du dossier créé en le recherchant
                        $newDossier = $dossier->first(['nom' => $dossierName]);
                        $dossierId = $newDossier->id;
                    }
                }

                // TRAITEMENT DE TOUS LES FICHIERS
                $uploadSuccess = true;
                $uploadedFiles = 0;
                
                foreach ($file['files']['name'] as $index => $fileName) {
                    if ($file['files']['error'][$index] === UPLOAD_ERR_OK) {
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

                        $filePath = $folder . $storageName;
                        
                        if (move_uploaded_file($file['files']['tmp_name'][$index], $filePath)) {
                            $document->insert($documentData);
                            $uploadedFiles++;
                        } else {
                            $uploadSuccess = false;
                            $document->errors['file'] = "Erreur lors du téléversement du fichier: " . $originalName;
                        }
                    }
                }
                
                if ($uploadSuccess && $uploadedFiles > 0) {
                    message("$uploadedFiles document(s) téléversé(s) avec succès dans le dossier '" . $dossierName . "'");
                    redirect('documents');
                } else {
                    if ($uploadedFiles > 0) {
                        message("$uploadedFiles document(s) téléversé(s), mais certaines erreurs sont survenues");
                    }
                }
            } else {
                $document->errors['file'] = "Veuillez sélectionner au moins un fichier valide";
            }
            
            $data['errors'] = $document->errors;
        }

        // Affichage des documents
        $data['document'] = $document->findAll();

        $this->view('documents', $data);
    }
}