
 document.getElementById("form").addEventListener("submit", function(event) {
    
  
    var username = document.getElementById("username").value;
    var email = document.getElementById("email").value;
    var phone = document.getElementById("phone").value;
    var birthDate = document.getElementById("birth_date").value;
    
    
    var isValid = true;

    function displayMessage(id, message, isError) 
    {
        var element = document.getElementById(id + "_error");
        element.style.color = isError ? "white" : "green";
        element.innerText = message;
    }

    // username
    if (username.length < 3) 
    {
        displayMessage("username", "Username must contain at least 3 characters", true);
        isValid = false;
    } 
    

    // email
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(email)) 
    {
        displayMessage("email", "Please enter a valid email address", true);
        isValid = false;
    } 
    

    // phone
    if (isNaN(phone) || (!/^\d{8}$/.test(phone))) 
    {
        displayMessage("phone", "Please enter a valid phone number", true);
        isValid = false;
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
        
    

    if (!isValid) 
    {
        event.preventDefault();
    }
    else
    {
        alert("Your changes have been saved!");
    }

  
});






