
<?php
session_start();
require_once '../Model/user.php';
require_once '../config.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'checkCode':
        checkCode();
        break;
    default:
        break;
}


function checkCode()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['code'])) {
        $enteredCode = $_POST['code'];

        if (isset($_SESSION['verification_code']) && $enteredCode == $_SESSION['verification_code']) {

            header('Location: ../View/backOffice/resetPassword.php');
            exit();
        } 
        else {

            $_SESSION['error_message'] = "Invalid verification code. Please try again.";
            header('Location: ../View/backOffice/verifyCode.php');
            exit();
        }
    }
}

?>



















