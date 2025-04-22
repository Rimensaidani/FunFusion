<?php
include 'C:\xamppp\htdocs\FunFusion\config.php';

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
}

$controller = new ChallengesController();
$message = "";

if (isset($_POST['ajouter'])) {
    $title = $_POST['title'];
    $type = $_POST['type'];
    $creation_date = $_POST['creation_date'];
    $score = $_POST['score'];

    if ($controller->ajouterchallenges($title, $type, $creation_date, $score)) {
        $message = "<div class='alert success'>✅ Challenge ajouté avec succès !</div>";
    } else {
        $message = "<div class='alert error'>❌ Erreur lors de l'ajout.</div>";
    }
}

if (isset($_POST['supprimer'])) {
    $id = $_POST['id_defi'];
    if ($controller->deleteChallenge($id)) {
        $message = "<div class='alert warning'>🗑️ Challenge supprimé avec succès !</div>";
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FunFusion | Challenges & Offers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            margin: 0;
            padding: 0;
        }
        
        .gaming-container {
            display: flex;
            min-height: 100vh;
        }
        
        .gaming-sidebar {
            width: 250px;
            background: linear-gradient(to bottom, var(--primary-dark), #1a1a2e);
            border-right: 1px solid rgba(0, 212, 255, 0.1);
            padding: 20px 0;
        }
        
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(0, 212, 255, 0.1);
            margin-bottom: 20px;
        }
        
        .sidebar-menu a {
            color: white;
            padding: 12px 15px;
            display: block;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
            margin-bottom: 5px;
            border-radius: 4px;
        }
        
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: rgba(0, 212, 255, 0.1);
            border-left: 4px solid var(--electric-blue);
        }
        
        .main-content {
            flex: 1;
            padding: 30px;
        }
        
        .gaming-card {
            background: rgba(15, 14, 23, 0.7);
            border: 1px solid rgba(0, 212, 255, 0.1);
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
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
        
        .btn-action {
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
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
        
        /* Styles de validation */
        .error-field {
            border: 2px solid var(--danger) !important;
        }
        .error-message {
            color: var(--danger);
            font-size: 0.8rem;
            margin-top: 5px;
        }
        .global-error-message {
            background: rgba(214, 48, 49, 0.1);
            color: var(--danger);
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid var(--danger);
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="gaming-container">
        <div class="gaming-sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-gamepad"></i> FUNFUSION</h3>
            </div>
            <div class="sidebar-menu">
                <a href="index.php" <?= $current_page == 'index.php' ? 'class="active"' : '' ?>>
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="challenges-and-offers.php" <?= $current_page == 'challenges-and-offers.php' ? 'class="active"' : '' ?>>
                    <i class="fas fa-trophy"></i> Challenges
                </a>
                <a href="#"><i class="fas fa-gift"></i> Offers</a>
                <a href="#"><i class="fas fa-users"></i> Users</a>
                <a href="#"><i class="fas fa-cog"></i> Settings</a>
            </div>
        </div>

        <div class="main-content">
            <div class="page-header">
                <h1><i class="fas fa-trophy"></i> CHALLENGES & OFFERS</h1>
            </div>
            
            <?= $message ?>
            
            <div class="gaming-card">
                <div class="card-header">
                    <i class="fas fa-database"></i> DATABASES / CHALLENGES
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
                            <tr>
                                <form method="post" class="challenge-form">
                                    <td>
                                        <input type="hidden" name="id_defi" value="<?= $challenge['id_defi'] ?>">
                                        DF<?= str_pad($challenge['id_defi'], 3, '0', STR_PAD_LEFT) ?>
                                    </td>
                                    <td>
                                        <input type="text" class="form-input" name="title" value="<?= htmlspecialchars($challenge['title']) ?>" required minlength="4">
                                    </td>
                                    <td>
                                        <select class="form-input" name="type" required>
                                            <option value="mission" <?= $challenge['type'] == 'mission' ? 'selected' : '' ?>>Mission</option>
                                            <option value="quiz" <?= $challenge['type'] == 'quiz' ? 'selected' : '' ?>>Quiz</option>
                                            <option value="mini_jeu" <?= $challenge['type'] == 'mini_jeu' ? 'selected' : '' ?>>Mini Jeu</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="date" class="form-input" name="creation_date" value="<?= htmlspecialchars($challenge['creation_date']) ?>" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-input" name="score" value="<?= htmlspecialchars($challenge['score']) ?>" min="51" required>
                                    </td>
                                    <td>
                                        <button type="submit" name="modifier" class="btn-action btn-edit">
                                            <i class="fas fa-edit"></i> EDIT
                                        </button>
                                        <button type="submit" name="supprimer" class="btn-action btn-delete">
                                            <i class="fas fa-trash-alt"></i> DELETE
                                        </button>
                                    </td>
                                </form>
                            </tr>
                            <?php endforeach; ?>
                            
                            <tr>
                                <form method="post" class="challenge-form">
                                    <td>#NEW</td>
                                    <td>
                                        <input type="text" class="form-input" name="title" required minlength="4">
                                    </td>
                                    <td>
                                        <select class="form-input" name="type" required>
                                            <option value="mission">Mission</option>
                                            <option value="quiz">Quiz</option>
                                            <option value="mini_jeu">Mini Jeu</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="date" class="form-input" name="creation_date" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-input" name="score" min="51" required>
                                    </td>
                                    <td>
                                        <button type="submit" name="ajouter" class="btn-action btn-add">
                                            <i class="fas fa-plus"></i> ADD
                                        </button>
                                    </td>
                                </form>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('.challenge-form');
        
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                let isValid = true;
                const title = form.querySelector('input[name="title"]');
                const type = form.querySelector('select[name="type"]');
                const score = form.querySelector('input[name="score"]');
                
                // Clear previous errors
                form.querySelectorAll('.error-message').forEach(el => el.remove());
                [title, type, score].forEach(field => {
                    field.classList.remove('error-field');
                    field.style.borderColor = '';
                });
                
                // Validate title
                if (title.value.trim().length < 4) {
                    showError(title, "4 caractères minimum requis");
                    isValid = false;
                }
                
                // Validate score
                if (parseInt(score.value) <= 50) {
                    showError(score, "Le score doit être supérieur à 50");
                    isValid = false;
                }
                
                if (!isValid) {
                    e.preventDefault();
                    const globalError = document.createElement('div');
                    globalError.className = 'global-error-message';
                    globalError.textContent = "Veuillez corriger les erreurs avant de soumettre";
                    form.prepend(globalError);
                    
                    // Scroll to first error
                    const firstError = form.querySelector('.error-field');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
        });
        
        function showError(input, message) {
            input.classList.add('error-field');
            input.style.borderColor = '#ff4757';
            
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.textContent = message;
            input.parentNode.appendChild(errorDiv);
        }
    });
    </script>
</body>
</html>