<?php 
include "classes\EmailController.php";
include "classes\EmployeeController.php";
include "classes\DBConnect.php";
$emailObj = new EmailController();
$db = new DatabaseConnection;
$empObj = new EmployeeController;
?>

<?php
if(isset($_POST['task']) && $_POST['task'] == 'showEmailForm'){ ?>
    <!-- modal header -->
    <div class="modal-header my-modal-header">
                <div class="modal-title My-regForm-title">Recover Your Account</div>
                <button type="button" class="btn-close my-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
    <!-- header close -->

           <!-- modal body -->
           <div class="modal-body">
                <div class="row img-row">
                    <div class="col-md-6 img-col">
                    <img src="assets\imgs\forgot_password.jpg" class="img-fluid reg-img" alt="...">
                    </div>
                    <div class="col-md-6">
                        <div class="card mt-2 ">
                            <div class="card-body">
                                <form id="FPassEmailForm">
                                    <div class="row mb-3">
                                        <div class="col-md-12 mb-2">
                                        <input type="text" name="forgot_pass_email" id="forgot_pass_email" class="form-control myinputText" placeholder="Email Address">
                                        <div id="strEmailError"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col"><button class="btn my-reg-btn" type="submit">Send OTP to Mail</button></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
           </div>
           <!-- body close -->
<?php }
?>

<?php 
if (isset($_REQUEST['task']) && $_REQUEST['task'] === 'sendOtp') {
    $email = $_REQUEST['email'];
    $otp = $_REQUEST['otp'];
    $emailObj->setOTPBody($otp);
    $emailObj->sendOTPCode($email);
    echo 1;
}
?>

<?php
if(isset($_POST['task']) && $_POST['task'] == 'showVerifyOTP'){ ?>
    <!-- modal header -->
    <div class="modal-header my-modal-header">
                <div class="modal-title My-regForm-title">Verify OTP</div>
                <button type="button" class="btn-close my-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
           <!-- header close -->

           <!-- modal body -->
           <div class="modal-body">
                <div class="row img-row">
                    <div class="col-md-6 img-col">
                    <img src="assets\imgs\forgot_password.jpg" class="img-fluid reg-img" alt="...">
                    </div>
                    <div class="col-md-6">
                        <div class="card mt-2 ">
                            <div class="card-body">
                                <form id="formOTP">
                                    <div class="row mb-3">
                                        <div class="col-md-12 mb-2">
                                        <input type="text" name="forgot_pass_OTP" id="forgot_pass_OTP" class="form-control myinputText" placeholder="Enter Your OTP">
                                        <div id="strOTPError"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col"><button class="btn my-reg-btn" type="submit">Verify OTP</button></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
           </div>
           <!-- body close -->
<?php }
?> 

<?php
if(isset($_POST['task']) && $_POST['task'] == 'showChangePassword'){ ?>
    <!-- modal header -->
    <div class="modal-header my-modal-header">
                <div class="modal-title My-regForm-title">Change Password</div>
                <button type="button" class="btn-close my-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
           <!-- header close -->

           <!-- modal body -->
           <div class="modal-body">
                <div class="row img-row">
                    <div class="col-md-6 img-col">
                    <img src="assets\imgs\forgot_password.jpg" class="img-fluid reg-img" alt="...">
                    </div>
                    <div class="col-md-6">
                        <div class="card mt-2 ">
                            <div class="card-body">
                                <form id="formChangePassword">
                                    <div class="row mb-3">
                                        <div class="col-md-12 mb-2">
                                        <input type="password" name="forgot_pass_password" id="forgot_pass_password" class="form-control myinputText" placeholder="New Password">
                                        <div id="strPasswordError"></div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12 mb-2">
                                        <input type="password" name="forgot_pass_repeat_password" id="forgot_pass_repeat_password" class="form-control myinputText" placeholder="Repeat New Password">
                                        <div id="strRePasswordError"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col"><button class="btn my-reg-btn" type="submit">Change Password</button></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
           </div>
           <!-- body close -->
<?php }
?> 
<?php 
if(isset($_POST['task']) && $_POST['task'] ==='ChangePass'){
    $data = [
        "email"=>mysqli_real_escape_string($db->conn,$_REQUEST['email']),
        "password"=>mysqli_real_escape_string($db->conn,$_REQUEST['password'])
    ];
    $result = $empObj -> changeEmpPasswordByEmail($data);
    if($result){
        echo 1;
    }else{
        echo 0;
    }
}
?>