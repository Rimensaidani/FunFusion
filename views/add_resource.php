
<?php
// Manually include dependencies if accessed directly
if (!isset($resources) || !isset($reservations)) {
    include_once '../models/Resource.php';
    include_once '../models/Reservation.php';
    include_once '../config/db.php';
    include_once '../controllers/ResourceController.php';
    include_once '../controllers/ReservationController.php';

    $database = new Database();
    $db = $database->getConnection();

    $resourceController = new ResourceController($db);
    $reservationController = new ReservationController($db);

    $resources = $resourceController->getAllResources();
    $reservations = $reservationController->getAllReservations();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- site metas -->
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- bootstrap css -->
    <link rel="stylesheet" href="assets2/css/bootstrap.min.css">
    <!-- style css -->
    <link rel="stylesheet" href="assets2/css/style.css">
    <!-- Responsive-->
    <link rel="stylesheet" href="assets2/css/responsive.css">
    <!-- fevicon -->
    <link rel="icon" href="assets2/images/fevicon.png" type="image/gif" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- New line added here -->

    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="assets2/css/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Tweaks for older IEs-->
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css">
    <title>Admin Panel - Add Resource</title>
</head>

<body>
    <div id="adminPanel" class="contact">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage">
                        <h3>ADMIN PANEL</h3>
                    </div>
                </div>
            </div>

            <div class="white_bg">
                <div class="row">
                    <!-- Form Column -->
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        <div class="contact">
                            <h4 class="mb-3">Add New Resource</h4>
                            <form method="POST" action="index.php?action=create_resource" onsubmit="return validateResourceForm();">
                                <div class="col-sm-12 mb-2">
                                    <input class="contactus" type="text" name="type" placeholder="Resource Type" >
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <input class="contactus" type="text" name="nom" placeholder="Resource Name" >
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <textarea class="contactus" name="description" placeholder="Description" ></textarea>
                                </div>
                                <div class="col-sm-12 mb-2">
                                    <select class="contactus" name="status" >
                                        <option value="">Select Status</option>
                                        <option value="available">Available</option>
                                        <option value="unavailable">Unavailable</option>
                                    </select>
                                </div>
                                <div class="col-sm-12">
                                    <button class="send" type="submit">Add Resource</button>
                                </div>
                            </form>

                            <?php if (isset($_GET['add']) && $_GET['add'] == 'success'): ?>
                                <div class="alert alert-success mt-3">Resource added successfully!</div>
                            <?php elseif (isset($_GET['add']) && $_GET['add'] == 'error'): ?>
                                <div class="alert alert-danger mt-3">Unable to add resource. Please try again.</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Image Column -->
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        <div class="rable-box">
                            <figure><img src="assets2/images/lev2.jpeg" alt="#" style="width: 100%;" /></figure>
                        </div>
                    </div>
                </div>

                <hr class="my-4 w-100">

                <!-- Resources Table -->
                <div class="row">
                    <div class="col-12">
                        <h4 class="mb-3">Resources</h4>
                        <?php
                        include_once '../controllers/ResourceController.php';
                        
                        $resourceController = new ResourceController($db);
                        $resources = $resourceController->getAllResources();
                        ?>

                        <?php if (empty($resources)): ?>
                            <p>No resources found.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Type</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($resources as $res): ?>
    <tr>
        <td><?php echo htmlspecialchars($res['type']); ?></td>
        <td><?php echo htmlspecialchars($res['nom']); ?></td>
        <td><?php echo htmlspecialchars($res['description']); ?></td>
        <td><?php echo htmlspecialchars($res['status']); ?></td>
        <td>
            <button class="btn btn-warning btn-sm" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($res)); ?>)">Edit</button>
            <a href="index.php?action=delete_resource&id=<?php echo $res['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
            <button class="btn btn-success btn-sm" onclick="rateResource(<?php echo $res['id']; ?>, 'like');">Like</button>
            <button class="btn btn-danger btn-sm" onclick="rateResource(<?php echo $res['id']; ?>, 'dislike');">Dislike</button>
            <span id="likesCount<?php echo $res['id']; ?>"><?php echo $resourceController->getLikesCount($res['id']); ?> Likes</span>
            <span id="dislikesCount<?php echo $res['id']; ?>"><?php echo $resourceController->getDislikesCount($res['id']); ?> Dislikes</span>
        </td>
    </tr>
