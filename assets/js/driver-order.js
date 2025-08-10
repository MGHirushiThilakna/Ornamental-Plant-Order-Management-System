$(document).ready(function(){
    loadDriverReadyOrderTable();
    loadOnTheWayTable();
    loadCompleteTable();
    loadCODTable();


     $('tbody').on('click', '.viewbtn', function() {
        const orderId = $(this).data('order-id');
        viewOrderDetails(orderId);
    });
     // Initialize date inputs with current month's range
  const today = new Date();
  const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
  const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);

  $("#startDate").val(firstDay.toISOString().split("T")[0]);
  $("#endDate").val(lastDay.toISOString().split("T")[0]);

  // Load initial COD summary
  updateCODSummary();

  // Add event listeners for filters
  $("#startDate, #endDate, #paymentType").on("change", function () {
    loadFilteredOrders();
    updateCODSummary();
  });

  // Update search button click handler
  $("#searchBtn").on("click", function () {
    loadFilteredOrders();
  });

  // Add keypress event for search input
  $("#searchOrder").on("keypress", function (e) {
    if (e.which == 13) {
      // Enter key
      e.preventDefault();
      loadFilteredOrders();
    }
  });

  // Clear search
  $("#searchOrder").on("input", function () {
    if ($(this).val() === "") {
      loadFilteredOrders();
    }
  });
     
});
function loadFilteredOrders() {
    const startDate = $("#startDate").val();
    const endDate = $("#endDate").val();
    const paymentType = $("#paymentType").val();
    const searchOrder = $("#searchOrder").val().trim();
  
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
      url: "driver-order-Ajax.php",
      method: "GET",
      data: {
        task: "loadFilteredOrders",
        startDate: startDate,
        endDate: endDate,
        paymentType: paymentType,
        searchOrder: searchOrder,
      },
      success: function (response) {
        Swal.close();
        $(".completbody").html(response);
  
        // Update COD summary after search
        updateCODSummary();
      },
      error: function () {
        Swal.close();
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Failed to load filtered orders.",
        });
      },
    });
  }
  
  function updateCODSummary() {
    const startDate = $("#startDate").val();
    const endDate = $("#endDate").val();
  
    $.ajax({
      url: "driver-order-Ajax.php",
      method: "GET",
      data: {
        task: "getCODSummary",
        startDate: startDate,
        endDate: endDate,
      },
      success: function (response) {
        const data = JSON.parse(response);
        $("#totalCODOrders").text(data.totalOrders);
        $("#totalCODAmount").text(data.totalAmount.toFixed(2));
      },
      error: function () {
        console.error("Failed to load COD summary");
      },
    });
  }
  
$(document).on('click', '#checkedBtn', function(event) {
    event.preventDefault();
    var orderId = $(this).data('order-id') || //  from button data attribute
                 $('form#AssignEMP input[name="order_id"]').val(); //specific selector
    console.log('Order ID from button:', $(this).data('order-id')); // Debug
    
    if (!orderId) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Could not determine order ID'
        });
        return;
    }
    
    $('#viewEachOrder').modal('hide');
    
    Swal.fire({
        title: 'Confirm Status Update',
        text: 'Do you want to mark this order as Checked?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Update'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "driver-order-Ajax.php",
                method: "POST",
                data: {
                    task: "updateToOnTheWay",
                    orderId: orderId
                },
                dataType: 'json', 
                success: function(response) {
                    console.log('Raw response:', response); // Debug log
                    try {
                       
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: result.message
                            }).then(() => {
                                loadDriverReadyOrderTable(); // Reload the orders table
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message || 'Failed to update order status'
                            }).then(() => {
                                $('#viewEachOrder').modal('show');
                            });
                        }
                    } catch (e) {
                        console.error('Parse error:', e);
                        console.log('Raw response:', response);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An unexpected error occurred'
                        }).then(() => {
                            $('#viewEachOrder').modal('show');
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Ajax error:', error);
                    console.log('Response Text:', xhr.responseText); // Add this for debugging
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update order status'
                    }).then(() => {
                        $('#viewEachOrder').modal('show');
                    });
                }
            });
        } else {
            $('#viewEachOrder').modal('show');
        }
    });
});

