$(document).ready(function () {

  $("#AddNoti").submit(function (event) {
    event.preventDefault();

    var title = $('input[name="NotiTitle"]');
    var notiDes = $('textarea[name="notifiDes"]');
    var image = $("#image01")[0].files[0];
    var noti_status = $('select[name="status"]').val(); 
    

    // Reset errors
    $(".myinputText, .myselect, .form-control").removeClass("is-invalid");
    $(".myFormGroup").removeClass("is-invalid");
    $("#strNTitleError, #strnotifiDesError, #strImgErr, #strStatusError").html(
      ""
    );

    var isValid = true;

    if (title.val().trim() === "") {
      $("#strNTitleError").html("This field is required");
      title.addClass("is-invalid");
      isValid = false;
    }

    if (notiDes.val().trim() === "") {
      $("#strnotifiDesError").html("This field is required");
      notiDes.addClass("is-invalid");
      isValid = false;
    }

    if (!image) {
      $("#image01").addClass("is-invalid");
      $("#strImgErr").html("This field is required");
      isValid = false;
    }

    if (noti_status === "0") {
      $('select[name="status"]').addClass("is-invalid");
      $("#strStatusError").html("Please select a Status of Notification");
      isValid = false;
  }

  // If validation is successful, proceed with AJAX request
  if (isValid) {
      var formData = new FormData();
      formData.append("title", title.val());
      formData.append("notiDes", notiDes.val());
      formData.append("image", image);
      formData.append("noti_status", noti_status);  // This will now be 'Active' or 'Inactive'
      formData.append("task", "addNoti");

      $.ajax({
        url: "notificationAjax.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          Swal.close();
          try {
            var result = JSON.parse(response);
            if (result.success) {
              Swal.fire("Success", result.message, "success");
              $("#AddNoti")[0].reset();
              // displayALLNotification(); // Uncomment if you have this function
            } else {
              Swal.fire("Error", result.message, "error");
            }
          } catch (e) {
            Swal.fire("Error", "An unexpected error occurred", "error");
          }
        },
        error: function (xhr, status, error) {
          Swal.close();
          Swal.fire(
            "Error",
            "An error occurred while processing your request",
            "error"
          );
        },
      });
    }
  });
 
    // Event listener for the Edit button
    $('.pop_btn').on('click', function() {
        var notiId = $(this).data('id'); // Get the noti_id from the button

        // Make an AJAX call to fetch notification details
        $.ajax({
            url: 'notificationAjax.php', 
            type: 'POST',
            data: { 
              noti_id: notiId,
              task: 'getNotiDetails' 
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    // Populate the modal fields with the fetched data
                    $('#editNotiId').val(data.noti.noti_id);
                    $('#editTitle').val(data.noti.noti_title);
                    $('#editDesc').val(data.noti.noti_desc);
                    $('#editStatus').val(data.noti.status);

                     // Set the image preview (check if the image is available)
                     if (data.noti.noti_img) {
                      $('#currentImage').attr('src', '../assets/imgs/notification/' + data.noti.noti_img);
                  } else {
                      $('#currentImage').attr('src', '../assets/imgs/notification/default.png'); // Set a default image or empty state
                  }
                } else {
                    alert('Failed to fetch notification details.');
                }
            },
            error: function() {
                alert('Error fetching notification details.');
            }
        });
    });
  });

    // Submit the edit form via AJAX
    $('#editNotiForm').on('submit', function(e) {
      e.preventDefault(); // Prevent the default form submission
  
      var formData = new FormData(this); // Create FormData object for file uploads
      formData.append('task', 'editNoti');
  
      $.ajax({
          url: 'notificationAjax.php', // Your PHP script to handle the update
          type: 'POST',
          data: formData,
          processData: false, // Important for FormData
          contentType: false, // Important for FormData
          success: function(response) {
              var result = JSON.parse(response);
              if (result.success) {
                  alert('Notification updated successfully.');
                  $('#editModal').modal('hide'); // Hide the modal
                  location.reload(); // Reload the page to see changes
              } else {
                  alert('Failed to update notification.');
              }
          },
          error: function() {
              alert('Error updating notification.');
          }
      });
});
  


function preview(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $("#previewImage").attr("src", e.target.result);
    };
    reader.readAsDataURL(input.files[0]);
  }
}


