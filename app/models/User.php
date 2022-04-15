<?php

class User {

    private $table = 'users';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllUsers()
    {
        $this->db->query('SELECT * FROM ' . $this->table);
        return $this->db->resultSet();
    }

    public function getUsersWhere($col, $cond, $val)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE ' . $col . ' ' . $cond . ' :val';

        $this->db->query($query);
        $this->db->bind('val',$val);
        return $this->db->resultSet();
    }

    public function getUserById($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id=:id');
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getUserByCredentials($data)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE email = :email AND password = :password';

        $this->db->query($query);
        $this->db->bind('email',$data['email']);
        $this->db->bind('password',$data['password']);
        return $this->db->single();
    }

    public function addUser($data)
    {
        $query = 'INSERT INTO ' . $this->table . ' VALUES (null, :email, :password, :name, :role)';

        $this->db->query($query);
        $this->db->bind('email', $data['email']);
        $this->db->bind('password', $data['password']);
        $this->db->bind('name', $data['name']);
        $this->db->bind('role', 'user');

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function deleteUser($id)
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        $this->db->query($query);
        $this->db->bind('id', $id);

        $this->db->execute();

        return $this->db->rowCount();
    }
}