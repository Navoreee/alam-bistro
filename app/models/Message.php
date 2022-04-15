<?php

class Message {

    private $table = 'messages';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllMessages()
    {
        $this->db->query('SELECT * FROM ' . $this->table);
        return $this->db->resultSet();
    }

    public function getMessagesWhere($col, $cond, $val)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE ' . $col . ' ' . $cond . ' :val';

        $this->db->query($query);
        $this->db->bind('val',$val);
        return $this->db->resultSet();
    }

    public function getMessageById($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id=:id');
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function addMessage($data)
    {
        $query = 'INSERT INTO ' . $this->table . ' VALUES (null, :subject, :name, :email, :number, :message, :read_receipt)';

        $this->db->query($query);
        $this->db->bind('subject', $data['subject']);
        $this->db->bind('name', $data['name']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('number', $data['number']);
        $this->db->bind('message', $data['message']);
        $this->db->bind('read_receipt', '0');

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function deleteMessage($id)
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        $this->db->query($query);
        $this->db->bind('id', $id);

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function updateReadReceipt($id)
    {
        $query = 'UPDATE ' . $this->table . ' SET read_receipt = 1 WHERE id = :id';

        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->execute();
    }
}