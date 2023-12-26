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

// Assuming you have the post details available
$postId = $_GET['post_id'];

// Fetch post details
$sqlPost = "SELECT posts.Title, posts.Content, posts.DatePosted, users.Username 
            FROM posts 
            JOIN users ON posts.UserID = users.ID 
            WHERE posts.PostID = $postId";

$resultPost = $conn->query($sqlPost);

if ($resultPost->num_rows > 0) {
    $rowPost = $resultPost->fetch_assoc();
    $postTitle = $rowPost['Title'];
    $postContent = $rowPost['Content'];
    $postDate = $rowPost['DatePosted'];
    $postUsername = $rowPost['Username'];
} else {
    echo "Post not found.";
    exit();
}

// Fetch comments for the post
$sqlComments = "SELECT comments.CommentID, comments.Content, comments.DateCommented, users.Username, comments.UserID 
                FROM comments 
                JOIN users ON comments.UserID = users.ID 
                WHERE comments.PostID = $postId";

$resultComments = $conn->query($sqlComments);

// Collect comments in an array
$comments = [];
if ($resultComments->num_rows > 0) {
    while ($rowComment = $resultComments->fetch_assoc()) {
        $comments[] = $rowComment;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Details</title>
    <link rel="stylesheet" href="../css/post.css">
</head>

<body>
    <!-- Your existing navigation code -->

    <div class="post-container">
        <div class="post">
            <h2 class="post-title"><?php echo $postTitle; ?></h2>
            <p class="post-meta">Posted by <?php echo $postUsername; ?> on <?php echo $postDate; ?></p>
            <p class="post-content"><?php echo $postContent; ?></p>
        </div>

        <div class="comments">
            <h3>Comments:</h3>
            <?php
            foreach ($comments as $comment) {
                echo "<div class='comment'>";
                echo "<p class='comment-username'>" . (isset($comment['Username']) ? $comment['Username'] : 'Anonymous') . "</p>";
                echo "<p class='comment-date'>" . (isset($comment['DateCommented']) ? 'commented on: ' . $comment['DateCommented'] : '') . "</p>";
                echo "<p class='comment-content'>" . (isset($comment['Content']) ? $comment['Content'] : '') . "</p>";

                // Add delete button if the comment belongs to the logged-in user
                if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['UserID']) {
                    echo "<form action='delete_comment.php' method='POST'>";
                    echo "<input type='hidden' name='comment_id' value='{$comment['CommentID']}'>";
                    echo "<button type='submit' class='delete-button'>Delete</button>";
                    echo "</form>";
                }

                echo "</div>";
            }
            ?>

            <h3>Add a Comment:</h3>
            <form action="../php/process_comment.php" method="POST">
                <textarea name="commentContent" placeholder="Type your comment here"></textarea>
                <input type="hidden" name="postId" value="<?php echo $postId; ?>">
                <button type="submit">Post Comment</button>
            </form>
            <div class="home">
                <p><a class="home-button" href="../index.php">Return to home</a></p>
            </div>
        </div>
    </div>
</body>

<footer>&copy; 2023 forum. All rights reserved.</footer>
</html>
