<?php  
require_once "GenerateID.php";
class EmployeeController{
    public function __construct(){
        $db = new DatabaseConnection;
        $this->generateId = new GenerateID;
        $this->conn = $db ->conn;
    }
    public function addEmployee($data){ //add employee
        $idType = "employee";
        $employeeID = $this->generateId->getNewID($idType);
        $fname = $data['fname'];
        $lname = $data['lname'];
        $jobRol = $data['Job_Role'];
        $contact = $data['contact'];
        $email = $data['email'];
        $password = password_hash($data['pass'],PASSWORD_DEFAULT);
        $status = $data['emp_status'];

        // Check if the email already exists in the database  
        $emailCheck = $this->checkEmailInDB($email);
        if ($emailCheck) {
            return "Email already exists.";
        }
        $sql_create = "INSERT INTO employee VALUES
        ('$employeeID','$fname','$lname','$jobRol','$email','$password','$contact','$status')";
        if($this->conn->query($sql_create)){
            $this->generateId->updatetID($idType);
            return true;
        }else{
            return $this->conn -> error;
        }
    }
    
    public function checkEmailInDB($email){
        $sql_getcount = "SELECT COUNT(*) as count FROM employee WHERE Email = '$email';";
        $results = $this->conn->query($sql_getcount);
        $row = $results->fetch_assoc();
        if($row['count'] > 0){
            return true;
        }else{
            return false;
        }
    }
    public function getEmpData(){
        $sql = "SELECT * FROM employee";
        $results = $this->conn->query($sql);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    } 

    public function searchEMP($category,$searchData){
        $sql = "SELECT * FROM employee WHERE $category LIKE '%$searchData%'";
        $results = $this->conn->query($sql);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    }
     
    public function updateEmployee($data){
        $employeeID = $data['id'];
        $fname = $data['fname'];
        $lname = $data['lname'];
        $contact = $data['contact'];
        $sql = "UPDATE employee SET FName = '$fname',LName = '$lname',Contact_No = '$contact' WHERE Emp_ID = '$employeeID'";
        if($this->conn->query($sql)){
            return true;
        }else{
            return false;
        }
    }
    public function getLoginData($email){
        $sql_get_login_data = "SELECT * FROM employee WHERE Email = '$email';";
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
    public function getInfoForUpdate($id){
        $sql = "SELECT * FROM employee WHERE Emp_ID ='$id'";
        $results = $this->conn->query($sql);
        if($results->num_rows > 0){
            return $results;
        }else{
            return false;
        }
    }

    public function ChangeEmpPassword($data){
        $empID = $data['id'];
        $password = $data['password'];
        $sql_update = "UPDATE employee SET `Password`='$password' WHERE Emp_ID = '$empID';";
        $result = $this->conn->query($sql_update);
        if($result){
            return true;
        }else{
            return false;
        }
    }
    public function getEmpDetailsforEdit($empId){
        $sql = "SELECT * FROM employee WHERE Emp_ID = '$empId';";
        $results = $this->conn->query($sql);
        if ($results->num_rows > 0) {
            return $results->fetch_assoc(); // Fetch the row as an associative array
        } else {
            return false; // Return false if no record is found
        }
    }

    public function checkExistEmployeeForUpdate($empId, $jobRole, $email ) {
        // Debugging: Log the mainId
        error_log("Checking email duplicate for emp_ID: " . $empId);
    
          $query = "SELECT COUNT(*) as count From employee WHERE Email = ? AND Job_Role = ? AND Emp_ID != ?";
        
          $stmt = $this->conn->prepare($query);
          if ($stmt) {
              $stmt->bind_param("sss", $email, $jobRole, $empId);
              
              if ($stmt->execute()) {
                  $result = $stmt->get_result();
                  if ($result) {
                      $row = $result->fetch_assoc();
                      return ($row['count'] > 0);
                  }
              }
              error_log("SQL Error: " . $stmt->error);
          }
          return false;
      }

    public function updateEmpFromAdmin($empId, $jobRole, $email, $status  ) {
        // Debugging: Log the mainId
          error_log("Updating employee  with emp_ID: " . $empId);
        $sql = "UPDATE employee SET Job_Role = ?, Email = ?, Emp_status = ?  WHERE Emp_ID  = ?";
        
        // Prepare the statement
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("ssss", $jobRole, $email, $status, $empId);
            
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

    public function changeEmpPasswordByEmail($data){
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
}
?>