<?php

namespace Connection;

use PDO;

class Connection
{
    /**
     * @var PDO
     */
    private static $instance;

    /**
     * Реализация singleton
     * @return PDO
     */
    public static function getInstance(): PDO
    {
        if (is_null(self::$instance)) {
            $dsn = 'mysql:dbname=db;host=127.0.0.1';
            $user = 'dbuser';
            $password = 'dbpass';
            self::$instance = new PDO($dsn, $user, $password);
        }

        return self::$instance;
    }

    public static function prepare(string $sql, array $options = [])
    {
        return self::getInstance()->prepare($sql, $options);
    }

    public static function lastInsertId() : string
    {
         $lastId = Connection::getInstance()->lastInsertId();
         if (!$lastId) {
             throw new \PDOException('error when get id');
         }

         return $lastId;
    }

    public static function execute(\PDOStatement $statement, $params = null) : bool
    {
        return $statement->execute($params);
    }

    public static function fetchAll(\PDOStatement $statement, int $mode = PDO::FETCH_BOTH) : array
    {
        return $statement->fetchAll($mode);
    }
}
