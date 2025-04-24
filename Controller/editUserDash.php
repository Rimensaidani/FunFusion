<?php
include_once '../Model/UserModel.php';
include_once '../Model/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') 
{    
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $birth_date = new DateTime($_POST['birth_date']);  
    $role = $_POST['role'];
    $password = $_POST['password'];  

    $user = new User($id, $username, $email, $phone, $birth_date, $role, $password);

    $userModel = new UserModel();
    $userModel->updateUser($user, $id);

    header('Location: ../View/backOffice/dashboard/index.php');
    exit();
}
?>
