document.getElementById("myForm").addEventListener("submit", function(event) {
    
    var phone = document.getElementById("phone").value;
    var isValid = true;

    function displayMessage(id, message, isError) 
    {
        var element = document.getElementById(id + "_error");
        element.style.color = isError ? "red" : "green";
        element.innerText = message;
    }
    // phone
    if (!/^\d{8}$/.test(phone))
    {
        displayMessage("phone", "Please enter a valid phone number", true);
        isValid = false;
    } 
    else
    {
        displayMessage("phone", "", false);
    }

    if (!isValid) 
    {
        event.preventDefault();
    }
});

