<?php 
$currentSubPage="adminHome";
include "adminHeader_1.php"; 

$empName =$_SESSION['Name'];
?>

<head>
<style>
.dashboard-summary {
    padding: 20px;
    background-color: #f8f9fa;
}

.summary-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.summary-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    padding: 20px;
}

.summary-card:hover {
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(117, 225, 144, 0.82);
    transform: translateY(-5px);
}
a {
    text-decoration: none;
    color: inherit; /* Keeps the text and styles from the div */
}
.card-content {
    display: flex;
    align-items: center;
    gap: 15px;
}

.icon-container {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.new-orders .icon-container {
    background-color: #ffe5e5;
    color: #dc3545;
}

.confirmed-orders .icon-container {
    background-color: #e5f6ff;
    color: #0d6efd;
}

.ready-orders .icon-container {
    background-color: #e5ffe5;
    color: #198754;
}

.dispatched-orders .icon-container {
    background-color: #f3e5ff;
    color: #6f42c1;
}

.info h3 {
    margin: 0;
    font-size: 16px;
    color: #6c757d;
}

.info .count {
    margin: 5px 0 0;
    font-size: 24px;
    font-weight: bold;
    color: #212529;
}

/* New hero section styles */
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

.dashboard-illustration {
    width: 100%;
    height: 400px;
    margin-top: 20px;
}

/* Animations */
@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
    100% { transform: translateY(0px); }
}

@keyframes pulse {
    0% { opacity: 0.7; }
    50% { opacity: 1; }
    100% { opacity: 0.7; }
}

.floating {
    animation: float 2s ease-in-out infinite;
}

.floating-delayed {
    animation: float 6s ease-in-out infinite;
    animation-delay: -2s;
}

.pulse {
    animation: pulse 3s ease-in-out infinite;
}

.count-updated {
    animation: pulse 1s ease-in-out;
}

