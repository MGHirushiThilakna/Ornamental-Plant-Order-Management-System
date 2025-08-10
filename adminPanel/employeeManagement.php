<?php 
$currentMainPage ="EmpManage";
include "adminHeader_1.php"; ?>
<nav class="navbar navbar-expand-lg myNavbarSub" >
    
    <div class="container mysubContainer" >
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContentSub" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        
        <div class="collapse navbar-collapse" id="navbarSupportedContentSub">
            <ul class="navbar-nav myNavbarNavSub justify-content-center">
            <li class="nav-item mynavitemSub">
                    <a class="nav-link mynavLinkSub <?php echo $currentSubPage == 'ViewEmp' ? 'active' : '' ?>"  href="ViewEmp.php">View Employees</a>
                </li>
                <li class="nav-item mynavitemSub">
                    <a class="nav-link mynavLinkSub <?php echo $currentSubPage == 'addEmp' ? 'active' : '' ?>"  href="AddEmp.php">Register Employee</a>
                </li>
            
            </ul>

        </div>
    </div>
    
</nav>

