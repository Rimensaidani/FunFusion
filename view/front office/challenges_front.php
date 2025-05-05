<?php
class ChallengesController {
    public function ajouterchallenges($title, $type, $creation_date) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("INSERT INTO challenges (title, type, creation_date, score) VALUES (?, ?, ?, 0)");
            $stmt->execute([$title, $type, $creation_date]);
            return true;
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    public function afficherChallenges() {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->query("SELECT * FROM challenges");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return [];
        }
    }

    public function deleteChallenge($id) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("DELETE FROM challenges WHERE id_defi = ?");
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    public function modifierChallenge($id, $title, $type, $creation_date) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("UPDATE challenges SET title = ?, type = ?, creation_date = ? WHERE id_defi = ?");
            $stmt->execute([$title, $type, $creation_date, $id]);
            return true;
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    public function getChallengeTypeByTitle($title) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("SELECT type FROM challenges WHERE title = ?");
            $stmt->execute([$title]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['type'] : 'mission';
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return 'mission';
        }
    }
}

include 'C:\xamppp\htdocs\FunFusion\config.php';
$controller = new ChallengesController();
$message = "";

// Handle add challenge
if (isset($_POST['ajouter'])) {
    $title = $_POST['title'];
    $type = $controller->getChallengeTypeByTitle($title);
    $creation_date = $_POST['creation_date'];

    if ($controller->ajouterchallenges($title, $type, $creation_date)) {
        $message = "<div class='alert success'>✅ Challenge ajouté avec succès !</div>";
        echo "<script>window.location.href = window.location.href;</script>";
    } else {
        $message = "<div class='alert error'>❌ Erreur lors de l'ajout.</div>";
    }
}

// Handle delete challenge
if (isset($_POST['supprimer'])) {
    $id = $_POST['id_defi'];
    if ($controller->deleteChallenge($id)) {
        $message = "<div class='alert warning'>🗑️ Challenge supprimé avec succès !</div>";
        echo "<script>window.location.href = window.location.href;</script>";
    } else {
        $message = "<div class='alert error'>❌ Erreur lors de la suppression.</div>";
    }
}

// Handle edit challenge
if (isset($_POST['modifier'])) {
    $id = $_POST['id_defi'];
    $title = $_POST['title'];
    $type = $_POST['type'];
    $creation_date = $_POST['creation_date'];

    if ($controller->modifierChallenge($id, $title, $type, $creation_date)) {
        $message = "<div class='alert info'>✏️ Challenge modifié avec succès !</div>";
        echo "<script>window.location.href = window.location.href;</script>";
    } else {
        $message = "<div class='alert error'>❌ Erreur lors de la modification.</div>";
    }
}

