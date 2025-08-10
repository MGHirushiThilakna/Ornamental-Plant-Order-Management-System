
$(document).ready(function(){
    

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

    $('#changePasswordForm').submit(function(event){
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
                url: "../customerRegForm.php",
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

  

});