$(document).ready(function () {
  displayALLEmpData(); // to Display empyoy records on a table

  // Employee Search bar
  $("#empSearch").submit(function (event) {
    event.preventDefault();
    var field = $('select[name="emp_col"]').val();
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
      url: "empAjax.php",
      data: {
        task: "search",
        field: field,
        data: searchData,
      },
      success: function (response) {
        Swal.close();
        $("#empDataShow").html(response);
      },
    });
  });

  // Employee Registration Form with Ajax request
  function checkEmailinDB(email, callback) {
    $.ajax({
      url: "empAjax.php",
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

  $("#AddEMP").submit(function (event) {
    event.preventDefault(); 

    var fname = $('input[name="FName"]');
    var lname = $('input[name="LName"]');
    var select_job = $('select[name="JobRole"]');
    var contact = $('input[name="contact"]');
    var email = $('input[name="email"]');
    var password = $('input[name="Password"]');
    var emp_status = $('select[name="status"]');

    $(".myinputText").removeClass("is-invalid");
    $(".myselect").removeClass("is-invalid");
   
    resetError();
    var isValid_fname = true;
    var isValid_lname = true;
    var isValid_job = true;
    var isValid_contact = true;
    var isValid_email = true;
    var isValid_pass = true;
    // var isValid_img = true;
    var isValid_status = true;

    // performing validation

    if (parseInt(select_job.val()) === 0) {
      select_job.addClass("is-invalid");
      $("#strJobError").html("Please select a Role");
      isValid_job = false;
    }

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
    // Email number
    if ($.trim(email.val()) == "") {
      $("#strEmailError").html("Please enter your email.");
      email.addClass("is-invalid");
      isValid_email = false;
    } else {
      // Regular expression for email validation
      var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test($.trim(email.val()))) {
        $("#strEmailError").html("Invalid  email entered.");
        email.addClass("is-invalid");
        isValid_email = false;
      } else {
        // ckecks whether the email exists or not
        checkEmailinDB(email.val(), function (exists) {
          if (exists) {
            $("#strEmailError").html("Email already exists.");
            email.addClass("is-invalid");
            isValid_email = false;
          } else {
            isValid_email = true;
          }
        });
      }
    }

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
      isValid_job &&
      isValid_contact &&
      isValid_email &&
      isValid_pass &&
      //isValid_img &&
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
        url: "empAjax.php",
        type: "post",
        data: {
          fname: fname.val(),
          lname: lname.val(),
          job: select_job.val(),
          contact: contact.val(),
          email: email.val(),
          pass: password.val(),
          status: emp_status.val(),
          task: "addEmp",
        },
        success: function (response) {
          Swal.close();

          if (response === "Email already exists.") {
            Swal.fire({
              icon: "warning",
              title: "Email already exists",
              text: "Please use a different email address.",
            });
          } else if (parseInt(response) === 1) {
            Swal.fire({
              icon: "success",
              title: "Done !",
              text: "Employee Account was created successfully",
            });
          } else {
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
  
  $("#empDataShow").on("click", ".btn-outline-danger", function () {
    var empId = $(this).closest("tr").find("td:first").text(); // Get the Emp_ID from the first column of the row
    
  });
 
});
function isValidEmail(email) {
  var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailPattern.test(email);
}

function loadEditEmp(empID, fname, lname, email, role, status) {
  $.ajax({
    url: "empAjax.php",
    data: {
      task: "editEmp",
      empID: empID,
      fname: fname,
      lname: lname,
      email: email,
      role: role,
      status: status,
    },
    success: function (response) {
      $(".modal-content").html(response);
      $("#viewEachEmp").modal("show");
    },
  });
}

function resetError() {
  $("#strFNameError").html("");
  $("#strLNameError").html("");
  $("#strJobError").html("");
  $("#strNumberError").html("");
  $("#strEmailError").html("");
  $("#strPasswordError").html("");
  // $("#strImgError").html("");
  $("#strStatusError").html("");
}

function displayALLEmpData() {  // correct ajax request 
  $.ajax({
    url: "empAjax.php",
    data: { task: "showAllData" },
    success: function (response) {
      $("#empDataShow").html(response);
    },
  });
}     
                             //correct admin employee update 
function loadEmpDetails(empID) { 
  var modalCategoryContent = document.getElementById("modalCategoryContent");
  console.log("Updating employee with ID:", empID);
  $.ajax({
      url: 'empAjax.php', 
      method: "POST",
      data: { emp_id: empID },
      success: function(response) {
          if (response) {
              var employee = JSON.parse(response); // Assuming the response is JSON
              modalCategoryContent.innerHTML = `
              <h2 class="modal-heading">Edit Employee details</h2>
              <div class="popup1"> 
                  <form onsubmit="event.preventDefault(); updateEmpFromAdmin();"> <!-- Prevent default form submission -->
                  <table>
                  <tr>
                          <input type="hidden" name="emp_id" value="${employee.Emp_ID }"> 
                      </tr>
                      <tr>
                          <td><label>Full name:</label></td>
                          <td> <input type="text" name="emp_name" readonly value="${employee.FName} ${employee.LName}"></td>
                      </tr>
                      <tr>
                      <td><label>Job Role:</label></td>
                                <td><select name="job_role" required>
                                    <option value="${employee.Job_Role}">${employee.Job_Role}</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Staff">Staff</option>
                                    <option value="Stock keeper">Stock keeper</option>
                                </select></td>
                          </tr>
                      <tr>
                          <td><label>Email Address:</label></td>
                          <td> <input type="text" name="email" required value="${employee.Email}"></td>
                      </tr>
                      <tr>
                      <td><label>Status:</label></td>
                                <td><select name="status" required>
                                    <option value="${employee.Emp_status}">${employee.Emp_status}</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select></td>
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


function confirmDelete(empID) { 
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
        url: "empAjax.php",
        data: {
          task: "deleteEmployee",
          empId: empID,
        },
        dataType: 'json',
        success: function (response) {
          if (response.status === 'success') {
            Swal.fire(
              "Deleted!",
              "The employee record has been deleted.",
              "success"
            );
            displayALLEmpData(); // Refresh the employee table
          } else {
            Swal.fire(
              "Error!",
              response.message,
              "error"
            );
          }
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
  function updateEmpFromAdmin() {
    const empID = document.querySelector('input[name="emp_id"]').value.trim();
    const jobRole = document.querySelector('select[name="job_role"]').value.trim();
    const email = document.querySelector('input[name="email"]').value.trim();
    const empStatus = document.querySelector('select[name="status"]').value.trim();

    // Validate inputs
    if (!empID || !jobRole || !email || !empStatus) {
        Swal.fire({
            title: 'Validation Error!',
            text: 'All fields are required.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }

    // Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        Swal.fire({
            title: 'Invalid Email!',
            text: 'Please enter a valid email address.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }

    // Show loading state
    Swal.fire({
        title: 'Processing...',
        text: 'Please wait while we update the employee details.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: 'empAjax.php',
        method: 'POST',
        dataType: 'json', // Expect JSON response
        data: {
            action: 'updateEmpByAdmin',
            emp_id: empID,
            job_role: jobRole,
            email: email,
            status: empStatus
        },
        success: function(response) {
            // Close loading state
            Swal.close();

            // Log response for debugging
            console.log("Server response:", response);

            // Handle the response
            if (response.status === 'success') {
                closeCategoryModal();
                Swal.fire({
                    title: 'Success!',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            } else {
              closeCategoryModal();
                Swal.fire({
                    title: 'Error!',
                    text: response.message || 'Failed to update employee details.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function(xhr, status, error) {
            // Close loading state
            Swal.close();

            // Log error for debugging
            console.error('Ajax Error:', {
                status: status,
                error: error,
                response: xhr.responseText
            });

            // Handle different types of errors
            let errorMessage = 'An error occurred while updating the employee.';
            
            try {
              closeCategoryModal();
                const errorResponse = JSON.parse(xhr.responseText);
                if (errorResponse.message) {
                    errorMessage = errorResponse.message;
                }
            } catch (e) {
              closeCategoryModal();
                console.error('Error parsing error response:', e);
            }
            Swal.fire({
                title: 'Error!',
                text: errorMessage,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}
//
