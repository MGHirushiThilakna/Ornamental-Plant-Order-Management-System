<?php 
$currentMainPage ="stockOders";
include "stock_keeper_header.php";
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
                    <a class="nav-link mynavLinkSub <?php echo $currentSubPage == 'KeeperNewOrders' ? 'active' : '' ?>"  href="view_stock_KeeperNewOrders.php">New Orders</a>
                </li>
            
                <li class="nav-item mynavitemSub">
                    <a class="nav-link mynavLinkSub <?php echo $currentSubPage == 'readyOrders' ? 'active' : '' ?>"  href="stock_keeper_readyOrders.php">Ready Orders</a>
                </li>
                <li class="nav-item mynavitemSub">
                    <a class="nav-link mynavLinkSub <?php echo $currentSubPage == 'dispatchedOrders' ? 'active' : '' ?>"  href="stock_keeper_dispatched.php"> Dispatched Orders</a>
                </li>
                <li class="nav-item mynavitemSub">
                    <a class="nav-link mynavLinkSub <?php echo $currentSubPage == 'orderHistory' ? 'active' : '' ?>"  href="stock_Keeper_order_history.php"> Order History</a>
                </li>
            
            </ul>

        </div>
    </div>
    
</nav>

