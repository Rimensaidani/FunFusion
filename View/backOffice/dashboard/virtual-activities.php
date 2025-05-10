<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Erreur: utilisateur non connecté.");
}

$id_utilisateur = $_SESSION['user_id'];

require_once('../../../Model/ActiviteVirtuelle.php');
require_once('../../../Controller/ActiviteVirtuelleController.php');
require_once('../../../Model/Participation.php');
require_once('../../../Controller/ParticipationController.php');

require '../../../phpmailer/PHPMailer.php';
require '../../../phpmailer/SMTP.php';
require '../../../phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;




    $dateToday = (new DateTime())->format('Y-m-d');

try {
    $db = config::getConnexion();

    $sql = "SELECT p.*, u.username, u.email, a.titre, a.date, a.lien
            FROM participation p
            INNER JOIN user u ON p.id_utilisateur = u.id
            INNER JOIN activites_virtuelles a ON p.id_activite = a.id_activite
            WHERE DATE(a.date) = CURDATE()";

    $stmt = $db->prepare($sql); 
    $stmt->execute();           
    $participations = $stmt->fetchAll();

    foreach ($participations as $part) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'styleiq2024@gmail.com'; // ton email
            $mail->Password = 'nbtg nusa fzml jzqk';   // ton mot de passe app gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('styleiq2024@gmail.com', 'FunFusion');
            $mail->addAddress($part['email'], $part['username']);

            $mail->isHTML(true);
            $mail->Subject = 'Rappel : votre activite FunFusion commence aujourd\'hui';

    $mail->Body = "
    <h2>Bonjour {$part['username']},</h2>
    <p>Petit rappel : votre activite <strong>\"{$part['titre']}\"</strong> a lieu aujourd'hui !</p>
    <p>Voici votre lien pour rejoindre l'activité :<br>
    <a href=\"{$part['lien']}\">Ouvrir l'activité</a></p>
    <p>Merci de faire partie de FunFusion 🚀</p>
";


            $mail->send();
        } catch (Exception $e) {
            error_log('Erreur envoi email: ' . $mail->ErrorInfo);
        }
    }
} catch (Exception $e) {
    error_log('Erreur base de données: ' . $e->getMessage());
}
    
                
    
    

$controller = new ActiviteVirtuelleController();
$activites = $controller->listActiviteVirtuelle(); // charge toujours la liste d'activités

$stats = $controller->getParticipationStats();

// Extraction des données
$labels = [];
$values = [];
foreach ($stats as $s) {
    $labels[] = $s['titre'];
    $values[] = $s['nombre_participants'];
}

// Puis si tu veux faire une recherche, surcharge la variable
if (isset($_GET['search']) && strlen(trim($_GET['search'])) > 0) {
    $mot = trim($_GET['search']);
    $activites = $controller->searchActivitesByTitre($mot);
}





