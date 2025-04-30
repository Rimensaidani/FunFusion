// commentValidation.js

document.addEventListener("DOMContentLoaded", function () {
    const commentForms = document.querySelectorAll("form[action*='create_comment']");

    commentForms.forEach(commentForm => {
        commentForm.addEventListener("submit", function (e) {
            const commentInput = commentForm.querySelector("textarea[name='comment_text']");

            let isValid = true;
            let errorMessage = "";

            // Validate comment content
            if (commentInput.value.trim() === "") {
                errorMessage += "Comment cannot be empty.\n";
                isValid = false;
            } else if (commentInput.value.length < 5) {
                errorMessage += "Comment must be at least 5 characters long.\n";
                isValid = false;
            }

            // Alert errors and prevent submission if invalid
            if (!isValid) {
                e.preventDefault(); // Prevent form submission
                alert(errorMessage);
            }
        });
    });
});