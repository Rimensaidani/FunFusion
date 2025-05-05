<?php
// Include the database configuration
include 'C:\xamppp\htdocs\FunFusion\config.php';

class OffresController {
    public function getAllOffres() {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->query("SELECT o.*, c.title AS challenge_title 
                                 FROM offres o 
                                 LEFT JOIN challenges c ON o.id_defi = c.id_defi");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return [];
        }
    }
}

$controller = new OffresController();
$offres = $controller->getAllOffres();

// Prepare offers for JavaScript and check for expiring offers
$expiringOffers = [];
$currentDate = new DateTime(); // Use dynamic current date
$thresholdDate = (clone $currentDate)->modify('+1 day'); // Offers expiring within 24 hours

$allOffersJs = [];
$offerCounter = 1;

// Debugging: Log the current date and threshold date
error_log("Current Date: " . $currentDate->format('Y-m-d H:i:s'));
error_log("Threshold Date: " . $thresholdDate->format('Y-m-d H:i:s'));

foreach ($offres as $offre) {
    $type = htmlspecialchars($offre['type']);
    $challengeTitle = htmlspecialchars($offre['challenge_title'] ?? 'Unknown Challenge');
    $dateExpiration = $offre['date_expiration'] == '0000-00-00' ? 'No Expiration' : date('Y-m-d', strtotime($offre['date_expiration']));
    $etat = htmlspecialchars($offre['etat']);
    $price = ($etat === 'debloque') ? 'FREE' : 'EXCLUSIVE';
    $desc = strtoupper(substr($type, 0, 3)); // e.g., "RÉD" for "réduction"

    // Descriptions based on offer type
    $descriptions = [
        'réduction' => "Remporte une réduction exclusive chez Decathlon en relevant avec succès deux défis sportifs sur notre plateforme FunFusion",
        'bonus' => "Gagne 500 points bonus en complétant cinq défis de jeu sur FunFusion",
        'accès' => "Obtiens un accès exclusif à un événement en direct en participant à trois activités FunFusion",
        'code_promo' => "Gagne un code promo Netflix en participant à trois soirées cinéma organisées via FunFusion",
    ];
    $description = $descriptions[$type] ?? "Participate in challenges to unlock this offer!";

    // Prepare the offer HTML for JavaScript
    $offerHtml = "
        <div class=\"single-pricing text-center\" data-aos=\"fade-up\" data-aos-delay=\"".($offerCounter % 2 == 0 ? 300 : 0)."\" data-aos-duration=\"600\" data-offer-name=\"{$type}\">
            <span>{$offerCounter}".($offerCounter == 1 ? 'st' : ($offerCounter == 2 ? 'nd' : ($offerCounter == 3 ? 'rd' : 'th')))." Offer</span>
            <h2>{$type}</h2>
            <p class=\"desc\">{$desc}</p>
            <p class=\"price\">{$price}</p>
            <p>{$description}</p>
            <a href=\"challenges_front.php\" class=\"btn btn-primary\">Enjoy Now</a>
            <svg viewBox=\"0 0 170 193\">
                <path fill-rule=\"evenodd\" fill=\"rgb(238, 21, 21)\" d=\"M39.000,31.999 C39.000,31.999 -21.000,86.500 9.000,121.999 C39.000,157.500 91.000,128.500 104.000,160.999 C117.000,193.500 141.000,201.000 150.000,183.000 C159.000,165.000 172.000,99.000 167.000,87.000 C162.000,75.000 170.000,63.000 152.000,45.000 C134.000,27.000 128.000,15.999 116.000,11.000 C104.000,6.000 89.000,-0.001 89.000,-0.001 L39.000,31.999 Z\" />
            </svg>
        </div>
    ";

    $allOffersJs[] = [
        'name' => $type,
        'html' => $offerHtml
    ];

    // Check if the offer is about to expire (within 24 hours)
    if ($dateExpiration !== 'No Expiration') {
        try {
            $expirationDate = new DateTime($dateExpiration);
            // Debugging: Log each offer's expiration date
            error_log("Offer: {$type}, Expiration Date: " . $expirationDate->format('Y-m-d H:i:s'));
            // Include offers expiring today or tomorrow
            if ($expirationDate >= $currentDate && $expirationDate <= $thresholdDate) {
                $expiringOffers[] = [
                    'type' => $type,
                    'date_expiration' => $dateExpiration
                ];
            }
        } catch (Exception $e) {
            // Log any date parsing errors
            error_log("Error parsing date for offer {$type}: " . $e->getMessage());
        }
    }

    $offerCounter++;
}

