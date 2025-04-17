<?php

session_start(); 
require_once __DIR__.'/../../Controller/userController.php';

$userC = new userController();

if (
    isset($_POST['username'], $_POST['email'], $_POST['phone'], $_POST['birth_date'], $_POST['role'], $_POST['password']) &&
    !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['phone']) &&
    !empty($_POST['birth_date']) && !empty($_POST['role']) && !empty($_POST['password'])
) {

    $user = new user(
        null,
        $_POST['username'],
        $_POST['email'],
        $_POST['phone'],
        new DateTime($_POST['birth_date']),
        $_POST['role'],
        $_POST['password']
    );

    $userC->addUser($user);


    $newUser = $userC->getUserByEmail($_POST['email']);


    if ($newUser) {

        $_SESSION['user'] = $newUser;
        $_SESSION['user_id'] = $newUser['id'];


        header('Location: ../../View/frontOffice/index_signin.php');
        exit();
    } else {

        $error = 'User creation failed.';
    }
} else {

    $error = 'Please enter valid data.';
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
        <title>Sign up | FunFusion</title>
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

        <div id="booktable" class="contact">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="titlepage">
                            <h3>JOIN US NOW</h3>
                        </div>
                    </div>
                </div>
                <div class="white_bg">
                    <div class="row">
    
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="contact">
    
                                <form id="forms" action="" method="POST">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <input class="contactus" type="text" id="username" name="username" autofocus placeholder="Username">
                                            <!--<p id="pUsername" hidden class="red">Username must contain at least 3 characters.</p><br>-->
                                            <span id="username_error"></span><br>
                                        </div>

                                        <div class="col-sm-12">
                                            <input class="contactus" type="text" id="email" name="email" placeholder="Email">
                                            <span id="email_error"></span><br>
                                            <!--<p id="pEmail" hidden class="red">Please enter a valid email address</p><br>-->
                                        </div>

                                        <div class="col-sm-12">
                                            <input class="contactus" type="number" id="phone" name="phone" placeholder="Phone Number">
                                            <!--<p id="pPhone" hidden class="red">Please enter a valid phone number</p><br>-->
                                            <span id="phone_error"></span><br>
                                        </div>

                                        <div class="col-sm-12">
                                            <label for="birth_date">Date of Birth</label>
                                            <input class="contactus" type="date" id="birth_date" name="birth_date" placeholder="Date of Birth">
                                            <!--<p id="pDateb" hidden class="red">Please enter your date of birth.</p><br>-->
                                            <span id="birth_date_error"></span><br>
                                        </div>

                                        <div class="col-sm-12">
                                            <select hidden id="role" name="role" class="contactus" placeholder="Role">
                                                <option value="Client" selected>Client</option>
                                            </select>
                                            <!--<p id="pRole" hidden class="red">Please select a role</p><br>-->
                                        </div>

                                        <div class="col-sm-12">
                                            <input class="contactus" type="password" id="password" name="password" placeholder="Password">
                                            <span id="password_error"></span><br>
                                            <!--<p id="pPassword" hidden class="red">Password must be at least 10 characters long and include numbers and special characters (. - _ ! % ?)</p><br>-->
                                        </div>
                                        
                                        <div class="col-sm-12">
                                            <button type="submit" class="send">Start Now</button><br><br>
                                        </div>

                                        <div class="col-sm-12">
                                            <u><a href="signIn.php">Have an account? Sign in!</a></u>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="rable-box">
                                <figure><img src="images/lev2.jpeg" alt="#levelUp" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <script src="js/addUser.js"></script>         

    </body>
 
</html>

