<?php
// Include the database connection
require '../../config/db.php';
require_once '../Model/Categorie.php'; // Include Categorie model

// Create an instance of the Categorie class
$categoryModel = new Categorie($pdo);

// Initialize an array to hold error messages
$errors = []; 

// Initialize an array to hold categories
$categories = [];

// Handle search functionality, if implemented
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $keyword = trim($_POST['search']);
    if (!empty($keyword)) {
        // Call the search function in the Categorie class if defined, or adjust as necessary
        $categories = $categoryModel->search($keyword); // Assuming you define search method in Categorie model
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['show_all'])) {
    // Show all categories when "Show All" button is clicked
    try {
        $categories = $categoryModel->readAll();
    } catch (Exception $e) {
        error_log("Database query failed: " . $e->getMessage());
        $errors[] = "Failed to retrieve categories.";
    }
} else {
    // Fetch all categories by default
    try {
        $categories = $categoryModel->readAll();
    } catch (Exception $e) {
        error_log("Database query failed: " . $e->getMessage());
        $errors[] = "Failed to retrieve categories.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>

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
                        <h3>Manage Categories</h3>
                        <a href="index.php?action=home" class="btn btn-outline-light">🏠 Home</a>
                    </div>
                </div>
            </div>

            <!-- Display error messages if they exist -->
            <div id="errorMessages" class="alert alert-danger <?= empty($errors) ? 'd-none' : '' ?>">
                <?php foreach ($errors as $error): ?>
                    <?= htmlspecialchars($error); ?>
                <?php endforeach; ?>
            </div>

            <div class="white_bg">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Add New Category Form -->
                        <form id="categoryForm" action="index.php?action=create_categorie" method="POST">
                            <div class="form-group">
                                <label for="nom">Category Name</label>
                                <input type="text" class="form-control" name="nom" id="nom" placeholder="Category Name" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Category</button>
                        </form>
                    </div>

                    <div class="col-md-6">
                        <div class="rable-box">
                            <figure>
                                <img src="assets2/images/lev2.jpeg" alt="Category" style="width: 100%; border-radius: 10px;" />
                            </figure>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Categories Section -->
            <h2 class="mt-5" style="color: white;">Search Categories</h2>
            <form action="index.php?action=categorie" method="POST" class="mb-4">
                <div class="form-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by Category Name">
                </div>
                <button type="submit" class="btn btn-secondary">Search</button>
            </form>

            <!-- Show All Categories Button -->
            <form action="index.php?action=categorie" method="POST" class="mb-4">
                <button type="submit" name="show_all" class="btn btn-success">Show All Categories</button>
            </form>

            <h2 class="mt-5" style="color: white;">List of Categories</h2>
            <ul class="list-group">
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($category['nom']); ?>
                            <div>
                            <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="<?= $category['id_categorie']; ?>">Edit</button>                            <a href="index.php?action=delete_categorie&id=<?= $category['id_categorie']; ?>" class="btn btn-danger btn-sm">Delete</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item">No categories found.</li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Edit Category Modal -->
        <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form id="editCategoryForm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Category</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="edit-id" name="id">
                            <div class="form-group">
                                <label>Category Name</label>
                                <input type="text" class="form-control" id="edit-nom" name="nom" required>
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
    <script>
        $(document).ready(function () {
            $('.edit-btn').click(function () {
                var id = $(this).data('id');

                // Fetch category data via AJAX
                $.get('index.php?action=edit&id=' + id, function (data) {
                    try {
                        var category = JSON.parse(data);
                        if (category && !category.error) {
                            $('#edit-id').val(id);
                            $('#edit-nom').val(category.nom);
                            $('#editCategoryModal').modal('show');
                        } else {
                            alert('Error fetching category: ' + category.error);
                        }
                    } catch (e) {
                        console.error(e);
                    }
                }).fail(function (jqXHR) {
                    console.error("AJAX request failed:", jqXHR.statusText);
                });
            });

            $('#editCategoryForm').submit(function (e) {
                e.preventDefault();
                var id = $('#edit-id').val();
                var formData = $(this).serialize();

                // Update category via AJAX
                $.post('index.php?action=edit&id=' + id, formData, function (response) {
                    try {
                        var result = JSON.parse(response);
                        if (result.success) {
                            $('#editCategoryModal').modal('hide');
                            location.reload(); // Reload the page to see changes
                        } else {
                            alert('Error updating category.');
                        }
                    } catch (e) {
                        alert('Could not parse response.');
                    }
                }).fail(function (jqXHR) {
                    console.error("AJAX request failed:", jqXHR.statusText);
                });
            });
        });
    </script>
</body>
</html>