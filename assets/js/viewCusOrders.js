$(document).ready(function(){
    loadOrders();

     $('tbody').on('click', '.viewbtn', function() {
        const orderId = $(this).data('order-id');
        viewOrderDetails(orderId);
    });

    $('#searchOrders').on('click', function() {
        loadSearchOrders();
    });

    $('#resetSearch').on('click', function() {
        $('#orderIdSearch').val('');
        $('#orderDateSearch').val('');
        $('#orderStatus').val('');
        $('#paymentType').val('');
        loadSearchOrders();
    });

    $('#orderIdSearch').on('keypress', function(e) {
        if(e.which === 13) {
            loadSearchOrders();
        }
    });
    
});
 
$(document).on('submit', '#AssignEMP', function(event) {
    event.preventDefault();
    var orderId = $('input[name="order_id"]').val();
    var empId = $('select[name="empIDSelect"]').val();
    var driverId = $('select[name="DriverSelect"]').val();
    var addressType = $('input[name="addressType"]').val();
    
    // Validate required fields based on address type
    if (!empId) {
         // Hide modal before showing alert
         $('#viewEachOrder').modal('hide');
        Swal.fire({
            icon: 'warning', 
            title: 'Please select an employee' ,
             text: 'An employee must be assigned to the order'
            }).then(() => {
                // Show modal again after alert is closed
                $('#viewEachOrder').modal('show');
            });
        return;
    }
    
     // For delivery orders, check if driver is selected
     if (addressType !== 'Store Pickup') {
        var driverId = $('select[name="DriverSelect"]').val();
        if (!driverId) {
            $('#viewEachOrder').modal('hide');
            Swal.fire({
                icon: 'warning',
                title: 'Please select a driver',
                text: 'A delivery driver must be assigned for delivery orders'
            }).then(() => {
                $('#viewEachOrder').modal('show');
            });
            return;
        }
    }

        // Prepare data object based on address type
        var requestData = {
            task: "assignOrder",
            orderId: orderId,
            empId: empId,
            addressType: addressType
        };

        // Add driverId only for delivery orders
    if (addressType !== 'Store Pickup') {
        requestData.driverId = $('select[name="DriverSelect"]').val();
    }
    $('#viewEachOrder').modal('hide');
    Swal.fire({
        title: 'Confirm Assignment',
        text: addressType === 'Store Pickup' ? 
              'Do you want to assign this employee to the order?' :
              'Do you want to assign this employee and driver to the order?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Assign'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "order-Ajax.php",
                method: "POST",
                data: requestData,
                success: function(response) {
                    try {
                        const result = JSON.parse(response);
                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: result.message
                            }).then(() => {
                                $('#viewEachOrder').modal('hide');
                                loadOrders(); // Reload the orders table
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message || 'Failed to assign order'
                            }).then(() => {
                                $('#viewEachOrder').modal('show');
                            });
                        }
                    } catch (e) {
                        console.error(e);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An unexpected error occurred'
                        }).then(() => {
                            $('#viewEachOrder').modal('show');
                        });
                    }
                }
            });
        }else {
            // If user cancels, show the modal again
            $('#viewEachOrder').modal('show');
        }
    });
});

function loadOrders(){
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
        url: "order-Ajax.php",
        data: { task: "loadTable" },
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

function loadSearchOrders() {
    // Show loading spinner
    Swal.fire({
        title: 'Loading...',
        html: '<div class="loading-spinner"></div>',
        showConfirmButton: false,
        allowOutsideClick: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    // Get search parameters
    const searchParams = {
        task: "loadSearchTable",
        orderId: $('#orderIdSearch').val().trim(),
        orderDate: $('#orderDateSearch').val(),
        orderStatus: $('#orderStatus').val(),
        paymentType: $('#paymentType').val()
    };

    // Make AJAX call
    $.ajax({
        url: "order-Ajax.php",
        method: "POST",
        data: searchParams,
        success: function(response) {
            Swal.close();
            $('tbody').html(response);
        },
        error: function() {
            Swal.close();
            Swal.fire(
                'Error',
                'Failed to load orders. Please try again.',
                'error'
            );
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
        url: "order-Ajax.php",
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

function assignOrder(orderId, empId, driverId, addressType) {
    $.ajax({
        url: "order-Ajax.php",
        method: "POST",
        data: {
            task: "assignOrder",
            orderId: orderId,
            empId: empId,
            driverId: driverId,
            addressType: addressType
        },
        success: function(response) {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: result.message
                    }).then(() => {
                        $('#viewEachOrder').modal('hide');
                        loadOrders(); // Reload the orders table
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message
                    });
                }
            } catch (e) {
                console.error(e);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred'
                });
            }
        }
    });
}
   