<?php

namespace Model;

defined('ROOTPATH') or exit('Access Denied!');

/**
 * Order class
 */
class Order
{

    use Model;

    protected $table = 'orders';

    protected $allowedColumns = [

        'user_id',
        'total_amount',
        'status',
        'contact',
        'delivery_address',
        'payment_method',
        'created_at'
    ];

    /**
     * Mettre à jour le statut de la commande à "paid"
     */
    public function updateStatusToPaid($order_id, $user_id)
    {
        $data = [
            'order_id' => $order_id,
            'user_id' => $user_id
        ];
        $query = "UPDATE $this->table SET status = 'paid' WHERE id = :order_id AND user_id = :user_id";

        return $this->query($query, $data);
    }

    public function validate($data)
    {
        $this->errors = [];

        if (empty($data['user_id'])) {
            $this->errors['user_id'] = "Users id is required";
        }
        
        if (empty($data['contact'])) {
            $this->errors['contact'] = "Contact number is required";
        }

        if (empty($data['delivery_address'])) {
            $this->errors['delivery_address'] = "Delivery address is required";
        }

        if (empty($this->errors)) {
            return true;
        }

        return false;
    }

    public function create_table()
    {
        

    
    }

    
}
