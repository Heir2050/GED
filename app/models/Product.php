<?php

namespace Model;

defined('ROOTPATH') or exit('Access Denied!');

/**
 * Product class
 */
class Product
{

    use Model;


    protected $table = 'products';

    protected $allowedColumns = [

        'image',
        'name',
        'description',
        'price',
        'weight',
        'stock_quantity',
        'category_id',
    ];

    public function validate($files_data, $post_data, $id = null)
    {
        
        $this->errors = [];

        if (empty($post_data['name'])) {
            $this->errors['name'] = "Name of product is required";
        }

        // if (empty($data['image'])) {
        //     $this->errors['image'] = "An image of product is required";
        // }

        if (empty($post_data['description'])) {
            $this->errors['description'] = "Description is required";
        }

        if (empty($post_data['category_id'])) {
            $this->errors['category_id'] = "Category is required";
        }

        if (!is_numeric($post_data['price'])) {
            $this->errors['price'] = "Price must be a number";
        }

        if (!is_numeric($post_data['weight'])) {
            $this->errors['weight'] = "Weight must be a number";
        }

        if (!is_numeric($post_data['stock_quantity'])) {
            $this->errors['stock_quantity'] = "Quantity must be a number";
        }

        if (empty($this->errors)) {
            return true;
        }

        return false;
    }

    public function create_table()
    {
        $query = "CREATE TABLE IF NOT EXISTS products(
			
		)";

        $this->query($query);
    }
}
