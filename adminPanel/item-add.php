<?php
include('../config/constant.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $checkbox = isset($_POST['checkbox']) ? true : false;
    if ($checkbox) {
        $name = $_POST['name'];
        $description = $_POST['des'];
        $category = $_POST['category'];
        $status = $_POST['status'];
        $watering = $_POST['watering'];
        $light = $_POST['light'];
        $soil = $_POST['soil'];
    } else {
        $name = $_POST['name'];
        $description = $_POST['des'];
        $category = $_POST['category'];
        $unitPrice = $_POST['unitprice'];
        $quantity = $_POST['quantity'];
        $watering = $_POST['watering'];
        $light = $_POST['light'];
        $soil = $_POST['soil'];
        $status = $_POST['status'];

        $sql = "SELECT MAX(CAST(SUBSTRING(item_id, 4) AS UNSIGNED)) AS max_id FROM item WHERE item_id LIKE 'ITM%'";
        $result = mysqli_query($conn, $sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $last_id_number = $row["max_id"];
            $next_id_number = $last_id_number + 1;

            $next_id = "ITM" . $next_id_number;
        } else {
            $next_id = "ITM1";
        }
        $directory = '../assets/imgs/item/';
        $folderName = $next_id;
        if (!file_exists($directory . $folderName)) {
            if (mkdir($directory . $folderName, 0777, true)) {
                $imagePaths = [];
                if (isset($_FILES['image01']) && count($_FILES['image01']['name']) > 0) {
                    foreach ($_FILES['image01']['name'] as $index => $imageName) {
                        if ($_FILES['image01']['error'][$index] == UPLOAD_ERR_OK) {
                            $imageTmpPath = $_FILES['image01']['tmp_name'][$index];
                            $imagePath = "../assets/imgs/item/" . $folderName . "/" . $imageName;

                            if (move_uploaded_file($imageTmpPath, $imagePath)) {
                                $imagePaths[] = $imagePath;
                            } else {
                                $_SESSION['add-image-error'] = "error";
                                header('location: ./addProduct.php');
                                exit;
                            }
                        }
                    }
                }

            } else {
                $_SESSION['add-image-error'] = "error";
                header('location: ./addProduct.php');
                exit;
            }
        } else {
            echo "Folder already exists.";
        }
        $sql1 = "INSERT INTO item SET
            item_id = '$next_id',
            name = '$name',
            price = '$unitPrice',
            colors = 'No',
            category = '$category',
            description = '$description',
            quantity = '$quantity',
            watering = '$watering',
            light = '$light',
            soil = '$soil',
            status = '$status'
        ";
        $res1 = mysqli_query($conn, $sql1);
        if ($res1 == TRUE) {
            $_SESSION['add-item-success'] = "Success";
            header('location: ./addProduct.php');
        } else {
            $_SESSION['add-item-error'] = "error";
            header('location: ./addProduct.php');
            exit;
        }
    }
    if ($checkbox) {
        // Get the last item_id and create the next item_id
        $sql = "SELECT MAX(CAST(SUBSTRING(item_id, 4) AS UNSIGNED)) AS max_id FROM item WHERE item_id LIKE 'ITM%'";
        $result = mysqli_query($conn, $sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $last_id_number = $row["max_id"];
            // Increment the last ACT... number
            $next_id_number = $last_id_number + 1;
            // Construct the next ACT... value
            $next_id1 = "ITM" . $next_id_number;
        } else {
            $next_id1 = "ITM1";
        }
        //add item_id, name, colors, category, status and description to the db 
        $sql1 = "INSERT INTO item SET
            item_id = '$next_id1',
            name = '$name',
            colors = 'Yes',
            category = '$category',
            description = '$description',
            watering = '$watering',
            light = '$light',
            soil = '$soil',
            status = '$status'
        ";
        $res1 = mysqli_query($conn, $sql1);
        if ($res1 == TRUE) {
            // Loop through each color index
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'color') === 0 && !empty($value)) {
                    $index = substr($key, 5);
                    $colors[$index]['color'] = $value;
                }
                if (strpos($key, 'quantityc') === 0 && !empty($value)) {
                    $index = substr($key, 9);
                    $colors[$index]['quantity'] = $value;
                }
                if (strpos($key, 'ucprice') === 0 && !empty($value)) {
                    $index = substr($key, 7);
                    $colors[$index]['unit_price'] = $value;
                }
            }
            // Array to store color image paths
            $colorImages = [];
            $directory = '../assets/imgs/item/';
            $folderName = $next_id1;
            if (!file_exists($directory . $folderName)) {
                if (mkdir($directory . $folderName, 0777, true)) {
                    // Loop through each color image
                    foreach ($_FILES as $fileKey => $fileArray) {
                        if (strpos($fileKey, 'imagec') === 0) {
                            foreach ($fileArray['name'] as $index => $imageName) {
                                if ($fileArray['error'][$index] == UPLOAD_ERR_OK) {
                                    $imageTmpPath = $fileArray['tmp_name'][$index];
                                    $imagePath = "../assets/imgs/item/" . $folderName . "/" . $imageName;

                                    // Move the uploaded file to the server's directory
                                    if (move_uploaded_file($imageTmpPath, $imagePath)) {
                                        $colorImages[$index] = $imagePath;
                                    } else {
                                        echo "Error uploading file for color " . $index;
                                        exit;
                                    }
                                }
                            }
                        }
                    }

                } else {
                    $_SESSION['add-image-error'] = "error";
                    header('location: ./addProduct.php');
                    exit;
                }
            } else {
                echo "Folder already exists.";
            }
            $sql = "SELECT MAX(CAST(SUBSTRING(clr_id, 4) AS UNSIGNED)) AS max_id FROM colors WHERE clr_id LIKE 'CLR%'";
            $result = mysqli_query($conn, $sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $last_id_number = $row["max_id"];
                // Increment the last ACT... number
                $next_id_number = $last_id_number + 1;
                // Construct the next ACT... value
                $next_id2 = "CLR" . $next_id_number;
            } else {
                $next_id2 = "CLR1";
            }
            $total_quantity = 0;
            // Loop through each color and process them
            foreach ($colors as $index => $color) {
                $stmt = $conn->prepare("INSERT INTO colors (clr_id, item_id, color, quantity, u_price, status) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssids", $next_id2, $next_id1, $color['color'], $color['quantity'], $color['unit_price'], $status);
                $stmt->execute();
                $stmt->close();

                // Increment clr_id for the next color
                $next_id_number++;
                $next_id2 = "CLR" . $next_id_number;

                // Add to total quantity
                $total_quantity += $color['quantity'];
            }

            // Update the total quantity in the item table
            $stmt = $conn->prepare("UPDATE item SET quantity = ? WHERE item_id = ?");
            $stmt->bind_param("is", $total_quantity, $next_id1);
            $stmt->execute();
            $stmt->close();

        } else {
            $_SESSION['add-item-error'] = "error";
            header('location: ./addProduct.php');
            exit;
        }


    } else {
        // Insert the main product without color variants into the database
        // Assume you have a function or a query to insert data
        // insertProduct($name, $description, $category, $unitPrice, $quantity, $watering, $light, $soil, $imagePaths);
    }

    // Header to the addProduct.php file
    header('location: ./addProduct.php');
    exit; // To prevent further execution
} else {
    echo "Invalid request method.";
}
?>