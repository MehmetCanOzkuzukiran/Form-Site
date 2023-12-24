<?php
// Sample session start, replace it with your actual session logic
session_start();

// Check if the user is logged in (you should have a proper authentication mechanism)
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Fetch user information from the database based on the user ID
    // Replace the following with your actual database connection and query
    $servername = "your_server";
    $username = "your_username";
    $password_db = "your_password";
    $dbname = "your_database";

    $conn = new mysqli($servername, $username, $password_db, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $getUserInfoQuery = "SELECT Email FROM Users WHERE UserID = $userId";
    $result = $conn->query($getUserInfoQuery);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['current_user_email'] = $row['Email'];
    }

    $conn->close();
}
?>
