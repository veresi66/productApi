<?php

namespace app;

use \PDO;

class Database
{
    /**
     * @return PDO
     */
    public function getConnection() : PDO
    {
        $dsn = "sqlite:" . __DIR__ . "/../db/database.db";

        return new PDO($dsn, null, null, [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false
        ]);
    }
}
