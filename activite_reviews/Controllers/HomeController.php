<?php
include_once '../Model/Activite_Reelle.php';
include_once '../Model/Categorie.php'; // Include the Categorie model
require_once __DIR__ . '/../../config/db.php';

class HomeController {
    private $activityModel;
    private $categoryModel;

    public function __construct($pdo) {
        $this->activityModel = new Activite_Reelle($pdo);
        $this->categoryModel = new Categorie($pdo); // Instantiate the Categorie model
    }

    public function index() {
        // Handle POST request to add activity
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titre'], $_POST['lieu'], $_POST['date'], $_POST['categorie_id'])) {
            $titre = $_POST['titre'];
            $lieu = $_POST['lieu'];
            $date = $_POST['date'];
            $categorie_id = $_POST['categorie_id'];
            $this->activityModel->create($titre, $lieu, $date, $categorie_id);
            $message = "Activité ajoutée avec succès !";
        }

        // Fetch all activities
        $activities = $this->activityModel->readAll();
        // Fetch all categories for the add activity form
        $categories = $this->categoryModel->readAll();

        include '../views/home.php'; // Include the home view
    }
}
?>