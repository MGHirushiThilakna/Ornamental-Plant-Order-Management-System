<?php 
$currentSubPage="ViewDrivers";
include "deliveryHandling.php"; ?>
<head>
<link rel="stylesheet" href="..\assets\css\employee-style.css">
<!-- jQuery (required for Bootstrap 4) -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
<div class="container my-container mt-6">
    <div class="card form-card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-5 off-my-col" style="margin-left:290px">
                        <form class="d-flex search-box" id="delSearch">
                            <select class="form-control search-input select-input" name="del_col">
                                <option value ="Driver_ID" selected>Driver ID </option>
                                <option value ="FName">First Name </option>
                                <option value ="LName">Last Name </option>
                                <option value ="Vehicle_No">Vehicle Number</option>
                                <option value ="Contact_No">Contact Number</option>
                                <option value ="Status">status</option>
                            </select>
                            <input class="form-control search-input " name="searchData" type="search" placeholder="Search" aria-label="Search">
                            
                            <button class="btn search-btn" type="submit"><i class="fas fa-search"></i></button>
                        </form>
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="card table-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Driver ID</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Email Address</th>
                            <th scope="col">Vehicle Number</th>
                            <th scope="col">Contact No</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id ="DelDriverDataShow"> 
                        
                    </tbody>
                </table>
            </div>
            <div id="categoryModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeCategoryModal()">&times;</span>
                        <div id="modalCategoryContent"></div>
                    </div>
                 </div>
        </div>
    </div>
</div> 
<script src="..\assets\js\form-validation\admin-deliveryDriverReg.js"></script>
<?php include "adminFooter.php"; ?>