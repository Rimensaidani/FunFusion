<?php
// Database Connection and Post fetching logic
function censorContent($content) {
    // List of words to censor
    $prohibitedWords = ['Amin', 'pppp', 'nour', 'malek'];

    // Replace each prohibited word with asterisks (assuming the same length as the word)
    foreach ($prohibitedWords as $word) {
        $replacement = str_repeat('*', strlen($word)); // Create a string of asterisks of the same length
        $content = str_ireplace($word, $replacement, $content); // Case-insensitive replacement
    }

    return $content;
}

function getPosts($searchTerm = null) {
    $db = new Database(); // Create a new Database instance
    $conn = $db->getConnection(); // Get the DB connection

    // Prepare the SQL query
    $query = "SELECT * FROM posts"; // Assumed table name; modify if necessary

    if ($searchTerm) {
        // Add a WHERE clause if a search term is provided
        $query .= " WHERE title LIKE :searchTerm OR content LIKE :searchTerm";
    }

    $stmt = $conn->prepare($query); // Prepare the statement

    if ($searchTerm) {
        // Bind the search term parameter
        $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%');
    }

    $stmt->execute(); // Execute the statement
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all posts as associative array
}

// Handle the search
$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';
$posts = getPosts($searchQuery); // Fetch posts with the search term
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Document title -->
    <title>FunFusion</title>
    <!-- Stylesheets & Fonts -->
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i%7CRajdhani:400,600,700"
        rel="stylesheet">
    <!-- Plugins Stylesheets -->
    <link rel="stylesheet" href="assets/css/loader/loaders.css">
    <link rel="stylesheet" href="assets/css/font-awesome/font-awesome.css">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/aos/aos.css">
    <link rel="stylesheet" href="assets/css/swiper/swiper.css">
    <link rel="stylesheet" href="assets/css/lightgallery.min.css">
    <!-- Template Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Responsive Stylesheet -->
    <link rel="stylesheet" href="assets/css/responsive.css">
    
    <style>
        
       
        .post-card {
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    margin-bottom: 20px;
    background-color: rgba(255, 255, 255, 0.9); /* Add slight transparency */
    transition: transform 0.3s, box-shadow 0.3s;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1); /* Soft shadow */
}

.post-card:hover {
    transform: scale(1.02);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}

.post-title {
    color: #007bff;
    font-weight: bold;
}

