<?php
session_start();
session_unset(); 
session_destroy(); 

header('Location: ../frontOffice/index_signin.php');
exit();
?>
