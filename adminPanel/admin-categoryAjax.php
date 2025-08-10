<?php 
include "..\classes\DBConnect.php";
include "..\classes\CategoryController.php";
$db = new DatabaseConnection;
$mainCatObj = new CategoryController;

if(isset($_REQUEST['task']) && $_REQUEST['task'] === 'addCate'){
    $data = [
        'type' => mysqli_real_escape_string($db->conn,$_REQUEST['cateType']),
        'categoryName' => mysqli_real_escape_string($db->conn,$_REQUEST['cateName']),
    ];

    $resMainCat = $mainCatObj->addNewMainCategory($data);
    if($resMainCat ==="exists"){
        echo "category exists";
    } else if($resMainCat === true) {
        echo 1; 
    } else if($resMainCat === false) {
        echo 0; 
    }else{
        echo $resMainCat;
    }
}

if (isset($_POST['mainId'])) {
    $mainId = $_POST['mainId'];
    $categoryDetails = $mainCatObj->getCategoryDetailsforEdit($mainId); // Call the modified method

    if ($categoryDetails) {
        echo json_encode($categoryDetails); // Encode the associative array to JSON
    } else {
        echo json_encode(['error' => 'No category found.']); // Return an error message
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) 
    && $_POST['action'] == 'updateCategory') {
   
    $mainId = $_POST['main_id'];
    $categoryType = $_POST['category_type'];
    $categoryName = $_POST['category_name'];

    // Validate the data
    if (!empty($mainId) && !empty($categoryType) && !empty($categoryName)) {
        // Call the method to update the category
        $updateSuccess = $mainCatObj->updateCategory($mainId, $categoryType, $categoryName);

        if ($updateSuccess) {
            echo json_encode(['status' => 'success', 'message' => 'Category updated successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update category.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && 
        $_POST['action'] == 'deleteCategory') {  

    $mainId = $_POST['main_id'];
    $deleteSuccess = $mainCatObj->deleteCategory($mainId);

    if ($deleteSuccess) {
        echo json_encode(['success' => true, 'message' => 'Category deleted successfully.']);
    } else {
        // Send failure response
        echo json_encode(['success' => false, 'message' => 'Failed to delete the category.']);
    }
    exit;
}
?>
