<?php
include_once '../Model/Categorie.php';
require_once __DIR__ . '/../../config/db.php';

class CategorieController {
    private $categorieModel;

    public function __construct($pdo) {
        $this->categorieModel = new Categorie($pdo);
    }

    public function index() {
        $categories = $this->categorieModel->readAll();
        include '../views/CategorieMAna.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $this->categorieModel->create($nom);
            header("Location: index.php?action=categorie");
            exit; // Stop further execution after redirection
        }
        include '../views/CategorieMAna.php';
    }

    public function delete($id) {
        $this->categorieModel->delete($id);
        header("Location: index.php?action=categorie");
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Update logic when the form is submitted via the modal
            $nom = $_POST['nom'];
            $this->categorieModel->update($id, $nom);
            echo json_encode(['success' => true]); // Respond with success
            exit;
        } else {
            // Fetch category details when the modal is opened
            $categorie = $this->categorieModel->find($id);
            
            if ($categorie) {
                echo json_encode($categorie); // Return the category data as a JSON response
            } else {
                echo json_encode(['error' => 'Category not found.']); // Handle not found case
            }
            exit; // Stop further execution
        }
    }
}
?>