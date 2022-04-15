<?php

class OrderDetail {

    private $table = 'order_details';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllDetails()
    {
        $this->db->query('SELECT * FROM ' . $this->table);
        return $this->db->resultSet();
    }

    public function getDetailsWhere($col, $cond, $val)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE ' . $col . ' ' . $cond . ' :val';

        $this->db->query($query);
        $this->db->bind('val',$val);
        return $this->db->resultSet();
    }

    public function getDetailById($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id=:id');
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getDetailsFoodWhere($cond)
    {
        $query = "SELECT d.id, d.food_id, f.name, f.price, d.quantity, d.subtotal
                    FROM order_details d
                    LEFT JOIN foods f
                    ON f.id = d.food_id
                    WHERE $cond";

        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function addDetail($data)
    {
        $query = 'INSERT INTO ' . $this->table . ' VALUES (null, :order_id, :food_id, :quantity, :subtotal)';

        $this->db->query($query);
        $this->db->bind('order_id', $data['order_id']);
        $this->db->bind('food_id', $data['food_id']);
        $this->db->bind('quantity', $data['quantity']);
        $this->db->bind('subtotal', $data['subtotal']);

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function deleteDetail($id)
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        $this->db->query($query);
        $this->db->bind('id', $id);

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function updateDetail($data)
    {
        $query = "UPDATE $this->table SET
                    quantity = :quantity,
                    subtotal = :subtotal
                    WHERE id = :id";

        $this->db->query($query);
        $this->db->bind('quantity', $data['quantity']);
        $this->db->bind('subtotal', $data['subtotal']);
        $this->db->bind('id', $data['id']);

        $this->db->execute();

        return $this->db->rowCount();
    }
    
}