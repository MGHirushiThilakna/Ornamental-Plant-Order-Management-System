<?php 
include "..\classes\DBConnect.php";
$db = new DatabaseConnection;
session_start();

if (isset($_POST['task']) && $_POST['task'] === 'getOrderSummary') {
 

    try {
        $driverId = $_SESSION['driverID'] ?? '';

        // Prepare SQL to get counts for each status
        $sql = "SELECT 
        COALESCE(SUM(o.subtotal), 0) as total_amount,
        SUM(CASE WHEN o.status = 'Dispatched' THEN 1 ELSE 0 END) as dispatched,
        SUM(CASE WHEN o.status = 'On The Way' THEN 1 ELSE 0 END) as on_the_way,
        SUM(CASE WHEN o.status = 'Completed' THEN 1 ELSE 0 END) as completed
        FROM order_tbl o
        INNER JOIN order_driver_assignment da ON o.odr_id = da.odr_id
        WHERE da.Driver_ID = ?";
        
        $stmt = $db->conn->prepare($sql);
        $stmt->bind_param("s", $driverId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            $summary = $result->fetch_assoc();
            echo json_encode([
                'success' => true,
                'summary' => [
                    'dispatched' => (int)$summary['dispatched'],
                    'onTheWay' => (int)$summary['on_the_way'],
                    'completed' => (int)$summary['completed']
                    
                ]
            ]);
        } else {
            throw new Exception("Failed to fetch order summary");
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error getting order summary: ' . $e->getMessage()
        ]);
    }
}

if (isset($_REQUEST['task']) && $_REQUEST['task'] === 'loadFilteredOrders') {
    $driverId = $_SESSION['driverID'] ?? '';

    if (empty($driverId)) {
        echo '<tr><td colspan="6" class="text-center">Please log in to view orders</td></tr>';
        exit;
    }

    $startDate = $_REQUEST['startDate'] ?? '';
    $endDate = $_REQUEST['endDate'] ?? '';
    $paymentType = $_REQUEST['paymentType'] ?? '';
    $searchOrder = trim($_REQUEST['searchOrder'] ?? '');

    $sql = "SELECT o.* 
            FROM order_tbl o
            INNER JOIN order_driver_assignment da ON o.odr_id = da.odr_id
            WHERE da.Driver_ID = ? 
            AND o.status = 'Completed'";

    $params = [$driverId];
    $types = "s";

    // Add date range filter
    if ($startDate && $endDate) {
        $sql .= " AND DATE(o.complete_date) BETWEEN ? AND ?";
        $params[] = $startDate;
        $params[] = $endDate;
        $types .= "ss";
    }

    // Add payment type filter
    if ($paymentType) {
        $sql .= " AND o.payment_type = ?";
        $params[] = $paymentType;
        $types .= "s";
    }

    // Enhanced search functionality
    if ($searchOrder) {
        $sql .= " AND (
            o.odr_id LIKE ? OR 
            o.user LIKE ? OR 
            CAST(o.subtotal AS CHAR) LIKE ?
        )";
        $searchTerm = "%$searchOrder%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= "sss";
    }

    $sql .= " ORDER BY o.complete_date DESC";

    $stmt = $db->conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $statusClass = 'text-success fw-bold';
            ?>
            <tr>
                <th scope="row"><?= htmlspecialchars($row['odr_id']) ?></th>
                <td><?= htmlspecialchars($row['complete_date']) ?></td>
                <td><?= htmlspecialchars($row['payment_type']) ?></td>
                <td>Rs. <?= number_format($row['subtotal'], 2) ?></td>
                <td class="<?= $statusClass ?>"><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <button class="btn viewbtn" data-order-id="<?= htmlspecialchars($row['odr_id']) ?>" type="button">
                        <i class="far fa-eye"></i> View
                    </button>
                </td>
            </tr>
            <?php
        }
    } else {
        echo '<tr><td colspan="6" class="text-center">No orders found</td></tr>';
    }
}

