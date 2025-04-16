<?php
include_once '../models/Comment.php'; // Adjusted path to Comment model
include_once '../config/db.php'; // Adjusted path for db connection

class CommentController {
    private $db;
    private $comment;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->comment = new Comment($this->db);
    }

    public function createComment($post_id, $content) {
        $this->comment->post_id = $post_id;
        $this->comment->content = $content;
        if ($this->comment->create()) {
            echo "Comment created successfully!";
        } else {
            echo "Unable to create comment.";
        }
    }

    public function deleteComment($id) {
        $this->comment->id = $id;
        if ($this->comment->delete()) {
            echo "Comment deleted successfully!";
        } else {
            echo "Unable to delete comment.";
        }
    }

    public function getCommentsByPostId($post_id) {
        $this->comment->post_id = $post_id; // Set the post_id for fetching comments
        $stmt = $this->comment->read(); // Call the read method
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all the comments
    
        return $comments; // Return comments as an array
    }
}
?>