<?php
session_start();

$enteredCode = $_POST['code'];
$correctCode = $_SESSION['email_code'];

if ($enteredCode == $correctCode) {
    header("Location: ../View/backOffice/resetEmailpassword.php");
    exit();
} else {
    header("Location: ../View/backOffice/verifyEmailcode.php?error=1");
    exit();
}

?>