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
                    $serviceId = $existingDossier->service_id;
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
                        'service_id' => $employe->service_id,
                        'createur_id' => $employe->id,
                        'date_creation' => date('Y-m-d H:i:s')
                    ];
                    
                    $dossier->insert($dossierData);
                    
                    // Récupérer l'ID du dossier créé
                    $newDossier = $dossier->first(['nom' => $dossierName]);
                    $dossierId = $newDossier->id;
                    $dossierPath = $newDossier->chemin;
                    $serviceId = $newDossier->service_id;
                    
                    // Créer le dossier physique sur le serveur
                    if (!file_exists($dossierPath)) {
                        mkdir($dossierPath, 0777, true);
                    }
                }

                // TRAITEMENT DE TOUS LES FICHIERS
                $uploadSuccess = true;
                $uploadedFiles = 0;
                $uploadedDocumentIds = [];
                
                foreach ($file['files']['name'] as $index => $fileName) {
                    if ($file['files']['error'][$index] === UPLOAD_ERR_OK) {
                        $originalName = basename($fileName);
                        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                        $storageName = time() . '_' . uniqid() . '_' . $index . '.' . $extension;
                        
                        // Chemin complet du fichier
                        $filePath = $dossierPath . $storageName;
                        
                        if (move_uploaded_file($file['files']['tmp_name'][$index], $filePath)) {
                            // Déterminer le type MIME du fichier
                            $fileType = $file['files']['type'][$index];
                            if (empty($fileType)) {
                                $fileType = mime_content_type($filePath);
                            }
                            
                            // Enregistrer en base de données
                            $documentData = [
                                'nom' => $originalName,
                                'nom_stockage' => $storageName,
                                'dossier_id' => $dossierId,
                                'uploader_id' => $ses->user('id'),
                                'date_upload' => date('Y-m-d H:i:s'),
                                'date_modification' => date('Y-m-d H:i:s'),
                                'taille' => $file['files']['size'][$index],
                                'type' => $fileType
                            ];

                            $document->insert($documentData);
                            $uploadedFiles++;
                            
                            // Récupérer l'ID du document créé
                            $newDocument = $document->first(['nom_stockage' => $storageName]);
                            $uploadedDocumentIds[] = $newDocument->id;

                            // 🔔 NOTIFICATION AUTOMATIQUE : Notifier les employés du service
                            /*
                            $notificationModel = new Notification();
                            $notificationsSent = $notificationModel->notifyServiceEmployees(
                                $newDocument->id,
                                $serviceId,
                                $ses->user('id'),
                                $originalName,
                                $dossierName
                            );
                            */
                            // Trouver l'employé correspondant à l'utilisateur




                        } else {
                            $uploadSuccess = false;
                            $document->errors['file'] = "Erreur lors du téléversement du fichier: " . $originalName;
                        }
                    }
                }
                
                if ($uploadSuccess && $uploadedFiles > 0) {
                    $message = "$uploadedFiles document(s) téléversé(s) avec succès dans le dossier '$dossierName'";
                    
                    // Ajouter l'information sur les notifications envoyées
                    if (isset($notificationsSent)) {
                        $message .= " - $notificationsSent notification(s) envoyée(s)";
                    }
                    
                    message($message);
                    redirect('document');
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
            redirect('document');
        }

        $this->view('documents', $data);
    }

    // public function dossier_stats($dossier_id = null)
    // {
    //     $ses = new Session();
    //     $req = new Request();
    //     $dossierModel = new Dossiers();
    //     $notificationModel = new Notification();

    //     $data = [];

    //     if ($dossier_id) {
    //         $data['dossier'] = $dossierModel->first(['id' => $dossier_id]);
    //         $data['notifications'] = $notificationModel->getDossierNotifications($dossier_id);
    //         $data['viewers'] = $notificationModel->getDossierViewers($dossier_id);
            
    //         // Récupérer les employés du service qui n'ont pas consulté
    //         $employeModel = new Employes();
    //         $data['non_viewers'] = $employeModel->query("
    //             SELECT e.* 
    //             FROM Employes e 
    //             WHERE e.service_id = :service_id 
    //             AND e.est_actif = 1 
    //             AND e.id NOT IN (
    //                 SELECT n.recipient_id 
    //                 FROM Notifications n 
    //                 WHERE n.dossier_id = :dossier_id 
    //                 AND n.is_read = TRUE
    //             )
    //         ", ['service_id' => $data['dossier']->service_id, 'dossier_id' => $dossier_id]);
    //     }

    //     $this->view('dossier_stats', $data);
    // }




    // Dans Controller\Document.php

    public function dossier_stats($dossier_id = null)
    {
        $ses = new Session();
        $req = new Request();
        $dossierModel = new Dossiers();
        $notificationModel = new Notification();

        $data = [];

        if ($dossier_id) {
            $data['dossier'] = $dossierModel->first(['id' => $dossier_id]);
            
            if ($data['dossier']) {
                $data['user_actions'] = $notificationModel->getUserActionsForDossier($dossier_id);
                $data['dossier_id'] = $dossier_id;
            }
        }

        $this->view('dossier_stats', $data);
    }

    // Dans Controller\Document.php
    // Pour les consultations de documents
