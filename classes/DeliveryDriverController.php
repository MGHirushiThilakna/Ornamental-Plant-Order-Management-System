<?php 
require_once "GenerateID.php";
class DeliveryDriverController{
    public function __construct(){
        $db = new DatabaseConnection;
        $this->generateId = new GenerateID;
        $this->conn = $db ->conn;
    }
    public function addDeliveryDriver($data){
        $idType = "delivery";
        $DelDriverID = $this->generateId->getNewID($idType);
        $fname = $data['fname'];
        $lname = $data['lname'];
        $VNum = $data['VNum'];
        $contact = $data['contact'];
        $email = $data['email'];
        $password = password_hash($data['pass'],PASSWORD_DEFAULT);
        $status = $data['driver_status'];
        $sql_create = "INSERT INTO deliver_driver VALUES('$DelDriverID','$fname','$lname','$VNum','$email','$password','$contact','$status')";
        if($this->conn->query($sql_create)){
            $this->generateId->updatetID($idType);
            return true;
        }else{
            return $this->conn -> error;
        }
    }

    public function getDeliveryDriverInfo(){
        $sql = "SELECT * FROM deliver_driver";
        $results = $this->conn->query($sql);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    } 
    public function updateDriverProfile($data){
        $DelDriverID = $data['id'];
        $fname = $data['fname'];
        $lname = $data['lname'];
        $contact = $data['contact'];
        $sql = "UPDATE deliver_driver SET FName = '$fname',LName = '$lname',Contact_No = '$contact' WHERE Driver_ID = '$DelDriverID'";
        if($this->conn->query($sql)){
            return true;
        }else{
            return false;
        }
    }
    public function searchDriver($category,$searchData){
        $sql = "SELECT * FROM deliver_driver WHERE $category LIKE '%$searchData%'";
        $results = $this->conn->query($sql);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    }

    public function getInfoForUpdate($id){
        $sql = "SELECT * FROM deliver_driver WHERE Driver_ID ='$id'";
        $results = $this->conn->query($sql);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    }

    public function UpdateDeliveryProfile($data){
        $DelDriverID = $data['id'];
        $fname = $data['fname'];
        $lname = $data['lname'];
        $VNum = $data['VNum'];
        $contact = $data['contact'];
        $sql = "UPDATE deliver_driver SET FName = '$fname', LName = '$lname', Vehicle_No = '$VNum', Contact_No = '$contact' WHERE Driver_ID = '$DelDriverID'";
        if($this->conn->query($sql)){
            return true;
        }else{
            return $this->conn -> error;
        }
    }

    public function ChangeDriverPassword($data){
        $DelDriverID = $data['id'];
        $password = $data['password'];
        $sql_update = "UPDATE deliver_driver SET `Password`='$password' WHERE Driver_ID = '$DelDriverID';";
        $result = $this->conn->query($sql_update);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function getLoginDriverData($username){
        $sql_get_login_data = "SELECT * FROM deliver_driver WHERE Email = '$username';";
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

    public function getDriverDetailsforEdit($driverId){
        $sql = "SELECT * FROM deliver_driver WHERE Driver_ID = '$driverId';";
        $results = $this->conn->query($sql);
        if ($results->num_rows > 0) {
            return $results->fetch_assoc(); // Fetch the row as an associative array
        } else {
            return false; // Return false if no record is found
        }
    }

    public function updateDriverFromAdmin($driverId, $vehicle_Num, $email ) {
        // Debugging: Log the mainId
          error_log("Updating category with Driver_ID: " . $driverId);
        $sql = "UPDATE deliver_driver SET Vehicle_No = ?, Email = ?  WHERE Driver_ID = ?";
        
        // Prepare the statement
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("sss", $vehicle_Num, $email, $driverId);
            
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

    public function getCompleteOrderDriverCount($Driver){
        $sql = "SELECT i.Invoice_ID,i.order_status,i.Payment_ID FROM invoice i,payment_cod pcod WHERE i.Payment_ID = pcod.`Payment_ID` AND i.order_status = 'Completed' AND pcod.`Driver_ID` = '$Driver'";
        $result = $this->conn->query($sql);
        if($result->num_rows > 0){
            return $result->num_rows;
        }else{
            return 0;
        }
    }

    public function getDispatchedOrderDriverCount($Driver){
        $sql = "SELECT i.Invoice_ID,i.order_status,i.Payment_ID FROM invoice i,payment_cod pcod WHERE i.Payment_ID = pcod.`Payment_ID` AND i.order_status = 'Dispatched' AND pcod.`Driver_ID` = '$Driver'";
        $result = $this->conn->query($sql);
        if($result->num_rows > 0){
            return $result->num_rows;
        }else{
            return 0;
        }
    }
    
}