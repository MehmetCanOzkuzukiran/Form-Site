<?php
session_start();

$servername = "localhost";
$username = "berke";
$password_db = "987Berker-456";
$dbname = "forumdb";

$conn = new mysqli($servername, $username, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming you have the post ID available
$postId = $_POST['post_id'];

// Fetch the post details using a prepared statement
$sqlPost = "SELECT posts.UserID FROM posts WHERE posts.PostID = ?";
$stmtPost = $conn->prepare($sqlPost);
$stmtPost->bind_param('i', $postId);
$stmtPost->execute();
$resultPost = $stmtPost->get_result();

if ($resultPost->num_rows > 0) {
    $rowPost = $resultPost->fetch_assoc();
    $userId = $rowPost['UserID'];

    // Check if the logged-in user has the permission to delete the post
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $userId) {
        // Delete the post
        $stmtDeletePost = $conn->prepare("DELETE FROM posts WHERE PostID = ?");
        $stmtDeletePost->bind_param('i', $postId);

        if ($stmtDeletePost->execute()) {
            echo "Post deleted successfully!";
            // Redirect back to the home page after deletion
            header("Location: ../index.php");
            exit();
        } else {
            echo "Error deleting post: " . $conn->error;
        }
    } else {
        echo "You don't have permission to delete this post.";
    }
} else {
    echo "Post not found.";
}

$conn->close();
?>
