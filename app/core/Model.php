<?php 

namespace app\core;

use \PDO;

class Model 
{
    private $db;

    public function __construct()
    {
       $this->db = Database::getInstance();
    }

    public function query($sql, $params = [])
    {
        $result = $this->db->query($sql, $params);

        return $result;
    }

    public function row($sql, $params = [])
    {
        $result = $this->db->query($sql, $params);

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function column($sql, $params = [])
    {
        $result = $this->db->query($sql, $params);

        return $result->fetchColumn();
    }

    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }
}