// postValidation.js

document.addEventListener("DOMContentLoaded", function () {
    const postForm = document.querySelector("form[action*='create_post']");
    
    if (postForm) {
        postForm.addEventListener("submit", function (e) {
            const titleInput = postForm.querySelector("input[name='post_title']");
            const contentInput = postForm.querySelector("textarea[name='post_content']");
            
            let isValid = true;
            let errorMessage = "";

            // Validate title
            if (titleInput.value.trim() === "") {
                errorMessage += "Post title cannot be empty.\n";
                isValid = false;
            }

            // Validate content
            if (contentInput.value.trim() === "") {
                errorMessage += "Post content cannot be empty.\n";
                isValid = false;
            } else if (contentInput.value.length < 10) {
                errorMessage += "Post content must be at least 10 characters long.\n";
                isValid = false;
            }

            // Alert errors and prevent submission if invalid
            if (!isValid) {
                e.preventDefault(); // Prevent form submission
                alert(errorMessage);
            }
        });
    }
});