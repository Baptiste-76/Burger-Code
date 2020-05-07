<?php

class Database {
    private static $connection = null;
    
    public static function connect() {     
        try {
            // self::$connection = new PDO("mysql:host=" . $_ENV['HOST'] . ";port=3306;dbname=" .$_ENV ['DB_NAME'], $_ENV['USER'], $_ENV['PASSWORD']);
            self::$connection = new PDO($_ENV['JAWSDB_URL']);
        } catch (PDOException $exception) {
            die($exception->getMessage());
        }
        return self::$connection;
    }

    public static function disconnect() {
        self::$connection = null;
    }
}