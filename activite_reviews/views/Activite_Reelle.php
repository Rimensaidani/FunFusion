<?php
// Include the database connection
require '../../config/db.php';
require_once '../Model/Activite_Reelle.php'; 
require_once '../Model/Categorie.php'; // Include Categorie model

// Create instances of Activity and Category classes
$activityModel = new Activite_Reelle($pdo);
$categoryModel = new Categorie($pdo);

// Initialize an array to hold error messages
$errors = []; 

// Initialize an array to hold activities
$activities = [];

// Handle search functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $keyword = trim($_POST['search']);
    if (!empty($keyword)) {
        // Call the search function in the Activity class
        $activities = $activityModel->search($keyword);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['show_all'])) {
    // Show all activities when "Show All" button is clicked
    try {
        $activities = $activityModel->readAll();
    } catch (Exception $e) {
        error_log("Database query failed: " . $e->getMessage());
        $errors[] = "Failed to retrieve activities.";
    }
} else {
    // Fetch all activities by default
    try {
        $activities = $activityModel->readAll();
    } catch (Exception $e) {
        error_log("Database query failed: " . $e->getMessage());
        $errors[] = "Failed to retrieve activities.";
    }
}

// Fetch categories for the dropdown menu
$categories = $categoryModel->readAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Management - Activité Réelle</title>

    <!-- Bootstrap and Custom CSS -->
    <link rel="stylesheet" href="assets2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets2/css/style.css">
    <link rel="icon" href="assets2/images/fevicon.png" type="image/gif" />
</head>
<body>
    <div id="booktable" class="contact">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage d-flex justify-content-between align-items-center">
                        <h3>Manage Activités Réelles</h3>
                        <a href="index.php?action=home" class="btn btn-outline-light">🏠 Home</a>
                    </div>
                </div>
            </div>

            <!-- Show error messages if they exist -->
            <div id="errorMessages" class="alert alert-danger <?= empty($errors) ? 'd-none' : '' ?>">
                <?php foreach ($errors as $error): ?>
                    <?= htmlspecialchars($error); ?>
                <?php endforeach; ?>
            </div>

            <div class="white_bg">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Add New Activity Form -->
                        <form id="activityForm" action="index.php?action=create_activity" method="POST">
                            <div class="form-group">
                                <label for="titre">Activity Title</label>
                                <input type="text" class="form-control" name="titre" id="titre" placeholder="Activity Title" >
                            </div>
                            
                            <div class="form-group">
                                <label for="lieu">Location</label>
                                <input type="text" class="form-control" name="lieu" id="lieu" placeholder="Location" >
                            </div>
                            
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" class="form-control" name="date" id="date" placeholder="Date" >
                            </div>

                            <button type="submit" class="btn btn-primary">Add Activité Réelle</button>
                        </form>
                    </div>

                    <div class="col-md-6">
                        <div class="rable-box">
                            <figure>
                                <img src="assets2/images/lev2.jpeg" alt="Activity" style="width: 100%; border-radius: 10px;" />
                            </figure>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Activities Section -->
            <h2 class="mt-5" style="color: white;">Search Activités Réelles</h2>
            <form action="index.php?action=Activite_Reelle" method="POST" class="mb-4">
                <div class="form-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by Activity Title">
                </div>
                <button type="submit" class="btn btn-secondary">Search</button>
            </form>

            <!-- Show All Activities Button -->
            <form action="index.php?action=Activite_Reelle" method="POST" class="mb-4">
                <button type="submit" name="show_all" class="btn btn-success">Show All Activités Réelles</button>
            </form>

            <h2 class="mt-5" style="color: white;">List of Activités Réelles</h2>
            <ul class="list-group">
                <?php if (!empty($activities)): ?>
                    <?php foreach ($activities as $activity): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($activity['titre']); ?> (<?= htmlspecialchars($activity['lieu']); ?>)
                            <div>
                                <a href="index.php?action=registrations&id=<?= $activity['id_activite']; ?>" class="btn btn-info btn-sm">Manage Inscription</a>
                                <a href="index.php?action=delete_activity&id=<?= $activity['id_activite']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="<?= $activity['id_activite']; ?>">Edit</button>
                                </div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item">No activities found.</li>
                <?php endif; ?>
            </ul>
        </div>
        <!-- Edit Activity Modal -->
<div class="modal fade" id="editActivityModal" tabindex="-1" role="dialog" aria-labelledby="editActivityModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="editActivityForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Activity</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="edit-id" name="id">
          <div class="form-group">
            <label>Title</label>
            <input type="text" class="form-control" id="edit-titre" name="titre" >
          </div>
          <div class="form-group">
            <label>Location</label>
            <input type="text" class="form-control" id="edit-lieu" name="lieu" >
          </div>
          <div class="form-group">
            <label>Date</label>
            <input type="date" class="form-control" id="edit-date" name="date" >
          </div>
          
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save changes</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="validation.js"></script>
    <script src="assets2/js/jquery.min.js"></script>
    <script src="assets2/js/bootstrap.bundle.min.js"></script>
    <script>
$(document).ready(function () {
    $('.edit-btn').click(function () {
        var id = $(this).data('id');

        // Make AJAX request to fetch activity data
        $.get('index.php?action=edit_activity&id=' + id, function (data) {
            try {
                var activity = JSON.parse(data);
                // Only fill the data if it was successful
                if (activity && !activity.error) {
                    $('#edit-id').val(id);
                    $('#edit-titre').val(activity.titre);
                    $('#edit-lieu').val(activity.lieu);
                    $('#edit-date').val(activity.date);
                    $('#editActivityModal').modal('show');
                } else {
                    console.error(activity.error);
                }
            } catch (e) {
                console.error("Parsing error:", e);
                console.warn("Received data:", data); // Log the full response for debugging
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX request failed:", textStatus, errorThrown);
        });
    });

    $('#editActivityForm').submit(function (e) {
        e.preventDefault();
        var id = $('#edit-id').val();
        var formData = $(this).serialize();

        $.post('index.php?action=edit_activity&id=' + id, formData, function (response) {
            try {
                var result = JSON.parse(response);
                if (result.success) {
                    $('#editActivityModal').modal('hide');
                    location.reload(); // Reload the list
                } else {
                    alert(result.message || 'Error updating activity.');
                }
            } catch (e) {
                console.warn("An error occurred while processing the response:", e);
                console.error("Response data:", response);
                alert('Could not parse response.');
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX request failed:", textStatus, errorThrown);
        });
    });
});
</script>

</body>
</html>