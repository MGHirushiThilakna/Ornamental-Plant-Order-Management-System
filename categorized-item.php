<?php include "main-header.php"; ?>
<?php 
if (isset($_SESSION["voice_assist"])) { 
    $voiceAssist = "ON";
}else{
  $voiceAssist = "OFF";
}
?>

<?php
                $type = isset($_GET['type']) ? $_GET['type'] : '';
                $category = isset($_GET['category']) ? $_GET['category'] : '';

                // select items by category or type
                if (!empty($type)) {
                    $sql = "SELECT i.*, mc.Cate_Name 
                            FROM item i 
                            INNER JOIN main_category mc ON i.category = mc.Main_ID 
                            WHERE i.status = 'Active' 
                            AND mc.Category_type = '$type'";

                } elseif (!empty($category)) {
                    $sql = "SELECT i.*, mc.Cate_Name 
                            FROM item i 
                            INNER JOIN main_category mc ON i.category = mc.Main_ID 
                            WHERE i.status = 'Active' 
                            AND mc.Cate_Name = '$category'";
                } else {
                    $sql = "SELECT i.*, mc.Cate_Name 
                            FROM item i 
                            INNER JOIN main_category mc ON i.category = mc.Main_ID 
                            WHERE i.status = 'Active'";
                }
                $result = mysqli_query($conn, $sql);

                if (!$result) {
                    echo "Error: " . mysqli_error($conn);
                    exit();
                }
                $pageTitle = !empty($type) ? $type . ' Plants' : ($category ?? 'All Plants');
                ?>

