$(document).ready(function () {
    getCustomerinfo();
  
    $("#cusSearch").submit(function (event) {
      event.preventDefault();
      var field = $('select[name="cus_col"]').val();
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
        url: "../customerRegForm.php",
        method: "POST",
        data: {
          task: "search",
          field: field,
          searchData: searchData,
        },
        success: function (response) {
          Swal.close();
          $("#customerinfo").html(response);
        },
        error: function (xhr, status, error) {
          Swal.close();
          console.error("AJAX error: " + status + "\nError: " + error);
          $("#customerinfo").html(
            "<tr><td colspan='12'>Error searching Customer. Please try again.</td></tr>"
          );
        },
      });
    });

    // Open modal and load customer data
    $(document).on("click", "[data-toggle='modal']", function (e) {
      e.preventDefault();
      const customerId = $(this).data("customer-id");
  
      // Ajax request to fetch customer data
      $.ajax({
          url: "../customerRegForm.php",
          method: "POST",
          data: {
              task: "getCustomerDetails",
              customer_id: customerId
          },
          success: function (response) {
              try {
                  const data = JSON.parse(response);
                  if (data.success) {
                      $("#customer_id").val(data.customer.Customer_ID);
                      $("#full_name").val(`${data.customer.FName} ${data.customer.LName}`);
                      $("#pending_orders").val(data.customer.pending_orders);
                      $("#status").val(data.customer.Status.trim());
                      $("#editCustomerModal").modal("show");
                      console.log("Status received from server:", data.customer.Status);
                  } else {
                      Swal.fire("Error", data.message || "Failed to fetch customer details.", "error");
                  }
              } catch (err) {
                  console.error("Error parsing JSON:", err);
                  console.error("Server Response:", response);
                  Swal.fire("Error", "Unexpected response from the server.", "error");
              }
          },
          error: function (xhr, status, error) {
              console.error("AJAX Error:", error);
              console.error("Server Response:", xhr.responseText);
              Swal.fire("Error", "Failed to fetch customer details.", "error");
          }
      });
  });

  // Save changes
  $("#saveChanges").click(function () {
    const customerId = $("#customer_id").val();
    const status = $("#status").val();

    // Perform validation if needed
    if (!customerId || !status) {
        Swal.fire("Error", "Customer ID or Status cannot be empty.", "error");
        return;
    }

    // Show a loading state
    Swal.fire({
        title: "Saving Changes...",
        html: '<div class="loading-spinner"></div>',
        showConfirmButton: false,
        allowOutsideClick: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    // Perform the AJAX request
    $.ajax({
        url: "../customerRegForm.php", // Ensure this matches your server-side endpoint
        method: "POST",
        data: {
            task: "updateCustStatus",
            customer_id: customerId,
            status: status
        },
        success: function (response) {
            try {
                const data = JSON.parse(response);
                if (data.success) {
                    Swal.fire("Success", "Customer status updated successfully.", "success").then(() => {
                        location.reload(); // Refresh the page to reflect changes
                    });
                } else {
                    Swal.fire("Error", data.message || "Failed to update customer status.", "error");
                }
            } catch (err) {
                console.error("Error parsing JSON:", err);
                console.error("Server Response:", response);
                Swal.fire("Error", "Unexpected response from the server.", "error");
            }
        },
        error: function (xhr, status, error) {
            Swal.close();
            console.error("AJAX Error:", error);
            Swal.fire("Error", "Failed to save changes. Please try again.", "error");
        }
    });
});
});
  
  function getCustomerinfo() {
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
      url: "../customerRegForm.php",
      method: "POST",
      data: { task: "displayAllCustomers" },
      success: function (response) {
        Swal.close();
        $("#customerinfo").html(response);
      },
      error: function (xhr, status, error) {
        Swal.close();
        console.error("AJAX error: " + status + "\nError: " + error);
        $("#customerinfo").html(
          "<tr><td colspan='12'>Error loading Customer details. Please try again.</td></tr>"
        );
      },
    });
  }

 // End of document ready function
  