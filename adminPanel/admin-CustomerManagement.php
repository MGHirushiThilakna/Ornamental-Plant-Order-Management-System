<?php 
$currentSubPage="mainCat";
include "adminHeader_1.php"; 
include "..\classes\DBConnect.php";
include "..\classes\CategoryController.php";
$db = new DatabaseConnection;
?>
<head>
   
<!-- jQuery (required for Bootstrap 4) -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<link rel="stylesheet" href="..\assets\css\employee-style.css">
</head>
<div class="container my-container" style="margin-top:40px;">
    <div class="page-title">Customer Management</div>
        <div class="card mycard" style="margin-top:20px;width:50%; margin-left:21%; ">
        
            <div class="card-body mycard-body" style="border-radius:55px;  " >
                <div class="row">

                    <div class="col-lg-6 off-my-col" style="margin-left:30%; ">
                            <form class="d-flex search-box" id="cusSearch">
                                <select class="form-control search-input select-input" name="cus_col">
                                    <option value ="Customer_ID" selected>Cus ID </option>
                                    <option value ="FName">First Name </option>
                                    <option value ="LName">Last Name </option>
                                    <option value ="Email">Email Address</option>
                                    <option value ="Contact_No">Contact Number</option>
                                    <option value ="Cus_status">Status</option>
                                </select>
                                <input class="form-control search-input " name="searchData" type="search" placeholder="Search" aria-label="Search">
                                
                                <button class="btn search-btn" type="submit"><i class="fas fa-search"></i></button>
                                
                            </form>
                    </div>

                </div>
            </div>
        </div>v

        <div class="card table-card" style="margin-left:10px; margin-top:20px;">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Cus ID</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Email Address</th>
                            <th scope="col">Contact No</th>
                            <th scope="col">No of Completed orders</th>
                            <th scope="col">No of pending orders</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id ="customerinfo">
                    
                        </tbody>
                    </table>
            </div>
          </div>
        </div>

        <!-- Add this modal HTML to your page -->
    <div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editCustomerForm">
                        <input type="hidden" id="customer_id" name="customer_id" />
                        <div class="form-group">
                            <label for="full_name">Full Name:</label>
                            <input type="text" id="full_name" name="full_name" class="form-control" readonly />
                        </div>
                        
                        <div class="form-group">
                            <label for="pending_orders">Pending Orders:</label>
                            <input type="text" id="pending_orders" name="pending_orders"class="form-control" readonly />
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="Active">Active</option>
                                <option value="Banned">Banned</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveChanges">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="..\assets\js\admin-viewCustomer.js"></script>

</body>
</html>
        