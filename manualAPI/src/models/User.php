<?php

namespace models;

use app\Database;
use \PDO;

class User
{
    private PDO $connection;
    private string $tableName = 'users';

    public function __construct(Database $database)
    {
        $this->connection = $database->getConnection();
    }

    /**
     * @param string $userName
     * @param string $password
     * @return mixed ( array | bool(false) )
     */
    public function get(string $userName, string $password)
    {
        $sql = "SELECT *
                FROM {$this->tableName}
                WHERE username = :username and password = :password ";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':username', $userName, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
