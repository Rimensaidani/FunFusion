
<?php 
//activites Virtuelles-----------------------------------------------------------------------------------------------------------------
session_start();
if (!isset($_SESSION['user_id'])) {
    // L'utilisateur n'est pas connecté, on le redirige
    header("Location: signin.php");
    exit();
}
    $_SESSION['id_utilisateur'] = $_SESSION['user_id'];

require_once '../../Controller/ActiviteVirtuelleController.php';
require_once '../../Model/ActiviteVirtuelle.php';
require_once '../../Model/Participation.php';
require_once '../../Controller/ParticipationController.php';


$controller = new ActiviteVirtuelleController();
$activites = $controller->listActivitesValidees(); 

$activitesValidees = array_filter($activites, function($a) {
    return $a['valide'] == 1;
});

$participationController = new ParticipationController();


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    if ($action === "add_participation") {
        $username = htmlspecialchars(trim($_POST["username"]));
        $age = intval($_POST["age"]);
        $id_activite = intval($_POST["id_activite"]);
        $id_utilisateur =  $_SESSION['user_id'];

        $participation = new Participation($username, $age, $id_activite, $id_utilisateur, $id_participation);
        $participationController->addParticipation($participation);
        header("Location: index_signin.php");
        exit();
    }

    if ($action === "update_participation") {
        $id_participation = intval($_POST["id_participation"]);
        $username = htmlspecialchars(trim($_POST["username"]));
        $age = intval($_POST["age"]);
        $id_activite = intval($_POST["id_activite"]);
        $id_utilisateur =  $_SESSION['user_id'];

        $participation = new Participation($username, $age, $id_activite, $id_utilisateur, $id_participation);
        $participationController->updateParticipation($participation);
        header("Location: index_signin.php");
        exit();
    }

    if ($action === "delete_participation") {
        $id_participation = intval($_POST["id_participation"]);
        $id_utilisateur =  $_SESSION['user_id'];
    
        $participationController->deleteParticipation($id_participation, $id_utilisateur);
    
        header("Location: index_signin.php");
        exit();
    }
    
}

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $searchTerm = strtolower(trim($_GET['search'])); // mettre tout en minuscule

    $activitesValidees = array_filter($activitesValidees, function($a) use ($searchTerm) {
        $titre = strtolower($a['titre']);
        $type = strtolower($a['type']);

        return (strpos($titre, $searchTerm) !== false) || (strpos($type, $searchTerm) !== false);
    });
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["titre"])  && $_POST['action'] === 'add') {
    $titre = htmlspecialchars(trim($_POST["titre"]));
    $type = htmlspecialchars(trim($_POST["type"]));

    $dateStr = $_POST["date_heure"];
    $plateforme = htmlspecialchars(trim($_POST["plateforme"]));
    $lien = htmlspecialchars(trim($_POST["lien"]));

    $date = !empty($dateStr) ? new DateTime($dateStr) : null;

    $id_createur =  $_SESSION['user_id'];
    $valide = 0; 

    $imageName = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $uploadDir = '../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $imageName = time() . '_' . basename($_FILES['image']['name']);
    $targetFile = $uploadDir . $imageName;
    move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
}

    
    $activite = new ActiviteVirtuelle($titre, $type, $date, $plateforme, $lien, $id_createur, $valide, null,$imageName);
   
    
    $controller = new ActiviteVirtuelleController();
    $controller->addActiviteVirtuelle($activite);

    
    header("Location: index_signin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = intval($_POST['id_activite']);
    $titre = htmlspecialchars(trim($_POST['titre']));
    $type = htmlspecialchars(trim($_POST['type']));
    $date = new DateTime($_POST['date_heure']);
    $plateforme = htmlspecialchars(trim($_POST['plateforme']));
    $lien = htmlspecialchars(trim($_POST['lien']));
    $id_createur =  $_SESSION['user_id'];
    $valide = 0;

    $imageName = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $uploadDir = '../uploads/';
    $imageName = time() . '_' . basename($_FILES['image']['name']);
    $targetFile = $uploadDir . $imageName;
    move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
} else {
    // récupérer ancienne image
    $stmt = config::getConnexion()->prepare("SELECT image FROM activites_virtuelles WHERE id_activite = ?");
    $stmt->execute([$id]);
    $imageName = $stmt->fetchColumn();
}


    $activite = new ActiviteVirtuelle($titre, $type, $date, $plateforme, $lien, $id_createur, $valide, $id,$imageName);
    $controller->updateActiviteVirtuelle($activite, $id);

    header("Location: index_signin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    
    $id = intval($_POST['id_activite']);
    $controller->deleteActiviteVirtuelle($id);
    header("Location: index_signin.php");
    exit();
}



// Filtrage par type 
if (isset($_GET['filter_type'])) {
    $activitesValidees = $controller->filterByType($_GET['filter_type']);
}

// Tri par date 
if (isset($_GET['sort_date'])) {
    $order = $_GET['sort_date'] == 'oldest' ? 'ASC' : 'DESC';
    $activitesValidees = $controller->sortByDate($order);
}

//----------------------------------------------------------------------------------------------------------------------------------

?>

<!doctype html>
<html lang="en">
    

