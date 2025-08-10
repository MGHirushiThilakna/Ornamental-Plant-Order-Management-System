$(document).ready(function () {
  $("#AddCategory").submit(function (event) {
    event.preventDefault(); // this prevent the form from default submission

    var cateType = $('select[name="Category_type"]');
    var cateName = $('input[name="CategoryName"]');

    // reset errors
    $(".myinputText").removeClass("is-invalid");
    $(".myselect").removeClass("is-invalid");

    resetError();
    var isValid_cate_name = true;
    var isValid_type = true;

    // performing validation

    if (parseInt(cateType.val()) === 0) {
      cateType.addClass("is-invalid");
      $("#strselectError").html("Please select a category type");
      isValid_type = false;
    }

    if (cateName.val() == "") {
      $("#strNameError").html("This field is required");
      cateName.addClass("is-invalid");
      isValid_cate_name = false;
    }

    if (isValid_cate_name && isValid_type) {
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
        url: "admin-categoryAjax.php",
        type: "post",
        data: {
          cateType: cateType.val(),
          cateName: cateName.val(),
          task: "addCate",
        },
        success: function (response) {
          
          Swal.close();
          if (response === "category exists") {
            Swal.fire({
              icon: "warning",
              title: "Category already exists",
              text: "Please enter a new category.",
            });
          } else if (parseInt(response) === 1) {
            Swal.fire({
              icon: "success",
              title: "Done !",
              text: "Category was insert successfully",
              
            });
          } else if (parseInt(response) === 0) {
            Swal.fire({
              icon: "warning",
              title: "Something is not right",
              text: "",
            });
          } else {
            Swal.fire({
              icon: "warning",
              title: "Something is not right",
              text: response,
            });
          }
          location.reload();
        },
        
      });
    }
  });

  // Edit button click event ----------------------------------------------------------------
  
// Submit form and save changes
$('#editCategoryForm').on('submit', function (e) {
    e.preventDefault(); // Prevent default form submission

    $.ajax({
        url: '/path/to/updateCategory.php',  // Endpoint to update category details
        type: 'POST',
        data: $('#editCategoryForm').serialize(),
        success: function (response) {
            alert('Category updated successfully.');
            $('#editCategoryModal').hide();
            // Optionally, refresh the category table here
        },
        error: function (error) {
            alert('Error updating category.');
        }
    });
  });
});
function resetError() {
  $("#strselectError").html("");
  $("#strNameError").html("");
}

function displayALLEmpData() {
  $.ajax({
    url: "empAjax.php",
    data: { task: "showAllData" },
    success: function (response) {
      $("#empDataShow").html(response);
    },
  });
}
