<?php
require_once 'userController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $controller = new userController();
    $id = (int) $_POST['id'];
    $controller->deleteUser($id);
}

header('Location: ../View/backOffice/dashboard/index.php');
exit;
?>