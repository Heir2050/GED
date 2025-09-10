<?php
// this save the forlder and the last file only and have the bug on adding new files
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
                
                // Vérifier que le nom du dossier est valide
                if (empty($dossierName)) {
                    $document->errors['dossier'] = "Le nom du dossier est requis";
                    $data['errors'] = $document->errors;
                    $this->view('documents', $data);
                    return;
                }

                // Nettoyer le nom du dossier pour le chemin de fichier
                $dossierNameClean = preg_replace('/[^a-zA-Z0-9_-]/', '_', $dossierName);
                $dossierId = 1; // Valeur par défaut
                
                // Chercher ou créer le dossier
                $existingDossier = $dossier->first(['nom' => $dossierName]);
                
                if ($existingDossier) {
                    $dossierId = $existingDossier->id;
                    $dossierFolder = "uploads/" . $dossierNameClean . "/";
                } else {
                    // Créer un nouveau dossier dans la base de données
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
                        $this->view('documents', $data);
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
                    $dossierFolder = "uploads/" . $dossierNameClean . "/";
                    if (!file_exists($dossierFolder)) {
                        if (mkdir($dossierFolder, 0777, true)) {
                            // Créer un fichier index.html pour la sécurité
                            // file_put_contents($dossierFolder . 'index.html', '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><h1>Directory access forbidden</h1></body></html>');
                        } else {
                            $document->errors['dossier'] = "Erreur lors de la création du dossier physique";
                            $data['errors'] = $document->errors;
                            $this->view('documents', $data);
                            return;
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
                        $storageName = time() . '_' . uniqid() . '.' . $extension;
                        
                        $documentData = [
                            'nom' => $originalName,
                            'nom_stockage' => $dossierNameClean . '/' . $storageName, // Chemin relatif avec dossier
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
                } else {
                    if (count($errors) > 0) {
                        $document->errors['file'] = implode(', ', $errors);
                    } else {
                        $document->errors['file'] = "Aucun fichier valide n'a pu être téléversé";
                    }
                    $data['errors'] = $document->errors;
                }
            } else {
                $document->errors['file'] = "Veuillez sélectionner au moins un fichier valide";
                $data['errors'] = $document->errors;
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