$listeChallenges = $controller->afficherChallenges();
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="FunFusion Challenges Management" />
    <meta name="author" content="FunFusion" />
    <title>FunFusion | Challenges</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-dark: #0f0e17;
            --primary-blue: #2575fc;
            --electric-blue: #00d4ff;
            --neon-pink: #ff00aa;
            --gaming-purple: #6a11cb;
            --light-gray: #f8f9fa;
            --success: #00b894;
            --warning: #fdcb6e;
            --danger: #d63031;
            --info: #0984e3;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary-dark), #1a1a2e);
            color: white;
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
        }
        
        header {
            background: linear-gradient(to right, var(--primary-dark), var(--gaming-purple)) !important;
            box-shadow: 0 0 15px rgba(0, 212, 255, 0.3);
            border-bottom: 1px solid rgba(0, 212, 255, 0.2);
        }
        
        .top-header {
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .top-header a {
            color: white;
            margin-right: 15px;
            font-size: 14px;
            text-decoration: none;
        }
        
        .top-header a:hover {
            color: var(--electric-blue);
        }
        
        .top-header i {
            margin-right: 5px;
        }
        
        .social li {
            display: inline-block;
            margin-left: 10px;
        }
        
        .social a {
            color: white;
            font-size: 16px;
        }
        
        .navbar-brand img {
            margin-right: 10px;
        }
        
        .navbar-brand h2 {
            display: inline-block;
            color: white;
            font-weight: bold;
            margin: 0;
            vertical-align: middle;
        }
        
        .navbar-light .navbar-nav .nav-link {
            color: white;
            font-weight: 500;
            padding: 10px 15px;
        }
        
        .navbar-light .navbar-nav .nav-link:hover {
            color: var(--electric-blue);
        }
        
        .search-icon, .login-icon, .cart {
            color: white;
            font-size: 18px;
            margin-left: 15px;
        }
        
        .search-form {
            position: absolute;
            right: 15px;
            top: 100%;
            display: none;
            z-index: 1000;
            padding: 10px;
            border-radius: 5px;
        }
        
        .main-container {
            padding: 20px;
            margin-top: 120px;
        }
        
        .navbar-custom {
            background: linear-gradient(to right, var(--primary-dark), var(--gaming-purple)) !important;
            box-shadow: 0 0 15px rgba(0, 212, 255, 0.3);
            border-bottom: 1px solid rgba(0, 212, 255, 0.2);
        }
        
        .card-custom {
            background: rgba(15, 14, 23, 0.7);
            border: 1px solid rgba(0, 212, 255, 0.1);
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(5px);
        }
        
        .table-custom {
            width: 100%;
            background: rgba(15, 14, 23, 0.5);
            color: white;
            border-collapse: collapse;
        }
        
        .table-custom thead th {
            background: rgba(106, 17, 203, 0.5);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid var(--electric-blue);
        }
        
        .table-custom tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(0, 212, 255, 0.1);
        }
        
        .front-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        
        .front-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .front-table thead th {
            background: rgba(0, 212, 255, 0.2);
            color: white;
            padding: 15px;
            text-align: left;
        }
        
        .front-table tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(0, 212, 255, 0.1);
        }
        
        .form-input-custom {
            width: 100%;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(0, 212, 255, 0.3);
            border-radius: 5px;
            color: white;
        }
        
        .form-input-custom:disabled {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 212, 255, 0.1);
            color: #aaa;
        }
        
        .editable {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(0, 212, 255, 0.3) !important;
            color: white !important;
        }
        
        .btn-action {
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            margin-right: 5px;
        }
        
        .btn-edit {
            background: linear-gradient(135deg, var(--gaming-purple), var(--primary-blue));
            color: white;
        }
        
        .btn-delete {
            background: linear-gradient(135deg, var(--neon-pink), #ff4b2b);
            color: white;
        }
        
        .btn-add {
            background: linear-gradient(135deg, var(--success), #96c93d);
            color: white;
            padding: 10px 20px;
        }
        
        .btn-save {
            background: linear-gradient(135deg, #00b894, #55efc4);
            color: white;
        }
        
        .btn-cancel {
            background: linear-gradient(135deg, #636e72, #b2bec3);
            color: white;
        }
        
        .btn-participate {
            background: linear-gradient(135deg, var(--gaming-purple), var(--primary-blue));
            color: white;
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }
        
        .success {
            background-color: rgba(0, 184, 148, 0.1);
            color: var(--success);
            border-left-color: var(--success);
        }
        
        .error {
            background-color: rgba(214, 48, 49, 0.1);
            color: var(--danger);
            border-left-color: var(--danger);
        }
        
        .warning {
            background-color: rgba(253, 203, 110, 0.1);
            color: var(--warning);
            border-left-color: var(--warning);
        }
        
        .error-field {
            border: 2px solid var(--danger) !important;
        }
        
        .error-message {
            color: var(--danger);
            font-size: 0.8rem;
            margin-top: 5px;
            display: none;
        }
        
        .edit-controls {
            display: inline-flex;
        }
        
        .error-container {
            position: relative;
        }
        
        select {
            color: #111;
            background-color: #fff;
            font-weight: bold;
            font-size: 16px;
            border: 1px solid #007bff;
            border-radius: 6px;
            padding: 8px;
        }

        option {
            color: #111;
            background-color: #fff;
        }
        
        .front-form {
            background: rgba(15, 14, 23, 0.7);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .front-form-title {
            font-size: 20px;
            margin-bottom: 15px;
            color: var(--electric-blue);
        }
        
        .front-btn {
            background: linear-gradient(135deg, var(--primary-blue), var(--electric-blue));
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .edit-form-front {
            display: none;
        }
        
        /* Calendar Styles */
        #calendar-container {
            display: none; /* Initially hidden */
            background: rgba(15, 14, 23, 0.7);
            border: 1px solid rgba(0, 212, 255, 0.1);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        
        #calendar {
            max-width: 100%;
            margin: 0 auto;
            color: white;
        }
        
        .fc {
            background: transparent;
        }
        
        .fc .fc-toolbar {
            background: rgba(106, 17, 203, 0.5);
            border-radius: 5px;
            padding: 10px;
        }
        
        .fc .fc-toolbar-title {
            color: var(--electric-blue);
            font-weight: bold;
            font-size: 1.5em;
        }
        
        .fc .fc-button {
            background: linear-gradient(135deg, var(--gaming-purple), var(--primary-blue));
            border: none;
            color: white;
            padding: 5px 10px;
            margin: 0 5px;
        }
        
        .fc .fc-button:hover {
            background: linear-gradient(135deg, var(--primary-blue), var(--electric-blue));
        }
        
        .fc .fc-daygrid-day {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 212, 255, 0.1);
        }
        
        .fc .fc-daygrid-day-number {
            color: white;
        }
        
        .fc .fc-event {
            border: none;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 0.9em;
            cursor: pointer;
            margin: 2px;
        }
        
        .fc .fc-event-mission {
            background: var(--primary-blue);
            color: white;
        }
        
        .fc .fc-event-quiz {
            background: var(--gaming-purple);
            color: white;
        }
        
        .fc .fc-event-mini_jeu {
            background: var(--neon-pink);
            color: white;
        }
        
        .fc .fc-event-title {
            color: white;
            font-weight: bold;
            white-space: normal;
        }
        
        .calendar-icon {
            cursor: pointer;
            font-size: 24px;
            color: var(--electric-blue);
            margin-bottom: 10px;
        }
        
        #challenge-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(15, 14, 23, 0.9);
            border: 1px solid var(--electric-blue);
            border-radius: 8px;
            padding: 20px;
            z-index: 1000;
            box-shadow: 0 0 15px rgba(0, 212, 255, 0.3);
            color: white;
            max-width: 400px;
            text-align: center;
        }
        
        #challenge-popup h3 {
            margin-top: 0;
            color: var(--electric-blue);
        }
        
        #challenge-popup p {
            margin: 10px 0;
        }
        
        #challenge-popup .btn-close {
            background: linear-gradient(135deg, var(--neon-pink), #ff4b2b);
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <header class="position-absolute w-100">
        <div class="container">
            <div class="top-header d-none d-sm-flex justify-content-between align-items-center">
                <div class="contact">
                    <a href="tel:+212567890" class="tel"><i class="fa fa-phone" aria-hidden="true"></i>+212567890</a>
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
                <a class="navbar-brand" href="index.php">
                    <img src="assets/images/logo_site.png" height="65" alt="logo FunFusion">
                    <h2>FunFusion</h2>
                </a>
                <div class="group d-flex align-items-center">
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <a class="login-icon d-sm-none" href="#"><i class="fa fa-user"></i></a>
                    <a class="cart" href="#"><i class="fa fa-shopping-cart"></i></a>
                </div>
                <a class="search-icon d-none d-md-block" href="#"><i class="fa fa-search"></i></a>
                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php#About-Us">About Us</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php#Offers-and-challenges">Offers and challenges</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php#Activities">Activities</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php#Contact-Us">Contact Us</a></li>
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

    <div class="main-container">
        <div class="container-fluid">
            <h1><i class="fas fa-trophy me-2"></i>CHALLENGES</h1>
            
            <?= $message ?>
            
            <i class="fas fa-calendar-alt calendar-icon" id="calendar-toggle"></i>
            <div id="calendar-container">
                <div id="calendar"></div>
            </div>
            
            <div class="front-form">
                <h2 class="front-form-title">AJOUTER UN DÉFI</h2>
                <form method="post" id="front-add-form">
                    <div class="form-group error-container">
                        <label>Titre du Défi:</label>
                        <select class="form-input-custom" name="title" id="front-title">
                            <option value="">Sélectionner un titre</option>
                            <?php foreach ($listeChallenges as $challenge): ?>
                                <option value="<?= htmlspecialchars($challenge['title']) ?>">
                                    <?= htmlspecialchars($challenge['title']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="error-message" id="front-title-error"></div>
                    </div>
                    
                    <div class="form-group error-container">
                        <label>Date de création:</label>
                        <input type="date" class="form-input-custom" name="creation_date" id="front-date">
                        <div class="error-message" id="front-date-error"></div>
                    </div>
                    
                    <button type="submit" name="ajouter" class="front-btn">
                        <i class="fas fa-plus"></i> VALIDER
                    </button>
                </form>
            </div>
            
            <div class="front-card">
                <table class="front-table">
                    <thead>
                        <tr>
                            <th>ID_DEFI</th>
                            <th>TITLE</th>
                            <th>TYPE</th>
                            <th>DATE_CREATION</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listeChallenges as $challenge): ?>
                        <tr id="front-row-<?= $challenge['id_defi'] ?>">
                            <td>DF<?= str_pad($challenge['id_defi'], 3, '0', STR_PAD_LEFT) ?></td>
                            <td><?= htmlspecialchars($challenge['title']) ?></td>
                            <td><?= ucfirst(str_replace('_', ' ', $challenge['type'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($challenge['creation_date'])) ?></td>
                            <td>
                                <button type="button" class="btn-action btn-edit" onclick="enableFrontEdit(<?= $challenge['id_defi'] ?>)">
                                    <i class="fas fa-edit"></i> Modifier
                                </button>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="id_defi" value="<?= $challenge['id_defi'] ?>">
                                    <button type="submit" name="supprimer" class="btn-action btn-delete">
                                        <i class="fas fa-trash-alt"></i> Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                        
                        <tr id="front-edit-<?= $challenge['id_defi'] ?>" class="edit-form-front">
                            <form method="post">
                                <input type="hidden" name="id_defi" value="<?= $challenge['id_defi'] ?>">
                                <td>DF<?= str_pad($challenge['id_defi'], 3, '0', STR_PAD_LEFT) ?></td>
                                <td>
                                    <select class="form-input-custom" name="title">
                                        <?php foreach ($listeChallenges as $existing): ?>
                                            <option value="<?= htmlspecialchars($existing['title']) ?>" <?= $challenge['title'] == $existing['title'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($existing['title']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-input-custom" name="type">
                                        <option value="mission" <?= $challenge['type'] == 'mission' ? 'selected' : '' ?>>Mission</option>
                                        <option value="quiz" <?= $challenge['type'] == 'quiz' ? 'selected' : '' ?>>Quiz</option>
                                        <option value="mini_jeu" <?= $challenge['type'] == 'mini_jeu' ? 'selected' : '' ?>>Mini Jeu</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="date" class="form-input-custom" name="creation_date" value="<?= $challenge['creation_date'] == '0000-00-00 00:00:00' ? '' : date('Y-m-d', strtotime($challenge['creation_date'])) ?>">
                                </td>
                                <td>
                                    <button type="submit" name="modifier" class="btn-action btn-save">
                                        <i class="fas fa-check"></i> Enregistrer
                                    </button>
                                    <button type="button" class="btn-action btn-cancel" onclick="cancelFrontEdit(<?= $challenge['id_defi'] ?>)">
                                        <i class="fas fa-times"></i> Annuler
                                    </button>
                                </td>
                            </form>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div id="challenge-popup">
                <h3 id="popup-title"></h3>
                <p id="popup-type"></p>
                <p id="popup-date"></p>
                <button class="btn-action btn-participate" onclick="participateInChallenge()">Participer</button>
                <button class="btn-close" onclick="closePopup()">Fermer</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Calendar toggle
            const calendarToggle = document.getElementById('calendar-toggle');
            const calendarContainer = document.getElementById('calendar-container');
            let calendar = null;

            calendarToggle.addEventListener('click', function() {
                if (calendarContainer.style.display === 'none' || calendarContainer.style.display === '') {
                    calendarContainer.style.display = 'block';
                    if (!calendar) {
                        // Initialize FullCalendar only when first shown
                        const calendarEl = document.getElementById('calendar');
                        calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            events: [
                                <?php
                                foreach ($listeChallenges as $challenge) {
                                    $id = htmlspecialchars($challenge['id_defi'], ENT_QUOTES, 'UTF-8');
                                    $title = htmlspecialchars($challenge['title'], ENT_QUOTES, 'UTF-8');
                                    $type = htmlspecialchars($challenge['type'], ENT_QUOTES, 'UTF-8');
                                    $creationDate = $challenge['creation_date'];
                                    $startDate = (strtotime($creationDate) !== false && $creationDate != '0000-00-00 00:00:00') ? date('Y-m-d', strtotime($creationDate)) : date('Y-m-d');
                                    $displayDate = (strtotime($creationDate) !== false && $creationDate != '0000-00-00 00:00:00') ? date('d/m/Y', strtotime($creationDate)) : date('d/m/Y');
                                    $typeDisplay = ucfirst(str_replace('_', ' ', $type));
                                    echo "{ id: '$id', title: '$title', start: '$startDate', className: 'fc-event-$type', extendedProps: { type: '$typeDisplay', date: '$displayDate' } },";
                                }
                                ?>
                            ],
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,timeGridDay'
                            },
                            height: 'auto',
                            contentHeight: 'auto',
                            aspectRatio: 1.5,
                            dayMaxEvents: true,
                            eventClick: function(info) {
                                document.getElementById('popup-title').textContent = info.event.title;
                                document.getElementById('popup-type').textContent = 'Type: ' + info.event.extendedProps.type;
                                document.getElementById('popup-date').textContent = 'Date: ' + info.event.extendedProps.date;
                                document.getElementById('challenge-popup').style.display = 'block';
                            },
                            themeSystem: 'bootstrap5'
                        });
                        calendar.render();
                    }
                } else {
                    calendarContainer.style.display = 'none';
                }
            });

            // Form validation
            const frontAddForm = document.getElementById('front-add-form');
            if (frontAddForm) {
                frontAddForm.addEventListener('submit', function(e) {
                    if (!validateFrontAddForm()) {
                        e.preventDefault();
                    }
                });
                
                document.getElementById('front-title').addEventListener('change', function() {
                    if (this.value) {
                        clearError('front-title-error');
                        this.classList.remove('error-field');
                    }
                });
                
                document.getElementById('front-date').addEventListener('change', function() {
                    validateDate(this, 'front-date-error');
                });
            }
        });
        
        function participateInChallenge() {
            const alertContainer = document.querySelector('.container-fluid');
            const alert = document.createElement('div');
            alert.className = 'alert success';
            alert.innerHTML = '✅ Vous avez rejoint le défi avec succès !';
            alertContainer.insertBefore(alert, alertContainer.firstChild);
            closePopup();
            setTimeout(() => alert.remove(), 3000);
        }
        
        function closePopup() {
            document.getElementById('challenge-popup').style.display = 'none';
        }
        
        function validateFrontAddForm() {
            let isValid = true;
            
            const title = document.getElementById('front-title');
            if (!title.value) {
                showError('front-title-error', 'Veuillez sélectionner un titre');
                title.classList.add('error-field');
                isValid = false;
            } else {
                clearError('front-title-error');
                title.classList.remove('error-field');
            }
            
            const date = document.getElementById('front-date');
            if (!validateDate(date, 'front-date-error')) {
                isValid = false;
            }
            
            return isValid;
        }
        
        function validateDate(field, errorId) {
            if (!field.value) {
                showError(errorId, 'La date est obligatoire');
                field.classList.add('error-field');
                return false;
            } else {
                clearError(errorId);
                field.classList.remove('error-field');
                return true;
            }
        }
        
        function enableFrontEdit(id) {
            document.getElementById(`front-row-${id}`).style.display = 'none';
            document.getElementById(`front-edit-${id}`).style.display = 'table-row';
        }
        
        function cancelFrontEdit(id) {
            document.getElementById(`front-row-${id}`).style.display = 'table-row';
            document.getElementById(`front-edit-${id}`).style.display = 'none';
        }
        
        function showError(id, message) {
            const errorElement = document.getElementById(id);
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }
        
        function clearError(id) {
            const errorElement = document.getElementById(id);
            errorElement.textContent = '';
            errorElement.style.display = 'none';
        }
    </script>
</body>
</html>