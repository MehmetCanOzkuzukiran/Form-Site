<?php
session_start();

$servername = "localhost";
$username = "root";
$password_db = "";
$dbname = "webpagetest";

$conn = new mysqli($servername, $username, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming you have the comment ID available
$commentId = $_POST['comment_id'];

// Fetch the comment details using a prepared statement
$sqlComment = "SELECT comments.PostID, comments.UserID 
               FROM comments 
               WHERE comments.CommentID = ?";
$stmtComment = $conn->prepare($sqlComment);
$stmtComment->bind_param('i', $commentId);
$stmtComment->execute();
$resultComment = $stmtComment->get_result();

if ($resultComment->num_rows > 0) {
    $rowComment = $resultComment->fetch_assoc();
    $postId = $rowComment['PostID'];
    $userId = $rowComment['UserID'];

    // Check if the logged-in user has the permission to delete the comment
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $userId) {
        // Delete the comment
        $stmtDeleteComment = $conn->prepare("DELETE FROM comments WHERE CommentID = ?");
        $stmtDeleteComment->bind_param('i', $commentId);

        if ($stmtDeleteComment->execute()) {
            echo "Comment deleted successfully!";
            // Redirect back to the post page after deletion
            header("Location: ../html/post.php?post_id=$postId");
            exit();
        } else {
            echo "Error deleting comment: " . $conn->error;
        }
    } else {
        echo "You don't have permission to delete this comment.";
    }
} else {
    echo "Comment not found.";
}

$conn->close();
?>
