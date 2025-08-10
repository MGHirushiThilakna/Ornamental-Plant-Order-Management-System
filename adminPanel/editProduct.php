<?php include('../config/constant.php') ?>

<?php
// Check if 'item_id' exists in the query string
if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];

    $sql = "SELECT * FROM item WHERE item_id='$item_id'";

    $res = mysqli_query($conn, $sql);

    $count = mysqli_num_rows($res);

    if ($count > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $colors = $row['colors'];

            if ($colors == 'No') {

                $name = $row['name'];
                $category = $row['category'];
                $price = $row['price'];
                $description = $row['description'];
                $quantity = $row['quantity'];
                $watering = $row['watering'];
                $light = $row['light'];
                $soil = $row['soil'];
                $status = $row['status'];

            } else {

                $name = $row['name'];
                $category = $row['category'];
                $description = $row['description'];
                $quantity = $row['quantity'];
                $unitPrice = $row['price']; //$unitPrice
                $watering = $row['watering'];
                $light = $row['light'];
                $soil = $row['soil'];
                $status = $row['status'];

            }

        }

        $sql2 = "SELECT * FROM main_category WHERE Main_ID = '$category'";
        $res2 = mysqli_query($conn, $sql2);
        $count2 = mysqli_num_rows($res2);

        if ($count2 > 0) {
            $row2 = mysqli_fetch_assoc($res2);
            $category_type = $row2['Category_type'];
            $cate_name = $row2['Cate_Name'];
        } else {
            // Handle error case
            $category_type = '';
            $cate_name = 'Category not found';
        }


        if ($colors == 'Yes') {

            $sql1 = "SELECT * FROM colors WHERE item_id='$item_id'";

            $res1 = mysqli_query($conn, $sql1);

            $count1 = mysqli_num_rows($res1);

            if ($count1 > 0) {
                while ($row = mysqli_fetch_assoc($res1)) {
                    $clr_id = $row['clr_id'];
                    $color = $row['color'];
                    $quantity = $row['quantity'];
                    $price = $row['u_price'];
                    $status = $row['status'];
                }
            }
        } else {
        }

    } else {
    }


} else {
    
}
?>

<?php 
include "ProductManagement.php";
?>

<link rel="stylesheet" href="..\assets\css\admin-product-new-product-style.css">
<script src="..\assets\js\form-validation\admin-product-form-validation.js"></script>
<script src="..\assets\js\admin-product-add.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

<style>
    body {
        font-family: Arial, sans-serif;
    }

    .imageShow {
        display: flex;
        flex-wrap: wrap;
        margin: 20px;
    }

    .imageShow img {
        width: 100px;
        /* Adjust size as needed */
        max-height: 100px;
        border-radius: 10px;
        margin: 5px;
        border: 2px solid #ccc;
        /*Optional border for better visibility */
    }

    .delete-icon {
        position: absolute;
        background-color: red;
        color: white;
        border-radius: 50%;
        top: 5px;
        right: 5px;
        cursor: pointer;
        padding: 5px;
        display: none;
        /* Hidden by default */
        border: none;
        /* Remove border for better appearance */
    }

    .img-container {
        position: relative;
        /* Position for absolute positioning of delete icon */
        display: inline-block;
        /* Allow images to be displayed next to each other */
        margin: 5px;
        /* Space between images */
    }
</style>

