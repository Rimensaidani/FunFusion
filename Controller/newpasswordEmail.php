<?php

session_start(); 

require_once '../Model/UserModel.php';

if (!isset($_SESSION['reset_email'])) {
    die('⚠️ Session expired or invalid access.');
}

$email = $_SESSION['reset_email'];
$newPassword = $_POST['new_password'];

$userModel = new UserModel();
$userModel->updatePasswordByEmail($email, $newPassword);

unset($_SESSION['reset_email']);
unset($_SESSION['email_code']);

header("Location: ../View/backOffice/signIn.php");

?>


