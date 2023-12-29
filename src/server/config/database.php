<?php
class Database
{
    private $username = 'root';
    private $password = '';
    private $db = 'ask-answer';
    private $servername = 'localhost';
    private $charset = 'utf8mb4';
    private $conn;


    public function connect()
    {
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->servername . ";dbname=" . $this->db . ';charset=' . $this->charset,
                $this->username,
                $this->password
            );

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "connected Failed" . $e->getMessage();
        }

        return $this->conn;
    }
}
