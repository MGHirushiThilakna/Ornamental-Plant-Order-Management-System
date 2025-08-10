<?php 
$currentMainPage = "profile";
include "staffHeader.php";
include "..\classes\DBConnect.php";
include "..\classes\EmployeeController.php";
$db = new DatabaseConnection;
$empObj = new EmployeeController();
?>
<link rel="stylesheet" href="..\assets\css\customer-profile-style.css">

<div class="container my-container " >
    
    <div class="row my-row ">
    <div class="page-title">Profile Settings</div>
    
        <div class="col-md-6 mb-1 my-col">
            <div class="card">
                <form id="SaveStaffInfo">
                    <?php  
                        $result = $empObj -> getInfoForUpdate($empID);
                        if($result){
                           $EmpData = $result -> fetch_assoc(); ?>
                          
                           <div class="card-header mycardheader">Your Infomation</div>

                           <div class="card-header">
                                <div class="d-flex justify-content-center">
                                <div class="rounded-circle my-imgBox">
                                <input type="file" class="d-none" id="profilePictureInput">
                                    <label for="profilePictureInput" class="d-flex justify-content-center align-items-center h-100 w-100">
                                    <i class="fas fa-camera fa-2x"></i>
                                    </label>
                                </div>
                                </div>
                            </div>
                <div class="card-body">
                    <input type="hidden" id="empID" value="<?=$empID?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating myFormFloating">
                                <input type="text" class="form-control myinputText yourForm" name="FName" id="floatingInput" placeholder=" " value="<?=$EmpData['FName']?>">
                                <label for="floatingInput">First Name</label>
                                <div id="strFnameError"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating myFormFloating">
                                <input type="text" class="form-control myinputText yourForm" name="LName" id="floatingInput" placeholder=" " value="<?=$EmpData['LName']?>">
                                <label for="floatingInput">Last Name</label>
                                <div id="strLnameError"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-floating myFormFloating">
                                <input type="text" class="form-control myinputText yourForm" name="email" id="floatingInput" placeholder=" " value="<?=$EmpData['Email']?>">
                                <label for="floatingInput">Email Address</label>
                                <div id="strEmailError"></div>

                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-floating myFormFloating">
                                <input type="text" class="form-control myinputText yourForm" name="contact" id="floatingInput" placeholder=" " value="<?=$EmpData['Contact_No']?>">
                                <label for="floatingInput">Contact Number</label>
                                <div id="strContactError"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-floating myFormFloating">
                                <input type="text" class="form-control myinputText yourForm" name="jobRole" id="floatingInput" placeholder=" " value="<?=$EmpData['Job_Role']?>">
                                <label for="floatingInput">Job Role</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="btn-col">
                                <button class="btn myBtn" id="btnSave" type="submit" name="btnSave">Save</button>
                                <button class="btn myBtn" id="btnEdit" onclick="" name="btnEdit">Edit</button>
                            </div>
                        </div>
                    </div>
                </div>

                        <?php }
                    ?> 
                
                </form>
            </div>
        </div>
        <div class="col-md-6 my-col">
            <div class="card">
                <div class="card-header mycardheader">Change Your Password</div>
                <form id="changePasswordForm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-11">
                                <div class="form-floating myFormFloating">
                                    <input type="password" class="form-control myinputText changeForm" name="currentPass" id="floatingInput" placeholder=" ">
                                    <label for="floatingInput">Current Password</label>
                                    <div id="strCurrentPassError"></div>

                                </div>
                            </div>
                            <div class="col-1 myeye">
                                <span id="CPeyeicon" class="fa fa-eye "></span>
                                <span id="CPeyeslashicon" class="fa fa-eye-slash"></span>
                            </div>
                            
                        </div>
                        <div class="row mt-3">
                            <div class="col-11">
                                <div class="form-floating myFormFloating">
                                    <input type="password" class="form-control myinputText changeForm" name="NewPass" id="floatingInput" placeholder=" ">
                                    <label for="floatingInput">New Password</label>
                                    <div id="strNewPassError"></div>
                                </div>
                            </div>
                            <div class="col-1 myeye">
                                <span id="CPeyeicon2" class="fa fa-eye "></span>
                                <span id="CPeyeslashicon2" class="fa fa-eye-slash"></span>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-11">
                                <div class="form-floating myFormFloating">
                                    <input type="password" class="form-control myinputText changeForm" name="RepeatNewPass" id="floatingInput" placeholder=" " >
                                    <label for="floatingInput">Repeat New Password</label>
                                    <div id="strRepeatNewPassError"></div>
                                </div>
                            </div>
                            <div class="col-1 myeye">
                                <span id="CPeyeicon3" class="fa fa-eye "></span>
                                <span id="CPeyeslashicon3" class="fa fa-eye-slash"></span>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="btn-col">
                                    <button class="btn myBtn" id="btnChangePass" type="submit" name="btnChangePass">Change Password</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="..\assets\js\staffProfile.js"></script>

<?php include "staffFooter.php";?>