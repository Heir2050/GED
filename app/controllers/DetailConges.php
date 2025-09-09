<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

use \Model\User; //Importing namespace
use \Core\Session;
use \Core\Model;
use \Model\DetailConge;
// use \Model\DetailConge; // Importing the Upload model with an alias
use \Model\Folders;
use \Model\Notification;

/**
 * home class
 */
class DetailConges
{
    // use \Core\Database;

	use MainController; // this importing another class
	

	public function index($id)
	{
		$ses = new Session();
		$detailsConge = new DetailConge(); // Importing the Upload model with an alias
		$folders = new Folders();

        $attachments = new \Model\CongeAttachment(); // Nouveau modèle pour la nouvelle table


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
        	$data['rows'] = $detailsConge->findAll();
		} else {
			// Afficher les fichiers creer par l'utilisateur lui meme
			$data['rows'] = $detailsConge->where(['user_id' => $ses->user('id')]);
		}

        $data['detailsconge'] = $detailsConge->first(['id' => $id]);

        // Récupère les personnes assignées à ce fichier
        // $sql = "SELECT u.id, u.nom, u.prenom, u.role, u.email, da.status, da.assigned_at
        //     FROM conge_attachments da
        //     JOIN users u ON da.assigned_to = u.id
        //     WHERE da.conge_id = ?";
        // $data['assigned_users'] = $users_assigned->query($sql, [$id]);

        // Récupération des pièces jointes existantes
        // $data['attachments'] = $attachments->where(['conge_id' => $id]);

        // Verification si la reponse au fichiers existe deja
        // Vérifie si une réponse existe déjà
        $data['has_response'] = false;
        $data['existing_response'] = null;
        
        $existing = $attachments->first(['conge_id' => $id]);
        if ($existing) {
            $data['has_response'] = true;
            $data['existing_response'] = $existing;
            
            // Récupère les infos de l'utilisateur qui a répondu
            $userModel = new User();
            $data['responder'] = $userModel->first(['id' => $existing->user_id]);
        }

        $ses = new Session();
        $notif = new Notification();

        $user_id = $ses->user('id');
        $data['notifications'] = $notif->getUserNotifications($user_id);


		$data['detailsConge'] = $detailsConge;

		$this->view('detailconge', $data);
	}
    

    public function handleAction($id)
    {
        $ses = new Session();
        $detailsConge = new DetailConge();
        $attachments = new \Model\CongeAttachment(); // Nouveau modèle pour la nouvelle table
        $notifications = new Notification();

        if (!$ses->is_logged_in()) {
            message('Veuillez vous connecter');
            redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $comment = $_POST['comment'] ?? '';

            if (!in_array($action, ['approuve', 'refuse'])) {
                message('Action invalide', 'error');
                redirect('details/' . $id);
            }

            // Gestion du fichier uploadé
            if (isset($_FILES['attachment_file']) && $_FILES['attachment_file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/conges/attachments/';
                if (!is_dir(ROOTPATH . '/' . $uploadDir)) {
                    mkdir(ROOTPATH . '/' . $uploadDir, 0755, true);
                }

                $extension = pathinfo($_FILES['attachment_file']['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extension;
                $filePath = $uploadDir . $filename;

                if (move_uploaded_file($_FILES['attachment_file']['tmp_name'], ROOTPATH . '/' . $filePath)) {
                    // Enregistrement de la pièce jointe dans conge_attachments
                    $attachmentData = [
                        'conge_id' => $id,
                        'file_path' => $filePath,
                        'user_id' => $ses->user('id'),
                        'action' => $action,
                        'comment' => $comment
                    ];

                    if ($attachments->validate($attachmentData)) {
                        $attachments->insert($attachmentData);

                        // Mise à jour du statut de la demande de congé
                        $statut = $action === 'approuve' ? 'approuve' : 'refuse';
                        $detailsConge->query("UPDATE demandes_conges SET `statut` = ? WHERE id = ?", [$statut, $id]);

                        // Récupérer l'auteur original de la demande
                        $conge = $detailsConge->first(['id' => $id]);
                        $uploaderId = $conge->user_id;

                        // Créer la notification
                        $notificationData = [
                            'recipient_user_id' => $uploaderId,
                            'actor_user_id' => $ses->user('id'),
                            'conge_id' => $id,
                            'action' => $action === 'approuve' ? 'conge_approuve' : 'conge_refuse',
                            'message' => $action === 'approuve'
                                ? 'Votre demande de congé a été approuvée'
                                : 'Votre demande de congé a été refusée',
                            'is_read' => false
                        ];

                        $notifications->insert($notificationData);

                        message('Action effectuée avec succès');
                    } else {
                        @unlink(ROOTPATH . '/' . $filePath);
                        message(implode('<br>', $attachments->errors), 'error');
                    }
                } else {
                    message('Erreur lors de l\'upload du fichier', 'error');
                }
            } else {
                message('Veuillez joindre un fichier justificatif', 'error');
            }

            redirect('detailconges/' . $id);
        }
    }
}
