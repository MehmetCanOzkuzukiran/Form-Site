<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html'); // Redirect to login page if not logged in
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postTitle = $_POST["postTitle"];
    $postContent = $_POST["postContent"];

    // Perform any processing or database insertion here

    // Redirect or respond accordingly
    header("Location: ../index.html");
    exit();
}
?>
