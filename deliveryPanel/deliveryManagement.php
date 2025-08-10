<?php 
$currentMainPage ="delivery";
include "driver-header.php";
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
                    <a class="nav-link mynavLinkSub <?php echo $currentSubPage == 'newOrderes' ? 'active' : '' ?>"  href="viewReadyOrders.php">New Orders</a>
                </li>
                <li class="nav-item mynavitemSub">
                    <a class="nav-link mynavLinkSub <?php echo $currentSubPage == 'checked' ? 'active' : '' ?>"  href="onTheWayOrders.php">On The Way Orders </a>
                </li>
                <li class="nav-item mynavitemSub">
                    <a class="nav-link mynavLinkSub <?php echo $currentSubPage == 'history' ? 'active' : '' ?>"  href="completeOrders.php">Order History</a>
                </li>
            </ul>

        </div>
    </div>
    
</nav>

