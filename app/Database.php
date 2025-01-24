<?php
class Database {
    private static $instance = null;
    private string $username;
    private string $password;
    private string $host;
    private string $database;
    private int $port;

    private PDO $connection;

    private function __construct() {
        $this->username = 'user';
        $this->password = 'password';
        $this->host = 'db';
        $this->database = 'app';
        $this->port = 3306;

        try {
            $this->connection = new PDO(
                "mysql:host=$this->host;port=$this->port;dbname=$this->database",
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $error) {
            die($error);
        }
    }

    public static function get(): PDO
    {
        if(!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->connection;
    }
}