if (isset($_REQUEST['task']) && $_REQUEST['task'] === 'getCODSummary') {
    $driverId = $_SESSION['driverID'] ?? '';

    if (empty($driverId)) {
        echo json_encode(['totalOrders' => 0, 'totalAmount' => 0]);
        exit;
    }

    $startDate = $_REQUEST['startDate'] ?? '';
    $endDate = $_REQUEST['endDate'] ?? '';

    $sql = "SELECT 
            COUNT(*) as total_orders,
            COALESCE(SUM(o.subtotal), 0) as total_amount
            FROM order_tbl o
            INNER JOIN order_driver_assignment da ON o.odr_id = da.odr_id
            WHERE da.Driver_ID = ?
            AND o.payment_type = 'COD'
            AND o.status = 'Completed'";

    $params = [$driverId];
    $types = "s";

    if ($startDate && $endDate) {
        $sql .= " AND DATE(o.complete_date) BETWEEN ? AND ?";
        $params[] = $startDate;
        $params[] = $endDate;
        $types .= "ss";
    }

    $stmt = $db->conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $summary = $result->fetch_assoc();

    echo json_encode([
        'totalOrders' => (int) $summary['total_orders'],
        'totalAmount' => (float) $summary['total_amount']
    ]);
}

if(isset($_REQUEST['task']) && $_REQUEST['task'] === 'loadDriverReadyOrderTable'){
   // Get the logged-in employee's ID from session
   $driverId = $_SESSION['driverID'] ?? '';
        
   if(empty($driverId)) {
       echo '<tr><td colspan="4" class="text-center">Please log in to view orders</td></tr>';
       exit;
   }
   
   // Modified query to join with order_employee_assignment table
   $sql = "SELECT o.* 
           FROM order_tbl o
           INNER JOIN order_driver_assignment da ON o.odr_id = da.odr_id
           WHERE da.Driver_ID = ? AND o.status = 'Dispatched'
           ORDER BY o.date_time DESC";
           
   $stmt = $db->conn->prepare($sql);
   $stmt->bind_param("s", $driverId);
   $stmt->execute();
   $result = $stmt->get_result();
   
   if($result && $result->num_rows > 0){
       while($row = $result->fetch_assoc()){
           $statusClass = match($row['status']) {
               'Pending' => 'text-danger fw-bold',
               'Confirmed' => 'text-success fw-bold',
               'Ready' => 'text-primary fw-bold',
               'Dispatched' => 'text-danger fw-bold',
               default => 'text-dark'
           };
           ?>
           <tr>
               <th scope="row"><?= htmlspecialchars($row['odr_id']) ?></th>
               <td><?= htmlspecialchars($row['date_time']) ?></td>
               <td><?= htmlspecialchars($row['payment_type']) ?></td>
               <td class="<?= $statusClass ?>"><?= htmlspecialchars($row['status']) ?></td>
               <td>
                   <button class="btn viewbtn" 
                    data-order-id="<?= htmlspecialchars($row['odr_id']) ?>"
                   type="button">
                   <i class="far fa-eye"></i> View
                   </button>
               </td>
           </tr>
           <?php
       }
   } else {
       ?>
       <tr>
           <td colspan="4" class="text-center">No Assigned Orders Found</td>
       </tr>
       <?php
   }
}

if(isset($_REQUEST['task']) && $_REQUEST['task'] === 'loadOnTheWayTable'){
    // Get the logged-in employee's ID from session
    $driverId = $_SESSION['driverID'] ?? '';
    
         
    if(empty($driverId)) {
        echo '<tr><td colspan="4" class="text-center">Please log in to view orders</td></tr>';
        exit;
    }
    
    // Modified query to join with order_employee_assignment table
    $sql = "SELECT o.* 
            FROM order_tbl o
            INNER JOIN order_driver_assignment da ON o.odr_id = da.odr_id
            WHERE da.Driver_ID = ? AND o.status = 'On The Way'
            ORDER BY o.date_time DESC";
            
    $stmt = $db->conn->prepare($sql);
    $stmt->bind_param("s", $driverId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result && $result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $statusClass = match($row['status']) {
                'Pending' => 'text-danger fw-bold',
                'Confirmed' => 'text-success fw-bold',
                'Ready' => 'text-primary fw-bold',
                'Dispatched' => 'text-danger fw-bold',
                'On The Way' => 'text-success fw-bold',
                default => 'text-dark'
            };
            ?>
            <tr>
                <th scope="row"><?= htmlspecialchars($row['odr_id']) ?></th>
                <td><?= htmlspecialchars($row['date_time']) ?></td>
                <td><?= htmlspecialchars($row['payment_type']) ?></td>
                <td class="<?= $statusClass ?>"><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <button class="btn viewbtn" 
                     data-order-id="<?= htmlspecialchars($row['odr_id']) ?>"
                    type="button">
                    <i class="far fa-eye"></i> View
                    </button>
                </td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr>
            <td colspan="4" class="text-center">No Assigned Orders Found</td>
        </tr>
        <?php
    }
}

