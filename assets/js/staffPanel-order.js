$(document).ready(function () {
  $(document).on("click", "#confirmOrder", function () {
    var invoice = $(this).attr("data-invoiceID");
    var stat = "Confirmed";
    Swal.fire({
      title: "Do you wish to Confirm this Order ?",
      icon: "info",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes",
    }).then((result) => {
      if (result.isConfirmed) {
        updateOrderStatus(invoice, stat);
      }
    });
  });
  $(document).on("click", "#readyOrder", function () {
    var invoice = $(this).attr("data-invoiceID");
    var stat = "Ready";
    Swal.fire({
      title: "Do you wish to Assign Ready Status to this Order ?",
      icon: "info",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes",
    }).then((result) => {
      if (result.isConfirmed) {
        updateOrderStatus(invoice, stat);
      }
    });
  });
  $(document).on("click", "#complete", function () {
    var invoice = $(this).attr("data-invoiceID");
    var stat = "Completed";
    Swal.fire({
      title: "Do you wish to Complete this Order ?",
      icon: "info",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes",
    }).then((result) => {
      if (result.isConfirmed) {
        updateOrderStatus(invoice, stat);
      }
    });
  });
  $("#showOrders").on("click", "#view", function () {
    var invoiceID = $(this).attr("data-invoiceID");
    var paymentID = $(this).attr("data-paymentID");
    var orderstatus = $(this).attr("data-orderStatus");
    loadViewOrder(invoiceID, paymentID, orderstatus);
  });

  loadDispatchedOrders();
  $("#newOrders").click(function () {
    console.log("clicked");
    loadDispatchedOrders();
  });
  $("#orderHistory").click(function () {
    console.log("clicked");
    loadCompleteOrders();
  });
});

function loadDispatchedOrders() {
  var staffID = $("#showOrders").attr("data-staffID");
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
    url: "staffAjax.php",
    data: { task: "showOrders", staffID: staffID },
    success: function (response) {
      Swal.close();
      $("#showOrders").html(response);
    },
  });
}

function loadCompleteOrders() {
  var staffID = $("#showOrders").attr("data-staffID");
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
    url: "staffAjax.php",
    data: { task: "showRCOrders", staffID: staffID },
    success: function (response) {
      Swal.close();
      $("#showOrders").html(response);
    },
  });
}
function loadViewOrder(invoiceID, paymentID, orderStatus) {
  $.ajax({
    url: "staffAjax.php",
    data: {
      task: "viewOrder",
      invoiceID: invoiceID,
      paymentID: paymentID,
      orderStatus: orderStatus,
    },
    success: function (response) {
      $(".modal-content").html(response);
      $("#viewEachOrder").modal("show");
    },
  });
}
function updateOrderStatus(invoiceID, stat) {
  $.ajax({
    url: "staffAjax.php",
    data: { task: "updateOrderAdminStatus", invoice: invoiceID, oStat: stat },
    success: function (response) {
      if (parseInt(response) === 1) {
        if (stat === "Confirmed") {
          Swal.fire({
            icon: "success",
            title: "Done !",
            text: "Order " + invoiceID + " is Confirmed",
          });
        } else if (stat === "Ready") {
          Swal.fire({
            icon: "success",
            title: "Done !",
            text: "Order " + invoiceID + " is Ready",
          });
        } else if (stat === "Dispatched") {
          Swal.fire({
            icon: "success",
            title: "Done !",
            text: "Order " + invoiceID + " is Dispatched",
          });
        } else if (stat === "Completed") {
          Swal.fire({
            icon: "success",
            title: "Done !",
            text: "Order " + invoiceID + " is Delivered",
          });
        } else {
          Swal.fire({
            icon: "success",
            title: "Done !",
            text: "Order " + invoiceID + " Status Updated",
          });
        }
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
