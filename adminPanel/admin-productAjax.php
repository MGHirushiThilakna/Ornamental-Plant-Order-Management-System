<?php include('../config/constant.php');

// Prevent any output before intended JSON response
ob_clean();

if (isset($_POST['task'])) {
    switch ($_POST['task']) {
        case 'displayAllProducts':
            displayAllProducts();
            break;
        case 'search':
            searchProducts($_POST['field'], $_POST['searchData']);
            break;
        case 'deleteProduct':
            deleteProduct($_POST['item_id']);
            break;
        case 'getColorPrices':
            getColorPrices($_POST['item_id']);
            break;
        case 'getImages':
            getImages($_POST['item_id']);
            break;
    }
    exit; // Ensure no additional output
}

function displayAllProducts()
{
    global $conn;
    $sql = "SELECT * FROM item";
    $res = mysqli_query($conn, $sql);
    outputProducts($res);
}

function searchProducts($field, $data)
{
    global $conn;
    $field = mysqli_real_escape_string($conn, $field);
    $data = mysqli_real_escape_string($conn, $data);

    $allowedFields = [
        'item_id' => 'string',
        'name' => 'string',
        'price' => 'float',
        'category' => 'string',
        'colors' => 'string',
        'status' => 'string'
    ];

    if (!array_key_exists($field, $allowedFields)) {
        echo "Invalid search field";
        return;
    }

    if ($allowedFields[$field] === 'int') {
        $sql = "SELECT * FROM item WHERE $field = '$data'";
    } elseif ($allowedFields[$field] === 'float') {
        $sql = "SELECT * FROM item WHERE $field = '$data'";
    } else {
        $sql = "SELECT * FROM item WHERE $field LIKE '%$data%'";
    }

    $res = mysqli_query($conn, $sql);
    outputProducts($res);
}

function outputProducts($res)
{
    $count = mysqli_num_rows($res);
    if ($count > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $item_id = $row['item_id'];
            $name = $row['name'];
            $price = $row['price'];
            $category = $row['category'];
            $description = $row['description'];
            $watering = $row['watering'];
            $light = $row['light'];
            $soil = $row['soil'];
            $colors = $row['colors'];
            $quantity = $row['quantity'];
            $status = $row['status'];

            if ($colors == "Yes") {
                ?>
                <tr>
                    <td><?php echo $item_id; ?></td>
                    <td><?php echo $name; ?></td>
                    <td><button style="border-radius: 10px;background-color:#E8751A; color: black;"
                            onclick="viewColorPrices('<?php echo $item_id; ?>')">View</button></td>
                    <td><?php echo $category; ?></td>
                    <td><?php echo $description; ?></td>
                    <td><?php echo $watering; ?></td>
                    <td><?php echo $light; ?></td>
                    <td><?php echo $soil; ?></td>
                    <td><button style="border-radius: 10px;background-color:#E8751A; color: black;"
                            onclick="viewImages('<?php echo $item_id; ?>')">View</button></td>
                    <td><?php echo $colors; ?></td>
                    <td><?php echo $quantity; ?></td>
                    <td><?php echo $status; ?></td>
                    <td>
                        <div style="display: flex; flex-direction: row; align-items: center;">
                            <div style="margin-left:45px;margin-right:15px;">
                                <a href="./editProduct.php?item_id=<?php echo $item_id; ?>"><button class="btn btn-outline-success"><i
                                            class="fas fa-edit"></i></button>
                            </div></a>
                            <div>
                                <a href="javascript:void(0);" onclick="confirmDelete('<?php echo $item_id; ?>')">
                                    <button class="btn btn-outline-danger"><i class="fas fa-trash-alt"></i></button>
                            </div></a>
                        </div>
                    </td>
                </tr>
            <?php } else { ?>
                <tr>
                    <td><?php echo $item_id; ?></td>
                    <td><?php echo $name; ?></td>
                    <td><?php echo $price; ?></td>
                    <td><?php echo $category; ?></td>
                    <td><?php echo $description; ?></td>
                    <td><?php echo $watering; ?></td>
                    <td><?php echo $light; ?></td>
                    <td><?php echo $soil; ?></td>
                    <td><button style="border-radius: 10px; background-color: #FFDB5C;"
                            onclick="viewImages('<?php echo $item_id; ?>')">View</button></td>
                    <td><?php echo $colors; ?></td>
                    <td><?php echo $quantity; ?></td>
                    <td><?php echo $status; ?></td>
                    <td>
                        <div style="display: flex; flex-direction: row; align-items: center;">
                            <div style="margin-left:45px;margin-right:15px;">
                                <a href="./editProduct.php?item_id=<?php echo $item_id; ?>"><button class="btn btn-outline-success"><i
                                            class="fas fa-edit"></i></button>
                            </div></a>
                            <div>
                                <a href="javascript:void(0);" onclick="confirmDelete('<?php echo $item_id; ?>')">
                                    <button class="btn btn-outline-danger"><i class="fas fa-trash-alt"></i></button>
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php
            }
        }
    } else {
        echo "<tr><td colspan='12'>No products found</td></tr>";
    }
}

// Delete product AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task = $_POST['task'];

    if ($task === 'deleteProduct') {
        $item_id = $_POST['item_id'];

        $query = "DELETE FROM item WHERE item_id = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $item_id);
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete product.']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to prepare SQL statement.']);
        }
    }
}

if ($_POST['task'] === 'getColorPrices') {
    getColorPrices($_POST['item_id']);
} elseif ($_POST['task'] === 'getImages') {
    getImages($_POST['item_id']);
}

function getColorPrices($itemId)
{
    global $conn;

    // Clear any previous output
    ob_clean();

    // Sanitize input
    $itemId = mysqli_real_escape_string($conn, $itemId);

    $sql = "SELECT color, u_price, quantity FROM colors WHERE item_id = ? AND status = 'Active'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $itemId);
    $stmt->execute();
    $result = $stmt->get_result();

    $colors = array();
    while ($row = $result->fetch_assoc()) {
        $colors[] = array(
            'color' => $row['color'],
            'u_price' => number_format($row['u_price'], 2),
            'quantity' => (int) $row['quantity']
        );
    }

    // Set proper JSON header
    header('Content-Type: application/json');
    echo json_encode($colors);
    exit;
}

function getImages($itemId)
{
    // Clear any previous output
    ob_clean();

    // Sanitize input
    $itemId = htmlspecialchars($itemId);

    $directory = "../assets/imgs/item/" . $itemId;
    $images = array();

    if (is_dir($directory)) {
        $files = scandir($directory);
        foreach ($files as $file) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']) && $file !== "." && $file !== "..") {
                // Use forward slashes for web paths
                $images[] = str_replace('\\', '/', $directory . "/" . $file);
            }
        }
    }

    // Set proper JSON header
    header('Content-Type: application/json');
    echo json_encode($images);
    exit;
}

// Your existing displayAllProducts, searchProducts, and other functions remain the same...

function deleteProduct($item_id)
{
    global $conn;

    ob_clean();
    header('Content-Type: application/json');

    $query = "DELETE FROM item WHERE item_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $item_id);
        $success = $stmt->execute();
        $stmt->close();

        echo json_encode(['success' => $success]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare SQL statement.']);
    }
    exit;
}

?>


<script>
    function sendItemId(itemId) {
        console.log('Item ID being sent:', itemId); // Debugging line
        $.ajax({
            url: 'process_item.php',
            type: 'POST',
            data: { item_id: itemId },
            success: function (response) {
                console.log('Response from server: ' + response);
            },
            error: function () {
                console.log('An error occurred.');
            }
        });
    }

</script>