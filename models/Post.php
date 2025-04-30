<?php
require_once 'Comment.php';

class Post {
    public $id;
    public $title;
    public $content;
    public $user_id; // 👈 New property
    public $comments;

    public function __construct($id = null, $title = null, $content = null, $comments = [], $user_id = null) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->comments = $comments;
        $this->user_id = $user_id; 
    }

    // Method to create a new post
    public function create($db) {
        $query = "INSERT INTO posts (title, content, user_id) VALUES (:title, :content, :user_id)"; // Include user_id in the query
        $stmt = $db->prepare($query);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':user_id', $this->user_id); // Bind user_id to the query
        return $stmt->execute();
    }
    // Method to read all posts
    public function read($db) {
        $query = "SELECT * FROM posts";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Method to delete a post
    public function delete($db) {
        $query = "DELETE FROM posts WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
    public function find($db) {
        $query = "SELECT * FROM posts WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update($db) {
        $query = "UPDATE posts SET title = :title, content = :content WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
    
    
}