.comment {
    background-color: rgba(248, 249, 250, 0.85); /* Soft light gray */
    border-left: 4px solid #007bff;
    margin-top: 10px;
    border-radius: 5px;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    transition: background-color 0.3s;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.form-container {
    background-color: rgba(248, 249, 250, 0.9); /* Slight transparency */
    padding: 20px;
    border-radius: 10px;
    border: 1px solid #007bff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.form-control {
    border: 1px solid #ccc;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: none;
}
.field {
    color: black; /* Text color */
    background-color: white; /* Background color for input */
}

.field::placeholder {
    color: black; /* Placeholder text color */
}
.btn-light, .btn-secondary {
    color: black !important;
}
.btn-secondary.show-all {
    color: white !important;
}
    </style>
</head>

<body>
<div class="css-loader">
        <div class="loader-inner line-scale d-flex align-items-center justify-content-center"></div>
    </div>
   
<header class=" w-100">
        <div class="container">
            <div class="top-header d-none d-sm-flex justify-content-between align-items-center">
                <div class="contact">
                    <a href="tel:+1234567890" class="tel"><i class="fa fa-phone" aria-hidden="true"></i>+2162567890</a>
                    <a href="mailto:info@yourmail.com"><i class="fa fa-envelope"
                            aria-hidden="true"></i>FunFusion@gmail.com</a>
                </div>
                <nav class="d-flex aic">
                    <a href="#" class="login"><i class="fa fa-user" aria-hidden="true"></i>Login</a>
                    <ul class="nav social d-none d-md-flex">
                        <li><a href="https://www.facebook.com/fh5co" target="_blank"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                    </ul>
                </nav>
            </div>
            <nav class="navbar navbar-expand-md navbar-light">
                <a class="navbar-brand" href="index.html"><img src="assets/images/logo_site.png" height="65" alt="logo FunFusion"></a><h2>    FunFusion</h2>
                <div class="group d-flex align-items-center">
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation"><span
                            class="navbar-toggler-icon"></span></button>
                    <a class="login-icon d-sm-none" href="#"><i class="fa fa-user"></i></a>
                    <a class="cart" href="#"><i class="fa fa-shopping-cart"></i></a>
                </div>
                <a class="search-icon d-none d-md-block" href="#"><i class="fa fa-search"></i></a>
                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?action=admin">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Portfolio</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Activities</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Contact Us</a></li>
                    </ul>
                   <!-- Updated Search Form with "Show All" Button -->
                   <form class="bg-white search-form mb-2" method="get" id="searchform">
    <div class="input-group">
        <input class="field form-control" id="s" name="query" type="text" placeholder="Search posts..." required style="color: black !important; background-color: white !important;">
        <span class="input-group-btn">
            <input class="submit btn btn-primary" id="searchsubmit" name="submit" type="submit" value="Search">
        </span>
    </div>
</form>

<!-- Always show the "Show All" button -->
<a href="index.php" class="btn btn-secondary" style="color: white; margin-top: 10px;">Show All</a>


                </div>
            </nav>
        </div>
    </header>   
    

    <section class="hero py-5">
  <div class="container mt-4">
    <h2 class="text-center mb-4 text-white">Forum Posts</h2>

    <!-- Add New Post Form -->
    <form method="POST" action="index.php?action=create_post&source=home" class="form-container mb-4 p-4 border rounded shadow-sm bg-light">
        <div class="form-group">
            <input style="color: black;" type="text" name="post_title" class="form-control" placeholder="Post Title" required>
        </div>
        <div class="form-group">
            <textarea style="color: black;" name="post_content" class="form-control" placeholder="Post Content" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Post</button>
    </form>

    <hr>

 
    <!-- Display All Posts -->
    <?php if (empty($posts)): ?>
        <p class="text-center">No posts found.</p>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
    <div class="post-card p-3 mb-4 border rounded shadow-sm bg-white">
    <h4 class="post-title text-primary"><?php echo htmlspecialchars(censorContent($post['title'])); ?></h4>
        <p><?php echo htmlspecialchars($post['content']); ?></p>

        <!-- Delete Button (only if current user is owner) -->
        <?php if (CURRENT_USER_ID === (int)$post['user_id']): ?>
            <form method="POST" action="index.php?action=delete_post" onsubmit="return confirm('Are you sure you want to delete this post?');" class="mb-2">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <button type="submit" class="btn btn-danger btn-sm">Delete Post</button>
            </form>
        <?php endif; ?>
        <?php if (CURRENT_USER_ID === (int)$post['user_id']): ?>
    <!-- Edit Button -->
    <a href="index.php?action=edit_post&id=<?php echo $post['id']; ?>" class="btn btn-warning btn-sm">Edit Post</a>

    
<?php endif; ?>

        <!-- Comments Section -->
        <h5 class="mt-3">Comments:</h5>
        <?php
            $comments = $commentController->getCommentsByPostId($post['id']); 
            if (empty($comments)): ?>
                <p>No comments yet.</p>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
        <div class="comment p-3 mb-2 bg-light border-left border-primary rounded">
            <p><?php echo htmlspecialchars(censorContent($comment['content'])); ?></p>

            <!-- Check if the current user is the owner of the comment -->
            <?php if (CURRENT_USER_ID === (int)$comment['user_id']): ?>
                <td>
                                                    <a href="index.php?action=delete_comment&comment_id=<?= $comment['id'] ?>&post_id=<?= $comment['post_id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                                                    <button data-id="<?= $comment['id'] ?>" data-content="<?= htmlspecialchars($comment['content']) ?>" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editCommentModal">Edit</button>
                                                </td>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

        <?php endif; ?>

        <!-- Add Comment Form -->
        <form method="POST" action="index.php?action=create_comment" class="mt-2">
            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
            <div class="form-group">
                <textarea style="color: black;" name="comment_text" class="form-control" placeholder="Add a comment..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Comment</button>
        </form>
    </div>
<?php endforeach; ?>

    <?php endif; ?>

  </div>
</section>

</body>
<footer>
        <!-- Widgets Start -->
        <div class="footer-widgets">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-xl-3">
                        <div class="single-widget contact-widget" data-aos="fade-up" data-aos-delay="0">
                            <h6 class="widget-tiltle">&nbsp;</h6>
                            <p>By subscribing to our mailing list you will always be update with the latest news from
                                us.
                            </p>
                            <div class="media">
                                <i class="fa fa-map-marker"></i>
                                <div class="media-body ml-3">
                                    <h6>Address</h6>
                                    Level 13, 2 Elizabeth St,<br>
                                    Melbourne, Victoria 3000 Australia
                                </div>
                            </div>
                            <div class="media">
                                <i class="fa fa-envelope-o"></i>
                                <div class="media-body ml-3">
                                    <h6>Have any questions?</h6>
                                    <a href="mailto:support@steelthemes.com">Support@Steelthemes.com</a>
                                </div>
                            </div>
                            <div class="media">
                                <i class="fa fa-phone"></i>
                                <div class="media-body ml-3">
                                    <h6>Call us & Hire us</h6>
                                    <a href="tel:+610791803458"> +61 (0) 7 9180 3458</a>
                                </div>
                            </div>
                            <div class="media">
                                <i class="fa fa-fax"></i>
                                <div class="media-body ml-3">
                                    <h6>Fax</h6>
                                    <a href="fax:911889047521432">(91) 188904752 1432</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="single-widget twitter-widget" data-aos="fade-up" data-aos-delay="200">
                            <h6 class="widget-tiltle">Fresh Tweets</h6>
                            <div class="media">
                                <i class="fa fa-twitter"></i>
                                <div class="media-body ml-3">
                                    <h6><a href="#">@Themes,</a> Html Version Out Now</h6>
                                    <span>10 Mins Ago</span>
                                </div>
                            </div>
                            <div class="media">
                                <i class="fa fa-twitter"></i>
                                <div class="media-body ml-3">
                                    <h6><a href="#">@Envato,</a> the best selling item of the day!</h6>
                                    <span>20 Mins Ago</span>
                                </div>
                            </div>
                            <div class="media">
                                <i class="fa fa-twitter"></i>
                                <div class="media-body ml-3">
                                    <h6><a href="#">@Collis,</a> We Planned to Update the Enavto Author Payment Method
                                        Soon!</h6>
                                    <span>10 Mins Ago</span>
                                </div>
                            </div>
                            <div class="media">
                                <i class="fa fa-twitter"></i>
                                <div class="media-body ml-3">
                                    <h6><a href="#">@SteelThemes,</a> Html Version Out Now</h6>
                                    <span>15 Mins Ago</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="single-widget recent-post-widget" data-aos="fade-up" data-aos-delay="400">
                            <h6 class="widget-tiltle">Latest Updates</h6>
                            <div class="media">
                                <a class="rcnt-img" href="#"><img src="assets/images/rcnt-post1.png"
                                        alt="Recent Post"></a>
                                <div class="media-body ml-3">
                                    <h6><a href="#">An engaging</a></h6>
                                    <p><i class="fa fa-user"></i>Mano <i class="fa fa-eye"></i> 202 Views</p>
                                </div>
                            </div>
                            <div class="media">
                                <a class="rcnt-img" href="#"><img src="assets/images/rcnt-post2.png"
                                        alt="Recent Post"></a>
                                <div class="media-body ml-3">
                                    <h6><a href="#">Statistics and analysis. The key to succes.</a></h6>
                                    <p><i class="fa fa-user"></i>Rosias <i class="fa fa-eye"></i> 20 Views</p>
                                </div>
                            </div>
                            <div class="media">
                                <a class="rcnt-img" href="#"><img src="assets/images/rcnt-post3.png"
                                        alt="Recent Post"></a>
                                <div class="media-body ml-3">
                                    <h6><a href="#">Envato Meeting turns into a photoshooting.</a></h6>
                                    <p><i class="fa fa-user"></i>Kien <i class="fa fa-eye"></i> 74 Views</p>
                                </div>
                            </div>
                            <div class="media">
                                <a class="rcnt-img" href="#"><img src="assets/images/rcnt-post4.png"
                                        alt="Recent Post"></a>
                                <div class="media-body ml-3">
                                    <h6><a href="#">An engaging embedded the video posts</a></h6>
                                    <p><i class="fa fa-user"></i>Robert <i class="fa fa-eye"></i> 48 Views</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="single-widget tags-widget" data-aos="fade-up" data-aos-delay="800">
                            <h6 class="widget-tiltle">Popular Tags</h6>
                            <a href="#">Amazing</a>
                            <a href="#">Design</a>
                            <a href="#">Photoshop</a>
                            <a href="#">Art</a>
                            <a href="#">Wordpress</a>
                            <a href="#">jQuery</a>
                        </div>
                        <div class="single-widget subscribe-widget" data-aos="fade-up" data-aos-delay="800">
                            <h6 class="widget-tiltle">Subscribe us</h6>
                            <p>Sign up for our mailing list to get latest updates and offers</p>
                            <form class="" method="get">
                                <div class="input-group">
                                    <input class="field form-control" name="subscribe" type="email"
                                        placeholder="Email Address">
                                    <span class="input-group-btn">
                                        <button type="submit" name="submit-mail"><i class="fa fa-check"></i></button>
                                    </span>
                                </div>
                            </form>
                            <p>We respect your privacy</p>
                            <ul class="nav social-nav">
                                <li><a href="https://www.facebook.com/fh5co" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Widgets End -->
        <!-- Foot Note Start -->
        <div class="foot-note">
            <div class="container">
                <div
                    class="footer-content text-center text-lg-left d-lg-flex justify-content-between align-items-center">
                    <p class="mb-0" data-aos="fade-right" data-aos-offset="0"> Copyright &copy; FunFusion 2024 <a href="https://freehtml5.co/multipurpose" target="_blank" class="fh5-link"></a></p>
                    <p class="mb-0" data-aos="fade-left" data-aos-offset="0"><a href="#">Terms Of Use</a><a
                            href="#">Privacy & Security
                            Statement</a></p>
                </div>
            </div>
        </div>
        <!-- Foot Note End -->
    </footer>
    <script src="assets/js/jquery-3.3.1.js"></script>
    <!--Plugins-->
    <script src="assets/js/bootstrap.bundle.js"></script>
    <script src="assets/js/loaders.css.js"></script>
    <script src="assets/js/aos.js"></script>
    <script src="assets/js/swiper.min.js"></script>
    <script src="assets/js/lightgallery-all.min.js"></script>
    <script src="assets/js/postValidation.js"></script>
    <script src="assets/js/commentValidation.js"></script>
    <!--Template Script-->
    <script src="assets/js/main.js"></script>
</html>