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

use Model\EtatDossierUtilisateur;

class Document
{
    use MainController;

    public function index()
    {
        $ses = new Session();
        $req = new Request();
        $document = new Documents();
        $dossier = new Dossiers();
        $envoiModel = new \Model\EnvoiDossiers();
        
        $userModel = new User();
        $employeModel = new Employes();

        $data = [];

        if (!$ses->is_logged_in()) {
            redirect('login');
        }

        $service_id = $this->getServiceIdUtilisateur($ses);

        

        // Récupérer les informations de l'employé
        $employe = $this->getEmployeInfo($ses);
        if (!$employe) {
            message("Profil employé non trouvé", 'error');
            redirect('logout');
        }

        // Ajouter l'employe aux données pour la vue
        $data['employe'] = $employe;

        // Récupérer les dossiers visibles
        $dossiers = $dossier->getDossiersVisibles(
            $employe->id, 
            $employe->service_id, 
            $employe->role_service
        );

        // Déterminer le type d'accès pour chaque dossier
        foreach ($dossiers as $dossier_item) {
            $dossier_item->type_acces = $this->determinerTypeAcces(
                $dossier_item, 
                $employe->service_id, 
                $employe->role_service
            );
        }

        // Trier les dossiers par date de création (du plus récent au plus ancien)
        if (!empty($dossiers)) {
            usort($dossiers, function($a, $b) {
                $dateA = strtotime($a->date_creation);
                $dateB = strtotime($b->date_creation);
                return $dateB - $dateA; // Ordre décroissant (plus récent en premier)
            });
        }

        // PAGINATION
        $page = $req->get('page') ? (int)$req->get('page') : 1;
        $perPage = 12; // 12 dossiers par page
        $totalDossiers = count($dossiers);
        $totalPages = ceil($totalDossiers / $perPage);
        
        // Validation de la page
        if ($page < 1) $page = 1;
        if ($page > $totalPages && $totalPages > 0) $page = $totalPages;
        
        // Calcul des indices pour la pagination
        $startIndex = ($page - 1) * $perPage;
        $dossiersPagines = array_slice($dossiers, $startIndex, $perPage);

        // Données pour la vue
        $data['dossiers'] = $dossiersPagines;
        $data['pagination'] = [
            'page' => $page,
            'totalPages' => $totalPages,
            'totalDossiers' => $totalDossiers,
            'perPage' => $perPage,
            'startIndex' => $startIndex + 1,
            'endIndex' => min($startIndex + $perPage, $totalDossiers)
        ];
        // FIN PAGINATION


        // Si un dossier est spécifié, afficher ses documents
        $dossier_id = $req->get('dossier_id');
        if ($dossier_id) {
            $data['documents'] = $document->where(['dossier_id' => $dossier_id]);
            $data['dossier_courant'] = $dossier->first(['id' => $dossier_id]);
            
            // Vérifier si l'utilisateur peut accéder à ce dossier
            $accesAutorise = $this->verifierAccesDossier($dossier_id, $employe->id, $employe->service_id, $employe->role_service);
            
            if (!$accesAutorise) {
                message("Accès non autorisé à ce dossier", 'error');
                redirect('document');
            }
            
            // Initialiser les états si nécessaire
            if ($data['dossier_courant']) {
                $dossier->initialiserEtatsDossier($dossier_id);
                $data['etats_utilisateurs'] = $dossier->getEtatsUtilisateurs($dossier_id);
                $data['tous_ont_cloture'] = $dossier->tousUtilisateursOntCloture($dossier_id);
                
                // Mettre à jour l'état de l'utilisateur courant
                $this->mettreAJourEtatUtilisateur($dossier_id, $employe->id, 'TRAITEMENT');
                
                // Marquer les notifications d'envoi de ce dossier comme lues
                $this->marquerNotificationsEnvoiCommeLues($dossier_id, $employe->id);
            }
        }

        // Création d'un document
        if ($req->posted() && $ses->is_logged_in()) {
            // Upload du fichier
            $file = $req->files();
            $arr = $req->post();

            if (!empty($file['files']['name'][0])) {
                // CAS 1: Ajout dans un dossier existant (dossier_id fourni)
                if (!empty($arr['dossier_id'])) {
                    $dossierId = $arr['dossier_id'];
                    $dossierExistant = $dossier->first(['id' => $dossierId]);
                    
                    if (!$dossierExistant) {
                        $document->errors['dossier'] = "Dossier introuvable";
                        $data['errors'] = $document->errors;
                        $this->view('documents', $data);
                        return;
                    }
                    
                    // Vérifier que l'utilisateur a le droit d'ajouter des fichiers à ce dossier
                    $accesAutorise = $this->verifierAccesDossier($dossierId, $employe->id, $employe->service_id, $employe->role_service);
                    
                    if (!$accesAutorise) {
                        message("Accès non autorisé à ce dossier", 'error');
                        redirect('document');
                    }
                    
                    $dossierName = $dossierExistant->nom;
                    $dossierPath = ROOTPATH . "/" . $dossierExistant->chemin;
                    $serviceId = $dossierExistant->service_id;
                    
                    // Vérifier que le dossier physique existe
                    if (!file_exists($dossierPath)) {
                        mkdir($dossierPath, 0777, true);
                    }
                    
                } 
                // CAS 2: Création d'un nouveau dossier (dossier_name fourni)
                else {
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
                        $dossierPath = ROOTPATH . "/uploads/documents/" . $dossierName . "/";
                        $serviceId = $existingDossier->service_id;
                        
                        // Vérifier que le dossier physique existe
                        if (!file_exists($dossierPath)) {
                            mkdir($dossierPath, 0777, true);
                        }
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
                        
                        // Vérifier/créer le répertoire parent
                        $parentDir = ROOTPATH . "/uploads/documents/";
                        if (!file_exists($parentDir)) {
                            mkdir($parentDir, 0777, true);
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
                        $dossierPath = ROOTPATH . "/" . $newDossier->chemin; // Chemin absolu
                        $serviceId = $newDossier->service_id;
                        
                        // Créer le dossier physique sur le serveur
                        if (!file_exists($dossierPath)) {
                            mkdir($dossierPath, 0777, true);
                        }
                        
                        // ENREGISTRER L'ACTION DE CRÉATION DE DOSSIER
                        $this->enregistrerAction(
                            'CREATION_DOSSIER',
                            "Création du dossier \"{$dossierName}\"",
                            null,
                            $dossierId
                        );
                    }
                }

                // TRAITEMENT DE TOUS LES FICHIERS (commun aux deux cas)
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
                                // 'taille' => $file['files']['size'][$index],
                                // 'type' => $fileType
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

                        } else {
                            $uploadSuccess = false;
                            $document->errors['file'] = "Erreur lors du téléversement du fichier: " . $originalName;
                        }
                    }
                }
                