$participationController = new ParticipationController();
$participations = $participationController->listParticipationsAvecActivite();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_participation') {
    // Récupération et validation des données
    $username = trim($_POST['username']);
    $age = intval($_POST['age']);
    $id_activite = intval($_POST['id_activite']);
    $id_utilisateur =  $_SESSION['user_id'];

    // Vérifier que l'activité existe
    $activiteExists = false;
    foreach ($activites as $a) {
        if ($a['id_activite'] == $id_activite) {
            $activiteExists = true;
            break;
        }
    }

    if (!$activiteExists) {
        die("L'activité sélectionnée n'existe pas");
    }

    $participation = new Participation($username, $age,  $id_activite, $id_utilisateur);
    $participationController->addParticipation($participation);

    header("Location: virtual-activities.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_participation') {
    $id_participation = intval($_POST["id_participation"]);
        $username = htmlspecialchars(trim($_POST["username"]));
        $age = intval($_POST["age"]);
        $id_activite = intval($_POST["id_activite"]);
        $id_utilisateur =  $_SESSION['user_id'];

        $participation = new Participation($username, $age, $id_activite, $id_utilisateur, $id_participation);
        $participationController->updateParticipation($participation);
        header("Location: virtual-activities.php");
        exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_participation') {
    $id = intval($_POST['id_participation']);
    
    if ($participationController->deleteParticipation($id)) {
        $_SESSION['success'] = "Participation supprimée avec succès";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression";
    }
    
    header("Location: virtual-activities.php");
    exit();
}

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    // Récupération et nettoyage des données
    $titre = htmlspecialchars(trim($_POST['titre']));
    $type = htmlspecialchars(trim($_POST['type']));
    $date_heure = new DateTime($_POST['date_heure']);
    $plateforme = htmlspecialchars(trim($_POST['plateforme']));
    $lien = htmlspecialchars(trim($_POST['lien']));
    $validee = isset($_POST['validee']) ? 1 : 0;

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

    $createur =  $_SESSION['user_id'];

    
    $activite = new ActiviteVirtuelle($titre, $type, $date_heure, $plateforme, $lien, $createur, $validee, null ,$imageName);
    

    
    $controller->addActiviteVirtuelle($activite);

    
    header("Location: virtual-activities.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = intval($_POST['id_activite']);
    $titre = htmlspecialchars(trim($_POST['titre']));
    $type = htmlspecialchars(trim($_POST['type']));
    $date_heure = new DateTime($_POST['date_heure']);
    $plateforme = htmlspecialchars(trim($_POST['plateforme']));
    $lien = htmlspecialchars(trim($_POST['lien']));
    $validee = isset($_POST['validee']) ? 1 : 0;

    
    $bdd = config::getConnexion();

    // Si une nouvelle image est uploadée
    $imageName = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
    } else {
        // Sinon on récupère l'ancienne image depuis la base
        $stmt = $bdd->prepare("SELECT image FROM activites_virtuelles WHERE id_activite = ?");
        $stmt->execute([$id]);
        $imageName = $stmt->fetchColumn();
    }

    
    $stmt = $bdd->prepare("SELECT id_createur FROM activites_virtuelles WHERE id_activite = ?");
    $stmt->execute([$id]);
    $createur = $stmt->fetchColumn();

    
    $activite = new ActiviteVirtuelle($titre, $type, $date_heure, $plateforme, $lien, $createur, $validee, $id, $imageName);
    $controller->updateActiviteVirtuelle($activite, $id);

    header("Location: virtual-activities.php");
    exit();
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = intval($_POST['id_activite']);
    $controller->deleteActiviteVirtuelle($id);
    header("Location: virtual-activities.php");
    exit();
}



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="FunFusion Gaming Dashboard - Activités Virtuelles" />
    <meta name="author" content="FunFusion" />
    <title>FunFusion | Activités Virtuelles</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
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
        }
        
        body {
            background: linear-gradient(135deg, var(--primary-dark), #1a1a2e);
            color: white;
            font-family: 'Rajdhani', 'Segoe UI', sans-serif;
            min-height: 100vh;
        }
        
        .gaming-card {
            background: rgba(15, 14, 23, 0.7);
            border: 1px solid rgba(0, 212, 255, 0.1);
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }
        
        .gaming-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 25px rgba(0, 212, 255, 0.2);
            border: 1px solid rgba(0, 212, 255, 0.3);
        }
        
        .gaming-table {
            background: rgba(15, 14, 23, 0.7);
            color: white;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .gaming-table thead th {
            background: rgba(106, 17, 203, 0.5);
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid rgba(0, 212, 255, 0.2);
        }
        
        .gaming-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(0, 212, 255, 0.1);
        }
        
        .gaming-table tbody tr:last-child {
            border-bottom: none;
        }
        
        .gaming-table tbody tr:hover {
            background: rgba(0, 212, 255, 0.1);
        }
        
        .badge-gaming {
            border-radius: 4px;
            font-weight: 700;
            padding: 5px 10px;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 1px;
        }
        
        .btn-gaming {
            background: linear-gradient(to right, var(--electric-blue), var(--primary-blue));
            border: none;
            border-radius: 50px;
            padding: 8px 20px;
            color: white;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            box-shadow: 0 0 15px rgba(0, 212, 255, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            font-size: 0.8rem;
        }
        
        .btn-gaming:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(0, 212, 255, 0.6);
            color: white;
        }
        
        .btn-gaming-secondary {
            background: linear-gradient(to right, var(--neon-pink), #ff6b6b);
        }
        
        .btn-gaming-success {
            background: linear-gradient(to right, #00b09b, #96c93d);
        }
        
        .card-header-gaming {
            background: linear-gradient(to right, rgba(106, 17, 203, 0.7), rgba(37, 117, 252, 0.7)) !important;
            border-bottom: 1px solid rgba(0, 212, 255, 0.2);
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .neon-text {
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.7);
        }
        
        .form-control-gaming {
            background-color: rgba(15, 14, 23, 0.7);
            border: 1px solid rgba(0, 212, 255, 0.2);
            color: white;
            border-radius: 5px;
        }
        
        .form-control-gaming:focus {
            background-color: rgba(15, 14, 23, 0.9);
            border-color: var(--electric-blue);
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(0, 212, 255, 0.25);
        }
        
        .modal-gaming {
            background-color: rgba(15, 14, 23, 0.9);
            border: 1px solid rgba(0, 212, 255, 0.2);
            border-radius: 8px;
        }
        
        .modal-header-gaming {
            border-bottom: 1px solid rgba(0, 212, 255, 0.2);
        }
    </style>
</head>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector('#addActivityModal form');

    form.addEventListener('submit', function (e) {
        const titre = document.getElementById('titre').value.trim();
        const type = document.getElementById('type').value;
        const dateHeure = document.getElementById('date_heure').value;
        const plateforme = document.getElementById('plateforme').value.trim();
        const lien = document.getElementById('lien').value.trim();

        let errors = [];

        
        if (titre === '') {
            errors.push("Le titre est requis.");
        }

        
        if (type === '') {
            errors.push("Veuillez sélectionner un type.");
        }

        
        if (new Date(dateHeure) < new Date()) {
            errors.push("La date doit être dans le futur.");
        }

        
        if (plateforme === '') {
            errors.push("La plateforme est requise.");
        }

        
        const urlRegex = /^(https?:\/\/)[^\s$.?#].[^\s]*$/gm;
        if (lien === '') {
            errors.push("Le lien est requis.");
        } else if (!urlRegex.test(lien)) {
            errors.push("Le lien doit être une URL valide (ex : https://example.com).");
        }

        if (errors.length > 0) {
            e.preventDefault();
            alert("Erreur de saisie :\n\n" + errors.join("\n"));
        }
    });
}); 
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const modifierForm = document.getElementById('modifierForm');

    if (modifierForm) {
        modifierForm.addEventListener('submit', function (e) {
            const titre = document.getElementById('edit_titre').value.trim();
            const type = document.getElementById('edit_type').value;
            const dateHeure = document.getElementById('edit_date').value;
            const plateforme = document.getElementById('edit_plateforme').value.trim();
            const lien = document.getElementById('edit_lien').value.trim();

            let erreurs = [];

            
            if (titre === '') {
                erreurs.push("Le titre est requis.");
            }

            
            if (type === '') {
                erreurs.push("Le type est requis.");
            }

            
            if (!dateHeure || new Date(dateHeure) < new Date()) {
                erreurs.push("La date doit être dans le futur.");
            }

        
            if (plateforme === '') {
                erreurs.push("La plateforme est requise.");
            }

            
            const urlRegex = /^(https?:\/\/)[^\s$.?#].[^\s]*$/;
            if (lien === '') {
                erreurs.push("Le lien est requis.");
            } else if (!urlRegex.test(lien)) {
                erreurs.push("Le lien doit être une URL valide.");
            }

            // Annuler soumission si erreurs
            if (erreurs.length > 0) {
                e.preventDefault();
                alert("Erreur de saisie :\n\n" + erreurs.join("\n"));
            }
        });
    }
});
</script>