/*
    public function view($document_id = null)
    {
        $ses = new Session();
        $req = new Request();
        $documentModel = new Documents();
        $consultationModel = new ConsultationDocument();

        if ($document_id && $ses->is_logged_in()) {
            // Récupérer le document
            $doc = $documentModel->first(['id' => $document_id]);
            
            if ($doc) {
                // Trouver l'employé correspondant
                $userModel = new User();
                $employeModel = new Employes();
                $user = $userModel->first(['id' => $ses->user('id')]);
                $employe = $employeModel->first(['email' => $user->email]);
                
                if ($employe) {
                    // Enregistrer l'action de consultation
                    $consultationModel->trackAction(
                        $document_id, 
                        $employe->id, 
                        'OUVERTURE',
                        $_SERVER['REMOTE_ADDR'] ?? null,
                        $_SERVER['HTTP_USER_AGENT'] ?? null
                    );
                }
                
                // Rediriger vers le fichier
                $dossier = new Dossiers();
                $dossier_info = $dossier->first(['id' => $doc->dossier_id]);
                
                if ($dossier_info && file_exists($dossier_info->chemin . $doc->nom_stockage)) {
                    header('Content-Type: ' . $doc->type);
                    header('Content-Disposition: inline; filename="' . $doc->nom . '"');
                    readfile($dossier_info->chemin . $doc->nom_stockage);
                    exit;
                }
            }
        }
        
        redirect('documents');
    }

    public function download($document_id = null)
    {
        $ses = new Session();
        $req = new Request();
        $documentModel = new Documents();
        $consultationModel = new ConsultationDocument();

        if ($document_id && $ses->is_logged_in()) {
            // Récupérer le document
            $doc = $documentModel->first(['id' => $document_id]);
            
            if ($doc) {
                // Trouver l'employé correspondant
                $userModel = new User();
                $employeModel = new Employes();
                $user = $userModel->first(['id' => $ses->user('id')]);
                $employe = $employeModel->first(['email' => $user->email]);
                
                if ($employe) {
                    // Enregistrer l'action de téléchargement
                    $consultationModel->trackAction(
                        $document_id, 
                        $employe->id, 
                        'TELECHARGEMENT',
                        $_SERVER['REMOTE_ADDR'] ?? null,
                        $_SERVER['HTTP_USER_AGENT'] ?? null
                    );
                }
                
                // Télécharger le fichier
                $dossier = new Dossiers();
                $dossier_info = $dossier->first(['id' => $doc->dossier_id]);
                
                if ($dossier_info && file_exists($dossier_info->chemin . $doc->nom_stockage)) {
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . $doc->nom . '"');
                    header('Content-Length: ' . filesize($dossier_info->chemin . $doc->nom_stockage));
                    readfile($dossier_info->chemin . $doc->nom_stockage);
                    exit;
                }
            }
        }
        
        redirect('documents');
    }

    public function user_actions($employe_id = null, $dossier_id = null)
    {
        $ses = new Session();
        $req = new Request();
        $employeModel = new Employes();
        $dossierModel = new Dossiers();
        $consultationModel = new ConsultationDocument();

        $data = [];

        if ($employe_id && $dossier_id) {
            $data['employe'] = $employeModel->first(['id' => $employe_id]);
            $data['dossier'] = $dossierModel->first(['id' => $dossier_id]);
            
            if ($data['employe'] && $data['dossier']) {
                $data['actions'] = $consultationModel->getUserActionsForDossier($employe_id, $dossier_id);
                $data['stats'] = $consultationModel->getUserActionDetails($employe_id, $dossier_id);
            }
        }

        $this->view('user_actions', $data);
    }
        */
}