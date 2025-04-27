document.getElementById("myForm").addEventListener("submit", function(event) {
    
    var password = document.getElementById("password").value;
    var confirm_password = document.getElementById("confirm_password").value;
    var password_error = document.getElementById("password_error");
    var confirm_password_error = document.getElementById("confirm_password_error");

    var isValid = true;

    // password
    if (password.length < 10)
    {
        password_error.innerText = "Password must be at least 10 characters long";
        password_error.style.color = "red";
        isValid=false;
    } 
    else
    {
        password_error.innerText = "";
        isValid=true;
    }
    
    if (password !== confirm_password) 
    {
        confirm_password_error.style.color = "red";
        confirm_password_error.innerText = "Passwords do not match!";
        isValid=false;
    } 
    else 
    {
        confirm_password_error.innerText = "";
        isValid=true;
    }

    if (!isValid) 
    {
        event.preventDefault();
    }
    else
    {
        alert("password reset successfully")
    }
});


    

