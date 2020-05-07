<?php

class Database {
    private static $dbHost = $_ENV['HOST'];
    private static $dbName = $_ENV ['DB_NAME'];
    private static $dbUser = $_ENV['USER'];
    private static $dbUserPassword = $_ENV['PASSWORD'];
    private static $connection = null;
    
    public static function connect() {     
        try {
            self::$connection = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName, self::$dbUser, self::$dbUserPassword);
        } catch (PDOException $exception) {
            die($exception->getMessage());
        }
        return self::$connection;
    }

    public static function disconnect() {
        self::$connection = null;
    }
}