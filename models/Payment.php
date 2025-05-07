<?php
class Payment {
    private $conn;

    // Payment properties
    public $id;
    public $reservation_id;
    public $amount;
    public $user_id; // Static user ID (1 for this example)
    public $status; // E.g. 'paid', 'pending', 'failed'

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create Payment
    public function createPayment() {
        $query = "INSERT INTO payments (reservation_id, amount, user_id, status) VALUES (:reservation_id, :amount, :user_id, :status)";
        $stmt = $this->conn->prepare($query);

        // Clean and bind
        $this->reservation_id = htmlspecialchars(strip_tags($this->reservation_id));
        $this->amount = htmlspecialchars(strip_tags($this->amount));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->status = htmlspecialchars(strip_tags($this->status));

        $stmt->bindParam(':reservation_id', $this->reservation_id);
        $stmt->bindParam(':amount', $this->amount);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':status', $this->status);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>