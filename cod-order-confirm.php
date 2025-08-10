<?php
session_start();
include('./config/constant.php');

if (!isset($_GET['order_id'])) {
    header('Location: index.php');
    exit;
}

$order_id = mysqli_real_escape_string($conn, $_GET['order_id']);
$customer_id = $_SESSION['Customer_ID'] ?? null;

if (!$customer_id) {
    header('Location: customerLogin.php');
    exit;
}

// Fetch order details
$query = "SELECT * FROM order_tbl WHERE odr_id = ? AND user = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ss', $order_id, $customer_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Calculate total amount
$total_amount = array_sum(array_column($orders, 'subtotal'));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #45a049;
            --background-color: #f8f9fa;
            --border-color: #dee2e6;
            --text-color: #333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background-color: var(--background-color);
            color: var(--text-color);
            padding: 20px;
        }

        .confirmation-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .success-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--border-color);
        }

        .success-header h1 {
            color: var(--primary-color);
            font-size: 2em;
            margin-bottom: 10px;
        }

        .order-id {
            color: #666;
            font-size: 1.2em;
        }

        .order-details {
            margin: 30px 0;
        }

        .order-section {
            background-color: var(--background-color);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .section-title {
            color: var(--text-color);
            margin-bottom: 15px;
            font-size: 1.3em;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
        }

        .item {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            padding: 15px;
            background-color: white;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .item:last-child {
            margin-bottom: 0;
        }

        .item p {
            margin: 5px 0;
        }

        .delivery-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .total-section {
            text-align: right;
            padding: 20px;
            background-color: var(--background-color);
            border-radius: 8px;
            margin: 20px 0;
        }

        .total-amount {
            font-size: 1.4em;
            color: var(--primary-color);
            font-weight: bold;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.3s ease;
            text-align: center;
        }

        .btn:hover {
            background-color: var(--secondary-color);
        }

        .buttons {
            text-align: center;
            margin-top: 30px;
        }

        @media (max-width: 600px) {
            .confirmation-container {
                padding: 15px;
            }

            .item {
                grid-template-columns: 1fr;
            }

            .delivery-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="confirmation-container">
        <div class="success-header">
            <h1>Order Confirmed!</h1>
            <div class="order-id">Order ID: <?php echo htmlspecialchars($order_id); ?></div>
        </div>

        <div class="order-details">
            <div class="order-section">
                <h2 class="section-title">Order Summary</h2>
                <?php foreach ($orders as $order): ?>
                    <div class="item">
                        <div>
                            <p><strong>Item ID:</strong> <?php echo htmlspecialchars($order['item_id']); ?></p>
                            <p><strong>Quantity:</strong> <?php echo htmlspecialchars($order['qty']); ?></p>
                        </div>
                        <div>
                            <p><strong>Price:</strong> Rs <?php echo number_format($order['price'], 2); ?></p>
                            <p><strong>Subtotal:</strong> Rs <?php echo number_format($order['subtotal'], 2); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="order-section">
                <h2 class="section-title">Delivery Information</h2>
                <div class="delivery-info">
                    <div>
                        <p><strong>Delivery Address:</strong></p>
                        <p><?php echo htmlspecialchars($orders[0]['address']); ?></p>
                    </div>
                    <div>
                        <p><strong>Contact Number:</strong></p>
                        <p><?php echo htmlspecialchars($orders[0]['mobile']); ?></p>
                    </div>
                </div>
            </div>

            <div class="total-section">
                <p>Payment Method: <strong>Cash on Delivery</strong></p>
                <p class="total-amount">Total Amount: Rs <?php echo number_format($total_amount, 2); ?></p>
            </div>
        </div>

        <div class="buttons">
            <a href="index.php" class="btn">Back to Home</a>
        </div>
    </div>
</body>

</html>