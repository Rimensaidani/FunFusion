<?php
$database = new Database();
$db = $database->getConnection();
$postController = new PostController($db);

$postsWithCommentCount = $postController->getPostsWithCommentCount();

$labels = [];
$data = [];
foreach ($postsWithCommentCount as $post) {
    $labels[] = htmlspecialchars($post['title']);
    $data[] = (int)$post['comment_count'];
}
?>

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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <!-- Tweaks for older IEs-->
        <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Admin Panel</title>
</head>
<body>
    <div id="adminPanel" class="contact">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage">
                        <h3>ADMIN PANEL</h3>
                    </div>
                </div>
            </div>

            <div class="white_bg">
                <div class="row">
                    <!-- Form Column -->
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        <div class="contact">
                            <!-- Add Post Form -->
                            <h4 class="mb-3">Add New Post</h4>
                            <form method="POST" action="index.php?action=create_post&source=admin">                                <div class="row">
                                    <div class="col-sm-12 mb-2">
                                        <input class="contactus" type="text" name="post_title" placeholder="Post Title" required>
                                    </div>
                                    <div class="col-sm-12 mb-2">
                                        <textarea class="contactus" name="post_content" placeholder="Post Content" required></textarea>
                                    </div>
                                    <div class="col-sm-12">
                                        <button class="send" type="submit">Add Post</button>
                                    </div>
                                </div>
                            </form>

                            <!-- Success / Error Messages -->
                            <?php if (isset($_GET['add']) && $_GET['add'] == 'success'): ?>
                                <div class="alert alert-success mt-3">Post added successfully!</div>
                            <?php elseif (isset($_GET['add']) && $_GET['add'] == 'error'): ?>
                                <div class="alert alert-danger mt-3">Unable to add post. Please try again.</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Image Column -->
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        <div class="rable-box">
                            <figure><img src="assets2/images/lev2.jpeg" alt="#" style="width: 100%;" /></figure>
                        </div>
                    </div>
                </div>

                <hr class="my-4 w-100">

                <!-- Posts Table -->
                <div class="row">
                    <div class="col-12">
                        <h4 class="mb-3">Posts</h4>
                        <?php
                        include_once '../controllers/PostController.php';
                        $postController = new PostController();
                        $posts = json_decode($postController->getAllPosts(), true);
                        ?>

                        <?php if (empty($posts)): ?>
                            <p>No posts found.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Title</th>
                                            <th>Content</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($posts as $post): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($post['title']); ?></td>
                                                <td><?php echo htmlspecialchars($post['content']); ?></td>
                                                <td>
                                                    <a href="index.php?action=edit_post&id=<?php echo $post['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="index.php?action=delete_post&post_id=<?php echo $post['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                                    <a href="index.php?action=comments&post_id=<?php echo $post['id']; ?>" class="btn btn-info btn-sm">Manage Comments</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Home Button -->
    <a href="index.php" class="home-float-btn" title="Go to Home">
        <i class="fas fa-home"></i>
    </a>
    <div id="chartContainer" style="width: 400px; height: 400px; overflow: hidden;">
    <canvas id="commentPieChart" width="400" height="400"></canvas>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const config = {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Comments per Post',
                data: <?php echo json_encode($data); ?>,
                backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)'],
                borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'],
                borderWidth: 1
            }]
        },
        options: { 
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Comments on Posts' }
            }
        }
    };

    new Chart(document.getElementById('commentPieChart'), config);
</script>

    <!-- Font Awesome -->
    

    <!-- Floating Button Style -->
    <style>
        .home-float-btn {
            position: fixed;
            bottom: 25px;
            right: 25px;
            background-color: #343a40;
            color: white;
            padding: 12px 16px;
            border-radius: 50%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            font-size: 20px;
            z-index: 999;
            transition: all 0.3s ease;
        }

        .home-float-btn:hover {
            background-color: #212529;
            color: #ffc107;
            transform: scale(1.1);
            text-decoration: none;
        }

        .home-float-btn i {
            vertical-align: middle;
        }
    </style>
</body>




</html>