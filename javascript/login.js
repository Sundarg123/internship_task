$(document).ready(function () {
    $(".button").click(function (e) {
        e.preventDefault(); // Prevent form submission
        
        let email = $("input[type='email']").val();
        let password = $("input[type='password']").val();
        let isValid = true;

        $(".error").remove(); // Clear previous errors

        if (email === '') {
            isValid = false;
            $("input[type='text']").after('<div class="error">Email is required</div>');
        } else if (!validateEmail(email)) {
            isValid = false;
            $("input[type='text']").after('<div class="error">Enter a valid Gmail address</div>');
        }

        if (password === '') {
            isValid = false;
            $("input[type='password']").after('<div class="error">Password is required</div>');
        } else if (password.length < 8) {
            isValid = false;
            $("input[type='password']").after('<div class="error">Password must be at least 8 characters</div>');
        }

        if (isValid) {
            $.ajax({
                url: "http://localhost/intern/Php/login.php", // Your login PHP script
                type: "POST",
                data: {
                    "email": email,
                    "password": password
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        localStorage.setItem("token",response.token); // Store session info
                        window.location.href = "profile.html"; // Redirect to profile page
                        // setTimeout(function () {
                        //     window.location.href = "profile.html";
                        // }, 1000);
                    } else {
                        alert(response.message)
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error, xhr.responseText);
                    alert("Something went wrong. Please try again.");
                }
            });
        }
    });

    function validateEmail(email) {
        return /^[^\s@]+@gmail+\.[^\s@]+$/.test(email); // Matches Gmail emails
    }
});
