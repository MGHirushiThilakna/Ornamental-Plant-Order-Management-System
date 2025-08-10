$(document).ready(function () {

  $("#addDriver").submit(function (event) {
    event.preventDefault();
 
    var fname = $('input[name="FName"]');
    var lname = $('input[name="LName"]');
    var vNum = $('input[name="VehicleNo"]');
    var contact = $('input[name="contact"]');
    var email = $('input[name="email"]');
    var password = $('input[name="Password"]');
    var emp_status = $('select[name="status"]');
    // reset errors
    $(".myinputText").removeClass("is-invalid");
    $(".myselect").removeClass("is-invalid");
    resetError();
    var isValid_fname = true;
    var isValid_lname = true;
    var isValid_Vnum = true;
    var isValid_contact = true;
    var isValid_email = true;
    var isValid_pass = true;
    var isValid_status = true;
    // performing validation
    if (fname.val() == "") {
      $("#strFNameError").html("This field is required");
      fname.addClass("is-invalid");
      isValid_fname = false;
    }

    if (lname.val() == "") {
      $("#strLNameError").html("This field is required");
      lname.addClass("is-invalid");
      isValid_lname = false;
    }
    // vNum
    if (vNum.val() == "") {
      $("#strVnumError").html("This field is required");
      vNum.addClass("is-invalid");
      isValid_Vnum = false;
    } else {
      var VehicleNumberPattern = /^[A-Za-z]{2,3}-\d{4}$/;
      if (!VehicleNumberPattern.test($.trim(vNum.val()))) {
        $("#strVnumError").html("Invalid valid Vehicle Number Format");
        vNum.addClass("is-invalid");
        isValid_Vnum = false;
      }
    }
    // contact number
    if ($.trim(contact.val()) == "") {
      $("#strNumberError").html("This field is required");
      contact.addClass("is-invalid");
      isValid_contact = false;
    } else {
      if ($.isNumeric($.trim(contact.val()))) {
        if ($.trim(contact.val()).length == 10) {
          isValid_contact = true;
          $(".myinputText").removeClass("is-invalid");
          $("#strNumberError").html("");
        } else {
          $("#strNumberError").html("Contact number must have 10 digits");
          contact.addClass("is-invalid");
          isValid_contact = false;
        }
      } else {
        $("#strNumberError").html("Invalid characters");
        contact.addClass("is-invalid");
        isValid_contact = false;
      }
    }

    //Email Validation
    if ($.trim(email.val()) == "") {
      $("#strEmailError").html("Please enter valid email address.");
      email.addClass("is-invalid");
      isValid_email = false;
    } else {
      // Regular expression for email validation
      var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test($.trim(email.val()))) {
        $("#strEmailError").html("Invalid valid email entered.");
        email.addClass("is-invalid");
        isValid_email = false;
      }
    }
    // password validation
    if ($.trim(password.val()) == "") {
      $("#strPasswordError").html("This field is required");
      password.addClass("is-invalid");
      isValid_pass = false;
    }

    if (parseInt(emp_status.val()) === 0) {
      emp_status.addClass("is-invalid");
      $("#strStatusError").html("Please select a Status of Employee");
      isValid_status = false;
    }
    if (
      isValid_fname &&
      isValid_lname &&
      isValid_Vnum &&
      isValid_contact &&
      isValid_email &&
      isValid_pass &&
      isValid_status
    ) {
      Swal.fire({
        title: "Loading...",
        html: '<div class="loading-spinner"></div>',
        showConfirmButton: false,
        allowOutsideClick: false,
        willOpen: () => {
          Swal.showLoading();
        },
      });
      $.ajax({
        url: "admin_deliveryDriverAjax.php",
        type: "post",
        data: {
          fname: fname.val(),
          lname: lname.val(),
          VNum: vNum.val(),
          contact: contact.val(),
          email: email.val(),
          pass: password.val(),
          driver_status: emp_status.val(),
          task: "addDelDriver",
        },
        success: function (response) {
          if (parseInt(response) === 1) {
            Swal.fire({
              icon: "success",
              title: "Done !",
              text: "Your Account was created successfully",
            });
          } else {
            console.log(response);
            Swal.fire({
              icon: "warning",
              title: "Something is not right",
              text: "",
            });
          }
        },
      });
    }
  });
  
  displayALLData();
  
  $("#delSearch").submit(function (event) {
    event.preventDefault();
    var field = $('select[name="del_col"]').val();
    var searchData = $.trim($('input[name="searchData"]').val());

    Swal.fire({
      title: "Loading...",
      html: '<div class="loading-spinner"></div>',
      showConfirmButton: false,
      allowOutsideClick: false,
      willOpen: () => {
        Swal.showLoading();
      },
    });
    $.ajax({
      url: "admin_deliveryDriverAjax.php",
      data: {
        task: "search",
        field: field,
        data: searchData,
      },
      success: function (response) {
        Swal.close();
        $("#DelDriverDataShow").html(response);
      },
    });
  });
});
 
