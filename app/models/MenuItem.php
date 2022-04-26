<?php

class MenuItem {

    private $table = 'menu_items';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllItems()
    {
        $this->db->query('SELECT * FROM ' . $this->table);
        return $this->db->resultSet();
    }

    public function getItemsWhere($col, $cond, $val)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE ' . $col . ' ' . $cond . ' :val';

        $this->db->query($query);
        $this->db->bind('val',$val);
        return $this->db->resultSet();
    }

    public function getItemById($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id=:id');
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function addItem($data)
    {
        $query = 'INSERT INTO ' . $this->table . ' VALUES (null, :category_id, :name, :description, :price, :img_name)';

        $this->db->query($query);
        $this->db->bind('name', $data['name']);
        $this->db->bind('description', $data['description']);
        $this->db->bind('category_id', $data['category_id']);
        $this->db->bind('price', $data['price']);
        $this->db->bind('img_name', $data['img_name']);

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function deleteItem($id)
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        $this->db->query($query);
        $this->db->bind('id', $id);

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function updateItem($data)
    {
        $query = "UPDATE $this->table SET
                    name = :name,
                    description = :description,
                    category_id = :category_id,
                    price = :price,
                    img_name = :img_name
                    WHERE id = :id";

        $this->db->query($query);
        $this->db->bind('name', $data['name']);
        $this->db->bind('description', $data['description']);
        $this->db->bind('category_id', $data['category_id']);
        $this->db->bind('price', $data['price']);
        $this->db->bind('img_name', $data['img_name']);
        $this->db->bind('id', $data['id']);

        $this->db->execute();

        return $this->db->rowCount();
    }
}