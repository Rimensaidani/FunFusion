<?php
session_start();
require_once '../Model/user.php'; 
require_once '../config.php';
require_once '../twilio/twilio-php-main/twilio-php-main/src/Twilio/autoload.php';
require_once 'sendSMS.php';


$action = $_GET['action'] ?? '';

switch ($action) {
    case 'verifyPhone':
        verifyPhone();
        break;
}

function verifyPhone()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $phone = $_POST['phone'];
        $user = User::getUserByPhone($phone);

        if ($user) {
            $verificationCode = rand(100000, 999999);

            $_SESSION['verification_code'] = $verificationCode;
            $_SESSION['reset_phone'] = $phone;
            $_SESSION['user_id'] = $user['id'];

            $message = "Your FunFusion verification code is: " . $verificationCode;
            sendSMS($phone, $message);

            header('Location: ../View/backOffice/verifyCode.php'); // Ensure this path is correct
            exit();
        } else {
            echo "Phone number not found. Please try again.";
            exit();
        }
    }
}



?>


















