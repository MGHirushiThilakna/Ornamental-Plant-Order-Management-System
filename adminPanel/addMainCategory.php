<?php 
$currentSubPage="mainCat";
include "adminHeader_1.php"; 
include "..\classes\DBConnect.php";
include "..\classes\CategoryController.php";
$db = new DatabaseConnection;
$mainCatObj = new CategoryController;
?>

<link rel="stylesheet" href="..\assets\css\admin-category-style.css">
<script src="..\assets\sweetalert2\sweetalert2.all.min.js"></script>
<script src="..\assets\js\staff-logoutProccess.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
<div class="container my-container">
<div class="page-title">Category Handling</div>
    <div class="card mycard" >
    
        <div class="card-body mycard-body">
        
            <div class="row">
            
                <div class="col-md-9 mb-4">
                    <div class="card" style="margin-left:250px">
                        <div class="card-header mycardheader">Add New Main Category</div>
                        <div class="card-body " >
                            <form id="AddCategory" >
                                <div class="row myrow">
                                    <div class="col ">
                                            <div class="form-floating myFormFloating">
                                            <select class="form-select myselect" id="floatingSelect" name="Category_type">
                                                <option value="0">Select type</option>
                                                <option value="Indoor">Indoor</option>
                                                <option value="Outdoor">Outdoor</option>
                                            </select>
                                        <label for="floatingSelect">Category Type</label>
                                             <div id="strselectError"></div>
                                            </div>
                                    </div>
                                    <div class="col ">
                                            <div class="form-floating myFormFloating">
                                                <input type="text" class="form-control myinputText" name="CategoryName" id="floatingInput" placeholder=" ">
                                                <label for="floatingInput">Enter Category Name</label>
                                                <div id="strNameError"></div>
                                            </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="btn-col">
                                        <button class="btn myBtn"  type="submit" name="btnMain">Add Category</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-10" style="margin-left:100px">
                    <div class="table-responsive">
                        <table class="table"  >
                            <thead>
                                <tr>
                                    <th scope="col">Main Category ID</th>
                                    <th scope="col">Category Type</th>
                                    <th scope="col">Category Name</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                    $results = $mainCatObj->getMainCategoryData();
                                    if($results){
                                        foreach($results as $row){
                                            ?>
                                            <tr>
                                            <th scope="row"><?=$row['Main_ID']?></th>
                                                <td><?=$row['Category_type']?></td>
                                                <td><?=$row['Cate_Name']?></td>
                                                <td> 
                                                    <button class="btn btn-outline-success edit-btn"  onclick="loadCategoryDetails('<?=$row['Main_ID']?>')"><i class="fas fa-edit"></i></button>
                                                    <button class="btn btn-outline-danger" onclick="confirmDelete('<?=$row['Main_ID']?>')"><i class="fas fa-trash-alt"></i></button>
                                                </td>
                                            </tr>
                                            <?php
                                                
                                        }
                                    }else{
                                        echo "<tr><td colspan='3'>No Records found</td></tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                 <div id="categoryModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeCategoryModal()">&times;</span>
                        <div id="modalCategoryContent"></div>
                    </div>
                 </div>
            </div>
        </div>
                    
    </div>
</div>
<script>

        let popup = document.getElementById("popup");

        function openPopup(){
            popup.classList.add("open-popup");
        }

        function closePopup(){
            popup.classList.remove("open-popup");
        }



        let popup1 = document.getElementById("popup1");

        function openPopup1(){
            popup1.classList.add("open-popup1");
        }

        function closePopup1(){
            popup1.classList.remove("open-popup1");
        }

    </script>

<script type="text/javascript">
                    function loadCategoryDetails(mainId) {
                    var modalCategoryContent = document.getElementById("modalCategoryContent");
                    console.log("Updating category with ID:", mainId);
                    $.ajax({
                        url: 'admin-categoryAjax.php', // The URL to fetch category details
                        method: "POST",
                        data: { mainId: mainId },
                        success: function(response) {
                            if (response) {
                        var category = JSON.parse(response); // Assuming the response is JSON
                        modalCategoryContent.innerHTML = `
                    <h2 class="modal-heading">Edit Category</h2>
                    <div class="popup1"> 
                        <form onsubmit="event.preventDefault(); updateCategory();"> <!-- Prevent default form submission -->
                        <table>
                        <tr>
                                <input type="hidden" name="main_id" value="${category.Main_ID}"> 
                            </tr>
                            <tr>
                                <td><label>Category Type:</label></td>
                                <td><select name="category_type" required>
                                    <option value="${category.Category_type}">${category.Category_type}</option>
                                    <option value="Indoor">Indoor</option>
                                    <option value="Outdoor">Outdoor</option>
                                </select></td>
                            </tr>
                            <tr>
                            <td> <label>Category Name:</label></td>
                            <td>  <input type="text" name="category_name" required value="${category.Cate_Name}"></td>
                        </tr>
                            <tr>
                                <td colspan="2" style="text-align: center;"> <button type="submit">Save Changes</button></td>
                            </tr>
                        </form>
                    </div>
                    `;
                    openCategoryModal();
                } else {
                    modalCategoryContent.innerHTML = "Error: Failed to load category details.";
                    openCategoryModal();
                }
            },
            error: function() {
                modalCategoryContent.innerHTML = "Error: Failed to load category details.";
                openCategoryModal();
            }
        });

    }

    function openCategoryModal() {
        document.getElementById("categoryModal").style.display = "block";
    }

    function closeCategoryModal() {
        document.getElementById("categoryModal").style.display = "none";
    }
        function updateCategory() {
            var mainId = document.querySelector('input[name="main_id"]').value;
            var categoryType = document.querySelector('select[name="category_type"]').value;
            var categoryName = document.querySelector('input[name="category_name"]').value;
            console.log("Updating category with ID:", mainId);
            
            $.ajax({
                url: 'admin-categoryAjax.php', // The URL to send the request to
                method: 'POST',
                data: {
                    action: 'updateCategory', // Action to identify the request
                    main_id: mainId,
                    category_type: categoryType,
                    category_name: categoryName
                },
                
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.status === 'success') {
                        alert(result.message); // Show success message
                        // Optionally, refresh the category list or close the modal
                        location.reload(); // Call a function to refresh the list
                        closeCategoryModal(); // Close the modal if needed
                    } else {
                        alert(result.message); // Show error message
                    }
                },
                error: function() {
                    alert('An error occurred while updating the category.');
                }
            });
            console.log("Updating category with ID:", mainId);
        }


        function confirmDelete(mainId) {
            console.log("delete category with ID:", mainId);
         Swal.fire({
                title: 'Are you sure?',
                text: "Once deleted, you will not be able to recover this category details!!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteCategory(mainId);
                }
            });
        }

    function deleteCategory(mainId) {
        $.ajax({
        url: 'admin-categoryAjax.php', // The URL to send the request to
        method: 'POST',
        data: {
            action: 'deleteCategory', // Action to identify the request
            main_id: mainId // Pass the mainId to delete
        },
        dataType: 'json', 
        success: function(response) {
            console.log("Response Type:", typeof response); // Should be 'object'
            console.log("Response Content:", response); // Log the parsed object

            if (response.success) {
                Swal.fire('Deleted!', response.message, 'success'); // Success alert
                location.reload();
            } else {
                Swal.fire('Error!', response.message || 'An unexpected error occurred.', 'error'); // Failure alert
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error); // Log AJAX errors
            Swal.fire('Error!', 'A server error occurred. Please try again.', 'error'); // Server error alert
        }
    });

}
</script>
<script src="..\assets\js\form-validation\admin-category-validation.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<?php include "adminFooter.php"; ?>
