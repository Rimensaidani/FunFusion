<?php
class Database {
    private $host = "localhost"; // Your database host
    private $db_name = "nono"; // Your database name
    private $username = "root"; // Your database username
    private $password = ""; // Your database password
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
          
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }
        return $this->conn;
    }
}
?>