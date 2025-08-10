<?php 
$currentMainPage ="NotiManage";
include "adminHeader_1.php"; ?>
<nav class="navbar navbar-expand-lg myNavbarSub" >
    <div class="container mysubContainer" >
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContentSub" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarSupportedContentSub">
        <ul class="navbar-nav myNavbarNavSub justify-content-center">
        <li class="nav-item mynavitemSub">
                <a class="nav-link mynavLinkSub <?php echo $currentSubPage == 'ViewNoti' ? 'active' : '' ?>"  href="viewNotifications.php">View Notification</a>
            </li>
            <li class="nav-item mynavitemSub">
                <a class="nav-link mynavLinkSub <?php echo $currentSubPage == 'addNoti' ? 'active' : '' ?>"  href="admin-addNotification.php"> Add Notifications</a>
            </li>
            
        </ul>

    </div>
    </div>
</nav>