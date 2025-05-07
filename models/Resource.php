<?php
class Resource {
    private $conn;
    public $id;
    public $type;
    public $nom;
    public $description;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $sql = "INSERT INTO resources (type, nom, description, status) 
                VALUES (:type, :nom, :description, :status)";
        $stmt = $this->conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':status', $this->status);

        return $stmt->execute();
    }

    public function readAll() {
        $sql = "SELECT * FROM resources";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $sql = "SELECT * FROM resources WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        $sql = "UPDATE resources SET type = :type, nom = :nom, 
                description = :description, status = :status 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    public function delete() {
        $sql = "DELETE FROM resources WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
}
?>