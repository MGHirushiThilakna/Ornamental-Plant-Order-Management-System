<?php
session_start();
require_once 'db_connection.php'; 

function updateInventory($orderId, $items) {
    global $conn; 
    try {
        $conn->begin_transaction();
        foreach ($items as $item) {
            $checkColorQuery = "SELECT has_colors FROM items WHERE item_id = ?";
            $stmt = $conn->prepare($checkColorQuery);
            $stmt->bind_param("i", $item['item_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $itemDetails = $result->fetch_assoc();

            if ($itemDetails['has_colors'] == 'Yes' && $item['color_id']) {
               
                $updateColorQuery = "UPDATE item_colors 
                                     SET quantity = quantity - ? 
                                     WHERE item_id = ? AND color_id = ?";
                $stmt = $conn->prepare($updateColorQuery);
                $stmt->bind_param("iii", $item['quantity'], $item['item_id'], $item['color_id']);
                $stmt->execute();
            } else {
               
                $updateItemQuery = "UPDATE items 
                                    SET quantity = quantity - ? 
                                    WHERE item_id = ?";
                $stmt = $conn->prepare($updateItemQuery);
                $stmt->bind_param("ii", $item['quantity'], $item['item_id']);
                $stmt->execute();
            }

            $removeCartQuery = "DELETE FROM cart 
                                WHERE item_id = ? 
                                AND (color_id = ? OR color_id IS NULL)";
            $stmt = $conn->prepare($removeCartQuery);
            $stmt->bind_param("ii", $item['item_id'], $item['color_id']);
            $stmt->execute();
        }

        // Commit transaction
        $conn->commit();
        return true;
    } catch (Exception $e) {
        // Rollback in case of error
        $conn->rollback();
        error_log("Inventory update error: " . $e->getMessage());
        return false;
    }
}

// Handle the inventory update after successful payment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['orderId'] ?? null;
    $items = json_decode($_POST['items'], true);

    if ($orderId && $items) {
        $result = updateInventory($orderId, $items);
        
        echo json_encode([
            'success' => $result,
            'message' => $result 
                ? 'Inventory updated successfully' 
                : 'Failed to update inventory'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid input'
        ]);
    }
    exit();
}