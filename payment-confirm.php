<?php
include('./config/constant.php');
session_start();
header('Content-Type: text/html');

if (!isset($_SESSION['Customer_ID'])) {
    echo "<script>window.location.href='customerLogin.php';</script>";
    exit();
}
$cus_id = $_SESSION['Customer_ID'];
function logError($message)
{
    error_log("[" . date('Y-m-d H:i:s') . "] " . $message);
}
// Error Page Rendering Function
function renderErrorPage($title, $message)
{
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title><?php echo htmlspecialchars($title); ?></title>
        <style>
            body {
                font-family: Arial, sans-serif;
                max-width: 600px;
                margin: 50px auto;
                text-align: center;
                background-color: #f4f4f4;
            }

            .error-container {
                background-color: #fff;
                border: 1px solid #ddd;
                border-radius: 5px;
                padding: 30px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }

            h1 {
                color: #d9534f;
            }

            .btn {
                display: inline-block;
                padding: 10px 20px;
                background-color: #5bc0de;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                margin-top: 20px;
            }
        </style>
    </head>

    <body>
        <div class="error-container">
            <h1><?php echo htmlspecialchars($title); ?></h1>
            <p><?php echo htmlspecialchars($message); ?></p>
            <a href="index.php" class="btn">Return to Home</a>
        </div>
    </body>

    </html>

<?php
    exit();
}

