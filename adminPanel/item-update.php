<?php
include('../config/constant.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_id = $_POST['item_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['des']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $watering = mysqli_real_escape_string($conn, $_POST['watering']);
    $light = mysqli_real_escape_string($conn, $_POST['light']);
    $soil = mysqli_real_escape_string($conn, $_POST['soil']);

    // Check for existing colors in database
    $check_colors = "SELECT COUNT(*) as count FROM colors WHERE item_id = '$item_id'";
    $check_result = mysqli_query($conn, $check_colors);
    $has_existing_colors = mysqli_fetch_assoc($check_result)['count'] > 0;

    // Get the current checkbox state
    $checkbox = isset($_POST['checkbox']) ? true : false;

    if (!$checkbox && !$has_existing_colors) {
        // Case 1: No colors (checkbox unchecked and no existing colors)
        $unitPrice = mysqli_real_escape_string($conn, $_POST['unitprice']);
        $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);

        $sql = "UPDATE item SET 
                name = '$name',
                price = '$unitPrice',
                category = '$category',
                description = '$description',
                quantity = '$quantity',
                colors = 'No',
                watering = '$watering',
                light = '$light',
                soil = '$soil',
                status = '$status'
                WHERE item_id = '$item_id'";

        $res = mysqli_query($conn, $sql);

    } else {
        // Case 2: With colors (either checkbox checked or existing colors)
        // Update main item record
        $sql = "UPDATE item SET 
                name = '$name',
                colors = 'Yes',
                category = '$category',
                description = '$description',
                watering = '$watering',
                light = '$light',
                soil = '$soil',
                status = '$status'
                WHERE item_id = '$item_id'";

        $res = mysqli_query($conn, $sql);

        if ($res) {
            $total_quantity = 0;

            // Process all color inputs
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'color') === 0 && $key !== 'checkbox') {
                    $index = substr($key, 5);
                    if (is_numeric($index)) {
                        $color = mysqli_real_escape_string($conn, $value);
                        $quantity = mysqli_real_escape_string($conn, $_POST['quantityc' . $index]);
                        $price = mysqli_real_escape_string($conn, $_POST['ucprice' . $index]);
                        $clr_id = isset($_POST['clr_id' . $index]) ? mysqli_real_escape_string($conn, $_POST['clr_id' . $index]) : null;

                        $total_quantity += intval($quantity);

                        if ($clr_id) {
                            // Update existing color
                            $sql = "UPDATE colors SET 
                            color = '$color',
                            quantity = '$quantity',
                            u_price = '$price',
                            status = '$status'
                            WHERE clr_id = '$clr_id' AND item_id = '$item_id'";
                        } else {
                            // Insert new color only if there's no clr_id
                            $sql_max = "SELECT MAX(CAST(SUBSTRING(clr_id, 4) AS UNSIGNED)) AS max_id FROM colors WHERE clr_id LIKE 'CLR%'";
                            $result = mysqli_query($conn, $sql_max);
                            $row = mysqli_fetch_assoc($result);
                            $next_id = 'CLR' . ($row['max_id'] + 1);

                            $sql = "INSERT INTO colors (clr_id, item_id, color, quantity, u_price, status) 
                            VALUES ('$next_id', '$item_id', '$color', '$quantity', '$price', '$status')";
                        }
                        mysqli_query($conn, $sql);
                    }
                }
            }

            // Update total quantity in item table
            $sql = "UPDATE item SET quantity = '$total_quantity' WHERE item_id = '$item_id'";
            mysqli_query($conn, $sql);
        }
    }

    // Handle all image uploads
    if (isset($_FILES)) {
        $directory = '../assets/imgs/item/' . $item_id . '/';
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Process both main images and color images
        foreach ($_FILES as $fileKey => $fileArray) {
            if (!empty($fileArray['name'][0])) {
                // Check if it's a color image or main image
                $isColorImage = strpos($fileKey, 'imagec') === 0;

                foreach ($fileArray['name'] as $index => $imageName) {
                    if ($fileArray['error'][$index] == UPLOAD_ERR_OK) {
                        $fileExtension = pathinfo($imageName, PATHINFO_EXTENSION);
                        $uniqueName = uniqid() . '.' . $fileExtension;
                        $imagePath = $directory . $uniqueName;

                        if (!move_uploaded_file($fileArray['tmp_name'][$index], $imagePath)) {
                            $_SESSION['update-image-error'] = "error";
                            header('location: ./editProduct.php?item_id=' . $item_id);
                            exit;
                        }
                    }
                }
            }
        }
    }

    if ($res) {
        $_SESSION['update-item-success'] = "Success";
    } else {
        $_SESSION['update-item-error'] = "error";
    }

    header('location: ./editProduct.php?item_id=' . $item_id);
    exit;
}
?>