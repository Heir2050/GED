<?php

namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');

use \Core\Session; //Importing namespace
use \Core\Request;
use Model\User;
use \Model\Image;
use \Model\Notification;

class Notifications 
{
    use MainController; // this importing another class

    public function index()
    {
        $users = new User();
        $req = new Request();
        $ses = new Session();

        # After doing anything, we unsure that the user is loged in.
        if (!$ses->is_logged_in()) {
            message('Please login as admin');
            redirect('login');
        }


        $ses = new Session();
        $notif = new Notification();

        $user_id = $ses->user('id');
        $data['notifications'] = $notif->getUserNotifications($user_id);



        $data['rows'] = $users->findAll();
        $data['action'] = "";

        $this->view('notifications', $data);
    }

    public function count()
    {
        $ses = new Session();
        $notif = new Notification();

        $user_id = $ses->user('id');
        echo $notif->getUnreadCount($user_id);
    }

    public function mark_read($id)
    {
        $ses = new Session();
        $notif = new Notification();

        $user_id = $ses->user('id');
        $notif->markAsRead($id, $user_id);
        echo "ok";
    }

    // public function createAssignmentNotification($document_id, $assigned_by, $assigned_to)
    // {

    //     $notif = new Notification();
    //     $userModel = new \Model\User();
    //     $assigner = $userModel->first(['id' => $assigned_by]);
        
    //     $message = "Un document vous a été assigné";
    //     if ($assigner) {
    //         $message .= " par " . $assigner->prenom . " " . $assigner->nom;
    //     }

    //     $notification = [
    //         'recipient_user_id' => $assigned_to,
    //         'actor_user_id' => $assigned_by,
    //         'document_id' => $document_id,
    //         'action' => 'document_assigned',
    //         'message' => $message,
    //         'is_read' => 0,
    //         'created_at' => date('Y-m-d H:i:s')
    //     ];

    //     return $notif->insert($notification);
    // }
}