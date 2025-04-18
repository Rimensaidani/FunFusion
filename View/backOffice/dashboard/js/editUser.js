document.addEventListener('DOMContentLoaded', function() {
    
    var usernameField = document.getElementById('username');
    var emailField = document.getElementById('email');
    var phoneField = document.getElementById('phone');
    var birth_dateField = document.getElementById('birth_date');
    var roleField = document.getElementById('role');
    var passwordField = document.getElementById('password');

   
    usernameField.addEventListener('keyup', validateUsername);
    emailField.addEventListener('keyup', validateEmail);
    phoneField.addEventListener('keyup',validatePhone);
    birth_dateField.addEventListener('change',validateBirth_date);
    roleField.addEventListener('keyup',validateRole);
    passwordField.addEventListener('keyup',validatePassword);
    
 
    
    var form = document.getElementById('form');

    form.addEventListener('submit', function (event) 
    {
        let isValid = true;

        if (!validateUsername()) isValid = false;
        if (!validateEmail()) isValid = false;
        if (!validatePhone()) isValid = false;
        if (!validateBirth_date()) isValid = false;
        if (!validateRole()) isValid = false;
        if (!validatePassword()) isValid = false;

        if (!isValid) 
        {
            event.preventDefault();
            alert("Submission failed. Ensure all required fields are valid.");
        }
        else
        {
            alert("Form submitted successfully!");
        }
    });
    


    function validateUsername() 
    {
        var username = usernameField.value;
        var usernameError = document.getElementById('username_error');

        if (username.length < 3) 
        {
            usernameError.style.color = "white";
            usernameError.innerHTML = "Username must contain at least 3 characters";
            usernameError.hidden=false;
            return false;
        } 
        else 
        {
            usernameError.hidden=true;
            return true;
            //usernameError.style.color = "green";
            //usernameError.innerHTML = "Correct";
        }
    }


    function validateEmail() 
    {
        var email = emailField.value;
        var emailError = document.getElementById('email_error');
        var pattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        if ( !pattern.test(email)) 
        {
            emailError.style.color = "white";
            emailError.innerHTML = "Please enter a valid email address";
            emailError.hidden=false;
            return false;
        } 
        else 
        {
            emailError.hidden=true;
            return true;
        }
    }


    function validatePhone() 
    {
        var phone = phoneField.value;
        var phoneError = document.getElementById('phone_error');

        if (isNaN(phone) || (!/^\d{8}$/.test(phone)) )
        {
            phoneError.style.color = "white";
            phoneError.innerHTML = "Please enter a valid phone number";
            phoneError.hidden=false;
            return false;
        } 
        else 
        {
            phoneError.hidden=true;
            return true;
        }
    }


    function validateBirth_date() 
    {
        var bdate = new Date(birth_dateField.value);
        var bdateError = document.getElementById('dateb_error');
    
        var today = new Date();
        var age = today.getFullYear() - bdate.getFullYear();
        var monthDiff = today.getMonth() - bdate.getMonth();
    
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < bdate.getDate())) 
        {
            age--;
        }
    
        if (isNaN(bdate.getTime()) || age < 18) 
            {
            bdateError.style.color = "white";
            bdateError.innerHTML = "You must be over 18 to have an account";
            bdateError.hidden=false;
            return false;
        } else {
            bdateError.hidden = true;
            return true;
        }
    }

    function validateRole() 
    {
        var role = roleField.value;
        var roleError = document.getElementById('role_error');

        if (role=="")
        {
            roleError.style.color = "white";
            roleError.innerHTML = "Please select a role";
            roleError.hidden=false;
            return false;
        } 
        else 
        {
            roleError.hidden=true;
            return true;
        }
    }

    function validatePassword() 
    {
        var password = passwordField.value;
        var passwordError = document.getElementById('password_error');

        if (password.length<10) 
        {
            passwordError.style.color = "white";
            passwordError.innerHTML = "Password must be at least 10 characters long";
            passwordError.hidden=false;
            return false;
        } 
        else 
        {
            passwordError.hidden=true;
            return true;
        }
    }


   
 });