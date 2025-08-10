<?php 
include "..\classes\DBConnect.php";
include "..\classes\DeliveryDriverController.php";
include "..\classes\EmailController.php";
$db = new DatabaseConnection;
$delDriverObj = new DeliveryDriverController();
$emailObj = new EmailController;
 
?>

<?php 
if(isset($_REQUEST['task']) && $_REQUEST['task'] === 'addDelDriver'){
    $data = [
        'fname' => mysqli_real_escape_string($db->conn,$_REQUEST['fname']),
        'lname' => mysqli_real_escape_string($db->conn,$_REQUEST['lname']),
        'VNum' => mysqli_real_escape_string($db->conn,$_REQUEST['VNum']),
        'contact' => mysqli_real_escape_string($db->conn,$_REQUEST['contact']),
        'email' => mysqli_real_escape_string($db->conn,$_REQUEST['email']),
        'pass' => mysqli_real_escape_string($db->conn,$_REQUEST['pass']),
        'driver_status' => mysqli_real_escape_string($db->conn,$_REQUEST['driver_status'])
    ];
    $result = $delDriverObj->addDeliveryDriver($data);
    if($result){
        $emailObj->setEmpCredentialsBody($_POST['email'],$_POST['pass']);
        $emailObj->sendEmpCredentials($_POST['email']);
        echo 1;
    }else{
        echo $result;
    }
}
?>

<?php 
if(isset($_REQUEST['task']) && $_REQUEST['task'] === 'showAllData'){
    $results = $delDriverObj->getDeliveryDriverInfo();
    if($results){
        foreach($results as $row){
            ?>
            <tr>
                <td><?=$row['Driver_ID']?></td>
                <td><?=$row['FName']?></td>
                <td><?=$row['LName']?></td>
                <td><?=$row['Email']?></td>
                <td><?=$row['Vehicle_No']?></td>
                <td><?=$row['Contact_No']?></td>
                <td><?=$row['Status']?></td>
                <td>
                    <a href="javascript:void(0);" onclick="loadDriverDetails('<?=$row['Driver_ID']?>')">
                     <button class="btn btn-outline-success"> <i class="fas fa-edit"></i> </button></a>
                     <a href="javascript:void(0);" onclick="confirmDelete('<?=$row['Driver_ID']?>')">
                        <button class="btn btn-outline-danger" ><i class="fas fa-trash-alt"></i></button></a>
                </td>
            </tr>
            <?php
        }
    }else{
        echo "<tr><td colspan='7'><label>No Records Found</label></td></tr>";
    }
}
?>

<?php 
if(isset($_REQUEST['task']) && $_REQUEST['task'] === 'search'){
    $results = $delDriverObj->searchDriver($_REQUEST['field'],$_REQUEST['data']);
    if($results){
        foreach($results as $row){
            ?>
            <tr>
                <td><?=$row['Driver_ID']?></td>
                <td><?=$row['FName']?></td>
                <td><?=$row['LName']?></td>
                <td><?=$row['Email']?></td>
                <td><?=$row['Vehicle_No']?></td>
                <td><?=$row['Contact_No']?></td>
                <td><?=$row['Status']?></td>
                <td>
                <a class="btn btn-outline-danger" ><i class="fas fa-trash-alt"></i></a>
                </td>
            </tr>
            <?php
        }
    }else{
        echo "<tr><td colspan='7'><label>No Records Found</label></td></tr>";
    }
}

if (isset($_POST['driverId'])) {
    $driverId = $_POST['driverId'];
    $driveryDetails = $delDriverObj->getDriverDetailsforEdit($driverId); // Call the modified method

    if ($driveryDetails) {
        echo json_encode($driveryDetails); // Encode the associative array to JSON
    } else {
        echo json_encode(['error' => 'No category found.']); // Return an error message
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) 
    && $_POST['action'] == 'updateDriverByAdmin') {
   
    $driverId = $_POST['driver_id'];
    $email = $_POST['email'];
    $vehicle_Num = $_POST['vehicle_Num'];

    // Validate the data
    if (!empty($driverId) && !empty($email) && !empty($vehicle_Num)) {
        // Call the method to update the category
        $updateSuccess = $delDriverObj->updateDriverFromAdmin($driverId, $vehicle_Num, $email );

        if ($updateSuccess) {
            echo json_encode(['status' => 'success', 'message' => "Driver's details updated successfully!"]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update details.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    }
}
?>
<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task']) && $_POST['task'] == 'deleteDriver') {
        $driveID = mysqli_real_escape_string($db->conn, $_REQUEST['driveId']);
        header('Content-Type: application/json');
                    
            try {
                if (!isset($_POST['driveId']) || empty($_POST['driveId'])) {
                    throw new Exception('Invalid employee ID provided');
                }
                $driveID = mysqli_real_escape_string($db->conn, $_POST['driveId']);
                    
                // Prepare and execute delete query
                $query = "DELETE FROM deliver_driver WHERE Driver_ID = ?";
                $stmt = $db->conn->prepare($query);
                
                if (!$stmt) {
                    throw new Exception('Failed to prepare delete statement');
                }
                $stmt->bind_param("s", $driveID);
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        echo json_encode([
                            'status' => 'success',
                            'message' => 'Driver record deleted successfully.'
                        ]);
                    } else {
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'No Driver found with the provided ID.'
                        ]);
                    }
                } else {
                    throw new Exception('Failed to execute delete statement');
                }
                $stmt->close();
            } catch (Exception $e) {
                error_log("Error deleting driver: " . $e->getMessage());
                echo json_encode([
                    'status' => 'error',
                    'message' => 'An error occurred while deleting the employee record.'
                ]);
            }
            exit;
            }
?>
