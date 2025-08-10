<?php 
include "..\classes\DBConnect.php";
include "..\classes\CustomerController.php";
$db = new DatabaseConnection;
$cusObj = new CustomerController();
session_start();
if(isset($_SESSION['Customer_ID'])){
    $userPassword = $_SESSION['CurrentPassword'];
    $customerID = $_SESSION['Customer_ID'];
}
?>
 
<?php 
if(isset($_REQUEST['task']) && $_REQUEST['task'] === 'updateCustomer'){
    $data_update = [
        "cid" => mysqli_real_escape_string($db->conn,$_REQUEST['customer']),
        "fname" => mysqli_real_escape_string($db->conn,$_REQUEST['fname']),
        "lname" => mysqli_real_escape_string($db->conn,$_REQUEST['lname']),
        "contact" => mysqli_real_escape_string($db->conn,$_REQUEST['contact']),
        "address" => mysqli_real_escape_string($db->conn,$_REQUEST['address'])
    ];
    $result = $cusObj -> updateCustomer($data_update);
    if($result){
        echo 1;
    }else{
        echo 0;
    }
}

// Verify current password
if(isset($_REQUEST['task']) && $_REQUEST['task'] === 'verifyPass'){
    $verify = password_verify($_REQUEST['password'], $userPassword);
    if($verify){
        echo 1;
    }else{
        echo 2;
    }
}

// Update password
if(isset($_REQUEST['task']) && $_REQUEST['task'] === 'updatePassword'){
    $newPassword = $_REQUEST['newPassword'];
    $customerID = $_REQUEST['customerID'];
    
    $data_update = [
        "cid" => mysqli_real_escape_string($db->conn, $customerID),
        "password" => password_hash($newPassword, PASSWORD_DEFAULT)
    ];
    
    $result = $cusObj->changePassword($data_update);
    if($result){
        // Update session password
        $_SESSION['CurrentPassword'] = password_hash($newPassword, PASSWORD_DEFAULT);
        echo 1;
    }else{
        echo 0;
    }
}
?>