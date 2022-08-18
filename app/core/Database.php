<?php 

namespace app\core;

use PDO;

class Database 
{
    use Singleton;

    private $connection;

    public function __construct()
    {
        $config = Config::getSection('database');
        $host = $config['DB_HOST'];
        $database = $config['DB_DATABASE'];
        $username = $config['DB_USERNAME'];
        $password = $config['DB_PASSWORD'];

        $this->connection = new PDO('mysql:host=' . $host . ';dbname=' . $database . '', $username, $password);
    }

    public function query($sql, $params = [])
    {   
        $stmt = $this->connection->prepare($sql);
        if (!empty($params) && is_array($params)) {
            foreach ($params as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
        }

        $stmt->execute();
        return $stmt;
    }

    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }
}