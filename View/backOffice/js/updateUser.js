document.getElementById("forms").addEventListener("submit", function(event) {
    
  
    var username = document.getElementById("username").value;
    var email = document.getElementById("email").value;
    var phone = document.getElementById("phone").value;
    var birthDate = document.getElementById("birth_date").value;
    var password = document.getElementById("password").value;
    
    var isValid = true;


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
    /*else 
    {
        displayMessage("username", "Correct", false);
    }*/

    // email
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(email)) 
    {
        displayMessage("email", "Please enter a valid email address", true);
        isValid = false;
    } 
    /*else 
    {
        displayMessage("email", "Correct", false);
    }*/

    // phone
    if (isNaN(phone) || (!/^\d{8}$/.test(phone))) 
    {
        displayMessage("phone", "Please enter a valid phone number", true);
        isValid = false;
    } 
    /*else 
    {
        displayMessage("phone", "Correct", false);
    }*/

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
        /*else 
        {
            displayMessage("birth_date", "", false);
            
        } */

    // password
    if (password.length < 10) 
    {
        displayMessage("password", "Password must be at least 10 characters long", true);
        isValid = false;
    } 
    /*else 
    {
        displayMessage("password", "", false);
    }*/

    if (!isValid) 
    {
        event.preventDefault();
    }
    else
    {
        alert("Your changes have been saved!");
    }

  
});

