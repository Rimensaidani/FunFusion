<?php
// Manually include dependencies if accessed directly
if (!isset($resources) || !isset($reservations)) {
    include_once '../models/Resource.php';
    include_once '../models/Reservation.php';
    include_once '../config/db.php';
    include_once '../controllers/ResourceController.php';
    include_once '../controllers/ReservationController.php';

    $database = new Database();
    $db = $database->getConnection();

    $resourceController = new ResourceController($db);
    $reservationController = new ReservationController($db);

    $resources = $resourceController->getAllResources();
    $reservations = $reservationController->getAllReservations();
}
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
   <!-- Add in <head> -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.1/aos.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.1/aos.js"></script>


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
        .modal {
    z-index: 1055;
    color :black;
}
.modal-content * {
    color: black !important;
}




        
       
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
#resource_id {
        background-color: #ffffff; /* White background */
        border: 1px solid #ced4da; /* Border color */
        border-radius: 0.25rem; /* Rounded corners */
        padding: 0.375rem 0.75rem; /* Padding for the select */
        font-size: 16px; /* Font size */
        color: #333; /* Dark font color for visibility */
        transition: border-color 0.2s; /* Smooth border color transition */
    }

    /* Change border color on focus */
    #resource_id:focus {
        border-color: #007bff; /* Border color when focused */
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Add shadow effect */
        outline: none; /* Remove default outline */
    }

    /* Style for the options */
    #resource_id option {
        color: #333; /* Ensure the text color is dark for visibility */
        background-color: #ffffff; /* White background for options */
    }

    /* Change option background on hover */
    #resource_id option:hover {
        background-color: #f1f1f1; /* Light gray background on hover */
        color: #333; /* Dark font color on hover */
    }

    </style>
</head>

<body >
    <div class="css-loader">
        <div class="loader-inner line-scale d-flex align-items-center justify-content-center"></div>
    </div>
   
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
            <a class="navbar-brand" href="index.html"><img src="assets/images/logo_site.png" height="65" alt="logo FunFusion"></a><h2>FunFusion</h2>
            <div class="group d-flex align-items-center">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <a class="login-icon d-sm-none" href="#"><i class="fa fa-user"></i></a>
                <a class="cart" href="#"><i class="fa fa-shopping-cart"></i></a>
            </div>
            <a class="search-icon d-none d-md-block" href="#"><i class="fa fa-search"></i></a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Portfolio</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?action=admin">Activities</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contact Us</a></li>
                </ul>
                <form class="bg-white search-form" method="get" id="searchform">
                    <div class="input-group">
                        <input class="field form-control" id="s" name="s" type="text" placeholder="Search">
                        <span class="input-group-btn">
                            <input class="submit btn btn-primary" id="searchsubmit" name="submit" type="submit" value="Search">
                        </span>
                    </div>
                </form>
            </div>
        </nav>
    </div>
</header>

<section class="hero py-5">
  <div class="container mt-4">
    <h2 class="text-center mb-4 text-white">All Resources</h2>

    <div class="row">
        <?php foreach ($resources as $resource): ?>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($resource['nom']) ?></h5>
                        <p class="card-text">
                            <strong>Type:</strong> <?= htmlspecialchars($resource['type']) ?><br>
                            <strong>Description:</strong> <?= htmlspecialchars($resource['description']) ?><br>
                            <strong>Status:</strong> <?= htmlspecialchars($resource['status']) ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h2 class="text-center mt-5 mb-4 text-white">Make a Reservation</h2>
    <form method="POST" action="index.php?action=create_reservation" onsubmit="return validateReservationForm();">
    <!-- Resource Selection -->
    <div class="form-group">
        <label for="resource_id">Resource</label>
        <select name="resource_id" id="resource_id" class="form-control" >
            <option value="">-- Select Resource --</option>
            <?php foreach ($resources as $resource): ?>
                <option value="<?= $resource['id'] ?>"><?= htmlspecialchars($resource['nom']) ?></option>
            <?php endforeach; ?>
        </select>
        <div class="text-danger" id="resource_id_error"></div>
    </div>

    <!-- Location -->
    <div class="form-group">
        <label for="location">Location</label>
        <input type="text" name="location" id="location" class="form-control" >
        <div class="text-danger" id="location_error"></div>
    </div>

    <!-- Date Reservation -->
    <div class="form-group">
        <label for="date_reservation">Date Reservation</label>
        <input type="date" name="date_reservation" id="date_reservation" class="form-control" >
        <div class="text-danger" id="date_reservation_error"></div>
    </div>

    <!-- Date Retour -->
    <div class="form-group">
        <label for="date_retour">Date Retour</label>
        <input type="date" name="date_retour" id="date_retour" class="form-control" >
        <div class="text-danger" id="date_retour_error"></div>
    </div>

    <!-- User -->
    <div class="form-group">
        <label for="user">User</label>
        <input type="text" name="user" id="user" class="form-control" >
        <div class="text-danger" id="user_error"></div>
    </div>

    <!-- Etat -->
    <div class="form-group">
        <label for="etat">Etat</label>
        <select name="etat" id="etat" class="form-control" >
            <option value="">-- Select Status --</option>
            <option value="reserved">Reserved</option>
            <option value="pending">Pending</option>
            <option value="returned">Returned</option>
        </select>
        <div class="text-danger" id="etat_error"></div>
    </div>

    <!-- Payment Amount -->
    <div class="form-group">
        <label for="amount">Payment Amount</label>
        <input type="number" name="amount" id="amount" class="form-control" required>
        <div class="text-danger" id="amount_error"></div>
    </div>

    <button type="submit" class="btn btn-primary">Submit Reservation</button>
