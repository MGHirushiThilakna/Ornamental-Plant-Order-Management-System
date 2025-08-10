<?php include "customer_header.php"; 
$cusName = $_SESSION['Name'];
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
<style>
  body {
      font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
  }
  .space-y-6 {
      position: fixed;
      top: 123px; /* Space for the header */
      left: 0;
      width: 200px; /* Adjust width as needed */
      height: calc(100% - 50px); /* Full height minus header */
      background-color: #f5f5f5;
      z-index: 3;
      overflow-y: auto;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
  }
  .side-panel a {
      display: flex;
      align-items: center;
      gap: 20px;
      text-decoration: none;
      color: #406c61;
      font-size: 15px;
      transition: transform 0.3s ease;
  }
  .side-panel ul a:hover {
      transform: translateX(10px);
      background-color: #9CDBA6;
  }
  .myhomelink{
    margin-top:45px;
    margin-left:3px;
    position: fixed;
    overflow-y: auto;
     
  }
  .side-panel-greeting{
    text-align: left;
    font-size: 12px;
    font-weight: bold;
    color: green;
     padding-bottom: 6px;
     margin-bottom:10px;
     background-color:;
     border-radius:10px;
     box-shadow: 2px 3px 5px rgba(0, 0, 0, 0.1);

  }
  .active-menu-item{
    background-color: #9CDBA6; /* Active background color */
    color: #ffffff; /* Active text color */
    font-weight: bold;
  }
  .side-panel ul a.active {
    background-color: red; /* Active background color */
    color: #ffffff; /* Active text color */
    font-weight: bold; /* Optional: bold text for active tab */
}
</style>
</head>
<body>
    <div class="min-h-screen bg-background text-foreground myhomelink">
        <div class="container mx-auto p-3 " style="margin-top:10px;" >
        <nav class="text-sm text-muted-foreground mb-2">
        <a href="../index.php" class="hover:underline">Home</a> &gt; <span>Account</span>
        </nav>
            <div class="side-panel">
                <p class="side-panel-greeting">
                    Welcome to Sunshine Plant House 
                </p>

                <ul id="sidebar-menu" class="space-y-6">
                    <li>
                        <a class="block py-2 px-4 rounded hover:bg-muted transition duration-200" 
                        href="customerDashboard.php" >
                            <i class="fas fa-tachometer-alt"></i> Overview
                         </a>
                </li>
                    <li>
                        <a class="block py-2 px-4 rounded hover:bg-muted transition duration-200" 
                         href="cusOrderHistory.php" >
                        <i class="fas fa-history"></i> Order History
                        </a>
                </li>
                    
                    <li>
                        <a href="cusFeedback.php" class="block py-2 px-4 rounded hover:bg-muted transition duration-200">
                        <i class="fas fa-comment-dots"></i> Feedback
                        </a>
                    </li>
                    <li>
                        <a href="cusProfile.php" class="block py-2 px-4 rounded hover:bg-muted transition duration-200">
                        <i class="fas fa-cogs"></i> Settings
                        </a>
                    </li>
                    <li>
                        <a href="cusMessage.php" class="block py-2 px-4 rounded hover:bg-muted transition duration-200">
                        <i class="fas fa-envelope"></i> Message Center
                        </a>
                    </li>
                    <li>
                        <a href="cusHelp.php" class="block py-2 px-4 rounded hover:bg-muted transition duration-200">
                        <i class="fas fa-question-circle"></i> Help Center
                        </a>
                    </li>
                    
                </ul>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    var sidebarLinks = document.querySelectorAll('#sidebar-menu a');
    
    sidebarLinks.forEach(function(link) {
        link.addEventListener('click', function(event) {
            console.log('Link clicked:', this.href);  // Debug log
        });
    });
});
    </script>
</body>
