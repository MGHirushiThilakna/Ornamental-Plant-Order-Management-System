<?php 
require_once "GenerateID.php";
class CustomerController{
    public function __construct(){
        $db = new DatabaseConnection;
        $this->generateId = new GenerateID;
        $this->conn = $db ->conn;
    }
    public function createCustomerAccount($data){
        $idType = "customer";
        $customerID = $this->generateId->getNewID($idType);
        $fname = $data['fname'];
        $lname = $data['lname'];
        $Email = $data['email'];
        $password = password_hash($data['pass'],PASSWORD_DEFAULT);
        $status = $data['status'];
        $sql_create = "INSERT INTO customer(Customer_ID,FName,LName,Email,`Password`,`Status`) VALUES('$customerID','$fname','$lname','$Email','$password','$status')";
        if($this->conn->query($sql_create)){
            $this->generateId->updatetID($idType);
            return true;
        }else{
            return false;
        }
    }
    public function checkEmailInDB($email){
        $sql_getcount = "SELECT * FROM customer WHERE Email = '$email';";
        $results = $this->conn->query($sql_getcount);
        if($results->num_rows > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getLoginData($username){
        $sql_get_login_data = "SELECT * FROM customer WHERE Email = '$username';";
        $result = $this->conn->query($sql_get_login_data);
        if($result){
            if($result->num_rows > 0){
                return $result;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function getCustomerInfo(){
        $sql = "SELECT Customer_ID,FName,LName,Email,`Address`,Contact_NO FROM customer";
        $result = $this->conn->query($sql);
        if($result->num_rows > 0){
            return $result;
        }else{
            return false;
        }

    }

    public function getInfoForUpate($customerID){
        $sql_getData = "SELECT FName,LName,Email,`Address`,Contact_NO FROM customer WHERE Customer_ID = '$customerID';";
        $result = $this->conn->query($sql_getData);
        if($result->num_rows > 0){
            return $result;
        }else{
            return false;
        }
    }

    public function updateCustomer($data){
        $customerID = $data['cid'];
        $fname = $data['fname'];
        $lname = $data['lname'];
        $address = $data['address'];
        $contact = $data['contact'];
        $sql_update = "UPDATE customer SET FName = '$fname',LName = '$lname',`Address` = '$address', Contact_NO = '$contact' WHERE Customer_ID = '$customerID';";
        $result = $this->conn->query($sql_update);
        if($result){
            return true;
        }else{
            return false;
        }
    }
    public function changePassword($data){
        $customerID = $data['cid'];
        $password = $data['password'];
        $sql_update = "UPDATE customer SET `Password`='$password' WHERE Customer_ID = '$customerID';";
        $result = $this->conn->query($sql_update);
        if($result){
            return true;
        }else{
            return false;
        }
    }
    public function changeCusPasswordByEmail($data){
        $email = $data['email'];
        $password = password_hash($data['password'],PASSWORD_DEFAULT);

        $sql_update = "UPDATE customer SET `Password`='$password' WHERE Email = '$email';";
        $result = $this->conn->query($sql_update);
        if($result){
            return true;
        }else{
            return false;
        }
    }
    public function searchCustomer($category, $searchData) {
        // Using prepared statement to prevent SQL injection
        $sql = "SELECT 
            c.Customer_ID, 
            c.FName, 
            c.LName, 
            c.Email, 
            c.Contact_NO, 
            c.Status,
            COUNT(DISTINCT CASE WHEN o.status = 'completed' THEN o.odr_id END) as completed_orders,
            COUNT(DISTINCT CASE WHEN o.status != 'completed' AND o.status IS NOT NULL THEN o.odr_id END) as pending_orders
        FROM customer c
        LEFT JOIN order_tbl o ON c.Customer_ID = o.user
        WHERE c.$category LIKE ?
        GROUP BY c.Customer_ID, c.FName, c.LName, c.Email, c.Contact_NO, c.Status";
        
        $searchTerm = "%$searchData%";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $results = $stmt->get_result();
        
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    }

    public function getCusDataForAdmin(){
        $sql = "SELECT 
        c.Customer_ID, 
        c.FName, 
        c.LName, 
        c.Email, 
        c.Contact_NO, 
        c.Status,
        COUNT(DISTINCT CASE WHEN o.status = 'Completed' THEN o.odr_id END) as completed_orders,
        COUNT(DISTINCT CASE WHEN o.status != 'Completed' AND o.status IS NOT NULL THEN o.odr_id END) as pending_orders
    FROM customer c
    LEFT JOIN order_tbl o ON c.Customer_ID = o.user
    GROUP BY c.Customer_ID, c.FName, c.LName, c.Email, c.Contact_NO, c.Status";
    
    $results = $this->conn->query($sql);
    if($results->num_rows > 0){
        return $results;
    }else{
        return false;
    }
}

    public function getCustomerById($customerId) {
        $query = "SELECT 
        c.Customer_ID, 
        c.FName, 
        c.LName, 
        c.Status,
        COUNT(DISTINCT CASE WHEN o.status != 'Completed' AND o.status IS NOT NULL THEN o.odr_id END) as pending_orders
    FROM customer c
    LEFT JOIN order_tbl o ON c.Customer_ID = o.user
    WHERE c.Customer_ID = ?
    GROUP BY c.Customer_ID, c.FName, c.LName, c.Status";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}
    
    public function updateCustomerStatus($customerId, $status) {
        $query = "UPDATE customer SET Status = ? WHERE Customer_ID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $status, $customerId);
        return $stmt->execute();
    }
    
}
?>