<head>
    <!--
    /   Multipurpose: Free Template by FreeHTML5.co
    /   Author: https://freehtml5.co
    /   Facebook: https://facebook.com/fh5co
    /   Twitter: https://twitter.com/fh5co
    -->
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
        input, select, textarea {
    background-color: #ffffff !important;
    color: #000000 !important;
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
    <header class="position-absolute w-100">
        <div class="container">
            <div class="top-header d-none d-sm-flex justify-content-between align-items-center">
                <div class="contact">
                    <a href="tel:+1234567890" class="tel"><i class="fa fa-phone" aria-hidden="true"></i>+2162567890</a>
                    <a href="mailto:info@yourmail.com"><i class="fa fa-envelope"
                            aria-hidden="true"></i>FunFusion@gmail.com</a>
                </div>
                <nav class="d-flex aic">
                    <a href="../backOffice/account.php" class="login"><i class="fa fa-user" aria-hidden="true"></i>My account</a>
                    <a href="../../Controller/logout.php" class="login"><img src="assets/images/logout24.png" height="14" alt="logout icon">Log Out</a>

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
                        <li class="nav-item"><a class="nav-link" href="#choice">Our Perks</a></li>
                        <li class="nav-item"><a class="nav-link" href="#about">About Us</a></li>
                        <li class="nav-item"><a class="nav-link" href="#virtual-activities">Virtual Activities</a></li>
                        <li class="nav-item"><a class="nav-link" href="#community">Community</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Contact Us</a></li>
                    </ul>
                    <form class="bg-white search-form" method="get" id="searchform">
                        <div class="input-group">
                            <input class="field form-control" id="s" name="s" type="text" placeholder="Search">
                            <span class="input-group-btn">
                                <input class="submit btn btn-primary" id="searchsubmit" name="submit" type="submit"
                                    value="Search">
                            </span>
                        </div>
                    </form>
                </div>
            </nav>
        </div>
    </header>

    <!-- Header End -->
    <!-- Hero Start -->
    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-12 offset-md-1 col-md-11">
                    <div class="swiper-container hero-slider">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide slide-content d-flex align-items-center">
                                <div class="single-slide">
                                    <h1 data-aos="fade-right" data-aos-delay="200">Welcome to<br>FunFusion
                                    </h1>
                                    <p data-aos="fade-right" data-aos-delay="700">the perfect platform to connect with people who share your interests, <br>whether for outdoor activities or virtual experiences.
                                    </p>
                                    <a data-aos="fade-right" data-aos-delay="900" href="#" class="btn btn-primary">See More</a>
                                    <!--<a data-aos="fade-right" data-aos-delay="900" href="../backOffice/addUser.html" class="btn btn-primary">sign up</a>-->
                                </div>
                            </div>
                            <div class="swiper-slide slide-content d-flex align-items-center">
                                <div class="single-slide">
                                    <h1 data-aos="fade-right" data-aos-delay="200">Welcome to<br>FunFusion
                                    </h1>
                                    <p data-aos="fade-right" data-aos-delay="600">FunFusion makes it easy to plan activities that match your preferences,<br> both online and in real life! 
                                    </p>
                                    <a data-aos="fade-right" data-aos-delay="900" href="#" class="btn btn-primary">See More</a>
                                    <!--<a data-aos="fade-right" data-aos-delay="900" href="../backOffice/signIn.html" class="btn btn-primary">sign in</a>-->
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div> 
            <!-- Add Control -->
            <span class="arr-left"><i class="fa fa-angle-left"></i></span>
            <span class="arr-right"><i class="fa fa-angle-right"></i></span>
        </div>
        <div class="texture"></div>
        <div class="diag-bg"></div>
    </section>
    <!-- Hero End -->
    <!-- Call To Action Start -->
    <section class="cta" data-aos="fade-up" data-aos-delay="0">
        <div class="container">
            <div class="cta-content d-xl-flex align-items-center justify-content-around text-center text-xl-left">
                <div class="content" data-aos="fade-right" data-aos-delay="200">
                    <h2>   THE PEOPLE WHO MAKE IT HAPPEN</h2>
                    <p>            Meet the talented individuals behind our success! Our team is packed with passion and creativity, <br>
                                     working together to bring innovative ideas to life.</p>
                </div>
                <div class="subscribe-btn" data-aos="fade-left" data-aos-delay="400" data-aos-offset="0">
                    <a href="#" class="btn btn-primary">Meet Our Team</a>
                </div>
            </div>
        </div>
    </section>
    <!-- Call To Action End -->
    <!-- Services Start -->
    <section id="choice" class="services">
        <div class="container">
            <div class="title text-center">
                <h6>Our Perks</h6>
                <h1 class="title-blue">Why Choose Us</h1>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-lg-4">
                        <div class="media" data-aos="fade-up" data-aos-delay="200" data-aos-duration="400">
                            <img class="mr-4" src="assets/images/service1.png" alt="Web Development">
                            <div class="media-body">
                                <h5>Unforgettable Moments</h5>
                                Every event is crafted to create memories that stick.
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="media" data-aos="fade-up" data-aos-delay="400" data-aos-duration="600">
                            <img class="mr-4" src="assets/images/service2.png" alt="Web Development">
                            <div class="media-body">
                                <h5>Something for Everyone</h5>
                                From chill movie nights to outdoor adventures — all vibes welcome.
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="media" data-aos="fade-up" data-aos-delay="600" data-aos-duration="800">
                            <img class="mr-4" src="assets/images/service3.png" alt="Web Development">
                            <div class="media-body">
                                <h5>Community First</h5>
                                We’re more than just fun — we’re a family.
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="media" data-aos="fade-up" data-aos-delay="200" data-aos-duration="400">
                            <img class="mr-4" src="assets/images/service4.png" alt="Web Development">
                            <div class="media-body">
                                <h5>Creative Energy</h5>
                                Expect unique ideas, bold experiences, and vibrant style.
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="media" data-aos="fade-up" data-aos-delay="400" data-aos-duration="600">
                            <img class="mr-4" src="assets/images/service1.png" alt="Web Development">
                            <div class="media-body">
                                <h5>Accessible & Flexible</h5>
                                Online or on-site, we make it easy to join the fun.
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="media" data-aos="fade-up" data-aos-delay="600" data-aos-duration="800">
                            <img class="mr-4" src="assets/images/service5.png" alt="Web Development">
                            <div class="media-body">
                                <h5>Always Evolving</h5>
                                We listen, we grow, and we keep things fresh.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Services End -->
    <!-- Featured Start -->
    <section id="about" class="featured">
        <div class="container">
            <div class="row">
                <div class="col-md-6" data-aos="fade-right" data-aos-delay="400" data-aos-duration="800">
                    <div class="title">
                        <h6 class="title-primary">about Us</h6>
                        <h1 class="title-blue">CONNECT. PLAY. EXPERIENCE.</h1>
                    </div>
                    <p>At FunFusion, we believe that great experiences are even better when shared. Our platform connects people with similar interests to enjoy activities together—whether in the real world or online.

                        Love outdoor adventures? Find partners for sports, hiking, escape games, and more. Prefer virtual fun? Join gaming sessions, movie nights, or live-streamed events. With our smart matchmaking system, you’ll easily meet like-minded people and create unforgettable moments.
                        
                        Join us and start exploring new activities with new friends today!
                        
                        </p>
                    
                    <a href="#" class="btn btn-primary">See More</a>
                </div>
                <div class="col-md-6" data-aos="fade-left" data-aos-delay="400" data-aos-duration="800">
                    <div class="featured-img">
                        <img class="featured-big" height="500" src="assets/images/tennis.jpeg" alt="Featured 1">
                        <img class="featured-small" height="400" src="assets/images/game3.jpeg" alt="Featured 2">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Featured End -->
    <!-- Activités Virtuelles Start ------------------------------------------------------------------------------------------------------------->
<!-- Activités Virtuelles Start -->
<section class="recent-posts" id="virtual-activities">
    <div class="container" class="wave-divider">
        <div class="title text-center">
            <h6 class="title-primary">Online Fun</h6>
            <h1 class="title-blue">Activités Virtuelles</h1>
            <p>Explorez nos sessions de jeux, soirées films et événements virtuels disponibles.</p>
        </div>
        <?php include 'tic-tac-toe.php'; ?>
        <button class="btn-mini-jeu" onclick="ouvrirMorpion()">Jouer au Morpion 🎮</button>


        
        <form method="GET" id="voice-search-form" class="d-flex align-items-center">
  <div class="input-group">
    <input name="search" class="form-control" type="text" placeholder="Rechercher une activité.">
    <button class="btn btn-primary" type="submit">
        <i class="fa fa-search"></i>
    </button>
  </div>
  <button type="button" id="voiceSearchBtn" class="btn btn-secondary ms-2" title="Recherche vocale">
      <i class="fa fa-microphone"></i>
  </button>
</form>




        <div class="activity-controls mb-4">
    <!-- Filtre par type -->
    <div class="btn-group filter-group mb-2">
        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-filter"></i> Filtrer par type
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="?filter_type=COMPÉTITION">Compétition</a>
            <a class="dropdown-item" href="?filter_type=SOCIAL">Social</a>
            <a class="dropdown-item" href="?filter_type=DÉFI">Défi</a>
            <a class="dropdown-item" href="?filter_type=STREAM">Stream</a>
            <a class="dropdown-item" href="?filter_type=AUTRE">Autre</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="index.php">Tous les types</a>
        </div>
    </div>

    <?php
$sortClassRecent = (!isset($_GET['sort_date']) || $_GET['sort_date'] == 'recent') ? 'active' : '';
$sortClassOldest = (isset($_GET['sort_date']) && $_GET['sort_date'] == 'oldest') ? 'active' : '';
?>

<div class="btn-group sort-group">
    <a href="?sort_date=recent" class="btn btn-outline-secondary <?= $sortClassRecent ?>">
        <i class="fa fa-arrow-down"></i> Récents
    </a>
    <a href="?sort_date=oldest" class="btn btn-outline-secondary <?= $sortClassOldest ?>">
        <i class="fa fa-arrow-up"></i> Anciens
    </a>
</div>
</div>

        <!-- Bouton pour déclencher le formulaire -->
        <div class="text-center mb-4">
            <button id="showActivityFormBtn" class="btn btn-primary">
                <i class="fa fa-plus me-1"></i>AJOUTER UNE ACTIVITÉ
            </button>
        </div>

        <!-- Formulaire compact (caché par défaut) -->
        <div id="activityFormContainer" class="activity-form-container" style="display: none;">
            <div class="activity-form-header">
                <h3>Ajouter une Activité Virtuelle</h3>
            </div>
            <form id="activityForm" class="activity-form" method="POST" action="" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="activityTitle">Titre</label>
                        <input type="text" class="form-control" id="activityTitle" name="titre">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="activityType">Type</label>
                        <select class="form-control" id="activityType" name="type" >
                                    <option value="">Sélectionner...</option>
                                    <option value="COMPÉTITION">Compétition</option>
                                    <option value="SOCIAL">Social</option>
                                    <option value="DÉFI">Défi</option>
                                    <option value="STREAM">Stream</option>
                                    <option value="AUTRE">Autre</option>
                        </select>
                    </div>
                </div>

                  
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="activityDate">Date et Heure</label>
                        <input type="datetime-local" class="form-control" id="activityDate" name="date_heure">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="activityPlatform">Plateforme</label>
                        <input type="text" class="form-control" id="activityPlatform" name="plateforme" >
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="activityLink">Lien (URL)</label>
                    <input type="url" class="form-control" id="activityLink" name="lien">
                    <div class="invalid-feedback"></div>
                </div>

<div class="form-group">
  <label for="activityImage">Image de l'activité</label>
  <input type="file" class="form-control" id="activityImage" name="image" accept="image/*">
</div>

                
                <div class="form-actions">
                    <button type="button" id="cancelActivityBtn" class="btn btn-secondary">Annuler</button>
                    <button type="submit" name="action" value="add" class="btn btn-primary">ENREGISTRER</button>
                </div>
            </form>
        </div>

        <!-- FORMULAIRE MODIFICATION (CACHÉ PAR DÉFAUT) -->
<div id="modifierFormContainer" class="activity-form-container" style="display:none;">
    <h3>Modifier Activité</h3>
    <form method="POST" action="" id="modifierForm" enctype="multipart/form-data">
        <input type="hidden" name="id_activite" id="edit_id">
        <div class="form-group">
            <label for="edit_titre">Titre</label>
            <input type="text" class="form-control" name="titre" id="edit_titre" >
        </div>
        <div class="form-group">
            <label for="edit_type">Type</label>
            <select class="form-control" name="type" id="edit_type" >
                                    <option value="">Sélectionner...</option>
                                    <option value="COMPÉTITION">Compétition</option>
                                    <option value="SOCIAL">Social</option>
                                    <option value="DÉFI">Défi</option>
                                    <option value="STREAM">Stream</option>
                                    <option value="AUTRE">Autre</option>
            </select>
        </div>
        <div class="form-group">
            <label for="edit_date">Date et Heure</label>
            <input type="datetime-local" class="form-control" name="date_heure" id="edit_date" >
        </div>
        <div class="form-group">
            <label for="edit_plateforme">Plateforme</label>
            <input type="text" class="form-control" name="plateforme" id="edit_plateforme" >
        </div>
        <div class="form-group">
            <label for="edit_lien">Lien</label>
            <input type="url" class="form-control" name="lien" id="edit_lien">
        </div>

<div class="form-group">
  <label for="activityImage">Image de l'activité</label>
  <input type="file" class="form-control" id="activityImage" name="image" accept="image/*">
</div>


        <button type="submit" name="action" value="update" class="btn btn-warning">Mettre à jour</button>
        <button type="button" id="cancelUpdateBtn"  class="btn btn-secondary">Annuler</button>
    </form>
</div>

<!-- Formulaire de participation (modale) -->
<div class="modal fade" id="participationModal" tabindex="-1" aria-labelledby="participationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="participationModalLabel">Rejoindre l'activité</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="participationForm" method="POST" action="" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" name="action" value="add_participation">
          <input type="hidden" name="id_activite" id="participation_id_activite">
          <div class="mb-3">
            <label for="username" class="form-label">Nom d'utilisateur</label>
            <input type="text" class="form-control" id="username" name="username" >
          </div>
          <div class="mb-3">
            <label for="age" class="form-label">Age</label>
            <input type="number" class="form-control" id="age" name="age" min="1" >
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Valider</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- modifier participation (modale) -->

<div class="modal fade" id="editParticipationModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" id="editParticipationForm" action="" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Modifier votre participation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="action" value="update_participation">
          <input type="hidden" name="id_participation" id="edit_participation_id">
          <input type="hidden" name="id_activite" id="edit_participation_id_activite">

          <div class="mb-3">
            <label>Nom</label>
            <input type="text" class="form-control" name="username" id="edit_username">
          </div>
          <div class="mb-3">
            <label>Âge</label>
            <input type="number" class="form-control" name="age" id="edit_age">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Sauvegarder</button>
        </div>
      </div>
    </form>
  </div>
</div>


        <!-- Liste des activités existantes (contenu original conservé) -->
        
        <!-- Liste des activités existantes -->

<div class="row">
    <?php foreach ($activitesValidees as $index => $a): ?>
<?php
    $participationController = new ParticipationController();
    $participationExistante = $participationController->getParticipationByUserAndActivity($_SESSION['id_utilisateur'], $a['id_activite']);
    ?>
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 shadow-sm activite-card" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
            <?php $img = !empty($a['image']) ? '../uploads/' . htmlspecialchars($a['image']) : '../uploads/default.jpg'; ?>
            <div class=card-img-container" style="height: 200px; overflow: hidden;">
                <img src="<?= $img ?>" class="card-img-top" alt="Image activité" style="height: 100%; width: 100%; object-fit: cover;">
            </div>
            
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <h5 class="card-title font-weight-bold"><?= htmlspecialchars($a['titre']) ?></h5>
                    <span class="badge badge-primary"><?= htmlspecialchars($a['type']) ?></span>
                </div>
                
                <div class="d-flex align-items-center mb-2">
                    <i class="fa fa-tv mr-2 text-muted"></i>
                    <span class="text-muted"><?= htmlspecialchars($a['plateforme']) ?></span>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <i class="fa fa-calendar mr-2 text-muted"></i>
                    <small class="text-muted"><?= date('d M Y H:i', strtotime($a['date'])) ?></small>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                <?php
                    $now = new DateTime();
                    $dateActivite = new DateTime($a['date']);
                    $estDisponiblePourJouer = $now >= $dateActivite;
                ?>
                <?php if ($estDisponiblePourJouer && $participationExistante ): ?>
    <!-- Si la date de l’activité est arrivée → Affiche lien vers jeu -->
    <a href="<?= htmlspecialchars($a['lien']) ?>" class="btn btn-success" target="_blank">
        🎮Commencer
    </a>
    <form method="POST" action="" onsubmit="return confirm('Voulez-vous vraiment quitter cette participation ?');" class="d-inline">
    <input type="hidden" name="action" value="delete_participation">
    <input type="hidden" name="id_participation" value="<?= $participationExistante['id_participation'] ?>">
    <button type="submit" class="btn btn-danger btn-sm">
        <i class="fa fa-times"></i> Quitter
    </button>
</form>
                <?php elseif (!$participationExistante): ?>
    <!-- Pas encore inscrit → bouton Rejoindre -->
    <button type="button"
        class="btn btn-primary btn-participer"
        data-id="<?= $a['id_activite'] ?>"
        data-bs-toggle="modal"
        data-bs-target="#participationModal">
        Rejoindre
    </button>
<?php else: ?>
    <!-- Déjà inscrit → boutons Modifier & Quitter -->
    <button type="button"
    class="btn btn-warning btn-sm btn-edit-participation"
    data-bs-toggle="modal"
    data-bs-target="#editParticipationModal"
    data-id="<?= $participationExistante['id_participation'] ?>"
    data-id_activite="<?= $a['id_activite'] ?>"
    data-username="<?= htmlspecialchars($participationExistante['username']) ?>"
    data-age="<?= $participationExistante['age'] ?>">
    <i class="fa fa-edit"></i> Modifier Participation
</button>

<form method="POST" action="index_signin.php" onsubmit="return confirm('Voulez-vous vraiment quitter cette participation ?');" class="d-inline">
    <input type="hidden" name="action" value="delete_participation">
    <input type="hidden" name="id_participation" value="<?= $participationExistante['id_participation'] ?>">
    <button type="submit" class="btn btn-danger btn-sm">
        <i class="fa fa-times"></i> Quitter Participation
    </button>
</form>
<?php endif; ?>

                    
                    <?php if (isset($_SESSION['id_utilisateur']) && $_SESSION['id_utilisateur'] == $a['id_createur']): ?>
                    <div class="btn-group">
                        <button class="btn btn-warning btn-sm edit-btn"
                            data-id="<?= $a['id_activite'] ?>"
                            data-titre="<?= htmlspecialchars($a['titre']) ?>"
                            data-type="<?= htmlspecialchars($a['type']) ?>"
                            data-date="<?= date('Y-m-d\TH:i', strtotime($a['date'])) ?>"
                            data-plateforme="<?= htmlspecialchars($a['plateforme']) ?>"
                            data-lien="<?= htmlspecialchars($a['lien']) ?>">
                            <i class="fa fa-edit"></i>
                        </button>
                        
                        <form method="POST" action="" onsubmit="return confirm('Supprimer cette activité ?');" class="d-inline">
                            <input type="hidden" name="id_activite" value="<?= $a['id_activite'] ?>">
                            <button type="submit" name="action" value="delete" class="btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    
</div>

    </div>
    
</section>


<script>
document.querySelectorAll('.btn-participer').forEach(button => {
    button.addEventListener('click', function() {
        const idActivite = this.dataset.id;
        const inputHidden = document.getElementById('participation_id_activite');
        if (inputHidden) {
            inputHidden.value = idActivite;
        }
    });
});
</script>


<script>
  document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', () => {
      document.getElementById('modifierFormContainer').style.display = 'block';

      // Injecter les données dans le formulaire
      document.getElementById('edit_id').value = button.dataset.id;
      document.getElementById('edit_titre').value = button.dataset.titre;
      document.getElementById('edit_type').value = button.dataset.type;
      document.getElementById('edit_date').value = button.dataset.date;
      document.getElementById('edit_plateforme').value = button.dataset.plateforme;
      document.getElementById('edit_lien').value = button.dataset.lien;
      
      // Scroll jusqu'au formulaire
      window.scrollTo({ top: document.getElementById('modifierFormContainer').offsetTop - 50, behavior: 'smooth' });
    });
  });
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const voiceBtn = document.getElementById("voiceSearchBtn");

    if (!voiceBtn) return;

    voiceBtn.addEventListener("click", function () {
        if (!('webkitSpeechRecognition' in window)) {
            alert("La recherche vocale n'est pas supportée sur ce navigateur.");
            return;
        }

        const recognition = new webkitSpeechRecognition();
        recognition.lang = 'fr-FR';
        recognition.interimResults = false;
        recognition.maxAlternatives = 1;

        recognition.start();

        recognition.onresult = function (event) {
            const transcript = event.results[0][0].transcript.trim();
            document.querySelector('input[name="search"]').value = transcript;
            document.getElementById('voice-search-form').submit();
        };

        recognition.onerror = function (event) {
            alert("Erreur de reconnaissance vocale : " + event.error);
        };
    });

    
    if (window.location.search.includes('search=')) {
        setTimeout(() => {
            const cards = document.querySelectorAll('.card');

            if (cards.length > 0) {
                let message = "Voici les activités trouvées : ";

                cards.forEach(card => {
                    const title = card.querySelector('.card-title')?.textContent || '';
                    const type = card.querySelector('.badge')?.textContent || '';
                    
                    const date = card.querySelector('small.text-muted')?.textContent || '';


                    message += `Activité ${title}, de type ${type}, prévue pour le ${date}. `;
                });

                const utterance = new SpeechSynthesisUtterance(message);
                utterance.lang = 'fr-FR';
                speechSynthesis.speak(utterance);
            } else {
                const utterance = new SpeechSynthesisUtterance("Aucune activité trouvée pour votre recherche.");
                utterance.lang = 'fr-FR';
                speechSynthesis.speak(utterance);
            }
        }, 500); // petit délai pour attendre que la page charge
    }
});
</script>


