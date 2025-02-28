$(document).ready(function () {
    let token = localStorage.getItem("token")
    console.log("token", token)
    if(!token){
        window.location.href = "login.html"
    }

    
$('#profileForm').on('submit', function (e) {
    e.preventDefault();

    if (!validateProfileForm()) {
        return; // Stop if validation fails
    }

    let profileData = {
        email: localStorage.getItem("hash"),  // Get stored email
        firstname: $('#firstname').val(),
        lastname: $('#lastname').val(),
        age: $('#age').val(),
        dob: $('#dob').val(),
        address: $('#address').val(),
        contact: $('#contact').val()
    };

    $.ajax({
        url: "http://localhost/intern/Php/profile.php",
        type: "POST",
        data: {
            data: profileData,
            action: "update",
            token: token
        },
        dataType: "json",
        success: function (response) {
            if (response.status === "success") {
                alert("Profile Updated Successfully!");
            } else {
                alert("Error: " + response.message);
            }
        },
        // error: function () {
        //     alert("Something went wrong. Please try again.");
        error: function (xhr, status, error) {
            console.error("AJAX Error:", error);
            console.log("Response Text:", xhr.responseText); 
            alert("Something went wrong. Please try again.");
        }
    });
});




});


function validateProfileForm() {
    let isValid = true;
    $('.error').remove(); // Remove previous error messages

    const firstname = $('#firstname').val();
    const lastname = $('#lastname').val();
    const age = $('#age').val();
    const dob = $('#dob').val();
    const address = $('#address').val();
    const contact = $('#contact').val();
    const phonePattern = /^[6-9]\d{9}$/; // Validates Indian 10-digit mobile numbers

    if (firstname === '') {
        isValid = false;
        $('#firstname').after('<div class="error">First name is required</div>');
    }

    if (lastname === '') {
        isValid = false;
        $('#lastname').after('<div class="error">Last name is required</div>');
    }

    if (age === '' || isNaN(age) || age < 1 || age > 120) {
        isValid = false;
        $('#age').after('<div class="error">Enter a valid age (1-120)</div>');
    }

    if (dob === '') {
        isValid = false;
        $('#dob').after('<div class="error">Date of birth is required</div>');
    }

    if (address === '') {
        isValid = false;
        $('#address').after('<div class="error">Address is required</div>');
    }

    if (contact === '' || !phonePattern.test(contact)) {
        isValid = false;
        $('#contact').after('<div class="error">Enter a valid 10-digit mobile number</div>');
    }

    return isValid;
}