<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
    <link rel="stylesheet" href="css/home.css">
</head>

<body>
    <nav class="main-nav">
        <h1 class="brand">Welcome to My Forum</h1>
    </nav>
    <div class="p-navSticky">
        <nav class="p-nav">
            <div class="p-nav-inner">
                <a class="nav-button" style="border: 0; " href="index.html">Home</a>
                <a class="nav-button" href="html/profile.php">My Profile</a>
                <a class="nav-button" href="html/postCreation.html">Create Post</a>
                <div class="p-nav-opposite">
                    <a class="nav-button" href="html/login.html">Login</a>
                    <a class="nav-button" href="html/signup.html">Sign Up</a>
                </div>
                <div class="p-nav-opposite">
                    <button id="logoutButton" style="display: none;" href="php/logout.php">Logout</button>
                </div>
            </div>
        </nav>
    </div>
    <div class="under-part">
        <ul class="tabPanes">
            <div class="block">
                <?php
                $servername = "localhost";
                $username = "root";
                $password_db = "";
                $dbname = "webpagetest";

                $conn = new mysqli($servername, $username, $password_db, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT posts.PostID, posts.Title, posts.DatePosted, users.Username 
                        FROM posts 
                        JOIN users ON posts.UserID = users.ID
                        ORDER BY posts.DatePosted DESC";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<div class='block'>";
                    while ($row = $result->fetch_assoc()) {
                        // Make only the post title clickable
                        echo "<p class='post-content'><a href='/html/post.php?post_id={$row['PostID']}'>{$row['Title']}</a> created by {$row['Username']} on {$row['DatePosted']}</p>";
                    }
                    echo "</div>";
                } else {
                    echo "No posts available.";
                }

                $conn->close();
                ?>
                </div>
        </ul>
    </div>
</body>

<footer>&copy; 2023 forum. All rights reserved.</footer>
</html>
