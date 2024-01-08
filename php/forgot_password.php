<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userEmail = json_decode(file_get_contents("php://input"))->email;

    // Validate and process the email (check if it exists in your database, etc.)
    // For simplicity, let's assume the email is valid, and proceed to generate a new password

    $newPassword = bin2hex(random_bytes(8)); // Generate a random password
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Perform database update based on the user's email
    // For example: UPDATE users SET password = :hashedNewPassword WHERE email = :userEmail

    // Send the new password to the user's email (you'll need to implement this)
    $subject = "Password Reset";
    $message = "Your new password is: $newPassword";
    $headers = "From: your_email@example.com"; // Change this to your email address

    // You can use the mail() function to send the email
    mail($userEmail, $subject, $message, $headers);

    // Return a response
    echo json_encode(['message' => 'Password reset successful. Check your email for the new password.']);
} else {
    echo json_encode(['message' => 'Invalid request.']);
}
?>
