<?php
header('Content-Type: application/json');

$item_id = $_POST['item_id'] ?? '';
if (empty($item_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Item ID is missing.']);
    exit;
}

// Directory to save images
$uploadDir = "../assets/imgs/item/$item_id/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$imagePaths = [];
foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
    $fileName = basename($_FILES['images']['name'][$key]);
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($tmpName, $targetPath)) {
        $imagePaths[] = str_replace('../', '/', $targetPath);
    }
}

if (empty($imagePaths)) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to upload images.']);
} else {
    echo json_encode(['status' => 'success', 'message' => 'Images uploaded successfully.', 'imagePaths' => $imagePaths]);
}

?>