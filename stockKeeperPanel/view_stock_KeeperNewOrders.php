<?php 
$currentSubPage = "KeeperNewOrders";
include "stock_leeper_OrderHandling.php"; 
?>
<link rel="stylesheet" href="..\assets\css\delivery-order-style.css">
<link rel="stylesheet" href="..\assets\css\admin-navbar-style-A.css">
<link rel="stylesheet" href="..\assets\css\employee-style.css">

<body style="background-color:yellow">
<div class="d-flex mywrapper" id="wrapper" style="background-color:#f4faf8; margin-top:88px; margin-left:194px; height:100%">

<div class="card my-card-content" style="height:100%">
    <div class="card-header myOrdercardHeader" >New Order Info</div>
    <div class="card-body my-card-body" >
    
        <div class="card-body mycardBody" >
            <div class="row">
                <div class="col-lg-6 off-my-col">
                        <form class="d-flex search-box" id="##">
                           
                            <input class="form-control search-input " name="searchData" type="search" placeholder="Order ID " aria-label="Search">
                            
                            <button class="btn search-btn" type="submit"><i class="fas fa-search"></i></button>
                        </form>
                </div>
                
            </div>
        </div>
    
        <div class="table-responsive mt-3" >
        <table class="table" style="margin-top:20px; width:85%">
            <thead>
                <tr>
                    <th scope="col">Order ID</th>
                    <th scope="col">Order Date</th>
                    <th scope="col">Order Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody> 
                        
            </tbody> 
        </table>
       
    </div>
    </div>
    </div>  
</div>
</body>
<div class="modal fade" id="viewEachOrder" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width:80%">
            <!-- Content will be loaded here dynamically -->
        </div>
    </div>
</div>
    
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="..\assets\js\stock_keeper_order.js"></script>



<?php include "stock_keeper_footer.php";?>