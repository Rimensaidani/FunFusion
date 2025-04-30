<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>FunFusion</title>

    <!-- Stylesheets & Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i%7CRajdhani:400,600,700"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/font-awesome/font-awesome.css">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/aos/aos.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">

    <style>
        body {
            background-image: url('assets/images/back_bg.jpg'); /* Set your background image here */
            background-size: cover;
            background-position: center;
            font-family: 'Open Sans', sans-serif;
        }

        .main-content {
            background-color: rgba(255, 255, 255, 0.9); /* White background with transparency */
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            margin-top: 20px; /* Add space from the header */
        }

        header {
            position: relative; /* Change from absolute to relative to sit within document flow */
            z-index: 1000; /* Keep it on top of other content */
            padding-bottom: 20px; /* Optional: Add padding to the bottom for spacing */
        }

        .nav-item {
            margin-right: 20px;
        }

        .navbar-brand img {
            height: 60px; /* Resize logo if needed */
        }

        .style-input {
            border: 0.2px solid black; /* Solid black border */
            border-radius: 4px; /* Slightly rounded corners (optional) */
            box-shadow: none; /* Remove Bootstrap's default shadow */
            color: black !important; /* Force text color inside the input */
            background-color: white; /* Set background to white for contrast */
        }
    </style>
</head>

<body>
    <!-- Loader Start -->
    <div class="css-loader">
        <div class="loader-inner line-scale d-flex align-items-center justify-content-center"></div>
    </div>
    <!-- Loader End -->

    <!-- Header Start -->
    <header class="w-100">
        <div class="container">
            <div class="top-header d-none d-sm-flex justify-content-between align-items-center">
                <div class="contact">
                    <a href="tel:+1234567890" class="tel"><i class="fa fa-phone" aria-hidden="true"></i>+2162567890</a>
                    <a href="mailto:info@yourmail.com"><i class="fa fa-envelope" aria-hidden="true"></i>FunFusion@gmail.com</a>
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
                <a class="navbar-brand" href="index.php">
                    <img src="assets/images/logo_site.png" alt="logo FunFusion">
                </a>
                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="index.php?action=home">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?action=Activite_Reelle">Manage Activités Réelles</a></li>
                        <li class="nav-item"><a class="nav-link" href="?action=manage_categories">Manage Categories</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?action=Inscription_Activite">reservation</a></li>


                    </ul>
                </div>
            </nav>
        </div>
    </header>
    <!-- Header End -->

    <!-- Main Content Start -->
    <main class="container my-5 main-content">
    <h2 class="my-4 text-center">Welcome to FunFusion</h2>

    <!-- Add New Activity Form -->
    <div class="row mb-5">
            <div class="col-md-6">
                <h4>Add New Activité Réelle</h4>
                <form action="index.php?action=home" method="POST"> <!-- Action updated here -->
                    <div class="form-group">
                        <label for="titre">Activity Title</label>
                        <input type="text" class="form-control" name="titre" id="titre" placeholder="Activity Title" required>
                    </div>
                    <div class="form-group">
                        <label for="lieu">Location</label>
                        <input type="text" class="form-control" name="lieu" id="lieu" placeholder="Location" required>
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" name="date" id="date" required>
                    </div>
                    <div class="form-group">
                        <label for="categorie_id">Category</label>
                        <select name="categorie_id" id="categorie_id" class="form-control" required>
                            <option value="">-- Select Category --</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id_categorie'] ?>"><?= htmlspecialchars($category['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Add Activité Réelle</button>
                </form>
            </div>
        </div>


    <!-- Activities List -->
    <div class="row">
        <div class="col-md-12">
            <h4>All Activités Réelles</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped bg-light">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Location</th>
                            <th>Date</th>
                           <th>Categorie</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($activities)): ?>
                                <?php foreach ($activities as $activity): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($activity['id_activite']); ?></td>
                                        <td><?= htmlspecialchars($activity['titre']); ?></td>
                                        <td><?= htmlspecialchars($activity['lieu']); ?></td>
                                        <td><?= htmlspecialchars($activity['date']); ?></td>
                                        <td><?= htmlspecialchars($activity['categorie_nom']); ?></td> <!-- Add category name -->
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center">No activities found.</td></tr>
                            <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>


    <!-- Main Content End -->

    <!-- Footer Start -->
    <footer>
        <div class="footer-widgets">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-xl-3">
                        <div class="single-widget contact-widget" data-aos="fade-up" data-aos-delay="0">
                            <h6 class="widget-title">Contact Us</h6>
                            <p>By subscribing to our mailing list, you will always be updated with the latest news from us.</p>
                            <div class="media">
                                <i class="fa fa-map-marker"></i>
                                <div class="media-body ml-3">
                                    <h6>Address</h6>
                                    Level 13, 2 Elizabeth St,<br>
                                    Melbourne, Victoria 3000 Australia
                                </div>
                            </div>
                            <div class="media">
                                <i class="fa fa-phone"></i>
                                <div class="media-body ml-3">
                                    <h6>Call us</h6>
                                    <a href="tel:+610791803458">+61 (0) 7 9180 3458</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="foot-note">
            <div class="container">
                <div class="footer-content text-center text-lg-left d-lg-flex justify-content-between align-items-center">
                    <p class="mb-0">Copyright &copy; FunFusion 2024</p>
                    <p class="mb-0"><a href="#">Terms Of Use</a><a href="#">Privacy & Security Statement</a></p>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer End -->

    <!-- Load JS -->
    <script src="validation.js"></script>
    <script src="assets/js/jquery-3.3.1.js"></script>
    <script src="assets/js/bootstrap.bundle.js"></script>
    <script src="assets/js/aos.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>