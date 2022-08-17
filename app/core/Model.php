<?php 

namespace app\core;

use \PDO;

class Model 
{
    protected $db;

    public function __construct()
    {
       $this->db = Database::getInstance();
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
}