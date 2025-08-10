<?php include('../config/constant.php') ?>

<?php
$currentSubPage = "view";
include "ProductManagement.php"; ?>
<link rel="stylesheet" href="..\assets\css\admin-view-product.css">

<div class="container myviewProMainCont">
    <div class="card form-card mb-2 my serchcard" style=" width: auto;">
        <div class="card-body" style="margin-left:300px;">
            <div class="row">

                <div class="col-lg-6 off-my-col">
                    <form class="d-flex search-box" id="itemSearch">
                        <select class="form-control search-input select-input" name="item_col">
                            <option value="item_id" selected>Item ID </option>
                            <option value="name">Plant Name </option>
                            <option value="price">price </option>
                            <option value="category">Plant category</option>
                            <option value="colors">Colors availability</option>
                            <option value="status">Status</option>
                        </select>
                        <input class="form-control search-input " name="searchData" type="search" placeholder="Search"
                            aria-label="Search">

                        <button class="btn search-btn" type="submit"><i class="fas fa-search"></i></button>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="card myviewProdCont" style=" z-index:0;">
        <div class="card-body main-card-body">
            <div class="table-responsive" style="width:auto;">
                <table class="table main-tbl">
                    <thead>
                        <tr>
                            <th>Item ID</th>
                            <th>Item Name</th>
                            <th>Unit Price</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Watering</th>
                            <th>Light</th>
                            <th>Soil</th>
                            <th>Images</th>
                            <th>Colors</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="pro_Data">
                        <!-- Product data will be loaded here via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script src="..\assets\js\form-validation\admin-product-view.js"></script>
<?php include "adminFooter.php"; ?>