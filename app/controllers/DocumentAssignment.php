<?php
namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');

use Core\Session;
use \Core\Request;
use Model\User;
use Model\Uploads;
use Model\DocumentAssignments;

class DocumentAssignment
{
    use MainController;

    public function index()
    {
        $ses = new Session();
        $req = new Request();
        $assignmentModel = new \Model\DocumentAssignments();
        $uploadsModel = new \Model\Uploads();
        $userModel = new \Model\User();
        $attachmentModel = new \Model\Detailss(); // Pour accéder à document_attachments
        $data = [];

        if (!$ses->is_logged_in()) {
            message('Veuillez vous connecter');
            redirect('login');
        }

        // Récupérer l'ID de l'utilisateur connecté (celui qui a fait les assignations)
        $current_user_id = $ses->user('id');

        // Récupérer les assignations faites par l'utilisateur connecté
        $assignments = $assignmentModel->where(['assigned_by' => $current_user_id]);
        
        // Récupérer les détails complets et le statut de réponse
        $data['assignments'] = [];
        foreach ($assignments as $assignment) {
            $document = $uploadsModel->first(['id' => $assignment->document_id]);
            $assigned_to_user = $userModel->first(['id' => $assignment->assigned_to]);
            $hasResponse = false;
            $responseType = null;

            if ($document && $assigned_to_user) {
                // Vérifier s'il y a une réponse pour ce document et cet assigné
                $sql = "SELECT action FROM document_attachments 
                        WHERE document_id = ? AND uploaded_by = ? 
                        ORDER BY created_at DESC LIMIT 1";
                $response = $attachmentModel->query($sql, [$document->id, $assignment->assigned_to]);
                if ($response && isset($response[0]->action)) {
                    $hasResponse = true;
                    $responseType = $response[0]->action; // 'approuve' ou 'refuse'
                }

                $data['assignments'][] = [
                    'assignment' => $assignment,
                    'document' => $document,
                    'assigned_to_user' => $assigned_to_user,
                    'hasResponse' => $hasResponse,
                    'responseType' => $responseType
                ];
            }
        }

        $this->view('documentassignments', $data);
    }

    // Fonction d'assignation
    public function assign($document_id)
    {
        $ses = new Session();
        $req = new Request();
        $assignmentModel = new \Model\DocumentAssignments();

        if (!$ses->is_logged_in()) {
            message('Veuillez vous connecter');
            redirect('login');
        }

        if ($req->posted()) {
            $assigned_to = $_POST['assigned_to'] ?? null;
            $assigned_by = $ses->user('id');
            if ($assigned_to) {
                // Vérifier si déjà assigné
                $exists = $assignmentModel->first([
                    'document_id' => $document_id,
                    'assigned_to' => $assigned_to
                ]);
                if (!$exists) {
                    $assignmentModel->insert([
                        'document_id' => $document_id,
                        'assigned_to' => $assigned_to,
                        'assigned_by' => $assigned_by,
                        'status' => 'non_lu'
                    ]);
                    message('Document assigné avec succès.');
                } else {
                    message('Déjà assigné le document à cet utilisateur.');
                }
            }
        }
        redirect('home');
    }
}