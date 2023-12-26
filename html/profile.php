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

// Fetch and display the current email
$currentEmail = '';

if (isset($_SESSION['current_user_email'])) {
    $currentEmail = $_SESSION['current_user_email'];
} else {
    // Fetch user information from the database based on the user ID
    $getUserInfoQuery = "SELECT Email FROM Users WHERE ID = $userId";
    $result = $conn->query($getUserInfoQuery);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentEmail = $row['Email'];
        $_SESSION['current_user_email'] = $currentEmail; // Cache the email in the session
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Rest of your existing code for updating email and password
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/profile.css">
    <title>Document</title>
    <script src="../js/script.js"></script>
</head>
<body>
        <section class="innerpage">
            <h1 class="inner-header">My Profile</h1>
            <form  action="../php/profilechg.php" method="POST" class="info" novalidate>
                <div class="img-box">
                    <img class="image" id="previewImage" src="/backgrounds/UserStockPhoto1.jpg" alt="UserImage">
                    <button style="display:block;width:120px; height:30px; "   onclick="previewImage()">Change My Photo</button>
                    <input type='file' id="getFile" style="display:none">
                </div>
                <div class="input-group">
                    <p style="color: #fff; margin: 10px;">Current Email: <?php echo htmlspecialchars($currentEmail); ?></p>

                    <div class="inputbox">
                        <ion-icon name="mail-outline">&#x2709;</ion-icon>
                        <input type="email" name="email" required>
                        <label for="">Change My Email</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline" onclick="togglePasswordVisibility('newPassword', 'confirmPassword')">
                            <div class="icon-lock" style="float: left">
                                <div class="lock-top-1" style="background-color: #E5E9EA"></div>
                                <div class="lock-top-2"></div>
                                <div class="lock-body" style="background-color: #E5E9EA"></div>
                                <div class="lock-hole"></div>
                            </div>
                        </ion-icon>
                        <input type="password" id="newPassword" name="password" required>
                        <label for="">Change the Password</label>
                    </div>
                    
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline" onclick="togglePasswordVisibility('confirmPassword', 'newPassword')">
                            <div class="icon-lock" style="float: left">
                                <div class="lock-top-1" style="background-color: #E5E9EA"></div>
                                <div class="lock-top-2"></div>
                                <div class="lock-body" style="background-color: #E5E9EA"></div>
                                <div class="lock-hole"></div>
                            </div>
                        </ion-icon>
                        <input type="password" id="confirmPassword" required oninput="checkPasswordMatch()">
                          <label for="">Confirm the Password</label>
                         <p id="passwordMismatch" style="color: red; font-size: 0.8rem; display: none;">Passwords do not match</p>
                    </div>
                    <button class="login-button">Save the changes</button>
                </div>
                <div  class="register">
                    <p><a href="../index.php">Continue Without Changes</a></p>
                  </div>
            </form>
            <a href="../php/logout.php" class="logout-button">Logout</a>
        </section>
</body>
</html>