// Debugging: Log the expiring offers
error_log("Expiring Offers: " . print_r($expiringOffers, true));

// Convert offers to JSON for JavaScript
$allOffersJson = json_encode($allOffersJs);
$expiringOffersJson = json_encode($expiringOffers);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>FunFusion</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i%7CRajdhani:400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/loader/loaders.css">
    <link rel="stylesheet" href="assets/css/font-awesome/font-awesome.css">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/aos/aos.css">
    <link rel="stylesheet" href="assets/css/swiper/swiper.css">
    <link rel="stylesheet" href="assets/css/lightgallery.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <style>
        /* Custom styles for the offers slider */
        .offers-slider .swiper-slide {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .single-pricing {
            position: relative;
            background: #0f1733;
            padding: 40px 20px;
            border-radius: 10px;
            margin: 10px;
            max-width: 350px;
            width: 100%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
        }
        .single-pricing:hover {
            transform: translateY(-10px);
        }
        .single-pricing span {
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            background: #ee1515;
            color: white;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 14px;
            font-weight: 600;
        }
        .single-pricing h2 {
            color: white;
            font-size: 28px;
            margin-bottom: 10px;
        }
        .single-pricing .desc {
            color: #ccc;
            font-size: 16px;
            margin-bottom: 10px;
        }
        .single-pricing .price {
            color: white;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .single-pricing p {
            color: #aaa;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .single-pricing .btn-primary {
            background: #ee1515;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: 600;
        }
        .single-pricing svg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 80px;
            z-index: 0;
        }
        /* Custom numbered pagination */
        .swiper-pagination-custom {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .swiper-pagination-custom .swiper-pagination-bullet {
            background: #ee1515;
            color: white;
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            border-radius: 50%;
            opacity: 0.5;
            font-size: 14px;
        }
        .swiper-pagination-custom .swiper-pagination-bullet-active {
            opacity: 1;
        }
        /* No offers found message */
        .no-offers {
            text-align: center;
            color: #ee1515;
            font-size: 18px;
            margin-top: 20px;
            display: none;
        }
        /* Notification styles */
        .notification {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.8);
            background: #ff4b2b;
            color: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: none;
            max-width: 400px;
            width: 90%;
            cursor: pointer;
            opacity: 0;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
        .notification.show {
            display: block;
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }
        .notification h4 {
            margin: 0 0 10px 0;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
        }
        .notification p {
            margin: 5px 0;
            font-size: 16px;
            text-align: center;
        }
        .notification .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }
        .notification:hover {
            background: #e43a1a;
        }
    </style>
</head>
<body>
    <!-- Loader Start -->
    <div class="css-loader">
        <div class="loader-inner line-scale d-flex align-items-center justify-content-center"></div>
    </div>
    <!-- Loader End -->
    <!-- Notification -->
    <div id="expiration-notification" class="notification" onclick="scrollToOffersSection(event)">
        <span class="close-btn" onclick="closeNotification(event)">×</span>
        <h4>Urgent: Offers Expiring Soon!</h4>
        <div id="expiring-offers-list"></div>
    </div>
    <!-- Header Start -->
    <header class="position-absolute w-100">
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
                <a class="navbar-brand" href="index.php"><img src="assets/images/logo_site.png" height="65" alt="logo FunFusion"></a><h2>FunFusion</h2>
                <div class="group d-flex align-items-center">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                    <a class="login-icon d-sm-none" href="#"><i class="fa fa-user"></i></a>
                    <a class="cart" href="#"><i class="fa fa-shopping-cart"></i></a>
                </div>
                <a class="search-icon d-none d-md-block" href="#"><i class="fa fa-search"></i></a>
                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
                        <li class="nav-item"><a class="nav-link" href="#Offers-and-challenges">Offers and challenges</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Activities</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Contact Us</a></li>
                    </ul>
                    <form class="bg-white search-form" method="get" id="searchform">
                        <div class="input-group">
                            <input class="field form-control" id="s" name="s" type="text" placeholder="Search offers by name...">
                            <span class="input-group-btn">
                                <input class="submit btn btn-primary" id="searchsubmit" name="submit" type="button" value="Search" onclick="searchOffers()">
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
                                    <h1 data-aos="fade-right" data-aos-delay="200">Welcome to<br>FunFusion</h1>
                                    <p data-aos="fade-right" data-aos-delay="600">the perfect platform to connect with people who share your interests, <br>whether for outdoor activities or virtual experiences.</p>
                                    <a data-aos="fade-right" data-aos-delay="900" href="#" class="btn btn-primary">See More</a>
                                    <a data-aos="fade-right" data-aos-delay="900" href="#" class="btn btn-primary">sign up</a>
                                </div>
                            </div>
                            <div class="swiper-slide slide-content d-flex align-items-center">
                                <div class="single-slide">
                                    <h1 data-aos="fade-right" data-aos-delay="200">Welcome to<br>FunFusion</h1>
                                    <p data-aos="fade-right" data-aos-delay="600">FunFusion makes it easy to plan activities that match your preferences,<br> both online and in real life!</p>
                                    <a data-aos="fade-right" data-aos-delay="900" href="#" class="btn btn-primary">See More</a>
                                    <a data-aos="fade-right" data-aos-delay="900" href="#" class="btn btn-primary">sign up</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
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
                    <h2>THE PEOPLE WHO MAKE IT HAPPEN</h2>
                    <p>Meet the talented individuals behind our success! Our team is packed with passion and creativity, <br>working together to bring innovative ideas to life.</p>
                </div>
                <div class="subscribe-btn" data-aos="fade-left" data-aos-delay="400" data-aos-offset="0">
                    <a href="#" class="btn btn-primary">Meet Our Team</a>
                </div>
            </div>
        </div>
    </section>
    <!-- Call To Action End -->
    <!-- Services Start -->
    <section class="services">
        <div class="container">
            <div class="title text-center">
                <h6>Our Speakers</h6>
                <h1 class="title-blue">Why Choose Us</h1>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-lg-4">
                        <div class="media" data-aos="fade-up" data-aos-delay="200" data-aos-duration="400">
                            <img class="mr-4" src="assets/images/service1.png" alt="Web Development">
                            <div class="media-body">
                                <h5>Web Development</h5>
                                Donec volutpat tincidunt neque, vitae lobortis libero mattis sed tempus.
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="media" data-aos="fade-up" data-aos-delay="400" data-aos-duration="600">
                            <img class="mr-4" src="assets/images/service2.png" alt="Web Development">
                            <div class="media-body">
                                <h5>Testing for perfection</h5>
                                Donec volutpat tincidunt neque, vitae lobortis libero mattis sed tempus.
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="media" data-aos="fade-up" data-aos-delay="600" data-aos-duration="800">
                            <img class="mr-4" src="assets/images/service3.png" alt="Web Development">
                            <div class="media-body">
                                <h5>Discussion of the idea</h5>
                                Donec volutpat tincidunt neque, vitae lobortis libero mattis sed tempus.
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="media" data-aos="fade-up" data-aos-delay="200" data-aos-duration="400">
                            <img class="mr-4" src="assets/images/service4.png" alt="Web Development">
                            <div class="media-body">
                                <h5>Modern style</h5>
                                Donec volutpat tincidunt neque, vitae lobortis libero mattis sed tempus.
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="media" data-aos="fade-up" data-aos-delay="400" data-aos-duration="600">
                            <img class="mr-4" src="assets/images/service1.png" alt="Web Development">
                            <div class="media-body">
                                <h5>Web Development</h5>
                                Donec volutpat tincidunt neque, vitae lobortis libero mattis sed tempus.
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="media" data-aos="fade-up" data-aos-delay="600" data-aos-duration="800">
                            <img class="mr-4" src="assets/images/service5.png" alt="Web Development">
                            <div class="media-body">
                                <h5>Elaboration of the core</h5>
                                Donec volutpat tincidunt neque, vitae lobortis libero mattis sed tempus.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Services End -->
    <!-- Featured Start -->
    <section class="featured">
        <div class="container">
            <div class="row">
                <div class="col-md-6" data-aos="fade-right" data-aos-delay="400" data-aos-duration="800">
                    <div class="title">
                        <h6 class="title-primary">about Us</h6>
                        <h1 class="title-blue">CONNECT. PLAY. EXPERIENCE.</h1>
                    </div>
                    <p>At FunFusion, we believe that great experiences are even better when shared. Our platform connects people with similar interests to enjoy activities together—whether in the real world or online.

                        Love outdoor adventures? Find partners for sports, hiking, escape games, and more. Prefer virtual fun? Join gaming sessions, movie nights, or live-streamed events. With our smart matchmaking system, you'll easily meet like-minded people and create unforgettable moments.
                        
                        Join us and start exploring new activities with new friends today!</p>
                    <div class="media-element d-flex justify-content-between">
                        <div class="media">
                            <i class="fa fa-magic mr-4"></i>
                            <div class="media-body">
                                <h5>any offer</h5>
                                New York, United States
                            </div>
                        </div>
                        <div class="media">
                            <i class="fa fa-magic mr-4"></i>
                            <div class="media-body">
                                <h5>any offer</h5>
                                New York, United States
                            </div>
                        </div>
                    </div>
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
    <!-- Recent Posts Start -->
    <section class="recent-posts">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="single-rpost d-sm-flex align-items-center" data-aos="fade-right" data-aos-duration="800">
                        <div class="post-content text-sm-right">
                            <time datetime="2019-04-06T13:53">15 Oct, 2019</time>
                            <h3><a href="#">Proudly for us to build stylish</a></h3>
                            <p><a href="#">Seanding</a>, <a href="#">Website</a>, <a href="#">E-commerce</a></p>
                            <a class="post-btn" href="#"><i class="fa fa-arrow-right"></i></a>
                        </div>
                        <div class="post-thumb">
                            <img class="img-fluid" src="assets/images/post1.jpg" alt="Post 1">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="single-rpost d-sm-flex align-items-center" data-aos="fade-left" data-aos-duration="800">
                        <div class="post-thumb">
                            <img class="img-fluid" src="assets/images/post2.jpg" alt="Post 1">
                        </div>
                        <div class="post-content">
                            <time datetime="2019-04-06T13:53">15 Oct, 2019</time>
                            <h3><a href="#">Remind me to water the plants</a></h3>
                            <p><a href="#">Seanding</a>, <a href="#">Website</a>, <a href="#">E-commerce</a></p>
                            <a class="post-btn" href="#"><i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="single-rpost d-sm-flex align-items-center" data-aos="fade-right" data-aos-delay="200" data-aos-duration="800">
                        <div class="post-content text-sm-right">
                            <time datetime="2019-04-06T13:53">15 Oct, 2019</time>
                            <h3><a href="#">Add apples to the grocery list</a></h3>
                            <p><a href="#">Seanding</a>, <a href="#">Website</a>, <a href="#">E-commerce</a></p>
                            <a class="post-btn" href="#"><i class="fa fa-arrow-right"></i></a>
                        </div>
                        <div class="post-thumb">
                            <img class="img-fluid" src="assets/images/post3.jpg" alt="Post 1">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="single-rpost d-sm-flex align-items-center" data-aos="fade-left" data-aos-delay="200" data-aos-duration="800">
                        <div class="post-thumb">
                            <img class="img-fluid" src="assets/images/post4.jpg" alt="Post 1">
                        </div>
                        <div class="post-content">
                            <time datetime="2019-04-06T13:53">15 Oct, 2019</time>
                            <h3><a href="#">Make it warmer downstairs</a></h3>
                            <p><a href="#">Seanding</a>, <a href="#">Website</a>, <a href="#">E-commerce</a></p>
                            <a class="post-btn" href="#"><i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <a href="#" class="btn btn-primary">See More</a>
            </div>
        </div>
    </section>
    <!-- Recent Posts End -->
    <!-- Trust Start -->
    <section class="trust">
        <div class="container">
            <div class="row">
                <div class="offset-xl-1 col-xl-6" data-aos="fade-right" data-aos-delay="200" data-aos-duration="800">
                    <div class="title">
                        <h6 class="title-primary">about Tamplate</h6>
                        <h1>a rich featured, epic & premium work.</h1>
                    </div>
                    <p>Suspendisse facilisis commodo lobortis. Nullam mollis lobortis ex vel faucibus. Proin nec viverra turpis. Nulla eget justo scelerisque, pretium purus vel, congue libero. Suspendisse potenti.</p>
                    <h5>Web Design & Development</h5>
                    <ul class="list-unstyled">
                        <li>Web Content</li>
                        <li>Website other</li>
                        <li>Fashion</li>
                        <li>Moblie & Tablette</li>
                    </ul>
                </div>
                <div class="col-xl-5 gallery">
                    <div class="row no-gutters h-100" id="lightgallery">
                        <a href="https://lorempixel.com/600/400/" class="w-50 h-100 gal-img" data-aos="fade-up" data-aos-delay="200" data-aos-duration="400">
                            <img class="img-fluid" src="assets/images/gallery1.jpg" alt="Gallery Image">
                            <i class="fa fa-caret-right"></i>
                        </a>
                        <a href="https://lorempixel.com/600/400/" class="w-50 h-50 gal-img" data-aos="fade-up" data-aos-delay="400" data-aos-duration="600">
                            <img class="img-fluid" src="assets/images/gallery2.jpg" alt="Gallery Image">
                            <i class="fa fa-caret-right"></i>
                        </a>
                        <a href="https://lorempixel.com/600/400/" class="w-50 h-50 gal-img gal-img3" data-aos="fade-up" data-aos-delay="0" data-aos-duration="600">
                            <img class="img-fluid" src="assets/images/gallery3.jpg" alt="Gallery Image">
                            <i class="fa fa-caret-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Trust End -->
   
    <!-- Pricing Start -->
    <section class="pricing-table" id="Offers-and-challenges">
        <div class="container">
            <div class="title text-center">
                <h6 class="title-primary">Our Offers and Challenges</h6>
                <h1 class="title-blue">Offers Table List</h1>
            </div>
            <!-- Offers Slider -->
            <div class="swiper-container offers-slider">
                <div class="swiper-wrapper" id="offers-wrapper">
                    <!-- Offers will be populated dynamically via JavaScript -->
                </div>
                <!-- Add Custom Pagination -->
                <div class="swiper-pagination-custom"></div>
            </div>
            <!-- No Offers Found Message -->
            <div class="no-offers" id="no-offers-message">No offers found.</div>
        </div>
    </section>
    <!-- Pricing End -->
    <!-- Testimonial and Clients Start -->
    <section class="testimonial-and-clients">
        <div class="container">
            <div class="testimonials">
                <div class="swiper-container test-slider">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide text-center">
                            <div class="row">
                                <div class="offset-lg-1 col-lg-10">
                                    <div class="test-img" data-aos="fade-up" data-aos-delay="0" data-aos-offset="0"><img src="assets/images/test1.png" alt="Testimonial 1"></div>
                                    <h5 data-aos="fade-up" data-aos-delay="200" data-aos-duration="600" data-aos-offset="0">John</h5>
                                    <span data-aos="fade-up" data-aos-delay="400" data-aos-duration="600" data-aos-offset="0">UI/UX Designer</span>
                                    <p data-aos="fade-up" data-aos-delay="600" data-aos-duration="600" data-aos-offset="0">Ash's tactics & books have helped me a lot in my understanding on how social media advertising works. I can say that he is one of the best development professionals I have dealt with so far. His experience is great & he is such a great & pleasant person to work with as he understands what you are</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide text-center">
                            <div class="row">
                                <div class="offset-lg-1 col-lg-10">
                                    <div class="test-img" data-aos="fade-up" data-aos-delay="0" data-aos-offset="0"><img src="assets/images/test1.png" alt="Testimonial 1"></div>
                                    <h5 data-aos="fade-up" data-aos-delay="200" data-aos-duration="600" data-aos-offset="0">John</h5>
                                    <span data-aos="fade-up" data-aos-delay="400" data-aos-duration="600" data-aos-offset="0">UI/UX Designer</span>
                                    <p data-aos="fade-up" data-aos-delay="600" data-aos-duration="600" data-aos-offset="0">Ash's tactics & books have helped me a lot in my understanding on how social media advertising works. I can say that he is one of the best development professionals I have dealt with so far. His experience is great & he is such a great & pleasant person to work with as he understands what you are</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide text-center">
                            <div class="row">
                                <div class="offset-lg-1 col-lg-10">
                                    <div class="test-img" data-aos="fade-up" data-aos-delay="0" data-aos-offset="0"><img src="assets/images/test1.png" alt="Testimonial 1"></div>
                                    <h5 data-aos="fade-up" data-aos-delay="200" data-aos-duration="600" data-aos-offset="0">John</h5>
                                    <span data-aos="fade-up" data-aos-delay="400" data-aos-duration="600" data-aos-offset="0">UI/UX Designer</span>
                                    <p data-aos="fade-up" data-aos-delay="600" data-aos-duration="600" data-aos-offset="0">Ash's tactics & books have helped me a lot in my understanding on how social media advertising works. I can say that he is one of the best development professionals I have dealt with so far. His experience is great & he is such a great & pleasant person to work with as he understands what you are</p>
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
    </section>
    <!-- Testimonial and Clients End -->
    <!-- Call To Action 2 Start -->
    <section class="cta cta2" data-aos="fade-up" data-aos-delay="0">
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
    </section>
    <!-- Call To Action 2 End -->
    <!-- Footer Start -->
    <footer>
        <div class="footer-widgets">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-xl-3">
                        <div class="single-widget contact-widget" data-aos="fade-up" data-aos-delay="0">
                            <h6 class="widget-tiltle"> </h6>
                            <p>By subscribing to our mailing list you will always be update with the latest news from us.</p>
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
                                    <h6><a href="#">@Collis,</a> We Planned to Update the Enavto Author Payment Method Soon!</h6>
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
                                <a class="rcnt-img" href="#"><img src="assets/images/rcnt-post1.png" alt="Recent Post"></a>
                                <div class="media-body ml-3">
                                    <h6><a href="#">An engaging</a></h6>
                                    <p><i class="fa fa-user"></i>Mano <i class="fa fa-eye"></i> 202 Views</p>
                                </div>
                            </div>
                            <div class="media">
                                <a class="rcnt-img" href="#"><img src="assets/images/rcnt-post2.png" alt="Recent Post"></a>
                                <div class="media-body ml-3">
                                    <h6><a href="#">Statistics and analysis. The key to succes.</a></h6>
                                    <p><i class="fa fa-user"></i>Rosias <i class="fa fa-eye"></i> 20 Views</p>
                                </div>
                            </div>
                            <div class="media">
                                <a class="rcnt-img" href="#"><img src="assets/images/rcnt-post3.png" alt="Recent Post"></a>
                                <div class="media-body ml-3">
                                    <h6><a href="#">Envato Meeting turns into a photoshooting.</a></h6>
                                    <p><i class="fa fa-user"></i>Kien <i class="fa fa-eye"></i> 74 Views</p>
                                </div>
                            </div>
                            <div class="media">
                                <a class="rcnt-img" href="#"><img src="assets/images/rcnt-post4.png" alt="Recent Post"></a>
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
                                    <input class="field form-control" name="subscribe" type="email" placeholder="Email Address">
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
        <div class="foot-note">
            <div class="container">
                <div class="footer-content text-center text-lg-left d-lg-flex justify-content-between align-items-center">
                    <p class="mb-0" data-aos="fade-right" data-aos-offset="0"> Copyright © FunFusion 2025 <a href="https://freehtml5.co/multipurpose" target="_blank" class="fh5-link"></a></p>
                    <p class="mb-0" data-aos="fade-left" data-aos-offset="0"><a href="#">Terms Of Use</a><a href="#">Privacy & Security Statement</a></p>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer End -->
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
    <script>
        // Initialize Swiper for Offers Slider
        let offersSlider;

        const allOffers = <?php echo $allOffersJson; ?>;

        function initializeSlider(offers) {
            // Destroy existing slider if it exists
            if (offersSlider) {
                offersSlider.destroy(true, true);
            }

            // Group offers into pairs
            const groupedOffers = [];
            for (let i = 0; i < offers.length; i += 2) {
                groupedOffers.push(offers.slice(i, i + 2));
            }

            // Generate new slides
            const wrapper = document.getElementById('offers-wrapper');
            wrapper.innerHTML = '';
            groupedOffers.forEach(group => {
                const slide = document.createElement('div');
                slide.className = 'swiper-slide';
                slide.innerHTML = group.map(offer => offer.html).join('');
                wrapper.appendChild(slide);
            });

            // Initialize new Swiper instance
            offersSlider = new Swiper('.offers-slider', {
                slidesPerView: 1,
                spaceBetween: 20,
                pagination: {
                    el: '.swiper-pagination-custom',
                    clickable: true,
                    renderBullet: function (index, className) {
                        return '<span class="' + className + '">' + (index + 1) + '</span>';
                    },
                },
                loop: groupedOffers.length > 1, // Only loop if more than one slide
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
            });

            // Show or hide the slider and "No offers found" message
            const noOffersMessage = document.getElementById('no-offers-message');
            if (offers.length === 0) {
                document.querySelector('.offers-slider').style.display = 'none';
                document.querySelector('.swiper-pagination-custom').style.display = 'none';
                noOffersMessage.style.display = 'block';
            } else {
                document.querySelector('.offers-slider').style.display = 'block';
                document.querySelector('.swiper-pagination-custom').style.display = 'flex';
                noOffersMessage.style.display = 'none';
            }
        }

        function searchOffers() {
            const searchInput = document.getElementById('s').value.trim().toLowerCase();
            const filteredOffers = allOffers.filter(offer => offer.name.toLowerCase().includes(searchInput));
            initializeSlider(filteredOffers);

            // Scroll to the Offers section
            document.getElementById('Offers-and-challenges').scrollIntoView({ behavior: 'smooth' });
        }

        // Show notification for expiring offers
        function showExpiringOffersNotification() {
            const expiringOffers = <?php echo $expiringOffersJson; ?>;
            const notification = document.getElementById('expiration-notification');
            const offersList = document.getElementById('expiring-offers-list');

            // Debugging: Log expiring offers to console
            console.log('Expiring Offers:', expiringOffers);

            if (expiringOffers.length > 0) {
                offersList.innerHTML = expiringOffers.map(offer => 
                    `<p>Offer "${offer.type}" expires on ${offer.date_expiration}!</p>`
                ).join('');
                notification.classList.add('show');
            } else {
                // Debugging: Show a notification even if no offers are expiring
                offersList.innerHTML = '<p>No offers are expiring within the next 24 hours.</p>';
                notification.classList.add('show');
            }
        }

        function closeNotification(event) {
            event.stopPropagation(); // Prevent the click from triggering the scroll
            const notification = document.getElementById('expiration-notification');
            notification.classList.remove('show');
        }

        function scrollToOffersSection(event) {
            // Only scroll if the click is not on the close button
            if (!event.target.classList.contains('close-btn')) {
                document.getElementById('Offers-and-challenges').scrollIntoView({ behavior: 'smooth' });
                closeNotification(event); // Close the notification after scrolling
            }
        }

        // Initial slider setup and notification
        document.addEventListener('DOMContentLoaded', function () {
            initializeSlider(allOffers);
            showExpiringOffersNotification();

            // Add event listener for Enter key on the header search input
            document.getElementById('s').addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // Prevent form submission
                    searchOffers();
                }
            });
        });
    </script>
</body>
</html>