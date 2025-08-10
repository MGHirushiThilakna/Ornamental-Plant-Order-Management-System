<?php
header('Content-Type: application/json');

// Ensure $item_id is received
$item_id = $_GET['item_id'] ?? '';

if (empty($item_id)) {
    echo json_encode([]);
    exit;
}

// Set the directory path
$directoryPath = "../assets/imgs/item/$item_id";

// Check if the directory exists
if (!is_dir($directoryPath)) {
    echo json_encode([]); // Return an empty array if the directory does not exist
    exit;
}

// Fetch all image files in the directory
$imageFiles = glob("$directoryPath/*.{jpg,jpeg,png,gif}", GLOB_BRACE);

// Create relative URLs for the images
$imagePaths = array_map(function ($file) {
    return $file; // Use the relative path for images
}, $imageFiles);

// Return the image paths as JSON
echo json_encode($imagePaths);

?>