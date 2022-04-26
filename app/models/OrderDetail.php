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

    public function getDetailsItemWhere($cond)
    {
        $query = "SELECT d.id, d.item_id, i.name, i.price, d.quantity, d.subtotal
                    FROM order_details d
                    LEFT JOIN menu_items i
                    ON i.id = d.item_id
                    WHERE $cond";

        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function addDetail($data)
    {
        $query = 'INSERT INTO ' . $this->table . ' VALUES (null, :order_id, :item_id, :quantity, :subtotal)';

        $this->db->query($query);
        $this->db->bind('order_id', $data['order_id']);
        $this->db->bind('item_id', $data['item_id']);
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