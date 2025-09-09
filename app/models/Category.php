<?php

namespace Model;

defined('ROOTPATH') or exit('Access Denied!');

/**
 * Category class
 */
class Category
{

    use Model;

    protected $table = 'categories';

    protected $allowedColumns = [

        'name',
        'description',
    ];

    public function getOrder_column() {
        return 'category_id';
    }

    public function validate($data)
    {

        $this->errors = [];

        if (empty($data['name'])) {
            $this->errors['name'] = "The name of category is required";
        }

        if (empty($data['description'])) {
            $this->errors['description'] = "Description is required";
        }

        if (empty($this->errors)) {
            return true;
        }

        return false;
    }

    public function create_table()
    {
        $query = "CREATE TABLE IF NOT EXISTS Categories(
			
		)";

        $this->query($query);
    }
}
