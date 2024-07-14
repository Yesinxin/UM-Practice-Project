<?php
class Connector
{
    private $conn;

    public function __construct()
    {
        $this->connect();
        $this->checkAndCreateTable();
    }

    private function connect()
    {
        $db_host = "localhost";
        $db_user = "root";
        $db_pass = "password";
        $db_select = "um_practice_project";

        try {
            $this->conn = new PDO("mysql:host=$db_host;dbname=$db_select", $db_user, $db_pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
            exit();
        }
    }

    private function checkAndCreateTable()
    {
        $stmt = $this->conn->prepare("SHOW TABLES LIKE 'users'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $createTableSql = "CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            $this->conn->exec($createTableSql);
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }
}

?>
