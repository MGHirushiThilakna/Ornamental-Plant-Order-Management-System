$(document).ready(function(){
    loadOrders();
    loadAllOrders();
    loadConfirmedTable();
    loadDispatched();
    loadDriverReadyOrderTable();
    
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
        $('#deliveryType').val('');
        loadSearchOrders();
    });

    $('#orderIdSearch').on('keypress', function(e) {
        if(e.which === 13) {
            loadSearchOrders();
        }
    });
    
});
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
        deliveryType: $('#deliveryType').val()
    };

    // Make AJAX call
    $.ajax({
        url: "staff-order-Ajax.php",
        method: "POST",
        data: searchParams,
        success: function(response) {
            Swal.close();
            $('.history_tbody').html(response);
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
                url: "staff-order-Ajax.php",
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
$(document).on('click', '#readyBtn', function(event) {
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
        text: 'Do you want to mark this order as Ready?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Update'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "staff-order-Ajax.php",
                method: "POST",
                data: {
                    task: "updateToReady",
                    orderId: orderId
                },
                success: function(response) {
                    console.log('Raw response:', response); // Debug log
                    try {
                        const result = JSON.parse(response);
                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: result.message
                            }).then(() => {
                                loadStaffTable(); // Reload the orders table
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
            // If user cancels, show the modal again
            $('#viewEachOrder').modal('show');
        }
    });
});
$(document).on('click', '#dispatchnBtn', function(event) {
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
        text: 'Do you want to mark this order as Dispatch?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Update'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "staff-order-Ajax.php",
                method: "POST",
                data: {
                    task: "updateToDispatche",
                    orderId: orderId
                },
                success: function(response) {
                    console.log('Raw response:', response); // Debug log
                    try {
                        const result = JSON.parse(response);
                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: result.message
                            }).then(() => {
                                loadStaffTable(); // Reload the orders table
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
            // If user cancels, show the modal again
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
                url: "staff-order-Ajax.php",
                method: "POST",
                data: {
                    task: "updateToComplete",
                    orderId: orderId
                },
                success: function(response) {
                    console.log('Raw response:', response); // Debug log
                    try {
                        const result = JSON.parse(response);
                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: result.message
                            }).then(() => {
                                loadReadyTable(); // Reload the orders table
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
            // If user cancels, show the modal again
            $('#viewEachOrder').modal('show');
        }
    });
}

);

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
        url: "staff-order-Ajax.php",
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
        url: "staff-order-Ajax.php",
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

function loadConfirmedTable(){
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
        url: "staff-order-Ajax.php",
        data: { task: "loadConfirmedTable" },
        method: "GET",
        success: function(response) {
            Swal.close();
            $('.ConFirmtbody').html(response);
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
function loadAllOrders(){
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
        url: "staff-order-Ajax.php",
        data: { task: "loadAllOrders" },
        method: "GET",
        success: function(response) {
            Swal.close();
            $('.history_tbody').html(response);
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

function loadReadyTable(){
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
        url: "staff-order-Ajax.php",
        data: { task: "loadReadyTable" },
        method: "GET",
        success: function(response) {
            Swal.close();
            $('.Readytbody').html(response);
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

function loadDispatched(){
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
        url: "staff-order-Ajax.php",
        data: { task: "loadDispatchedTable" },
        method: "GET",
        success: function(response) {
            Swal.close();
            $('.dispatchtbody').html(response);
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
        url: "staff-order-Ajax.php",
        data: { task: "loadDispatchedTable" },
        method: "GET",
        success: function(response) {
            Swal.close();
            $('.dispatchtbody').html(response);
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


   