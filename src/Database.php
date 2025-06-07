<?php

class Database {
    private string $host;
    private string $name;
    private string $user;
    private string $password;
    private string $port;

    public function __construct(string $host, string $name, string $user, string $password, string $port = '3306') 
    {
        $this->host = $host;
        $this->name = $name;
        $this->user = $user;
        $this->password = $password;
        $this->port = $port;
    }

public function getConnection(): PDO {
    $dsn = "mysql:host={$this->host};dbname={$this->name};port={$this->port}";
    try {
        $pdo = new PDO($dsn, $this->user, $this->password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
        exit;
    }
}

}