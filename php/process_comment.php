<?php
// process_comment.php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    include 'connectdb.php'; //db connection

    $conn = new mysqli($servername, $username, $password_db, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize and get data from the form
    $commentContent = filter_var($_POST['commentContent'], FILTER_SANITIZE_STRING);
    $postId = $_POST['postId'];
    $userId = $_SESSION['user_id'];  // Make sure the user is logged in

    // Insert the comment into the database
    $stmt = $conn->prepare("INSERT INTO comments (PostID, UserID, Content, DateCommented) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param('iis', $postId, $userId, $commentContent);

    if ($stmt->execute()) {
        // Redirect back to the post.php page after successfully adding the comment
        header("Location: ../html/post.php?post_id={$postId}");
        exit();
    } else {
        echo "Error adding comment: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect to the home page or login page if accessed directly without a valid session or form submission
    header("Location: ../index.php");
    exit();
}
?>