<body style=" z-index:4;">
    <div class="container myMAinCont">
        <div class="card mt-4 myCard" style=" ">
            <div class="card-header mycardheader">Edit Product ID : <?php echo $item_id; ?> </div>
            <div class="card-body">
                <form action="item-update.php?item_id=<?php echo $item_id; ?>" method="post"
                    enctype="multipart/form-data">
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-floating myFormFloating">
                                <input type="text" class="form-control myinputText" name="name" id="name"
                                    placeholder=" " value="<?php echo $name; ?>">
                                <label for="floatingInput">Product Name </label>
                                <div id="strPNameError"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating myFormFloating">
                                <textarea class="form-control" name="des" placeholder="Enter Plant description here"
                                    id="des"><?php echo $description; ?></textarea>
                                <label for="floatingTextarea">Product Description</label>
                                <div id="strPDescError"></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating myFormFloating">
                                <select class="form-select myselect" id="category" name="category">
                                    <option value="<?php echo $category; ?>"><?php echo $cate_name; ?></option>

                                    <?php
                                    $sql = "SELECT * FROM main_category";
                                    $res = mysqli_query($conn, $sql);

                                    $count = mysqli_num_rows($res);

                                    if ($count > 0) {
                                        while ($row = mysqli_fetch_assoc($res)) {
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
                                    <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
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
                                <input type="number" class="form-control myinputText" min=0 name="unitprice" id="price"
                                    placeholder=" " value="<?php echo $price; ?>"> <?php  ?>
                                <label for="floatingInput">Product Unit Price (Rs.)</label>
                                <div id="strPUnitError"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3 myFormFloating">
                                <input type="number" class="form-control myinputText" min="0" name="quantity"
                                    id="quantity" placeholder=" " value="<?php echo $quantity; ?>" disabled>
                                <label for="floatingInput">Quantity</label>
                                <div id="strQuaitError"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating myFormFloating">
                                <textarea class="form-control" name="watering" placeholder="Enter watering details here"
                                    id="watering"><?php echo $watering; ?></textarea>
                                <label for="floatingTextarea">Enter watering details here</label>
                                <div id="strWaterPDescError"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating myFormFloating">
                                <textarea class="form-control" name="light" placeholder="Enter light requirements here"
                                    id="light"><?php echo $light; ?></textarea>
                                <label for="floatingTextarea">Enter light requirements here</label>
                                <div id="strLightPDescError"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating myFormFloating">
                                <textarea class="form-control" name="soil" placeholder="Enter soil details here"
                                    id="soil"><?php echo $soil; ?></textarea>
                                <label for="floatingTextarea">Enter soil details here</label>
                                <div id="strSoilPDescError"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group has-validation myFormGroup">
                                <label for="formFile" class="form-label">Please select iamges</label>
                                <input type="file" id="image01" name="image01[]" accept="image/png, image/jpeg"
                                    onchange="preview(); enableNextImageInput('image01', 'image02');" multiple
                                    class="form-control myChooseFile">
                                <div id="strErr"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="checkbox">
                        <label class="form-check-label" for="checkbox">Enable Colors</label>
                    </div>

                    <br>
                    <h4>Images</h4>  <!-- Images shows in this section -->

                    <div class="imageShow"
                        style="min-height: 145px; max-height: fit-content; border: 2px solid black; border-radius: 15px; padding: 8px; position: relative; ">
                        
                    </div>

                    
                    <div class="row mt-3" id="colors" style="display: none;">
                        <div id="colorSections" style=" margin-bottom:30px;"> <!-- Colors Section -->
                        </div>
                        <div class="btn-col">
                            <button class="btn myBtn"  name="add" id="addcolor" style="width:350px;height:50px; margin-left:400px; margin-bottom:10px;">Add another color</button>
                        </div>
                    </div>

                    <input type="hidden" name="existing_clr_id<?php echo $index; ?>" value="<?php echo $clr_id; ?>">
                    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="btn-col">
                                <button class="btn myBtn" type="submit" name="btnProduct" style="width:350px; height:50px;margin-left:400px; margin-bottom:20px;">Save</button>
                            </div>

                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const imageShowDiv = document.querySelector('.imageShow');
            const item_id = '<?php echo $item_id; ?>';

            // Fetch images from the PHP script
            fetch(`getImages.php?item_id=${item_id}`)
                .then(response => response.json())
                .then(images => {
                    if (images.length === 0) {
                        // Create a message element
                        const messageElement = document.createElement('div');
                        messageElement.innerText = "You haven't uploaded any images yet.";
                        messageElement.style.textAlign = 'center';
                        messageElement.style.color = 'red'; 
                        messageElement.style.fontSize = '24px'; 
                        messageElement.style.margin = '20px'; 
                        messageElement.style.fontWeight = 'bold'; 
                        messageElement.style.fontStyle = 'italic'; 
                        messageElement.style.position = 'absolute';
                        messageElement.style.top = '50%'; 
                        messageElement.style.left = '50%'; 
                        messageElement.style.transform = 'translate(-50%, -50%)'; 
                        imageShowDiv.appendChild(messageElement);
                        return; 
                    }

                    // Loop through the images and create elements
                    images.forEach(image => {
                        
                        const imgContainer = document.createElement('div');
                        imgContainer.style.position = 'relative';
                        imgContainer.style.display = 'inline-block';
                        imgContainer.style.margin = '5px';

                        // Create the image element
                        const imgElement = document.createElement('img');
                        imgElement.src = image;
                        imgElement.style.width = '100px'; 
                        imgElement.style.height = 'auto';
                        imgElement.style.borderRadius = '10px';

                        // Create delete icon using the provided span
                        const deleteIcon = document.createElement('span');
                        deleteIcon.className = 'material-symbols-outlined';
                        deleteIcon.innerText = 'delete'; 
                        deleteIcon.style.position = 'absolute';
                        deleteIcon.style.backgroundColor = 'red';
                        deleteIcon.style.color = 'white';
                        deleteIcon.style.borderRadius = '50%';
                        deleteIcon.style.top = '5px';
                        deleteIcon.style.right = '5px';
                        deleteIcon.style.display = 'none'; 
                        deleteIcon.style.cursor = 'pointer'; 
                        deleteIcon.style.padding = '5px'; 
                        deleteIcon.style.fontSize = '20px'; 

                        // Show delete icon on hover
                        imgContainer.addEventListener('mouseenter', () => {
                            deleteIcon.style.display = 'block';
                        });

                        imgContainer.addEventListener('mouseleave', () => {
                            deleteIcon.style.display = 'none';
                        });

                        // Delete image on icon click
                        deleteIcon.addEventListener('click', (event) => {
                            event.preventDefault(); 
                            const confirmDelete = confirm('Are you sure you want to delete this image?');
                            if (!confirmDelete) return; 

                            const imagePath = image; 

                            // Send a POST request to delete the image
                            fetch('delete_image.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: new URLSearchParams({
                                    'imagePath': imagePath, 
                                    'item_id': item_id  
                                })
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === 'success') {
                                        console.log(data.message);
                                        imgContainer.remove(); 
                                    } else {
                                        alert(`Failed to delete image: ${data.message}`);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error deleting image:', error);
                                    alert('An error occurred while deleting the image.');
                                });
                        });


                        // Append  image show elements
                        imgContainer.appendChild(imgElement);
                        imgContainer.appendChild(deleteIcon);
                        imageShowDiv.appendChild(imgContainer);
                    });
                })
                .catch(error => console.error('Error fetching images:', error));
        });
    </script>

    
    <script>
        //form validation part
        document.getElementById('editProduct').addEventListener('submit', function (event) {
            // Get form inputs
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

            // Validate  fields
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



    <?php
    $sql3 = "SELECT * FROM colors WHERE item_id='$item_id'";
    $res3 = mysqli_query($conn, $sql3);
    $existingColors = [];

    if ($res3 && mysqli_num_rows($res3) > 0) {
        while ($row = mysqli_fetch_assoc($res3)) {
            $clr_id = $row['clr_id'];
            $item_id = $row['item_id'];
            $color = $row['color'];
            $quantity = $row['quantity'];
            $price = $row['u_price']; 
    
            // Add each color data to the array,
            $existingColors[] = [
                'clr_id' => $clr_id,
                'item_id' => $item_id,
                'color' => $color,
                'quantity' => $quantity,
                'price' => $price,
            ];
        }
    }
    $existingColorsJson = json_encode($existingColors);
    ?>
    <script>
        const existingColors = <?php echo $existingColorsJson; ?>; 
        const colors = "<?php echo $colors; ?>"; // yes or no

        //  initial state of the checkbox 
        document.addEventListener('DOMContentLoaded', function () {
            const checkbox = document.getElementById('checkbox');

            if (colors === "Yes") {
                checkbox.checked = true;
            }
            toggleColors();

            //  event listener to toggle colors section on checkbox change
            checkbox.addEventListener('change', toggleColors);
        });

        // Function to handle showing/hiding the colors 
        function toggleColors() {
            const checkbox = document.getElementById('checkbox');
            const colorsSection = document.getElementById('colors');
            const inputsToDisable = document.querySelectorAll('#price, #quantity, #image01'); // Adjust these IDs as necessary

            if (checkbox.checked) {
                colorsSection.style.display = 'block'; 
                // Disable inputs
                inputsToDisable.forEach(function (input) {
                    input.disabled = true;
                });
            } else {
                colorsSection.style.display = 'none'; // Hide the colors section
                // Enable inputs
                inputsToDisable.forEach(function (input) {
                    input.disabled = false;
                });
            }
        }



        //  to add a new color section dynamically
        function addColorSection(colorData, index) {
            var colorSections = document.getElementById('colorSections');
            var numColors = colorSections.getElementsByClassName('newcolor').length + 1;

            var newColorDiv = document.createElement('div');
            newColorDiv.className = 'row mt-3 newcolor';
            newColorDiv.id = `colorSection${colorData ? colorData.clr_id : index}`;

            // Add hidden input for clr_id if it exists
            const clrIdInput = colorData ?
                `<input type="hidden" name="clr_id${numColors}" value="${colorData.clr_id}">` : '';

            newColorDiv.innerHTML = `
            ${clrIdInput}
            <div class="col-md-6">
            <h5 class="colorHeader">Color ${numColors < 10 ? '0' + numColors : numColors}</h5>
            <div class="form-floating myFormFloating">
                <input type="color" class="form-control myinputText" name="color${numColors}" value="${colorData ? colorData.color : ''}" placeholder=" " required>
                <label for="floatingInput">Color</label>
            </div>
            <div class="form-floating myFormFloating">
                <input type="number" class="form-control myinputText" min="0" name="quantityc${numColors}" value="${colorData ? colorData.quantity : ''}" placeholder=" " required>
                <label for="floatingInput">Quantity</label>
            </div>
            <div class="form-floating myFormFloating">
                <input type="number" class="form-control myinputText" min="0" name="ucprice${numColors}" value="${colorData ? colorData.price : ''}" placeholder=" " required>
                <label for="floatingInput">Unit Price</label>
            </div>
            <div class="form-group has-validation myFormGroup">
                <label for="formFile" class="form-label">Please select the image</label>
                <input type="file" name="imagec${numColors}[]" accept="image/png, image/jpeg" onchange="preview();" multiple class="form-control myChooseFile">
                <div id="strErr"></div>
            </div>
            <button type="button" class="btn btn-danger mt-2" onclick="deleteColor('${colorData ? colorData.clr_id : index}', '${colorData ? colorData.item_id : ''}', 'colorSection${colorData ? colorData.clr_id : index}')">Delete Color</button>
            </div>
         `;

            colorSections.appendChild(newColorDiv);
        }

        // Function to update color section numbers after deletion
        function updateColorNumbers() {
            var colorSections = document.getElementById('colorSections');
            var colorSectionsArray = colorSections.getElementsByClassName('newcolor');

            for (let i = 0; i < colorSectionsArray.length; i++) {
                const colorHeader = colorSectionsArray[i].getElementsByClassName('colorHeader')[0];
                colorHeader.textContent = `Color ${i + 1 < 10 ? '0' + (i + 1) : i + 1}`;
            }
        }



        // Function to delete the color section
        function deleteColor(clr_id, item_id, section_id) {
            if (confirm('Are you sure you want to delete this color?')) {
                fetch('deleteColor.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ clr_id: clr_id, item_id: item_id })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            var sectionToDelete = document.getElementById(section_id);
                            if (sectionToDelete) {
                                sectionToDelete.remove();
                                updateColorNumbers();
                            }
                            
                            alert('Color successfully deleted.');
                        } else {
                            alert('Failed to delete the color. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while trying to delete the color.');
                    });
            } else {
                // If the user presses "Cancel", 
                alert('Deletion canceled.');
            }
        }
        // Function to re-align color numbers after a deletion
        function reAlignColorNumbers() {
            var colorSections = document.getElementsByClassName('newcolor');
            for (var i = 0; i < colorSections.length; i++) {
                var numColors = i + 1;
                var colorSection = colorSections[i];

                // Update the color header numbering
                var colorHeader = colorSection.querySelector('.colorHeader');
                if (colorHeader) {
                    colorHeader.textContent = `Color ${numColors < 10 ? '0' + numColors : numColors}`;
                }

                // Update input field IDs and names for proper alignment
                var colorInput = colorSection.querySelector('input[type="color"]');
                if (colorInput) {
                    colorInput.name = `color${numColors}`;
                    colorInput.id = `color${numColors}`;
                }

                var quantityInput = colorSection.querySelector('input[name^="quantityc"]');
                if (quantityInput) {
                    quantityInput.name = `quantityc${numColors}`;
                    quantityInput.id = `quantityc${numColors}`;
                }

                var priceInput = colorSection.querySelector('input[name^="ucprice"]');
                if (priceInput) {
                    priceInput.name = `ucprice${numColors}`;
                    priceInput.id = `ucprice${numColors}`;
                }

                var imageInput = colorSection.querySelector('input[type="file"]');
                if (imageInput) {
                    imageInput.name = `imagec${numColors}[]`;
                    imageInput.id = `imagec${numColors}`;
                }
            }
        }

        // Function to load existing color data into the form
        function loadExistingColors() {
            existingColors.forEach(function (colorData, index) {
                addColorSection(colorData, index + 1);
            });
        }

        //  adding a new color section manually
        document.getElementById('addcolor').addEventListener('click', function (event) {
            event.preventDefault();
            var index = document.getElementById('colorSections').getElementsByClassName('newcolor').length;
            addColorSection(null, index);
        });

        // Call loadExistingColors to pre-fill the form with existing color data
        loadExistingColors();

    </script>

</body>

</html>