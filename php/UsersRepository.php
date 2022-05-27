<?php

namespace Gateway;

use Connection\Connection;
use PDO;
use PDOException;


class UsersRepository
{
    const limit = 10;

    /**
     * Возвращает список пользователей старше заданного возраста.
     * @param int $ageFrom
     * @return array
     */
    public static function getUsers(int $ageFrom): array
    {
        $stmt = Connection::prepare("SELECT id, name, lastName, from, age, settings FROM Users WHERE age > {$ageFrom} LIMIT " . self::limit);
        if (!$stmt) {
            throw new \PDOException('invalid query');
        }

        if (!Connection::execute($stmt)) {
            return [];
        }

        $rows = Connection::fetchAll($stmt, PDO::FETCH_ASSOC);
        $users = [];
        foreach ($rows as $row) {
            $settings = json_decode($row['settings']);
            $users[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'lastName' => $row['lastName'],
                'from' => $row['from'],
                'age' => $row['age'],
                'key' => $settings['key'],
            ];
        }

        return $users;
    }

    /**
     * Возвращает пользователя по имени.
     * @param string $name
     * @return array
     */
    public static function user(string $name): array
    {
        $stmt = Connection::prepare("SELECT id, name, lastName, from, age, settings FROM Users WHERE name = {$name}");
        if (!$stmt) {
            throw new \PDOException('invalid query');
        }

        if (!Connection::execute($stmt)) {
            return [];
        }

        $userByName = Connection::fetchAll($stmt, PDO::FETCH_ASSOC);

        return [
            'id' => $userByName['id'],
            'name' => $userByName['name'],
            'lastName' => $userByName['lastName'],
            'from' => $userByName['from'],
            'age' => $userByName['age'],
        ];
    }

    /**
     * Добавляет пользователя в базу данных.
     * @param string $name
     * @param string $lastName
     * @param int $age
     * @return string
     */
    public static function add(string $name, string $lastName, int $age): string
    {
        $sth = Connection::prepare("INSERT INTO Users (name, lastName, age) VALUES (:name, :age, :lastName)");
        if (!Connection::execute($sth, [':name' => $name, ':age' => $age, ':lastName' => $lastName])) {
            throw new PDOException('error when adding a user');
        }

        return Connection::lastInsertId();
    }
}