$(document).on('click', '#completeBtn', function(event) {
    event.preventDefault();
    var orderId = $(this).data('order-id') || //  from button data attribute
                 $('form#AssignEMP input[name="order_id"]').val(); //specific selector
    console.log('Order ID from button:', $(this).data('order-id')); // Debug
    
    if (!orderId) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Could not determine order ID'
        });
        return;
    }
    
    $('#viewEachOrder').modal('hide');
    
    Swal.fire({
        title: 'Confirm Status Update',
        text: 'Do you want to mark this order as Complete?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Update'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "driver-order-Ajax.php",
                method: "POST",
                data: {
                    task: "updateToComplete",
                    orderId: orderId
                },
                dataType: 'json', 
                success: function(response) {
                    console.log('Raw response:', response); // Debug log
                    try {
                       
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: result.message
                            }).then(() => {
                                loadDriverReadyOrderTable(); // Reload the orders table
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message || 'Failed to update order status'
                            }).then(() => {
                                $('#viewEachOrder').modal('show');
                            });
                        }
                    } catch (e) {
                        console.error('Parse error:', e);
                        console.log('Raw response:', response);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An unexpected error occurred'
                        }).then(() => {
                            $('#viewEachOrder').modal('show');
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Ajax error:', error);
                    console.log('Response Text:', xhr.responseText); // Add this for debugging
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update order status'
                    }).then(() => {
                        $('#viewEachOrder').modal('show');
                    });
                }
            });
        } else {
            $('#viewEachOrder').modal('show');
        }
    });
});


function loadDriverReadyOrderTable(){
    Swal.fire({
        title: 'Loading...',
        html: '<div class="loading-spinner"></div>',
        showConfirmButton: false,
        allowOutsideClick: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    $.ajax({
        url: "driver-order-Ajax.php",
        data: { task: "loadDriverReadyOrderTable" },
        method: "GET",
        success: function(response) {
            Swal.close();
            $('tbody').html(response);
        },
        error: function() {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load orders.'
            });
        }
    });
}

function viewOrderDetails(orderId) {
   if (!orderId) {
        console.error('No order ID provided');
        return;
    }

    Swal.fire({
        title: 'Loading...',
        html: '<div class="loading-spinner"></div>',
        showConfirmButton: false,
        allowOutsideClick: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    $.ajax({
        url: "driver-order-Ajax.php",
        data: { 
            task: "getOrderDetails",
            orderId: orderId
        },
        method: "GET",
        success: function(response) {
        Swal.close();
        try {
            const data = JSON.parse(response);
            if(data.success) {
             $('.modal-content').html(data.html);
             $('#viewEachOrder').modal('show');
             } else {
                Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to load order details.'
                });
                }
            } catch(e) {
                console.error('JSON parsing error:', e);
                console.log('Raw response:', response);
                Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Invalid response from server.'
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.close();
            console.error('Ajax error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load order details.'
            });
        }
    });
}

function loadOnTheWayTable(){
    Swal.fire({
        title: 'Loading...',
        html: '<div class="loading-spinner"></div>',
        showConfirmButton: false,
        allowOutsideClick: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    $.ajax({
        url: "driver-order-Ajax.php",
        data: { task: "loadOnTheWayTable" },
        method: "GET",
        success: function(response) {
            Swal.close();
            $('.onTheWaytbody').html(response);
        },
        error: function() {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load orders.'
            });
        }
    });
}

function loadCompleteTable(){
    Swal.fire({
        title: 'Loading...',
        html: '<div class="loading-spinner"></div>',
        showConfirmButton: false,
        allowOutsideClick: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    $.ajax({
        url: "driver-order-Ajax.php",
        data: { task: "loadCompleteTable" },
        method: "GET",
        success: function(response) {
            Swal.close();
            $('.completbody').html(response);
        },
        error: function() {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load orders.'
            });
        }
    });
}

function loadCODTable(){
    Swal.fire({
        title: 'Loading...',
        html: '<div class="loading-spinner"></div>',
        showConfirmButton: false,
        allowOutsideClick: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    $.ajax({
        url: "driver-order-Ajax.php",
        data: { task: "loadWithCompleteTime" },
        method: "GET",
        success: function(response) {
            Swal.close();
            $('.cod').html(response);
        },
        error: function() {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load orders.'
            });
        }
    });
}



   