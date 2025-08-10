<?php
session_start();
if(!isset( $_SESSION['empID'])){
    header("location:../staffLogin.php");// i change it to stafflogin
} 
$empID = $_SESSION['empID'];
$currentMainPage ="staffHome";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Staff - Sunshine Plant House</title>
    
    <!-- Bootstrap-->
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css" />
    <!--fontawsome icons -->
    <link rel="stylesheet" href="../assets/icons/css/all.min.css">
    <!-- custom style -->
    <link rel="stylesheet" href="../assets/css/admin-navbar-style-A.css" />
    <link rel="icon" type="image/jpeg" href="..\assets/imgs/brand_logo.jpg">
    <!-- Sweet Alert 2-->
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
                            <div class="notification_counts"></div> 
                            </button>
                        </div>
                        <div class="dropdown dropdown-toggle" onclick="toggleDropdown()">
                            <img class="profile_pic" src="../assets/imgs/profile_pic.jpg" id="profile-pic">
                        </div>
                    <div class="dropdown-menu" id="dropdownMenu">
                    <a <?php echo $currentMainPage == 'profile' ? 'active' : '' ?>  href="staffProfile.php">Settings</a>
                        
                        <a  href="" id="signout"><i class="far fa-user-circle fa-lg" ></i> Logout</a>
                    </div>

                    </div>
    
</nav>
<script>                 // drop down menu f
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
			<a href="../staffPanel/index.php" style="height:56px" ><span class=" admin_panel_lbl" style="font-size:19px" >Staff Panel</span></a>
                <div class="side_bar">
                
                <a class="nav_side_bar" <?php echo $currentMainPage == 'Home' ? 'active' : '' ?>  href="index.php">        
                <div class="sidebar_link">
                    <div class="sidebar_icon_div">
                        <img  src="../assets/imgs/my_icons/hamburger-menu.svg">
                    </div>
                        <div class="sidebar_cell_text">Dashboard 
                            </div>
                </div>
                 </a>
                <a class="nav_side_bar" <?php echo $currentMainPage == 'stafforders' ? 'active' : '' ?>  href="viewStaffOrders.php">
                        
                        <div class="sidebar_link">
                            <div class="sidebar_icon_div">
                            <img  src="../assets/imgs/my_icons/hamburger-menu.svg">
                            </div>
                                <div class="sidebar_cell_text">Order Handling 
                                    </div>
                        </div>
                    </a>
                    

                    
                   
                </div>
            </div>
        </div>
        
    </nav>

   

</body>
<script src="..\assets\js\logoutProccess.js"></script>
