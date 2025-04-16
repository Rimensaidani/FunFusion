<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'funfusion';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Récupération du type de défi depuis l'URL
    $type = isset($_GET['type']) ? $_GET['type'] : null;
    
    // Construction de la requête SQL
    if ($type && in_array($type, ['mission', 'quiz', 'mini_jeu'])) {
        $stmt = $pdo->prepare("SELECT * FROM challenges WHERE type = :type ORDER BY creation_date DESC");
        $stmt->execute([':type' => $type]);
    } else {
        $stmt = $pdo->query("SELECT * FROM challenges ORDER BY creation_date DESC");
    }
    
    $defis = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FunFusion - Liste des Défis</title>
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
        .defis-container {
            padding: 100px 0 80px;
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .defi-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
        }
        .defi-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .defi-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 15px;
            margin: -25px -25px 20px -25px;
            border-radius: 10px 10px 0 0;
        }
        .defi-title {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .defi-type {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            text-transform: uppercase;
        }
        .defi-date {
            color: #6c757d;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }
        .defi-score {
            font-weight: bold;
            color: #2575fc;
            font-size: 1.1rem;
        }
        .page-title {
            text-align: center;
            margin-bottom: 50px;
        }
        .page-title h1 {
            color: #2575fc;
            font-weight: 700;
        }
        .btn-participate {
            background: linear-gradient(135deg, #ff00aa 0%, #ff6b6b 100%);
            border: none;
            padding: 8px 20px;
            border-radius: 30px;
            color: white;
            margin-top: 15px;
            display: inline-block;
            text-decoration: none;
            transition: all 0.3s;
        }
        .btn-participate:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,0,170,0.3);
            color: white;
        }
        .type-badge {
            font-size: 1rem;
            padding: 5px 15px;
            border-radius: 20px;
            margin-bottom: 20px;
            display: inline-block;
        }
        .mission-badge {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
        }
        .quiz-badge {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }
        .mini-jeu-badge {
            background: linear-gradient(135deg, #f46b45 0%, #eea849 100%);
            color: white;
        }
        .no-challenges {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="css-loader">
        <div class="loader-inner line-scale d-flex align-items-center justify-content-center"></div>
    </div>

    <header class="position-absolute w-100">
        <div class="container">
            <div class="top-header d-none d-sm-flex justify-content-between align-items-center">
                <div class="contact">
                    <a href="tel:+2162567890" class="tel"><i class="fa fa-phone" aria-hidden="true"></i>+2162567890</a>
                    <a href="mailto:FunFusion@gmail.com"><i class="fa fa-envelope" aria-hidden="true"></i>FunFusion@gmail.com</a>
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
                <a class="navbar-brand" href="index.php"><img src="assets/images/logo_site.png" height="65" alt="logo FunFusion"></a>
                <h2>FunFusion</h2>
                <div class="group d-flex align-items-center">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <a class="login-icon d-sm-none" href="#"><i class="fa fa-user"></i></a>
                    <a class="cart" href="#"><i class="fa fa-shopping-cart"></i></a>
                </div>
                <a class="search-icon d-none d-md-block" href="#"><i class="fa fa-search"></i></a>
                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
                        <li class="nav-item"><a class="nav-link" href="challenges-and-offers.php">Offers and Challenges</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Activities</a></li>
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

    <div class="defis-container">
        <div class="container">
            <div class="page-title">
                <?php if ($type): ?>
                    <?php 
                    $badge_class = '';
                    $type_name = '';
                    switch($type) {
                        case 'mission': 
                            $badge_class = 'mission-badge';
                            $type_name = 'Missions';
                            break;
                        case 'quiz': 
                            $badge_class = 'quiz-badge';
                            $type_name = 'Quizzes';
                            break;
                        case 'mini_jeu': 
                            $badge_class = 'mini-jeu-badge';
                            $type_name = 'Mini Games';
                            break;
                    }
                    ?>
                    <span class="type-badge <?= $badge_class ?>"><?= $type_name ?></span>
                <?php endif; ?>
                <h1>Our Exciting Challenges</h1>
                <p>Take on these challenges and win exceptional rewards!</p>
            </div>

            <div class="row">
                <?php if (!empty($defis)): ?>
                    <?php foreach ($defis as $defi): ?>
                    <div class="col-md-4">
                        <div class="defi-card">
                            <div class="defi-header">
                                <h3 class="defi-title"><?= htmlspecialchars($defi['title']) ?></h3>
                                <span class="defi-type"><?= strtoupper(htmlspecialchars($defi['type'])) ?></span>
                            </div>
                            <div class="defi-body">
                                <p class="defi-date">Date: <?= htmlspecialchars($defi['creation_date']) ?></p>
                                <p>Score: <span class="defi-score"><?= htmlspecialchars($defi['score']) ?> points</span></p>
                                <a href="#" class="btn-participate">Participate</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="no-challenges">
                            <h3>No challenges available at the moment</h3>
                            <p>Please check back later or try another challenge category</p>
                            <a href="challenges-and-offers.php" class="btn btn-primary mt-3">Back to Challenges</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer>
        <!-- [Votre footer original] -->
    </footer>

    <script src="assets/js/jquery-3.3.1.js"></script>
    <script src="assets/js/bootstrap.bundle.js"></script>
    <script src="assets/js/loaders.css.js"></script>
    <script src="assets/js/aos.js"></script>
    <script src="assets/js/swiper.min.js"></script>
    <script src="assets/js/lightgallery-all.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>