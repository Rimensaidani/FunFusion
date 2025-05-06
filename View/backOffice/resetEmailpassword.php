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
        <title>Reset Password |FunFusion</title>
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
                            <h3>New Password</h3>
                        </div>
                    </div>
                </div>
                <div class="white_bg">
                    <div class="row">
    
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="contact">
    
                                <form id="myForm" method="post" action="../../Controller/newpasswordEmail.php">
                                    <div class="row">
                                        <div class="col-sm-12"><br>
                                        <p>Please enter your new password</p>
                                        <p>and click reset</p>
                                        </div>

                                            <input type="hidden" name="phone" value="<?php echo $_SESSION['reset_phone']; ?>">

                                        <div class="col-sm-12"><br><br><br>
                                            <label for="new_password">New Password:</label><br>
                                            <input type="password" class="contactus" id="new_password" name="new_password" placeholder="New Password"><br>
                                            <span id="password_error"></span>
                                        </div>

                                        <div class="col-sm-12">
                                            <label for="confirm_password">Confirm Password:</label><br>
                                            <input type="password" class="contactus" id="confirm_password" name="confirm_password" placeholder="Confirm Password"><br>
                                            <span id="confirm_password_error"></span><br>
                                        </div>
                                        
                                        
                                        <div class="col-sm-12"><br>
                                            <button name="reset_password" class="send" type="submit">Verify</button><br><br><br><br><br>
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
    <script src="js/newPaasword.js"></script>
 
</html>