/* Section Styles */
.summary-section {
    margin-bottom: 2rem;
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.section-header {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f0f0f0;
}

.section-header i {
    font-size: 1.5rem;
    margin-right: 0.75rem;
    color: #2c3e50;
}

.section-header h2 {
    font-size: 1.25rem;
    color: #2c3e50;
    margin: 0;
    font-weight: 600;
}

/* Card Colors by Section */
/* Order Section */
.new-orders .icon-container {
    background-color: #ffe5e5;
    color: #dc3545;
}

.confirmed-orders .icon-container {
    background-color: #e5f6ff;
    color: #0d6efd;
}

.ready-orders .icon-container {
    background-color: #e5ffe5;
    color: #198754;
}

.dispatched-orders .icon-container {
    background-color: #f3e5ff;
    color: #6f42c1;
}

.on-way-orders .icon-container {
    background-color: #fff3cd;
    color: #ffc107;
}

.completed-orders .icon-container {
    background-color: #d4edda;
    color: #28a745;
}

/* Inventory Section */
.inventory-items .icon-container {
    background-color: #e8f5e9;
    color: #4CAF50;
}

.categories .icon-container {
    background-color: #efebe9;
    color: #795548;
}

.income .icon-container {
    background-color: #e8f5e9;
    color: #4CAF50;
}

/* Users Section */
.cust, .Staff,
.drivers, .income,
.categories, .inventory-items,
.completed-orders, .completed-orders,
.on-way-orders,.dispatched-orders,
.ready-orders, .confirmed-orders,
.new-orders
 {
  background-color: #f5f5f5;
    color: #333;
}
.Staff{
  background-color: #f5f5f5;

}
.customers .icon-container {
    background-color: #f3e5f5;
    color: #9C27B0;
}

.staff .icon-container {
    background-color: #e3f2fd;
    color: #2196F3;
}

.drivers .icon-container {
    background-color: #fff3e0;
    color: #FF9800;
}

/* Maintain existing hover effects */
.summary-card:hover {
    transform: translateY(-5px);
    background-color:rgb(235, 251, 228);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Grid Layout */
.summary-container {
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.summary-section {
    animation: fadeIn 0.5s ease-out forwards;
}
</style>
<link rel="stylesheet" href="..\assets\css\admin-dashboard-home-style-A.css">

</head>
<div class="card main-container" style= "height:100%" >
<div calss="dash_img_box"  >

<h5 class="card-title mt-3"><?php echo htmlspecialchars($empName);?> </h5>
        </div>
        <img class="dash_img" src="../assets/imgs/dashboars.jpg">
        <div style="color: white; font-size: 24px; width:360px;">
        
</div>
<div class="dashboard-hero">
  <div class="hero-content">
    <h1>Welcome to Your Dashboard</h1>
    <p>Track and manage your orders in real-time</p>
  </div>
  
</div>

<div class="dashboard-summary">
  <!-- Previous dashboard summary code remains the same -->

  <div class="summary-section">
    <div class="section-header">
      <i class="fas fa-shopping-bag"></i>
      <h2>Order Management</h2>
    </div>
    <div class="summary-container">
      <a href="ViewOrders.php">
        <div class="summary-card new-orders">
          <div class="card-content">
            <div class="icon-container">
              <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="info">
              <h3>New Orders</h3>
              <p class="count" id="pending-count">0</p>
            </div>
          </div>
        </div>
      </a>

      <a href="ViewOrders.php">
        <div class="summary-card confirmed-orders">
          <div class="card-content">
            <div class="icon-container">
              <i class="fas fa-clipboard-check"></i>
            </div>
            <div class="info">
              <h3>Confirmed Orders</h3>
              <p class="count" id="confirmed-count">0</p>
            </div>
          </div>
        </div>
      </a>

      <a href="ViewOrders.php">
        <div class="summary-card ready-orders">
          <div class="card-content">
            <div class="icon-container">
              <i class="fas fa-box-open"></i>
            </div>
            <div class="info">
              <h3>Ready Orders</h3>
              <p class="count" id="ready-count">0</p>
            </div>
          </div>
        </div>
      </a>

      <a href="ViewOrders.php">
        <div class="summary-card dispatched-orders">
          <div class="card-content">
            <div class="icon-container">
              <i class="fas fa-truck-loading"></i>
            </div>
            <div class="info">
              <h3>Dispatched Orders</h3>
              <p class="count" id="dispatched-count">0</p>
            </div>
          </div>
        </div>
      </a>

      <a href="ViewOrders.php">
        <div class="summary-card on-way-orders">
          <div class="card-content">
            <div class="icon-container">
              <i class="fas fa-route"></i>
            </div>
            <div class="info">
              <h3>On The Way Orders</h3>
              <p class="count" id="onTheWay-count">0</p>
            </div>
          </div>
        </div>
      </a>

      <a href="ViewOrders.php">
        <div class="summary-card completed-orders">
          <div class="card-content">
            <div class="icon-container">
              <i class="fas fa-flag-checkered"></i>
            </div>
            <div class="info">
              <h3>Completed Orders</h3>
              <p class="count" id="completed-count">0</p>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>

  <!-- Inventory Section -->
  <div class="summary-section">
    <div class="section-header">
      <i class="fas fa-warehouse"></i>
      <h2>Inventory & Revenue</h2>
    </div>
    <div class="summary-container">
      <a href="viewProduct.php">
        <div class="summary-card inventory-items">
          <div class="card-content">
            <div class="icon-container">
              <i class="fas fa-leaf"></i>
            </div>
            <div class="info">
              <h3>Active Items</h3>
              <p class="count" id="activeItem-count">0</p>
            </div>
          </div>
        </div>
      </a>

      <a href="addMainCategory.php">
        <div class="summary-card categories">
          <div class="card-content">
            <div class="icon-container">
              <i class="fas fa-sitemap"></i>
            </div>
            <div class="info">
              <h3>Plant Categories</h3>
              <p class="count" id="category-count">0</p>
            </div>
          </div>
        </div>
      </a>

      <a href="#">
        <div class="summary-card income">
          <div class="card-content">
            <div class="icon-container">
              <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="info">
              <h3>Total Income</h3>
              <p class="count" id="income-count">0</p>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>

  <!-- Users Section -->
  <div class="summary-section">
    <div class="section-header">
      <i class="fas fa-users"></i>
      <h2>User Management</h2>
    </div>
    <div class="summary-container">
      <a href="admin-CustomerManagement.php">
        <div class="summary-card cust">
          <div class="card-content">
            <div class="icon-container">
              <i class="fas fa-user-friends"></i>
            </div>
            <div class="info">
              <h3>Registered Customers</h3>
              <p class="count" id="customer-count">0</p>
            </div>
          </div>
        </div>
      </a>

      <a href="ViewEmp.php">
        <div class="summary-card Staff">
          <div class="card-content">
            <div class="icon-container">
              <i class="fas fa-users-cog"></i>
            </div>
            <div class="info">
              <h3>Active Staff</h3>
              <p class="count" id="staff-count">0</p>
            </div>
          </div>
        </div>
      </a>

      <a href="viewDeliveryDriver.php">
        <div class="summary-card drivers">
          <div class="card-content">
            <div class="icon-container">
              <i class="fas fa-car"></i>
            </div>
            <div class="info">
              <h3>Active Drivers</h3>
              <p class="count" id="driver-count">0</p>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>

</div>
<script>
$(document).ready(function() {
    loadOrderSummary();
    // Refresh summary every 30 seconds
    setInterval(loadOrderSummary, 30000);
});

function loadOrderSummary() {
  $.ajax({
        url: 'order-Ajax.php',
        method: 'POST',
        data: { task: 'getOrderSummary' },
        success: function(response) {
            try {
                const data = JSON.parse(response);
                if (data.success) {
                    // Update order counts
                    $('#pending-count').text(data.summary.pending || 0);
                    $('#confirmed-count').text(data.summary.confirmed || 0);
                    $('#ready-count').text(data.summary.ready || 0);
                    $('#dispatched-count').text(data.summary.dispatched || 0);
                    $('#onTheWay-count').text(data.summary.onTheWay || 0);
                    $('#completed-count').text(data.summary.completed || 0);
                    
                    // Update other metrics
                    $('#activeItem-count').text(data.summary.active_items || 0);
                    $('#category-count').text(data.summary.categories || 0);
                    $('#staff-count').text(data.summary.active_staff || 0);
                    $('#driver-count').text(data.summary.active_drivers || 0);
                    $('#customer-count').text(data.summary.registered_customers || 0);
                    $('#income-count').text('Rs.' + data.summary.total_income || '0.00');
                    
                    // Add animation
                    $('.count').addClass('count-updated');
                    setTimeout(() => {
                        $('.count').removeClass('count-updated');
                    }, 1000);
                } else {
                    console.error('Failed to load summary:', data.message);
                }
            } catch (e) {
                console.error('Error parsing summary data:', e);
            }
        },
        error: function(xhr, status, error) {
            console.error('Ajax request failed:', error);
        }
    });
}

function updateDashboardCounts(summary) {
    $('#dispatched-count').text(summary.dispatched || 0);
    $('#OTW-count').text(summary.onTheWay || 0);
    $('#complete-count').text(summary.completed || 0);
    
    // Add animation class to counts that changed
    $('.count').each(function() {
        $(this).addClass('count-updated');
        setTimeout(() => {
            $(this).removeClass('count-updated');
        }, 1000);
    });
}
</script>

<?php include "adminFooter.php"; ?>