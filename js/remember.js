// Define the GetCookie function to retrieve cookie values
function GetCookie(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length === 2) return parts.pop().split(";").shift();
}

// Fill the email and password fields with cookie values when the page is loaded
document.addEventListener("DOMContentLoaded", function() {
    var emailCookie = GetCookie('emailid');
    var passwordCookie = GetCookie('pswd');

    // Check if cookies exist before filling the fields
    if (emailCookie && passwordCookie) {
        // Use decodeURIComponent to decode the email cookie value
        var decodedEmail = decodeURIComponent(emailCookie.replace(/\+/g, ' '));

        // Replace %40 with @ in the email
        decodedEmail = decodedEmail.replace(/%40/g, '@');

        document.getElementById('email').value = decodedEmail;
        document.getElementById('passwordField').value = passwordCookie;
    }
});
