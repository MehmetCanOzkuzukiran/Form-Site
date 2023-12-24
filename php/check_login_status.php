<?php
session_start();

$response = array('isLoggedIn' => false);

if (isset($_SESSION['user_id'])) {
    $response['isLoggedIn'] = true;
}

header('Content-Type: application/json');
echo json_encode($response);
?>
