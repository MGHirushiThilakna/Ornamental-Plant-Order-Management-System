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
  <style>
    .dashboard-hero {
    background: linear-gradient(135deg, #fff, #f8f9fa);
    padding: 40px 20px;
    position: relative;
    overflow: hidden;
}
    .hero-content {
    max-width: 1200px;
    margin: 0 auto;
    text-align: center;
    position: relative;
    z-index: 1;
}

.hero-content h1 {
    font-size: 2.5rem;
    color: #212529;
    margin-bottom: 1rem;
}

.hero-content p {
    font-size: 1.25rem;
    color: #6c757d;
}
     .mydash {
      margin-left:80px;
      height:100%;
      width:1500px;
      display: flex;  /* Make container flex */
      flex-direction: row; /* Force horizontal layout */
    }

    .mainDash {
      margin-top: 100px;
      margin-left: 250px;
      width:2000px; /* Take full width of parent */
    }

    main {
      width: 100%; /* Take full width */
    }

    img {
      width: 50px;
      height: 50px;
    }

    .overview-container {
      padding: 20px;
      margin-left: 0; /* Remove left margin */
      margin-top: 0; /* Reduce top margin */
      width: 1000px; /* Take full width */
    }

    .card {
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 10px;
      padding: 20px;
      margin: 10px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      flex: 1; /* Allow cards to grow equally */
    }

    .grid {
      display: flex; /* Change to flex instead of grid */
      flex-direction: row; /* Force horizontal layout */
      flex-wrap: nowrap; /* Prevent wrapping */
      gap: 20px;
      margin-top: 20px;
      width: 100%;
    }

    .profile {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 30px;
    }

    .profile img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
    }

    /* Keep your existing status and badge styles */
    .status {
      font-size: 14px;
      color: #777;
    }

    .status-badge {
      display: inline-block;
      padding: 5px 10px;
      border-radius: 20px;
      color: #fff;
      font-size: 12px;
    }

    .badge-new { background: #f39c12; }
    .badge-confirmed { background: #27ae60; }
    .badge-ready { background: #2980b9; }
    .badge-ontheway { background: #8e44ad; }

    /* Add media query for responsiveness */
    @media (max-width: 1024px) {
      .grid {
        flex-wrap: wrap; /* Allow wrapping on smaller screens */
      }
      
      .card {
        flex: 1 1 calc(50% - 20px); /* Two cards per row on medium screens */
      }
    }

    @media (max-width: 768px) {
      .card {
        flex: 1 1 100%; /* One card per row on small screens */
      }
    }
</style>
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
                  <p>Track and manage your orders in real-time</p>
                </div>
              <div class="overview-container" style="">
        <!-- Profile Section -->
        <div class="card profile" style="margin-top:70px">
            <img src="../assets/imgs/signin.png" alt="Profile Picture"> <!-- Replace with dynamic profile picture if available -->
            <div>
                <h3><?php echo $customer['FName'] . ' ' . $customer['LName']; ?></h3>
                <p class="status">Customer ID: <?php echo $customerID; ?></p>
                <p class="status">Email: <?php echo $customer['Email']; ?></p>
            </div>
        </div>

        <!-- Orders Overview -->
        <div class="grid">
            <div class="card">
                <h3>New Orders</h3>
                <p><span class="status-badge badge-new">New</span> <?php echo $order_summary['new_orders']; ?></p>
            </div>
            <div class="card">
                <h3>Confirmed Orders</h3>
                <p><span class="status-badge badge-confirmed">Confirmed</span> <?php echo $order_summary['confirmed_orders']; ?></p>
            </div>
            <div class="card">
                <h3>Ready Orders</h3>
                <p><span class="status-badge badge-ready">Ready</span> <?php echo $order_summary['ready_orders']; ?></p>
            </div>
            <div class="card">
                <h3>On the Way</h3>
                <p><span class="status-badge badge-ontheway">On the Way</span> <?php echo $order_summary['on_the_way_orders']; ?></p>
            </div>
        </div>
    </div>

            </div>
          </div>

          
  </body>
  </html>