<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="js/search.js"></script>
</head>

<body>
    <?php
    // Check if the user is logged in
    session_start();
    if (isset($_SESSION['user_id'])) {
        // If logged in, get the username
        $username = $_SESSION['username'];
    ?>
    <div class="user-info">
        <p>Welcome, <?php echo $username; ?>!</p>
        <button id="logoutButton" onclick="window.location.href='php/logout.php'">Logout</button>
    </div>
    <?php } ?>
    <nav class="main-nav">
        <h1 class="brand">Welcome to My Forum</h1>
    </nav>
    <div class="p-navSticky">
        <nav class="p-nav">
            <div class="p-nav-inner">
                <a class="nav-button" style="border: 0; " href="index.php">Home</a>
                <a class="nav-button" href="html/profile.php">My Profile</a>
                <a class="nav-button" href="html/postCreation.html">Create Post</a>
                <div class="p-nav-opposite">
                    <?php
                    // Display login and signup if the user is not logged in
                    if (!isset($_SESSION['user_id'])) {
                        ?>
                        <a class="nav-button" href="html/login.html">Login</a>
                        <a class="nav-button" href="html/signup.html">Sign Up</a>
                        <?php } ?>
                    </div>
                </div>
            </nav>
        </div>
        <div class="under-part">
            <div class="search-bar">
            <input type="text" class="input" id="searchInput" placeholder="search">
            <i class="fa fa-search"></i>
            </div>
            <ul class="tabPanes">
            <div class="block">
                <?php
                include ("php/connectdb.php"); //db connection

                $conn = new mysqli($servername, $username, $password_db, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT posts.PostID, posts.Title, posts.DatePosted, users.Username, posts.totalRating, posts.ratingCount, users.profilePicture
                        FROM posts 
                        JOIN users ON posts.UserID = users.ID
                        ORDER BY posts.DatePosted DESC";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<div class='block'>";
                    while ($row = $result->fetch_assoc()) {
                        // Make only the post title clickable
                        echo "<div class='post-content'>";
                        echo "<h2 class='post-title'><a href='/html/post.php?post_id={$row['PostID']}'>{$row['Title']}</a></h2>";
                        echo "<p class='post-rating'>Rating: {$row['totalRating']}</p>";
                        echo "<p class='post-raters'>{$row['ratingCount']} people rated</p>";
                        echo "<div class='post-user'>";
                        echo "<img class='post-avatar' src='/" . (htmlspecialchars($row['profilePicture']) . "'>");
                        echo "<p class='post-creator'>Created by {$row['Username']}</p>";
                        echo "<p class='post-date'>Created on {$row['DatePosted']}</p>";
                        echo "</div>";
                        echo "</div>";
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
    <script src="js/stickyNav.js"></script>
    
</body>

<footer>&copy; 2023 forum. All rights reserved.</footer>
</html>
