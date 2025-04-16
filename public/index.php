<?php
// Ensure the correct paths for the includes
include_once '../config/db.php'; // Correct path to db.php
include_once '../controllers/PostController.php'; // Correct path to PostController
include_once '../controllers/CommentController.php'; // Correct path to CommentController

// Initialize controllers with the PDO connection from the database file
$postController = new PostController(); // Ensure you pass the PDO object if required
$commentController = new CommentController();

// Define actions based on GET request
$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'home':
    default:
        displayHome($postController, $commentController); // Call the function to display home
        break;

    case 'admin':
        displayAdmin(); // New case for the admin panel
        break;

    case 'comments':
        displayComments($commentController); // New case for the comments management
        break;

        case 'create_post':
            if (isset($_POST['post_title'], $_POST['post_content'])) {
                $result = $postController->createPost($_POST['post_title'], $_POST['post_content']);
                
                $source = $_GET['source'] ?? 'home'; // Default to 'home' if not set
        
                if ($source === 'admin') {
                    header("Location: index.php?action=admin&add=success");
                } else {
                    header("Location: index.php?action=home&add=success");
                }
                exit;
            }
            break;
        

    case 'create_comment':
        if (isset($_POST['comment_text'], $_POST['post_id'])) {
            $commentController->createComment($_POST['post_id'], $_POST['comment_text']);
            // Redirect to the comments page where the comment was posted
            header("Location: index.php?action=home&post_id={$_POST['post_id']}"); 
            exit;
        }
        break;

    case 'delete_post':
        if (isset($_GET['post_id'])) {
            $postController->deletePost($_GET['post_id']);
            header("Location: index.php?action=admin"); // Redirect after deletion
            exit;
        }
        break;
        case 'edit_post':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_title'], $_POST['post_content'], $_GET['id'])) {
                $postController->updatePost($_GET['id'], $_POST['post_title'], $_POST['post_content']);
                header("Location: index.php?action=admin&edit=success");
                exit;
            } else {
                $post = $postController->getPostById($_GET['id']);
                include '../views/edit_post.php'; // Create this view
            }
            break;
        

            case 'delete_comment':
                if (isset($_GET['comment_id'])) {
                    $commentController->deleteComment($_GET['comment_id']);
                    
                
                    header("Location: index.php?action=home&post_id={$_GET['post_id']}");
                    exit;
                }
                break;
            
}

// Function to display the homepage with posts and comment forms
function displayHome($postController, $commentController) {
    $posts = json_decode($postController->getAllPosts(), true); // Fetch all posts
    include '../views/home.php'; // Render the home view
}

// New function to display comments for a specific post
function displayComments($commentController) {
    $post_id = $_GET['post_id'] ?? null; // Get the post ID from the request
    if ($post_id) {
        $comments = $commentController->getCommentsByPostId($post_id); // Fetch comments related to the post
        include '../views/comments.php'; // Render the comments view
    } else {
        echo "No post selected for comments."; // Handle case where no post ID is provided
    }
}

function displayAdmin() {
    include '../views/admin.php'; // Renders the admin view
}
?>