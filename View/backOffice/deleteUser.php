<?php
require_once __DIR__.'/../../Controller/userController.php';

if (isset($_GET['id'])) 
{
    $userC = new userController();
    $userC->deleteUser($_GET['id']);

    session_start();
    session_unset();
    session_destroy();

    header('Location: ../../View/frontOffice/index_signup.php');
    exit();
} 

?>
