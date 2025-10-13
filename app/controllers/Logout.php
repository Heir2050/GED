<?php

namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');

use \Core\Session; //Importing namespace

/**
 * Logout class
 */
class Logout
{
    use MainController; // this importing another class

    public function index()
    {

        $ses = new Session();

        // Enregistrer la déconnexion
        if ($ses->is_logged_in()) {
            $this->enregistrerAction('LOGOUT', 'Déconnexion du système');
        }

        $ses->logout();


        redirect('login');
    }


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
}
