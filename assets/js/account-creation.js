function displayForm() {
  $.ajax({
    url: "customerRegForm.php",
    type: "post",
    data: { task: "create" },
    success: function (response) {
      $(".regForm").html(response);
      $("#register-form-modal").modal("show");
    },
  });
}

$(document).ready(function () {
  $("#login").submit(function (event) {
    var emp_email = $("#email").val();
    var password = $("#userpassword").val();

    // reset validating conditions
    $(".form-control").removeClass("is-invalid");
    var isEmailValid = true;
    var isPasswordValid = true;
    $("strUserError").html("");
    $("strpasswordError").html("");

    // Validate email
    if (emp_email === "") {
      $("#strUserError").html("Please enter your email.");
      $("#username").addClass("is-invalid");
      isEmailValid = false;
    } else {
      // Regular expression for email validation
      var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(emp_email)) {
        $("#strUserError").html("Invalid email entered.");
        $("#username").addClass("is-invalid");
        isEmailValid = false;
      }
    }
    // password
    if (password === "") {
      $("#strpasswordError").html("This field is required");
      $("#userpassword").addClass("is-invalid");
      isPasswordValid = false;
    }

    if (isEmailValid && isPasswordValid) {
      $("strUserError").html("");
      $("strpasswordError").html("");
      $(".form-control").removeClass("is-invalid");
    } else {
      event.preventDefault();
    }
  });
});