</form>

    <h2 class="text-center mt-5 mb-4">All Reservations</h2>
    <form action="" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="query" class="form-control" placeholder="Search by User or Location" required>
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Resource name</th>
            <th>Location</th>
            <th>Date Reservation</th>
            <th>Date Retour</th>
            <th>User</th>
            <th>Etat</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reservations as $res): ?>
            <tr>
                <td><?= $res['id'] ?></td>
                <td><?= htmlspecialchars($resourceController->getResourceNameById($res['resource_id'])) ?></td>
                <td><?= htmlspecialchars($res['location']) ?></td>
                <td><?= $res['date_reservation'] ?></td>
                <td><?= $res['date_retour'] ?></td>
                <td><?= htmlspecialchars($res['user']) ?></td>
                <td><?= htmlspecialchars($res['etat']) ?></td>
                <td>
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $res['id'] ?>">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <a href="index.php?action=delete_reservation&id=<?= $res['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php foreach ($reservations as $res): ?>
    <div class="modal fade" id="editModal<?= $res['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $res['id'] ?>" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="index.php?action=update_reservation" method="POST" style="color: black;">
            <input type="hidden" name="resource_id" value="<?= htmlspecialchars($res['resource_id'] ?? '') ?>">
                <input type="hidden" name="id" value="<?= $res['id'] ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel<?= $res['id'] ?>">Edit Reservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($res['location']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date Reservation</label>
                        <input type="date" name="date_reservation" class="form-control" value="<?= $res['date_reservation'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date Retour</label>
                        <input type="date" name="date_retour" class="form-control" value="<?= $res['date_retour'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">User</label>
                        <input type="text" name="user" class="form-control" value="<?= htmlspecialchars($res['user']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Etat</label>
                        <select name="etat" class="form-select" required>
                            <option value="reserved" <?= ($res['etat'] === 'reserved') ? 'selected' : '' ?>>Reserved</option>
                            <option value="returned" <?= ($res['etat'] === 'returned') ? 'selected' : '' ?>>Returned</option>
                            <option value="pending" <?= ($res['etat'] === 'pending') ? 'selected' : '' ?>>Pending</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; ?>

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
    <script>
function validateReservationForm() {
    console.log("Validation started"); 
    const resource = document.getElementById('resource_id');
    const location = document.getElementById('location');
    const dateReservation = document.getElementById('date_reservation');
    const dateRetour = document.getElementById('date_retour');
    const user = document.getElementById('user');
    const etat = document.getElementById('etat');

    let isValid = true;
    let errorMessages = [];

    // Validate Resource selection
    if (!resource.value) {
        errorMessages.push("Resource is required.");
        isValid = false;
    }

    // Validate Location
    if (!location.value.trim()) {
        errorMessages.push("Location is required.");
        isValid = false;
    }

    // Validate Dates
    if (!dateReservation.value) {
        errorMessages.push("Reservation date is required.");
        isValid = false;
    }

    if (!dateRetour.value) {
        errorMessages.push("Return date is required.");
        isValid = false;
    } else if (dateRetour.value < dateReservation.value) {
        errorMessages.push("Return date must be after reservation date.");
        isValid = false;
    }

    // Validate User
    if (!user.value.trim()) {
        errorMessages.push("User is required.");
        isValid = false;
    }

    // Validate Status
    if (!etat.value) {
        errorMessages.push("Etat is required.");
        isValid = false;
    }

    // Show error messages
    if (!isValid) {
        alert(errorMessages.join("\n"));
    }

    return isValid;
}
</script>   

    <script src="assets/js/jquery-3.3.1.js"></script>
    <!--Plugins-->
    <script src="assets/js/bootstrap.bundle.js"></script>
    <script src="assets/js/loaders.css.js"></script>
    <script src="assets/js/aos.js"></script>
    <script src="assets/js/swiper.min.js"></script>
    <script src="assets/js/lightgallery-all.min.js"></script>
    <!--Template Script-->
    <script src="assets/js/main.js"></script>
    <script>
  document.querySelectorAll('.btn[data-bs-toggle="modal"]').forEach(button => {
    button.addEventListener('click', () => {
     
      setTimeout(() => {
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.style.overflow = 'auto';
      }, 200); 
    });
  });
</script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</html>