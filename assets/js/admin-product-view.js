$(document).ready(function () {
  getProductinfo();

  $("#itemSearch").submit(function (event) {
    event.preventDefault();
    var field = $('select[name="item_col"]').val();
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
      url: "admin-productAjax.php",
      method: "POST",
      data: {
        task: "search",
        field: field,
        searchData: searchData,
      },
      success: function (response) {
        Swal.close();
        $("#pro_Data").html(response);
      },
      error: function (xhr, status, error) {
        Swal.close();
        console.error("AJAX error: " + status + "\nError: " + error);
        $("#pro_Data").html(
          "<tr><td colspan='12'>Error searching products. Please try again.</td></tr>"
        );
      },
    });
  });
});

function getProductinfo() {
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
    url: "admin-productAjax.php",
    method: "POST",
    data: { task: "displayAllProducts" },
    success: function (response) {
      Swal.close();
      $("#pro_Data").html(response);
    },
    error: function (xhr, status, error) {
      Swal.close();
      console.error("AJAX error: " + status + "\nError: " + error);
      $("#pro_Data").html(
        "<tr><td colspan='12'>Error loading products. Please try again.</td></tr>"
      );
    },
  });
}

function confirmDelete(itemId) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, delete it!",
    cancelButtonText: "No, cancel!",
  }).then((result) => {
    if (result.isConfirmed) {
      // Proceed with deletion
      deleteProduct(itemId);
    }
  });
}

function deleteProduct(itemId) {
  $.ajax({
    url: "admin-productAjax.php",
    method: "POST",
    data: { task: "deleteProduct", item_id: itemId },

    success: function (response) {
      console.log("Item ID being sent:", itemId);
      if (response.success) {
        Swal.fire("Deleted!", "Your product has been deleted.", "success");
        getProductinfo(); // Refresh the product list
      } else {
        Swal.fire("Deleted!", "Your product has been deleted.", "success");
      }
      location.reload();
    },
    error: function (xhr, status, error) {
      console.error("AJAX error: " + status + "\nError: " + error);
      Swal.fire(
        "Error!",
        "An error occurred while deleting the product.",
        "error"
      );
    },
  });
}

function viewColorPrices(itemId) {
  Swal.fire({
    title: "Loading...",
    html: '<div class="loading-spinner"></div>',
    showConfirmButton: false,
    allowOutsideClick: false,
  });

  $.ajax({
    url: "admin-productAjax.php",
    method: "POST",
    data: {
      task: "getColorPrices",
      item_id: itemId,
    },
    dataType: "json", // Specify expected data type
    success: function (data) {
      if (data.length === 0) {
        Swal.fire(
          "No Data",
          "No color variants found for this product",
          "info"
        );
        return;
      }

      let content = `
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Color</th>
                                <th>Price ($)</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>`;

      data.forEach((item) => {
        content += `
                    <tr>
                        <td>
                            <div style="width: 30px; height: 30px; background-color: ${item.color}; border: 1px solid #ddd; border-radius: 5px;"></div>
                        </td>
                        <td>${item.u_price}</td>
                        <td>${item.quantity}</td>
                    </tr>`;
      });

      content += `</tbody></table></div>`;

      Swal.fire({
        title: "Color Variants and Prices",
        html: content,
        width: "600px",
        showConfirmButton: true,
        confirmButtonText: "Close",
        confirmButtonColor: "#3085d6",
      });
    },
    error: function (xhr, status, error) {
      console.error("AJAX error:", error);
      Swal.fire(
        "Error",
        "Failed to load color prices. Please try again.",
        "error"
      );
    },
  });
}

function viewImages(itemId) {
  Swal.fire({
    title: "Loading...",
    html: '<div class="loading-spinner"></div>',
    showConfirmButton: false,
    allowOutsideClick: false,
  });

  $.ajax({
    url: "admin-productAjax.php",
    method: "POST",
    data: {
      task: "getImages",
      item_id: itemId,
    },
    dataType: "json",
    success: function (images) {
      if (images.length === 0) {
        Swal.fire("No Images", "No images found for this product", "info");
        return;
      }

      let content =
        '<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; padding: 10px;">';

      images.forEach((image) => {
        content += `
                    <div style="text-align: center;">
                        <img src="${image}" 
                             style="width: 100px; 
                                    height: 100px; 
                                    object-fit: cover;
                                    border-radius: 8px; 
                                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);" 
                             onerror="this.onerror=null; this.src='../assets/imgs/placeholder.jpg';" />
                    </div>`;
      });

      content += "</div>";

      Swal.fire({
        title: "Product Images",
        html: content,
        width: "600px",
        showConfirmButton: true,
        confirmButtonText: "Close",
        confirmButtonColor: "#3085d6",
        customClass: {
          popup: "swal-wide",
        },
      });
    },
    error: function (xhr, status, error) {
      console.error("AJAX error:", error);
      Swal.fire("Error", "Failed to load images. Please try again.", "error");
    },
  });
}
