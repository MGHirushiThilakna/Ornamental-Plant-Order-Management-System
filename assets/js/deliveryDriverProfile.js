// function to disable the form  Save button is clicked
function disable(){
    $(".yourForm").prop("disabled", true);
    $('#btnSave').hide();
    $('#btnEdit').show();
}
// function to enable the form Edit button is clicked
function enable(){
    $(".yourForm").prop("disabled", false);
    $('input[name="email"]').prop("disabled",true);
    $('#btnEdit').hide();
    $('#btnSave').show();
}
// function to reset the User Info Errors
function resetStrError(){
    $('#strFnameError').html('');
    $('#strLnameError').html('');
    $('#strContactError').html('');
    $('#strVehicleNumError').html('');
}
// function to reset the Password change Info Errors
function resetStrError2(){
    $('#strCurrentPassError').html('');
    $('#strNewPassError').html('');
    $('#strRepeatNewPassError').html('');
}

// function to validate current password
function checkCurrentPassword(password,callback){
    $.ajax({
        url:"delProfileAjax.php",
        data:{password:password,task:"verifyPass"},
        success: function(response){
            if(parseInt(response) === 1){
                callback(true);
            }else{
                callback(false);
            }
        }
    });
}
// functions to show eye icon and disable eye icon
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

// function ready
$(document).ready(function(){
    showEyeICON(); // display current password  icons
    showEyeICON2();// display new password  icons
    showEyeICON3();// display Repeat New password  icons
    $('#CPeyeicon').on('click',function(e){         // function to chance type = password to text when icon clicked
        e.preventDefault();
        showEyeSlashIcon();
        $('input[name="currentPass"]').attr('type', 'text');
    });
    $('#CPeyeslashicon').on('click',function(e){    // function to chance type = text to password when icon clicked
        e.preventDefault();
        showEyeICON();
        $('input[name="currentPass"]').attr('type', 'password');
    });

    $('#CPeyeicon2').on('click',function(e){    // function to chance type = password to text when icon clicked
        e.preventDefault();
        showEyeSlashIcon2();
        $('input[name="NewPass"]').attr('type', 'text');
    });
    $('#CPeyeslashicon2').on('click',function(e){   // function to chance type = text to password when icon clicked
        e.preventDefault();
        showEyeICON2();
        $('input[name="NewPass"]').attr('type', 'password');
    });

    $('#CPeyeicon3').on('click',function(e){        // function to chance type = password to text when icon clicked
        e.preventDefault();
        showEyeSlashIcon3();
        $('input[name="RepeatNewPass"]').attr('type', 'text');
    });
    $('#CPeyeslashicon3').on('click',function(e){   // function to chance type = text to password when icon clicked
        e.preventDefault(); 
        showEyeICON3();
        $('input[name="RepeatNewPass"]').attr('type', 'password');
    });

    disable();  // disable the form by default
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
                enable(); // enable the user info form when edit button is clicked
            }
        });
    });

    $('#changePasswordForm').submit(function(event){ // password change form 
        event.preventDefault();

        // get values
        var currentPassword = $.trim($('input[name="currentPass"]').val());
        var NewPassword = $.trim($('input[name="NewPass"]').val());
        var RepeatNewPass = $.trim($('input[name="RepeatNewPass"]').val());

        // reset errors 
        $('.changeForm').removeClass('is-invalid');
        resetStrError2();
        var isCurrentPassValid = true;
        var isNewPassValid = true;
        var isRepeatNewPassValid = true;

        // validate CurrentPassword
        if(currentPassword === ''){
            $('#strCurrentPassError').html("This field is required");
            $('input[name="currentPass"]').addClass('is-invalid');
            isCurrentPassValid = false;
        }else{
            checkCurrentPassword(currentPassword,function(valid){
                if(valid){
                    isCurrentPassValid = true;
                }else{
                    $('#strCurrentPassError').html("Does not match with your current password");
                    $('input[name="currentPass"]').addClass('is-invalid');
                    isCurrentPassValid = false;
                }
            })
        }

        // validate NewPasswords
        if (NewPassword.length === 0 || RepeatNewPass.length === 0) {
            $('#strNewPassError').html('Please enter a valid password.');
            $('#strRepeatNewPassError').html('Please enter a valid password.');
            $('input[name="NewPass"]').addClass('is-invalid');
            $('input[name="RepeatNewPass"]').addClass('is-invalid');
            isNewPassValid = false;
            isRepeatNewPassValid = false;
        } else {
                    if (NewPassword !== RepeatNewPass) {
                        $('#strNewPassError').html('Password does not match.');
                        $('#strRepeatNewPassError').html('Password does not match.');
                        $('input[name="NewPass"]').addClass('is-invalid');
                        $('input[name="RepeatNewPass"]').addClass('is-invalid');
                        isNewPassValid = false;
                        isRepeatNewPassValid = false;
                    }
                }
        if (isCurrentPassValid && isNewPassValid && isRepeatNewPassValid) {
            $('.changeForm').removeClass('is-invalid');
            resetStrError2();
            $.ajax({
                url: "delProfileAjax.php",
                type:"post",
                data:{newPassword:NewPassword},
                success: function(response){
                    if(parseInt(response) === 1){
                        Swal.fire({icon:'success',title:'Done !',text:'Your Password is Changed Successfully'});
                        $('input[name="currentPass"]').val('');
                        $('input[name="NewPass"]').val('');
                        $('input[name="RepeatNewPass"]').val('');

                    }else{
                        console.log(response);
                        Swal.fire({icon:'warning',title:'Something is not right',text:''});
                    }
                }
            });
        }

    });

    $('#saveDriverINFO').submit(function(event) { // driver form
        event.preventDefault(); // Prevent the default form submission
    
        // Perform validation
        var fname = $.trim($('input[name="FName"]').val());
        var lname = $.trim($('input[name="LName"]').val());
        var contact = $.trim($('input[name="contact"]').val());
        var vehicleNum = $.trim($('input[name="VehicleNumber"]').val());
        var diver = $('#driverID').val();
        // reset errors 
        $('.yourForm').removeClass('is-invalid');
        resetStrError();
        var isFnameValid = true;
        var isLnameValid = true;
        var isVnumValid = true;
        var isContactValid = true;

      
        // Validate name
        if (fname === '') {
            $('#strFnameError').html("This field is required");
            $('input[name="FName"]').addClass('is-invalid');
            isFnameValid = false;
        }
    
        if (lname === '') {
            $('#strLnameError').html("This field is required");
            $('input[name="LName"]').addClass('is-invalid');
            isLnameValid = false;
        }     
        //validate address
        if (vehicleNum === '') {
            $('#strVehicleNumError').html("This field is required");
            $('input[name="VehicleNumber"]').addClass('is-invalid');
            isVnumValid = false;
        }else{
            var VehicleNumberPattern = /^[A-Za-z]{2,3}-\d{4}$/;
            if (!VehicleNumberPattern.test(vehicleNum)) {
                $('#strVehicleNumError').html('Invalid valid Vehicle Number Format');
                $('input[name="VehicleNumber"]').addClass('is-invalid');
                isVnumValid = false;
            }
        }
        // validate contact
        if(contact === ''){
            $('#strContactError').html("contact Number is required");
            $('input[name="contact"]').addClass('is-invalid');
            isContactValid = false;
        }else{
            if(parseInt(contact)){
                if(contact.length === 10){
                    isContactValid = true;
                }else{
                    $('#strContactError').html("contact number should have 10 digits");
                    $('input[name="contact"]').addClass('is-invalid');
                    isContactValid = false;
                }
            }else{
                $('#strContactError').html("Invalid values for contact Number");
                $('input[name="contact"]').addClass('is-invalid');
                isContactValid = false;
            }
        }
        
        if (isFnameValid && isLnameValid && isVnumValid && isContactValid) {
            $('.yourForm').removeClass('is-invalid')
            resetStrError();
           $.ajax({
              url: "delProfileAjax.php",
              data:{fname:fname,lname:lname,vNum:vehicleNum,diver:diver,contact:contact,task:"updateDriver"},
              success: function(response){
                  console.log("ajax success: "+response);
                  if(parseInt(response) === 1){
                      Swal.fire({icon:'success',title:'Done !',text:'Your Information was saved successfully'});
                      disable();
                  }else{
                      console.log(response);
                      Swal.fire({icon:'warning',title:'Something is not right',text:''});
                  }
              }
           });
        }
      });

});