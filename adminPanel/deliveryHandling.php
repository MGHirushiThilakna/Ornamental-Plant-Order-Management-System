<?php 
$currentMainPage ="delivery";
include "adminHeader_1.php";
?>

<nav class="navbar navbar-expand-lg myNavbarSub" >
    <div class="container">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContentSub" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarSupportedContentSub">
        <ul class="navbar-nav myNavbarNavSub justify-content-center">
            
            <li class="nav-item mynavitemSub">
                <a class="nav-link mynavLinkSub <?php echo $currentSubPage == 'ViewDrivers' ? 'active' : '' ?>"  href="viewDeliveryDriver.php">View Delivery Driver</a>
            </li>
            <li class="nav-item mynavitemSub">
                <a class="nav-link mynavLinkSub <?php echo $currentSubPage == 'AddDeliveryDriver' ? 'active' : '' ?>"  href="addDeliveryDriver.php">Add Delivery Driver</a>
            </li>
            
        </ul>

    </div>
    </div>
</nav>