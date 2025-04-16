/*document.getElementById("forms").addEventListener("submit",function(e)
{
    e.preventDefault();
    let username=document.getElementById("username");
    let pUsername=document.getElementById("pUsername");
    if (username.value.length<3) 
    {
        pUsername.hidden=false;  
    }
    else
    { 
        pUsername.innerHTML="Correct";
        pUsername.classList.remove('red');
        pUsername.classList.add('green'); 
        pUsername.hidden=true;  
    }


    let email=document.getElementById("email");
    let pEmail=document.getElementById("pEmail");
    if (!/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email.value)) 
    {
        pEmail.innerHTML="Please enter a valid email address";
        pEmail.classList.remove('green');
        pEmail.classList.add('red');   
    }
    else
    {   
        pEmail.innerHTML="Correct";
        pEmail.classList.remove('red');
        pEmail.classList.add('green');  
    }


    let phone=document.getElementById("phone");
    let pPhone=document.getElementById("pPhone");
    if (phone.value<10000000 || phone.value>99999999) 
    {
        pPhone.innerHTML="Please enter a valid phone number";
        pPhone.classList.remove('green');
        pDeppPhoneart.classList.add('red');   
    }
    else
    { 
        pPhone.innerHTML="Correct";
        pPhone.classList.remove('red');
        pPhone.classList.add('green');    
    }


    let bDate=document.getElementById("date_birth");
    let pDate_birth=document.getElementById("pDate_birth");
    if (isNaN(bDate.getTime())) 
    {
        pDate_birth.innerHTML="Please enter your date of birth.";
        pDate_birth.classList.remove('green');
        pRetpDate_birthurn.classList.add('red');   
    }
    else
    { 
        pDate_birth.innerHTML="Correct";
        pRepDate_birthturn.classList.remove('red');
        pDate_birth.classList.add('green');   
    }


    let password=document.getElementById("password");
    let pPassword=document.getElementById("pPassword");
    if (password.value.length<8) 
    {
        pPassword.innerHTML="Password must contain at least 8 characters.";
        pPassword.classList.remove('green');
        pPassword.classList.add('red');   
    }
    else
    { 
        pPassword.innerHTML="Correct";
        pPassword.classList.remove('red');
        pPassword.classList.add('green');    
    }
});*/














/*document.getElementById("username").addEventListener("keyup",function(e)
{
    e.preventDefault();
    let username=document.getElementById("username");
    let pUsername=document.getElementById("pUsername");
    if(username.length<3)
    {
        pUsername.hidden=false;   
    }
    else
    { 
        pUsername.hidden=true; 
    }
    
});


document.getElementById("email").addEventListener("keyup",function(e)
{
    e.preventDefault();
    let email=document.getElementById("email");
    let pEmail=document.getElementById("pEmail");
    if(!/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email.value))
    {
        pEmail.hidden=false;   
    }
    else
    { 
        pEmail.hidden=true; 
    }
    
});


document.getElementById("phone").addEventListener("keyup",function(e)
{
    e.preventDefault();
    let phone=document.getElementById("phone");
    let pPhone=document.getElementById("pPhone");
    if(phone.value<10000000 || phone.value>99999999)
    {
        pPhone.hidden=false;   
    }
    else
    { 
        pPhone.hidden=true; 
    }
    
});


document.getElementById("bDate").addEventListener("keyup",function(e)
{
    e.preventDefault();
    let bDate=document.getElementById("bDate");
    let pBDate=document.getElementById("pBDate");
    if(isNaN(bDate.getTime()))
    {
        pBDate.hidden=false;   
    }
    else
    { 
        pBDate.hidden=true; 
    }
    
});


document.getElementById("role").addEventListener("change", function(e) {
    e.preventDefault();
    
    let role = document.getElementById("role");
    let pRole = document.getElementById("pRole");
    
    if (role.value === "") 
        {
        pRole.hidden = false;
    } 
    else 
    {
        pRole.hidden = true;
    }
});


document.getElementById("password").addEventListener("keyup",function(e)
{
    e.preventDefault();
    let password=document.getElementById("password");
    let pPassword=document.getElementById("pPassword");
    let val = password.value;
    if(val.length<10 || !/[0-9]/.test(val)|| !/[._-!%?]/.test(val) )
    {
        pPassword.hidden=false;   
    }
    else
    { 
        pPassword.hidden=true; 
    }


    
    
});

*/


document.addEventListener('DOMContentLoaded', function() {
    
    var usernameField = document.getElementById('username');
    var emailField = document.getElementById('email');
    var phoneField = document.getElementById('phone');
    var roleField = document.getElementById('role');
    var passwordField = document.getElementById('password');
   
    usernameField.addEventListener('keyup', validateUsername);
    emailField.addEventListener('keyup', validateEmail);
    phoneField.addEventListener('keyup',validatePhone);
    roleField.addEventListener('keyup',validateRole);
    passwordField.addEventListener('keyup',validatePassword);

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

    function validatePassword() 
    {
        var password = passwordField.value;
        var passwordError = document.getElementById('password_error');

        if (password.length<10) 
        {
            passwordError.style.color = "red";
            passwordError.innerHTML = "Password must be at least 10 characters long";
        } 
        else 
        {
            passwordError.hidden=true;
        }
    }



   
 });



