<html>
<body>
        <div class="top-header">
  <div class="contact-info">
    <span>Phone: (+94) 112960036</span>
    <span>Email: sunshineplanthouse.store@gmail.com</span>
  </div>
  <div class="user-actions">
  <a href="./index.php" class="hover:underline">Home</a>
    <a href="#">My Account</a>
  </div>
</div>

</body>
</html>
<?php
session_start(); 

include "classes\DBConnect.php";
include "classes\CustomerController.php";

$db = new DatabaseConnection;
$customerObj = new CustomerController();

if(isset($_POST['login'])){
    $email_unsafe=$_POST['email'];
    $pass_unsafe=$_POST['password'];

    $email=mysqli_real_escape_string($db->conn,$email_unsafe);
    $pass=mysqli_real_escape_string($db->conn,$pass_unsafe);
 
            $cusResult = $customerObj->getLoginData($email);
            if($cusResult){
                $data = $cusResult -> fetch_assoc();
                $customer_id = $data['Customer_ID'];
                $fname = $data['FName'];
                $lname = $data['LName'];
                $password = $data['Password'];
                $email = $data['Email'];
                $status = $data['Status'];
                $verify_customer=password_verify($pass, $password);
                if( $status === "Active"){
                    if($verify_customer){
                        
                        $_SESSION['Customer_ID']=$customer_id;
                        $_SESSION['Name']=$fname." ".$lname;
                        $_SESSION['CurrentPassword'] = $password;
                        $_SESSION['USerEmail'] = $email;
                                header("Location: customerPanel/customerDashboard.php");
                                exit();
                            } else {
                                $message = "Incorrect Password Customer please use correct password !";
                                header("Location: customerLogin.php?message=".urlencode($message));
                                exit();
                            }
                          }else{
                            $message = "You are Banned Customer!";
                            header("Location: customerLogin.php?message=$message");
                            exit();
                          } 
            }else{
                $message = "Incorrect Username!";
                header("Location: customerLogin.php?message=$message");
                        exit();
            }
        }
    
        include "loginHeader.php";
include "footer.php";
?>
