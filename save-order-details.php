<?php
session_start();

// Validate and sanitize input
$grandTotal = filter_input(INPUT_POST, 'grandTotal', FILTER_VALIDATE_FLOAT);
$deliveryMethod = filter_input(INPUT_POST, 'deliveryMethod', FILTER_SANITIZE_STRING);
$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
$phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
$items = json_decode($_POST['items'], true);

// Save details to session
$_SESSION['order_details'] = [
    'grandTotal' => $grandTotal,
    'deliveryMethod' => $deliveryMethod,
    'address' => $address,
    'phone' => $phone,
    'items' => $items
];

echo json_encode(['status' => 'success']);

?>