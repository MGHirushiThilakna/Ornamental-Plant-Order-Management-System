<?php 
include "..\classes\DBConnect.php";
include "..\classes\EmployeeController.php";
include "..\classes\EmailController.php";
$db = new DatabaseConnection;
$empObj = new EmployeeController();
$emailObj = new EmailController;

?>

<?php 
if(isset($_REQUEST['task']) && $_REQUEST['task'] === 'addEmp'){
    $data = [
        'fname' => mysqli_real_escape_string($db->conn,$_REQUEST['fname']),
        'lname' => mysqli_real_escape_string($db->conn,$_REQUEST['lname']),
        'Job_Role' => mysqli_real_escape_string($db->conn,$_REQUEST['job']),
        'contact' => mysqli_real_escape_string($db->conn,$_REQUEST['contact']),
        'email' => mysqli_real_escape_string($db->conn,$_REQUEST['email']),
        'pass' => mysqli_real_escape_string($db->conn,$_REQUEST['pass']),
        'emp_status' => mysqli_real_escape_string($db->conn,$_REQUEST['status'])
    ];
  
    $result = $empObj->addEmployee($data);
    if($result === "Email already exists."){
        echo "Email already exists.";
    } elseif($result){
        $emailObj->setEmpCredentialsBody($_POST['email'],$_POST['pass']);
        $emailObj->sendEmpCredentials($_POST['email']);
        echo 1;
    }else{
        echo $result;
    }
}
?>

<?php                   // table data get from heare for view employee
if(isset($_REQUEST['task']) && $_REQUEST['task'] === 'showAllData'){
    $results = $empObj->getEmpData();
    if($results){
        foreach($results as $row){
            ?>
            <tr>
                <td><?=$row['Emp_ID']?></td>
                <td><?=$row['FName']?></td>
                <td><?=$row['LName']?></td>
                <td><?=$row['Email']?></td>
                <td><?=$row['Job_Role']?></td>
                <td><?=$row['Contact_No']?></td>
                <td><?=$row['Emp_status']?></td>
                <td>
                    <a href="javascript:void(0);" onclick="loadEmpDetails('<?=$row['Emp_ID']?>')">
                     <button class="btn btn-outline-success"> <i class="fas fa-edit"></i> </button></a>
                     <a href="javascript:void(0);" onclick="confirmDelete('<?=$row['Emp_ID']?>')">
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
if(isset($_REQUEST['validateEmail'])){
    $res = $empObj -> checkEmailInDB($_REQUEST['validateEmail']);
    if($res){
        echo 1;
    }else{
        echo 0;
    } 
}
?>

<?php 
if(isset($_REQUEST['task']) && $_REQUEST['task'] === 'search'){
    $results = $empObj->searchEMP($_REQUEST['field'],$_REQUEST['data']);
    if($results){
        foreach($results as $row){
            ?>
            <tr>
                <td><?=$row['Emp_ID']?></td>
                <td><?=$row['FName']?></td>
                <td><?=$row['LName']?></td>
                <td><?=$row['Email']?></td>
                <td><?=$row['Job_Role']?></td>
                <td><?=$row['Contact_No']?></td>
                <td><?=$row['Emp_status']?></td>
                <td>
                    <a href="javascript:void(0);" onclick="loadEmpDetails('<?=$row['Emp_ID']?>')">
                     <button class="btn btn-outline-success"> <i class="fas fa-edit"></i> </button></a>
                     <a href="javascript:void(0);" onclick="confirmDelete('<?=$row['Emp_ID']?>')">
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
if (isset($_POST['emp_id'])) {
    $empId = $_POST['emp_id'];
    $empDetails = $empObj->getEmpDetailsforEdit($empId); // Call the modified method

    if ($empDetails) {
        echo json_encode($empDetails); // Encode the associative array to JSON
    } else {
        echo json_encode(['error' => 'No category found.']); // Return an error message
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])  && $_POST['action'] == 'updateEmpByAdmin') {
   
       ini_set('display_errors', 1);
         error_reporting(E_ALL);
         ob_clean();
                                           
     $empId = $_POST['emp_id'];
    $jobRole = $_POST['job_role'];
    $email = $_POST['email'];
    $status = $_POST['status'];

    try {
        
        if (!empty($empId) && !empty($jobRole) && !empty($email) && !empty($status)) {

            $isDuplicate = $empObj->checkExistEmployeeForUpdate($empId, $jobRole, $email);
            error_log("isDuplicate check result: " . ($isDuplicate ? 'true' : 'false'));

            if ($isDuplicate) {
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'The email is already used for this job role by another employee.'
                ]);
            } else {
                $updateSuccess = $empObj->updateEmpFromAdmin($empId, $jobRole, $email, $status);
                error_log("Update result: " . ($updateSuccess ? 'success' : 'failed'));
                
                if ($updateSuccess) {
                    echo json_encode([
                        'status' => 'success', 
                        'message' => "Employee's details updated successfully!"
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error', 
                        'message' => 'Failed to update details. Please check the database connection.'
                    ]);
                }
            }
        } else {
            echo json_encode([
                'status' => 'error', 
                'message' => 'All fields are required.'
            ]);
        }
    } catch (Exception $e) {
        error_log("Exception occurred: " . $e->getMessage());
        echo json_encode([
            'status' => 'error', 
            'message' => 'An error occurred: ' . $e->getMessage()
        ]);
    }
    exit;
}

?>

<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task']) && $_POST['task'] == 'deleteEmployee') {
        $empId = mysqli_real_escape_string($db->conn, $_REQUEST['empId']);
        header('Content-Type: application/json');
                    
            try {
                if (!isset($_POST['empId']) || empty($_POST['empId'])) {
                    throw new Exception('Invalid employee ID provided');
                }
                $empId = mysqli_real_escape_string($db->conn, $_POST['empId']);
                    
                // Prepare and execute delete query
                $query = "DELETE FROM employee WHERE Emp_ID = ?";
                $stmt = $db->conn->prepare($query);
                
                if (!$stmt) {
                    throw new Exception('Failed to prepare delete statement');
                }
                $stmt->bind_param("s", $empId);
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        echo json_encode([
                            'status' => 'success',
                            'message' => 'Employee record deleted successfully.'
                        ]);
                    } else {
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'No employee found with the provided ID.'
                        ]);
                    }
                } else {
                    throw new Exception('Failed to execute delete statement');
                }
                $stmt->close();
            } catch (Exception $e) {
                error_log("Error deleting employee: " . $e->getMessage());
                echo json_encode([
                    'status' => 'error',
                    'message' => 'An error occurred while deleting the employee record.'
                ]);
            }
            exit;
            }
?>






