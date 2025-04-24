<?php
require_once '../../../Model/UserModel.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $userModel = new UserModel();


    $user = $userModel->getUserById($id);
} else {
    die("User ID not provided.");
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="FunFusion Gaming Dashboard" />
    <meta name="author" content="FunFusion" />
    <title>Dashboard | FunFusion</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
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
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }
        
        .gaming-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 25px rgba(0, 212, 255, 0.2);
            border: 1px solid rgba(0, 212, 255, 0.3);
        }
        
        .bg-gaming-primary {
            background: linear-gradient(135deg, var(--gaming-purple), var(--primary-blue)) !important;
        }
        
        .bg-gaming-secondary {
            background: linear-gradient(135deg, #ff00aa, #ff6b6b) !important;
        }
        
        .bg-gaming-accent {
            background: linear-gradient(135deg, #00d4ff, #2575fc) !important;
        }
        
        .welcome-banner {
            background: linear-gradient(to right, var(--primary-dark), var(--gaming-purple));
            color: white;
            padding: 2.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            border: 1px solid rgba(0, 212, 255, 0.2);
            box-shadow: 0 0 30px rgba(106, 17, 203, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .welcome-banner::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 300px;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" opacity="0.05"><path fill="%2300d4ff" d="M30,-40C38.5,-31.8,44.8,-21.7,49.8,-9.9C54.8,1.9,58.5,15.4,53.2,25.5C47.9,35.6,33.6,42.3,19.3,48.9C5,55.5,-9.3,62.1,-22.3,59.4C-35.3,56.7,-47,44.8,-54.3,31.5C-61.6,18.2,-64.5,3.6,-61.5,-9.7C-58.5,-23,-49.6,-35,-38.1,-43.6C-26.6,-52.2,-12.5,-57.4,0.7,-58.2C13.9,-59,27.8,-55.4,30,-40Z"/></svg>') no-repeat;
            background-size: contain;
            background-position: center right;
        }
        
        .btn-gaming {
            background: linear-gradient(to right, var(--electric-blue), var(--primary-blue));
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            color: white;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            box-shadow: 0 0 15px rgba(0, 212, 255, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-gaming:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(0, 212, 255, 0.6);
            color: white;
        }
        
        .btn-gaming::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }
        
        .btn-gaming:hover::before {
            left: 100%;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.8rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            background: linear-gradient(to right, var(--electric-blue), white);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .sb-sidenav-menu-heading {
            color: var(--electric-blue) !important;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            opacity: 0.7;
        }
        
        .sb-sidenav .nav-link {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 600;
            border-radius: 5px;
            margin: 5px 0;
            transition: all 0.3s ease;
        }
        
        .sb-sidenav .nav-link:hover {
            color: white;
            background: rgba(0, 212, 255, 0.1);
            transform: translateX(5px);
        }
        
        .sb-sidenav .nav-link .sb-nav-link-icon {
            color: var(--electric-blue);
        }
        
        .sb-sidenav-footer {
            background: rgba(0, 0, 0, 0.3) !important;
            color: var(--electric-blue) !important;
            border-top: 1px solid rgba(0, 212, 255, 0.1);
        }
        
        .neon-text {
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.7);
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
        }
        
        .gaming-table tbody tr {
            transition: all 0.3s ease;
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
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(0, 212, 255, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(0, 212, 255, 0); }
            100% { box-shadow: 0 0 0 0 rgba(0, 212, 255, 0); }
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
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
        <!-- Navbar Search
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control bg-dark text-white border-dark" type="text" placeholder="Search games..." aria-label="Search" />
                <button class="btn btn-gaming" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>-->
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle pulse-animation" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-astronaut fa-lg"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end bg-dark" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item text-white" href="#!"><i class="fas fa-cog me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider bg-secondary" /></li>
                    <li><a class="dropdown-item text-danger" href="#!"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
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
                        <a class="nav-link active" href="index.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">GAMING</div>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Users
                        </a>
                        <a class="nav-link" href="games.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-gamepad"></i></div>
                            Virtual activities
                        </a>
                        <a class="nav-link" href="friends.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Real activities
                        </a>
                        <a class="nav-link" href="achievements.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-trophy"></i></div>
                            rimene
                        </a>
                        <a class="nav-link" href="achievements.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-trophy"></i></div>
                            nour
                        </a>
                        <a class="nav-link" href="achievements.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-trophy"></i></div>
                            amine
                        </a>
                        <!--<div class="sb-sidenav-menu-heading">COMMUNITY</div>
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
                        </a>-->
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
                    <div class="welcome-banner">
                        <h1 class="mt-4 mb-3 neon-text">WELCOME TO</h1>
                        <h2 class="display-4 mb-4" style="font-weight: 800; letter-spacing: 2px;">FUNFUSION GAMING</h2>
                        <p class="lead mb-0">The perfect platform to connect with gamers who share your passion for virtual experiences and competitive play.</p>
                    </div>
                    <!--
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card gaming-card bg-gaming-primary text-white mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title">ACTIVE GAMES</h5>
                                            <h2 class="mb-0">24</h2>
                                        </div>
                                        <i class="fas fa-gamepad fa-3x opacity-50"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between border-top border-secondary">
                                    <a class="small text-white stretched-link" href="#">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card gaming-card bg-gaming-secondary text-white mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title">FRIENDS ONLINE</h5>
                                            <h2 class="mb-0">15</h2>
                                        </div>
                                        <i class="fas fa-users fa-3x opacity-50"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between border-top border-secondary">
                                    <a class="small text-white stretched-link" href="#">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card gaming-card bg-gaming-accent text-white mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title">NEW MESSAGES</h5>
                                            <h2 class="mb-0">8</h2>
                                        </div>
                                        <i class="fas fa-envelope fa-3x opacity-50"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between border-top border-secondary">
                                    <a class="small text-white stretched-link" href="#">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card gaming-card bg-dark text-white mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title">ACHIEVEMENTS</h5>
                                            <h2 class="mb-0">12</h2>
                                        </div>
                                        <i class="fas fa-trophy fa-3x opacity-50"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between border-top border-secondary">
                                    <a class="small text-white stretched-link" href="#">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>-->
                    
                    <!--<div class="row">
                        <div class="col-xl-6">
                            <div class="card gaming-card mb-4">
                                <div class="card-header border-bottom border-secondary">
                                    <i class="fas fa-chart-line me-1 text-primary"></i>
                                    GAMING ACTIVITY (LAST 30 DAYS)
                                </div>
                                <div class="card-body">
                                    <canvas id="myAreaChart" width="100%" height="40"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card gaming-card mb-4">
                                <div class="card-header border-bottom border-secondary">
                                    <i class="fas fa-chart-pie me-1 text-primary"></i>
                                    GAME TIME DISTRIBUTION
                                </div>
                                <div class="card-body">
                                    <canvas id="myBarChart" width="100%" height="40"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>-->
                    
                    <div class="card gaming-card mb-4">
                        <div class="card-header border-bottom border-secondary">
                            <i class="fas fa-table me-1 text-primary"></i>
                            Update User
                        </div>
                        <div class="card-body">
                            
                        
                    <h2>Edit User</h2>

                    <form id="form" method="POST" action="../../../Controller/editUserDash.php">
                        <input type="hidden" name="action" value="update"><br>
                        <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">

                        <label class="sb-sidenav-menu-heading">Username:</label><br>
                        <input class="col-xl-3 col-md-6" type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>"><br><br>
                        <span id="username_error"></span><br>

                        <label class="sb-sidenav-menu-heading">Email:</label><br>
                        <input class="col-xl-3 col-md-6" type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" ><br><br>
                        <span id="email_error"></span><br>

                        <label class="sb-sidenav-menu-heading">Phone:</label><br>
                        <input class="col-xl-3 col-md-6" type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" ><br><br>
                        <span id="phone_error"></span><br>

                        <label class="sb-sidenav-menu-heading">Birth Date:</label><br>
                        <input class="col-xl-3 col-md-6" type="date" id="birth_date" name="birth_date" value="<?= htmlspecialchars($user['birth_date']) ?>"><br><br>
                        <span id="birth_date_error"></span><br>

                        <label class="sb-sidenav-menu-heading">Role:</label><br>
                            <select class="col-xl-3 col-md-6" name="role" >
                            <option class="col-xl-3 col-md-6" value="client" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Client</option>
                            <option class="col-xl-3 col-md-6" value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select><br><br>
                        <span id="role_error"></span><br>

                        <input type="hidden" name="password" value="<?= htmlspecialchars($user['password']) ?>">

                        <button name="update" class="btn btn-gaming btn-sm" type="submit">Update User</button>
                        <button onclick="window.location.href='index.php'" class="btn btn-gaming btn-sm" type="submit">Cancel</button>
                    </form>



                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-dark mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; FunFusion Gaming 2025</div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script>
        // Area Chart
        var ctx = document.getElementById("myAreaChart");
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["1 Dec", "5 Dec", "10 Dec", "15 Dec", "20 Dec", "25 Dec", "30 Dec"],
                datasets: [{
                    label: "Hours Played",
                    lineTension: 0.3,
                    backgroundColor: "rgba(0, 212, 255, 0.05)",
                    borderColor: "rgba(0, 212, 255, 1)",
                    pointRadius: 4,
                    pointBackgroundColor: "rgba(0, 212, 255, 1)",
                    pointBorderColor: "rgba(255, 255, 255, 0.8)",
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: "rgba(0, 212, 255, 1)",
                    pointHoverBorderColor: "rgba(255, 255, 255, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: [3.5, 5.2, 4.8, 6.1, 5.6, 7.2, 6.5],
                }],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: "rgba(255, 255, 255, 0.05)",
                        },
                        ticks: {
                            color: "rgba(255, 255, 255, 0.7)",
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            color: "rgba(255, 255, 255, 0.7)",
                        }
                    }
                },
                legend: {
                    labels: {
                        fontColor: "white",
                    }
                }
            }
        });

        // Bar Chart
        var ctx2 = document.getElementById("myBarChart");
        var myBarChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ["FPS", "RPG", "MOBA", "Strategy", "Sports"],
                datasets: [{
                    data: [35, 25, 20, 15, 5],
                    backgroundColor: [
                        'rgba(106, 17, 203, 0.8)',
                        'rgba(0, 212, 255, 0.8)',
                        'rgba(255, 0, 170, 0.8)',
                        'rgba(37, 117, 252, 0.8)',
                        'rgba(100, 221, 187, 0.8)'
                    ],
                    borderColor: [
                        'rgba(106, 17, 203, 1)',
                        'rgba(0, 212, 255, 1)',
                        'rgba(255, 0, 170, 1)',
                        'rgba(37, 117, 252, 1)',
                        'rgba(100, 221, 187, 1)'
                    ],
                    borderWidth: 1,
                }],
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: 'white',
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="js/editUser.js"></script>     
</body>
</html>