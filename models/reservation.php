<?php
class Reservation {
    private $conn;
    public $id;
    public $resource_id; // foreign key to Resource
    public $location;
    public $date_reservation;
    public $date_retour;
    public $user; // or employee
    public $etat; // reserved, returned, pending, etc.

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $sql = "INSERT INTO reservations (resource_id, location, date_reservation, date_retour, user, etat) 
                VALUES (:resource_id, :location, :date_reservation, :date_retour, :user, :etat)";
        $stmt = $this->conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':resource_id', $this->resource_id);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':date_reservation', $this->date_reservation);
        $stmt->bindParam(':date_retour', $this->date_retour);
        $stmt->bindParam(':user', $this->user);
        $stmt->bindParam(':etat', $this->etat);

        return $stmt->execute();
    }

    public function readAll() {
        $sql = "SELECT * FROM reservations";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $sql = "SELECT * FROM reservations WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        $sql = "UPDATE reservations SET 
                    resource_id = :resource_id, 
                    location = :location, 
                    date_reservation = :date_reservation, 
                    date_retour = :date_retour, 
                    user = :user, 
                    etat = :etat 
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':resource_id', $this->resource_id);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':date_reservation', $this->date_reservation);
        $stmt->bindParam(':date_retour', $this->date_retour);
        $stmt->bindParam(':user', $this->user);
        $stmt->bindParam(':etat', $this->etat);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM reservations WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>