<script>
  document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.edit-btn').forEach(button => {
      button.addEventListener('click', () => {
        console.log(" Bouton modifier cliqué");

        
        const id = button.dataset.id;
        const titre = button.dataset.titre;
        const type = button.dataset.type;
        const date = button.dataset.date;
        const plateforme = button.dataset.plateforme;
        const lien = button.dataset.lien;
        const validee = button.dataset.validee;

        
        console.log({ id, titre, type, date, plateforme, lien, validee });

        
        document.getElementById('edit_id').value = button.dataset.id;
        document.getElementById('edit_titre').value = titre;
        document.getElementById('edit_type').value = type;
        
        document.getElementById('edit_date').value = date;
        document.getElementById('edit_plateforme').value = plateforme;
        document.getElementById('edit_lien').value = lien;
        document.getElementById('edit_validee').checked = validee === "1";
      });
    });
  });
</script>




<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark gaming-navbar">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.html">
            <i class="fas fa-gamepad me-2"></i>FUNFUSION
        </a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        <!-- Navbar Search-->
        <form method="GET" class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"> 
    <div class="input-group">
        <input name="search" class="form-control bg-dark text-white border-dark" type="text" placeholder="Search activities..." aria-label="Search" />
        <button class="btn btn-gaming" type="submit">
            <i class="fas fa-search"></i>
        </button>
    </div>
