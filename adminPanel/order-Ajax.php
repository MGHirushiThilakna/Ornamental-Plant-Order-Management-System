<?php 
include "..\classes\DBConnect.php";
$db = new DatabaseConnection;
 
if (isset($_POST['task']) && $_POST['task'] === 'getOrderSummary') {
    try {
    $ordersSql = "SELECT 
            SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'Confirmed' THEN 1 ELSE 0 END) as confirmed,
            SUM(CASE WHEN status = 'Ready' THEN 1 ELSE 0 END) as ready,
            SUM(CASE WHEN status = 'Dispatched' THEN 1 ELSE 0 END) as dispatched,
            SUM(CASE WHEN status = 'On The Way' THEN 1 ELSE 0 END) as onTheWay,
            SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed
            FROM order_tbl";
        
        $itemsSql = "SELECT SUM(quantity) as active_items FROM item WHERE status = 'Active'";
        
        $categoriesSql = "SELECT COUNT(*) as categories FROM main_category";
        
        $staffSql = "SELECT COUNT(*) as active_staff FROM employee WHERE Emp_status = 'Active'";
        
        $driversSql = "SELECT COUNT(*) as active_drivers FROM deliver_driver WHERE Status = 'Active'";
        
        $customersSql = "SELECT COUNT(*) as registered_customers FROM customer WHERE Status = 'Active'";
        
        $incomeSql = "SELECT SUM(subtotal) as total_income FROM order_tbl WHERE status = 'Completed'";
        
        $ordersResult = $db->conn->query($ordersSql);
        $itemsResult = $db->conn->query($itemsSql);
        $categoriesResult = $db->conn->query($categoriesSql);
        $staffResult = $db->conn->query($staffSql);
        $driversResult = $db->conn->query($driversSql);
        $customersResult = $db->conn->query($customersSql);
        $incomeResult = $db->conn->query($incomeSql);
        
        $summary = [
            'success' => true,
            'summary' => array_merge(
                $ordersResult->fetch_assoc(),
                [
                    'active_items' => $itemsResult->fetch_assoc()['active_items'],
                    'categories' => $categoriesResult->fetch_assoc()['categories'],
                    'active_staff' => $staffResult->fetch_assoc()['active_staff'],
                    'active_drivers' => $driversResult->fetch_assoc()['active_drivers'],
                    'registered_customers' => $customersResult->fetch_assoc()['registered_customers'],
                    'total_income' => number_format($incomeResult->fetch_assoc()['total_income'], 2)
                ]
            )
        ];
        
        echo json_encode($summary);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error getting summary: ' . $e->getMessage()
        ]);
    }
}



if(isset($_REQUEST['task']) && $_REQUEST['task'] === 'loadTable'){
    $sql = "SELECT * FROM order_tbl ORDER BY (CASE WHEN status = 'Pending' THEN 0 ELSE 1 END), date_time DESC";
    $result = $db->conn->query($sql);
    
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
                <td><?= htmlspecialchars($row['date_time']) ?></td>
                <td class="<?= $statusClass ?>"><?= htmlspecialchars($row['status']) ?></td>
                <td class="<?= $statusClass ?>"><?= htmlspecialchars($row['payment_type']) ?></td>
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
            <td colspan="4" class="text-center">No Orders Found</td>
        </tr>
        <?php
    }
} 

