document.getElementById("myForm").addEventListener("submit", function(event) {
    
    var email = document.getElementById("email").value;
    var isValid = true;

    function displayMessage(id, message, isError) 
    {
        var element = document.getElementById(id + "_error");
        element.style.color = isError ? "red" : "green";
        element.innerText = message;
    }

    //email
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

    if (!isValid) 
    {
        event.preventDefault();
    }
});

