/*document.addEventListener('DOMContentLoaded', function() {
    
    var usernameField = document.getElementById('username');
    var emailField = document.getElementById('email');
    var phoneField = document.getElementById('phone');
    var birth_dateField = document.getElementById('birth_date');
    var passwordField = document.getElementById('password');

   
    usernameField.addEventListener('keyup', validateUsername);
    emailField.addEventListener('keyup', validateEmail);
    phoneField.addEventListener('keyup',validatePhone);
    birth_dateField.addEventListener('change',validateBirth_date);
    passwordField.addEventListener('keyup',validatePassword);
 
    
    var form = document.getElementById('forms');

    form.addEventListener('submit', function (event) 
    {
        let isValid = true;

        if (!validateUsername()) isValid = false;
        if (!validateEmail()) isValid = false;
        if (!validatePhone()) isValid = false;
        if (!validateBirth_date()) isValid = false;
        if (!validatePassword()) isValid = false;
        alert("Submission failed. Ensure all required fields are valid.");

        if (!isValid) {
            event.preventDefault();
        }
    });


    function validateUsername() 
    {
        var username = usernameField.value;
        var usernameError = document.getElementById('username_error');

        if (username.length < 3) 
        {
            usernameError.style.color = "red";
            usernameError.innerHTML = "Username must contain at least 3 characters";
            phoneError.hidden=false;
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
            emailError.style.color = "red";
            emailError.innerHTML = "Please enter a valid email address";
            phoneError.hidden=false;
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
            phoneError.style.color = "red";
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
            bdateError.style.color = "red";
            bdateError.innerHTML = "You must be over 18 to have an account";
            phoneError.hidden=false;
            return false;
        } else {
            bdateError.hidden = true;
            return true;
        }
    }


    function validatePassword() 
    {
        var password = passwordField.value;
        var passwordError = document.getElementById('password_error');

        if (password.length<10) 
        {
            passwordError.style.color = "red";
            passwordError.innerHTML = "Password must be at least 10 characters long";
            phoneError.hidden=false;
            return false;
        } 
        else 
        {
            passwordError.hidden=true;
            return true;
        }
    }


   
 });
*/

document.getElementById("forms").addEventListener("submit", function(event) {
    
  
    var username = document.getElementById("username").value;
    var email = document.getElementById("email").value;
    var phone = document.getElementById("phone").value;
    var birthDate = document.getElementById("birth_date").value;
    var password = document.getElementById("password").value;
    
    var isValid = true;

    // Fonction pour afficher les messages d'erreur ou de succès
    function displayMessage(id, message, isError) 
    {
        var element = document.getElementById(id + "_error");
        element.style.color = isError ? "red" : "green";
        element.innerText = message;
    }

    // username
    if (username.length < 3) 
    {
        displayMessage("username", "Username must contain at least 3 characters", true);
        isValid = false;
    } 
    else 
    {
        displayMessage("username", "", false);
    }

    // email
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(email)) 
    {
        displayMessage("email", "Please enter a valid email address", true);
        isValid = false;
    } 
    else 
    {
        displayMessage("email", "", false);
    }

    // phone
    if (isNaN(phone) || (!/^\d{8}$/.test(phone))) 
    {
        displayMessage("phone", "Please enter a valid phone number", true);
        isValid = false;
    } 
    else
    {
        displayMessage("phone", "", false);
    }

    // birthday
        var bdate = new Date(birthDate);
        var today = new Date();
        var age = today.getFullYear() - bdate.getFullYear();
        var monthDiff = today.getMonth() - bdate.getMonth();
    
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < bdate.getDate())) 
        {
            age--;
        }
    
        if (isNaN(bdate.getTime()) || age < 18) 
        {
            displayMessage("birth_date", "You must be over 18 to have an account", true);
            isValid = false; 
        } 
        else
        {
            displayMessage("birth_date", "", false);
            
        } 

    // password
    if (password.length < 10) 
    {
        displayMessage("password", "Password must be at least 10 characters long", true);
        isValid = false;
    } 
    else 
    {
        displayMessage("password", "", false);
    }


    if (!isValid) 
    {
        event.preventDefault();
        alert("Submission failed. Ensure all required fields are valid.");
    }
    else
    {
        alert("Your account has been created successfully!");
    }

  
});






