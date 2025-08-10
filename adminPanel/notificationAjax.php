<?php 
include "..\classes\DBConnect.php";
$db = new DatabaseConnection;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task']) && $_POST['task'] == 'addNoti') {
    $title = $_POST['title'];
    $description = $_POST['notiDes'];
    $status =  $_POST['noti_status'];

    // Fetch the last noti_id and generate a new one
    $noti_id_query = "SELECT noti_id FROM notification ORDER BY noti_id DESC LIMIT 1";
    $result = $db->conn->query($noti_id_query);

    if ($result->num_rows > 0) {
        $last_id_row = $result->fetch_assoc();
        $last_id = intval(str_replace('NOT', '', $last_id_row['noti_id']));
        $new_noti_id = 'NOT' . ($last_id + 1);
    } else {
        $new_noti_id = 'NOT1';
    }

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        $image_ext = pathinfo($image['name'], PATHINFO_EXTENSION);
         
        $image_name = $new_noti_id . '.' . $image_ext;
        
        $target_dir = "../assets/imgs/notification/";
        $target_file = $target_dir . basename($image_name);
        
        if (move_uploaded_file($image['tmp_name'], $target_file)) {
            // Insert data into the notification table
            $sql = "INSERT INTO notification (noti_id, noti_title, noti_desc, noti_img, status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->conn->prepare($sql);
            $stmt->bind_param('sssss', $new_noti_id, $title, $description, $image_name, $status);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Notification added successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database insertion failed.']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Image upload failed.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Image is required or invalid.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task']) && $_POST['task'] == 'getNotiDetails') {
    $noti_id = $_POST['noti_id'];

    // Fetch the notification details from the database
    $query = "SELECT * FROM notification WHERE noti_id = ?";
    $stmt = mysqli_prepare($db->conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $noti_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode(['success' => true, 'noti' => $row]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Notification not found.']);
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task']) && $_POST['task'] == 'editNoti') {
    $noti_id = $_POST['noti_id'];
    $title = $_POST['editTitle'];
    $description = $_POST['editDesc'];
    $status = $_POST['editStatus'];

    // Handle optional image upload
    $image_name = null;
    if (isset($_FILES['editImage']) && $_FILES['editImage']['error'] == 0) {
        $image = $_FILES['editImage'];
        $image_ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $image_name = $noti_id . '.' . $image_ext;
        $target_dir = "../assets/imgs/notification/";
        $target_file = $target_dir . basename($image_name);
        move_uploaded_file($image['tmp_name'], $target_file);
    }

    // Update the notification
    $query = "UPDATE notification SET noti_title = ?, noti_desc = ?, status = ?";
    if ($image_name) {
        $query .= ", noti_img = ?";
    }
    $query .= " WHERE noti_id = ?";

    $stmt = mysqli_prepare($db->conn, $query);
    if ($image_name) {
        mysqli_stmt_bind_param($stmt, "sssss", $title, $description, $status, $image_name, $noti_id);
    } else {
        mysqli_stmt_bind_param($stmt, "ssss", $title, $description, $status, $noti_id);
    }

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'Notification updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update notification']);
    }
}

 
?>