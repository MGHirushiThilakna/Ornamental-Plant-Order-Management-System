<?php 
include "backendHeader.php";
include "classes\DBConnect.php";
include "classes\EmployeeController.php";
include "classes\DeliveryDriverController.php";

$db = new DatabaseConnection;
$employeeObj = new EmployeeController();
$driverObj = new DeliveryDriverController();

if(isset($_POST['login'])) {
    $email_unsafe = $_POST['email'];
    $pass_unsafe = $_POST['password'];

    $email = mysqli_real_escape_string($db->conn, $email_unsafe);
    $password = mysqli_real_escape_string($db->conn, $pass_unsafe);

    // First check employee table (includes both staff and admin)
    $EmpResult = $employeeObj->getLoginData($email);
    
    if($EmpResult && $EmpResult->num_rows > 0) {
        $data = $EmpResult->fetch_assoc();
        
        if(password_verify($pass_unsafe, $data['Password'])) {
            if($data['Emp_status'] != 'Active') {
                $message = "Your Account is Inactive!";
                header("Location: staffLogin.php?message=$message");
                exit();
            }

            // Start session and set common session variables
            session_start();
            $_SESSION['empID'] = $data['Emp_ID'];
            $_SESSION['Name'] = $data['FName'] . " " . $data['LName'];
            $_SESSION['CurrentPassword'] = $data['Password'];
            $_SESSION['UserEmail'] = $email;

            // Redirect based on role
            if($data['Job_Role'] == 'Staff') {
                echo "<meta http-equiv='refresh' content='2;url=staffPanel\index.php'>";
                $roleText = "Staff Member";

            } else if($data['Job_Role'] == 'Admin') {
                echo "<meta http-equiv='refresh' content='2;url=adminPanel\index.php'>";
                $roleText = "Admin Member";

            }else if($data['Job_Role'] == 'Stock Keeper') {
                echo "<meta http-equiv='refresh' content='2;url=stockKeeperPanel\index.php'>";
                $roleText = "Store Keeper";
            }

            // Show loading progress
            echo "<div class='container mt-5'>
                    <div class='progress'>
                        <div class='progress-bar progress-bar-striped progress-bar-animated' 
                             role='progressbar' aria-valuenow='100' aria-valuemin='0' 
                             aria-valuemax='100' style='width: 100%; background-color:#3d8361;'>
                            <span class='itext'>Please Wait {$roleText}!...</span>
                        </div>
                    </div>
                    <br>
                </div>";
            exit();
        } else {
            $message = "Incorrect Password!";
            header("Location: staffLogin.php?message=$message");
            exit();
        }
    } else {
        // If not found in employee table, check driver table
        $DriverRes = $driverObj->getLoginDriverData($email);
        
        if($DriverRes && $DriverRes->num_rows > 0) {
            $data = $DriverRes->fetch_assoc();
            
            if(password_verify($pass_unsafe, $data['Password'])) {
                if($data['Status'] != 'Active') {
                    $message = "Your Driver Account is Inactive!";
                    header("Location: staffLogin.php?message=$message");
                    exit();
                }

                // Start session and set driver session variables
                session_start();
                $_SESSION['driverID'] = $data['Driver_ID'];
                $_SESSION['Name'] = $data['FName'] . " " . $data['LName'];
                $_SESSION['CurrentPassword'] = $data['Password'];
                $_SESSION['UserEmail'] = $email;

                echo "<meta http-equiv='refresh' content='2;url=deliveryPanel\index.php'>";
                echo "<div class='container mt-5'>
                        <div class='progress'>
                            <div class='progress-bar progress-bar-striped progress-bar-animated' 
                                 role='progressbar' aria-valuenow='100' aria-valuemin='0' 
                                 aria-valuemax='100' style='width: 100%; background-color:#3d8361;'>
                                <span class='itext'>Please Wait Driver!...</span>
                            </div>
                        </div>
                        <br>
                    </div>";
                exit();
            } else {
                $message = "Incorrect Password!";
                header("Location: staffLogin.php?message=$message");
                exit();
            }
        } else {
            $message = "Account not found!";
            header("Location: staffLogin.php?message=$message");
            exit();
        }
    }
}

include "footer.php";
?>