<?php
require_once "GenerateID.php";
class CategoryController{
    public function __construct(){
        $db = new DatabaseConnection;
        $this->generateId = new GenerateID;
        $this->conn = $db ->conn;
    }
    /*Add category*/
    public function addNewMainCategory($data){
        $idType = "mainCat";
        $mainId = $this->generateId->getNewID($idType);
        $catType = $data['type'];
        $catName = $data['categoryName'];

        $categoryCheck = $this->checkCategoryInDB($catType, $catName);
        if ($categoryCheck) {
            return "exists";
        }else{
            $sql_add_mainCat = "INSERT INTO main_category (Main_ID, Category_type, Cate_Name) VALUES('$mainId','$catType','$catName');";
        if($this->conn->query($sql_add_mainCat)){
            $this->generateId->updatetID($idType);
            return true;
        }else{
            return false;
        }

        }
        
    }
/*checks exits category*/
    public function checkCategoryInDB($catType, $catName){
        $sql_getcount = "SELECT COUNT(*) as count FROM main_category WHERE Category_type = '$catType' AND Cate_Name = '$catName';";
        $results = $this->conn->query($sql_getcount);
        $row = $results->fetch_assoc();
        if($row['count'] > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getMainCategoryData(){
        $sql_get_main_data = "SELECT * FROM main_category;";
        $results = $this->conn->query($sql_get_main_data);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    }
    
    public function getCategoryDetailsforEdit($mainId){
        $sql = "SELECT * FROM main_category WHERE Main_ID = '$mainId';";
        $results = $this->conn->query($sql);
        if ($results->num_rows > 0) {
            return $results->fetch_assoc(); // Fetch the row as an associative array
        } else {
            return false; // Return false if no record is found
        }
    }
    public function updateCategory($mainId, $categoryType, $categoryName) {
        // Debugging: Log the mainId
          error_log("Updating category with Main_ID: " . $mainId);
        $sql = "UPDATE main_category SET Category_type = ?, Cate_Name = ? WHERE Main_ID = ?";
        
        // Prepare the statement
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("sss", $categoryType, $categoryName, $mainId);
            
            // Execute the statement
            if ($stmt->execute()) {
                return true; // Update successful
            } else {
                return false; // Update failed
            }
        } else {
            return false; // Statement preparation failed
        }
    }
    public function deleteCategory($mainId) {
        $sql = "DELETE FROM main_category WHERE Main_ID = ?";
        
        // Prepare the statement
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $mainId);
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    return true; // Successfully deleted
                } else {
                    error_log("No rows affected. Main_ID: $mainId does not exist.");
                    return false; // No rows affected, ID not found
                }
            } else {
                error_log("Execute failed for Main_ID: $mainId. Error: " . $stmt->error);
                return false; // Execution failed
            }
        } else {
            error_log("Statement preparation failed. Error: " . $this->conn->error);
            return false; // Statement preparation failed
        }
    }
}
?>