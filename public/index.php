<?php
// Ensure the correct paths for the includes
include_once '../config/db.php'; // Correct path to db.php
include_once '../controllers/PostController.php'; // Correct path to PostController
include_once '../controllers/CommentController.php'; // Correct path to CommentController
$database = new Database();
$db = $database->getConnection();
// Initialize controllers with the PDO connection from the database file
$postController = new PostController($db);
$commentController = new CommentController($db);

define('CURRENT_USER_ID', 2);
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
                // Add user_id as part of the post data
                $result = $postController->createPost($_POST['post_title'], $_POST['post_content'], CURRENT_USER_ID);
                
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
            $commentController->createComment($_POST['post_id'], $_POST['comment_text'],CURRENT_USER_ID);
            // Redirect to the comments page where the comment was posted
            header("Location: index.php?action=home&post_id={$_POST['post_id']}"); 
            exit;
        }
        break;

        case 'delete_post':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
                $post = new Post($_POST['post_id']);
                $postData = $post->find($db);
        
                if ((int)$postData['user_id'] === CURRENT_USER_ID) {
                    $postController->deletePost($_POST['post_id']);
                }
        
                header("Location: index.php?action=home&delete=success");
                exit;
            }
            
        
           
        
        
        break;
        case 'edit_post':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_title'], $_POST['post_content'], $_GET['id'])) {
                // Check if the current user is the author of the post
                $post = $postController->getPostById($_GET['id']);
                if ((int)$post['user_id'] === CURRENT_USER_ID) { // Ensure the current user is the author
                    $postController->updatePost($_GET['id'], $_POST['post_title'], $_POST['post_content']);
                    header("Location: index.php?action=home&edit=success");
                    exit;
                } else {
                    // Redirect to home or show an error if the user is not the author
                    header("Location: index.php?action=home&error=not_authorized");
                    exit;
                }
            } else {
                $post = $postController->getPostById($_GET['id']);
                // Ensure the current user is the author before displaying the edit form
                if ((int)$post['user_id'] === CURRENT_USER_ID) {
                    include '../views/edit_post.php'; // Only show the edit form if the user is the author
                } else {
                    // Redirect to home or show an error if the user is not the author
                    header("Location: index.php?action=home&error=not_authorized");
                    exit;
                }
            }
            break;
        

            case 'delete_comment':
                if (isset($_GET['comment_id'])) {
                    $commentController->deleteComment($_GET['comment_id']);
                    
                
                    header("Location: index.php?action=home&post_id={$_GET['post_id']}");
                    exit;
                }
                break;
                case 'update_comment':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'], $_POST['comment_text'])) {
                        $success = $commentController->updateComment($_POST['comment_id'], $_POST['comment_text']);
                        if ($success) {
                            // Fetch the updated comment to return it
                            $updatedComment = $commentController->getCommentsByPostId($_POST['comment_id']); // Create this method if you don't have it
                            echo json_encode([
                                "success" => true,
                                "updated_content" => htmlspecialchars($updatedComment['content']),
                                "updated_at" => htmlspecialchars($updatedComment['created_at']),
                                "comment_id" => $_POST['comment_id'],
                                "post_id" => $updatedComment['post_id'],
                            ]);
                        } else {
                            echo json_encode([
                                "success" => false,
                                "message" => "Unable to update comment."
                            ]);
                        }
                        include '../views/comments.php'; // Render the comments view
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