// Order Insertion Function
function insertOrderDetails($paymentDetails, $conn)
{
    // Generate unique order ID
    $odr_id = uniqid('ODR-' . date('Ymd') . '-');
    $currentDateTime = date('Y-m-d H:i:s');
    $payment_type = "online";

    // Begin transaction
    $conn->begin_transaction();

    try {
        $cus_id = $_SESSION['Customer_ID'] ?? null;
        if (!$cus_id) {
            throw new Exception("Customer ID not found in session");
        }

        $successfulInserts = 0;
        $insertErrors = [];

        foreach ($paymentDetails['items'] as $item) {
            $item_id = $item['itemId'] ?? null;
            $qty = max(0, intval($item['quantity'] ?? 0));
            $price = max(0, floatval($item['price'] ?? 0));

            $itemPortion = ($qty * $price) / $paymentDetails['subtotal'];
            $deliveryChargePortion = $paymentDetails['deliveryCharge'] * $itemPortion;
            $subtotal = ($qty * $price) + $deliveryChargePortion;

            if (!$item_id) {
                throw new Exception("Invalid item ID");
            }

            // Check if item has colors
            $check_colors_stmt = $conn->prepare("SELECT colors FROM item WHERE item_id = ?");
            $check_colors_stmt->bind_param('s', $item_id);
            $check_colors_stmt->execute();
            $colors_result = $check_colors_stmt->get_result();
            $item_data = $colors_result->fetch_assoc();
            $check_colors_stmt->close();

            // Set color_id based on whether the item has colors
            $color_id = null;
            if ($item_data['colors'] === 'Yes') {
                $color_id = $item['colorId'] ?? null;
                if (!$color_id) {
                    throw new Exception("Color ID required for item with colors");
                }

                // Check and update color quantity
                $check_stock = $conn->prepare("SELECT quantity FROM colors WHERE clr_id = ? AND item_id = ? FOR UPDATE");
                $check_stock->bind_param('ss', $color_id, $item_id);
                $check_stock->execute();
                $stock_result = $check_stock->get_result();
                $stock_data = $stock_result->fetch_assoc();
                $check_stock->close();

                if (!$stock_data || $stock_data['quantity'] < $qty) {
                    throw new Exception("Insufficient stock for item with color ID: $color_id");
                }

                // Update color quantity
                $update_stock = $conn->prepare("UPDATE colors SET quantity = quantity - ? WHERE clr_id = ? AND item_id = ?");
                $update_stock->bind_param('iss', $qty, $color_id, $item_id);
                if (!$update_stock->execute()) {
                    throw new Exception("Failed to update color inventory");
                }
                $update_stock->close();
            } else {
                // For items without colors,
                $color_id = '';

                // Check and update item quantity
                $check_stock = $conn->prepare("SELECT quantity FROM item WHERE item_id = ? FOR UPDATE");
                $check_stock->bind_param('s', $item_id);
                $check_stock->execute();
                $stock_result = $check_stock->get_result();
                $stock_data = $stock_result->fetch_assoc();
                $check_stock->close();

                if (!$stock_data || $stock_data['quantity'] < $qty) {
                    throw new Exception("Insufficient stock for item ID: $item_id");
                }

                // Update item quantity
                $update_stock = $conn->prepare("UPDATE item SET quantity = quantity - ? WHERE item_id = ?");
                $update_stock->bind_param('is', $qty, $item_id);
                if (!$update_stock->execute()) {
                    throw new Exception("Failed to update item inventory");
                }
                $update_stock->close();
            }

            // Insert order 
            $insert_order = $conn->prepare("INSERT INTO order_tbl (odr_id, item_id, color_id, qty, price, subtotal, date_time, address, payment_type, mobile, status, user) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $address = $paymentDetails['address'] ?? 'N/A';
            $mobile = $paymentDetails['phone'] ?? '0';
            $status = 'Pending';

            $insert_order->bind_param(
                "sssiddsssiss",
                $odr_id,
                $item_id,
                $color_id, // Now properly handled for both cases
                $qty,
                $price,
                $subtotal,
                $currentDateTime,
                $address,
                $payment_type,
                $mobile,
                $status,
                $cus_id
            );

            if (!$insert_order->execute()) {
                throw new Exception("Failed to insert order details");
            }
            $insert_order->close();

            $successfulInserts++;

            // Clear cart item
            $clear_cart = $conn->prepare("DELETE FROM cart WHERE cus_id = ? AND item_id = ?");
            $clear_cart->bind_param('ss', $cus_id, $item_id);
            $clear_cart->execute();
            $clear_cart->close();
        }

        $conn->commit();

        return [
            'orderId' => $odr_id,
            'successfulInserts' => $successfulInserts,
            'errors' => $insertErrors
        ];

    } catch (Exception $e) {
        $conn->rollback();
        logError("Order Insertion Error: " . $e->getMessage());
        throw $e;
    }
}

// Payment Confirmation Page Rendering Function
function renderConfirmation($paymentDetails, $insertResult)
{
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Payment Confirmation</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                max-width: 800px;
                margin: 50px auto;
                padding: 20px;
                background-color: #f4f4f4;
            }

            .confirmation-container {
                background-color: #fff;
                border: 1px solid #ddd;
                border-radius: 5px;
                padding: 30px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }

            h1 {
                color: #5cb85c;
                text-align: center;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            th,
            td {
                border: 1px solid #ddd;
                padding: 12px;
                text-align: left;
            }

            .order-summary {
                text-align: right;
                margin-top: 20px;
            }

            .return-btn {
                text-align: center;
                margin-top: 20px;
            }

            .return-btn a {
                display: inline-block;
                background-color: #4CAF50;
                color: white;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
            }
        </style>
    </head>

    <body>
        <div class="confirmation-container">
            <h1>Payment Confirmation</h1>
            <p>Order ID: <?php echo htmlspecialchars($insertResult['orderId']); ?></p>

            <table>
                <thead>
                    <tr>
                        <th>Item ID</th>
                        <th>Color ID</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($paymentDetails['items'] as $item):
                        $itemId = $item['itemId'] ?? 'N/A';
                        $colorId = $item['colorId'] ?? 'N/A';
                        $quantity = $item['quantity'] ?? 0;
                        $price = $item['price'] ?? 0;
                        $subtotal = $quantity * $price;
                        $total += $subtotal;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($itemId); ?></td>
                            <td><?php echo htmlspecialchars($colorId); ?></td>
                            <td><?php echo intval($quantity); ?></td>
                            <td><?php echo number_format($price, 2); ?> LKR</td>
                            <td><?php echo number_format($subtotal, 2); ?> LKR</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="order-summary">
                <table class="summary-table">
                    <tr>
                        <td>Subtotal:</td>
                        <td><?php echo number_format($paymentDetails['subtotal'], 2); ?> LKR</td>
                    </tr>
                    <tr>
                        <td>Delivery Charge:</td>
                        <td><?php echo number_format($paymentDetails['deliveryCharge'], 2); ?> LKR</td>
                    </tr>
                    <tr class="total-row">
                        <td><strong>Grand Total:</strong></td>
                        <td><strong><?php echo number_format($paymentDetails['grandTotal'], 2); ?> LKR</strong></td>
                    </tr>
                </table>
            </div>

            <!-- Add delivery details section -->
            <div class="delivery-details">
                <h3>Delivery Information</h3>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($paymentDetails['address']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($paymentDetails['phone']); ?></p>
            </div>

            <div class="return-btn">
                <a href="index.php">Return to Home</a>
            </div>
        </div>
    </body>

    </html>
    <?php
    exit();
}

// Main Payment Confirmation Processing
try {
    // Handle GET request (initial PayHere return)
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['order_id'])) {
        echo <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            
        <script>
           document.addEventListener('DOMContentLoaded', function() {
                    const orderDetails = localStorage.getItem('orderDetails');
                    if (orderDetails) {
                        fetch('payment-confirm.php', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                            body: 'orderDetails=' + encodeURIComponent(orderDetails)
                        })
                        .then(response => response.text())
                        .then(html => {
                            document.body.innerHTML = html;
                            localStorage.removeItem('orderDetails');
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            window.location.href = 'index.php';
                        });
                    } else {
                        window.location.href = 'index.php';
                    }
                });
            </script>
        </head>
        <body>
            <p>Processing payment...</p>
        </body>
        </html>
        HTML;
        exit();
    }

    // Handle POST request with order details
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orderDetails'])) {
        $paymentDetailsJson = $_POST['orderDetails'];
        $paymentDetails = json_decode($paymentDetailsJson, true);

        // Add detailed validation
        if (!$paymentDetails) {
            logError("JSON decode failed: " . json_last_error_msg());
            renderErrorPage("Payment Error", "Invalid payment details format");
        }

        // Validate required fields
        $requiredFields = ['grandTotal', 'items', 'address', 'phone'];
        foreach ($requiredFields as $field) {
            if (!isset($paymentDetails[$field])) {
                logError("Missing required field: " . $field);
                renderErrorPage("Payment Error", "Missing required payment information");
            }
        }

        if (empty($paymentDetails['items'])) {
            logError("No items in payment details");
            renderErrorPage("Payment Error", "No items found in order");
        }

        try {
            // Insert order details
            $insertResult = insertOrderDetails($paymentDetails, $conn);

            renderConfirmation($paymentDetails, $insertResult);
        } catch (Exception $e) {
            logError("Order insertion failed: " . $e->getMessage());
            renderErrorPage("Order Processing Error", "Failed to process order: " . $e->getMessage());
        }

        exit(); // Important: exit 
    }

    renderErrorPage("Invalid Request", "Invalid request method or missing parameters");

} catch (Exception $e) {
    logError("Payment Confirmation Exception: " . $e->getMessage());
    renderErrorPage("System Error", "An unexpected error occurred. Please contact support.");
} finally {
    if (isset($conn)) {
        mysqli_close($conn);
    }
}