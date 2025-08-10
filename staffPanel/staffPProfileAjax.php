<?php 
include "..\classes\DBConnect.php";
include "..\classes\EmployeeController.php";
$db = new DatabaseConnection;
$empObj = new EmployeeController();
session_start();
if(isset( $_SESSION['empID'])){
    $userPassword = $_SESSION['CurrentPassword'];
    $empID =  $_SESSION['empID'];
}
?>
 
<?php 
if(isset($_REQUEST['task']) && $_REQUEST['task'] === 'updateEmployee'){
    $data_update = [
        "id" => mysqli_real_escape_string($db->conn,$_REQUEST['employee']),
        "fname" => mysqli_real_escape_string($db->conn,$_REQUEST['fname']),
        "lname" => mysqli_real_escape_string($db->conn,$_REQUEST['lname']),
        "contact" => mysqli_real_escape_string($db->conn,$_REQUEST['contact'])
    ];
    $result = $empObj -> updateEmployee($data_update);
    if($result){
        echo 1;
    }else{
        echo 0;
    }
}

if(isset($_REQUEST['task']) && $_REQUEST['task'] === 'verifyPass'){
    $verify = password_verify($_REQUEST['password'],$userPassword);
    if($verify){
        echo 1;
    }else{
        echo 2;
    }
}
if(isset($_REQUEST['newPassword'])){
    $password = password_hash($_REQUEST['newPassword'],PASSWORD_DEFAULT);
    $update = [
        "id" => mysqli_real_escape_string($db->conn,$empID),
        "password" => mysqli_real_escape_string($db->conn,$password)
    ];
    $result = $empObj -> ChangeEmpPassword($update);
    if($result){
        echo 1;
    }else{
        echo 0;
    }
}
?>