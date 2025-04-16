<!DOCTYPE html>
<html lang="en">
<head>
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
    <title>comments management</title>
</head>
<body>
    <div id="manageComments" class="contact">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage">
                        <h3>Manage Comments</h3>
                    </div>
                </div>
            </div>

            <div class="white_bg">
                <div class="row">
                    <!-- Comments Table -->
                    <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">
                        <div class="contact">
                            <?php if (!empty($comments)): ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Content</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($comments as $comment): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($comment['content']) ?></td>
                                                <td><?= htmlspecialchars($comment['created_at']) ?></td>
                                                <td>
                                                    <a href="index.php?action=delete_comment&comment_id=<?= $comment['id'] ?>&post_id=<?= $comment['post_id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p>No comments found for this post.</p>
                            <?php endif; ?>

                            <a href="index.php?action=admin" class="btn btn-secondary mt-2">Back to Admin Panel</a>
                        </div>
                    </div>

                    <!-- Image Column -->
                    <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 d-flex align-items-center">
                        <div class="rable-box w-100">
                            <figure><img src="assets2/images/lev2.jpeg" alt="#" style="width: 100%; border-radius: 10px;"></figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
