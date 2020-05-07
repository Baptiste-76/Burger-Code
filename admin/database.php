<?php

class Database {
    private static $connection = null;
    
    public static function connect() {     
        try {
            self::$connection = new PDO("mysql:host=" . $_ENV['HOST'] . ";dbname=" . $_ENV ['DB_NAME'], $_ENV['USER'], $_ENV['PASSWORD']);
        } catch (PDOException $exception) {
            die($exception->getMessage());
        }
        return self::$connection;
    }

    public static function disconnect() {
        self::$connection = null;
    }
}