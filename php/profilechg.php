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
    // Check if the new email is not empty before proceeding
    $newEmail = trim($_POST['email']);
    if (!empty($newEmail)) {
        // Check if the email already exists for another user
        $stmt = $conn->prepare("SELECT ID FROM users WHERE email = ? AND ID <> ?");
        $stmt->bind_param('si', $newEmail, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Email already in use by another account.";
        } else {
            // Update user's email
            $stmt = $conn->prepare("UPDATE users SET email = ? WHERE ID = ?");
            $stmt->bind_param('si', $newEmail, $userId);

            if ($stmt->execute()) {
                echo "Email updated successfully!";
                $_SESSION['current_user_email'] = $newEmail; // Update the session with the new email
                header("Location: ../html/profile.php");
            } else {
                echo "Error updating email: " . $conn->error;
            }
        }
    }

    // Check if the new password is not empty before proceeding
    if (!empty($_POST['password'])) {
        // Update user's password
        $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE ID = ?");
        $stmt->bind_param('si', $newPassword, $userId);

        if ($stmt->execute()) {
            echo "Password updated successfully!";
            header("Location: ../html/profile.php");
        } else {
            echo "Error updating password: " . $conn->error;
        }
    }

    // Handle file upload (profile picture)
    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "../backgrounds/profile/";
        $targetFile = $targetDir . basename($_FILES["profilePicture"]["name"]);

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $targetFile)) {
            // Update your database with the new file path if needed
            // Example: $filePath = "backgrounds/profile/" . basename($_FILES["profilePicture"]["name"]);
            echo "Profile picture uploaded successfully!";
            // After move_uploaded_file
            $filePath = "backgrounds/profile/" . basename($_FILES["profilePicture"]["name"]);
            $_SESSION['profile_picture_path'] = $filePath;
            // Add this SQL query to update the database
            $updateFilePathQuery = "UPDATE users SET profilePicture = '$filePath' WHERE ID = $userId";
            $conn->query($updateFilePathQuery);
            header("Location: ../html/profile.php");
        } else {
            echo "Error uploading profile picture.";
        }
    }

    // Redirect to the profile page only if there is no output (no errors)
    if (ob_get_length() === 0) {
        header("Location: ../html/profile.php");
        exit();
    }
    // Now, after the header is set, you can echo the messages.
    if (isset($successMessage)) {
        echo $successMessage;
    } elseif (isset($errorMessage)) {
        echo $errorMessage;
    }
}

$conn->close();
?>