<?php 
include "customerSidePanal.php";
include "..\classes\DBConnect.php";
$db = new DatabaseConnection;
$conn = $db->getConnection();

if (!isset($_SESSION['Customer_ID'])) {
    header("Location: customerLogin.php");
    exit;
}

$customerID = $_SESSION['Customer_ID'];

// Using prepared statement for customer query
$sql_customer = "SELECT FName, LName, Email FROM customer WHERE Customer_ID = ?";
$stmt = $conn->prepare($sql_customer);
$stmt->bind_param("s", $customerID);
$stmt->execute();
$result_customer = $stmt->get_result();
$customer = $result_customer->fetch_assoc();
$stmt->close();

// Using prepared statement for orders query
$sql_orders = "SELECT 
    COUNT(*) AS total_orders,
    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS new_orders,
    SUM(CASE WHEN status = 'Confirmed' THEN 1 ELSE 0 END) AS confirmed_orders,
    SUM(CASE WHEN status = 'Ready' THEN 1 ELSE 0 END) AS ready_orders,
    SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) AS completed_orders,
    SUM(CASE WHEN status = 'On the Way' THEN 1 ELSE 0 END) AS on_the_way_orders
FROM order_tbl WHERE user = ?";

$stmt = $conn->prepare($sql_orders);
$stmt->bind_param("s", $customerID);
$stmt->execute();
$result_orders = $stmt->get_result();
$order_summary = $result_orders->fetch_assoc();
$stmt->close();
?>
?>
<head>
<link rel="stylesheet" href="../assets/css/customer-account.css" />
</style>
</head>
<body>
    <div class="flex flex-col lg:flex-row mydash" style="" >
      <main class="flex-1 lg:ml-2 mainDash" >
        <div class="bg-card p-4 rounded-lg shadow-md mb-3">
          <div class="flex items-center">
            
            <div>
            <div class="dashboard-hero">
                <div class="hero-content">
                  <h1>Welcome to Your Dashboard</h1>
                  <p>Track your orders in real-time</p>
                </div>
              <div class="overview-container" style="">
        <!-- Profile Section -->
        <div class="card profile" style="text-content:center" style="margin-top:70px">
            <img src="../assets/imgs/signin.png" alt="Profile Picture"> <!-- Replace with dynamic profile picture if available -->
            <div>
                <h3><?php echo $customer['FName'] . ' ' . $customer['LName']; ?></h3>
                <p class="status">Email: <?php echo $customer['Email']; ?></p>
            </div>
        </div>

        <!-- Orders Overview -->
        <div class="grid">
            <div class="card">
                <h3>New Orders</h3>
                <img src="https://openui.fly.dev/openui/48x48.svg?text=%F0%9F%93%9D" alt="To be reviewed" class="mx-auto mb-2 hover:scale-110 transition-transform duration-200">
                <p><span class="status-badge badge-new">New</span> <?php echo $order_summary['new_orders']; ?></p>
            </div>
            <div class="card">
                <h3>Confirmed Orders</h3>
                <img src="https://openui.fly.dev/openui/48x48.svg?text=💵" alt="Unpaid" class="mx-auto mb-2 hover:scale-110 transition-transform duration-200">
                
                <p><span class="status-badge badge-confirmed">Confirmed</span> <?php echo $order_summary['confirmed_orders']; ?></p>
            </div>
            <div class="card">
                <h3>Ready Orders</h3>
                <img src="https://openui.fly.dev/openui/48x48.svg?text=📦" alt="Ready Orders" class="mx-auto mb-2 hover:scale-110 transition-transform duration-200">
                <p><span class="status-badge badge-ready">Ready</span> <?php echo $order_summary['ready_orders']; ?></p>
            </div>
            <div class="card">
                <h3>On the Way</h3>
                <img src="https://openui.fly.dev/openui/48x48.svg?text=🚚" alt="Shipped" class="mx-auto mb-2 hover:scale-110 transition-transform duration-200">
                <p><span class="status-badge badge-ontheway">On the Way</span> <?php echo $order_summary['on_the_way_orders']; ?></p>
            </div>
            <div class="card">
                <h3>Complete Orders</h3>
                <img src="https://openui.fly.dev/openui/48x48.svg?text=✅" alt="Shipped" class="mx-auto mb-2 hover:scale-110 transition-transform duration-200">
                <p><span class="status-badge badge-completed">Completed</span> <?php echo $order_summary['completed_orders']; ?></p>
            </div>
        </div>
    </div>

            </div>
          </div>

          
  </body>
  </html>