$(document).ready(function () {
    $("#loader").show();
    $(".container-body").hide();

    setTimeout(function () {
         $(".container-body").show();
        $("#loader").hide();
    }, 1000);
    
    $('#signupForm').on('submit', function (e) {
        e.preventDefault();
        
        if (validateInputs()) {
            $.ajax({
                url: "http://localhost/intern/Php/register.php", // PHP file that handles the registration
                type: "POST",
      
                data: {
                    "userName": $('#username').val(),
                    "email": $('#email').val(),
                    "password": $('#password').val()
                },
                dataType: "json",
                success: function (response) {
                    $('#loader').hide(); // Hide loader when the response is received
                    if (response.status === "success") {
                        alert("Registration successful!");
                        window.location.href = "login.html"; // Redirect after successful signup
                    } else {
                        alert("Error: " + response);
                    }
                },
                error: function () {
                    $('#loader').hide();
                    $("body").removeClass("blur-effect"); // Hide loader on error
                    alert("Something went wrong. Please try again.");
                }
            });
        }
    });
});

function validateInputs() {
    let isValid = true;
    
    const username = $('#username').val();
    const email = $('#email').val();
    const password = $('#password').val();
    const confirmPassword = $('#Cpassword').val();
    
    $('.error').remove(); // Clear previous error messages

    if (username === '') {
        isValid = false;
        $('#username').after('<div class="error">Username is required</div>');
    }

    if (email === '') {
        isValid = false;
        $('#email').after('<div class="error">Email is required</div>');
    } else if (!validateEmail(email)) {
        isValid = false;
        $('#email').after('<div class="error">Invalid email format</div>');
    }

    if (password === '') {
        isValid = false;
        $('#password').after('<div class="error">Password is required</div>');
    } else if (password.length < 8) {
        isValid = false;
        $('#password').after('<div class="error">Password must be at least 8 characters</div>');
    }

    if (confirmPassword === '') {
        isValid = false;
        $('#Cpassword').after('<div class="error">Confirm Password is required</div>');
    } else if (confirmPassword !== password) {
        isValid = false;
        $('#Cpassword').after('<div class="error">Passwords do not match</div>');
    }

    return isValid;
}

function validateEmail(email) {
    const emailPattern = /^[^\s@]+@gmail+\.[^\s@]+$/;
    return emailPattern.test(email);
}