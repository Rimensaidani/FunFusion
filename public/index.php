<?php
// public/index.php
include_once '../models/Resource.php';
include_once '../config/db.php';
include_once '../controllers/ResourceController.php';
include_once '../controllers/ReservationController.php';

$database = new Database();
$db = $database->getConnection();

$resourceController = new ResourceController($db);
$reservationController = new ReservationController($db);

// Default data
$resources = [];
$reservations = [];
$message = "";

// Routing
$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'update_reservation':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $resource_id = $_POST['resource_id'] ?? null;
            $location = $_POST['location'];
            $date_reservation = $_POST['date_reservation'];
            $date_retour = $_POST['date_retour'];
            $user = $_POST['user'];
            $etat = $_POST['etat'];

            if ($reservationController->updateReservation($id, $resource_id, $location, $date_reservation, $date_retour, $user, $etat)) {
                header("Location: index.php?action=home&message=reservation_updated");
                exit();
            } else {
                header("Location: index.php?action=home&message=reservation_update_error");
                exit();
            }
        }
        break;

    case 'delete_resource':
        if (isset($_GET['id'])) {
            $resourceId = $_GET['id'];
            if ($resourceController->deleteResource($resourceId)) {
                $message = "Resource deleted successfully!";
            } else {
                $message = "Error deleting resource.";
            }
        }
        header("Location: index.php?action=admin");
        break;

    case 'delete_reservation':
        if (isset($_GET['id'])) {
            $reservationId = $_GET['id'];
            if ($reservationController->deleteReservation($reservationId)) {
                $message = "Reservation deleted successfully!";
            } else {
                $message = "Error deleting reservation.";
            }
        }
        header("Location: index.php?action=home");
        break;

    case 'create_reservation':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $message = $reservationController->createReservation($_POST) ? "Reservation created successfully!" : "Error creating reservation.";
        }
        $resources = $resourceController->getAllResources();
        header("Location: index.php?action=home");
        break;

    case 'create_resource':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($resourceController->createResource($_POST)) {
                $message = "Resource created successfully!";
            } else {
                $message = "Error creating resource.";
            }
        }
        $resources = $resourceController->getAllResources();
        $reservations = $reservationController->getAllReservations();
        include '../views/home.php';
        break;

    case 'resources':
        $resources = $resourceController->getAllResources();
        $reservations = $reservationController->getAllReservations();
        include '../views/home.php';
        break;

        case 'rate_resource':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $resourceId = $_POST['resource_id'];
                $rating = $_POST['rating'];
                $userId = 5; 
        
                if ($resourceController->rateResource($resourceId, $rating, $userId)) {
                    echo json_encode([
                        'success' => true,
                        'likes' => $resourceController->getLikesCount($resourceId),
                        'dislikes' => $resourceController->getDislikesCount($resourceId),
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'You have already rated this resource.']);
                }
                exit;
            }
            break;
        
        

    case 'edit_resource':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $resourceData = [
                'type' => $_POST['type'] ?? null,
                'nom' => $_POST['nom'] ?? null,
                'description' => $_POST['description'] ?? null,
                'status' => $_POST['status'] ?? null,
            ];

            if ($resourceController->updateResource(
                $id,
                $resourceData['type'],
                $resourceData['nom'],
                $resourceData['description'],
                $resourceData['status']
            )) {
                header("Location: index.php?action=admin&message=resource_updated");
            } else {
                header("Location: index.php?action=admin&message=resource_update_error");
            }
            exit();
        }
        break;

    case 'admin':
        include '../views/add_resource.php';
        break;

    case 'home':
    default:
        $resources = $resourceController->getAllResources();
        $reservations = $reservationController->getAllReservations();
        // Handle search input for reservations
    if (isset($_GET['query'])) {
        $searchQuery = trim($_GET['query']);
        $filteredReservations = [];

        foreach ($reservations as $res) {
            if (stripos($res['user'], $searchQuery) !== false || stripos($res['location'], $searchQuery) !== false) {
                $filteredReservations[] = $res;
            }
        }

        $reservations = $filteredReservations;
    }

    include '../views/home.php';
}
