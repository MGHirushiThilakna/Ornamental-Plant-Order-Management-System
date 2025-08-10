<?php 
 class GenerateID{
    public function  __construct(){
        $db = new DatabaseConnection;
        $this->conn = $db ->conn;
    }
    private $id_No;
    public function getNewID($id_Type){
        $sql = "SELECT ID_no,Id_Prefix FROM generate_id WHERE ID_Type ='$id_Type';";
        $result = $this->conn->query($sql);
        $row =$result -> fetch_assoc();
        $this->id_No = $row['ID_no'];
        return $row['Id_Prefix'].$row['ID_no'];
    }
    public function updatetID($id_Type){
        $this->id_No= $this->id_No+1;
        $sql= "UPDATE generate_id SET ID_no = '$this->id_No' WHERE ID_Type = '$id_Type';";
        $this->conn->query($sql);
    }
 }

?>