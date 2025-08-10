<script src="https://www.payhere.lk/lib/payhere.js"></script>

<?php
header('Content-Type: application/json');

try {
    // Ensure no previous output
    ob_clean();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405); 
        echo json_encode([
            'error' => true,
            'message' => 'Only POST method is allowed'
        ]);
        exit();
    }

    // Validate input data
    $requiredFields = ['grandTotal', 'deliveryMethod', 'address', 'phone', 'items'];
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field])) {
            http_response_code(400); 
            echo json_encode([
                'error' => true,
                'message' => "Missing required field: $field"
            ]);
            exit();
        }
    }

    // Sanitize and validate input
    $grandTotal = filter_input(INPUT_POST, 'grandTotal', FILTER_VALIDATE_FLOAT);
    $deliveryMethod = filter_input(INPUT_POST, 'deliveryMethod', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $items = $_POST['items']; // This will be an array of items

    // Validate grand total
    if ($grandTotal === false || $grandTotal <= 0) {
        http_response_code(400);
        echo json_encode([
            'error' => true,
            'message' => 'Invalid total amount'
        ]);
        exit();
    }

    $order_id = uniqid('ORDER_');

    // Merchant details 
    $merchant_id = "1228976"; 
    $merchant_secret = "MTkwOTcwOTM3NTIzNTYwMTYwNzQyMzQ2OTQ4MDY1MjQ1ODAwODA3NA=="; // Your actual merchant secret
    $currency = "LKR";

    // Generate hash for PayHere
    $hash = strtoupper(
        md5(
            $merchant_id . 
            $order_id . 
            number_format($grandTotal, 2, '.', '') . 
            $currency .  
            strtoupper(md5($merchant_secret)) 
        ) 
    );

    // Prepare response
    $response = [
        'error' => false,
        'merchant_id' => $merchant_id,
        'order_id' => $order_id,
        'amount' => number_format($grandTotal, 2, '.', ''),
        'currency' => $currency,
        'hash' => $hash,
        'return_url' => 'http://localhost/opoms/',
        'cancel_url' => 'http://localhost/opoms/',
        'notify_url' => 'http://localhost/opoms/',
        
        // Hardcoded customer details 
        'first_name' => 'Saman',
        'last_name' => 'Perera',
        'email' => 'samanp@gmail.com',
        'phone' => $phone,
        'address' => $address,
        'city' => 'Colombo',
        'country' => 'Sri Lanka',
        
        // Optional: Format items for PayHere
        'items' => array_map(function($item) {
            error_log("Processing Item: " . json_encode($item));
            return [
                'itemId' => $item['itemId'] ?? 'N/A',
                'colorId' => $item['colorId'] ?? 'N/A',
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ];
        }, $items)
    ];

    error_log("Received Items: " . print_r($items, true));

    // Output JSON response
    echo json_encode($response);
    exit();

} catch (Exception $e) {
    // Catch any unexpected errors
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => 'Internal Server Error: ' . $e->getMessage()
    ]);
    exit();
}
?>