<?php

class Database
{
    public $conn;

    public function __construct()
    {
        $host = getenv('DB_HOST') ?: 'localhost';
        $name = getenv('DB_NAME') ?: 'coma_news';
        $user = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASS');
        if ($password === false) {
            throw new RuntimeException('DB_PASS is not configured.');
        }
        try {
            $this->conn = new PDO(
                "mysql:host={$host};dbname={$name};charset=utf8mb4",
                $user,
                $password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $exception) {
            error_log('Database connection failed (code ' . $exception->getCode() . ').');
            http_response_code(503);
            exit('Databasen är tillfälligt otillgänglig.');
        }
    }
}
