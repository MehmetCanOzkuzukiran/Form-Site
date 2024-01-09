<?php
$servername = "localhost";
$username = "berke";
$password = "987Berker-456";
$dbname = "forumdb";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_username = $_POST['username'];
        $user_email = $_POST['email'];
        $user_password = $_POST['password'];

        // Check for existing email or username
        $stmt = $conn->prepare("SELECT email, username FROM users WHERE email = :email OR username = :username");
        $stmt->bindParam(':email', $user_email);
        $stmt->bindParam(':username', $user_username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "Username or Email already registered.";
        } else {
            $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $user_username);
            $stmt->bindParam(':email', $user_email);
            $stmt->bindParam(':password', $hashed_password);
            
            $stmt->execute();
            echo "Registration successful!";
            header("Location: ../html/login.html");
        }
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
$conn = null;
?>
