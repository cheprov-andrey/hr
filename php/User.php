<?php

namespace Manager;

use Connection\Connection;
use Gateway\UsersRepository;

class User
{
    /**
     * Возвращает пользователей старше заданного возраста.
     * @param int $ageFrom
     * @return array
     */
    function getUsers(int $ageFrom): array
    {
        return UsersRepository::getUsers($ageFrom);
    }

    /**
     * Возвращает пользователей по списку имен.
     * @param array $names
     * @return array
     */
    public static function getByNames(array $names): array
    {
        $users = [];
        foreach ($names as $name) {
            $users[] = UsersRepository::user($name);
        }

        return $users;
    }

    /**
     * Добавляет пользователей в базу данных.
     * @param array $users
     * @return array
     */
    public function addUsers(array $users): array
    {
        $ids = [];
        Connection::getInstance()->beginTransaction();
        foreach ($users as $user) {
            try {
                UsersRepository::add($user['name'], $user['lastName'], $user['age']);
                Connection::getInstance()->commit();
                $ids[] = Connection::getInstance()->lastInsertId();
            } catch (\Exception $e) {
                Connection::getInstance()->rollBack();
            }
        }

        return $ids;
    }
}