if(isset($_REQUEST['task']) && $_REQUEST['task'] === 'loadCompleteTable'){
    // Get the logged-in employee's ID from session
    $driverId = $_SESSION['driverID'] ?? '';
    
         
    if(empty($driverId)) {
        echo '<tr><td colspan="4" class="text-center">Please log in to view orders</td></tr>';
        exit;
    }
    
    // Modified query to join with order_employee_assignment table
    $sql = "SELECT o.* 
            FROM order_tbl o
            INNER JOIN order_driver_assignment da ON o.odr_id = da.odr_id
            WHERE da.Driver_ID = ? AND o.status = 'Completed'
            ORDER BY o.date_time DESC";
            
    $stmt = $db->conn->prepare($sql);
    $stmt->bind_param("s", $driverId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result && $result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $statusClass = match($row['status']) {
                'Pending' => 'text-danger fw-bold',
                'Confirmed' => 'text-success fw-bold',
                'Ready' => 'text-primary fw-bold',
                'Dispatched' => 'text-info fw-bold',
                'Completed' => 'text-success fw-bold',
                default => 'text-dark'
            };
            ?>
            <tr>
                <th scope="row"><?= htmlspecialchars($row['odr_id']) ?></th>
                <td><?= htmlspecialchars($row['complete_date']) ?></td>
                <td><?= htmlspecialchars($row['payment_type']) ?></td>
                <td><?= htmlspecialchars($row['subtotal']) ?></td>
                <td class="<?= $statusClass ?>"><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <button class="btn viewbtn" 
                     data-order-id="<?= htmlspecialchars($row['odr_id']) ?>"
                    type="button">
                    <i class="far fa-eye"></i> View
                    </button>
                </td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr>
            <td colspan="4" class="text-center">No Assigned Orders Found</td>
        </tr>
        <?php
    }
}