<script>
// === Validation en temps réel du formulaire de participation ===

// Sélection des champs
const participationIdActivite = document.getElementById('participation_id_activite');

const participationUsername = document.getElementById('username');
const participationAge = document.getElementById('age');

// Fonction réutilisable
function validateInput(input, condition, message) {
    let feedback = input.nextElementSibling;
    if (!feedback || (!feedback.classList.contains('invalid-feedback') && !feedback.classList.contains('valid-feedback'))) {
        feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        input.parentNode.appendChild(feedback);
    }

    if (condition) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        feedback.textContent = "";
        feedback.style.display = "none";
    } else {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        feedback.textContent = message;
        feedback.classList.add('invalid-feedback');
        feedback.style.display = "block";
    }
}

// Ajout des écouteurs pour chaque champ
if (participationUsername) {
    participationUsername.addEventListener('input', () => {
        validateInput(participationUsername, participationUsername.value.trim().length >= 3, "Le nom doit contenir au moins 3 caractères.");
    });
}
if (participationAge) {
    participationAge.addEventListener('input', () => {
        const ageVal = parseInt(participationAge.value);
        validateInput(participationAge, !isNaN(ageVal) && ageVal >= 1, "Âge invalide.");
    });
}


// Validation avant soumission du formulaire
const participationForm = document.getElementById('participationForm');
if (participationForm) {
    participationForm.addEventListener('submit', function(e) {
        let isValid = true;

        if (participationUsername.value.trim().length < 3) {
            isValid = false;
            validateInput(participationUsername, false, "Le nom doit contenir au moins 3 caractères.");
        }

        const ageVal = parseInt(participationAge.value);
        if (isNaN(ageVal) || ageVal < 12) {
            isValid = false;
            validateInput(participationAge, false, "Âge invalide (plus 12 ans).");
        }


        if (!isValid) {
            e.preventDefault(); // Empêche l'envoi si invalide
        }
    });
}


