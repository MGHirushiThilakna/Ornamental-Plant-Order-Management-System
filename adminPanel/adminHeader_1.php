<?php 
session_start();
if(!isset( $_SESSION['empID'])){
    header("location:../staffLogin.php");
} 
$empID = $_SESSION['empID'];

$currentMainPage ="adminHome";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sunshine Plant House - Admin</title>
    <!-- Bootstrap-->
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css" />
    <!--fontawsome icons -->
    <link rel="stylesheet" href="../assets/icons/css/all.min.css">
    <!-- custom style -->
    <link rel="stylesheet" href="../assets/css/admin-navbar-style-A.css" />
    <!-- Sweet Alert 2 and JS-->
    <script src="..\assets\sweetalert2\jquery-3.5.1.min.js"></script>
    <script src="..\assets\sweetalert2\sweetalert2.all.min.js"></script>
    <script src="..\assets\js\staff-logoutProccess.js"></script>
    
     
</head>
<body style="z-index:0;"> 
    <nav class="navbar navbar-expand-lg myNavbar" >
        <div class="container-fluid" style="border-style: solid;">
            <div class="myNavbar header">
                
                    <div class="myNavbar left_section">
                        <img class="brand_logo"src="../assets/imgs/brand_logo.jpg" alt="logo" class="logo" >
                        <div class="brand_name"> Sunshine Plant House
                        </div>
                </div>
                    <div class="middle_section">
                    <input class="search-bar" type="text" placeholder="Search here">
                        
                    <button class="search_icon_btn">
                            <img class="search_icon" src="../assets/imgs/my_icons/search.svg" alt="logo" class="logo">
                                <div class="tooltip">
                                    Search 
                                </div>
                        </button >
                        
                    </div>
                    <div class="righr_section">
                        
                        <div class="notification_icon_container"><button class="notification_icon_btn">
                            <img class="notification_icon" src="../assets/imgs/my_icons/notifications.svg">
                            <div class="notification_counts">0</div> 
                            </button>
                        </div>
                        <!-- header drop down menu-->
                        <div class="dropdown dropdown-toggle" onclick="toggleDropdown()">
                            <img class="profile_pic" src="../assets/imgs/profile_pic.jpg" id="profile-pic">
                        </div>
                    <div class="dropdown-menu" id="dropdownMenu">
                    <a <?php echo $currentMainPage == 'profile' ? 'active' : '' ?>href="adminProfile.php">Settings</a>
                    <a  href="" id="signout"><i class="far fa-user-circle fa-lg" ></i> Logout</a>
                    </div>

                    </div>
</nav>
<script>
    function toggleDropdown() {
        var dropdownMenu = document.getElementById("dropdownMenu");
        dropdownMenu.classList.toggle("show");
    }

    window.onclick = function(event) {
        var dropdownMenu = document.getElementById("dropdownMenu");
        if (!event.target.matches('.dropdown-toggle')) {
            if (dropdownMenu.classList.contains('show')) {
                dropdownMenu.classList.remove('show');
            }
        }
    }
</script>

                                                               <!--side bar -->
        <div class=" main_sidebar">
		<a href="index.php" style="height:56px" ><span class=" admin_panel_lbl" style="font-size:19px">Admin Panel</span></a>
            <div class="side_bar">
                
            <a class="nav_side_bar" 
            <?php echo $currentMainPage == 'adminHome' ? 'active' : '' ?> href="index.php">        
            <div class="sidebar_link">
            <div class="sidebar_icon_div">
             <img  src="../assets/imgs/my_icons/hamburger-menu.svg">
             </div>
             <div class="sidebar_cell_text">Dashboard 
             </div>
                </div>
                 </a>
                <a class="nav_side_bar"
                 <?php echo $currentMainPage == 'CategoryPage' ? 'active' : '' ?>  href="addMainCategory.php">
                        
                 <div class="sidebar_link">
                 <div class="sidebar_icon_div">
                    <img  src="../assets/imgs/my_icons/hamburger-menu.svg">
                    </div>
                      <div class="sidebar_cell_text">Category Handling 
                     </div>
                 </div>
                 </a>
                <a class="nav_side_bar"
                 <?php echo $currentMainPage == 'products' ? 'active' : '' ?> href="viewProduct.php">
                        
                <div class="sidebar_link">
                 <div class="sidebar_icon_div">
                    <img  src="../assets/imgs/my_icons/hamburger-menu.svg">
                     </div>
                  <div class="sidebar_cell_text">Item Management 
                  </div>
                     </div>
                    </a>
                    <a class="nav_side_bar" <?php echo $currentMainPage == 'ordrHandling' ? 'active' : '' ?>  href="ViewOrders.php">
                        
                        <div class="sidebar_link">
                            <div class="sidebar_icon_div">
                            <img  src="../assets/imgs/my_icons/hamburger-menu.svg">
                            </div>
                                <div class="sidebar_cell_text">Order Handling 
                                    </div>
                        </div> 
                    </a>
                    <a class="nav_side_bar" <?php echo $currentMainPage == 'delivery' ? 'active' : '' ?>  href="viewDeliveryDriver.php">
                        
                        <div class="sidebar_link">
                            <div class="sidebar_icon_div">
                            <img  src="../assets/imgs/my_icons/hamburger-menu.svg">
                            </div>
                                <div class="sidebar_cell_text">Delivery Driver Handling 
                                    </div>
                        </div>
                    </a>
                    <a class="nav_side_bar"  href="admin-CustomerManagement.php">
                        
                        <div class="sidebar_link">
                            <div class="sidebar_icon_div">
                            <img  src="../assets/imgs/my_icons/hamburger-menu.svg">
                            </div>
                                <div class="sidebar_cell_text">Customer Management 
                                    </div>
                        </div>
                    </a>
                    <a class="nav_side_bar"  href="admin-addFAQ.php">
                        
                        <div class="sidebar_link">
                            <div class="sidebar_icon_div">
                            <img  src="../assets/imgs/my_icons/hamburger-menu.svg">
                            </div>
                                <div class="sidebar_cell_text">FAQ Handling 
                                    </div>
                        </div>
                    </a>
                    <a class="nav_side_bar"  href="index.php">
                        
                        <div class="sidebar_link">
                            <div class="sidebar_icon_div">
                            <img  src="../assets/imgs/my_icons/hamburger-menu.svg">
                            </div>
                                <div class="sidebar_cell_text">Report 
                                    </div>
                        </div>
                    </a>
                     <a class="nav_side_bar" <?php echo $currentMainPage == 'EmpManage' ? 'active' : '' ?>  href="ViewEmp.php"> 
                        
                        <div class="sidebar_link">
                            <div class="sidebar_icon_div">
                            <img  src="../assets/imgs/my_icons/hamburger-menu.svg">
                            </div>
                                <div class="sidebar_cell_text">Employee Management 
                                    </div>
                        </div>
                    </a>
                    <a class="nav_side_bar"  href="index.php">
                        
                        <div class="sidebar_link">
                            <div class="sidebar_icon_div">
                            <img  src="../assets/imgs/my_icons/hamburger-menu.svg">
                            </div>
                                <div class="sidebar_cell_text">Offer handling 
                                    </div>
                        </div>
                    </a>
                    <a class="nav_side_bar" <?php echo $currentMainPage == 'NotiManage' ? 'active' : '' ?>  href="viewNotifications.php">
                        
                        <div class="sidebar_link">
                            <div class="sidebar_icon_div">
                            <img  src="../assets/imgs/my_icons/hamburger-menu.svg">
                            </div>
                                <div class="sidebar_cell_text">Notification handling 
                                    </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        
    </nav>

   

</body>