if(isset($_REQUEST['task']) && $_REQUEST['task'] === 'getOrderDetails'){
        $orderId = $_REQUEST['orderId'] ?? '';
        if(!empty($orderId)){
            $sql = "SELECT * FROM order_tbl WHERE odr_id = ?";
            $stmt = $db->conn->prepare($sql);
            $stmt->bind_param("s", $orderId);
            $stmt->execute();
            $result = $stmt->get_result();
            $orderData = $result->fetch_assoc();

        $employeeSql = "SELECT Emp_ID, CONCAT(FName, ' ', LName) as emp_name 
        FROM employee WHERE Job_Role = 'Staff' AND Emp_status = 'Active'";
        $employeeResult = $db->conn->query($employeeSql);
        $employees = [];
        while($emp = $employeeResult->fetch_assoc()) {
            $employees[] = $emp;
        }
        $driverSql = "SELECT Driver_ID, CONCAT(FName, ' ', LName) as driver_name 
        FROM deliver_driver WHERE Status = 'Active'";
        $driverResult = $db->conn->query($driverSql);
        $drivers = [];
        while($driver = $driverResult->fetch_assoc()) {
            $drivers[] = $driver;
        }
            
        $sql = "SELECT o.*,
        dd.Driver_ID,
        CONCAT(dd.FName, ' ', dd.LName) as driver_name,
        da.driver_assigned_date,
        e.Emp_ID,
        CONCAT(e.FName, ' ', e.LName) as employee_name,
        ea.emp_assigned_date
        FROM order_tbl o
        LEFT JOIN order_driver_assignment da ON o.odr_id = da.odr_id
        LEFT JOIN deliver_driver dd ON da.Driver_ID = dd.Driver_ID
        LEFT JOIN order_employee_assignment ea ON o.odr_id = ea.odr_id
        LEFT JOIN employee e ON ea.Emp_ID = e.Emp_ID
        WHERE o.odr_id = ?";
        
$stmt = $db->conn->prepare($sql);
$stmt->bind_param("s", $orderId);
$stmt->execute();
$result = $stmt->get_result();
$orderData = $result->fetch_assoc();

if($orderData){
    $statusClass = match($orderData['status']) {
        'Pending' => 'text-danger fw-bold',
        'Confirmed' => 'text-success fw-bold',
        'Ready' => 'text-primary fw-bold',
        'Dispatched' => 'text-info fw-bold',
        default => 'text-dark'
    };
    ob_start();
    ?>
                
                <div class="modal-header" style="background-color:#4e944f; color:white">
                    <h5 class="modal-title">Order Details - #<?= htmlspecialchars($orderId) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header" style="background-color:#daf4dd;">
                                    <h6>Customer Information</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Customer ID:</strong> <?= htmlspecialchars($orderData['user'] ) ?></p>
                                    <p><strong>Contact:</strong> <?= htmlspecialchars($orderData['mobile']) ?></p>
                                    <p><strong>Address:</strong> <?= htmlspecialchars($orderData['address']) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header" style="background-color:#daf4dd;">
                                    <h6>Order Information</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Order Date:</strong> <?= htmlspecialchars($orderData['date_time']) ?></p>
                                    <p><strong>Status:</strong> <span class="<?= $statusClass ?>"><?= htmlspecialchars($orderData['status']) ?></span></p>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php if($orderData['Driver_ID']): ?>
                                        <p><strong>Assigned Driver:</strong> 
                                            <?= htmlspecialchars($orderData['Driver_ID']) ?> - 
                                            <?= htmlspecialchars($orderData['driver_name']) ?>
                                        </p>
                                        <p><strong>Driver Assigned Date:</strong> 
                                            <?= htmlspecialchars($orderData['driver_assigned_date']) ?>
                                        </p>
                                    <?php else: ?>
                                        <p><strong>Assigned Driver:</strong> Not assigned yet</p>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <?php if($orderData['Emp_ID']): ?>
                                        <p><strong>Assigned Stock Keeper:</strong> 
                                            <?= htmlspecialchars($orderData['Emp_ID']) ?> - 
                                            <?= htmlspecialchars($orderData['employee_name']) ?>
                                        </p>
                                        <p><strong>Staff Assigned Date:</strong> 
                                            <?= htmlspecialchars($orderData['emp_assigned_date']) ?>
                                        </p>
                                    <?php else: ?>
                                        <p><strong>Assigned Stock Keeper:</strong> Not assigned yet</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6>Order Items</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                            <tr>
                                                    <td>
                                                    <?php 
                                                    $imagePath = "../assets/imgs/item/" . $orderData['item_id'] . ".jpg";
                                                    if(file_exists($imagePath)): 
                                                    ?>
                                                        <img src="<?= htmlspecialchars($imagePath) ?>" 
                                                            alt="<?= htmlspecialchars($orderData['item_id']) ?>" 
                                                            class="img-thumbnail" 
                                                            style="width: 50px; height: 50px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <img src="../assets/imgs/item/placeholder.jpg" 
                                                            alt="No image available" 
                                                            class="img-thumbnail" 
                                                            style="width: 50px; height: 50px; object-fit: cover;">
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= htmlspecialchars($orderData['item_id']) ?></td>
                                                <td>Rs.<?= number_format($orderData['price'], 2) ?></td>
                                                <td><?= htmlspecialchars($orderData['qty']) ?></td>
                                                <td><?= number_format(floatval($orderData['subtotal']), 2) ?></td>
                                            </tr>
                                            
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">Total:</th>
                                            <th>Rs.<?= number_format(floatval($orderData['subtotal']), 2)?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
               
                <div class="card-body">
                    
                        
                        <div class="text-end">
                            
                        <?php 
                            if ($orderData['status'] === 'Dispatched' && $orderData['address'] !== 'Store Pickup') {
                            ?>
                                <button type="button" 
                                    class="btn btn-primary" 
                                    id="checkedBtn" 
                                    data-order-id="<?= htmlspecialchars($orderId) ?>" 
                                    style="background-color:#3d8361;">
                                    Checked Order
                                </button>
                            <?php 
                            } else if ($orderData['status'] === 'On The Way' && $orderData['address'] !== 'Store Pickup') {
                            ?>
                                <button type="button" 
                                    class="btn btn-primary" 
                                    id="completeBtn"  
                                    data-order-id="<?= htmlspecialchars($orderId) ?>" 
                                    style="background-color:#3d8361;">
                                    Complete Order
                                </button>
                            <?php 
                            }
                            ?>
                        </div>
                    </div>

    
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                <?php
                $html = ob_get_clean();
                
                echo json_encode([
                    'success' => true,
                    'html' => $html
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Order not found'
                ]);
            }
        }
}
    

    if (isset($_REQUEST['task']) && $_REQUEST['task'] === 'updateToOnTheWay') {
        
         // Check if it's an AJAX request
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        exit('Direct access not permitted');
    }
    // Set proper content type
    header('Content-Type: application/json');

        if (!isset($_POST['orderId'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Order ID is missing'
            ]);
            exit;
        }
        $orderId = $_POST['orderId'];
        
        try {
            $db->conn->begin_transaction();
            error_log("Updating order status to On The Way for order: " . $orderId);

            // Update order status to Dispatched
            $updateOrderSql = "UPDATE order_tbl SET status = 'On The Way' WHERE odr_id = ? AND status = 'Dispatched' AND address != 'Store Pickup'";
            $stmt = $db->conn->prepare($updateOrderSql);
            $stmt->bind_param("s", $orderId);
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                $db->conn->commit();
                echo json_encode([
                    'success' => true,
                    'message' => 'Order status updated to  On The Way successfully'
                ]);
            } else {// Check why the update failed
                $checkOrderSql = "SELECT status, address FROM order_tbl WHERE odr_id = ?";
                $checkStmt = $db->conn->prepare($checkOrderSql);
                $checkStmt->bind_param("s", $orderId);
                $checkStmt->execute();
                $result = $checkStmt->get_result();
                $orderInfo = $result->fetch_assoc();
                
                $errorMessage = "Order update failed. ";
                if (!$orderInfo) {
                    $errorMessage .= "Order not found.";
                } else {
                    if ($orderInfo['status'] !== 'Dispatched') {
                        $errorMessage .= "Order status is " . $orderInfo['status'] . ", not Dispatched.";
                    }
                    if ($orderInfo['address'] === 'Store Pickup') {
                        $errorMessage .= "This is a store pickup order.";
                    }
                }
                
                throw new Exception($errorMessage);
            }
            
        } catch (Exception $e) {
            $db->conn->rollback();
            echo json_encode([
                'success' => false,
                'message' => 'Error updating order status: ' . $e->getMessage()
            ]);
        }
    }

    if (isset($_REQUEST['task']) && $_REQUEST['task'] === 'updateToComplete') {
        
        // Check if it's an AJAX request
       if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
       exit('Direct access not permitted');
   }
   // Set proper content type
   header('Content-Type: application/json');

       if (!isset($_POST['orderId'])) {
           echo json_encode([
               'success' => false,
               'message' => 'Order ID is missing'
           ]);
           exit;
       }
       $orderId = $_POST['orderId'];
       
       try {
           $db->conn->begin_transaction();
           error_log("Updating order status to On The Way for order: " . $orderId);

           // Update order status to Dispatched
           $updateOrderSql = "UPDATE order_tbl SET status = 'Completed' , 	complete_date = CURRENT_TIMESTAMP WHERE odr_id = ? AND status = 'On The Way' AND address != 'Store Pickup'";
           $stmt = $db->conn->prepare($updateOrderSql);
           $stmt->bind_param("s", $orderId);
           $stmt->execute();
           
           if ($stmt->affected_rows > 0) {
               $db->conn->commit();
               echo json_encode([
                   'success' => true,
                   'message' => 'Order status updated to  On The Way successfully'
               ]);
           } else {// Check why the update failed
            $checkOrderSql = "SELECT status, address FROM order_tbl WHERE odr_id = ?";
            $checkStmt = $db->conn->prepare($checkOrderSql);
            $checkStmt->bind_param("s", $orderId);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            $orderInfo = $result->fetch_assoc();
            
            $errorMessage = "Order update failed. ";
            if (!$orderInfo) {
                $errorMessage .= "Order not found.";
            } else {
                if ($orderInfo['status'] !== 'On The Way') {
                    $errorMessage .= "Order status is " . $orderInfo['status'] . ", not On The Way.";
                }
                if ($orderInfo['address'] === 'Store Pickup') {
                    $errorMessage .= "This is a store pickup order.";
                }
            }
            
            throw new Exception($errorMessage);
        }
        
    } catch (Exception $e) {
        $db->conn->rollback();
        echo json_encode([
            'success' => false,
            'message' => 'Error updating order status: ' . $e->getMessage()
        ]);
    }
}
    
    
    ?>
    
    


    