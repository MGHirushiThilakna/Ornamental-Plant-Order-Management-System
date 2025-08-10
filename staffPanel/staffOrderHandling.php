<?php 
$currentMainPage ="stafforders";
include "staffHeader.php";
include "..\classes\DBConnect.php";
$db = new DatabaseConnection;
?>
<nav class="navbar navbar-expand-lg myNavbarSub" >
    
    <div class="container mysubContainer" >
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContentSub" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        
        <div class="collapse navbar-collapse" id="navbarSupportedContentSub">
            <ul class="navbar-nav myNavbarNavSub justify-content-center">
            <li class="nav-item mynavitemSub">
                    <a class="nav-link mynavLinkSub <?php echo $currentSubPage == 'viewStaffOrders' ? 'active' : '' ?>"  href="viewStaffOrders.php">New Orders</a>
                </li>
                <li class="nav-item mynavitemSub">
                    <a class="nav-link mynavLinkSub <?php echo $currentSubPage == 'confOrders' ? 'active' : '' ?>"  href="confirmedOrders.php">Assigned Orders</a>
                </li>
                <li class="nav-item mynavitemSub">
                    <a class="nav-link mynavLinkSub <?php echo $currentSubPage == 'orderHistory' ? 'active' : '' ?>"  href="staff_orderHistory.php">All Orders</a>
                
            </ul>

        </div>
    </div>
    
</nav>