<?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pie Chart for Likes and Dislikes -->
                            <div id="pieChartContainer">
                            <canvas id="likesDislikesChart" width="400" height="400"></canvas>
                        </div>

                            <!-- Edit Resource Modal -->
                            <div id="editModal" class="modal fade" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Resource</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="editResourceForm" method="POST" action="index.php?action=edit_resource">
                                                <input type="hidden" name="id" id="editResourceId" required>
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="type" id="editResourceType" placeholder="Resource Type" required>
                                                </div>
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="nom" id="editResourceNom" placeholder="Resource Name" required>
                                                </div>
                                                <div class="form-group">
                                                    <textarea class="form-control" name="description" id="editResourceDescription" placeholder="Description" required></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <select class="form-control" name="status" id="editResourceStatus" required>
                                                        <option value="">Select Status</option>
                                                        <option value="available">Available</option>
                                                        <option value="unavailable">Unavailable</option>
                                                    </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Home Button -->
    <a href="index.php" class="home-float-btn" title="Go to Home">
        <i class="fas fa-home"></i>
    </a>

    <!-- Floating Button Style -->
    <style>
        #pieChartContainer {
            margin-top: 30px; /* Add some margin on top */
            width: 100%;
            max-width: 600px;
        }
        .home-float-btn {
            position: fixed;
            bottom: 25px;
            right: 25px;
            background-color: #343a40;
            color: white;
            padding: 12px 16px;
            border-radius: 50%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            font-size: 20px;
            z-index: 999;
            transition: all 0.3s ease;
        }

        .home-float-btn:hover {
            background-color: #212529;
            color: #ffc107;
            transform: scale(1.1);
            text-decoration: none;
        }

        .home-float-btn i {
            vertical-align: middle;
        }
    </style>

    
<script>
    // Fetch total likes and dislikes for the chart
    const totalLikes = <?php echo $resourceController->getTotalLikes(); ?>; // Use the new method for total likes
    const totalDislikes = <?php echo $resourceController->getTotalDislikes(); ?>; // Use the new method for total dislikes

    // Create the Pie Chart
    const ctx = document.getElementById('likesDislikesChart').getContext('2d');
    const likesDislikesChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Likes', 'Dislikes'],
            datasets: [{
                data: [totalLikes, totalDislikes],
                backgroundColor: ['#36A2EB', '#FF6384'], // Colors for the segments
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Likes and Dislikes'
                }
            }
        },
    });

    </script>
    <script>
    function openEditModal(resource) {
        document.getElementById('editResourceId').value = resource.id;
        document.getElementById('editResourceType').value = resource.type;
        document.getElementById('editResourceNom').value = resource.nom;
        document.getElementById('editResourceDescription').value = resource.description;
        document.getElementById('editResourceStatus').value = resource.status;

        $('#editModal').modal('show'); // Show the modal
    }

    function rateResource(resourceId, rating) {
    console.log("Clicked:", resourceId, rating); // DEBUG LOG

    $.post("index.php?action=rate_resource", {
        resource_id: resourceId,
        rating: rating
    }, function(data) {
        console.log("Server response:", data); // DEBUG LOG

        if (data.success) {
            $('#likesCount' + resourceId).text(data.likes + " Likes");
            $('#dislikesCount' + resourceId).text(data.dislikes + " Dislikes");
        } else {
            alert(data.message || "Error occurred");
        }
    }, "json").fail(function(xhr, status, error) {
        console.error("AJAX error:", status, error); // DEBUG LOG
        console.log("Response text:", xhr.responseText); // DEBUG LOG
    });
}

    function validateResourceForm() {
        const type = document.querySelector('input[name="type"]');
        const name = document.querySelector('input[name="nom"]');
        const description = document.querySelector('textarea[name="description"]');
        const status = document.querySelector('select[name="status"]');
        
        // Reset any previous error messages
        let isValid = true;
        const errorMessages = [];

        // Validate Resource Type
        if (type.value.trim() === "") {
            errorMessages.push("Resource Type is required.");
            isValid = false;
        }

        // Validate Resource Name
        if (name.value.trim() === "") {
            errorMessages.push("Resource Name is required.");
            isValid = false;
        }

        // Validate Description
        if (description.value.trim() === "") {
            errorMessages.push("Description is required.");
            isValid = false;
        }

        // Validate Status
        if (status.value === "") {
            errorMessages.push("Please select a Status.");
            isValid = false;
        }

        // Display error messages
        if (!isValid) {
            alert(errorMessages.join("\n"));
        }

        return isValid; // Return true if form is valid
    }
    </script>

</body>

<!-- jQuery (must be included first) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</html>