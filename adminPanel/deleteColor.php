<?php
include('../config/constant.php');

header('Content-Type: application/json');

// Check the connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Get the raw POST data and decode it
$data = json_decode(file_get_contents('php://input'), true);
$clr_id = $data['clr_id'] ?? null;
$item_id = $data['item_id'] ?? null;

if ($clr_id && $item_id) {
    // Prepare and execute the delete statement
    $stmt = $conn->prepare("DELETE FROM colors WHERE clr_id = ? AND item_id = ?");
    $stmt->bind_param("ss", $clr_id, $item_id);

    if ($stmt->execute()) { 
        // Fetch the total quantity for the item
        $sql = "SELECT quantity FROM colors WHERE item_id = ?";
        $stmt2 = $conn->prepare($sql);
        $stmt2->bind_param("s", $item_id);
        $stmt2->execute();
        $res = $stmt2->get_result();

        $quantity1 = 0; // Initialize $quantity1 before using it

        // Sum the quantities of remaining colors
        while ($row = $res->fetch_assoc()) {
            $quantity1 += $row['quantity'];
        }

        // Update the item quantity in the `item` table
        $sql2 = "UPDATE item SET quantity = ? WHERE item_id = ?";
        $stmt3 = $conn->prepare($sql2);
        $stmt3->bind_param("is", $quantity1, $item_id);

        if ($stmt3->execute()) {
            echo json_encode(['success' => true, 'message' => 'Color deleted and quantity updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update the quantity in the item table.']);
        }

        $stmt2->close();
        $stmt3->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete the color from the database.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid color ID or item ID.']);
}

$conn->close();
?>
