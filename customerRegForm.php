
<?php 
include "classes\DBConnect.php";
include "classes\CustomerController.php";
$db = new DatabaseConnection;
$customerObj = new CustomerController();
session_start();
if(isset($_SESSION['customerID'])){
    $userPassword = $_SESSION['CurrentPassword'];
    $CustomerID = $_SESSION['customerID'];
}
?>

<?php
if(isset($_POST['task']) && $_POST['task'] == 'create'){ ?>
    <!-- modal header -->
    <div class="modal-header my-modal-header">
                <div class="modal-title My-regForm-title">Sunshine Plant House -Create Your Account</div>
                <button type="button" class="btn-close my-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
           <!-- header close -->

           <!-- modal body -->
            
           <div class="modal-body" >
                <div class="row  img-row" >
                    <div class="col-md-5 img-col">
                    <img src="assets\imgs\crreateAccount.jpg" class="img-fluid reg-img" alt="...">
                    </div>
                    <div class="col-md-6 d-flex justify-content-center align-items-center" style="margin-left:50px;">
                        <div class="card mt-2 ">
                            <div class="card-body">
                                <form id="customerRegisterForm">
                                    <div class="row mb-3">
                                        <div class="col-md-6 mb-2">
                                        <input type="text" name="Fname" id="Fname" class="form-control myinputText" placeholder="First Name">
                                        <div id="strFnameError"></div>
                                        </div>
                                        <div class="col-md-6">
                                        <input type="text" name="Lname" id="Lname" class="form-control myinputText" placeholder="Last Name">
                                        <div id="strLnameError"></div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12 mb-2">
                                        <input type="text" name="cusEmail" id="cusEmail" class="form-control myinputText" placeholder="Email Address">
                                        <div id="strEmailError"></div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12 mb-2">
                                        <input type="password" name="password" id="password" class="form-control myinputText" placeholder="Password">
                                        <div id="strPasswordError"></div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12 mb-2">
                                        <input type="password" name="repeat_password" id="repeat_password" class="form-control myinputText" placeholder="Repeat Password">
                                        <div id="strRePasswordError"></div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col"><button class="btn my-reg-btn" type="submit">Create Account</button></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
           </div>
           <script src="assets\js\form-validation\cusRegister-form-validation.js"></script>
           <!-- body close -->
<?php }
?>

<?php 
if(isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['status'])){
    $data = [
        "fname" => mysqli_real_escape_string($db->conn,$_POST['fname']),
        "lname" => mysqli_real_escape_string($db->conn,$_POST['lname']),
        "email" => mysqli_real_escape_string($db->conn,$_POST['email']),
        "pass" => mysqli_real_escape_string($db->conn,$_POST['pass']),
        "status" => mysqli_real_escape_string($db->conn,$_POST['status']),
    ];
    $result = $customerObj -> createCustomerAccount($data);
    if($result){
        echo 1;
    }else{
        echo 0;
    }
}
?>

<?php 
if(isset($_REQUEST['validateEmail'])){
    $res = $customerObj -> checkEmailInDB($_REQUEST['validateEmail']);
    if($res){
        echo 1;
    }else{
        echo 0;
    }
}

if(isset($_REQUEST['fname']) && isset($_REQUEST['lname']) && isset($_REQUEST['address']) && isset($_REQUEST['customer']) && isset($_REQUEST['contact'])){
    $data_update = [
        "cid" => mysqli_real_escape_string($db->conn,$_REQUEST['customer']),
        "fname" => mysqli_real_escape_string($db->conn,$_REQUEST['fname']),
        "lname" => mysqli_real_escape_string($db->conn,$_REQUEST['lname']),
        "address" => mysqli_real_escape_string($db->conn,$_REQUEST['address']),
        "contact" => mysqli_real_escape_string($db->conn,$_REQUEST['contact'])
    ];
    $result = $customerObj -> updateCustomerInfo($data_update);
    if($result){
        echo 1;
    }else{
        echo 0;
    }
}

if(isset($_REQUEST['password'])){
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
        "cid" => mysqli_real_escape_string($db->conn,$CustomerID),
        "password" => mysqli_real_escape_string($db->conn,$password)
    ];
    $result = $customerObj -> changePassword($update);
    if($result){
        echo 1;
    }else{
        echo 0;
    }
}?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task']) && $_POST['task'] === 'displayAllCustomers') {
        displayAllCustomer();
    } 
}

function displayAllCustomer() {
    global $customerObj;
    $results = $customerObj->getCusDataForAdmin();
    if($results){
        foreach($results as $row){
            ?> 
            
            <tr>
                <td><?=$row['Customer_ID']?></td>
                <td><?=$row['FName']?></td>
                <td><?=$row['LName']?></td>
                <td><?=$row['Email']?></td>
                <td><?=$row['Contact_NO']?></td>
                <td><?=$row['completed_orders']?></td>
                <td><?=$row['pending_orders']?></td>
                <td><?=$row['Status']?></td>
                <td>
                        <div style="display: flex; flex-direction: row; align-items: center;">
                            <div style="margin-right:5px;">
                            <a href="#" data-customer-id="<?=$row['Customer_ID']?>" data-toggle="modal" data-target="#editCustomerModal">
                                <button class="btn btn-outline-success" ><i class="fas fa-edit"></i></button>

                                </a>
                            </div>
                        </div>
                    </td>
            </tr>
            <?php
        }
    } else {
        echo "<tr><td colspan='8'>No customers found</td></tr>";
    }
}
?>


<?php 
if(isset($_REQUEST['task']) && $_REQUEST['task'] === 'search'){
    global $customerObj;
    $results = $customerObj->searchCustomer($_REQUEST['field'], $_REQUEST['searchData']);
    if($results){
        foreach($results as $row){
            ?>
            <tr>
                <td><?=$row['Customer_ID']?></td>
                <td><?=$row['FName']?></td>
                <td><?=$row['LName']?></td>
                <td><?=$row['Email']?></td>
                <td><?=$row['Contact_NO']?></td>
                <td><?=$row['completed_orders']?></td>
                <td><?=$row['pending_orders']?></td>
                <td><?=$row['Status']?></td>
                <td>
                    <div style="display: flex; flex-direction: row; align-items: center;">
                        <div style="margin-right:5px;">
                            <a href="#" data-customer-id="<?=$row['Customer_ID']?>" data-toggle="modal" data-target="#editCustomerModal">
                                <button class="btn btn-outline-success"><i class="fas fa-edit"></i></button>
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
            <?php
        }
    } else {
        echo "<tr><td colspan='9'>No customers found</td></tr>";
    }
}
?>

<?php
if ($_POST['task'] === 'getCustomerDetails') {
    $customerId = $_POST['customer_id'];
        $customerId = mysqli_real_escape_string($db->conn, $_POST['customer_id']); // Ensure it's sanitized
        $customerDetails  = $customerObj->getCustomerById($customerId);
    
        if ($customerDetails) {
            echo json_encode(['success' => true, 'customer' => $customerDetails]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Customer not found.']);
        }
    exit;
    }
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task']) && $_POST['task'] === 'updateCustStatus') {
    $customerId = $_POST['customer_id'];
    $status = $_POST['status'];

    // Validate input
    if (empty($customerId) || empty($status)) {
        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
        exit;
    }

    try {
        $result = $customerObj->updateCustomerStatus($customerId, $status);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Customer status updated.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update customer status.']);
        }
    } catch (Exception $e) {
        error_log("Error updating customer status: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'An error occurred.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>