$(document).ready(function(){
    // Initialize tables first
    try {
        loadNewAssignedOrderTable();
        loadReadyTable();
        loadCompletedOrder();
        loadDispatched();
        
        // Only call loadSearchOrders if search elements exist
        if ($('#orderIdSearch').length || 
            $('#orderDateSearch').length || 
            $('#orderStatus').length || 
            $('#deliveryType').length) {
            loadSearchOrders();
        }
    } catch (error) {
        console.error('Error during initialization:', error);
    }
    
    // Event handlers
    $('tbody').on('click', '.viewbtn', function() {
        const orderId = $(this).data('order-id');
        if (orderId) {
            viewOrderDetails(orderId);
        }
    });

    $('#searchOrders').on('click', function(e) {
        e.preventDefault();
        loadSearchOrders();
    });

    $('#resetSearch').on('click', function(e) {
        e.preventDefault();
        if ($('#orderIdSearch').length) $('#orderIdSearch').val('');
        if ($('#orderDateSearch').length) $('#orderDateSearch').val('');
        if ($('#orderStatus').length) $('#orderStatus').val('');
        if ($('#deliveryType').length) $('#deliveryType').val('');
        loadSearchOrders();
    });

    $('#orderIdSearch').on('keypress', function(e) {
        if(e.which === 13) {
            e.preventDefault();
            loadSearchOrders();
        }
    });
});

function loadSearchOrders() {
    Swal.fire({
        title: 'Loading...',
        html: '<div class="loading-spinner"></div>',
        showConfirmButton: false,
        allowOutsideClick: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    const searchParams = {
        task: "loadSearchOrders",
        orderId: $('#orderIdSearch').length ? $('#orderIdSearch').val().trim() : '',
        orderDate: $('#orderDateSearch').length ? $('#orderDateSearch').val() : '',
        orderStatus: $('#orderStatus').length ? $('#orderStatus').val() : '',
        deliveryType: $('#deliveryType').length ? $('#deliveryType').val() : ''
    };

    $.ajax({
        url: "stock_Keeper_Order-Ajax.php",
        method: "POST",
        data: searchParams,
        success: function(response) {
            Swal.close();
            $('tbody').html(response);
        },
        error: function() {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load orders. Please try again.'
            });
        }
    });
}
function loadOrderSummary() {
    $.ajax({
        url: 'stock_Keeper_Order-Ajax.php',
        method: 'POST',
        data: { task: 'getOrderSummary' },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Update the dashboard counts
                $('#newOrder-count').text(response.summary.newAssigned);
                $('#ready-count').text(response.summary.ready);
                $('#dispatched-count').text(response.summary.dispatched);
                $('#complete-count').text(response.summary.completed);
            } else {
                console.error('Failed to load summary:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Ajax request failed:', error);
        }
    });
}

function updateDashboardCounts(summary) {
    $('#newOrder-count').text(summary.newAssigned);
    $('#ready-count').text(summary.ready);
    $('#dispatched-count').text(summary.dispatched);
    $('#complete-count').text(summary.completed);
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
         url: "stock_Keeper_Order-Ajax.php",
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

function loadNewAssignedOrderTable(){
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
        url: "stock_Keeper_Order-Ajax.php",
        data: { task: "loadNewAssignedOrders" },
        method: "GET",
        success: function(response) {
            Swal.close();
            $('tbody').html(response);
        },
        error: function(xhr, status, error) {
            Swal.close();
            console.error('Ajax error:', error);
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
        url: "stock_Keeper_Order-Ajax.php",
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
        url: "stock_Keeper_Order-Ajax.php",
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

function loadCompletedOrder(){
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
        url: "stock_Keeper_Order-Ajax.php",
        data: { task: "loadCompletedORders" },
        type: "POST",
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
                url: "stock_Keeper_Order-Ajax.php",
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
                                loadNewAssignedOrderTable(); // Reload the orders table
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
                url: "stock_Keeper_Order-Ajax.php",
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
                                loadNewAssignedOrderTable(); // Reload the orders table
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
                url: "stock_Keeper_Order-Ajax.php",
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





   