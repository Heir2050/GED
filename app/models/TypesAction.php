<?php

    namespace Model;

    defined('ROOTPATH') OR exit('Access Denied!');

    class TypesAction
    {
        use Model;

        protected $table = 'typesaction';
        protected $allowedColumns = [
            'id',
            'code',
            'description'
        ];
    }