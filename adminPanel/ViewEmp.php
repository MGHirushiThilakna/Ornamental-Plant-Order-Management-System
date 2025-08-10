<?php  
$currentSubPage="ViewEmp";
include "employeeManagement.php"; ?>
<link rel="stylesheet" href="..\assets\css\employee-style.css">
<!-- Sweet Alert 2 and JS-->
<script src="..\assets\sweetalert2\jquery-3.5.1.min.js"></script>
    <script src="..\assets\sweetalert2\sweetalert2.all.min.js"></script>

<div class="container mt-6 my-container " >
    <div class="card form-card mb-4 my serchcard" style=" width: auto;">
        <div class="card-body" style="margin-left:300px;">
            <div class="row">

                <div class="col-lg-6 off-my-col">
                        <form class="d-flex search-box" id="empSearch">
                            <select class="form-control search-input select-input" name="emp_col">
                                <option value ="Emp_ID" selected>Emp ID </option>
                                <option value ="FName">First Name </option>
                                <option value ="LName">Last Name </option>
                                <option value ="Job_Role">Job Role</option>
                                <option value ="Contact_No">Contact Number</option>
                                <option value ="Emp_status">Status</option>
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
                            <th scope="col">Emp ID</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Email Address</th>
                            <th scope="col">Job Role</th>
                            <th scope="col">Contact No</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id ="empDataShow">
                        
                    </tbody>
                </table>
            </div>
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
    </div>
</div>
<script src="..\assets\js\form-validation\admin-employeeRegistration.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php include "adminFooter.php"; ?>