</script>
<script>
  document.querySelectorAll('.btn-edit-participation').forEach(btn => {
    btn.addEventListener('click', function () {
      document.getElementById('edit_participation_id').value = this.dataset.id;
      document.getElementById('edit_participation_id_activite').value = this.dataset.id_activite;
      document.getElementById('edit_username').value = this.dataset.username;
      document.getElementById('edit_age').value = this.dataset.age;

      // Affiche la modale
      new bootstrap.Modal(document.getElementById('editParticipationModal')).show();
    });
  });
</script>


<script>
// Affichage du formulaire
document.getElementById('showActivityFormBtn').addEventListener('click', function () {
    document.getElementById('activityFormContainer').style.display = 'block';
    this.style.display = 'none';
    window.scrollTo({
        top: document.getElementById('activityFormContainer').offsetTop - 20,
        behavior: 'smooth'
    });
});

document.getElementById("cancelUpdateBtn").addEventListener("click", function () {
    document.getElementById("modifierFormContainer").style.display = "none";
    document.getElementById("modifierForm").reset();
    // Supprime les couleurs
    document.querySelectorAll("#modifierForm .form-control").forEach(input => {
        input.classList.remove("is-valid", "is-invalid");
    });
});

// Annuler (masquer formulaire)
document.getElementById('cancelActivityBtn').addEventListener('click', function () {
    document.getElementById('activityFormContainer').style.display = 'none';
    document.getElementById('showActivityFormBtn').style.display = 'inline-block';
    document.getElementById('activityForm').reset();
    // Réinitialiser les couleurs
    document.querySelectorAll('.form-control').forEach(input => {
        input.classList.remove('is-valid', 'is-invalid');
    });
});

