<?php
session_start();
require_once '../Model/userModel.php';

require '../vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = $_POST['email'];
$userModel = new UserModel();

if ($userModel->emailExists($email)) {
    $code = rand(100000, 999999);
    $_SESSION['reset_email'] = $email;
    $_SESSION['email_code'] = $code;

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'funfusion2418@gmail.com';      
        $mail->Password = 'app password';   
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('youraccount@gmail.com', 'FunFusion');
        $mail->addAddress($email);

        $mail->Subject = 'FunFusion - Password Reset Code';
        $mail->Body    = "Your code is: $code";

        $mail->send();

        header("Location: ../View/backOffice/verifyEmailcode.php");
        exit();
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Email address not found.";
}
?>