if (isset($_REQUEST['task']) && $_REQUEST['task'] === 'getOrderDetails') {
    $orderId = $_REQUEST['orderId'] ?? '';
    if (!empty($orderId)) {
        // Fetch order details
        $sql = "SELECT * FROM order_tbl WHERE odr_id = ?";
        $stmt = $db->conn->prepare($sql);
        $stmt->bind_param("s", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        $orderData = $result->fetch_assoc();

        // Fetch employees
        $employeeSql = "SELECT Emp_ID, CONCAT(FName, ' ', LName) as emp_name 
        FROM employee WHERE Job_Role = 'Stock Keeper' AND Emp_status = 'Active'";
        $employeeResult = $db->conn->query($employeeSql);
        $employees = [];
        
        while ($emp = $employeeResult->fetch_assoc()) {
            $employees[] = $emp;
        }

        // Fetch drivers
        $driverSql = "SELECT Driver_ID, CONCAT(FName, ' ', LName) as driver_name 
                      FROM deliver_driver 
                      WHERE Status = 'Active'";
        $driverResult = $db->conn->query($driverSql);
        $drivers = [];
        while ($driver = $driverResult->fetch_assoc()) {
            $drivers[] = $driver;
        }

        // Fetch full order assignment details
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

        if ($orderData) {
            // Determine status class
            $statusClass = match ($orderData['status']) {
                'Pending' => 'text-danger fw-bold',
                'Confirmed' => 'text-success fw-bold',
                'Ready' => 'text-primary fw-bold',
                'Dispatched' => 'text-info fw-bold',
                'Completed' => 'text-success fw-bold',
                default => 'text-dark',
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
                                <p><strong>Customer ID:</strong> <?= htmlspecialchars($orderData['user']) ?></p>
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
                            <?php if ($orderData['Driver_ID']): ?>
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
                            <?php if ($orderData['Emp_ID']): ?>
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
                                            if (file_exists($imagePath)): ?>
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
                                        <th>Rs.<?= number_format(floatval($orderData['subtotal']), 2) ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
                    <?php if ($orderData['status'] === 'Pending'): ?>
                            <div class="card mt-3">
                                <div class="card-header" style="background-color:#daf4dd;">
                                    <h6>Order Assignment</h6>
                                </div>
                                <div class="card-body">
                                <form id="AssignEMP">
                                
                                    <input type="hidden" name="order_id" value="<?= htmlspecialchars($orderId) ?>">
                                    <input type="hidden" name="addressType" value="<?= htmlspecialchars($orderData['address']) ?>">
                                    <div class="row">
                                    
                                    <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="employee" class="form-label">Assign Stock keeper to ready Order</label>
                                    <select class="form-select" name="empIDSelect" id="employee" required>
                                        <option value="">Stock Keeper</option>
                                        <?php foreach($employees as $emp): ?>
                                            <option value="<?= htmlspecialchars($emp['Emp_ID']) ?>">
                                            <?= htmlspecialchars($emp['Emp_ID']) ?> - <?= htmlspecialchars($emp['emp_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">

                                                <?php if ($orderData['address'] === 'Store Pickup'): ?>
                                                <p style="color:#FB4141;">This is a store pickup order. No delivery driver is required.</p>
                                            <?php else: ?>
                                                <label for="driver" class="form-label">Assign Delivery Driver</label>
                                                <select class="form-select" name="DriverSelect" id="driver" required>
                                                    <option value="">Select Driver</option>
                                                    <?php foreach ($drivers as $driver): ?>
                                                        <option value="<?= htmlspecialchars($driver['Driver_ID']) ?>">
                                                        <?= htmlspecialchars($driver['Driver_ID']) ?> - <?= htmlspecialchars($driver['driver_name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                            
                            <div class="text-end">
                                <?php if($orderData['address'] === 'Store Pickup'){
                                    if($orderData['status'] === 'Pending'){
                                        ?>
                                        <button type="submit" class="btn btn-primary" id="assignBtn" data-orderID = "<?php $_REQUEST['orderId'] ?>" style="background-color:#3d8361;">
                                        Assign Order
                                        </button>
                                    <?php 
                                    } else if($orderData['status'] === 'Confirmed') {
                                    ?>
                                <?php if($orderData['status'] === 'Confirmed' && $orderData['address'] === 'Store Pickup') { ?>
                                            <button type="button" 
                                                    class="btn btn-primary" 
                                                    id="readyBtn" 
                                                    data-order-id="<?= htmlspecialchars($orderId) ?>" 
                                                    style="background-color:#3d8361;">
                                                Ready Order
                                            </button>
                                        <?php } ?> 
                                    <?php
                                    } else if ($orderData['status'] === 'Ready') {?>
                                        <?php if($orderData['status'] === 'Ready' && $orderData['address'] === 'Store Pickup') { ?>
                                            <button type="button" 
                                                    class="btn btn-primary" 
                                                    id="completeBtn" 
                                                    data-order-id="<?= htmlspecialchars($orderId) ?>" 
                                                    style="background-color:#3d8361;">
                                                Complete Order
                                            </button>
                                        <?php } ?> 
                                        <?php
                                    } else {
                                        ?>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <?php
                                    }
                                }else {
                                    if($orderData['status'] === 'Pending'){
                                        ?>
                                        <button type="submit" class="btn btn-primary" id="assignBtn" data-orderID = "<?php $_REQUEST['orderId'] ?>" style="background-color:#3d8361;">
                                        Assign Order
                                        </button>
                                        <?php
                                        } else if($orderData['status'] === 'Confirmed') { ?>
                                        <?php if($orderData['status'] === 'Confirmed' && $orderData['address'] !== 'Store Pickup') { ?>
                                            <button type="button" 
                                                    class="btn btn-primary" 
                                                    id="dispatchnBtn" 
                                                    data-order-id="<?= htmlspecialchars($orderId) ?>" 
                                                    style="background-color:#3d8361;">
                                                Dispatch Order
                                            </button>
                                        <?php } ?> 
                                            <?php
                                            }  else{ ?>
                                        
                                        <?php
                                        }
                                        }
                                        ?>

                                
                            </div>
                        </form>
                    </div>
                </div>

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
   
    if (isset($_REQUEST['task']) && $_REQUEST['task'] === 'assignOrder') {
        $orderId = $_REQUEST['orderId'];
        $empId = $_REQUEST['empId'];
        $addressType = $_REQUEST['addressType'];

        // Fetch the last noti_id and generate a new one
        $empAssign_id_query = "SELECT assignment_id FROM order_employee_assignment ORDER BY assignment_id DESC LIMIT 1";
        $resultEmp = $db->conn->query($empAssign_id_query);

        if ($resultEmp->num_rows > 0) {
            $last_id_row = $resultEmp->fetch_assoc();
            $last_id = intval(str_replace('EA', '', $last_id_row['assignment_id']));
            $new_assigned_id = 'EA' . ($last_id + 1);
        } else {
            $new_assigned_id = 'EA1';
        }
            
        try {
            $db->conn->begin_transaction();
            
            // Insert employee assignment
            $empAssignSql = "INSERT INTO order_employee_assignment (assignment_id, odr_id, Emp_ID) VALUES (?, ?, ?)";
            $stmt = $db->conn->prepare($empAssignSql);
            $stmt->bind_param("sss",$new_assigned_id, $orderId, $empId);
            $stmt->execute();
    
            // For delivery orders, handle driver assignment  		assignment_id	odr_id	Emp_ID	emp_assigned_date		

            if ($addressType !== 'Store Pickup') {
                if (!isset($_REQUEST['driverId'])) {
                    throw new Exception('Driver ID is required for delivery orders');
                }
                $driverId = $_REQUEST['driverId'];
                            // Fetch the last noti_id and generate a new one
                    $driverAssign_id_query = "SELECT driver_assignment_id FROM order_driver_assignment ORDER BY driver_assignment_id DESC LIMIT 1";
                    $resultDriver = $db->conn->query($driverAssign_id_query);

                    if ($resultDriver->num_rows > 0) {
                        $last_id_row = $resultDriver->fetch_assoc();
                        $last_id = intval(str_replace('DA', '', $last_id_row['driver_assignment_id']));
                        $new_driver_id = 'DA' . ($last_id + 1);
                    } else {
                        $new_driver_id = 'DA1';
                    }
                
                $driverAssignSql = "INSERT INTO order_driver_assignment (driver_assignment_id, odr_id, Driver_ID) VALUES (?, ?, ?)";
                $stmt = $db->conn->prepare($driverAssignSql);
                $stmt->bind_param("sss",$new_driver_id, $orderId, $driverId);
                $stmt->execute();
            }
            
            // Update order status to Confirmed
            $updateOrderSql = "UPDATE order_tbl SET status = 'Confirmed' WHERE odr_id = ?";
            $stmt = $db->conn->prepare($updateOrderSql);
            $stmt->bind_param("s", $orderId);
            $stmt->execute();
            
            $db->conn->commit();
            
            echo json_encode([
                'success' => true,
                'message' => $addressType === 'Store Pickup' ? 
                            'Employee assigned successfully' : 
                            'Employee and driver assigned successfully'
            ]);
            
        } catch (Exception $e) {
            $db->conn->rollback();
            echo json_encode([
                'success' => false,
                'message' => 'Error assigning order: ' . $e->getMessage()
            ]);
        }
    }

    if(isset($_POST['task']) && $_POST['task'] === 'loadSearchTable') {
        // Base query
        $sql = "SELECT * FROM order_tbl WHERE 1=1";
        $params = array();
        $types = "";
    
        // Add Order ID filter
        if (!empty($_POST['orderId'])) {
            $sql .= " AND odr_id LIKE ?";
            $searchOrderId = "%" . $_POST['orderId'] . "%";
            $params[] = $searchOrderId;
            $types .= "s";
        }
    
        // Add Date filter
        if (!empty($_POST['orderDate'])) {
            $sql .= " AND DATE(date_time) = ?";
            $params[] = $_POST['orderDate'];
            $types .= "s";
        }
    
        // Add Status filter
        if (!empty($_POST['orderStatus'])) {
            $sql .= " AND status = ?";
            $params[] = $_POST['orderStatus'];
            $types .= "s";
        }
    
        // Add Payment Type filter
        if (!empty($_POST['paymentType'])) {
            $sql .= " AND payment_type = ?";
            $params[] = $_POST['paymentType'];
            $types .= "s";
        }
    
        // Add order by clause
        $sql .= " ORDER BY (CASE WHEN status = 'Pending' THEN 0 ELSE 1 END) ASC";
        // Prepare and execute the statement
        $stmt = $db->conn->prepare($sql);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
    
        if($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
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
                <td><?= htmlspecialchars($row['date_time']) ?></td>
                <td class="<?= $statusClass ?>"><?= htmlspecialchars($row['status']) ?></td>
                <td class="<?= $statusClass ?>"><?= htmlspecialchars($row['payment_type']) ?></td>
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
                <td colspan="4" class="text-center">No Orders Found</td>
            </tr>
            <?php
        }
        
        $stmt->close();
    }
    ?>
    
    


    