// Validation en temps réel
document.addEventListener("DOMContentLoaded", function () {
    const fields = ['activityTitle', 'activityDate', 'activityPlatform', 'activityLink'];

    fields.forEach(fieldId => {
        const input = document.getElementById(fieldId);
        input.addEventListener('input', () => {
            if (input.value.trim() === "") {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
            }
        });
    });
});
</script>

<script>
// Fonction de validation d'un champ individuel
function validateInput(input, condition, message) {
    // Trouver ou créer l'élément de feedback (message d'erreur) après le champ
    let feedback = input.nextElementSibling;
    if (!feedback || (!feedback.classList.contains('invalid-feedback') && !feedback.classList.contains('valid-feedback'))) {
        // Créer un élément <div> pour afficher le message d'erreur si absent
        feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';  // par défaut, style pour message d’erreur
        input.parentNode.appendChild(feedback);
    }
    // Appliquer les classes et le message en fonction de la validité du champ
    if (condition) {
        // Champ valide
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        // Retirer le message d'erreur s'il existait
        feedback.textContent = "";
        feedback.style.display = "none";
    } else {
        // Champ invalide
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        // Afficher le message d'erreur en rouge
        feedback.textContent = message;
        feedback.classList.remove('valid-feedback');
        feedback.classList.add('invalid-feedback');
        feedback.style.display = "block";
    }
}

