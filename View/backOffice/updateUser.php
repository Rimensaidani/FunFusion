<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

require_once __DIR__.'/../../Controller/userController.php';
require_once __DIR__.'/../../Model/user.php';

$userC = new userController();

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $birth_date = new DateTime($_POST['birth_date']);
    $role = $_POST['role'];
    $password = $_POST['password'];

    $user = new user((int)$id, $username, $email, $phone, $birth_date, $role, $password);

    $userC->updateUser($user, $id);



    $_SESSION['user'] = $userC->showUser($id);


    header('Location: account.php');
    exit();
}

$user = $userC->showUser($_SESSION['user_id']);
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
        <title>My account | FunFusion</title>
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

        <link href="css/controle-saisie.css" rel="stylesheet">

    </head> 

    <body>

        <div id="booktable" class="contact2" >
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="titlepage">
                            <h3>MY ACCOUNT</h3>
                        </div>
                    </div>
                </div>
                <div class="white_bg">
                    <div class="row">
    
                        <div class="col-xl-6 col-lg-6 col-md-8 col-sm-10 mx-auto">
                            <div class="contact">
    
                                <form id="forms" action="updateUser.php" method="POST">
                                    <div class="row">

                                        <div class="col-sm-12">
                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                        </div>

                                        <div class="col-sm-12">
                                            <label for="username">Username</label>
                                            <input class="contactus" type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                                            <span id="username_error"></span><br>
                                        </div>

                                        <div class="col-sm-12">
                                            <label for="email">Email</label>
                                            <input class="contactus" type="text" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                                            <span id="email_error"></span><br>
                                        </div>

                                        <div class="col-sm-12">
                                            <label for="phone">Phone</label>
                                            <input class="contactus" type="number" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                                            <span id="phone_error"></span><br>
                                        </div>

                                        <div class="col-sm-12">
                                            <label for="birth_date">Date of Birth</label>
                                            <input class="contactus" type="date" id="birth_date" name="birth_date" value="<?php echo htmlspecialchars($user['birth_date']); ?>" >
                                            <span id="birth_date_error"></span><br>
                                        </div>

                                        <div class="col-sm-12">
                                            <input type="hidden" name="role" value="<?php echo htmlspecialchars($user['role']); ?>">
                                        </div>


                                        <div class="col-sm-12">
                                            <label for="password">Password</label>
                                            <input class="contactus" type="text" id="password" name="password" value="<?php echo htmlspecialchars($user['password']); ?>">
                                            <span id="role_password"></span><br>
                                        </div>
                                            <div class="col-sm-12 text-center">
                                                <button type="submit" id="update" name="update" class="send mx-2">Confirm</button>
                                                <button type="button" onclick="window.location.href='account.php'" class="send mx-2">Cancel</button>
                                            </div>
                                         

                                    </div>
                                </form>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>




        <script src="js/updateUser.js"></script>         

    </body>
 
</html>

