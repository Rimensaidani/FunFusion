<?php
class Comment {
    private $conn;
    private $table = "comments";

    // Comment properties
    public $id;
    public $post_id;
    public $content;
    public $created_at;

    // Constructor to initialize the database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new comment
    public function create() {
        $query = "INSERT INTO " . $this->table . " (post_id, content) VALUES (:post_id, :content)";
        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->post_id = htmlspecialchars(strip_tags($this->post_id));
        $this->content = htmlspecialchars(strip_tags($this->content));

        // Bind values
        $stmt->bindParam(":post_id", $this->post_id);
        $stmt->bindParam(":content", $this->content);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Get all comments for a post
    public function read() {
        $query = "SELECT * FROM " . $this->table . " WHERE post_id = :post_id";
        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->post_id = htmlspecialchars(strip_tags($this->post_id));

        // Bind the post_id parameter
        $stmt->bindParam(":post_id", $this->post_id);

        $stmt->execute();
        return $stmt;
    }

    // Get a single comment by ID
    public function readSingle() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind the ID parameter
        $stmt->bindParam(":id", $this->id);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set the properties of the Comment object
        if ($row) {
            $this->content = $row['content'];
            $this->created_at = $row['created_at'];
            $this->post_id = $row['post_id'];
        }
    }

    // Update a comment
    public function update() {
        $query = "UPDATE " . $this->table . " SET content = :content WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind values
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":id", $this->id);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Delete a comment
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind the ID parameter
        $stmt->bindParam(":id", $this->id);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
