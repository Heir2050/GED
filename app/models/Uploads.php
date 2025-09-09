<?php

    namespace Model;

    defined('ROOTPATH') or exit('Access Denied!');

    class Uploads
    {

        use Model;
        
        protected $table = 'documents';

        protected $allowedColumns = [
            'type',
            'file', // This is the file name, not the path
            'title',
            'objet',
            'description',
            'file_path',

            'folder_id',
            'uploaded_by',
            'status_id',

            'numero_interne',
            'numero_reception',
            'date_envoye',
            'date_recu',
            'date_signature',
            'numero_reference',
            'envoyeur',

            'numero_expedie',
            'numero_expedition',
            'date_expedition',
            'institution_destinataire',

            'created_at',
        ];

        public function validate($files, $post, $id = null)
        {
            $this->errors = [];

            // Validation du titre
            if (empty($post['title'])) {
                $this->errors['title'] = "Le titre est obligatoire";
            } elseif (strlen($post['title']) > 100) {
                $this->errors['title'] = "Le titre ne doit pas dépasser 100 caractères";
            }

            // Validation de l'objet
            if (empty($post['objet'])) {
                $this->errors['objet'] = "L'objet du lettre est obligatoire";
            }

            // Validation de l'objet
            if (empty($post['type'])) {
                $this->errors['type'] = "Veuillez sélectionner le type de lettre";
            }

            // Validation de la description
            if (empty($post['description'])) {
                $this->errors['description'] = "La description est obligatoire";
            } elseif (strlen($post['description']) > 1000) {
                $this->errors['description'] = "La description ne doit pas dépasser 1000 caractères";
            }

            // Validation du fichier (seulement pour l'ajout ou si un nouveau fichier est uploadé)
            // if ((!$id || !empty($files['file']['name']))) {
            //     if (!empty($files['file']['name'])) {
            //         // Vérification de la taille
            //         if ($files['file']['size'] > 10 * 1024 * 1024) {
            //             $this->errors['file'] = "Le fichier ne doit pas dépasser 10MB";
            //         }

            //         // Vérification du type
            //         $validTypes = [
            //             'image/jpeg', 'image/png', 'image/gif',
            //             'application/pdf',
            //             'application/msword',
            //             'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            //             'application/vnd.ms-excel',
            //             'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            //             'application/zip',
            //             'application/x-rar-compressed'
            //         ];

            //         if (!in_array($files['file']['type'], $validTypes)) {
            //             $this->errors['file'] = "Type de fichier non supporté";
            //         }
            //     } elseif (!$id) {
            //         // Fichier obligatoire seulement pour l'ajout
            //         $this->errors['file'] = "Un fichier est obligatoire";
            //     }
            // }

            return empty($this->errors);
        }

        
    }