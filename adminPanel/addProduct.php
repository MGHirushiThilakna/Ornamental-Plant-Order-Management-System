<?php include('../config/constant.php') ?>

<?php 
$currentSubPage="add";
include "ProductManagement.php"; 
?>

<link rel="stylesheet" href="..\assets\css\admin-product-new-product-style.css">
<script src="..\assets\js\form-validation\admin-product-form-validation.js"></script>
<script src="..\assets\js\admin-product-add.js"></script>

<body style=" z-index:4;">
<div class="container myMAinCont" >
    <div class="card mt-4 myCard" style=" ">
        <div class="card-header mycardheader">Add New Product</div>
        <div class="card-body">
            <form id="AddProduct" action="item-add.php" method="post" enctype="multipart/form-data" >
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-floating myFormFloating">
                            <input type="text" class="form-control myinputText" name="name" id="name" placeholder=" ">
                            <label for="floatingInput">Product Name</label>
                            <div id="strPNameError"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating myFormFloating">
                            <textarea class="form-control" name="des" placeholder="Enter Plant description here" id="des"></textarea>
                            <label for="floatingTextarea">Product Description</label>
                            <div id="strPDescError"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating myFormFloating">
                            <select class="form-select myselect" id="category" name="category">
                                <option value="0">Select</option>

                                <?php
                                    $sql = "SELECT * FROM main_category";
                                    $res = mysqli_query($conn, $sql);
                            
                                    $count = mysqli_num_rows($res);
                            
                                    if($count>0){
                                        while($row=mysqli_fetch_assoc($res)){
                                            $main_id = $row['Main_ID'];
                                            $cate_name = $row['Cate_Name']; ?>

                                            <option value="<?php echo $main_id; ?>"><?php echo $cate_name; ?></option>
                            
                                        <?php
                                        }
                                    }
                                ?>
                            </select>
                            <label for="floatingSelect">Select category</label>
                            <div id="strSubError"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating myFormFloating">
                            <select class="form-select myselect" id="status" name="status">
                                <option value="0">Select</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <label for="floatingSelect">Select Status</label>
                            <div id="strStaError"></div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-floating mb-3 myFormFloating">
                            <input type="number" class="form-control myinputText" min=0 name="unitprice" id="price" placeholder=" ">
                            <label for="floatingInput">Product Unit Price (Rs.)</label>
                            <div id="strPUnitError"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3 myFormFloating">
                            <input type="number" class="form-control myinputText" min=0 name="quantity" id="quantity" placeholder=" ">
                            <label for="floatingInput">Quantity</label>
                            <div id="strQuaitError"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating myFormFloating">
                            <textarea class="form-control" name="watering" placeholder="Enter watering details here" id="watering"></textarea>
                            <label for="floatingTextarea">Enter watering details here</label>
                            <div id="strWaterPDescError"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating myFormFloating">
                            <textarea class="form-control" name="light" placeholder="Enter light requirements here" id="light"></textarea>
                            <label for="floatingTextarea">Enter light requirements here</label>
                            <div id="strLightPDescError"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating myFormFloating">
                            <textarea class="form-control" name="soil" placeholder="Enter soil details here" id="soil"></textarea>
                            <label for="floatingTextarea">Enter soil details here</label>
                            <div id="strSoilPDescError"></div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group has-validation myFormGroup">
                            <label for="formFile" class="form-label">Please select iamges</label>
                            <input type="file" id="image01" name="image01[]" accept="image/png, image/jpeg" onchange="preview(); enableNextImageInput('image01', 'image02');" multiple class="form-control myChooseFile">
                            <div id="strErr"></div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="form-group">
                        <label for="checkbox">More than one color</label>
                        <input type="checkbox" id="checkbox" name="checkbox" onclick="toggleColors()">
                    </div>
                </div>
                                                 <!-- after clicking checkbox-->
                                                  
                <div class="row mt-3" id="colors" style="display: none;">
                    <div id="colorSections">
                        <div class="row mt-3 newcolor">
                            <div class="col-md-6">
                                <h5>Color 01</h5>
                                <div class="form-floating myFormFloating">
                                    <input type="color" class="form-control myinputText" name="color" id="color" placeholder=" " required>
                                    <label for="floatingInput">Color</label>
                                </div>
                                <div class="form-floating myFormFloating">
                                    <input type="number" class="form-control myinputText" min="0" name="quantityc" id="quantityc" placeholder=" " required>
                                    <label for="floatingInput">Quantity</label>
                                </div>
                                <div class="form-floating myFormFloating">
                                    <input type="number" class="form-control myinputText" min="0" name="ucprice" id="ucprice" placeholder=" " required>
                                    <label for="floatingInput">Unit Price</label>
                                </div>
                                <div class="form-group has-validation myFormGroup">
                                    <label for="formFile" class="form-label">Please select the image</label>
                                    <input type="file" id="imagec" name="imagec[]" accept="image/png, image/jpeg" onchange="preview(); enableNextImageInput('image01', 'image02');" multiple class="form-control myChooseFile">
                                    <div id="strErr"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-col">
                        <button class="btn myBtn" name="add" id="addcolor">Add another color</button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="btn-col">
                            <button class="btn myBtn" type="submit" name="btnProduct">Add Product</button>
                        </div>
                        
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
                        //form validation part
    document.getElementById('AddProduct').addEventListener('submit', function(event) {
    // Get form elements
    const name = document.getElementById('name').value.trim();
    const description = document.getElementById('des').value.trim();
    const category = document.getElementById('category').value;
    const status = document.getElementById('status').value;
    const unitPrice = document.getElementById('price').value.trim();
    const quantity = document.getElementById('quantity').value.trim();
    const watering = document.getElementById('watering').value.trim();
    const light = document.getElementById('light').value.trim();
    const soil = document.getElementById('soil').value.trim();
    const image = document.getElementById('image01').value.trim();
    const checkbox = document.getElementById('checkbox').checked;

    let isValid = true;

    // Clear previous error messages
    document.getElementById('strPNameError').innerHTML = '';
    document.getElementById('strPDescError').innerHTML = '';
    document.getElementById('strSubError').innerHTML = '';
    document.getElementById('strStaError').innerHTML = '';
    document.getElementById('strPUnitError').innerHTML = '';
    document.getElementById('strQuaitError').innerHTML = '';
    document.getElementById('strWaterPDescError').innerHTML = '';
    document.getElementById('strLightPDescError').innerHTML = '';
    document.getElementById('strSoilPDescError').innerHTML = '';
    document.getElementById('strErr').innerHTML = '';
    
    // Validate common fields
    if (name === '') {
        document.getElementById('strPNameError').innerHTML = 'Product Name is required';
        isValid = false;
    }

    if (description === '') {
        document.getElementById('strPDescError').innerHTML = 'Product Description is required';
        isValid = false;
    }

    if (category === '0') {
        document.getElementById('strSubError').innerHTML = 'Please select a category';
        isValid = false;
    }

    if (status === '0') {
        document.getElementById('strStaError').innerHTML = 'Please select the status';
        isValid = false;
    }

    if (watering === '') {
        document.getElementById('strWaterPDescError').innerHTML = 'Watering details are required';
        isValid = false;
    }

    if (light === '') {
        document.getElementById('strLightPDescError').innerHTML = 'Light requirements are required';
        isValid = false;
    }

    if (soil === '') {
        document.getElementById('strSoilPDescError').innerHTML = 'Soil details are required';
        isValid = false;
    }

    // If checkbox is not checked, validate all fields
    if (!checkbox) {
        if (unitPrice === '') {
            document.getElementById('strPUnitError').innerHTML = 'Product Unit Price is required';
            isValid = false;
        }

        if (quantity === '') {
            document.getElementById('strQuaitError').innerHTML = 'Quantity is required';
            isValid = false;
        }

        if (image === '') {
            document.getElementById('strErr').innerHTML = 'At least one image is required';
            isValid = false;
        }
    }

    // If any validation failed, prevent form submission
    if (!isValid) {
        event.preventDefault();
    }
});
</script>

