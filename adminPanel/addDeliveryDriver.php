<?php 
$currentSubPage="AddDeliveryDriver";
include "deliveryHandling.php"; ?>
<link rel="stylesheet" href="..\assets\css\employee-style.css">

<div class="container myMainContainer" >
    <div class="card mt-2 " style="margin-left:100px;margin-top:0px;margin-righ:100px">
        <div class="card-header mycardheader">Add New Driver</div>
        <div class="card-body mycardbody" >

            <form id="addDriver" style="width: 700px; margin-left:100px;  ">
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-floating myFormFloating">
                            <input type="text" class="form-control myinputText" name="FName" id="floatingInput" placeholder=" ">
                            <label for="floatingInput">First Name</label>
                            <div id="strFNameError"></div> <!-- First Name validation error -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating myFormFloating">
                            <input type="text" class="form-control myinputText" name="LName" id="floatingInput" placeholder=" " >
                            <label for="floatingInput">Last Name</label>
                            <div id="strLNameError"></div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    
                    <div class="col-md-6">
                        <div class="form-floating myFormFloating">
                            <input type="text" class="form-control myinputText" name="contact" id="floatingInput" placeholder=" ">
                            <label for="floatingInput">Contact Number</label>
                            <div id="strNumberError"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                            <div class="form-floating myFormFloating">
                                <input type="text" class="form-control myinputText" name="VehicleNo" id="floatingInput" placeholder=" ">
                                <label for="floatingInput">Vehicle Number</label>
                                <div id="strVnumError"></div>
                            </div>
                        </div>
                    
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-floating myFormFloating">
                            <input type="text" class="form-control myinputText" name="email" id="floatingInput" placeholder=" ">
                            <label for="floatingInput">Email Address</label>
                            <div id="strEmailError"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating myFormFloating">
                            <input type="text" class="form-control myinputText" name="Password" id="floatingInput" placeholder=" ">
                            <label for="floatingInput">Password</label>
                            <div id="strPasswordError"></div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                <div class="col-md-6" >
                        <div class="form-floating myFormFloating">
                        <select class="form-select myselect" id="floatingSelect" name="status">
                                <option value="0">Select</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>    
                            </select>
                            <label for="floatingSelect">Status</label>
                            <div id="strStatusError"></div>
                        </div>
                    </div>
                    
                </div>
                <div class="row mt-3">
                    <div class="col-md-11">
                        <div class="btn-col">
                            <button class="btn myBtn" type="submit" name="btnEmp">Add Driver</button>
                        </div>
                        
                    </div>
                </div>
                
            </form>
           
            
    </div>
        </div>
    </div>
</div>
<script src="..\assets\js\form-validation\admin-deliveryDriverReg.js"></script>
<?php include "adminFooter.php"; ?>