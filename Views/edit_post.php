<!DOCTYPE html>
<html lang="en">
<head>
       <!-- basic -->
       <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- mobile metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="initial-scale=1, maximum-scale=1">
        <!-- site metas -->
        
        <meta name="keywords" content="">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- bootstrap css -->
        <link rel="stylesheet" href="assets2/css/bootstrap.min.css">
        <!-- style css -->
        <link rel="stylesheet" href="assets2/css/style.css">
        <!-- Responsive-->
        <link rel="stylesheet" href="assets2/css/responsive.css">
        <!-- fevicon -->
        <link rel="icon" href="assets2/images/fevicon.png" type="image/gif" />
        <!-- Scrollbar Custom CSS -->
        <link rel="stylesheet" href="assets2/css/jquery.mCustomScrollbar.min.css">
        <!-- Tweaks for older IEs-->
        <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Edit post</title>
</head>
<body>
    <div id="editPost" class="contact">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage">
                        <h3>Edit Post</h3>
                    </div>
                </div>
            </div>

            <div class="white_bg">
                <div class="row">
                    <!-- Form Column -->
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        <div class="contact">
                            <form method="POST" action="index.php?action=edit_post&id=<?php echo $post['id']; ?>">
                                <div class="row">
                                    <div class="col-sm-12 mb-3">
                                        <label>Title:</label>
                                        <input class="contactus" type="text" name="post_title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                                    </div>
                                    <div class="col-sm-12 mb-3">
                                        <label>Content:</label>
                                        <textarea class="contactus" name="post_content" rows="5" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                                    </div>
                                    <div class="col-sm-12">
                                        <button class="send" type="submit">Update Post</button>
                                        <a href="index.php?action=admin" class="btn btn-secondary ml-2">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Image Column -->
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        <div class="rable-box">
                            <figure><img src="assets2/images/lev2.jpeg" alt="Image" style="width: 100%; border-radius: 10px;" /></figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>


</html>