function resetError() {
  $("#strFNameError").html("");
  $("#strLNameError").html("");
  $("#strVnumError").html("");
  $("#strNumberError").html("");
  $("#strEmailError").html("");
  $("#strPasswordError").html("");
}

function displayALLData() {
  $.ajax({
    url: "admin_deliveryDriverAjax.php",
    data: { task: "showAllData" },
    success: function (response) {
      $("#DelDriverDataShow").html(response);
    },
  });
}

function loadDriverDetails(driver_Id) {
  var modalCategoryContent = document.getElementById("modalCategoryContent");
  console.log("Updating driver with ID:", driver_Id);
  $.ajax({
      url: 'admin_deliveryDriverAjax.php', // The URL to fetch category details
      method: "POST",
      data: { driverId: driver_Id },
      success: function(response) {
          if (response) {
              var driver = JSON.parse(response); // Assuming the response is JSON
              modalCategoryContent.innerHTML = `
              <h2 class="modal-heading">Edit Driver details</h2>
              <div class="popup1"> 
                  <form onsubmit="event.preventDefault(); updateDriverFromAdmin();"> <!-- Prevent default form submission -->
                  <table>
                  <tr>
                          <input type="hidden" name="driver_id" value="${driver.Driver_ID}"> 
                      </tr>
                      <tr>
                          <td><label>Full name:</label></td>
                          <td> <input type="text" name="name" readonly value="${driver.FName} ${driver.LName}"></td>
                      </tr>
                      <tr>
                          <td><label>Email Address:</label></td>
                          <td> <input type="text" name="email" required value="${driver.Email}"></td>
                      </tr>
                      <tr>
                      <td> <label>Vehicle number:</label></td>
                      <td>  <input type="text" name="vehicle_num" required value="${driver.	Vehicle_No}"></td>
                  </tr>
                      <tr>
                          <td colspan="2" style="text-align: center;"> <button type="submit">Save Changes</button></td>
                      </tr>
                  </form>
              </div>
              `;
              openCategoryModal();
          } else {
              modalCategoryContent.innerHTML = "Error: Failed to load category details.";
              openCategoryModal();
          }
      },
      error: function() {
          modalCategoryContent.innerHTML = "Error: Failed to load category details.";
          openCategoryModal();
      }
  });

}

function confirmDelete(driverID) { 
  Swal.fire({
    title: "Are you sure.....?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        type:"POST",
        url: "admin_deliveryDriverAjax.php",
        data: {
          task: "deleteDriver",
          driveId: driverID,
        },
        dataType: 'json',
        success: function (response) {
          closeCategoryModal();
          if (response.status === 'success') {
            Swal.fire(
              "Deleted!",
              "The Driver record has been deleted.",
              "success"
            );
            // Refresh the employee table
          } else {
            Swal.fire(
              "Error!",
              response.message,
              "error"
            );
          }
          location.reload();
        },
        error: function(xhr, status, error) {
          Swal.fire(
            "Error!",
            "Server communication error occurred.",
            "error"
          );
        }
      });
    }
  });
}

function openCategoryModal() {
  document.getElementById("categoryModal").style.display = "block";
}

function closeCategoryModal() {
  const modal = document.getElementById('categoryModal');
  if (modal) {
      modal.style.display = 'none'; // Hide the modal
  }
}
  function updateDriverFromAdmin() {
      var driverID = document.querySelector('input[name="driver_id"]').value;
      var email = document.querySelector('input[name="email"]').value;
      var vehicleNum = document.querySelector('input[name="vehicle_num"]').value;
      console.log("Updating Driver with ID:", driverID);
      
      $.ajax({
          url: 'admin_deliveryDriverAjax.php', // The URL to send the request to
          method: 'POST',
          data: {
              action: 'updateDriverByAdmin', // Action to identify the request
              driver_id: driverID,
              email: email,
              vehicle_Num: vehicleNum
          },
          
          success: function(response) {
            closeCategoryModal();
              var result = JSON.parse(response);
              if (result.status === 'success') {
                  alert(result.message); // Show success message
                  // Optionally, refresh the category list or close the modal
                  location.reload(); // Call a function to refresh the list
                  closeCategoryModal(); // Close the modal if needed
              } else {
                  alert(result.message); // Show error message
              }
          },
          error: function() {
              alert('An error occurred while updating the category.');
          }
      });
      console.log("Updating category with ID:", driverID);
  }