</form>

<a href="export_pdf.php" class="btn btn-gaming btn-sm" target="_blank">
  <i class="fas fa-file-pdf"></i> Exporter en PDF
</a>
<a href="calendrier.php" class="btn btn-info">Voir le calendrier</a>


        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle pulse-animation" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-astronaut fa-lg"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end bg-dark" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item text-white" href="#!"><i class="fas fa-user me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item text-white" href="#!"><i class="fas fa-cog me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider bg-secondary" /></li>
                    <li><a href="../../../Controller/logout.php"  class="dropdown-item text-danger" href="#!"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
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
                        
                        <a class="nav-link active" href="virtual-activities.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-laptop-code"></i></div>
                            Activités Virtuelles
                        </a>
                        
                        <a class="nav-link" href="achievements.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-trophy"></i></div>
                            Achievements
                        </a>
                        <div class="sb-sidenav-menu-heading">COMMUNITY</div>
                        <a class="nav-link" href="streams.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-video"></i></div>
                            Streams
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
                    Admin
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4 neon-text">Gestion des Activités Virtuelles</h1>
                    <ol class="breadcrumb mb-4" style="background: rgba(15, 14, 23, 0.7); border-radius: 8px; padding: 10px 15px;">
                        <li class="breadcrumb-item"><a href="index.html" class="text-electric-blue">Dashboard</a></li>
                        <li class="breadcrumb-item active text-white">Activités Virtuelles</li>
                    </ol>
                    
                    <div class="card gaming-card mb-4">
                        <div class="card-header card-header-gaming d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-laptop-code me-2"></i>
                                Liste des Activités
                            </div>
                            <button class="btn btn-gaming btn-sm" data-bs-toggle="modal" data-bs-target="#addActivityModal">
                                <i class="fas fa-plus me-1"></i> Ajouter une activité
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="virtualActivities" class="table table-borderless gaming-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>TITRE</th>
                                            <th>TYPE</th>
                                            <th>DATE/HEURE</th>
                                            <th>PLATEFORME</th>
                                            <th>CRÉATEUR</th>
                                            <th>STATUT</th>
                                            <th>IMAGE</th>
                                            <th>ACTIONS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php foreach ($activites as $a): ?>
    <td><?php echo htmlspecialchars($a['id_activite']); ?></td>
        <td class="fw-bold"><?php echo htmlspecialchars($a['titre']); ?></td>
        <td>
            <span class="badge badge-gaming 
                <?php
                    switch (strtolower($a['type'])) {
                        case 'compétition': echo 'bg-primary'; break;
                        case 'social': echo 'bg-warning text-dark'; break;
                        case 'défi': echo 'bg-info text-dark'; break;
                        case 'stream': echo 'bg-secondary'; break;
                        default: echo 'bg-light text-dark'; break;
                    }
                ?>">
                <?php echo htmlspecialchars($a['type']); ?>
            </span>
        </td>
        <td><?php echo date('d/m/Y H:i', strtotime($a['date'])); ?></td>
        <td><?php echo htmlspecialchars($a['plateforme']); ?></td>
        <td><?php echo htmlspecialchars($a['id_createur']); ?></td>
        <td>
            <?php if ($a['valide']): ?>
                <span class="badge badge-gaming bg-success">VALIDÉE</span>
            <?php else: ?>
                <span class="badge badge-gaming bg-warning text-dark">EN ATTENTE</span>
            <?php endif; ?>
        </td>

        <td>
        <img src="../../uploads/<?= htmlspecialchars($a['image']) ?>" alt="Image activité" width="100">

        </td>

        <td>
            <a href="<?php echo htmlspecialchars($a['lien']); ?>" class="btn btn-gaming btn-sm me-1" title="Voir le lien" target="_blank">
                <i class="fas fa-link"></i>
            </a>