                if ($uploadSuccess && $uploadedFiles > 0) {
                    $message = "$uploadedFiles document(s) téléversé(s) avec succès";
                    
                    // Message différent selon le cas
                    if (!empty($arr['dossier_id'])) {
                        $message .= " dans le dossier existant '$dossierName'";
                        // Rediriger vers le dossier actuel
                        redirect('document?dossier_id=' . $dossierId);
                    } else {
                        $message .= " dans le nouveau dossier '$dossierName'";
                        // Rediriger vers la liste des dossiers
                        redirect('document');
                    }
                    
                    // Ajouter l'information sur les notifications envoyées
                    if (isset($notificationsSent)) {
                        $message .= " - $notificationsSent notification(s) envoyée(s)";
                    }
                    
                    message($message);
                    
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

    public function cloturer_dossier($dossier_id = null)
    {
        $ses = new Session();
        $dossier = new Dossiers();
        
        if ($dossier_id && $ses->is_logged_in()) {
            $employe_id = $this->getEmployeId($ses);
            
            if ($employe_id) {
                error_log("Tentative de clôture - Dossier: $dossier_id, Employé: $employe_id");
                
                // Debug: vérifier l'état actuel
                $etat_actuel = $dossier->query("
                    SELECT etat FROM etatdossierutilisateur 
                    WHERE dossier_id = :dossier_id AND employe_id = :employe_id
                ", ['dossier_id' => $dossier_id, 'employe_id' => $employe_id]);
                
                error_log("État actuel: " . print_r($etat_actuel, true));
                
                $result = $dossier->mettreAJourEtat($dossier_id, $employe_id, 'CLOTURE');

                if ($result) {
                    // ENREGISTRER L'ACTION DE CLÔTURE
                    $dossier_data = $dossier->first(['id' => $dossier_id]);
                    if ($dossier_data) {
                        $this->enregistrerAction(
                            'CLOTURE_DOSSIER',
                            "Clôture du dossier \"{$dossier_data->nom}\"",
                            null,
                            $dossier_id
                        );
                    }
                    // message("Dossier marqué comme clôturé");
                }

                error_log("Résultat mise à jour: " . ($result ? 'SUCCÈS' : 'ÉCHEC'));
                
                // Vérifier l'état après mise à jour
                /*
                    $etat_apres = $dossier->query("
                        SELECT etat FROM etatdossierutilisateur 
                        WHERE dossier_id = :dossier_id AND employe_id = :employe_id
                    ", ['dossier_id' => $dossier_id, 'employe_id' => $employe_id]);
                    
                    error_log("État après: " . print_r($etat_apres, true));
                */
                
                message("Dossier marqué comme clôturé");
            } else {
                error_log("Employé ID non trouvé");
                message("Erreur: Employé non trouvé", 'error');
            }
        } else {
            error_log("Session non connectée ou dossier_id manquant");
            message("Erreur de session", 'error');
        }
        
        redirect('document/etats_utilisateurs/' . $dossier_id);
    }

    public function archiver_dossier($dossier_id = null)
    {
        $ses = new Session();
        $dossier = new Dossiers();
        
        if ($dossier_id && $ses->is_logged_in()) {
            if ($dossier->tousUtilisateursOntCloture($dossier_id)) {
                $dossier_data = $dossier->first(['id' => $dossier_id]);
                
                if ($dossier_data) {
                    $dossier->archiverDossier($dossier_id);
                    
                    // ENREGISTRER L'ACTION D'ARCHIVAGE
                    $this->enregistrerAction(
                        'ARCHIVAGE_DOSSIER',
                        "Archivage du dossier \"{$dossier_data->nom}\"",
                        null,
                        $dossier_id
                    );
                    
                    message("Dossier archivé avec succès");
                }
            } else {
                message("Impossible d'archiver : tous les utilisateurs n'ont pas clôturé le dossier", 'error');
                redirect('document?dossier_id=' . $dossier_id);
            }
        }
        
        redirect('document');
    }

    public function archives()
    {
        $ses = new Session();
        $dossier = new Dossiers();
        
        $service_id = $this->getServiceIdUtilisateur($ses);
        $data['dossiers_archives'] = $dossier->getDossiersArchives($service_id);
        
        $this->view('archives', $data);
    }

    private function getServiceIdUtilisateur($ses)
    {
        $employeModel = new Employes();
        $employe = $employeModel->first(['id' => $ses->user('id')]);
        
        if (!$employe) {
            $userModel = new User();
            $user = $userModel->first(['id' => $ses->user('id')]);
            $employe = $employeModel->first(['email' => $user->email]);
        }
        
        return $employe ? $employe->service_id : null;
    }

    private function getEmployeId($ses)
    {
        $employeModel = new Employes();
        $employe = $employeModel->first(['id' => $ses->user('id')]);
        
        if (!$employe) {
            $userModel = new User();
            $user = $userModel->first(['id' => $ses->user('id')]);
            $employe = $employeModel->first(['email' => $user->email]);
        }
        
        return $employe ? $employe->id : null;
    }

// Others
protected function getEtatLabel($etat)
{
    $labels = [
        'NON_OUVERT' => 'Non ouvert',
        'TRAITEMENT' => 'En traitement', 
        'CLOTURE' => 'Clôturé'
    ];
    return $labels[$etat] ?? $etat;
}

// protected function getEmployeId($ses)
// {
//     $employeModel = new Employes();
//     $employe = $employeModel->first(['id' => $ses->user('id')]);
    
//     if (!$employe) {
//         $userModel = new User();
//         $user = $userModel->first(['id' => $ses->user('id')]);
//         $employe = $employeModel->first(['email' => $user->email]);
//     }
    
//     return $employe ? $employe->id : null;
// }

// Nouvelle méthode pour afficher les états des utilisateurs
public function etats_utilisateurs($dossier_id = null)
{
    $ses = new Session();
    $dossier = new Dossiers();
    
    $data = [];
    $data['ses'] = $ses; // Ajouter la session aux données
    
    if ($dossier_id) {
        $data['dossier_courant'] = $dossier->first(['id' => $dossier_id]);
        
        if ($data['dossier_courant']) {
            // Initialiser les états si nécessaire
            $dossier->initialiserEtatsDossier($dossier_id);
            
            $data['etats_utilisateurs'] = $dossier->getEtatsUtilisateurs($dossier_id);
            $data['tous_ont_cloture'] = $dossier->tousUtilisateursOntCloture($dossier_id);
            
            // Mettre à jour l'état de l'utilisateur courant à "TRAITEMENT" s'il consulte cette page
            $employe_id = $this->getEmployeId($ses);
            if ($employe_id) {
                $this->mettreAJourEtatUtilisateur($dossier_id, $employe_id, 'TRAITEMENT');
            }
        }
    }
    
    $this->view('etats_utilisateurs', $data);
}
// Dans Document.php, ajoutez cette méthode temporaire
public function reparer_etats()
{
    $dossier = new Dossiers();
    $envoiModel = new \Model\EnvoiDossiers();
    
    // Réinitialiser tous les états
    $dossier->query("TRUNCATE TABLE etatdossierutilisateur");
    
    // Recréer tous les états pour les dossiers internes
    $dossiers = $dossier->where(['est_archive' => false]);
    foreach ($dossiers as $d) {
        $dossier->initialiserEtatsDossier($d->id);
    }
    
    // Recréer les états pour les dossiers envoyés
    $envoisActifs = $envoiModel->query("
        SELECT DISTINCT dossier_id, service_id, type_envoi, role_service 
        FROM envoidossiers 
        WHERE est_actif = true
    ");
    
    foreach ($envoisActifs as $envoi) {
        // Utiliser une requête directe pour ajouter les utilisateurs
        if ($envoi->type_envoi == 'SERVICE') {
            $query = "
                INSERT IGNORE INTO etatdossierutilisateur (dossier_id, employe_id, etat)
                SELECT :dossier_id, e.id, 'NON_OUVERT'
                FROM employes e
                WHERE e.service_id = :service_id 
                AND e.est_actif = TRUE
            ";
            $params = [
                'dossier_id' => $envoi->dossier_id,
                'service_id' => $envoi->service_id
            ];
        } else if ($envoi->type_envoi == 'ROLE_SERVICE') {
            $query = "
                INSERT IGNORE INTO etatdossierutilisateur (dossier_id, employe_id, etat)
                SELECT :dossier_id, e.id, 'NON_OUVERT'
                FROM employes e
                WHERE e.service_id = :service_id 
                AND e.role_service = :role_service
                AND e.est_actif = TRUE
            ";
            $params = [
                'dossier_id' => $envoi->dossier_id,
                'service_id' => $envoi->service_id,
                'role_service' => $envoi->role_service
            ];
        } else {
            continue; // Type d'envoi non supporté
        }
        
        $dossier->query($query, $params);
    }
    
    message("États des dossiers réparés avec succès");
    redirect('document');
}

/**
 * Méthode de test pour vérifier le fonctionnement
 */
public function test_etats_envoi($dossier_id = null)
{
    if (!$dossier_id) {
        message("ID du dossier requis", 'error');
        redirect('document');
    }
    
    $dossier = new Dossiers();
    $envoiModel = new \Model\EnvoiDossiers();
    
    // Vérifier les envois actifs pour ce dossier
    $envois = $envoiModel->query("
        SELECT * FROM envoidossiers 
        WHERE dossier_id = :dossier_id AND est_actif = true
    ", ['dossier_id' => $dossier_id]);
    
    echo "<h1>Test des états pour le dossier $dossier_id</h1>";
    echo "<h2>Envois actifs :</h2>";
    echo "<pre>" . print_r($envois, true) . "</pre>";
    
    // Vérifier les états actuels
    $etats = $dossier->query("
        SELECT edu.*, e.nom, e.prenom, s.nom as service_nom
        FROM etatdossierutilisateur edu
        JOIN employes e ON edu.employe_id = e.id
        JOIN services s ON e.service_id = s.id
        WHERE edu.dossier_id = :dossier_id
        ORDER BY s.nom, e.nom
    ", ['dossier_id' => $dossier_id]);
    
    echo "<h2>États actuels :</h2>";
    echo "<pre>" . print_r($etats, true) . "</pre>";
    
    // Tester l'ajout manuel
    echo "<h2>Test d'ajout manuel :</h2>";
    foreach ($envois as $envoi) {
        if ($envoi->type_envoi == 'SERVICE') {
            $query = "
                INSERT IGNORE INTO etatdossierutilisateur (dossier_id, employe_id, etat)
                SELECT :dossier_id, e.id, 'NON_OUVERT'
                FROM employes e
                WHERE e.service_id = :service_id 
                AND e.est_actif = TRUE
            ";
            $params = [
                'dossier_id' => $envoi->dossier_id,
                'service_id' => $envoi->service_id
            ];
        } else if ($envoi->type_envoi == 'ROLE_SERVICE') {
            $query = "
                INSERT IGNORE INTO etatdossierutilisateur (dossier_id, employe_id, etat)
                SELECT :dossier_id, e.id, 'NON_OUVERT'
                FROM employes e
                WHERE e.service_id = :service_id 
                AND e.role_service = :role_service
                AND e.est_actif = TRUE
            ";
            $params = [
                'dossier_id' => $envoi->dossier_id,
                'service_id' => $envoi->service_id,
                'role_service' => $envoi->role_service
            ];
        }
        
        $result = $dossier->query($query, $params);
        echo "Ajout pour envoi {$envoi->id} (Service: {$envoi->service_id}, Type: {$envoi->type_envoi}): " . ($result ? 'SUCCÈS' : 'ÉCHEC') . "<br>";
    }
    
    die(); // Arrêter l'exécution pour voir les résultats
}

/**
 * Test de la nouvelle logique d'ajout en masse
 */
public function test_ajout_masse($dossier_id = null)
{
    if (!$dossier_id) {
        message("ID du dossier requis", 'error');
        redirect('document');
    }
    
    $dossier = new Dossiers();
    $envoiModel = new \Model\EnvoiDossiers();
    $employeModel = new Employes();
    
    echo "<h1>Test d'ajout en masse pour le dossier $dossier_id</h1>";
    
    // Vérifier les envois vers des services
    $envois = $envoiModel->query("
        SELECT ed.*, s.nom as service_nom
        FROM envoidossiers ed
        JOIN services s ON ed.service_id = s.id
        WHERE ed.dossier_id = :dossier_id AND ed.est_actif = true
    ", ['dossier_id' => $dossier_id]);
    
    echo "<h2>Envois actifs :</h2>";
    echo "<pre>" . print_r($envois, true) . "</pre>";
    
    foreach ($envois as $envoi) {
        echo "<h3>Service destinataire : {$envoi->service_nom} (ID: {$envoi->service_id})</h3>";
        
        // Compter les employés du service
        $employesService = $employeModel->query("
            SELECT COUNT(*) as count 
            FROM employes 
            WHERE service_id = :service_id AND est_actif = TRUE
        ", ['service_id' => $envoi->service_id]);
        
        echo "Nombre d'employés dans le service : " . $employesService[0]->count . "<br>";
        
        // Compter les employés déjà dans etatdossierutilisateur
        $employesDansEtat = $dossier->query("
            SELECT COUNT(*) as count 
            FROM etatdossierutilisateur edu
            JOIN employes e ON edu.employe_id = e.id
            WHERE edu.dossier_id = :dossier_id 
            AND e.service_id = :service_id
        ", [
            'dossier_id' => $dossier_id,
            'service_id' => $envoi->service_id
        ]);
        
        echo "Employés déjà dans etatdossierutilisateur : " . $employesDansEtat[0]->count . "<br>";
        
        // Simuler l'ouverture par un utilisateur du service
        $premierEmploye = $employeModel->query("
            SELECT id FROM employes 
            WHERE service_id = :service_id AND est_actif = TRUE 
            LIMIT 1
        ", ['service_id' => $envoi->service_id]);
        
        if (!empty($premierEmploye)) {
            $employeId = $premierEmploye[0]->id;
            echo "Simulation d'ouverture par l'employé ID : $employeId<br>";
            
            // Utiliser notre nouvelle méthode
            $this->mettreAJourEtatUtilisateur($dossier_id, $employeId, 'TRAITEMENT');
            
            // Vérifier le résultat
            $employesApres = $dossier->query("
                SELECT COUNT(*) as count 
                FROM etatdossierutilisateur edu
                JOIN employes e ON edu.employe_id = e.id
                WHERE edu.dossier_id = :dossier_id 
                AND e.service_id = :service_id
            ", [
                'dossier_id' => $dossier_id,
                'service_id' => $envoi->service_id
            ]);
            
            echo "Employés après ouverture : " . $employesApres[0]->count . "<br>";
            echo "Résultat : " . ($employesApres[0]->count == $employesService[0]->count ? "✅ SUCCÈS - Tous ajoutés" : "❌ ÉCHEC - Pas tous ajoutés") . "<br><br>";
        }
    }
    
    die(); // Arrêter l'exécution pour voir les résultats
}

protected function getEmployeIdFromSession()
{
    $ses = new Session();
    if (!$ses->is_logged_in()) {
        return null;
    }
    
    $employeModel = new \Model\Employes();
    $employe = $employeModel->first(['id' => $ses->user('id')]);
    
    if (!$employe) {
        $userModel = new \Model\User();
        $user = $userModel->first(['id' => $ses->user('id')]);
        if ($user) {
            $employe = $employeModel->first(['email' => $user->email]);
        }
    }
    
    return $employe ? $employe->id : null;
}


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


// TRANSFERT DE DOSSIER PAR SERVICE OU PAR FONCTION




/**
 * Envoyer un dossier
 */
/**
 * Envoyer un dossier
 */
public function envoyer($dossier_id = null)
{
    $ses          = new Session();
    $req          = new Request();
    $dossier      = new Dossiers();
    $envoiModel   = new \Model\EnvoiDossiers();
    $serviceModel = new \Model\Services();

    if (!$ses->is_logged_in()) {
        redirect('login');
    }

    $employe = $this->getEmployeInfo($ses);
    if (!$employe) {
        message("Profil employé non trouvé", 'error');
        redirect('document');
    }

    // -----  RÉCUPÉRER LE DOSSIER  -----
    $dossier_data = $dossier->first(['id' => $dossier_id]);
    if (!$dossier_data) {
        message("Dossier non trouvé", 'error');
        redirect('document');
    }

    // -----  TRAITEMENT DU POST  -----
    if ($req->posted() && $dossier_id) {
        /* CORRECTION : Conversion explicite en NULL */
        $type_envoi   = $req->post('type_envoi');
        $service_dest = $req->post('service_dest') ? (int)$req->post('service_dest') : null;
        $role_dest    = $req->post('role_dest') ?: null;

        // Conversion des chaînes vides en NULL
        if ($service_dest === '') $service_dest = null;
        if ($role_dest === '') $role_dest = null;

        // validation
        if ($type_envoi == 'SERVICE' && empty($service_dest)) {
            message("Veuillez sélectionner un service", 'error');
            redirect('document/envoyer/' . $dossier_id);
        }
        if ($type_envoi == 'ROLE_SERVICE' && empty($role_dest)) {
            message("Veuillez sélectionner un rôle", 'error');
            redirect('document/envoyer/' . $dossier_id);
        }

        // envoi
        $result = $envoiModel->envoyerDossier(
            $dossier_id,
            $type_envoi,
            $service_dest,   // peut être NULL
            $role_dest,
            $employe->id
        );

        // log & message
        if ($result) {
            $dossier->marquerCommeEnvoye($dossier_id);

            $dest = $type_envoi === 'SERVICE'
                ? 'Service : ' . ($serviceModel->first(['id' => $service_dest])->nom ?? "ID:$service_dest")
                : 'Rôle : ' . $role_dest . ($service_dest ? " (Service:$service_dest)" : " (tous services)");

            $this->enregistrerAction(
                'ENVOI_DOSSIER',
                "Envoi du dossier \"{$dossier_data->nom}\" à $dest",
                null,
                $dossier_id
            );
            message("Dossier envoyé avec succès ✅");
        } else {
            message("Ce dossier a déjà été envoyé à cette destination", 'error');
        }
        redirect('document?dossier_id=' . $dossier_id);
    }

    // -----  PRÉPARATION DE LA VUE  -----
    $service_utilisateur = $employe->service_id;
    $tous_services       = $serviceModel->findAll();

    $envois_existants = $envoiModel->where([
        'dossier_id' => $dossier_id,
        'est_actif'  => true
    ]);

    $services_exclus = [$service_utilisateur];
    if ($envois_existants) {
        foreach ($envois_existants as $e) {
            if ($e->service_id) {
                $services_exclus[] = $e->service_id;
            }
        }
    }

    $services_disponibles = array_filter($tous_services, function ($s) use ($services_exclus) {
        return !in_array($s->id, $services_exclus);
    });

    $roles = [];
    try {
        $employeModel = new \Model\Employes();
        if (method_exists($employeModel, 'getEnumValues')) {
            $roles = $employeModel->getEnumValues('role_service');
        }
    } catch (\Exception $e) {
        error_log("Erreur récupération rôles: " . $e->getMessage());
    }

    $data['dossier']  = $dossier_data;
    $data['services'] = $services_disponibles;
    $data['roles']    = $roles;

    $this->view('envoyer_dossier', $data);
}


/**
 * VERIT_FICATION DE L'ENVOIE EXISTANTE
 */
/*
    public function check_envoi_existant()
    {
        $req = new Request();
        $envoiModel = new \Model\EnvoiDossiers();

        $dossier_id = $req->post('dossier_id');
        $service_id = $req->post('service_id');
        $role_service = $req->post('role_service');
        $type_envoi = $req->post('type_envoi');

        $conditions = [
            'dossier_id' => $dossier_id,
            'service_id' => $service_id,
            'type_envoi' => $type_envoi,
            'est_actif' => true
        ];

        if ($type_envoi === 'ROLE_SERVICE') {
            $conditions['role_service'] = $role_service;
        }

        $existe = $envoiModel->first($conditions);

        header('Content-Type: application/json');
        echo json_encode(['exists' => $existe ? true : false]);
        exit;
    }
*/



/**
 * Retirer un envoi de dossier
 */
public function retirer_envoi($envoi_id = null)
{
    $ses = new Session();
    $envoiModel = new \Model\EnvoiDossiers();

    if (!$ses->is_logged_in() || !$envoi_id) {
        redirect('document');
    }

    $envoi = $envoiModel->first(['id' => $envoi_id]);
    if ($envoi) {
        // Vérifier que le dossier n'est pas archivé
        $dossierModel = new Dossiers();
        $dossier = $dossierModel->first(['id' => $envoi->dossier_id]);
        
        if ($dossier && !$dossier->est_archive) {
            $envoiModel->retirerEnvoi($envoi_id);
            
            // Enregistrer l'action
            $destinataire = ($envoi->type_envoi == 'SERVICE') ? "Service ID: {$envoi->service_id}" : "Rôle: {$envoi->role_service} dans Service: {$envoi->service_id}";
            $this->enregistrerAction(
                'RETRAIT_ENVOI_DOSSIER', 
                "Retrait de l'envoi du dossier \"{$dossier->nom}\" à $destinataire",
                null,
                $dossier->id
            );
            
            message("Envoi retiré avec succès");
        }
    }

    redirect('document');
}

/**
 * Vérifier l'accès à un dossier - CORRIGÉ
 */
/**
 * Vérifier l'accès à un dossier - Version corrigée pour ROLE_SERVICE
 */
private function verifierAccesDossier($dossier_id, $employe_id, $service_id, $role_service)
{
    $dossierModel = new Dossiers();
    $envoiModel = new \Model\EnvoiDossiers();
    
    // Vérifier d'abord si c'est un dossier interne
    $dossierInterne = $dossierModel->first([
        'id' => $dossier_id,
        'service_id' => $service_id,
        'est_envoye' => false,
        'est_archive' => false
    ]);
    
    if ($dossierInterne) {
        return true;
    }
    
    // Vérifier les envois avec une requête qui gère ROLE_SERVICE
    $query = "
        SELECT COUNT(*) as count 
        FROM envoidossiers ed
        JOIN dossiers d ON ed.dossier_id = d.id
        WHERE ed.dossier_id = :dossier_id
        AND ed.est_actif = true
        AND d.est_archive = false
        AND (
            -- Envoi par service
            (ed.type_envoi = 'SERVICE' AND ed.service_id = :service_id)
            OR 
            -- Envoi par rôle (service_id peut être NULL ou correspondre)
            (ed.type_envoi = 'ROLE_SERVICE' AND ed.role_service = :role_service 
             AND (ed.service_id IS NULL OR ed.service_id = :service_id2))
        )
    ";
    
    $result = $envoiModel->query($query, [
        'dossier_id' => $dossier_id,
        'service_id' => $service_id,
        'role_service' => $role_service,
        'service_id2' => $service_id
    ]);
    
    return ($result && $result[0]->count > 0);
}

/**
 * Méthodes utilitaires
 */
private function getEmployeInfo($ses)
{
    $employeModel = new Employes();
    $employe = $employeModel->first(['id' => $ses->user('id')]);
    
    if (!$employe) {
        $userModel = new User();
        $user = $userModel->first(['id' => $ses->user('id')]);
        if ($user) {
            $employe = $employeModel->first(['email' => $user->email]);
        }
    }
    
    return $employe;
}

private function mettreAJourEtatUtilisateur($dossier_id, $employe_id, $etat)
{
    $dossierModel = new Dossiers();
    $envoiModel = new \Model\EnvoiDossiers();
    
    // Vérifier si c'est un dossier reçu (envoyé à un service)
    $dossier = $dossierModel->first(['id' => $dossier_id]);
    $employeModel = new Employes();
    $employe = $employeModel->first(['id' => $employe_id]);
    
    if (!$dossier || !$employe) {
        return false;
    }
    
    // Vérifier si ce dossier a été envoyé au service de l'utilisateur
    $envoiVersService = $envoiModel->query("
        SELECT * FROM envoidossiers 
        WHERE dossier_id = :dossier_id 
        AND service_id = :service_id 
        AND est_actif = true
    ", [
        'dossier_id' => $dossier_id,
        'service_id' => $employe->service_id
    ]);
    
    // Si c'est un dossier reçu par le service, ajouter tous les utilisateurs du service
    if (!empty($envoiVersService)) {
        $this->ajouterTousUtilisateursService($dossier_id, $employe->service_id, $employe_id, $etat);
    } else {
        // Sinon, comportement normal pour les dossiers internes
        $etat_actuel = $dossierModel->query("
            SELECT etat FROM etatdossierutilisateur 
            WHERE dossier_id = :dossier_id AND employe_id = :employe_id
        ", ['dossier_id' => $dossier_id, 'employe_id' => $employe_id]);
        
        // Ne changer l'état que s'il n'est pas déjà CLOTURE
        if (empty($etat_actuel) || $etat_actuel[0]->etat !== 'CLOTURE') {
            $dossierModel->mettreAJourEtat($dossier_id, $employe_id, $etat);
        }
    }
}

/**
 * Ajouter tous les utilisateurs d'un service dans etatdossierutilisateur
 */
private function ajouterTousUtilisateursService($dossier_id, $service_id, $utilisateur_ouvrant_id, $etat)
{
    $dossierModel = new Dossiers();
    
    // Vérifier si des utilisateurs du service sont déjà dans etatdossierutilisateur
    $utilisateursExistants = $dossierModel->query("
        SELECT COUNT(*) as count 
        FROM etatdossierutilisateur edu
        JOIN employes e ON edu.employe_id = e.id
        WHERE edu.dossier_id = :dossier_id 
        AND e.service_id = :service_id
    ", [
        'dossier_id' => $dossier_id,
        'service_id' => $service_id
    ]);
    
    // Si aucun utilisateur du service n'est encore dans la table, les ajouter tous
    if ($utilisateursExistants && $utilisateursExistants[0]->count == 0) {
        $query = "
            INSERT INTO etatdossierutilisateur (dossier_id, employe_id, etat)
            SELECT :dossier_id, e.id, 'NON_OUVERT'
            FROM employes e
            WHERE e.service_id = :service_id 
            AND e.est_actif = TRUE
        ";
        
        $result = $dossierModel->query($query, [
            'dossier_id' => $dossier_id,
            'service_id' => $service_id
        ]);
        
        if ($result) {
            error_log("Tous les utilisateurs du service $service_id ajoutés dans etatdossierutilisateur pour le dossier $dossier_id");
        }
    }
    
    // Maintenant mettre à jour l'état de l'utilisateur qui ouvre le dossier
    $etat_actuel = $dossierModel->query("
        SELECT etat FROM etatdossierutilisateur 
        WHERE dossier_id = :dossier_id AND employe_id = :employe_id
    ", ['dossier_id' => $dossier_id, 'employe_id' => $utilisateur_ouvrant_id]);
    
    // Ne changer l'état que s'il n'est pas déjà CLOTURE
    if (empty($etat_actuel) || $etat_actuel[0]->etat !== 'CLOTURE') {
        $dossierModel->mettreAJourEtat($dossier_id, $utilisateur_ouvrant_id, $etat);
    }
}

/**
 * Marquer les notifications d'envoi de dossier comme lues
 */
private function marquerNotificationsEnvoiCommeLues($dossier_id, $employe_id)
{
    $notificationModel = new \Model\Notification();
    
    $query = "UPDATE Notifications 
              SET is_read = TRUE, date_lecture = NOW()
              WHERE dossier_id = :dossier_id 
              AND recipient_id = :employe_id 
              AND message LIKE '%vous a envoyé le dossier%'
              AND is_read = FALSE";
    
    $result = $notificationModel->query($query, [
        'dossier_id' => $dossier_id,
        'employe_id' => $employe_id
    ]);
    
    if ($result) {
        error_log("Notifications d'envoi marquées comme lues pour l'employé $employe_id et le dossier $dossier_id");
    }
    
    return $result;
}



// last last
/**
 * Helper pour enregistrer les actions
 */
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

/**
 * Mettre à jour la dernière connexion
 */
/**
 * Mettre à jour la dernière connexion - Version avec requête directe
 */
protected function updateLastLogin($employe_id)
{
    $employeModel = new \Model\Employes();
    
    // CORRECTION : Utiliser une requête directe
    $employeModel->query(
        "UPDATE employes SET derniere_connexion = :derniere_connexion WHERE id = :id",
        [
            'derniere_connexion' => date('Y-m-d H:i:s'),
            'id' => $employe_id
        ]
    );
    
    // Enregistrer dans l'historique
    $this->enregistrerAction('LOGIN', 'Connexion au système');
}



/**
 * Visualiser un document dans le navigateur (méthode générique)
 */
public function visualiser($document_id = null)
{
    $ses = new Session();
    $document = new Documents();
    $dossier = new Dossiers();
    
    if (!$ses->is_logged_in() || !$document_id) {
        redirect('login');
    }
    
    // Récupérer le document
    $doc = $document->first(['id' => $document_id]);
    if (!$doc) {
        message("Document non trouvé", 'error');
        redirect('document');
    }
    
    // Récupérer le dossier
    $dossier_data = $dossier->first(['id' => $doc->dossier_id]);
    if (!$dossier_data) {
        message("Dossier non trouvé", 'error');
        redirect('document');
    }
    
    // Vérifier l'accès
    $employe = $this->getEmployeInfo($ses);
    if (!$employe) {
        message("Profil employé non trouvé", 'error');
        redirect('document');
    }
    
    $accesAutorise = $this->verifierAccesDossier($doc->dossier_id, $employe->id, $employe->service_id, $employe->role_service);
    if (!$accesAutorise) {
        message("Accès non autorisé à ce document", 'error');
        redirect('document');
    }
    
    // Chemin complet du fichier
    $file_path = ROOTPATH . '/' . $dossier_data->chemin . $doc->nom_stockage;
    
    // Vérifier que le fichier existe
    if (!file_exists($file_path)) {
        message("Fichier non trouvé sur le serveur", 'error');
        redirect('document?dossier_id=' . $doc->dossier_id);
    }
    
    // Enregistrer l'action de visualisation
    $this->enregistrerAction(
        'VISUALISATION_DOCUMENT',
        "Visualisation du document \"{$doc->nom}\"",
        $doc->id,
        $doc->dossier_id
    );
    
    // Déterminer le type MIME
    $mime_type = $this->getMimeTypeForDisplay($file_path, $doc->nom);
    
    // Headers pour l'affichage dans le navigateur
    header('Content-Type: ' . $mime_type);
    header('Content-Disposition: inline; filename="' . basename($doc->nom) . '"');
    header('Content-Length: ' . filesize($file_path));
    header('Cache-Control: public, must-revalidate, max-age=0');
    header('Pragma: public');
    header('Expires: 0');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($file_path)) . ' GMT');
    
    // Désactiver la compression pour certains types
    if (in_array($mime_type, ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'])) {
        header('Content-Encoding: identity');
    }
    
    // Lire et output le fichier
    readfile($file_path);
    exit;
}

/**
 * Obtenir le type MIME pour l'affichage
 */
private function getMimeTypeForDisplay($file_path, $filename)
{
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    $mime_types = [
        'pdf'  => 'application/pdf',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
        'bmp'  => 'image/bmp',
        'webp' => 'image/webp',
        'txt'  => 'text/plain; charset=utf-8',
        'csv'  => 'text/csv; charset=utf-8',
        'html' => 'text/html; charset=utf-8',
        'htm'  => 'text/html; charset=utf-8',
        'xml'  => 'text/xml; charset=utf-8',
        'doc'  => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls'  => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'ppt'  => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    ];
    
    if (isset($mime_types[$extension])) {
        return $mime_types[$extension];
    }
    
    // Détection automatique
    if (function_exists('mime_content_type')) {
        return mime_content_type($file_path);
    }
    
    // Fallback
    return 'application/octet-stream';
}



/**
 * Servir le fichier pour la visualisation dans une nouvelle fenêtre
 */
public function servir_fichier($document_id = null)
{
    $ses = new Session();
    $document = new Documents();
    $dossier = new Dossiers();
    
    if (!$ses->is_logged_in() || !$document_id) {
        http_response_code(403);
        exit('Accès non autorisé');
    }
    
    // Récupérer le document
    $doc = $document->first(['id' => $document_id]);
    if (!$doc) {
        http_response_code(404);
        exit('Document non trouvé');
    }
    
    // Récupérer le dossier
    $dossier_data = $dossier->first(['id' => $doc->dossier_id]);
    if (!$dossier_data) {
        http_response_code(404);
        exit('Dossier non trouvé');
    }
    
    // Vérifier l'accès
    $employe = $this->getEmployeInfo($ses);
    if (!$employe) {
        http_response_code(403);
        exit('Profil employé non trouvé');
    }
    
    $accesAutorise = $this->verifierAccesDossier($doc->dossier_id, $employe->id, $employe->service_id, $employe->role_service);
    if (!$accesAutorise) {
        http_response_code(403);
        exit('Accès non autorisé à ce document');
    }
    
    // Chemin complet du fichier
    $file_path = ROOTPATH . '/' . $dossier_data->chemin . $doc->nom_stockage;
    
    if (!file_exists($file_path)) {
        http_response_code(404);
        exit('Fichier non trouvé sur le serveur');
    }
    
    // Servir le fichier
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $doc->nom . '"');
    header('Content-Length: ' . filesize($file_path));
    readfile($file_path);
    exit;
}




















/**
 * Afficher une page de prévisualisation intelligente
 */
public function preview($document_id = null)
{
    $ses = new Session();
    $document = new Documents();
    $dossier = new Dossiers();
    
    if (!$ses->is_logged_in() || !$document_id) {
        redirect('login');
    }
    
    // Récupérer le document
    $doc = $document->first(['id' => $document_id]);
    if (!$doc) {
        message("Document non trouvé", 'error');
        redirect('document');
    }
    
    // Récupérer le dossier
    $dossier_data = $dossier->first(['id' => $doc->dossier_id]);
    if (!$dossier_data) {
        message("Dossier non trouvé", 'error');
        redirect('document');
    }
    
    // Vérifier l'accès
    $employe = $this->getEmployeInfo($ses);
    if (!$employe) {
        message("Profil employé non trouvé", 'error');
        redirect('document');
    }
    
    $accesAutorise = $this->verifierAccesDossier($doc->dossier_id, $employe->id, $employe->service_id, $employe->role_service);
    if (!$accesAutorise) {
        message("Accès non autorisé à ce document", 'error');
        redirect('document');
    }
    
    // Déterminer le type de fichier et la méthode d'affichage
    $file_info = $this->getFileViewingMethod($doc, $dossier_data);
    
    $data = [
        'document' => $doc,
        'dossier' => $dossier_data,
        'file_info' => $file_info
    ];
    
    $this->view('document_preview', $data);
}

/**
 * Déterminer comment afficher le fichier
 */
private function getFileViewingMethod($document, $dossier)
{
    $extension = strtolower(pathinfo($document->nom, PATHINFO_EXTENSION));
    $file_path = ROOTPATH . '/' . $dossier->chemin . $document->nom_stockage;
    $file_url = ROOT . '/' . $dossier->chemin . $document->nom_stockage;
    
    $info = [
        'extension' => $extension,
        'can_display_natively' => false,
        'view_method' => 'download',
        'view_url' => ROOT . '/document/telecharger/' . $document->id,
        'google_viewer_url' => null,
        'mime_type' => 'application/octet-stream'
    ];
    
    // Fichiers affichables nativement
    $native_types = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'txt', 'csv', 'html', 'htm'];
    if (in_array($extension, $native_types)) {
        $info['can_display_natively'] = true;
        $info['view_method'] = 'native';
        $info['view_url'] = ROOT . '/document/visualiser/' . $document->id;
    }
    
    // Fichiers Office - utiliser Google Docs Viewer
    $office_types = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
    if (in_array($extension, $office_types)) {
        $info['view_method'] = 'google_viewer';
        $encoded_url = urlencode($file_url);
        $info['google_viewer_url'] = "https://docs.google.com/gview?url={$encoded_url}&embedded=true";
        $info['view_url'] = $info['google_viewer_url'];
    }
    
    // Déterminer le MIME type
    $mime_types = [
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    ];
    
    if (isset($mime_types[$extension])) {
        $info['mime_type'] = $mime_types[$extension];
    }
    
    return $info;
}

/**
 * Déterminer le type d'accès pour un dossier
 */
private function determinerTypeAcces($dossier, $service_id, $role_service)
{
    // Dossier interne
    if ($dossier->origine == 'INTERNE') {
        return 'interne';
    }
    
    // Dossier externe reçu par service
    if ($dossier->origine == 'EXTERNE' && $dossier->type_envoi == 'SERVICE') {
        return 'envoye_service';
    }
    
    // Dossier externe reçu par rôle
    if ($dossier->origine == 'EXTERNE' && $dossier->type_envoi == 'ROLE_SERVICE') {
        return 'envoye_role';
    }
    
    return 'autre';
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