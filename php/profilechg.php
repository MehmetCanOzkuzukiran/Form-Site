<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../html/login.html');
    exit();
}

$userId = $_SESSION['user_id'];
$servername = "localhost";
$username = "root";
$password_db = "";
$dbname = "webpagetest";

$conn = new mysqli($servername, $username, $password_db, $dbname);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch current email from the session
$currentEmail = isset($_SESSION['current_user_email']) ? $_SESSION['current_user_email'] : 'Not logged in';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $newEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the new email is not empty before proceeding
    if (!empty($newEmail)) {
        // Check if the email already exists for another user
        $stmt = $conn->prepare("SELECT ID FROM users WHERE email = ? AND ID <> ?");
        $stmt->bind_param('si', $newEmail, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Email already in use by another account.";
        } else {
            // Update user's email and password
            $stmt = $conn->prepare("UPDATE users SET email = ?, password = ? WHERE ID = ?");
            $stmt->bind_param('ssi', $newEmail, $newPassword, $userId);

            if ($stmt->execute()) {
                echo "Profile updated successfully!";
                $stmt->close(); // Close the statement here
                $_SESSION['current_user_email'] = $newEmail; // Update the session with the new email
                header("Location: ../html/profile.php"); // Redirect to the profile page
                exit();
            } else {
                echo "Error updating record: " . $conn->error;
            }
        }
    } else {
        echo "New email cannot be empty.";
    }
}

// Close the statement outside of the conditional block
if (isset($stmt)) {
    $stmt->close();
}

$conn->close();
?>
