<?php 
include "customerSidePanal.php";
include "..\classes\DBConnect.php";
$db = new DatabaseConnection;
$conn = $db->getConnection();

if (!isset($_SESSION['Customer_ID'])) {
    header("Location: customerLogin.php");
    exit;
}

$customerID = $_SESSION['Customer_ID'];
$customerName = $_SESSION['Name'];
?>
<head>
<link rel="stylesheet" href="../assets/css/customer-account.css" />

<style>
  .card-body{
    padding:20px;

  }
  
  .card-body p{
    padding:5px;
  }
  .card-body thead{
    background-color:#daf4dd;
  }
  .container, .mx-auto, .p-3{
    margin-top:0px;
  }
        .order-history {
            padding: 2rem;
            margin-left: 200px;
            margin: 2rem auto;
            max-width: 1200px;
        }

        .order-table {
            width: 100%;
            margin-left: 50px;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .order-table thead {
            background: #f8f9fa;
        }

        .order-table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color:rgb(143, 76, 10);
            border-bottom: 2px solid #dee2e6;
        }

        .order-table td {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
            color: #212529;
        }

        .order-table tbody tr:hover {
            background-color: #f8f9fa;
            transition: background-color 0.2s ease;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 500;
            text-align: center;
            white-space: nowrap;
        }

        /* Status colors */
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-ready {
            background-color: #d4edda;
            color: #155724;
        }

        .status-on-the-way {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .status-complete {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .btn-view {
            padding: 0.5rem 1rem;
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.875rem;
            transition: background-color 0.2s ease;
        }

        .viewbtn:hover {
            background-color:rgb(219, 144, 117);
        }
         .viewbtn{
          background-color:rgb(246, 216, 196);
          padding: 0.3rem

        }

        .order-header {
            margin-bottom: 2rem;
        }

        .order-header h2 {
            color: #212529;
            margin: 0;
            font-size: 1.75rem;
        }

        .order-header p {
            color: #6c757d;
            margin: 0.5rem 0 0 0;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .order-history-container {
                padding: 1rem;
                margin: 1rem;
            }

            .order-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }

        
    </style>
</head>

<body>
<div class="modal fade" id="viewEachOrder" data-bs-backdrop="static" data-bs-keyboard="false">     
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="width:1000px"> 
        </div>
    </div>
</div>
  
<div class="flex flex-col lg:flex-row mydash" style="" >
      <main class="flex-1 lg:ml-2 mainDash" >
        <div class="bg-card p-4 rounded-lg shadow-md mb-3">
          <div class="flex items-center">
            
            <div>
        <div class="order-header">
            <h2>Order History</h2>
            <p>View and track your order history</p>
        </div>

        <div class="order-table-container">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Items</th>
                        <th>Total Amount</th>
                        <th>Payment Type</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch orders for the logged-in user
                    $sql = "SELECT DISTINCT odr_id, date_time, payment_type, status, qty, subtotal
                           FROM order_tbl 
                           WHERE user = ? 
                           ORDER BY date_time DESC";
                    
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $_SESSION['Customer_ID']);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                          $statusClass = match($row['status']) {
                            'Pending' => 'text-danger fw-bold',
                            'Confirmed' => 'text-success fw-bold',
                            'Ready' => 'text-primary fw-bold',
                            'Dispatched' => 'text-danger fw-bold',
                            'On The Way' => 'text-success fw-bold',
                            'Completed' => 'text-success fw-bold',
                            default => 'text-dark'
                        };
                            ?>
                            <tr>
                                <td>#<?php echo $row['odr_id']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['date_time'])); ?></td>
                                <td><?php echo $row['qty']; ?></td>
                                <td>Rs. <?php echo number_format($row['subtotal'], 2); ?></td>
                                <td><?php echo $row['payment_type']; ?></td>
                                <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $row['status']; ?></span></td>
                                <td>
                                <button class="btn viewbtn" 
                                    data-order-id="<?= htmlspecialchars($row['odr_id']) ?>"
                                  type="button">
                                  <i class="far fa-eye"></i> View </button>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="7" class="empty-state">
                                <p>You haven't placed any orders yet</p>
                            </td>
                        </tr>
                        <?php
                    }
                    $stmt->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
       $('tbody').on('click', '.viewbtn', function() {
        const orderId = $(this).data('order-id');
        viewOrderDetails(orderId);
    });

    function viewOrderDetails(orderId) {
    $.ajax({
        url: 'view_order_details.php',
        type: 'POST',
        data: {
            order_id: orderId
        },
        success: function(response) {
            $('#viewEachOrder .modal-content').html(response);
            $('#viewEachOrder').modal('show');
        },
        error: function() {
            alert('Error fetching order details');
        }
    });
}
      </script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>    
  </body>
  </html>