<?php 
include "customerSidePanal.php";
include "..\classes\DBConnect.php";
include "..\classes\CustomerController.php";
$db = new DatabaseConnection;
$cusObj = new CustomerController();

if(isset($_SESSION['Customer_ID'])){
    $userPassword = $_SESSION['CurrentPassword'];
    $customerID = $_SESSION['Customer_ID'];
}
?>
<head>
<script src="..\assets\sweetalert2\jquery-3.5.1.min.js"></script>
<script src="..\assets\sweetalert2\sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="..\assets\css\customer-edit.css">
  <style>
   
</style>
</head>
<body>
    <div class="flex flex-col lg:flex-row mydash" style="" >
      <main class="flex-1 lg:ml-2 mainDash" >
        <div class="bg-card p-4 rounded-lg shadow-md mb-3">
          <div class="flex items-center" style="margin-bottom:10px; text-aling:center; " >
           
            <div class="order-header" style="margin-bottom:0px; padding-bottom:10px;" >
            <div >
            <h2>Profile Setting</h2>
            <p style="font-size:16px">You can change your information here</p>
        </div>
            </div>
          </div>

          <div class="container" style="width:80%">
        <div class="profile-container">
            <!-- Left Section - Profile Info -->
            <div class="profile-section">
                <h3>Your Information</h3>
                <form id="saveCustomerINFO">
                    <?php 
                        $result = $cusObj -> getInfoForUpate($customerID);
                        if($result){
                           $customerData = $result -> fetch_assoc(); ?>
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
                    <input type="hidden" id="customerID" value="<?=$customerID?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating myFormFloating">
                            <label for="floatingInput">First Name</label>
                                <input type="text" class="form-control myinputText yourForm" name="fname" id="floatingInput" placeholder=" " value="<?=$customerData['FName']?>">
                                
                                <div id="strFnameError"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating myFormFloating">
                            <label for="floatingInput">Last Name</label>
                                <input type="text" class="form-control myinputText yourForm" name="lname" id="floatingInput" placeholder=" " value="<?=$customerData['LName']?>">
                                
                                <div id="strLnameError"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-floating myFormFloating">
                            <label for="floatingInput">Contact Number</label>
                                <input type="text" class="form-control myinputText yourForm" name="contact" id="floatingInput" placeholder=" " value="<?=$customerData['Contact_NO']?>">
                                
                                <div id="strContactError"></div>

                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-floating myFormFloating">
                            <label for="floatingTextarea">Address</label>
                                <textarea class="form-control myinputTextArea yourForm" name="address"  placeholder=" " id="floatingTextarea"><?=$customerData['Address']?></textarea>
                                
                                <div id="strAddressError"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="btn-col">
                                <button type ="submit"class="btn myBtn" id="btnEdit" onclick="" name="btnEdit">Change Information</button>
                            </div>
                        </div>
                    </div>
                </div>

                        <?php }
                    ?> 
                
                </form>
            </div>

            <!-- Right Section - Password Change -->
            <div class="password-section">
                <h2>Change Password</h2>
                <form id="changePasswordForm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-11">
                                <div class="form-floating myFormFloating">
                                <label for="floatingInput">Current Password</label>
                                    <input type="password" class="form-control myinputText changeForm" name="currentPass" id="floatingInput" placeholder=" ">
                                    
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
                                <label for="floatingInput">New Password</label>
                                    <input type="password" class="form-control myinputText changeForm" name="NewPass" id="floatingInput" placeholder=" ">
                                  
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
                                <label for="floatingInput">Repeat New Password</label>
                                    <input type="password" class="form-control myinputText changeForm" name="RepeatNewPass" id="floatingInput" placeholder=" " >
                                    
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

