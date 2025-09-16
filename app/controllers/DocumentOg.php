<?php
namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');

use Core\Session;
use Core\Request;
use Model\Documents;
use Model\Dossiers;
use Model\Notification;
use Model\User;
use Model\Employes;

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
        $employeModel = new Employes();

        $data = [];

        // Récupérer tous les dossiers avec le nombre de documents
        $data['dossiers'] = $dossier->query("
            SELECT d.*, COUNT(doc.id) as nb_documents 
            FROM dossiers d 
            LEFT JOIN documents doc ON d.id = doc.dossier_id 
            GROUP BY d.id
            ORDER BY d.nom
        ");

        // Si un dossier est spécifié dans l'URL, afficher ses documents
        $dossier_id = $req->get('dossier_id');
        if ($dossier_id) {
            $data['documents'] = $document->where(['dossier_id' => $dossier_id]);
            $data['dossier_courant'] = $dossier->first(['id' => $dossier_id]);
        }

        // Création d'un document
        if ($req->posted() && $ses->is_logged_in()) {
            // Upload du fichier
            $file = $req->files();
            $arr = $req->post();

            if (!empty($file['files']['name'][0])) {
                $dossierName = trim($arr['dossier_name']);
                
                if (empty($dossierName)) {
                    $document->errors['dossier'] = "Le nom du dossier est requis";
                    $data['errors'] = $document->errors;
                    $this->view('documents', $data);
                    return;
                }

                // Chercher ou créer le dossier
                $existingDossier = $dossier->first(['nom' => $dossierName]);
                
                if ($existingDossier) {
                    $dossierId = $existingDossier->id;
                    $dossierPath = "uploads/documents/" . $dossierName . "/";
                } else {
                    // Créer un nouveau dossier
                    $user_id = $ses->user('id');
                    
                    // Vérifier si l'utilisateur a un profil employé
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
                    
                    // Créer le dossier en base
                    $dossierData = [
                        'nom' => $dossierName,
                        'chemin' => 'uploads/documents/' . $dossierName . '/',
                        'createur_id' => $employe->id,
                        'date_creation' => date('Y-m-d H:i:s')
                    ];
                    
                    $dossier->insert($dossierData);
                    
                    // Récupérer l'ID du dossier créé
                    $newDossier = $dossier->first(['nom' => $dossierName]);
                    $dossierId = $newDossier->id;
                    $dossierPath = $newDossier->chemin;
                    
                    // Créer le dossier physique sur le serveur
                    if (!file_exists($dossierPath)) {
                        mkdir($dossierPath, 0777, true);
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
                        
                        // Chemin complet du fichier
                        $filePath = $dossierPath . $storageName;
                        
                        if (move_uploaded_file($file['files']['tmp_name'][$index], $filePath)) {
                            // Enregistrer en base de données
                            $documentData = [
                                'nom' => $originalName,
                                'nom_stockage' => $storageName,
                                'dossier_id' => $dossierId,
                                'uploader_id' => $ses->user('id'),
                                'date_upload' => date('Y-m-d H:i:s'),
                                'date_modification' => date('Y-m-d H:i:s'),
                                'taille' => $file['files']['size'][$index],
                                'type' => $file['files']['type'][$index]
                            ];

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

        $this->view('documents', $data);
    }

    // Méthode pour afficher les documents d'un dossier spécifique
    public function dossier($id = null)
    {
        $ses = new Session();
        $req = new Request();
        $document = new Documents();
        $dossier = new Dossiers();

        $data = [];

        if ($id) {
            $data['dossier'] = $dossier->first(['id' => $id]);
            $data['documents'] = $document->where(['dossier_id' => $id]);
        } else {
            // Rediriger vers la page principale si aucun ID n'est spécifié
            redirect('documents');
        }

        $this->view('documents', $data);
    }
}