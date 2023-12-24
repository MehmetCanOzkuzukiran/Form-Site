<?php
session_start();

$response = array('email' => '');

if (isset($_SESSION['current_user_email'])) {
    $response['email'] = $_SESSION['current_user_email'];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
