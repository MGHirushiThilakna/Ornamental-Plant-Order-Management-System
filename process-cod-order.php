<?php
session_start();

include('./config/constant.php');

error_reporting(E_ALL);
ini_set('display_errors', 0); 

function logError($message)
{
    $logFile = __DIR__ . '/logs/error.log';
    $timestamp = date('Y-m-d H:i:s');
    $formattedMessage = "[$timestamp] $message\n";

    // Create logs directory if it doesn't exist
    if (!is_dir(__DIR__ . '/logs')) {
        mkdir(__DIR__ . '/logs', 0777, true);
    }

    error_log($formattedMessage, 3, $logFile);
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    logError("POST request received");
    logError("POST data: " . print_r($_POST, true));

    // Get customer ID from session
    $customer_id = $_SESSION['Customer_ID'] ?? null;
    if (!$customer_id) {
        logError("No customer ID found in session");
        echo json_encode(['error' => true, 'message' => 'User not authenticated']);
        exit;
    }

    try {
        // Validate POST data
        if (!isset($_POST['items']) || !isset($_POST['address']) || !isset($_POST['phone'])) {
            throw new Exception('Missing required data');
        }

        // Decode and validate items
        $items = json_decode($_POST['items'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid items data format');
        }

        logError("Decoded items: " . print_r($items, true));

        if (empty($items)) {
            throw new Exception('No items in order');
        }

        // Start transaction
        mysqli_begin_transaction($conn);

        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);

        // Generate order ID
        $date = date('Ymd');
        $unique = uniqid();
        $order_id = "ODR-{$date}-{$unique}";

        logError("Generated order ID: " . $order_id);

        $total_amount = 0;

        foreach ($items as $item) {
            logError("Processing item: " . print_r($item, true));

            // Validate item data
            if (!isset($item['itemId']) || !isset($item['quantity']) || !isset($item['price'])) {
                throw new Exception('Invalid item data structure');
            }

            $item_id = mysqli_real_escape_string($conn, $item['itemId']);
            $color_id = isset($item['colorId']) && $item['colorId'] !== null ?
                mysqli_real_escape_string($conn, $item['colorId']) :
                'N/A';
            $qty = (int) $item['quantity'];
            $price = (float) $item['price'];
            $subtotal = $qty * $price;
            $total_amount += $subtotal;

            // Check if item has colors
            $check_colors_query = "SELECT colors FROM item WHERE item_id = ?";
            $check_stmt = mysqli_prepare($conn, $check_colors_query);
            mysqli_stmt_bind_param($check_stmt, 's', $item_id);
            mysqli_stmt_execute($check_stmt);
            $result = mysqli_stmt_get_result($check_stmt);
            $item_data = mysqli_fetch_assoc($result);

            // Verify stock availability and update quantity
            if ($item_data['colors'] === 'Yes' && $color_id !== 'N/A') {
                // Check and update color quantity
                $check_stock = "SELECT quantity FROM colors WHERE clr_id = ? AND item_id = ? FOR UPDATE";
                $stock_stmt = mysqli_prepare($conn, $check_stock);
                mysqli_stmt_bind_param($stock_stmt, 'ss', $color_id, $item_id);
                mysqli_stmt_execute($stock_stmt);
                $stock_result = mysqli_stmt_get_result($stock_stmt);
                $stock_data = mysqli_fetch_assoc($stock_result);

                if (!$stock_data || $stock_data['quantity'] < $qty) {
                    throw new Exception("Insufficient stock for item with color ID: $color_id");
                }

                // Update color quantity
                $update_color = "UPDATE colors SET quantity = quantity - ? WHERE clr_id = ? AND item_id = ?";
                $update_stmt = mysqli_prepare($conn, $update_color);
                mysqli_stmt_bind_param($update_stmt, 'iss', $qty, $color_id, $item_id);
                if (!mysqli_stmt_execute($update_stmt)) {
                    throw new Exception('Failed to update color quantity');
                }
            } else {
                // Check and update item quantity
                $check_stock = "SELECT quantity FROM item WHERE item_id = ? FOR UPDATE";
                $stock_stmt = mysqli_prepare($conn, $check_stock);
                mysqli_stmt_bind_param($stock_stmt, 's', $item_id);
                mysqli_stmt_execute($stock_stmt);
                $stock_result = mysqli_stmt_get_result($stock_stmt);
                $stock_data = mysqli_fetch_assoc($stock_result);

                if (!$stock_data || $stock_data['quantity'] < $qty) {
                    throw new Exception("Insufficient stock for item ID: $item_id");
                }

                // Update item quantity
                $update_item = "UPDATE item SET quantity = quantity - ? WHERE item_id = ?";
                $update_stmt = mysqli_prepare($conn, $update_item);
                mysqli_stmt_bind_param($update_stmt, 'is', $qty, $item_id);
                if (!mysqli_stmt_execute($update_stmt)) {
                    throw new Exception('Failed to update item quantity');
                }
            }

            // Insert order
            $query = "INSERT INTO order_tbl 
                      (odr_id, user, item_id, color_id, qty, price, subtotal, date_time, address, payment_type, mobile, status) 
                      VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, 'COD', ?, 'Pending')";

            $stmt = mysqli_prepare($conn, $query);
            if (!$stmt) {
                throw new Exception('Failed to prepare statement: ' . mysqli_error($conn));
            }

            mysqli_stmt_bind_param(
                $stmt,
                'ssssiddss',
                $order_id,
                $customer_id,
                $item_id,
                $color_id,
                $qty,
                $price,
                $subtotal,
                $address,
                $phone
            );

            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception('Failed to execute statement: ' . mysqli_stmt_error($stmt));
            }

            // Clear cart item
            $clear_cart = "DELETE FROM cart WHERE cus_id = ? AND item_id = ?";
            $cart_stmt = mysqli_prepare($conn, $clear_cart);
            if (!$cart_stmt) {
                throw new Exception('Failed to prepare cart clear statement');
            }
            mysqli_stmt_bind_param($cart_stmt, 'ss', $customer_id, $item_id);
            if (!mysqli_stmt_execute($cart_stmt)) {
                throw new Exception('Failed to clear cart item');
            }
        }

        // Commit transaction
        mysqli_commit($conn);

        logError("Order processed successfully: " . $order_id);

        echo json_encode([
            'success' => true,
            'order_id' => $order_id,
            'total_amount' => $total_amount,
            'message' => 'Order placed successfully'
        ]);

    } catch (Exception $e) {
        mysqli_rollback($conn);
        logError("Error processing order: " . $e->getMessage());
        logError("Stack trace: " . $e->getTraceAsString());

        echo json_encode([
            'error' => true,
            'message' => 'Order processing failed: ' . $e->getMessage()
        ]);
    }
} else {
    logError("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode([
        'error' => true,
        'message' => 'Invalid request method'
    ]);
}
?>