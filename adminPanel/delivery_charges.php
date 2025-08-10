<?php 
$currentSubPage="DeliveryCharges";
include "orderHandling.php"; 
include "..\classes\DBConnect.php";
$db = new DatabaseConnection;
?>

<link rel="stylesheet" href="..\assets\css\employee-style.css">
<script src="..\assets\js\delivery_charge_validation.js"></script>

<div class="container my-container" style="margin-top:130px;margin-left:190px;">
    <div class="card mycard" style="margin-left:120px; margin-right:0px;">
        <div class="card-body mycard-body">
            <div class="row">
                <div class="col-md-9 mb-6">
                    <div class="card" style="margin-left:250px">
                        <div class="card-header mycardheader">Add Delivery Charge</div>
                        <div class="card-body">
                        <form id="update-charge-form" novalidate>
                            <div class="row">
                                <div class="col">
                                    <div class="form-floating myFormFloating">
                                        <input type="text" class="form-control myinputText" name="charge_name" id="chargeName" placeholder="Enter charge name" required>
                                        <label for="chargeName">Charge Name:</label>
                                        <div class="invalid-feedback">Please enter a charge name</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <div class="form-floating myFormFloating">
                                        <input type="number" class="form-control myinputText" name="charge_value" id="chargeValue" placeholder="Enter amount" required step="0.01" min="0">
                                        <label for="chargeValue">Delivery Charge Value (Rs.):</label>
                                        <div id="chargeValueError" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="btn-col" style="margin-top:30px;">
                                    <button class="btn myBtn" type="submit">Save</button>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-10" style="margin-left:100px; margin-top:30px;">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Charge ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Value</th>
                                    <th scope="col">Update Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $query = "SELECT deli_charge_id, name, value, update_date 
                                     FROM delivery_charge 
                                     ORDER BY update_date DESC";
                            $result = $db->conn->query($query);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['deli_charge_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                    echo "<td>Rs." . number_format($row['value'], 2) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['update_date']) . "</td>";
                                    echo "<td>";
                                    echo "<button class='btn btn-sm btn-primary edit-charge' 
                                          style='background-color:#3d8361;' 
                                          data-id='" . htmlspecialchars($row['deli_charge_id']) . "' 
                                          data-name='" . htmlspecialchars($row['name']) . "' 
                                          data-value='" . htmlspecialchars($row['value']) . "'>Edit</button> ";
                                    echo "<button class='btn btn-sm btn-danger delete-charge' 
                                          data-id='" . htmlspecialchars($row['deli_charge_id']) . "'>Delete</button>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' style='text-align:center;'>No delivery charges found.</td></tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Edit Modal -->
                <div class="modal fade" id="editChargeModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Delivery Charge</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form id="edit-charge-form">
                                    <input type="hidden" id="edit_charge_id" name="charge_id">
                                    <div class="mb-3">
                                        <label for="edit_charge_name" class="form-label">Charge Name</label>
                                        <input type="text" class="form-control" id="edit_charge_name" name="charge_name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit_charge_value" class="form-label">Charge Value (Rs.)</label>
                                        <input type="number" class="form-control" id="edit_charge_value" name="charge_value" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="..\assets\js\delivery_charge_validation.js"></script>

<?php include "adminFooter.php"; ?>