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
$sqluser = "SELECT profilePicture, email FROM users WHERE ID = ?";
$stmtuser = $conn->prepare($sqluser);

if (!$stmtuser) {
    die("Error preparing statement: " . $conn->error);
}

$stmtuser->bind_param('i', $userId);
$stmtuser->execute();

$resultuser = $stmtuser->get_result();
$rowuser = $resultuser->fetch_assoc();
$profilePicturePath = $rowuser['profilePicture'];
$resultAssoc = $rowuser['email'];

if ($resultAssoc !== null) {
    $currentEmail = $resultAssoc;
} else {
    $currentEmail = 'Not found';  // Provide a default value or handle the case where no results are returned
}
// Fetch current email and profile picture path from the session
/* $currentEmail = isset($_SESSION['current_user_email']) ? $_SESSION['current_user_email'] : 'Not logged in'; */
/* $profilePicturePath = isset($_SESSION['profile_picture_path']) ? $_SESSION['profile_picture_path'] : 'backgrounds/profile/UserStockPhoto1.jpg'; */
$leadingSlash = "/";
$fullProfilePicturePath = $leadingSlash . $profilePicturePath;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $newEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the new email is not empty before proceeding
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
            } else {
                echo "Error updating email: " . $conn->error;
            }
        }
    }

    // Check if the new password is not empty before proceeding
    if (!empty($_POST['password'])) {
        // Update user's password
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE ID = ?");
        $stmt->bind_param('si', $newPassword, $userId);

        if ($stmt->execute()) {
            echo "Password updated successfully!";
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
            // Update the session with the new profile picture path
            $_SESSION['profile_picture_path'] = "backgrounds/profile/" . basename($_FILES["profilePicture"]["name"]);
            echo "Profile picture uploaded successfully!";
        } else {
            echo "Error uploading profile picture.";
        }
    }

    header("Location: ../html/profile.php"); // Redirect to the profile page
    exit();
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
    <script src="../js/validateEmail.js"></script>
</head>
<body>
        <section class="innerpage">
            <h1 class="inner-header">My Profile</h1>
            <form action="../php/profilechg.php" method="POST" class="info" onsubmit="return validateForm()" novalidate enctype="multipart/form-data" >
            <div class="img-box">
            <img class="image" id="previewImage" src="<?php echo htmlspecialchars($fullProfilePicturePath) . '?version=' . uniqid(); ?>" alt="UserImage">
                <input type="file" id="getFile" name="profilePicture" onchange="previewImage(this)">
            </div>
                <div class="input-group">
                    <p style="color: #fff; margin: 10px;">Current Email: <?php echo htmlspecialchars($currentEmail); ?></p>

                    <div class="inputbox">
                        <ion-icon name="mail-outline">&#x2709;</ion-icon>
                        <input type="email" id="emailField" name="email" required>
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
