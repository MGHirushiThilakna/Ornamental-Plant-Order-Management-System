<?php 
$currentMainPage ="products";
include "adminHeader_1.php"; 
include "..\classes\DBConnect.php";
include "..\classes\ProductController.php";
include "..\classes\CategoryController.php";

$db = new DatabaseConnection;


$productObj = new ProductController;
$categoryObj = new CategoryController;

?>
<nav class="navbar navbar-expand-lg myNavbarSub" style="" >
    <div class="container" style="padding-left:0px;margin-left:0px">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContentSub" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarSupportedContentSub">
        <ul class="navbar-nav myNavbarNavSub justify-content-center">
            <li class="nav-item mynavitemSub">
                <a class="nav-link mynavPMLinkSub <?php echo $currentSubPage == 'view' ? 'active' : '' ?>"  href="viewProduct.php">View</a>
            </li>
            <li class="nav-item mynavitemSub">
                <a class="nav-link mynavPMLinkSub <?php echo $currentSubPage == 'add' ? 'active' : '' ?>"  href="addProduct.php">New Product</a>
            </li>
            
        </ul>

    </div>
    </div>
</nav>