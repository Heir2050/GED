<?php
namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');

use \Core\Session;
use \Core\Request;
use Model\User;
use \Model\Notification;
use Model\Employes;

class Notifications 
{
    use MainController;

    public function index()
    {
        $users = new User();
        $req = new Request();
        $ses = new Session();

        if (!$ses->is_logged_in()) {
            message('Please login');
            redirect('login');
        }

        $notif = new Notification();
        $employeModel = new Employes();
        
        // Trouver l'employé correspondant à l'utilisateur
        $user = $users->first(['id' => $ses->user('id')]);
        $employe = $employeModel->first(['email' => $user->email]);
        
        if (!$employe) {
            message('Profil employé non trouvé');
            redirect('documents');
        }
        
        // Marquer toutes les notifications comme lues
        $notif->markAllAsRead($employe->id);
        
        $data['notifications'] = $notif->getUserNotifications($employe->id);
        $data['rows'] = $users->findAll();
        $data['action'] = "";

        $this->view('notifications', $data);
    }

    public function count()
    {
        $users = new User();
        $ses = new Session();
        $notif = new Notification();
        $employeModel = new Employes();

        if ($ses->is_logged_in()) {
            $user = $users->first(['id' => $ses->user('id')]);
            $employe = $employeModel->first(['email' => $user->email]);
            
            if ($employe) {
                echo $notif->getUnreadCount($employe->id);
                return;
            }
        }
        
        echo "0";
    }

    public function mark_read($id)
    {
        $users = new User();
        $ses = new Session();
        $notif = new Notification();
        $employeModel = new Employes();

        if ($ses->is_logged_in()) {
            $user = $users->first(['id' => $ses->user('id')]);
            $employe = $employeModel->first(['email' => $user->email]);
            
            if ($employe) {
                $notif->markAsRead($id, $employe->id);
                echo "ok";
                return;
            }
        }
        
        echo "error";
    }

    public function mark_all_read()
    {
        $users = new User();
        $ses = new Session();
        $notif = new Notification();
        $employeModel = new Employes();

        if ($ses->is_logged_in()) {
            $user = $users->first(['id' => $ses->user('id')]);
            $employe = $employeModel->first(['email' => $user->email]);
            
            if ($employe) {
                $notif->markAllAsRead($employe->id);
                echo "ok";
                return;
            }
        }
        
        echo "error";
    }
}