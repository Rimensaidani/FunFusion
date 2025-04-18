<?php
/*

session_start();
require_once '../../Model/user.php';
require_once '../../config.php';
require_once '../../Controller/userController.php';

if (isset($_POST['signin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $controller = new userController();
    $user = $controller->getUserByUsername($username);

    if ($user && $user['password'] === $password) {
        
        $_SESSION['user'] = $user;  
        $_SESSION['user_id'] = $user['id'];
        header('Location:../../View/frontOffice/index_signin.php'); 
        exit;
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}*/
?>
<?php
session_start();
require_once '../../Model/user.php';
require_once '../../config.php';
require_once '../../Controller/userController.php';

$error = '';

if (isset($_POST['signin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $controller = new userController();
    $user = $controller->getUserByUsername($username);

    // For now you’re comparing plaintext; later switch this to password_verify()
    if ($user && $user['password'] === $password) {
        // 1) Store everything in the session
        $_SESSION['user']    = $user;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role']    = $user['role'];  

        // 2) Redirect based on role
        if ($user['role'] === 'admin') {
            header('Location:../../View/backOffice/dashboard/index.php');
        } else {
            header('Location:../../View/frontOffice/index_signin.php');
        }
        exit;
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>




<!DOCTYPE html>
<html>
    <head>
        <!-- basic -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- mobile metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="initial-scale=1, maximum-scale=1">
        <!-- site metas -->
        <title>Sign in | FunFusion</title>
        <meta name="keywords" content="">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- bootstrap css -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <!-- style css -->
        <link rel="stylesheet" href="css/style.css">
        <!-- Responsive-->
        <link rel="stylesheet" href="css/responsive.css">
        <!-- fevicon -->
        <link rel="icon" href="images/fevicon.png" type="image/gif" />
        <!-- Scrollbar Custom CSS -->
        <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
        <!-- Tweaks for older IEs-->
        <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->

    </head> 

    <body>

        <div id="booktable" class="contact">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="titlepage">
                            <h3>Sign In</h3>
                        </div>
                    </div>
                </div>
                <div class="white_bg">
                    <div class="row">
    
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="contact">
    
                                <form method="post" action="">
                                    <div class="row">
                                        
                                        <div class="col-sm-12"><br><br><br><br><br>
                                            <input class="contactus" type="text" name="username" required minlength="3" autofocus placeholder="Username"><br>
                                        </div>
                                        
                                        <div class="col-sm-12">
                                            <input class="contactus" type="password" name="password" required placeholder="Password"><br>
                                        </div>
                                        <div class="col-sm-12">
                                            <u><a href="password.php">Forgot password?</a></u>
                                        </div>
                                        
                                        
                                        <div class="col-sm-12"><br><br>
                                            <button name="signin" class="send">Sign in</button><br><br><br><br><br>
                                        </div>
                                        
                                        <div class="col-sm-12">
                                            <u><a href="addUser.php">Need an account? Sign up!</a></u>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="rable-box">
                                <figure><img src="images/lev2.jpeg" alt="#" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




                    

    </body>
 
</html>
