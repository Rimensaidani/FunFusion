<?php
class ChallengesController {
    public function ajouterchallenges($title, $type, $creation_date, $score) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("INSERT INTO challenges (title, type, creation_date, score) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $type, $creation_date, $score]);
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

    public function modifierChallenge($id, $title, $type, $creation_date, $score) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("UPDATE challenges SET title = ?, type = ?, creation_date = ?, score = ? WHERE id_defi = ?");
            $stmt->execute([$title, $type, $creation_date, $score, $id]);
            return true;
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    public function searchChallenges($searchTerm) {
        try {
            $pdo = config::getConnexion();
            $stmt = $pdo->prepare("SELECT * FROM challenges WHERE id_defi LIKE ? OR title LIKE ?");
            $searchTerm = "%" . $searchTerm . "%";
            $stmt->execute([$searchTerm, $searchTerm]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return [];
        }
    }

    public function getChallengeOfferStats() {
        try {
            $pdo = config::getConnexion();
            
            // Get all challenges
            $challengesStmt = $pdo->query("SELECT id_defi, title FROM challenges");
            $challenges = $challengesStmt->fetchAll(PDO::FETCH_ASSOC);
            
            $stats = [];
            foreach ($challenges as $challenge) {
                $id_defi = $challenge['id_defi'];
                
                // Get total offers for this challenge
                $totalStmt = $pdo->prepare("SELECT COUNT(*) as total FROM offres WHERE id_defi = ?");
                $totalStmt->execute([$id_defi]);
                $totalOffersForChallenge = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
                
                $challengeStats = [
                    'id_defi' => $id_defi,
                    'title' => $challenge['title'],
                    'total_offers' => $totalOffersForChallenge,
                    'offer_types' => []
                ];
                
                if ($totalOffersForChallenge > 0) {
                    // Get breakdown of offer types for this challenge
                    $stmt = $pdo->prepare("
                        SELECT type, COUNT(*) as count
                        FROM offres
                        WHERE id_defi = ?
                        GROUP BY type
                    ");
                    $stmt->execute([$id_defi]);
                    $offerTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    // Calculate percentages for each offer type
                    foreach ($offerTypes as $offer) {
                        $percentage = ($offer['count'] / $totalOffersForChallenge) * 100;
                        $challengeStats['offer_types'][] = [
                            'type' => $offer['type'],
                            'count' => $offer['count'],
                            'percentage' => $percentage
                        ];
                    }
                }
                
                $stats[] = $challengeStats;
            }
            
            return $stats;
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return [];
        }
    }
}

include 'C:\xamppp\htdocs\FunFusion\config.php';
$controller = new ChallengesController();
$message = "";

// Handle search
$searchTerm = isset($_POST['search']) ? trim($_POST['search']) : '';
if (!empty($searchTerm)) {
    $listeChallenges = $controller->searchChallenges($searchTerm);
} else {
    $listeChallenges = $controller->afficherChallenges();
}

// Fetch statistics data
$challengeStats = $controller->getChallengeOfferStats();

// Debugging: Log the challengeStats to check the data
error_log("Challenge Stats: " . print_r($challengeStats, true));

if (isset($_POST['ajouter'])) {
    $title = $_POST['title'];
    $type = $_POST['type'];
    $creation_date = $_POST['creation_date'];
    $score = $_POST['score'];

    if ($controller->ajouterchallenges($title, $type, $creation_date, $score)) {
        $message = "<div class='alert success'>✅ Challenge ajouté avec succès !</div>";
        echo "<script>window.location.href = window.location.href;</script>";
    } else {
        $message = "<div class='alert error'>❌ Erreur lors de l'ajout.</div>";
    }
}

if (isset($_POST['supprimer'])) {
    $id = $_POST['id_defi'];
    if ($controller->deleteChallenge($id)) {
        $message = "<div class='alert warning'>🗑️ Challenge supprimé avec succès !</div>";
        echo "<script>window.location.href = window.location.href;</script>";
    } else {
        $message = "<div class='alert error'>❌ Erreur lors de la suppression.</div>";
    }
}

if (isset($_POST['modifier'])) {
    $id = $_POST['id_defi'];
    $title = $_POST['title'];
    $type = $_POST['type'];
    $creation_date = $_POST['creation_date'];
    $score = $_POST['score'];

    if ($controller->modifierChallenge($id, $title, $type, $creation_date, $score)) {
        $message = "<div class='alert info'>✏️ Challenge modifié avec succès !</div>";
        echo "<script>window.location.href = window.location.href;</script>";
    } else {
        $message = "<div class='alert error'>❌ Erreur lors de la modification.</div>";
    }
}

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
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
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
            font-family: 'Rajdhani', 'Segoe UI', sans-serif;
            min-height: 100vh;
        }
        
        .gaming-navbar {
            background: linear-gradient(to right, var(--primary-dark), var(--gaming-purple)) !important;
            box-shadow: 0 0 15px rgba(0, 212, 255, 0.3);
            border-bottom: 1px solid rgba(0, 212, 255, 0.2);
        }
        
        .gaming-sidebar {
            background: linear-gradient(to bottom, var(--primary-dark), #1a1a2e) !important;
            border-right: 1px solid rgba(0, 212, 255, 0.1);
        }
        
        .gaming-card {
            background: rgba(15, 14, 23, 0.7);
            border: 1px solid rgba(0, 212, 255, 0.1);
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(5px);
        }
        
        .gaming-table {
            width: 100%;
            background: rgba(15, 14, 23, 0.5);
            color: white;
            border-collapse: collapse;
        }
        
        .gaming-table thead th {
            background: rgba(106, 17, 203, 0.5);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid var(--electric-blue);
        }
        
        .gaming-table tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(0, 212, 255, 0.1);
        }
        
        .form-input {
            width: 100%;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(0, 212, 255, 0.3);
            border-radius: 5px;
            color: white;
        }
        
        .form-input:disabled {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(0, 212, 255, 0.1) !important;
            color: #aaa !important;
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
        
        .btn-info {
            background: linear-gradient(135deg, var(--info), #74b9ff);
            color: white;
            padding: 10px 20px;
        }

        canvas {
            max-width: 100%;
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
        
        td {
            position: relative;
            padding-bottom: 25px !important;
        }
        select {
            color: #111;
            background-color: #fff;
            font-weight: bold;
            font-size: 16px;
            border: 1px solid #007bff;
            border-radius: 6px;
            padding: 8px;
            font-family: 'Segoe UI', sans-serif;
        }

        option {
            color: #111;
            background-color: #fff;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark gaming-navbar">
        <a class="navbar-brand ps-3" href="index.php">
            <i class="fas fa-gamepad me-2"></i>FUNFUSION
        </a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0" method="post" action="">
            <div class="input-group">
                <input class="form-control bg-dark text-white border-dark" type="text" name="search" placeholder="Search by ID or Title..." aria-label="Search" value="<?= htmlspecialchars($searchTerm) ?>" />
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li><a class="dropdown-item" href="#!">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark gaming-sidebar" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">CORE</div>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">GAMING</div>
                        <a class="nav-link" href="games.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-gamepad"></i></div>
                            My Games
                        </a>
                        <a class="nav-link" href="friends.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Friends
                        </a>
                        <a class="nav-link" href="achievements.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-trophy"></i></div>
                            Achievements
                        </a>
                        <div class="sb-sidenav-menu-heading">Communication</div>
                        <a class="nav-link active" href="challenges.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-trophy"></i></div>
                            Challenges
                        </a>
                        <a class="nav-link" href="offres.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-gift"></i></div>
                            Offres
                        </a>
                        <a class="nav-link" href="chat.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-comments"></i></div>
                            Chat
                        </a>
                        <a class="nav-link" href="events.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-calendar-star"></i></div>
                            Events
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    GamerPro123
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4"><i class="fas fa-trophy me-2"></i>Challenges</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Challenges</li>
                    </ol>
                    
                    <?= $message ?>
                    
                    <div class="card gaming-card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i> Challenges Management
                        </div>
                        <div class="card-body">
                            <table class="gaming-table">
                                <thead>
                                    <tr>
                                        <th>ID_DEFI</th>
                                        <th>TITLE</th>
                                        <th>TYPE</th>
                                        <th>DATE_CREATION</th>
                                        <th>SCORE</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($listeChallenges as $challenge): ?>
                                    <tr id="row-<?= $challenge['id_defi'] ?>">
                                        <form method="post" class="challenge-form edit-form">
                                            <td>
                                                <input type="hidden" name="id_defi" value="<?= $challenge['id_defi'] ?>">
                                                DF<?= str_pad($challenge['id_defi'], 3, '0', STR_PAD_LEFT) ?>
                                            </td>
                                            <td class="error-container">
                                                <input type="text" class="form-input" name="title" value="<?= htmlspecialchars($challenge['title']) ?>" disabled>
                                                <div class="error-message" id="edit-title-error-<?= $challenge['id_defi'] ?>"></div>
                                            </td>
                                            <td class="error-container">
                                                <select class="form-input" name="type" disabled>
                                                    <option value="mission" <?= $challenge['type'] == 'mission' ? 'selected' : '' ?>>Mission</option>
                                                    <option value="quiz" <?= $challenge['type'] == 'quiz' ? 'selected' : '' ?>>Quiz</option>
                                                    <option value="mini_jeu" <?= $challenge['type'] == 'mini_jeu' ? 'selected' : '' ?>>Mini Jeu</option>
                                                </select>
                                                <div class="error-message" id="edit-type-error-<?= $challenge['id_defi'] ?>"></div>
                                            </td>
                                            <td class="error-container">
                                                <input type="date" class="form-input" name="creation_date" value="<?= $challenge['creation_date'] == '0000-00-00 00:00:00' ? '' : date('Y-m-d', strtotime($challenge['creation_date'])) ?>" disabled>
                                                <div class="error-message" id="edit-date-error-<?= $challenge['id_defi'] ?>"></div>
                                            </td>
                                            <td class="error-container">
                                                <input type="number" class="form-input" name="score" value="<?= htmlspecialchars($challenge['score']) ?>" disabled>
                                                <div class="error-message" id="edit-score-error-<?= $challenge['id_defi'] ?>"></div>
                                            </td>
                                            <td class="actions-cell">
                                                <button type="button" class="btn-action btn-edit" onclick="enableEdit(<?= $challenge['id_defi'] ?>)">
                                                    <i class="fas fa-edit"></i> EDIT
                                                </button>
                                                <button type="submit" name="supprimer" class="btn-action btn-delete">
                                                    <i class="fas fa-trash-alt"></i> DELETE
                                                </button>
                                                <div class="edit-controls" style="display: none;">
                                                    <button type="submit" name="modifier" class="btn-action btn-save">
                                                        <i class="fas fa-check"></i> SAVE
                                                    </button>
                                                    <button type="button" class="btn-action btn-cancel" onclick="cancelEdit(<?= $challenge['id_defi'] ?>)">
                                                        <i class="fas fa-times"></i> CANCEL
                                                    </button>
                                                </div>
                                            </td>
                                        </form>
                                    </tr>
                                    <?php endforeach; ?>
                                    
                                    <tr>
                                        <form method="post" class="challenge-form" id="add-form">
                                            <td>#NEW</td>
                                            <td class="error-container">
                                                <input type="text" class="form-input" name="title" id="add-title" >
                                                <div class="error-message" id="title-error"></div>
                                            </td>
                                            <td class="error-container">
                                                <select class="form-input" name="type" id="add-type" >
                                                    <option value="mission">Mission</option>
                                                    <option value="quiz">Quiz</option>
                                                    <option value="mini_jeu">Mini Jeu</option>
                                                </select>
                                                <div class="error-message" id="type-error"></div>
                                            </td>
                                            <td class="error-container">
                                                <input type="date" class="form-input" name="creation_date" id="add-date" >
                                                <div class="error-message" id="date-error"></div>
                                            </td>
                                            <td class="error-container">
                                                <input type="number" class="form-input" name="score" id="add-score" min="51">
                                                <div class="error-message" id="score-error"></div>
                                            </td>
                                            <td>
                                                <button type="submit" name="ajouter" class="btn-action btn-add" id="add-button">
                                                    <i class="fas fa-plus"></i> ADD
                                                </button>
                                            </td>
                                        </form>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Statistics Section -->
                            <div class="mt-4 text-center">
                                <button type="button" class="btn-action btn-info" id="toggleStatsBtn">
                                    <i class="fas fa-chart-pie me-1"></i> Show Statistics
                                </button>
                            </div>

                            <div id="statsSection" class="mt-4" style="display: none;">
                                <h3 class="text-center mb-4">Offer Types per Challenge Statistics</h3>
                                <?php if (empty($challengeStats)): ?>
                                    <p class="text-center">No challenges available to display statistics.</p>
                                <?php else: ?>
                                    <div class="row justify-content-center">
                                        <?php foreach ($challengeStats as $stat): ?>
                                            <div class="col-md-4 mb-4">
                                                <div class="gaming-card p-3">
                                                    <h5 class="text-center"><?= htmlspecialchars($stat['title']) ?> (ID: DF<?= str_pad($stat['id_defi'], 3, '0', STR_PAD_LEFT) ?>)</h5>
                                                    <?php if ($stat['total_offers'] == 0): ?>
                                                        <p class="text-center">No offers for this challenge.</p>
                                                    <?php else: ?>
                                                        <canvas id="chart-<?= $stat['id_defi'] ?>" height="200"></canvas>
                                                        <div class="text-center mt-2">
                                                            <p>Total Offers: <?= $stat['total_offers'] ?></p>
                                                            <ul class="list-unstyled">
                                                                <?php foreach ($stat['offer_types'] as $offer): ?>
                                                                    <li>
                                                                        <?= htmlspecialchars(ucfirst($offer['type'])) ?>: <?= $offer['count'] ?> (<?= round($offer['percentage'], 2) ?>%)
                                                                    </li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-dark mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright © FunFusion 2025</div>
                        <div>
                            <a href="#" class="text-decoration-none text-light">Privacy</a>
                            ·
                            <a href="#" class="text-decoration-none text-light">Terms</a>
                            ·
                            <a href="#" class="text-decoration-none text-light">Contact</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validation pour l'ajout
            const addForm = document.getElementById('add-form');
            
            addForm.addEventListener('submit', function(e) {
                if (!validateAddForm()) {
                    e.preventDefault();
                }
            });
            
            // Validation en temps réel pour l'ajout
            document.getElementById('add-title').addEventListener('input', function() {
                validateTitle(this, 'title-error');
            });
            
            document.getElementById('add-score').addEventListener('input', function() {
                validateScore(this, 'score-error');
            });
            
            document.getElementById('add-date').addEventListener('change', function() {
                validateDate(this, 'date-error');
            });

            // Toggle statistics section
            const toggleStatsBtn = document.getElementById('toggleStatsBtn');
            const statsSection = document.getElementById('statsSection');

            if (toggleStatsBtn && statsSection) {
                toggleStatsBtn.addEventListener('click', function() {
                    console.log('Toggle Stats button clicked'); // Debugging
                    if (statsSection.style.display === 'none' || statsSection.style.display === '') {
                        statsSection.style.display = 'block';
                        this.innerHTML = '<i class="fas fa-chart-pie me-1"></i> Hide Statistics';
                        
                        // Check if Chart.js is loaded
                        if (typeof Chart === 'undefined') {
                            console.error('Chart.js not loaded');
                            statsSection.innerHTML = '<p class="text-center">Error: Unable to load charts. Please ensure Chart.js is loaded.</p>';
                            return;
                        }

                        // Define colors for each offer type
                        const offerTypeColors = {
                            'réduction': {
                                background: 'rgba(0, 212, 255, 0.7)', // Electric Blue
                                border: 'rgba(0, 212, 255, 1)'
                            },
                            'bonus': {
                                background: 'rgba(106, 17, 203, 0.5)', // Gaming Purple
                                border: 'rgba(106, 17, 203, 1)'
                            },
                            'accès': {
                                background: 'rgba(255, 0, 170, 0.7)', // Neon Pink
                                border: 'rgba(255, 0, 170, 1)'
                            },
                            'code_promo': {
                                background: 'rgba(0, 184, 148, 0.7)', // Success Green
                                border: 'rgba(0, 184, 148, 1)'
                            }
                        };

                        // Initialize charts
                        <?php foreach ($challengeStats as $stat): ?>
                            <?php if ($stat['total_offers'] > 0): ?>
                                try {
                                    const ctx_<?= $stat['id_defi'] ?> = document.getElementById('chart-<?= $stat['id_defi'] ?>');
                                    if (ctx_<?= $stat['id_defi'] ?>) {
                                        new Chart(ctx_<?= $stat['id_defi'] ?>, {
                                            type: 'pie',
                                            data: {
                                                labels: [
                                                    <?php foreach ($stat['offer_types'] as $offer): ?>
                                                        '<?= ucfirst($offer['type']) ?>',
                                                    <?php endforeach; ?>
                                                ],
                                                datasets: [{
                                                    data: [
                                                        <?php foreach ($stat['offer_types'] as $offer): ?>
                                                            <?= $offer['percentage'] ?>,
                                                        <?php endforeach; ?>
                                                    ],
                                                    backgroundColor: [
                                                        <?php foreach ($stat['offer_types'] as $offer): ?>
                                                            offerTypeColors['<?= $offer['type'] ?>']?.background || 'rgba(128, 128, 128, 0.7)',
                                                        <?php endforeach; ?>
                                                    ],
                                                    borderColor: [
                                                        <?php foreach ($stat['offer_types'] as $offer): ?>
                                                            offerTypeColors['<?= $offer['type'] ?>']?.border || 'rgba(128, 128, 128, 1)',
                                                        <?php endforeach; ?>
                                                    ],
                                                    borderWidth: 1
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                plugins: {
                                                    legend: {
                                                        position: 'bottom',
                                                        labels: {
                                                            color: 'white'
                                                        }
                                                    }
                                                }
                                            }
                                        });
                                    } else {
                                        console.error('Canvas element not found for chart-<?= $stat['id_defi'] ?>');
                                    }
                                } catch (error) {
                                    console.error('Error initializing chart for challenge <?= $stat['id_defi'] ?>:', error);
                                }
                            <?php endif; ?>
                        <?php endforeach; ?>
                    } else {
                        statsSection.style.display = 'none';
                        this.innerHTML = '<i class="fas fa-chart-pie me-1"></i> Show Statistics';
                    }
                });
            } else {
                console.error('Toggle Stats button or Stats Section not found');
            }
        });
        
        // Fonctions de validation pour l'ajout
        function validateAddForm() {
            let isValid = true;
            
            // Valider titre
            const title = document.getElementById('add-title');
            if (!validateTitle(title, 'title-error')) {
                isValid = false;
            }
            
            // Valider score
            const score = document.getElementById('add-score');
            if (!validateScore(score, 'score-error')) {
                isValid = false;
            }
            
            // Valider date
            const date = document.getElementById('add-date');
            if (!validateDate(date, 'date-error')) {
                isValid = false;
            }
            
            return isValid;
        }
        
        function validateTitle(field, errorId) {
            if (field.value.trim().length < 4) {
                showError(errorId, 'Le titre doit contenir au moins 4 caractères');
                field.classList.add('error-field');
                return false;
            } else {
                clearError(errorId);
                field.classList.remove('error-field');
                return true;
            }
        }
        
        function validateScore(field, errorId) {
            if (parseInt(field.value) < 51 || isNaN(field.value)) {
                showError(errorId, 'La valeur doit être supérieure ou égale à 51');
                field.classList.add('error-field');
                return false;
            } else {
                clearError(errorId);
                field.classList.remove('error-field');
                return true;
            }
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
        
        // Fonctions pour l'édition
        function enableEdit(id) {
            const row = document.getElementById(`row-${id}`);
            const inputs = row.querySelectorAll('.form-input');
            const editControls = row.querySelector('.edit-controls');
            
            inputs.forEach(input => {
                input.disabled = false;
                input.classList.add('editable');
            });
            
            row.querySelector('.btn-edit').style.display = 'none';
            editControls.style.display = 'inline-flex';
        }
        
        function cancelEdit(id) {
            const row = document.getElementById(`row-${id}`);
            const form = row.querySelector('form');
            const inputs = row.querySelectorAll('.form-input');
            const editControls = row.querySelector('.edit-controls');
            
            inputs.forEach(input => {
                input.disabled = true;
                input.classList.remove('editable');
                input.classList.remove('error-field');
            });
            
            // Effacer les messages d'erreur
            clearError(`edit-title-error-${id}`);
            clearError(`edit-score-error-${id}`);
            clearError(`edit-date-error-${id}`);
            
            row.querySelector('.btn-edit').style.display = 'inline-flex';
            editControls.style.display = 'none';
        }
        
        // Fonctions utilitaires
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