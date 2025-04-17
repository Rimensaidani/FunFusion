
document.addEventListener('DOMContentLoaded', function() {
    
    var usernameField = document.getElementById('username');
    var emailField = document.getElementById('email');
    var phoneField = document.getElementById('phone');
    var roleField = document.getElementById('role');
   
    usernameField.addEventListener('keyup', validateUsername);
    emailField.addEventListener('keyup', validateEmail);
    phoneField.addEventListener('keyup',validatePhone);
    roleField.addEventListener('keyup',validateRole);


    function validateUsername() 
    {
        var username = usernameField.value;
        var usernameError = document.getElementById('username_error');

        if (username.length < 3) 
        {
            usernameError.style.color = "red";
            usernameError.innerHTML = "Username must contain at least 3 characters";
        } 
        else 
        {
            usernameError.hidden=true;
            //usernameError.style.color = "green";
            //usernameError.innerHTML = "Correct";
        }
    }

    function validateEmail() 
    {
        var email = emailField.value;
        var emailError = document.getElementById('email_error');
        var regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        if ( !regex.test(email)) 
        {
            emailError.style.color = "red";
            emailError.innerHTML = "Please enter a valid email address";
        } 
        else 
        {
            emailError.hidden=true;
        }
    }

    function validatePhone() 
    {
        var phone = phoneField.value;
        var phoneError = document.getElementById('phone_error');

        if (isNaN(phone) || phone < 10000000 || phone > 99999999) 
        {
            phoneError.style.color = "red";
            phoneError.innerHTML = "Please enter a valid phone number";
        } 
        else 
        {
            phoneError.hidden=true;
        }
    }

    function validateRole() 
    {
        var role = roleField.value;
        var roleError = document.getElementById('role_error');

        if (role.value === "") 
        {
            roleError.style.color = "red";
            roleError.innerHTML = "Please select a role";
        } 
        else 
        {
            roleError.hidden=true;
        }
    }




   
 });



