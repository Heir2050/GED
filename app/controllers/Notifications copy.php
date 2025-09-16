<?php
namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');

use \Core\Session;
use \Core\Request;
use Model\User;
use \Model\Notification;

class Notifications 
{
    use MainController;

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

        $notif = new Notification();
        $user_id = $ses->user('id');
        
        // Marquer toutes les notifications comme lues lorsqu'on visite la page
        $notif->markAllAsRead($user_id);
        
        $data['notifications'] = $notif->getUserNotifications($user_id);
        $data['rows'] = $users->findAll();
        $data['action'] = "";

        $this->view('notifications', $data);
    }

    public function count()
    {
        $ses = new Session();
        $notif = new Notification();

        if ($ses->is_logged_in()) {
            $user_id = $ses->user('id');
            echo $notif->getUnreadCount($user_id);
        } else {
            echo "0";
        }
    }

    public function mark_read($id)
    {
        $ses = new Session();
        $notif = new Notification();

        if ($ses->is_logged_in()) {
            $user_id = $ses->user('id');
            $notif->markAsRead($id, $user_id);
            echo "ok";
        }
    }

    public function mark_all_read()
    {
        $ses = new Session();
        $notif = new Notification();

        if ($ses->is_logged_in()) {
            $user_id = $ses->user('id');
            $notif->markAllAsRead($user_id);
            echo "ok";
        }
    }
}