<button class="btn btn-gaming btn-sm me-1 edit-btn"
    data-id="<?= $a['id_activite'] ?>"
    data-titre="<?= htmlspecialchars($a['titre']) ?>"
    data-type="<?= htmlspecialchars($a['type']) ?>"
    data-date="<?= date('Y-m-d\TH:i', strtotime($a['date'])) ?>"
    data-plateforme="<?= htmlspecialchars($a['plateforme']) ?>"
    data-lien="<?= htmlspecialchars($a['lien']) ?>"
    data-validee="<?= $a['valide'] ?>"
    data-bs-toggle="modal"
    data-bs-target="#editActivityModal"
    title="Modifier">
    <i class="fas fa-edit"></i>
</button>

<form method="POST" action="" onsubmit="return confirm('Supprimer cette activité ?')" style="display:inline;">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id_activite" value="<?= $a['id_activite'] ?>">
    <button type="submit" class="btn btn-gaming btn-sm btn-gaming-secondary" title="Supprimer">
        <i class="fas fa-trash"></i>
    </button>
</form>

        </td>
    </tr>
<?php endforeach; ?>
</tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>

<!-- Remplacez votre table actuelle par ce code -->
<div class="card gaming-card mb-4">
    <div class="card-header card-header-gaming d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-users me-2"></i>
            Liste des Participations
        </div>
        <button class="btn btn-gaming btn-sm" data-bs-toggle="modal" data-bs-target="#addParticipationModal">
            <i class="fas fa-plus me-1"></i> Ajouter une participation
        </button>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-borderless gaming-table">
                <thead>
                    <tr>
                        <th> ID</th>
                        <th>Nom d'utilisateur</th>
                        <th>Âge</th>
                        <th>Activité liée</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($participations as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['id_participation']) ?></td>
                        <td><?= htmlspecialchars($p['username']) ?></td>
                        <td><?= intval($p['age']) ?></td>
                        <td><?= htmlspecialchars($p['titre']) ?></td>
                        <td>
                            <!-- Modifier Participation -->
                            <button class="btn btn-gaming btn-sm me-1 edit-participation-btn"
                                data-id="<?= $p['id_participation'] ?>"
                                data-username="<?= htmlspecialchars($p['username']) ?>"
                                data-age="<?= $p['age'] ?>"
                                data-id_activite="<?= $p['id_activite'] ?>"
                                title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!-- Supprimer Participation -->
                            <form method="POST" action="" class="d-inline" onsubmit="return confirm('Supprimer cette participation ?');">
                                <input type="hidden" name="action" value="delete_participation">
                                <input type="hidden" name="id_participation" value="<?= $p['id_participation'] ?>">
                                <button type="submit" class="btn btn-gaming btn-sm btn-gaming-secondary" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<canvas id="statsChart"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('statsChart').getContext('2d');

const statsChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Nombre de participations',
            data: <?= json_encode($values) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2
        }]
    },
    options: {
        animation: {
            duration: 1000,
            easing: 'easeInOutBounce'
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>



            </main>
            <footer class="py-4 bg-dark mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; FunFusion Gaming 2023</div>
                        <div>
                            <a href="#" class="text-decoration-none text-light">Privacy</a>
                            &middot;
                            <a href="#" class="text-decoration-none text-light">Terms</a>
                            &middot;
                            <a href="#" class="text-decoration-none text-light">Contact</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
<!-- Modal Ajouter Participation -->
<div class="modal fade" id="addParticipationModal" tabindex="-1" aria-labelledby="addParticipationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-gaming">
            <div class="modal-header modal-header-gaming">
                <h5 class="modal-title neon-text" id="addParticipationModalLabel">Ajouter une Participation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="action" value="add_participation">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_username" class="form-label">Nom d'utilisateur</label>
                        <input type="text" class="form-control form-control-gaming" id="add_username" name="username" >
                    </div>
                    <div class="mb-3">
                        <label for="add_age" class="form-label">Âge</label>
                        <input type="number" class="form-control form-control-gaming" id="add_age" name="age" >
                    </div>
                    <div class="mb-3">
                        <label for="add_id_activite" class="form-label">Activité</label>
                        <select class="form-select form-control-gaming" id="add_id_activite" name="id_activite" >
                            <option value="">Sélectionner une activité...</option>
                            <?php foreach ($activites as $a): ?>
                                <option value="<?= $a['id_activite'] ?>">
                                    <?= htmlspecialchars($a['titre']) ?> 
                                
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-gaming">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
    <!-- Modal Modifier Participation -->
<div class="modal fade" id="editParticipationModal" tabindex="-1" aria-labelledby="editParticipationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-gaming">
            <div class="modal-header modal-header-gaming">
                <h5 class="modal-title neon-text" id="editParticipationModalLabel">Modifier Participation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="action" value="update_participation">
                <input type="hidden" id="edit_participation_id" name="id_participation">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_username" class="form-label">Nom d'utilisateur</label>
                        <input type="text" class="form-control form-control-gaming" id="edit_username" name="username" >
                    </div>
                    <div class="mb-3">
                        <label for="edit_age" class="form-label">Âge</label>
                        <input type="number" class="form-control form-control-gaming" id="edit_age" name="age" >
                    </div>
                    <div class="mb-3">
                        <label for="edit_id_activite" class="form-label">Activité</label>
                        <select class="form-select form-control-gaming" id="edit_id_activite" name="id_activite" >
                            <?php foreach ($activites as $a): ?>
                                <option value="<?= $a['id_activite'] ?>"><?= htmlspecialchars($a['titre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-gaming">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- Modal Ajout Activité -->
    <div class="modal fade" id="addActivityModal" tabindex="-1" aria-labelledby="addActivityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modal-gaming">
                <div class="modal-header modal-header-gaming">
                    <h5 class="modal-title neon-text" id="addActivityModalLabel">Ajouter une Activité Virtuelle</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="titre" class="form-label">Titre</label>
                                <input type="text" class="form-control form-control-gaming" id="titre" name="titre" >
                            </div>
                            <div class="col-md-6">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-select form-control-gaming" id="type" name="type">
                                    <option value="">Sélectionner...</option>
                                    <option value="COMPÉTITION">Compétition</option>
                                    <option value="SOCIAL">Social</option>
                                    <option value="DÉFI">Défi</option>
                                    <option value="STREAM">Stream</option>
                                    <option value="AUTRE">Autre</option>
                                </select>
                            </div>
                        </div> 
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date_heure" class="form-label">Date et Heure</label>
                                <input type="datetime-local" class="form-control form-control-gaming" id="date_heure" name="date_heure" >
                            </div>
                            <div class="col-md-6">
                                <label for="plateforme" class="form-label">Plateforme</label>
                                <input type="text" class="form-control form-control-gaming" id="plateforme" name="plateforme" >
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="lien" class="form-label">Lien (URL)</label>
                            <input  class="form-control form-control-gaming" id="lien" name="lien" >
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="validee" name="validee">
                            <label class="form-check-label" for="validee">Activité validée</label>
                        </div>

                        <div class="form-group">
                        <label for="image">Image de l'activité</label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/*">
                        </div>


                        <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-gaming">Enregistrer</button>
                </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>

<!-- Modal Modifier Activité -->
<div class="modal fade" id="editActivityModal" tabindex="-1" aria-labelledby="editActivityModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content modal-gaming">
      <div class="modal-header modal-header-gaming">
        <h5 class="modal-title neon-text" id="editActivityModalLabel">Modifier une Activité</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="virtual-activities.php" id="modifierForm" enctype="multipart/form-data">
        <input type="hidden" name="action" value="update">
        <input type="hidden" id="edit_id" name="id_activite">
       
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="edit_titre" class="form-label">Titre</label>
              <input type="text" class="form-control form-control-gaming" id="edit_titre" name="titre" >
            </div>
            <div class="col-md-6">
              <label for="edit_type" class="form-label">Type</label>
              <select class="form-select form-control-gaming" id="edit_type" name="type" >
                <option value="COMPÉTITION">Compétition</option>
                <option value="SOCIAL">Social</option>
                <option value="DÉFI">Défi</option>
                <option value="STREAM">Stream</option>
                <option value="AUTRE">Autre</option>
              </select>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="edit_date" class="form-label">Date et Heure</label>
              <input type="datetime-local" class="form-control form-control-gaming" id="edit_date" name="date_heure" >
            </div>
            <div class="col-md-6">
              <label for="edit_plateforme" class="form-label">Plateforme</label>
              <input type="text" class="form-control form-control-gaming" id="edit_plateforme" name="plateforme" >
            </div>
          </div>
          <div class="mb-3">
            <label for="edit_lien" class="form-label">Lien (URL)</label>
            <input type="url" class="form-control form-control-gaming" id="edit_lien" name="lien" >
          </div>
          <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="edit_validee" name="validee">
            <label class="form-check-label" for="edit_validee">Activité validée</label>
          </div>
          <div class="form-group mb-3">
            <label for="edit_image">Image de l'activité</label>
            <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
        </div>

        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-gaming">Enregistrer les modifications</button>
        </div>
      </form>
    </div>
  </div>
</div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script>
        const dataTable = new simpleDatatables.DataTable("#virtualActivities", {
            classes: {
                table: "gaming-table",
            },
            columns: [
                { select: 0, sort: "asc" }, // Tri par titre par défaut
                { select: 2, type: "date" } // Tri correct pour les dates
            ],
            labels: {
                placeholder: "Rechercher...",
                perPage: "{select} activités par page",
                noRows: "Aucune activité trouvée",
                info: "Affichage de {start} à {end} sur {rows} activités"
            }
        });

        // Fonction pour afficher le lien dans un modal
        document.querySelectorAll('[title="Voir le lien"]').forEach(btn => {
            btn.addEventListener('click', function() {
                // Ici vous pourriez récupérer le lien depuis les données
                alert("Voir le lien de l'activité");
            });
        });
    </script>
   
<script>
    document.addEventListener("DOMContentLoaded", function() {
    // Gestion de l'édition des participations
    document.querySelectorAll('.edit-participation-btn').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            const username = button.dataset.username;
            const age = button.dataset.age;
            const id_activite = button.dataset.id_activite;

            document.getElementById('edit_participation_id').value = id;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_age').value = age;
            
            // Sélectionner l'activité correspondante
            document.getElementById('edit_id_activite').value = id_activite;

            // Afficher le modal
            const modal = new bootstrap.Modal(document.getElementById('editParticipationModal'));
            modal.show();
        });
    });

    // Validation du formulaire d'ajout
    document.querySelector('#addParticipationModal form').addEventListener('submit', function(e) {
        const username = document.getElementById('add_username').value.trim();
        const age = document.getElementById('add_age').value;
        const idActivite = document.getElementById('add_id_activite').value;
        
        let errors = [];
        
        if (username === '') errors.push("Le nom d'utilisateur est requis.");
        if (!age || age < 12) errors.push("L'âge doit être plus que 12 ans.");
        if (!idActivite) errors.push("Vous devez sélectionner une activité.");
        
        if (errors.length > 0) {
            e.preventDefault();
            alert("Erreurs :\n" + errors.join("\n"));
        }
    });
});


document.addEventListener("DOMContentLoaded", function() {
    // Gestion de l'édition des participations
    document.querySelectorAll('.edit-participation-btn').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            const username = button.dataset.username;
            const age = button.dataset.age;
            const id_activite = button.dataset.id_activite;

            document.getElementById('edit_participation_id').value = id;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_age').value = age;
            
            
            document.getElementById('edit_id_activite').value = id_activite;

            // Afficher le modal
            const modal = new bootstrap.Modal(document.getElementById('editParticipationModal'));
            modal.show();
        });
    });
});
</script>
</body>
</html>