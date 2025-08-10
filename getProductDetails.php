<?php
// In getProductDetails.php
include('./config/constant.php'); 
header('Content-Type: application/json');

if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

$item_id = $_GET['item_id'];
error_log("Fetching details for item ID: $item_id"); 

// Fetch main item details from item table including category name from main_category table 
$item_sql = "SELECT 
                i.item_id, 
                i.name, 
                i.price, 
                i.description, 
                i.colors,
                i.quantity, 
                COALESCE(mc.Cate_Name, 'Uncategorized') AS category
             FROM 
                item i
             LEFT JOIN 
                main_category mc ON i.category = mc.Main_ID
             WHERE 
                i.item_id = ?";

$stmt = $conn->prepare($item_sql);
$stmt->bind_param("s", $item_id);
$stmt->execute();
$item_result = $stmt->get_result();
$item = $item_result->fetch_assoc();

error_log("Item details: " . json_encode($item)); 

if (isset($item['quantity'])) {
    error_log("Item quantity: " . $item['quantity']);
} else {
    error_log("Item quantity is missing.");
}
// Fetch images from folder
$image_folder = "./assets/imgs/item/$item_id/";
$image_files = [];

if (is_dir($image_folder)) {
    $files = scandir($image_folder);
    foreach ($files as $file) {
        if (!in_array($file, ['.', '..']) && preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
            $image_files[] = $image_folder . $file;
        }
    }
}
error_log("Image files: " . json_encode($image_files)); 
$item['images'] = $image_files;

// Fetch color variations
if ($item['colors'] === 'Yes') {
    $colors_sql = "SELECT 
                    clr_id, 
                    color, 
                    quantity, 
                    u_price 
                   FROM 
                    colors 
                   WHERE 
                    item_id = ? AND 
                    status = 'Active'";
    
    $colors_stmt = $conn->prepare($colors_sql);
    $colors_stmt->bind_param("s", $item_id);
    $colors_stmt->execute();
    $colors_result = $colors_stmt->get_result();
    
    $colors = [];
    while ($color_row = $colors_result->fetch_assoc()) {
        $colors[] = [
            'color_id' => $color_row['clr_id'],
            'color' => $color_row['color'],
            'quantity' => $color_row['quantity'],
            'u_price' => $color_row['u_price']
        ];
    }
    
    $item['color_variations'] = $colors;
} else {
    $item['color_variations'] = [];
}

echo json_encode($item);

$stmt->close();
$conn->close();
?>
 