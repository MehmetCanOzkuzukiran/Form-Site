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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $postTitle = filter_var($_POST['postTitle'], FILTER_SANITIZE_STRING);
    $postContent = filter_var($_POST['postContent'], FILTER_SANITIZE_STRING);
    $datePosted = date('Y-m-d H:i:s');

    // Insert new post into the database
    $stmt = $conn->prepare("INSERT INTO posts (UserID, Title, Content, DatePosted) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('isss', $userId, $postTitle, $postContent, $datePosted);

    if ($stmt->execute()) {
        echo "Post created successfully!";
        header("Location: ../index.php"); // Redirect to the profile page
        exit();
    } else {
        echo "Error creating post: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