<script>
    // Function to toggle the "required" attribute of quantity and unit price inputs
    function toggleRequired() {
        var checkboxes = document.getElementsByName('checkbox');
        var quantityInputs = document.querySelectorAll('[name^="quantityc"]');
        var priceInputs = document.querySelectorAll('[name^="ucprice"]');
        var isCheckboxChecked = checkboxes[0].checked;  

        quantityInputs.forEach(function(input) { 
            if (!isCheckboxChecked) {                   // when not tick checkbox 
                input.removeAttribute('required');
            } else {
                input.required = true;
            }
        });

        priceInputs.forEach(function(input) {
            if (!isCheckboxChecked) {
                input.removeAttribute('required');
            } else {
                input.required = true;
            }
        });
    }

    // Call toggleRequired initially to set the correct state
    toggleRequired();

    // Event listener for the checkbox
    document.getElementById('checkbox').addEventListener('click', function() {
        toggleRequired();
    });

    // Event listener for adding a new color section
    document.getElementById('addcolor').addEventListener('click', function(event) {
        event.preventDefault();
        var colorSections = document.getElementById('colorSections');
        var numColors = colorSections.getElementsByClassName('newcolor').length + 1;

        var newColorDiv = document.createElement('div');
        newColorDiv.className = 'row mt-3 newcolor';
        newColorDiv.innerHTML = `
         <div class="col-md-6">
           <h5>Color ${numColors < 10 ? '0' + numColors : numColors}</h5>
           <div class="form-floating myFormFloating">
             <input type="color" class="form-control myinputText" name="color${numColors}" id="color${numColors}" placeholder=" ">
             <label for="floatingInput">Color</label>
             </div>
            <div class="form-floating myFormFloating">
                <input type="number" class="form-control myinputText" min="0" name="quantityc${numColors}" id="quantityc${numColors}" placeholder=" ">
              <label for="floatingInput">Quantity</label>
            </div>
             <div class="form-floating myFormFloating">
              <input type="number" class="form-control myinputText" min="0" name="ucprice${numColors}" id="ucprice${numColors}" placeholder=" ">
               <label for="floatingInput">Unit Price</label>
            </div>
                <div class="form-group has-validation myFormGroup">
                <label for="formFile" class="form-label">Please select the image</label>
                 <input type="file" id="imagec${numColors}" name="imagec${numColors}[]" accept="image/png, image/jpeg" onchange="preview(); enableNextImageInput('image01', 'image02');" multiple class="form-control myChooseFile">
                 <div id="strErr"></div>
                </div>
            </div>
        `;

        colorSections.appendChild(newColorDiv);

        // If the checkbox is checked, make the quantity and unit price inputs required
        if (document.getElementById('checkbox').checked) {
            var newQuantityInput = newColorDiv.querySelector(`[name="quantityc${numColors}"]`);
            var newPriceInput = newColorDiv.querySelector(`[name="ucprice${numColors}"]`);
            newQuantityInput.required = true;
            newPriceInput.required = true;
        }
    });

          // Event listener for form submission
    document.getElementById('AddProduct').addEventListener('submit', function(event) {
        var checkboxes = document.getElementsByName('checkbox');
        var quantityInputs = document.querySelectorAll('[name^="quantityc"]');
        var priceInputs = document.querySelectorAll('[name^="ucprice"]');
        var isCheckboxChecked = checkboxes[0].checked;
    });

</script>

<script>                            // when checkbox true--> input feilds ---> disable
    function toggleColors() {
        var checkbox = document.getElementById('checkbox');
        var colorsSection = document.getElementById('colors');
        var inputsToDisable = document.querySelectorAll('#price, #quantity, #image01');

        // Check if the checkbox is checked
        if (checkbox.checked) {
            colorsSection.style.display = 'block'; // when showShow the colors section
            // Disable inputs
            inputsToDisable.forEach(function(input) {
                input.disabled = true;
            });
        } else {
            colorsSection.style.display = 'none'; // Hide the colors section
            // Enable inputs
            inputsToDisable.forEach(function(input) {
                input.disabled = false;
            });
        }
    }
</script>

</body>
</html>