// Sélection des champs du formulaire d'ajout
const titre    = document.getElementById('activityTitle');
const type     = document.getElementById('activityType');
const date     = document.getElementById('activityDate');
const plateforme = document.getElementById('activityPlatform');
const lien     = document.getElementById('activityLink');

// Sélection des champs du formulaire de modification
const editTitre      = document.getElementById('edit_titre');
const editType       = document.getElementById('edit_type');
const editDate       = document.getElementById('edit_date');
const editPlateforme = document.getElementById('edit_plateforme');
const editLien       = document.getElementById('edit_lien');

// Écouteurs d'événement pour validation en temps réel (formulaire d'ajout)
if (titre) {
    titre.addEventListener('input', () => {
        validateInput(titre, titre.value.trim().length >= 3, "Le titre doit contenir au moins 3 caractères.");
    });
}
if (type) {
    type.addEventListener('change', () => {
        validateInput(type, type.value !== "", "Veuillez sélectionner un type.");
    });
}
if (date) {
    date.addEventListener('input', () => {
        // La date est valide si elle est renseignée ET strictement postérieure à maintenant
        const isFuture = date.value !== "" && new Date(date.value) > new Date();
        validateInput(date, isFuture, "Veuillez choisir une date future.");
    });
}
if (plateforme) {
    plateforme.addEventListener('input', () => {
        validateInput(plateforme, plateforme.value.trim().length >= 3, "La plateforme doit contenir au moins 3 caractères.");
    });
}
if (lien) {
    lien.addEventListener('input', () => {
        // Lien valide s'il commence par http:// ou https:// et n'a pas d'espaces
        const urlPattern = /^https?:\/\/\S+$/;
        validateInput(lien, urlPattern.test(lien.value), "Veuillez entrer une URL valide (commençant par http:// ou https://).");
    });
}

// Écouteurs d'événement pour validation en temps réel (formulaire de modification)
if (editTitre) {
    editTitre.addEventListener('input', () => {
        validateInput(editTitre, editTitre.value.trim().length >= 3, "Le titre doit contenir au moins 3 caractères.");
    });
}
if (editType) {
    editType.addEventListener('change', () => {
        validateInput(editType, editType.value !== "", "Veuillez sélectionner un type.");
    });
}
if (editDate) {
    editDate.addEventListener('input', () => {
        const isFuture = editDate.value !== "" && new Date(editDate.value) > new Date();
        validateInput(editDate, isFuture, "Veuillez choisir une date future.");
    });
}
if (editPlateforme) {
    editPlateforme.addEventListener('input', () => {
        validateInput(editPlateforme, editPlateforme.value.trim().length >= 3, "La plateforme doit contenir au moins 3 caractères.");
    });
}
if (editLien) {
    editLien.addEventListener('input', () => {
        const urlPattern = /^https?:\/\/\S+$/;
        validateInput(editLien, urlPattern.test(editLien.value), "Veuillez entrer une URL valide (commençant par http:// ou https://).");
    });
}

// Empêcher la soumission si des champs sont invalides (formulaire d'ajout)
const addForm = document.getElementById('activityForm');
if (addForm) {
    addForm.addEventListener('submit', function(e) {
        let formIsValid = true;
        // Vérifier chaque champ et afficher l'erreur si nécessaire
        if (titre.value.trim().length < 3) {
            formIsValid = false;
            validateInput(titre, false, "Le titre doit contenir au moins 3 caractères.");
        }
        if (type.value === "") {
            formIsValid = false;
            validateInput(type, false, "Veuillez sélectionner un type.");
        }
        if (!date.value || new Date(date.value) <= new Date()) {
            formIsValid = false;
            validateInput(date, false, "Veuillez choisir une date future.");
        }
        if (plateforme.value.trim().length < 3) {
            formIsValid = false;
            validateInput(plateforme, false, "La plateforme doit contenir au moins 3 caractères.");
        }
        const urlPattern = /^https?:\/\/\S+$/;
        if (!urlPattern.test(lien.value)) {
            formIsValid = false;
            validateInput(lien, false, "Veuillez entrer une URL valide (commençant par http:// ou https://).");
        }
        // Si au moins une erreur, empêcher l'envoi du formulaire
        if (!formIsValid) {
            e.preventDefault();
        }
    });
}

// Empêcher la soumission si des champs sont invalides (formulaire de modification)
const updateForm = document.getElementById('modifierForm');
if (updateForm) {
    updateForm.addEventListener('submit', function(e) {
        let formIsValid = true;
        if (editTitre.value.trim().length < 3) {
            formIsValid = false;
            validateInput(editTitre, false, "Le titre doit contenir au moins 3 caractères.");
        }
        if (editType.value === "") {
            formIsValid = false;
            validateInput(editType, false, "Veuillez sélectionner un type.");
        }
        if (!editDate.value || new Date(editDate.value) <= new Date()) {
            formIsValid = false;
            validateInput(editDate, false, "Veuillez choisir une date future.");
        }
        if (editPlateforme.value.trim().length < 3) {
            formIsValid = false;
            validateInput(editPlateforme, false, "La plateforme doit contenir au moins 3 caractères.");
        }
        const urlPattern = /^https?:\/\/\S+$/;
        if (!urlPattern.test(editLien.value)) {
            formIsValid = false;
            validateInput(editLien, false, "Veuillez entrer une URL valide (commençant par http:// ou https://).");
        }
        if (!formIsValid) {
            e.preventDefault();
        }
    });
}
</script>
<script>
// Animation au survol des cartes
document.querySelectorAll('.card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-10px)';
        this.style.boxShadow = '0 15px 30px rgba(0,0,0,0.2)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = '';
        this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
    });
});

// Animation des boutons
document.querySelectorAll('.btn').forEach(btn => {
    btn.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px)';
    });
    
    btn.addEventListener('mouseleave', function() {
        this.style.transform = '';
    });
});

