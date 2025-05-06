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
                            <h3>Enter Verification Code</h3>
                        </div>
                    </div>
                </div>
                <div class="white_bg">
                    <div class="row">
    
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="contact">
    
                                <form id="form" method="post" action="../../Controller/checkEmailcode.php">
                                    <div class="row">
                                        <div class="col-sm-12"><br><br><br><br>
                                        <p>Please enter your verification code</p>
                                        </div>


                                        <div class="col-sm-12"><br><br><br>
                                            <label class="contactus" for="code">Verification Code:</label><br>
                                            <input class="contactus" type="text" id="code" name="code" required autofocus placeholder="Code"><br>
                                        </div>

                                        <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
                                            <div class="col-sm-12" style="color: red; margin-bottom: 10px;">
                                                Incorrect code. Please try again.
                                             </div>
                                        <?php endif; ?>


                                        
                                        <div class="col-sm-12"><br>
                                            <button class="send" type="submit">Verify code</button><br><br><br><br><br>
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
