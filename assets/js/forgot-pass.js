var OTP;
var EmailAddress;
$(document).ready(function () {
  $("#passRecover").click(function () {
    $.ajax({
      url: "forgotPassword.php",
      type: "post",
      data: { task: "showEmailForm" },
      success: function (response) {
        $(".modal-forgt").html(response);
        $("#fogot-password-modal").modal("show");
      },
    });
  });
  //get forgot password email form inputs

  $(".modal-forgt").on("submit", "#FPassEmailForm", function (event) {
    event.preventDefault();
    var emailInput = $(this).find('input[name="forgot_pass_email"]');

    emailInput.removeClass("is-invalid");
    $("#strEmailError").html("");
    var isEmailFPValid = true;

    if (emailInput.val() === "") {
      $("#strEmailError").html("Please enter your email.");
      emailInput.addClass("is-invalid");
      isEmailFPValid = false;
    } else {
      // Regular expression for email validation
      var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(emailInput.val())) {
        $("#strEmailError").html("Invalid valid email entered.");
        emailInput.addClass("is-invalid");
        isEmailFPValid = false;
      } else {
        checkEmailinDBFP(emailInput.val(), function (exist) {
          //checks  user exist
          console.log(!exist);
          if (exist) {
            OTP = generateOTP();
            sendOTP(emailInput.val(), OTP);
            $("#fogot-password-modal").modal("hide");
            EmailAddress = emailInput.val();
            showOTPFORM();
          } else {
            $("#strEmailError").html("The user does not exsist");
            emailInput.addClass("is-invalid");
            isEmailFPValid = false;
          }
        });
      }
    }

    if (isEmailFPValid) {
      // if valid email
      emailInput.removeClass("is-invalid");
      $("#strEmailError").html("");
    }
  });

  $(".modal-otp").on("submit", "#formOTP", function (event) {
    //evaluate OTP
    event.preventDefault();
    var enteredOTP = $(this).find('input[name="forgot_pass_OTP"]');

    enteredOTP.removeClass("is-invalid");
    $("#strEmailError").html("");
    var isOTPValid = true;

    if (enteredOTP.val() === "") {
      $("#strEmailError").html("Please enter your OTP.");
      enteredOTP.addClass("is-invalid");
      isOTPValid = false;
    } else {
      if (parseInt(enteredOTP.val()) === parseInt(OTP, 10)) {
        isOTPValid = true;
        enteredOTP.addClass("is-valid");
      } else {
        $("#strEmailError").html("The OTP does not match");
        enteredOTP.addClass("is-invalid");
        isOTPValid = false;
      }
    }

    if (isOTPValid) {
      Swal.fire({
        title: "Done !",
        text: "Your OTP has been verified",
        icon: "success",
        showCancelButton: false,
        confirmButtonColor: "#686de0",
        cancelButtonColor: "#d33",
        confirmButtonText: "OK",
      }).then((result) => {
        if (result.isConfirmed) {
          $("#OTP-modal").modal("hide");
          showChangePasswordFORM();
        }
      });
    }
  });

  $(".modal-change-password").on("submit","#formChangePassword",
    function (event) {
      //evaluate changePass
      event.preventDefault();
      var newPass = $(this).find('input[name="forgot_pass_password"]');
      var repeatNewPass = $(this).find(
        'input[name="forgot_pass_repeat_password"]'
      );

      newPass.removeClass("is-invalid");
      repeatNewPass.removeClass("is-invalid");
      $("#strPasswordError").html("");
      $("#strRePasswordError").html("");
      var isPasswordValid = true;
      var isRePasswordValid = true;

      // Validate password
      if (newPass.val().length === 0 || repeatNewPass.val().length === 0) {
        $("#strPasswordError").html("Please enter a valid password.");
        $("#strRePasswordError").html("Please enter a valid password.");
        newPass.addClass("is-invalid");
        repeatNewPass.addClass("is-invalid");
        isPasswordValid = false;
        isRePasswordValid = false;
      } else {
        if (newPass.val() !== repeatNewPass.val()) {
          $("#strPasswordError").html("Password does not match.");
          $("#strRePasswordError").html("Password does not match.");
          newPass.addClass("is-invalid");
          repeatNewPass.addClass("is-invalid");
          isPasswordValid = false;
          isRePasswordValid = false;
        }
      }

      if (isPasswordValid && isRePasswordValid) {
        ChangePassword(newPass.val(), EmailAddress);
      }
    }
  );
});
 
function checkEmailinDBFP(email, callback) {
  $.ajax({
    url: "adminPanel/empAjax.php", // ajax request and call back
    data: { validateEmail: email },
    success: function (response) {
      if (parseInt(response) === 1) {
        callback(true);
      } else {
        callback(false);
      }
    },
  });
}
function generateOTP() {
  var otp = "";
  var digits = "0123456789";

  for (var i = 0; i < 6; i++) {
    otp += digits.charAt(Math.floor(Math.random() * digits.length));
  }

  return otp;
}
function sendOTP(email, otpvalue) {
  Swal.fire({
    title: "Sending OTP...",
    html: '<div class="loading-spinner"></div>',
    showConfirmButton: false,
    allowOutsideClick: false,
    willOpen: () => {
      Swal.showLoading();
    },
  });
  $.ajax({
    url: "forgotPassword.php",
    type: "post",
    data: { task: "sendOtp", otp: otpvalue, email: email },
    success: function (response) {
      if (parseInt(response) === 1) {
        Swal.close();
        Swal.fire({
          icon: "success",
          title: "OTP sent",
          text: "Check Your Mail",
          toast: true,
          position: "top-end",
          timer: 3000,
          timerProgressBar: true,
          showConfirmButton: false,
        });
      } else {
        console.log(response);
        Swal.fire({
          icon: "error",
          title: "OTP Sending Failed",
          text: "please try again!",
          toast: true,
          position: "top-end",
          timer: 3000,
          timerProgressBar: true,
          showConfirmButton: false,
        });
      }
    },
  });
}

function showOTPFORM() {
  $.ajax({
    url: "forgotPassword.php",
    type: "post",
    data: { task: "showVerifyOTP" },
    success: function (response) {
      $(".modal-otp").html(response);
      $("#OTP-modal").modal("show");
    },
  });
}

function showChangePasswordFORM() {
  $.ajax({
    url: "forgotPassword.php",
    type: "post",
    data: { task: "showChangePassword" },
    success: function (response) {
      $(".modal-change-password").html(response);
      $("#change-password").modal("show");
    },
  });
}

function ChangePassword(password, email) {
  $.ajax({
    url: "forgotPassword.php",
    type: "post",
    data: { task: "ChangePass", password: password, email: email },
    success: function (response) {
      if (parseInt(response) === 1) {
        Swal.fire({
          title: "Done !",
          text: "Your Password Changed successfully",
          icon: "success",
          showCancelButton: false,
          confirmButtonColor: "#686de0",
          cancelButtonColor: "#d33",
          confirmButtonText: "OK",
        }).then((result) => {
          if (result.isConfirmed) {
            $("#change-password").modal("hide");
          }
        });
      } else {
        console.log(response);
      }
    },
  });
}
