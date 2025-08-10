<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('./config/constant.php');
header('Content-Type: application/json');

//  send JSON response and exit
function sendResponse($success, $message, $details = null) {
    $response = [
        'success' => $success,
        'message' => $message
    ];

    if ($details) {
        $response['details'] = $details;
    }
    
    echo json_encode($response);
    exit;
}

// Validate POST data
if (
    !isset($_POST['item_id']) || 
    !isset($_POST['quantity']) || 
    empty($_POST['item_id']) || 
    intval($_POST['quantity']) <= 0
) {
    sendResponse(false, 'Invalid item or quantity', [
        'post_data' => $_POST
    ]);
}

$item_id = $_POST['item_id'];
$quantity = intval($_POST['quantity']);

// Handle color variations 
$color_id = isset($_POST['color_id']) && $_POST['color_id'] !== 'No Color' 
    ? $_POST['color_id'] 
    : '';  
$cus_id = isset($_SESSION['Customer_ID']) ? $_SESSION['Customer_ID'] : '';

try {
    // Check item details
    $item_check_sql = "SELECT item_id, name, colors, quantity FROM item WHERE item_id = ?";
    $stmt = $conn->prepare($item_check_sql);
    $stmt->bind_param("s", $item_id);
    $stmt->execute();
    $item_result = $stmt->get_result();

    if ($item_result->num_rows == 0) {
        sendResponse(false, 'Item not found', ['item_id' => $item_id]);
    }

    $item = $item_result->fetch_assoc();

    // Stock checking with color
    if ($item['colors'] === 'Yes') {
        if (empty($color_id)) {
            sendResponse(false, 'Color selection is required for this item');
        }

        // Validate color variation and check stock
        $color_check_sql = "SELECT clr_id, quantity FROM colors WHERE clr_id = ? AND item_id = ? AND status = 'Active'";
        $stmt = $conn->prepare($color_check_sql);
        $stmt->bind_param("ss", $color_id, $item_id);
        $stmt->execute();
        $color_result = $stmt->get_result();

        if ($color_result->num_rows == 0) {
            sendResponse(false, 'Invalid or inactive color selected', ['color_id' => $color_id]);
        }

        $color_var = $color_result->fetch_assoc();

        if ($color_var['quantity'] < $quantity) {
            sendResponse(false, 'Insufficient stock for selected color', [
                'requested_quantity' => $quantity,
                'available_quantity' => $color_var['quantity']
            ]);
        }

        // Check if item with this color is already in cart
        $checkCartSql = "SELECT id, Qty FROM cart WHERE item_id = ? AND cus_id = ? AND color_id = ?";
        $stmt = $conn->prepare($checkCartSql);
        $stmt->bind_param("sss", $item_id, $cus_id, $color_id);
    } else {
        //without color variations
        if ($item['quantity'] < $quantity) {
            sendResponse(false, 'Insufficient stock', [
                'requested_quantity' => $quantity,
                'available_quantity' => $item['quantity']
            ]);
        }

        // Check if item is already in cart
        $checkCartSql = "SELECT id, Qty FROM cart WHERE item_id = ? AND cus_id = ? AND color_id = ''";
        $stmt = $conn->prepare($checkCartSql);
        $stmt->bind_param("ss", $item_id, $cus_id);
    }
    $stmt->execute();
    $cart_result = $stmt->get_result();

    if ($cart_result->num_rows > 0) {
        // Item exists in cart, update quantity
        $cart_row = $cart_result->fetch_assoc();
        $new_quantity = $cart_row['Qty'] + $quantity;

        $update_sql = "UPDATE cart SET Qty = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ii", $new_quantity, $cart_row['id']);

        if ($stmt->execute()) {
            sendResponse(true, 'Cart updated successfully', ['new_quantity' => $new_quantity]);
        } else {
            sendResponse(false, 'Failed to update cart', ['error' => $stmt->error]);
        }
    } else {
        // Insert new cart item
        $insert_sql = "INSERT INTO cart (item_id, cus_id, color_id, Qty) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sssi", $item_id, $cus_id, $color_id, $quantity);

        if ($stmt->execute()) {
            sendResponse(true, 'Item added to cart successfully');
        } else {
            sendResponse(false, 'Failed to add item to cart', ['error' => $stmt->error]);
        }
    }

} catch (Exception $e) {
    sendResponse(false, 'An unexpected error occurred', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>