<div class="main-content-st2">
    <!-- Category banner -->
    <section class="slide-wrapper banner-title-header">
        <img src="images/shop/shop banner 7.png" alt="<?php echo htmlspecialchars($category); ?> banner">
        <div class="slide-bg">
            <div class="overlay">
                <div class="st2-banner-content-child-page">
                    <h2 class="banner-child-page-title"><?php echo htmlspecialchars($pageTitle); ?></h2>
                    <div class="back-link align-items-center">
                        <ul class="back-link-breadcrumb">            
                            <li class="breadcrumb-items"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-items"><a href="List_PlantShop.php">Shop Products</a></li>
                            <li class="breadcrumb-items"><?php echo htmlspecialchars($pageTitle); ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
                                      <!--Upper control bar -->
       <div class="shoppage-fullwidth">
        <div class="shoppage-fullwidth-box padding-section">
          <div class="container">
            <div class="shoppage-fullwidth-wrapper">
              <div class="shoppage-fullwidth-action">
                <div class="row">
                  <div class="col-t-7 col-xl-6 col-lg-6 col-md-6">
                    <div class="filter-left">
                      <div class="row">
                        <div class="col-xl-6 col-lg-6 d-none-19">
                          <div class="filter-left-showing">
                            <p class="filter-left-showing-text mb-0">Showing 1–16 of 25 results</p>
                          </div>
                        </div>
                 <!--Price range customizer -->
                        <div class="col-xl-6 col-lg-6 col-19px-12 d-none-67px">
                          <div class="filter-left-price">
                            <div class="filter-left-price-name">Price</div>
                            <form class="filter-left-price-range">
                              <div data-role="rangeslider">
                                <input type="text" class="js-range-slider" name="my_range" value="" />
                              </div>
                            </form>
                          </div>
                        </div>
            <!--/Price range customizer -->
                      </div>
                    </div>
                  </div>
         
                  <div class="col-t-5 col-xl-6 col-lg-6 col-md-6">
                    <div class="filter-right">
                      <ul class="filter-right-list mb-0">

                                    <!-- Category dropdown -->
                        <li class="filter-right-item">
                            <div class="dropdown filter-right-dropdow">
                                <button class="btn btn-link filter-dropdow-btn dropdown-toggle" type="button" id="filter-cate" data-bs-toggle="dropdown" aria-expanded="false">
                                    Categories
                                    <span class="dropdow-filter-icon lnr lnr-chevron-down"></span>
                                </button>
                                <ul class="dropdown-menu filter-dropdow-list" aria-labelledby="filter-cate">
                                <li><a class='dropdown-item filter-dropdow-item' href='categorized-item.php?type=Indoor' > Indoor</a></li>
                                <li><a class='dropdown-item filter-dropdow-item' href='categorized-item.php?type=Outdoor' > Outdoor</a></li>
                                    <?php
                                    $cat_sql = "SELECT * FROM main_category ";
                                    $cat_result = mysqli_query($conn, $cat_sql);
                                    while ($cat = mysqli_fetch_assoc($cat_result)) {
                                        echo "<li><a class='dropdown-item filter-dropdow-item' href='categorized-item.php?category=" . urlencode($cat['Cate_Name']) . "'>" . htmlspecialchars($cat['Cate_Name']) . "</a></li>";
                                    }
                                    ?>
                                </ul>
                            </div>
                        </li>
                  <!--Sort filter -->
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
        <!-- item  Quick view box by clicking eye symbol-->
        <div class="product-quick-view">
          <div class="product-quick-view-overlay"></div>
            <div class="product-quick-view-box">
              <section class="product-detail-v1 product-quick-view-box-inner">
                  <div class="container">
                      <div class="product-detail-v1-wrapper">
                          <div class="row">
                              <div class="col-md-6">
                                  <div class="product-detail-v3-image">
                                    <div id="quickview-carousel" class="carousel">
                                        <div class="carousel-inner"></div>
                                        <button class="carousel-control-prev" id="prev-image">‹</button>
                                        <button class="carousel-control-next" id="next-image">›</button>
                                    </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="product-detail-v1-info">
                                      <h1 id="quickview-product-name" class="product-detail-v1-title mb-0"></h1>
                                      <div id="color-variation-container" class="color-variations"></div>
                                      <div class="product-detail-v1-meta">
                                          <div class="product-detail-v1-price">
                                              <div id="quickview-original-price" class="sale"></div>
                                          </div>
                                          <div id="quickview-quantity" class="quantity"></div>
                                      </div>
                                      <p id="quickview-description" class="mb-0"></p>
                                      <div class="product-detail-v1-attr">
                                          <p id="quickview-sku" class="mb-0"></p>
                                          <p id="quickview-category" class="mb-0"></p>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </section>
              <a href="javascript:void(0);" class="close-quick-view close-st" id="pull">
                  <i class="ti-close"></i>
              </a>
          </div>
        </div>
      </div>

    <!-- Category products -->
    <section class="popular-product padding-section" style="padding-top: 12px; margin-left:100px;">
        <div class="container">
            <div class="row" style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: flex-start;">
            <?php
            $type = isset($_GET['type']) ? $_GET['type'] : '';
            $category = isset($_GET['category']) ? $_GET['category'] : '';

            if (!empty($type)) {
                $sql = "SELECT i.*, mc.Cate_Name,
                        CASE WHEN i.colors = 'Yes' THEN 
                        COALESCE((SELECT SUM(quantity) FROM colors 
                        WHERE item_id = i.item_id AND status = 'Active'), 0)
                        ELSE i.quantity END as available_stock
                        FROM item i 
                        INNER JOIN main_category mc ON i.category = mc.Main_ID 
                        WHERE i.status = 'Active' 
                        AND mc.Category_type = ?
                        ORDER BY (CASE WHEN i.colors = 'Yes' THEN 
                        (SELECT COUNT(*) FROM colors WHERE item_id = i.item_id AND status = 'Active' AND quantity > 0) > 0
                        ELSE i.quantity > 0 END) DESC, i.name ASC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $type);
            
            } else if (!empty($category)) {
                $sql = "SELECT i.*, mc.Cate_Name,
                        CASE WHEN i.colors = 'Yes' THEN 
                        COALESCE((SELECT SUM(quantity) FROM colors 
                        WHERE item_id = i.item_id AND status = 'Active'), 0)
                        ELSE i.quantity END as available_stock
                        FROM item i 
                        INNER JOIN main_category mc ON i.category = mc.Main_ID 
                        WHERE i.status = 'Active' 
                        AND mc.Cate_Name = ?
                        ORDER BY (CASE WHEN i.colors = 'Yes' THEN 
                        (SELECT COUNT(*) FROM colors WHERE item_id = i.item_id AND status = 'Active' AND quantity > 0) > 0
                        ELSE i.quantity > 0 END) DESC, i.name ASC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $category);
            } else {
                $sql = "SELECT i.*, mc.Cate_Name,
                        CASE WHEN i.colors = 'Yes' THEN 
                        COALESCE((SELECT SUM(quantity) FROM colors 
                        WHERE item_id = i.item_id AND status = 'Active'), 0)
                        ELSE i.quantity END as available_stock
                        FROM item i 
                        INNER JOIN main_category mc ON i.category = mc.Main_ID 
                        WHERE i.status = 'Active'
                        ORDER BY (CASE WHEN i.colors = 'Yes' THEN 
                        (SELECT COUNT(*) FROM colors WHERE item_id = i.item_id AND status = 'Active' AND quantity > 0) > 0
                        ELSE i.quantity > 0 END) DESC, i.name ASC";
                $stmt = $conn->prepare($sql);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $item_id = $row['item_id'];
                    $name = $row['name'];
                    $price = $row['price'];
                    $has_colors = $row['colors'] === 'Yes';
                    $item_quantity = $row['quantity'];
            
                    // Check stock based on its type
                    $is_out_of_stock = false;
            
                    if ($has_colors) {
                        $color_stock_query = "SELECT COUNT(*) as in_stock_colors 
                                            FROM colors 
                                            WHERE item_id = '$item_id' 
                                            AND status = 'Active' 
                                            AND quantity > 0";
                        $color_stock_result = mysqli_query($conn, $color_stock_query);
                        $color_stock_row = mysqli_fetch_assoc($color_stock_result);
                        $is_out_of_stock = $color_stock_row['in_stock_colors'] == 0;
                    } else {
                        $is_out_of_stock = $item_quantity <= 0;
                    }
            
                    // Get price information for items with colors
                    if ($has_colors) {
                        $sql1 = "SELECT * FROM colors WHERE item_id = '$item_id' AND status = 'Active' LIMIT 1";
                        $res1 = mysqli_query($conn, $sql1);
                        if (mysqli_num_rows($res1) > 0) {
                            $row1 = mysqli_fetch_assoc($res1);
                            $u_price = $row1['u_price'];
                        }
                    }
                    ?>
                    <div class="items-display">
                        <div class="box-item box-item-list-shoppage" style="width: 250px; height: 370px; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2); 
                                border-radius: 10px; overflow: hidden; 
                                <?php echo ($is_out_of_stock ? 'opacity: 0.7;' : ''); ?>">
            
                            <div class="box-item-image" 
                                style="height: 300px; padding: 7px; display: flex; justify-content: center; align-items: center; position: relative;">
                                <?php if ($is_out_of_stock): ?>
                                    <div style="position: absolute; top: 10px; right: 10px; background: #ff4444; color: white; 
                                              padding: 5px 10px; border-radius: 5px; font-size: 12px; z-index: 1;">
                                        Out of Stock
                                    </div>
                                <?php endif; ?>
            
                                <div style="height: 210px; display: flex; justify-content: center; align-items: center;">
                                    <?php
                                    $folderPath = "./assets/imgs/item/$item_id/";
                                    $images = glob($folderPath . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
                                    $imagePath = count($images) > 0 ? $images[0] : 'images/default-placeholder.png';
                                    ?>
                                    <a href=""><img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($name); ?>" 
                                            style="width: 200px; height: 200px; object-fit: cover; border-radius: 10px;"></a>
                                </div>
                            </div>
            
                            <div class="box-item-info" style="padding: 10px; text-align: center; margin-top: 20px;">
                                <h3 class="item-name m-bottom-0" style="margin-top: 10px; font-size: 18px; font-weight: bold;">
                                    <?php echo $name; ?>
                                </h3>
                                <div class="item-price-rate" style="margin-top: 10px;">
                                    <div class="item-price">
                                        <?php if ($has_colors): ?>
                                            <span class="sale">Rs.<?php echo number_format($u_price, 2); ?></span>
                                        <?php else: ?>
                                            <span class="sale">Rs.<?php echo number_format($price, 2); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                                        <!-- view action -->
                            <div class="button-loop-action list-shoppage-hide" style="margin-top: 10px;">
                                <ul class="loop-action" style="display: flex; justify-content: center; gap: 10px; list-style: none; padding: 0; margin: 0; height:53px;">
                                    <li class="item-action review">
                                        <a class="action-link" href="javascript:void(0);" data-item-id="<?php echo $item_id; ?>" 
                                           style="text-decoration: none;">
                                            <i class="icon ti-eye"></i>
                                        </a>
                                    </li>
                                    <li class="item-action wishlist">
                                        <a class="action-link" href="javascript:void(0);" style="text-decoration: none;">
                                            <i class="icon lnr lnr-heart"></i>
                                        </a>
                                    </li>
                                    <?php if (!$is_out_of_stock): ?>
                                        <li class="item-action add-to-card">
                                            <a class="action-link" href="javascript:void(0);" style="text-decoration: none;">
                                                <i class="icon icon-ecommerce-basket"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php } 
             } else{ 
                ?>
                    <div style="text-align: center; padding: 60px 20px; background-color: #f8f9fa; border-radius: 12px; margin: 20px auto; max-width: 600px;">
                <h2 style="color: #2c3e50; font-size: 24px; margin-bottom: 15px;">No Plants Available</h2>
                <p style="color: #7f8c8d; font-size: 16px; margin-bottom: 20px;">We're currently growing our collection in this category. Check back soon!</p>
                <a href="List_PlantShop.php" style="display: inline-block; background-color:rgba(20, 120, 62, 0.96); color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: bold;">View All Plants</a>
            </div>
        <?php
        }
        ?>
        </div>
    </div>
</section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap dropdown
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="assets\js\shop-scripts.js"></script>
<?php include "footer.php"; ?>