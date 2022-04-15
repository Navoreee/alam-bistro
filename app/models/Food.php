<?php

class Food {

    private $table = 'foods';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllFoods()
    {
        $this->db->query('SELECT * FROM ' . $this->table);
        return $this->db->resultSet();
    }

    public function getFoodsWhere($col, $cond, $val)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE ' . $col . ' ' . $cond . ' :val';

        $this->db->query($query);
        $this->db->bind('val',$val);
        return $this->db->resultSet();
    }

    public function getFoodById($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id=:id');
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function addFood($data)
    {
        $query = 'INSERT INTO ' . $this->table . ' VALUES (null, :name, :description, :category_id, :price, :img_name)';

        $this->db->query($query);
        $this->db->bind('name', $data['name']);
        $this->db->bind('description', $data['description']);
        $this->db->bind('category_id', $data['category_id']);
        $this->db->bind('price', $data['price']);
        $this->db->bind('img_name', $data['img_name']);

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function deleteFood($id)
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        $this->db->query($query);
        $this->db->bind('id', $id);

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function updateFood($data)
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