<?php
session_start();

header('Content-Type: application/json');

// Check if the session is set and has a valid Customer_ID
$response = [
    'loggedIn' => isset($_SESSION['Customer_ID']) && !empty($_SESSION['Customer_ID'])
];

echo json_encode($response);
exit;