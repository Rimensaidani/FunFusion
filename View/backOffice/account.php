<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../frontOffice/index_signin.php'); 
    exit();
}
$user = $_SESSION['user'];

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
    
                                <form id="forms">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label for="username">Username</label>
                                            <input class="contactus" type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled >
                                        </div>

                                        <div class="col-sm-12">
                                            <label for="email">Email</label>
                                            <input class="contactus" type="text" id="email" name="email" value="<?php echo htmlspecialchars($user['email']);  ?>" disabled >
                                        </div>

                                        <div class="col-sm-12">
                                            <label for="phone">Phone</label>
                                            <input class="contactus" type="number" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']);  ?>" disabled>
                                        </div>

                                        <div class="col-sm-12">
                                            <label for="date_birth">Date of Birth</label>
                                            <input class="contactus" type="date" id="dateb" name="birth_date" value="<?php echo htmlspecialchars($user['birth_date']);  ?>" disabled>
                                        </div>


                                        <div class="col-sm-12">
                                            <label for="password">Password</label>
                                            <input class="contactus" type="text" id="password" name="password" value="<?php echo htmlspecialchars($user['password']);  ?>" disabled>
                                        </div>
                                        
                                        <div class="col-sm-12 text-center">  
                                            <!--<button type="button" onclick="window.location.href='updateUser.php'" class="send mx-2">Update</button>-->
                                            <a href="updateUser.php" class="send mx-2 btn btn-primary">Update</a>
                                            <a href="../frontOffice/index_signin.php" class="send mx-2 btn btn-primary">Back</a><br><br><br>
                                            <a href="deleteUser.php?id=<?php echo $user['id'] ?>" 
                                            onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">Delete account</a>
                                        </div>

                                        

                                    </div>
                                </form>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>



        

    </body>
 
</html>

