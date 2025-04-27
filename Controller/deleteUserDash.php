
<?php
session_start(); 
require_once 'userController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int) $_POST['id'];
    $controller = new userController();

    // Check if trying to delete admin with ID 63
    if ($id === 63) {
        $_SESSION['error'] = "You cannot delete the main admin.";
    } else {
        $controller->deleteUser($id);
        $_SESSION['success'] = "User deleted successfully.";
    }

    // Redirect to the dashboard after setting the session message
    header('Location: ../View/backOffice/dashboard/index.php');
    exit;
}
?>