// Effet de chargement des cartes
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.activite-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100 + 300);
    });
});
</script>
<script>
// Mise en évidence du filtre actif
document.addEventListener('DOMContentLoaded', function() {
    // Highlight du type sélectionné
    const urlParams = new URLSearchParams(window.location.search);
    const filterType = urlParams.get('filter_type');
    
    if (filterType) {
        const dropdownItems = document.querySelectorAll('.dropdown-item');
        dropdownItems.forEach(item => {
            if (item.textContent.trim() === filterType) {
                item.classList.add('active-filter');
                document.querySelector('.dropdown-toggle').innerHTML = 
                    `<i class="fa fa-filter"></i> ${filterType}`;
            }
        });
    }
    
    // Animation des boutons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        btn.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });
});

document.querySelectorAll('.btn-participer').forEach(btn => {
    btn.addEventListener('click', () => {
        btn.classList.add('animated-button');
    });
});

// Pour les autres actions (modification / suppression)
document.querySelectorAll('.btn-warning, .btn-danger').forEach(btn => {
    btn.classList.add('animated-button');
});

</script>
    <!-- Activités Virtuelles End -->
<!-- Activités Virtuelles End ---------------------------------------------------------------------------------------------------------------->

    <section id="community" class="trust">
        <div class="container">
            <div class="row">
                <div class="offset-xl-1 col-xl-6" data-aos="fade-right" data-aos-delay="200" data-aos-duration="800">
                    <div class="title">
                        <h6 class="title-primary">community highlights</h6>
                        <h1>What Our Fusion Fam Says...</h1>
                    </div>
                    <p>"The camping trip was a total blast! The best way to disconnect from the hustle and reconnect with nature. Can't wait for the next one!"
                        
                    </p>
                    <p>- Sarah L. | Outdoor Enthusiast</p><br>
                    <p>"The movie streaming event was so much fun. It was like watching with friends, even though we were all at home. Super fun!"
                        
                    </p>
                    <p>- Jenny T. | Movie Buff</p><br>
                    <p>"Had such a great time! The events are always so well-organized and everyone’s so friendly. It’s like a second family."
                        
                    </p>
                    <p>- Lisa M. | FunFusion Regular</p>
                    <!--<h5>Web Design & Development</h5>
                    <ul class="list-unstyled">
                        <li>Web Content</li>
                        <li>Website other</li>
                        <li>Fashion</li>
                        <li>Moblie & Tablette</li>
                    </ul>-->
                </div>
                <div class="col-xl-5 gallery">
                    <div class="row no-gutters h-100" id="lightgallery">
                        <a href="https://lorempixel.com/600/400/" class="w-50 h-100 gal-img" data-aos="fade-up"
                            data-aos-delay="200" data-aos-duration="400">
                            <img class="img-fluid" src="assets/images/community1.jpg" alt="Gallery Image">
                            <i class="fa fa-caret-right"></i>
                        </a>
                        <a href="https://lorempixel.com/600/400/" class="w-50 h-50 gal-img" data-aos="fade-up"
                            data-aos-delay="400" data-aos-duration="600">
                            <img class="img-fluid" src="assets/images/communityTalk.jpg" alt="Gallery Image">
                            <i class="fa fa-caret-right"></i>
                        </a>
                        <a href="https://lorempixel.com/600/400/" class="w-50 h-50 gal-img gal-img3" data-aos="fade-up"
                            data-aos-delay="0" data-aos-duration="600">
                            <img class="img-fluid" src="assets/images/community2.jpg" alt="Gallery Image">
                            <i class="fa fa-caret-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Trust End -->
    <!-- Pricing Start -->
    <section id="contact" class="pricing-table">
        <div class="container">
            <div class="title text-center">
                <h6 class="title-primary">Our contact</h6>
                <h1 class="title-blue">Get in touch</h1>
            </div>
            <div class="row no-gutters">
                <div class="col-md-4">
                    <div class="single-pricing text-center" data-aos="fade-up" data-aos-delay="0"
                        data-aos-duration="600">
                        <span>Email</span>
                        <h2>Email us</h2>
                        <p class="desc">FunFusion@gmail.com</p>
                        <!--<p class="price">FunFusion@gmail.com</p>-->
                        <p>feel free to reach out to us by email. Your message matters to us.</p>
                        <a href="#" class="btn btn-primary">Contact Us</a>
                        <svg viewBox="0 0 170 193">
                            <path fill-rule="evenodd" fill="rgb(238, 21, 21)"
                                d="M39.000,31.999 C39.000,31.999 -21.000,86.500 9.000,121.999 C39.000,157.500 91.000,128.500 104.000,160.999 C117.000,193.500 141.000,201.000 150.000,183.000 C159.000,165.000 172.000,99.000 167.000,87.000 C162.000,75.000 170.000,63.000 152.000,45.000 C134.000,27.000 128.000,15.999 116.000,11.000 C104.000,6.000 89.000,-0.001 89.000,-0.001 L39.000,31.999 Z" />
                        </svg>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="single-pricing text-center" data-aos="fade-up" data-aos-delay="300"
                        data-aos-duration="600">
                        <span>Socials</span>
                        <h2>Connect with us</h2>
                        <p class="desc">Instagram: @FunFusionOfficial</p>
                        <p class="desc">Facebook: FunFusion</p>
                        <!--<p class="desc">X: @FunFusionLive</p>-->
                        <!--<p class="price">$39.00</p>-->
                        <p>Stay connected and catch the latest updates, events, and vibes.
                            Join our growing community online</p>
                        <a href="#" class="btn btn-primary">Contact Us</a>
                        <svg viewBox="0 0 170 193">
                            <path fill-rule="evenodd" fill="rgb(238, 21, 21)"
                                d="M39.000,31.999 C39.000,31.999 -21.000,86.500 9.000,121.999 C39.000,157.500 91.000,128.500 104.000,160.999 C117.000,193.500 141.000,201.000 150.000,183.000 C159.000,165.000 172.000,99.000 167.000,87.000 C162.000,75.000 170.000,63.000 152.000,45.000 C134.000,27.000 128.000,15.999 116.000,11.000 C104.000,6.000 89.000,-0.001 89.000,-0.001 L39.000,31.999 Z" />
                        </svg>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="single-pricing text-center" data-aos="fade-up" data-aos-delay="600"
                        data-aos-duration="600">
                        <span>Call</span>
                        <h2>Call us</h2>
                        <p class="desc">+216 24 618 989</p>
                        <!--<p class="price">+216 24 618 989</p>-->
                        <p>We’re just a ring away for info, ideas, or good vibes.</p>
                        <a href="#" class="btn btn-primary">Contact Us</a>
                        <svg viewBox="0 0 170 193">
                            <path fill-rule="evenodd" fill="rgb(238, 21, 21)"
                                d="M39.000,31.999 C39.000,31.999 -21.000,86.500 9.000,121.999 C39.000,157.500 91.000,128.500 104.000,160.999 C117.000,193.500 141.000,201.000 150.000,183.000 C159.000,165.000 172.000,99.000 167.000,87.000 C162.000,75.000 170.000,63.000 152.000,45.000 C134.000,27.000 128.000,15.999 116.000,11.000 C104.000,6.000 89.000,-0.001 89.000,-0.001 L39.000,31.999 Z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Pricing End -->
    <!-- Testimonial and Clients Start -->
    <!--<section class="testimonial-and-clients">
        <div class="container">
            <div class="testimonials">
                <div class="swiper-container test-slider">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide text-center">
                            <div class="row">
                                <div class="offset-lg-1 col-lg-10">
                                    <div class="test-img" data-aos="fade-up" data-aos-delay="0" data-aos-offset="0"><img
                                            src="assets/images/test1.png" alt="Testimonial 1"></div>
                                    <h5 data-aos="fade-up" data-aos-delay="200" data-aos-duration="600"
                                        data-aos-offset="0">John</h5>
                                    <span data-aos="fade-up" data-aos-delay="400" data-aos-duration="600"
                                        data-aos-offset="0">UI/UX
                                        Designer</span>
                                    <p data-aos="fade-up" data-aos-delay="600" data-aos-duration="600"
                                        data-aos-offset="0">Ash's tactics &
                                        books have helped me a lot in my understanding on how social
                                        media
                                        advertising works.I can say that he is one of the best development professionals
                                        i have
                                        dealt with so far. His experience is great & he is such a great & pleasant
                                        person to
                                        work with as he understands what you are</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide text-center">
                            <div class="row">
                                <div class="offset-lg-1 col-lg-10">
                                    <div class="test-img" data-aos="fade-up" data-aos-delay="0" data-aos-offset="0"><img
                                            src="assets/images/test1.png" alt="Testimonial 1"></div>
                                    <h5 data-aos="fade-up" data-aos-delay="200" data-aos-duration="600"
                                        data-aos-offset="0">John</h5>
                                    <span data-aos="fade-up" data-aos-delay="400" data-aos-duration="600"
                                        data-aos-offset="0">UI/UX
                                        Designer</span>
                                    <p data-aos="fade-up" data-aos-delay="600" data-aos-duration="600"
                                        data-aos-offset="0">Ash's tactics &
                                        books have helped me a lot in my understanding on how social
                                        media
                                        advertising works.I can say that he is one of the best development professionals
                                        i have
                                        dealt with so far. His experience is great & he is such a great & pleasant
                                        person to
                                        work with as he understands what you are</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide text-center">
                            <div class="row">
                                <div class="offset-lg-1 col-lg-10">
                                    <div class="test-img" data-aos="fade-up" data-aos-delay="0" data-aos-offset="0"><img
                                            src="assets/images/test1.png" alt="Testimonial 1"></div>
                                    <h5 data-aos="fade-up" data-aos-delay="200" data-aos-duration="600"
                                        data-aos-offset="0">John</h5>
                                    <span data-aos="fade-up" data-aos-delay="400" data-aos-duration="600"
                                        data-aos-offset="0">UI/UX
                                        Designer</span>
                                    <p data-aos="fade-up" data-aos-delay="600" data-aos-duration="600"
                                        data-aos-offset="0">Ash's tactics &
                                        books have helped me a lot in my understanding on how social
                                        media
                                        advertising works.I can say that he is one of the best development professionals
                                        i have
                                        dealt with so far. His experience is great & he is such a great & pleasant
                                        person to
                                        work with as he understands what you are</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="test-pagination"></div>
                </div>
            </div>
            <div class="clients" data-aos="fade-up" data-aos-delay="200" data-aos-duration="600">
                <div class="swiper-container clients-slider">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="assets/images/client1.png" alt="Client 1">
                        </div>
                        <div class="swiper-slide">
                            <img src="assets/images/client2.png" alt="Client 2">
                        </div>
                        <div class="swiper-slide">
                            <img src="assets/images/client3.png" alt="Client 3">
                        </div>
                        <div class="swiper-slide">
                            <img src="assets/images/client4.png" alt="Client 4">
                        </div>
                        <div class="swiper-slide">
                            <img src="assets/images/client5.png" alt="Client 5">
                        </div>
                    </div>
                    <div class="test-pagination"></div>
                </div>
            </div>
        </div>
    </section>-->
    <!-- Testimonial and Clients End -->
    <!-- Call To Action 2 Start -->
    <!--<section class="cta cta2" data-aos="fade-up" data-aos-delay="0">
        <div class="container">
            <div class="cta-content d-xl-flex align-items-center justify-content-around text-center text-xl-left">
                <div class="content" data-aos="fade-right" data-aos-delay="200">
                    <h2>FOR BUILDING THE MODERN WEBSITE</h2>
                    <p>Packed with all the goodies you can get, Kallyas is our flagship premium template.</p>
                </div>
                <div class="subscribe-btn" data-aos="fade-left" data-aos-delay="400" data-aos-offset="0">
                    <a href="#" class="btn btn-primary">Join Newsletter</a>
                </div>
            </div>
        </div>
    </section>-->
    <!-- Call To Action 2 End -->
    <!-- Footer Start -->
    <footer>
        <!-- Widgets Start -->
        <!--<div class="footer-widgets">
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
        </div>-->
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
    <!-- Footer Endt -->
    <!--jQuery-->
    <script src="assets/js/jquery-3.3.1.js"></script>
    <!--Plugins-->
    <script src="assets/js/bootstrap.bundle.js"></script>
    <script src="assets/js/loaders.css.js"></script>
    <script src="assets/js/aos.js"></script>
    <script src="assets/js/swiper.min.js"></script>
    <script src="assets/js/lightgallery-all.min.js"></script>
    <!--Template Script-->
    <script src="assets/js/main.js"></script>
    <!-- jQuery (obligatoire pour certaines fonctionnalités si tu es en Bootstrap 4, mais pas en BS5 pur) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Popper.js et Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

</body>

</html>