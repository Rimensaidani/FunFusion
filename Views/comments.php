<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="assets2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets2/css/style.css">
    <link rel="stylesheet" href="assets2/css/responsive.css">
    <link rel="icon" href="assets2/images/fevicon.png" type="image/gif" />
    <link rel="stylesheet" href="assets2/css/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
    <title>Comments Management</title>
</head>
<body>
    <div id="manageComments" class="contact">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage">
                        <h3>Manage Comments</h3>
                    </div>
                </div>
            </div>

            <div class="white_bg">
                <div class="row">
                    <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">
                        <div class="contact">
                            <?php if (!empty($comments)): ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Content</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($comments as $comment): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($comment['content']) ?></td>
                                                <td><?= htmlspecialchars($comment['created_at']) ?></td>
                                                <td>
                                                    <a href="index.php?action=delete_comment&comment_id=<?= $comment['id'] ?>&post_id=<?= $comment['post_id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                                                    <button data-id="<?= $comment['id'] ?>" data-content="<?= htmlspecialchars($comment['content']) ?>" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editCommentModal">Edit</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p>No comments found for this post.</p>
                            <?php endif; ?>

                            <a href="index.php?action=admin" class="btn btn-secondary mt-2">Back to Admin Panel</a>
                        </div>
                    </div>

                    <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 d-flex align-items-center">
                        <div class="rable-box w-100">
                            <figure><img src="assets2/images/lev2.jpeg" alt="#" style="width: 100%; border-radius: 10px;"></figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Comments -->
    <div class="modal fade" id="editCommentModal" tabindex="-1" role="dialog" aria-labelledby="editCommentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCommentModalLabel">Edit Comment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editCommentForm" method="post" action="index.php?action=update_comment" data-ajax="true">
                    <div class="modal-body">
                        <input type="hidden" name="comment_id" id="comment_id" value="">
                        <div class="form-group">
                            <label for="comment_text">Comment:</label>
                            <textarea class="form-control" name="comment_text" id="comment_text"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="commentValidation.js"></script>
    <script>
       $(document).ready(function () {
    // Set up modal with comment details when the edit button is clicked
    $('button[data-target="#editCommentModal"]').click(function () {
        const commentId = $(this).data('id');
        const commentContent = $(this).data('content');
        
        $('#comment_id').val(commentId);
        $('#comment_text').val(commentContent);
    });

    // Handle form submission for editing comment
   // Handle form submission for editing comment
$('#editCommentForm').submit(function (e) {
    e.preventDefault(); // Prevent default form submission

    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        success: function (data) {
            // Parse response as JSON
            const response = JSON.parse(data);
            if (response.success) {
                // Update the comment in the table
                const updatedComment = `<td>${response.updated_content}</td>
                                        <td>${response.updated_at}</td>
                                        <td>
                                            <a href="index.php?action=delete_comment&comment_id=${response.comment_id}&post_id=${response.post_id}" class="btn btn-danger btn-sm">Delete</a>
                                            <button data-id="${response.comment_id}" data-content="${response.updated_content}" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editCommentModal">Edit</button>
                                        </td>`;
                $(`button[data-id='${response.comment_id}']`).closest('tr').html(updatedComment);
                $('#editCommentModal').modal('hide'); // Hide the modal after update
            } else {
                alert(response.message); // Display an error message
            }
        },
        error: function (xhr) {
            console.error(xhr);
            alert("An error occurred while updating the comment.");
        }
    });
});
    </script>
</body>
</html>