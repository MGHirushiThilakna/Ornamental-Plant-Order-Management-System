$(document).ready(function(){
    loadOrders();

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

    $('tbody').on('click',"#view",function(){
        var invoiceID = $(this).attr("data-invoiceID");
        var paymentID = $(this).attr("data-paymentID");
        var orderstatus = $(this).attr("data-orderStatus");
        loadViewOrder(invoiceID,paymentID,orderstatus);
    });

    $(document).on('click', '#confirmOrder', function() {
        var invoice = $(this).attr('data-invoiceID');
        var stat = "Confirmed";
        Swal.fire({
            title: 'Do you wish to Confirm this Order ?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
              if (result.isConfirmed) {
                updateOrderStatus(invoice,stat);
              }
          });

        
    });

    $(document).on('click', '#readyOrder', function() {
        var invoice = $(this).attr('data-invoiceID');
        var stat = "Ready";
        Swal.fire({
            title: 'Do you wish to Assign Ready Status to this Order ?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
              if (result.isConfirmed) {
                updateOrderStatus(invoice,stat);
              }
          });
    });

    $(document).on('click', '#dispatch', function() {
        var invoice = $(this).attr('data-invoiceID');
        var stat = "Dispatched";
        Swal.fire({
            title: 'Do you wish to Dispatch this Order ?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
              if (result.isConfirmed) {
                updateOrderStatus(invoice,stat);
              }
        });
    });

    $(document).on('submit','#AssignEMP',function(event){
        event.preventDefault();
        var empId = $('select[name="empIDSelect"]').val();
        var invoice = $('input[name="invoiceID"]').val();
        
        Swal.fire({
            title: 'Do you wish to Assign this Employee ?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
              if (result.isConfirmed) {
                assignEmp(empId,invoice);
              }
        });
    }); 

    $(document).on('submit','#AssignDriver',function(event){
        event.preventDefault();
        var driverID = $('select[name="DriverSelect"]').val();
        var paymentID = $('input[name="paymentID"]').val();
        console.log(driverID + " : " +paymentID);
        Swal.fire({
            title: 'Do you wish to Assign this Driver ?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
              if (result.isConfirmed) {
                assignDriver(driverID,paymentID);
              }
        });
    })

    $('[name="orderStatus"]').change(function(){
        var orderStatus = $(this).val();
        var pm = $('[name="paymentMethods"]').val();
        loadOrderOnCondition(pm,orderStatus);
    });
    $('[name="paymentMethods"]').change(function(){
        var paymentMethods = $(this).val();
        var os = $('[name="orderStatus"]').val();
        loadOrderOnCondition(paymentMethods,os);
    });

});

function loadOrderOnCondition(pm,os){
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
        url: "orderAjaxCalls.php",
        data: {pm:pm,os:os,task:"loadOnCondition"},
        success:function(response){
            Swal.close();
            $('tbody').html(response);
        }
    });  
}

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
        url: "orderAjaxCalls.php",
        data: {task:"loadTable"},
        success:function(response){
            Swal.close();
            $('tbody').html(response);
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
        task: "loadTable",
        orderId: $('#orderIdSearch').val().trim(),
        orderDate: $('#orderDateSearch').val(),
        orderStatus: $('#orderStatus').val(),
        paymentType: $('#paymentType').val()
    };

    // Make AJAX call
    $.ajax({
        url: "orderAjaxCalls.php",
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




