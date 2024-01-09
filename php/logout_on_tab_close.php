<?php
session_start();
session_destroy();
// You may want to perform additional cleanup or logging here
echo json_encode(['success' => true]);
?>
