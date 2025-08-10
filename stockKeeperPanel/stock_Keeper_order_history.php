<?php 
$currentSubPage = "orderHistory";
include "stock_leeper_OrderHandling.php"; 
?>
<link rel="stylesheet" href="..\assets\css\delivery-order-style.css">
<link rel="stylesheet" href="..\assets\css\admin-navbar-style-A.css">
<link rel="stylesheet" href="..\assets\css\employee-style.css">

<body style="background-color:yellow">
<div class="d-flex mywrapper" id="wrapper" style="background-color:#f4faf8; margin-top:88px; margin-left:194px; height:100%">

<div class="card my-card-content" style="height:100%">
    <div class="card-header myOrdercardHeader" >Order History</div>
    <div class="card-body my-card-body" >
    
    <div class="card form-card mb-3 mt-3">
    <div class="card-body">
        <div class="row">
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="orderIdSearch" placeholder="Order ID">
                        <label for="orderIdSearch">Order ID</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="date" class="form-control" id="orderDateSearch">
                        <label for="orderDateSearch">Order Date</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <select class="form-select" id="orderStatus" name="orderStatus">
                            <option value="">All Status</option>
                            <option value="Confirmed">New Assigned</option>
                            <option value="Ready">Ready</option>
                            <option value="Dispatched">Dispatched</option>
                            <option value="Completed">Completed</option>
                        </select>
                        <label for="orderStatus">Order Status</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <select class="form-select" id="deliveryType" name="deliveryType">
                            <option value="">All Delivery Types</option>
                            <option value="Store Pickup">Store Pickup</option>
                            <option value="Delivery">Delivery</option>
                        </select>
                        <label for="deliveryType">Delivery Type</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12 text-end">
                    <button class="btn btn-primary" style="background-color:#3d8361" id="searchOrders">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                    <button class="btn btn-secondary" id="resetSearch">
                        <i class="fas fa-redo me-2"></i>Reset
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
    
        <div class="table-responsive mt-3" >
        <table class="table" style="margin-top:20px; width:85%">
            <thead>
                <tr>
                <table class="table" style="margin-top:20px; width:85%">
            <thead>
                <tr>
                    <th scope="col">Order ID</th>
                    <th scope="col">Order Date</th>
                    <th scope="col">Order Status</th>
                    <th scope="col">Delivery Type</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody class="history_tbody">
                        
            </tbody> 
                </tr>
             
        </table>
    </div>
    </div>
    </div>  
</div>
</body>
<div class="modal fade" id="viewEachOrder" data-bs-backdrop="static" data-bs-keyboard="false">     
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        </div>
    </div>
</div>
    
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="..\assets\js\stock_keeper_order.js"></script>



<?php include "stock_keeper_footer.php";?>