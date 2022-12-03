<?php

namespace models;

use \PDO;
use app\Database;

class Product
{
    private PDO $connection;
    private array $cast = [
        'id' => 'integer',
        'price' => 'integer'
    ];

    /**
     * Set database connection
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->connection = $database->getConnection();
    }

    /**
     * Get all products
     * @return array
     */
    public function getAll() : array
    {
        $sql = "SELECT * 
                FROM products";
        $stmt = $this->connection->query($sql);

        $data =  [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $this->castData($row);
        }

        return $data;
    }

    /**
     * Get product
     * @param int $id
     * @return array | bool(false)
     */
    public function get(string $id)
    {
        $sql = "SELECT * 
                FROM products 
                WHERE id = :id";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $this->castData($stmt->fetch(PDO::FETCH_ASSOC));
    }

    /**
     * Add product
     * @param array $data
     * @return string
     */
    public function create(array $data) : string
    {
        $sql = "INSERT INTO products (name, description, price)
                VALUES (:name, :description, :price);";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindValue(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindValue(':price', $data['price'], PDO::PARAM_INT);
        $stmt->execute();

        return $this->connection->lastInsertId();
    }

    /**
     * Update product
     * @param array $current
     * @param array $new
     * @return int
     */
    public function update(array $current, array $new) : int
    {
        $sql = "UPDATE products
                SET name = :name, description = :description, price = :price
                WHERE id = :id";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':name', $new['name'] ?? $current['name'], PDO::PARAM_STR);
        $stmt->bindValue(':description', $new['description'] ?? $current['description'], PDO::PARAM_STR);
        $stmt->bindValue(':price', $new['price'] ?? $current['price'], PDO::PARAM_INT);
        $stmt->bindValue(':id', $current['id'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }

    /**
     * Delete product
     * @param string $id
     * @return int
     */
    public function delete(string $id) : int
    {
        $sql = "DELETE FROM products 
                WHERE id = :id";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }

    /**
     * Setting the return data type
     * @param array | bool(false) $row
     * @return array | bool(false)
     */
    private function castData($row)
    {
        if ($row) {
            foreach ($this->cast as $key => $value) {
                settype($row[$key], $value);
            }
        }
        return $row;
    }
}
