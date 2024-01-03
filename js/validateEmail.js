function validateForm() {
    var emailField = document.getElementById('emailField');
    var emailValue = emailField.value.trim();

    // Check if the email is empty or not a valid email address
    if (emailValue === ""){
        return true;
    } else if (!isValidEmail(emailValue)) {
        alert("Please provide a valid email address.");
        return false; // Prevent form submission
    }

    // Optionally, you can perform additional checks here

    return true; // Allow form submission
}

// Function to check if the email address is valid
function isValidEmail(email) {
    // Regular expression for basic email validation
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (!emailRegex.test(email)) {
        // Attempt to correct common email typos
        var correctedEmail = tryCorrectEmail(email);
        
        // If correction is successful, update the email field
        if (correctedEmail) {
            alert("Email corrected to: " + correctedEmail);
        } else {
            return false; // Correction failed, return false
        }
    }

    return true; // Email is valid or corrected successfully
}

// Function to attempt to correct common email typos
function tryCorrectEmail(email) {
    // Example: Append a default domain if none is provided
    if (!/@/.test(email)) {
        return email + '@example.com';
    }

    // Add more correction logic as needed
    // ...

    return null; // Return null if no correction is possible
}