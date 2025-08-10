<?php
include "..\classes\DBConnect.php";
$db = new DatabaseConnection;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add':
            $name = $_POST['charge_name'] ?? '';
            $value = $_POST['charge_value'] ?? '';
            
            $sql = "INSERT INTO delivery_charge (name, value, update_date) VALUES (?, ?, NOW())";
            $stmt = $db->conn->prepare($sql);
            $stmt->bind_param("sd", $name, $value);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add delivery charge']);
            }
            break;
            
        case 'edit':
            $id = $_POST['charge_id'] ?? '';
            $name = $_POST['charge_name'] ?? '';
            $value = $_POST['charge_value'] ?? '';
            
            $sql = "UPDATE delivery_charge SET name = ?, value = ?, update_date = NOW() WHERE deli_charge_id = ?";
            $stmt = $db->conn->prepare($sql);
            $stmt->bind_param("sdi", $name, $value, $id);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update delivery charge']);
            }
            break;
            
        case 'delete':
            $id = $_POST['charge_id'] ?? '';
            
            $sql = "DELETE FROM delivery_charge WHERE deli_charge_id = ?";
            $stmt = $db->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete delivery charge']);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>