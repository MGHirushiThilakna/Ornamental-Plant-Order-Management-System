<?php 
$currentSubPage = "history";
include "deliveryManagement.php"; 
?>
<link rel="stylesheet" href="..\assets\css\delivery-order-style.css">
<link rel="stylesheet" href="..\assets\css\admin-navbar-style-A.css">
<link rel="stylesheet" href="..\assets\css\employee-style.css">

<body style="background-color:yellow">
<div class="d-flex mywrapper" id="wrapper" style="background-color:#f4faf8; margin-top:88px; margin-left:194px; height:100%">

<div class="card my-card-content" style="height:100%">
    <div class="card-header myOrdercardHeader" >Delivery History</div>
    <div class="card-body my-card-body" >
    
        <div class="card-body mycardBody" style="margin-left:70px;">
        <div class="row g-3 align-items-center mb-3">
         <!-- Date Range Selection -->
            <div class="col-lg-3">
              <label for="startDate" class="form-label">From Date:</label>
               <input type="date" class="form-control" id="startDate" name="startDate">
             </div>
              <div class="col-lg-3">
                 <label for="endDate" class="form-label">To Date:</label>
                  <input type="date" class="form-control" id="endDate" name="endDate">
              </div>

            <!-- Search Bar -->
              <div class="col-lg-4" style="margin-left: 155px;">
                 <label for="searchOrder" class="form-label">Search Order:</label>
                  <div class="d-flex">
                    <input class="form-control search-input" id="searchOrder" name="searchData"
                         type="search" placeholder="Order ID" aria-label="Search">
                    <button class="btn search-btn" id="searchBtn" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                   </div>
                  </div>
                </div>

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Total COD Orders</h6>
                                    <h4 class="card-text" id="totalCODOrders">0</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Total COD Amount (Rs.)</h6>
                                    <h4 class="card-text" id="totalCODAmount">0.00</h4>
                                </div>
                            </div>
                        </div>
                    </div>
        </div>
    
        <div class="table-responsive mt-3" >
        <table class="table" style="margin-top:20px; width:85%">
            <thead>
                <tr>
                    <th scope="col">Order ID</th>
                    <th scope="col">Completed Date</th>
                    <th scope="col">Payment Type</th>
                    <th scope="col">Total (Rs.)</th>
                    <th scope="col">Order Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody class="completbody">
                        
            </tbody> 
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
<script src="..\assets\js\driver-order.js"></script>
