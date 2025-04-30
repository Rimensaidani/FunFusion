<?php
include_once '../Model/Activite_reelle.php';
require_once __DIR__ . '/../../config/db.php';

class ActivityController {
    private $activityModel;

    public function __construct($pdo) {
        $this->activityModel = new Activite_Reelle($pdo);
    }

    public function index() {
        $activities = $this->activityModel->readAll();
        include '../views/Activite_Reelle.php';
    }

    public function getAllActivities() {
        return $this->activityModel->readAll(); 
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'];
            $lieu = $_POST['lieu'];
            $date = $_POST['date'];
            $this->activityModel->create($titre, $lieu, $date);
        }
        include '../views/Activite_Reelle.php';
    }

    public function delete($id) {
        $this->activityModel->delete($id);
        header("Location: index.php?action=activities");
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // AJAX form submit (edit)
            $titre = $_POST['titre'];
            $lieu = $_POST['lieu'];
            $date = $_POST['date'];
    
            if ($this->activityModel->update($id, $titre, $lieu, $date)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Update failed.']);
            }
            exit;
        } else {
            // AJAX request to fetch activity data
            $activity = $this->activityModel->find($id);
            
            if ($activity) {
                echo json_encode($activity);
            } else {
                echo json_encode(['error' => 'Activity not found.']);
            }
            exit;
        }
    }
        
    
}
?>
