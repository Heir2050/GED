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

        // if (!$ses->is_logged_in()) {
        //     redirect('login');
        // }

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
        $data['dossiers'] = $dossier->getDossiersVisibles(
            $employe->id, 
            $employe->service_id, 
            $employe->role_service
        );

        // Récupérer seulement les dossiers non archivés
        // $data['dossiers'] = $dossier->query("
        //     SELECT d.*, COUNT(doc.id) as nb_documents 
        //     FROM dossiers d 
        //     LEFT JOIN documents doc ON d.id = doc.dossier_id 
        //     WHERE d.est_archive = false
        //     " . ($service_id ? " AND d.service_id = :service_id" : "") . "
        //     GROUP BY d.id
        //     ORDER BY d.nom
        // ", $service_id ? ['service_id' => $service_id] : []);

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
                error_log("Résultat mise à jour: " . ($result ? 'SUCCÈS' : 'ÉCHEC'));
                
                // Vérifier l'état après mise à jour
                $etat_apres = $dossier->query("
                    SELECT etat FROM etatdossierutilisateur 
                    WHERE dossier_id = :dossier_id AND employe_id = :employe_id
                ", ['dossier_id' => $dossier_id, 'employe_id' => $employe_id]);
                
                error_log("État après: " . print_r($etat_apres, true));
                
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
                $dossier->archiverDossier($dossier_id);
                message("Dossier archivé avec succès");
                redirect('document');
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
public function envoyer($dossier_id = null)
{
    $ses = new Session();
    $req = new Request();
    $dossier = new Dossiers();
    $envoiModel = new \Model\EnvoiDossiers();
    $serviceModel = new \Model\Services();

    if (!$ses->is_logged_in()) {
        redirect('login');
    }

    $employe = $this->getEmployeInfo($ses);
    if (!$employe) {
        message("Profil employé non trouvé", 'error');
        redirect('document');
    }

    if ($req->posted() && $dossier_id) {
        $type_envoi = $req->post('type_envoi');
        $service_dest = $req->post('service_dest');
        $role_dest = $req->post('role_dest');

        // Validation
        if ($type_envoi == 'SERVICE' && empty($service_dest)) {
            message("Veuillez sélectionner un service", 'error');
            redirect('document/envoyer/' . $dossier_id);
        }

        if ($type_envoi == 'ROLE_SERVICE' && empty($role_dest)) {
            message("Veuillez sélectionner un rôle", 'error');
            redirect('document/envoyer/' . $dossier_id);
        }

        // Envoyer le dossier
        $result = $envoiModel->envoyerDossier(
            $dossier_id,
            $type_envoi,
            $service_dest,
            $role_dest,
            $employe->id
        );

        if ($result) {
            // Marquer le dossier comme envoyé
            $dossier->marquerCommeEnvoye($dossier_id);
            
            message("Dossier envoyé avec succès");
        } else {
            message("Ce dossier a déjà été envoyé à cette destination", 'error');
        }

        redirect('document?dossier_id=' . $dossier_id);
    }

    $data['dossier'] = $dossier->first(['id' => $dossier_id]);
    $data['services'] = $serviceModel->findAll();
    $data['roles'] = ['EMPLOYE', 'CHEF_SERVICE', 'ADMIN_SERVICE'];

    $this->view('envoyer_dossier', $data);
}

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
            message("Envoi retiré avec succès");
        } else {
            message("Impossible de retirer l'envoi : dossier archivé", 'error');
        }
    }

    redirect('document');
}

/**
 * Vérifier l'accès à un dossier - CORRIGÉ
 */
/**
 * Vérifier l'accès à un dossier - Version simplifiée
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
    
    // Vérifier les envois avec une requête directe qui gère correctement les NULL
    $query = "
        SELECT COUNT(*) as count 
        FROM envoidossiers ed
        JOIN dossiers d ON ed.dossier_id = d.id
        WHERE ed.dossier_id = :dossier_id
        AND ed.est_actif = true
        AND d.est_archive = false
        AND ed.service_id = :service_id
        AND (
            (ed.type_envoi = 'SERVICE' AND ed.role_service IS NULL)
            OR 
            (ed.type_envoi = 'ROLE_SERVICE' AND ed.role_service = :role_service)
        )
    ";
    
    $result = $envoiModel->query($query, [
        'dossier_id' => $dossier_id,
        'service_id' => $service_id,
        'role_service' => $role_service
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

/**
 * Marquer la notification comme ouverte quand on consulte un dossier reçu
 */













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