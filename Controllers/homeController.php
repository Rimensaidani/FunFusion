<?php
include_once 'PostController.php';
include_once 'CommentController.php';

class HomeController {
    private $postController;
    private $commentController;

    // Constructor to initialize PostController and CommentController
    public function __construct($pdo) {
        $this->postController = new PostController($pdo);
        $this->commentController = new CommentController();
    }

    // Index method to fetch and display posts with comments
    public function index() {
        // Get all posts
        $postsJson = $this->postController->getAllPosts();
        $posts = json_decode($postsJson, true); // Assuming getAllPosts returns a JSON string

        // Render the homepage with posts
        include 'views/home.php';
    }
}