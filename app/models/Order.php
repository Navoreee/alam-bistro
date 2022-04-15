<?php

class Order {

    private $table = 'orders';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllOrders()
    {
        $this->db->query('SELECT * FROM ' . $this->table);
        return $this->db->resultSet();
    }

    public function getOrdersWhere($col, $cond, $val)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE ' . $col . ' ' . $cond . ' :val';

        $this->db->query($query);
        $this->db->bind('val',$val);
        return $this->db->resultSet();
    }

    public function getOrderById($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id=:id');
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getOrderUserWhere($cond)
    {
        $query = "SELECT o.id, o.table_no, o.total_price, o.time, o.completed, u.name
                    FROM orders o
                    LEFT JOIN users u
                    ON u.id = o.user_id
                    WHERE $cond";

        $this->db->query($query);
        return $this->db->single();
    }

    public function getCurrentOrder($user_id)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE completed = 0 AND user_id = :user_id';

        $this->db->query($query);
        $this->db->bind('user_id', $user_id);

        return $this->db->single();
    }

    public function addOrder()
    {
        $query = 'INSERT INTO ' . $this->table . ' VALUES (null, :user_id, :table_no, :total_price, :time, :submitted, :completed)';

        $this->db->query($query);
        $this->db->bind('user_id', Auth::userId());
        $this->db->bind('table_no', null);
        $this->db->bind('total_price', 0);
        $this->db->bind('time', null);
        $this->db->bind('submitted', 0);
        $this->db->bind('completed', 0);

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function deleteOrder($id)
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        $this->db->query($query);
        $this->db->bind('id', $id);

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function updateOrder($data)
    {
        $query = "UPDATE $this->table SET
                    table_no = :table_no,
                    total_price = :total_price,
                    time = :time,
                    submitted = :submitted
                    WHERE id = :id";

        $this->db->query($query);
        $this->db->bind('table_no', $data['table_no']);
        $this->db->bind('total_price', $data['total_price']);
        $this->db->bind('time', $data['time']);
        $this->db->bind('submitted', $data['submitted']);
        $this->db->bind('id', $data['id']);

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function updateCompleted($id)
    {
        $query = 'UPDATE ' . $this->table . ' SET completed = 1 WHERE id = :id';

        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->execute();
    }
}