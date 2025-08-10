<?php 
include "..\classes\DBConnect.php";
include "..\classes\DeliveryDriverController.php";
$db = new DatabaseConnection;
$driveObj = new DeliveryDriverController();
session_start();
if(isset( $_SESSION['driverID'])){
    $userPassword = $_SESSION['CurrentPassword'];
    $driverID =  $_SESSION['driverID'];
}
?>
 
<?php 
if(isset($_REQUEST['task']) && $_REQUEST['task'] === 'updateDriver'){
    $data_update = [
        "id" => mysqli_real_escape_string($db->conn,$_REQUEST['driver']),
        "fname" => mysqli_real_escape_string($db->conn,$_REQUEST['fname']),
        "lname" => mysqli_real_escape_string($db->conn,$_REQUEST['lname']),
        "contact" => mysqli_real_escape_string($db->conn,$_REQUEST['contact'])
    ];
    $result = $driveObj -> updateDriverProfile($data_update);
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
        "id" => mysqli_real_escape_string($db->conn,$driverID),
        "password" => mysqli_real_escape_string($db->conn,$password)
    ];
    $result = $driveObj -> ChangeDriverPassword($update);
    if($result){
        echo 1;
    }else{
        echo 0;
    }
}
?>