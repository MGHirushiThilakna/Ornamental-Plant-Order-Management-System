
<?php 
include "..\classes\DBConnect.php";
$db = new DatabaseConnection;
$conn = $db->conn; 
session_start();
$customerName = $_SESSION['Name'];

if(isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    $sql = "SELECT o.*, i.name as item_name, i.description, i.colors, i.watering, i.light, i.soil 
            FROM order_tbl o
            JOIN item i ON o.item_id = i.item_id 
            WHERE o.odr_id = ? AND o.user = ?
            ORDER BY o.date_time DESC;";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $order_id, $_SESSION['Customer_ID']);
            $stmt->execute();
            $result = $stmt->get_result();

            $orderData = $result->fetch_assoc();$statusClass = match($orderData['status']) {
                'Pending' => 'text-danger fw-bold',
                'Confirmed' => 'text-success fw-bold', 
                'Ready' => 'text-primary fw-bold',
                'Dispatched' => 'text-danger fw-bold',
                 'Completed' => 'text-success fw-bold',
                default => 'text-dark'
            };
?>
        
    <div class="modal-header" style="background-color:#4e944f; color:white">
       <h5 class="modal-title">Order Details - #<?= htmlspecialchars($order_id) ?></h5>
       <button type="button" class="btn-close" style="color:red" data-bs-dismiss="modal" aria-label="Close"></button>
   </div>
   <div class="modal-body" style="width:1000px;">
       <div class="row">
           <div class="col-md-6">
               <div class="card">
                   <div class="card-header" style="background-color:#daf4dd;">
                       <h6>Customer Information</h6>
                   </div>
                   <div class="card-body">
                       <p><strong>Customer Name :</strong> <?= htmlspecialchars($customerName) ?></p>
                       <p><strong>Contact :</strong> <?= htmlspecialchars($orderData['mobile']) ?></p>
                       <p><strong>Address :</strong> <?= htmlspecialchars($orderData['address']) ?></p>
                   </div>
               </div>
           </div>
           <div class="col-md-6">
               <div class="card">
                   <div class="card-header" style="background-color:#daf4dd;">
                       <h6>Order Information</h6>
                   </div>
                   <div class="card-body">
                       <p><strong>Order Date :</strong> <?= date('M d, Y', strtotime($orderData['date_time'])) ?></p>
                       <p><strong>Status :</strong> <span class="<?= $statusClass ?>"><?= htmlspecialchars($orderData['status']) ?></span></p>
                       <p><strong>Payment Type :</strong> <?= htmlspecialchars($orderData['payment_type']) ?></p>
                   </div>
               </div>
           </div>
           <div class="col-md-6">
               <div class="card">
                   <div class="card-header" style="background-color:#daf4dd;">
                       <h6>Item Information</h6>
                   </div>
                   <div class="card-body">
                       <p><strong>Color :</strong> <?= htmlspecialchars($orderData['colors'])?></p>
                       <p><strong>Watering 💧 :</strong> <?= htmlspecialchars($orderData['watering']) ?></p>
                       <p><strong>Sunlight 🌞 :</strong> <?= htmlspecialchars($orderData['light']) ?></p>
                       <p><strong>Soil 🌿 :</strong> <?= htmlspecialchars($orderData['soil']) ?></p>
                       <p><strong>Description 📝 :</strong> <?= htmlspecialchars($orderData['description']) ?></p>
                   </div>
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
                                            alt="<?= htmlspecialchars($orderData['item_name']) ?>" 
                                            class="img-thumbnail" 
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                   <?php else: ?>
                                       <img src="../assets/imgs/item/placeholder.jpg" 
                                            alt="No image available" 
                                            class="img-thumbnail" 
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                   <?php endif; ?>
                               </td>
                               <td><?= htmlspecialchars($orderData['item_name']) ?></td>
                               <td>Rs.<?= number_format($orderData['price'], 2) ?></td>
                               <td><?= htmlspecialchars($orderData['qty']) ?></td>
                               <td>Rs.<?= number_format($orderData['subtotal'], 2) ?></td>
                           </tr>
                       </tbody>
                       <tfoot>
                           <tr>
                               <th colspan="4" class="text-end">Total:</th>
                               <td>Rs.<?= number_format($orderData['subtotal'], 2) ?></td>
                           </tr>
                       </tfoot>
                   </table>
               </div>
           </div>
       </div>
   </div>
   <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
  
   <?php
   $stmt->close();
}
?>
