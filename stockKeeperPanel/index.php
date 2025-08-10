<?php 
$currentMainPage = "Home";
include "stock_keeper_header.php";

$empName =$_SESSION['Name'];
?>
<head><style>
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
</style>
<link rel="stylesheet" href="..\assets\css\admin-dashboard-home-style-A.css">
</head>
<div class="container myindexContainer">
<h5 class="card-title mt-3 displayName">Welcome, <?php echo htmlspecialchars($empName);?> </h5>
    <div class="card main-container myStaffDash">
    
    <div class="dashboard-hero">
  <div class="hero-content">
    <h1>Welcome to Your Dashboard</h1>
    <p>Track and manage your orders in real-time</p>
  </div>
  
</div>

<div class="dashboard-summary">
  <!-- Stock keeper Dsh -->
  <div class="summary-container">
  <a href="view_stock_KeeperNewOrders.php">
    <div class="summary-card new-orders" >
      <div class="card-content">
        <div class="icon-container">
          <i class="fas fa-clock"></i>
        </div>
        <div class="info">
          <h3>New Orders</h3>
          <p class="count" id="newOrder-count">0</p>
        </div>
      </div>
    </div></a>
    
    
    <a href="stock_keeper_readyOrders.php">
    <div class="summary-card confirmed-orders">
      <div class="card-content">
        <div class="icon-container">
          <i class="fas fa-box"></i>
        </div>
        <div class="info">
          <h3>Ready Orders</h3>
          <p class="count" id="ready-count">0</p>
        </div>
      </div>
    </div></a>
    
    <a href="stock_keeper_dispatched.php">
    <div class="summary-card dispatched-orders">
      <div class="card-content">
        <div class="icon-container">
          <i class="fas fa-truck"></i>
        </div>
        <div class="info">
          <h3>Dispatched Orders</h3>
          <p class="count" id="dispatched-count">0</p>
        </div>
      </div>
    </div></a>
    <a href="stock_Keeper_order_history.php">
    <div class="summary-card ready-orders">
      <div class="card-content">
        <div class="icon-container">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="info">
          <h3>Complete orders</h3>
          <p class="count" id="complete-count">0</p>
        </div>
      </div>
    </div></a>
    
  </div>
</div>
<script>
$(document).ready(function() {
    loadOrderSummary();
    // Refresh summary every 30 seconds
    setInterval(loadOrderSummary, 20000);
});

function loadOrderSummary() {
    $.ajax({
      url: 'stock_Keeper_Order-Ajax.php',
        method: 'POST',
        data: { task: 'getOrderSummary' },
        dataType: 'json', // Add this line to parse JSON automatically
        success: function(data) {
            if (data.success) {
                updateDashboardCounts(data.summary);
            } else {
                console.error('Failed to load summary:', data.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Ajax request failed:', error);
        }
    });
}

function updateDashboardCounts(summary) {
    $('#newOrder-count').text(summary.newAssigned || 0);
    $('#ready-count').text(summary.ready || 0);
    $('#dispatched-count').text(summary.dispatched || 0);
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

<?php include "stock_keeper_footer.php";?>