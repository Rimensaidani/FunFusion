<?php
include '../../config/db.php';
include '../Controllers/ActivityController.php';
include '../Controllers/HomeController.php';
include '../Controllers/CategorieController.php';

$action = $_GET['action'] ?? 'home'; // Default action to 'home'
$activityId = $_GET['id'] ?? null;

$activityController = new ActivityController($pdo);
$homeController = new HomeController($pdo);
$categorieController = new CategorieController($pdo);

switch ($action) {
    case 'home':
        $homeController->index();
        break;

    case 'Activite_Reelle':
        $activityController->index();
        break;

    case 'create_activity':
        $activityController->create();
        break;

    case 'delete_activity':
        $activityController->delete($activityId);
        break;

    case 'edit_activity':
        $activityController->edit($activityId);
        break;

    case 'categorie':
        $categorieController->index();
        break;

    case 'create_categorie':
        $categorieController->create();
        break;

    case 'delete_categorie':
        $categorieController->delete($_GET['id']);
        break;

    case 'manage_categories':
        $categorieController->index();
        break;

    default:
        $homeController->index();
        break;
}
?>