<script>
    $(document).ready(function(){
     showEyeICON();
    showEyeICON2();
    showEyeICON3();

    $('#saveCustomerINFO').submit(function(event) {
        event.preventDefault(); // Prevent the default form submission
    
        // Perform validation
        var fname = $.trim($('input[name="fname"]').val());
        var lname = $.trim($('input[name="lname"]').val());
        var contact = $.trim($('input[name="contact"]').val());
        var address = $('textarea[name="address"]').val();
        var customer = $('#customerID').val();

       if (validateCustomerForm(fname, lname, contact, address)) {
            Swal.fire({
                title: 'Confirm Update',
                text: 'Do you want to update your information?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Update Details'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "cus-profile-Ajax.php",
                        method: "POST",
                        data: {
                            fname: fname,
                            lname: lname,
                            customer: customer,
                            contact: contact,
                            address: address,
                            task: "updateCustomer"
                        },
                        success: function(response) {
                            if (parseInt(response) === 1) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Done!",
                                    text: "Your information was saved successfully"
                                });
                                ClearProfileFields();
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: "Failed to update information"
                                });
                            }
                        }
                    });
                }
            });
        }
    });

    $('#changePasswordForm').submit(function(event){
        event.preventDefault();

        var currentPassword = $.trim($('input[name="currentPass"]').val());
        var newPassword = $.trim($('input[name="NewPass"]').val());
        var repeatNewPass = $.trim($('input[name="RepeatNewPass"]').val());

        $('.changeForm').removeClass('is-invalid');
        resetStrError2();

        if (validatePasswordForm(currentPassword, newPassword, repeatNewPass)) {
            $.ajax({
                url: "cus-profile-Ajax.php",
                method: "POST",
                data: {
                    task: "verifyPass",
                    password: currentPassword
                },
                success: function(response) {
                    if (parseInt(response) === 1) {
                        // Current password verified, proceed with password change
                        $.ajax({
                            url: "cus-profile-Ajax.php",
                            method: "POST",
                            data: {
                                task: "updatePassword",  // Added task identifier
                                newPassword: newPassword,
                                customerID: $('#customerID').val() // Added customer ID
                            },
                            success: function(response) {
                                if (parseInt(response) === 1) {
                                    Swal.fire({
                                        icon: "success",
                                        title: "Success!",
                                        text: "Your password has been changed successfully"
                                    });
                                    // Clear password fields
                                    clearPasswordFields();
                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Error",
                                        text: "Failed to update password"
                                    });
                                }
                            }
                        });
                    } else {
                        $('#strCurrentPassError').html("Current password is incorrect");
                        $('input[name="currentPass"]').addClass('is-invalid');
                    }
                }
            });
        }
    });
});


    $('#CPeyeicon').on('click',function(e){
        e.preventDefault();
        showEyeSlashIcon();
        $('input[name="currentPass"]').attr('type', 'text');
    });
    $('#CPeyeslashicon').on('click',function(e){
        e.preventDefault();
        showEyeICON();
        $('input[name="currentPass"]').attr('type', 'password');
    });

    $('#CPeyeicon2').on('click',function(e){
        e.preventDefault();
        showEyeSlashIcon2();
        $('input[name="NewPass"]').attr('type', 'text');
    });
    $('#CPeyeslashicon2').on('click',function(e){
        e.preventDefault();
        showEyeICON2();
        $('input[name="NewPass"]').attr('type', 'password');
    });

    $('#CPeyeicon3').on('click',function(e){
        e.preventDefault();
        showEyeSlashIcon3();
        $('input[name="RepeatNewPass"]').attr('type', 'text');
    });
    $('#CPeyeslashicon3').on('click',function(e){
        e.preventDefault();
        showEyeICON3();
        $('input[name="RepeatNewPass"]').attr('type', 'password');
    });

    disable();
    $('#btnEdit').on('click',function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Do you wish Edit your Infromation?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                enable();
            }
        });
    });

    
    
// Validation Functions
function validateCustomerForm(fname, lname, contact, address) {
    var isValid = true;
    resetStrError();

    if (fname === '') {
        $('#strFnameError').html("This field is required");
        $('input[name="fname"]').addClass('is-invalid');
        isValid = false;
    }

    if (lname === '') {
        $('#strLnameError').html("This field is required");
        $('input[name="lname"]').addClass('is-invalid');
        isValid = false;
    }

    if (address === '') {
        $('#strAddressError').html("This field is required");
        $('textarea[name="address"]').addClass('is-invalid');
        isValid = false;
    }

    if (contact === '') {
        $('#strContactError').html("Contact Number is required");
        $('input[name="contact"]').addClass('is-invalid');
        isValid = false;
    } else if (!contact.match(/^\d{10}$/)) {
        $('#strContactError').html("Contact number should be 10 digits");
        $('input[name="contact"]').addClass('is-invalid');
        isValid = false;
    }

    return isValid;
}

function validatePasswordForm(currentPassword, newPassword, repeatNewPass) {
    var isValid = true;
    resetStrError2();

    if (currentPassword === '') {
        $('#strCurrentPassError').html("Current password is required");
        $('input[name="currentPass"]').addClass('is-invalid');
        isValid = false;
    }

    if (newPassword === '') {
        $('#strNewPassError').html("New password is required");
        $('input[name="NewPass"]').addClass('is-invalid');
        isValid = false;
    }

    if (repeatNewPass === '') {
        $('#strRepeatNewPassError').html("Please confirm new password");
        $('input[name="RepeatNewPass"]').addClass('is-invalid');
        isValid = false;
    }

    if (newPassword !== repeatNewPass) {
        $('#strNewPassError').html("Passwords do not match");
        $('#strRepeatNewPassError').html("Passwords do not match");
        $('input[name="NewPass"]').addClass('is-invalid');
        $('input[name="RepeatNewPass"]').addClass('is-invalid');
        isValid = false;
    }

    return isValid;
}
   
function resetStrError() {
    $('#strFnameError').html('');
    $('#strLnameError').html('');
    $('#strAddressError').html('');
    $('#strContactError').html('');
}

function resetStrError2() {
    $('#strCurrentPassError').html('');
    $('#strNewPassError').html('');
    $('#strRepeatNewPassError').html('');
}

function clearPasswordFields() {
    $('input[name="currentPass"]').val('');
    $('input[name="NewPass"]').val('');
    $('input[name="RepeatNewPass"]').val('');
}

function ClearProfileFields() {
    $('input[name="fname"]').val('');
    $('input[name="lname"]').val('');
    $('input[name="lname"]').val('');
    $('input[name="contact"]').val('');
    $('textarea[name="address"]').val('');
}
function showEyeICON(){
    $('#CPeyeslashicon').hide();
    $('#CPeyeicon').show();
}
function showEyeSlashIcon(){
    $('#CPeyeslashicon').show();
    $('#CPeyeicon').hide();
}
function showEyeICON2(){
    $('#CPeyeslashicon2').hide();
    $('#CPeyeicon2').show();
}
function showEyeSlashIcon2(){
    $('#CPeyeslashicon2').show();
    $('#CPeyeicon2').hide();
}
function showEyeICON3(){
    $('#CPeyeslashicon3').hide();
    $('#CPeyeicon3').show();
}
function showEyeSlashIcon3(){
    $('#CPeyeslashicon3').show();
    $('#CPeyeicon3').hide();
}

</script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
    
          
  </body>
  </html>