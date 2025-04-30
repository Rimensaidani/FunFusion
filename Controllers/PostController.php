<?php
include_once '../models/Post.php'; // Path to Post model
include_once '../config/db.php'; // Path for db connection

class PostController {
    private $db;

    public function __construct() {
        $database = new Database(); // Create a Database instance
        $this->db = $database->getConnection(); // Get database connection
    }
    public function createPost($title, $content) {
        // Pass CURRENT_USER_ID as the user_id when creating a new Post instance
        $post = new Post(null, $title, $content, [], CURRENT_USER_ID); // Include CURRENT_USER_ID
        
        if ($post->create($this->db)) {
            echo "Post created successfully!";
        } else {
            echo "Unable to create post.";
        }
    }

    public function getAllPosts() {
        $post = new Post(); // Create a new instance of Post to fetch posts
        $stmt = $post->read($this->db); // Call read and get the statement
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all posts as associative array
        return json_encode($posts); // Convert the posts to JSON for returning
    }

    public function deletePost($id) {
        $post = new Post($id); // Create a new instance of Post for deletion
        if ($post->delete($this->db)) { // Call the delete method
            echo "Post deleted successfully!";
        } else {
            echo "Unable to delete post.";
        }
    }
    public function getPostById($id) {
        $post = new Post($id);
        $data = $post->find($this->db);
        return $data;
    }
    
    public function updatePost($id, $title, $content) {
        $post = new Post($id, $title, $content);
        return $post->update($this->db);
    }
    
}
?>