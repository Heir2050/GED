<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

use \Model\User; //Importing namespace
use \Core\Session;
use \Core\Model;
use \Model\Detailss;
use \Model\Uploads; // Importing the Upload model with an alias
use \Model\Folders;
use \Model\Notification;

/**
 * home class
 */
class Details
{
    // use \Core\Database;

	use MainController; // this importing another class
	

	public function index($id)
	{
		$ses = new Session();
		$uploads = new Uploads(); // Importing the Upload model with an alias
		$folders = new Folders();
        
        $attachments = $users_assigned = new Detailss();


		// Grab all users
		$users = (new User())->findAll();
		$data['users'] = $users;

		if (!$ses->is_logged_in()) {
            message('Please login');
            redirect('login');
        }
		
		// Changer ID
        $folders->setOrder_column('folder_id');
		$folders->setLimit(10); // Mets une limite plus grande si besoin
		$folders->offset = 0;
		$allFolders = $folders->findAll();
		$data['folders'] = array_filter($allFolders, function($f) {
			return $f->parent_id == 0 || is_null($f->parent_id);
		});
		$data['folders'] = array_slice($data['folders'], 0, 4); // Limite à 4

		if ($ses->user('role') == 'admin') {
        	$data['rows'] = $uploads->findAll();
		} else {
			// Afficher les fichiers creer par l'utilisateur lui meme
			$data['rows'] = $uploads->where(['uploaded_by' => $ses->user('id')]);
		}

        $data['allrow'] = $uploads->first(['id' => $id]);

        // Details views
        // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //     $action = $_POST['action'] ?? '';
        //     $status_id = null;
        //     if ($action == 'approuve') $status_id = 2;
        //     elseif ($action == 'refuse') $status_id = 3;
        //     elseif ($action == 'en_attente') $status_id = 1;

        //     if ($status_id) {
        //         $uploads->update($id, ['status_id' => $status_id], 'id');
        //         message("Statut mis à jour !");
        //     }
        //     redirect('details/' . $id);
        // }

        // Récupère les personnes assignées à ce fichier
        $sql = "SELECT u.id, u.nom, u.prenom, u.role, u.email, da.status, da.assigned_at
            FROM document_assignments da
            JOIN users u ON da.assigned_to = u.id
            WHERE da.document_id = ?";
        $data['assigned_users'] = $users_assigned->query($sql, [$id]);

        // Ajoute cette partie pour récupérer l'utilisateur connecté
        // $user_id = $ses->user('id');
        // $userModel = new User();
        // $user = $userModel->first(['id' => $user_id]);
        // $data['user'] = $user;

        // Récupération des pièces jointes existantes
        $data['attachments'] = $attachments->where(['document_id' => $id]);

        // Verification si la reponse au fichiers existe deja
        // Vérifie si une réponse existe déjà
        $data['has_response'] = false;
        $data['existing_response'] = null;
        
        $existing = $attachments->first(['document_id' => $id]);
        if ($existing) {
            $data['has_response'] = true;
            $data['existing_response'] = $existing;
            
            // Récupère les infos de l'utilisateur qui a répondu
            $userModel = new User();
            $data['responder'] = $userModel->first(['id' => $existing->uploaded_by]);
        }

        $ses = new Session();
        $notif = new Notification();

        $user_id = $ses->user('id');
        $data['notifications'] = $notif->getUserNotifications($user_id);


		$data['uploads'] = $uploads;

		$this->view('details', $data);
	}


    public function unassign()
    {
        $users_assigned = new Detailss();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $document_id = $_POST['document_id'] ?? null;
            $user_id = $_POST['user_id'] ?? null;

            if ($document_id && $user_id) {
                // $db = new \Core\Database();
                $users_assigned->query(
                    "DELETE FROM document_assignments WHERE document_id = ? AND assigned_to = ?",
                    [$document_id, $user_id]
                );
                message("Utilisateur retiré avec succès !");
            } else {
                message("Paramètres invalides.");
            }
            redirect('details/' . $document_id);
        }
    }
    

    public function handleAction($id)
    {
        $ses = new Session();
        $uploads = new Uploads();
        $attachments = new Detailss();
        $notifications = new Notification(); // Assurez-vous que ce modèle existe

        if (!$ses->is_logged_in()) {
            message('Veuillez vous connecter');
            redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $comment = $_POST['comment'] ?? '';
            
            // Vérifie que l'action est valide
            if (!in_array($action, ['approuve', 'refuse'])) {
                message('Action invalide', 'error');
                redirect('details/' . $id);
            }

            // Gestion du fichier uploadé
            if (isset($_FILES['attachment_file']) && $_FILES['attachment_file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/attachments/';
                if (!is_dir(ROOTPATH . '/' . $uploadDir)) {
                    mkdir(ROOTPATH . '/' . $uploadDir, 0755, true);
                }
                
                $extension = pathinfo($_FILES['attachment_file']['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extension;
                $filePath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['attachment_file']['tmp_name'], ROOTPATH . '/' . $filePath)) {
                    // Enregistrement de la pièce jointe
                    $attachmentData = [
                        'document_id' => $id,
                        'file_path' => $filePath,
                        'uploaded_by' => $ses->user('id'),
                        'action' => $action,
                        'comment' => $comment
                    ];
                    
                    if ($attachments->validate($attachmentData)) {
                        $attachments->insert($attachmentData);
                        
                        // Mise à jour du statut du document
                        $status = $action === 'approuve' ? 'approuve' : 'refuse'; 
                        $attachments->query("UPDATE documents SET `status` = ? WHERE id = ?", [$status, $id]);
                        
                        // Récupérer l'auteur original du document
                        $document = $uploads->first(['id' => $id]);
                        $uploaderId = $document->uploaded_by;
                        
                        // Créer la notification
                        $notificationData = [
                            'recipient_user_id' => $uploaderId,
                            'actor_user_id' => $ses->user('id'),
                            'document_id' => $id,
                            'action' => $action === 'approuve' ? 'document_approved' : 'document_rejected',
                            'message' => $action === 'approuve' 
                                ? 'Votre document a été approuvé' 
                                : 'Votre document a été refusé',
                            'is_read' => false
                        ];
                        
                        $notifications->insert($notificationData);
                        
                        message('Action effectuée avec succès');
                    } else {
                        @unlink(ROOTPATH . '/' . $filePath); // Supprime le fichier en cas d'erreur
                        message(implode('<br>', $attachments->errors), 'error');
                    }
                } else {
                    message('Erreur lors de l\'upload du fichier', 'error');
                }
            } else {
                message('Veuillez joindre un fichier justificatif', 'error');
            }
            
            redirect('details/' . $id);
        }
    }
    
    // Dans votre méthode de contrôleur (avant tout affichage)
    // public function pdf($relativePath)
    // {
    //     $relativePath = rawurldecode($relativePath);
    //     $file = ROOTPATH . '/' . $relativePath;
    //     if (file_exists($file)) {
    //         header('Content-Type: application/pdf');
    //         header('Content-Disposition: inline; filename="' . basename($file) . '"');
    //         header('Content-Length: ' . filesize($file));
    //         readfile($file);
    //         exit;
    //     } else {
    //         http_response_code(404);
    //         echo "Fichier introuvable.";
    //     }
    // }

}
