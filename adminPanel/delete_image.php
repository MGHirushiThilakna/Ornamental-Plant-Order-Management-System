<?php
header('Content-Type: application/json');

// Check if the required data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imagePath = $_POST['imagePath'] ?? '';
    $item_id = $_POST['item_id'] ?? '';

    // Validate inputs
    if (empty($imagePath) || empty($item_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid data provided.']);
        exit;
    }

    // Ensure the image path is within the allowed directory for security
    $allowedDir = realpath("../assets/imgs/item/$item_id");
    $imageFullPath = realpath($imagePath);

    if (!$allowedDir || !$imageFullPath || strpos($imageFullPath, $allowedDir) !== 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid image path.']);
        exit;
    }

    // Attempt to delete the image
    if (file_exists($imageFullPath) && unlink($imageFullPath)) {
        echo json_encode(['status' => 'success', 'message' => 'Image deleted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete the image.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

?>