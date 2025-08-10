<?php include('./config/constant.php') ?>

<?php session_start(); ?>

<?php
if (isset($_SESSION["Customer_ID"])) {
  $cus_id = $_SESSION["Customer_ID"];
  $login = "logged";
} else {
  $login = "not";
}
?>

<?php
if (isset($_SESSION["voice_assist"])) {
  $voiceAssist = "ON";
} else {
  $voiceAssist = "OFF";
}
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Font family -->
  <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700%7CRoboto:400,500,700&display=swap"
    rel="stylesheet">

  <!-- Libary -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="vendors/owlcarousel/assets/owl.carouselv2.2.css">
  <link rel="stylesheet" href="vendors/owlcarousel/assets/owl.theme.default.min.css">
  <link rel="stylesheet" href="vendors/slick/slick.css">
  <link rel="stylesheet" href="vendors/slick/slick-theme.css">
  <link rel="stylesheet" type="text/css" href="css/animate.min.css">
  <link rel="stylesheet" href="vendors/rangeslider/css/ion.rangeSlider.min.css" />
  <link rel="stylesheet" href="vendors/rangeslider/css/theme.scss.css">
  <link rel="stylesheet" href="vendors/rangeslider/css/layout.min.css">

  <!-- Font -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="fonts/themify-icons/themify-icons.css">
  <link rel="stylesheet" href="fonts/linearicons/style.css">
  <link rel="stylesheet" href="fonts/linea/styles.css">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <!--fontawsome icons -->

  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/homePage-style-A.css">
  <link rel="stylesheet" href="css/styles-checkout.css">
  <link rel="icon" type="image/jpeg" href="assets/imgs/brand_logo.jpg">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/voice-assistance.js"></script>
  
  <!-- Sweet Alert 2-->
  <script src="..\assets\sweetalert2\jquery-3.5.1.min.js"></script>
  <script src="..\assets\sweetalert2\sweetalert2.all.min.js"></script>
  <script src="..\assets\js\staff-logoutProccess.js"></script>

  <title>Sunshine PlantHouse- Home</title>
  <style>
    .microphone {
      position: relative;
      /* Ensure the tooltip is relative to the button */
      display: inline-block;
      cursor: pointer;
    }

    .message-box {
      position: absolute;
      top: 110%;
      left: 50%;
      width:270px;
      transform: translateX(-50%);
      background-color:rgb(255, 255, 255);
      color:rgb(169, 50, 74);
      padding: 15px 20px;
      border-radius: 10px;
      border: 1px red solid ;
      font-size: 19px;
      font-weight: bold;
      box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
      visibility: hidden;
      opacity: 0;
      transition: opacity 0.3s ease, visibility 0.3s ease;
      z-index: 9999999999999999999999999;
      text-align: center;
    }

    /* Arrow pointing upwards */
    .message-box::after {
      content: '';
      position: absolute;
      bottom: 100%;
      /* Position the arrow at the top of the tooltip */
      left: 50%;
      transform: translateX(-50%);
      border-width: 12px;
      /* Make the arrow larger */
      border-style: solid;
      border-color: transparent transparent #355F2E transparent;
      /* Match the background color */
    }

    /* Show message */
    .message-box.show {
      visibility: visible;
      opacity: 1;
    }

    .my-swal-container {
      z-index: 99999 !important;
    }

    .my-swal-popup {
      position: relative;
      z-index: 99999 !important;
    }

    .swal2-backdrop-show {
      z-index: 99998 !important;
    }

    .login-warning-alert {
      z-index: 9999999999999 !important;
    }
  </style>
</head>

<body data-login="<?php echo $login; ?>">

  <!-- Header -->
  <div>
    <!-- Top header -->
    <div class="header-topbar style">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <div class="st2-info-header">
              <ul class="st2-info-header-list">
                <li class="st2-info-li st2-info-phone">
                  <span style="font-size:15px">Phone: (+94) 112960036</span>
                </li>
                <li class="st2-info-li st2-info-email">
                  <span style="font-size:15px">Email: sunshineplanthouse.store@gmail.com </span>
                </li>

              </ul>
            </div>
          </div>
          <div class="col-md-6" >
            <div class="st2-info-myacount" >
              <ul class="st2-myacount-list">

                <span id="voice-assistance" class="microphone">
                  <i class="fas fa-microphone"></i>
                  <span id="mic-status" style="font-size:16px"><?php echo $voiceAssist; ?></span>

                  <!-- Message box directly inside the voice assistance button -->
                  <div id="button-message" class="message-box">
                    සිංහල හඬ සහායක සක්‍රීය කරගැනීමට මෙය ක්ලික් කරන්න
                  </div>
                </span>

                <?php
                if ($login == "not") { ?>
                  <li class="st2-myacount-li st2-checkout" style="font-size:16px"><a class="st2-myacount-link" href="customerLogin.php">Login /
                      Register</a></li>
                  <?php
                } else { ?>
                  <li class="st2-myacount-li st2-checkout"><a class="st2-myacount-link" href="checkout.php">Checkout</a>
                  </li>
                  <li class="st2-myacount-li st2-myacount"><a class="st2-myacount-link" id="my-account" href="#">My
                      Account</a>
                    <div class="sub-menu" id="sub-menu">
                      <a href="customerPanel/customerDashboard.php">Profile</a>
                      <a href="" id="logout"> Logout</a>
                      <?php
                }
                ?>

                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="register-form-modal" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
      <link rel="stylesheet" href="assets\css\reg-modal-style.css">
      <div class="modal-content regForm">

      </div>
    </div>
  </div>
  <!-- Header bottom -->
  <div class="header-bottom">
    <div class="header-bottom-st2-wrapper">
      <div class="header-bottom-st2-inner">
        <div class="container">
          <div class="row js-compare">
            <div class="col-lg-3 st2-col-lapt-1 col-md-1 col-1">
              <div class="st2-header-logo">
                <img style="border-radius: 60px; width:70px" src="assets/imgs/brand_logo.jpg" alt="Logo">

                <span style="font-size:20px;margin-left:10px;color:#45a508;">Sunshine Plant House</span>
              </div>
            </div>
            <div class="col-lg-6 hide-reponsive js-dad">
              <!-- Menu -->
              <div class="st2-hamadryad-menu">
                <nav>
                  <ul class="st2-menu-primary">
                    <li class="st2-li-primary">
                      <a class="st2-item-link" href="index.php">Home</a>
                    </li>
                    <li class="st2-li-primary">
                      <a class="st2-item-link" href="List_PlantShop.php">Shop</a>
                      <!-- submenu home -->
                      <div class="st2-hamadryad-megamenu megamenu-home js-dropmenu megamenu-bg-active1"
                        style="border-radius: 55px;background-color:#D3F1DF; width:100px">
                        <section class="st2-hamadryad-megamenu-modal home">
                          <div class="container-fluid">
                            <div class="st2-hamadryad-submenu-wrapper" style="">

                              <div class="row">
                                <?php
                                // Function to get indoor/outdoor categories
                                function getCategoriesByCatType($category_type)
                                {
                                  global $conn; // Use the existing connection from constant.php
                                  $sql = "SELECT Cate_Name FROM main_category WHERE Category_type = '$category_type'";
                                  $result = mysqli_query($conn, $sql);

                                  $categories = array();
                                  if ($result && mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                      $categories[] = $row['Cate_Name'];
                                    }
                                  }
                                  return $categories;
                                }

                                // Categories section
                                ?>


                                <div class="col-md-3">
                                  <div class="st2-submenu-list-item">
                                    <h2 class="st2-submenu-item-title">Plant Category</h2>
                                    <ul class="st2-submenu-item-ul">
                                      <li class="st2-submenu-item-li">
                                        <a href="categorized-item.php?type=Indoor" class="st2-submenu-item-link">Indor
                                          Plants</a>
                                      </li>
                                      <li class="st2-submenu-item-li">
                                        <a href="categorized-item.php?type=Outdoor"
                                          class="st2-submenu-item-link">Outdoor Plants</a>
                                      </li>
                                      <li class="st2-submenu-item-li">
                                        <a href="categorized-item.php?category=Flower"
                                          class="st2-submenu-item-link">Flowering Plants</a>
                                      </li>
                                      <li class="st2-submenu-item-li">
                                        <a href="categorized-item.php?category=Cactus"
                                          class="st2-submenu-item-link">Cactus</a>
                                      </li>

                                    </ul>
                                  </div>
                                </div>
                                <div class="col-md-3">
                                  <div class="st2-submenu-list-item">
                                    <h2 class="st2-submenu-item-title"> .. </h2>
                                    <ul class="st2-submenu-item-ul">
                                      <li class="st2-submenu-item-li">
                                        <a href="categorized-item.php?category=Air Purifying plant"
                                          class="st2-submenu-item-link">Air Purifying plants</a>
                                      </li>
                                      <li class="st2-submenu-item-li">
                                        <a href="categorized-item.php?category=Table plants"
                                          class="st2-submenu-item-link">Table plants</a>
                                      </li>
                                      <li class="st2-submenu-item-li">
                                        <a href="categorized-item.php?category=Landscape Plant"
                                          class="st2-submenu-item-link">Landscape Plant</a>
                                      </li>
                                      <li class="st2-submenu-item-li">
                                        <a href="categorized-item.php?category=Aquatic Plant"
                                          class="st2-submenu-item-link">Aquatic Plant</a>
                                      </li>
                                    </ul>
                                  </div>
                                </div>

                                <div class="col-md-3">
                                  <div><img style="border-radius: 5px; width:400px; margin-top:25px;"
                                      src="assets/imgs/dashboars.jpg" alt="Logo">
                                  </div>
                                </div>

                              </div>
                            </div>
                          </div>
                        </section>
                      </div>
                      <!-- end submenu home -->
                    </li>
                    <li class="st2-li-primary"><a class="st2-item-link" href="">Plant Care</a></li>

                    <li class="st2-li-primary"><a class="st2-item-link" href="">About Us</a></li>
                  </ul>
                </nav>
              </div>
            </div>
            <div class="col-lg-3 st2-col-lapt-9 col-md-9 col-9">
              <div class="st2-control-right">
                <div class="st2-control">
                  <!-- search mobie -->
                  <div class="search-mobie" data-toggle="collapse" data-target="#showSearch">
                    <span class="lnr lnr-magnifier"></span>
                  </div>
                  <!-- search -->
                  <div class="st2-search-box st2-control-block hide-reponsive">
                    <div class="st2-search-block">
                      <form method="get" class="st2-search-form" action="/search">
                        <div class="at2-search-fields">
                          <div class="st2-search-input">
                            <input type="search" class="st2-search-field" placeholder="Search ..." value="" name="s">
                            <button type="submit" class="st2-search-submit">
                              <span class="lnr lnr-magnifier"></span>
                            </button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>

                  <?php
                  if (isset($_SESSION['Customer_ID'])) {
                    $cus_id = $_SESSION['Customer_ID'];
                    $query = "SELECT * FROM cart WHERE cus_id = '$cus_id'";
                    $result = mysqli_query($conn, $query);
                    $count = mysqli_num_rows($result);
                  }
                  ?>

                  <!-- cart -->
                  <div class="st2-cart-block st2-control-block js-click-cart">
                    <div class="st2-cart-icon">
                      <span class="icon-ecommerce-basket"></span>
                      <span class="st2-cart-number">
                        <?php
                        if (isset($_SESSION['Customer_ID'])) {
                          echo $count;
                        } else { ?>
                          <?php
                          echo 0;
                        } ?></span>
                    </div>
                  </div>
                  <!-- mega menu -->
                  <div class="st2-megamenu-block st2-control-block js-click-megamenu">
                    <div class="st2-megamenu-icon">
                      <span class="lnr lnr-menu"></span>
                    </div>
                  </div>

                </div>
              </div>
            </div>
            <!-- Form search -->
            <form method="get" class="search-form collapse" action="/search" id="showSearch">
              <div class="search-fields">
                <div class="search-input">
                  <span class="reset-instant-search-wrap"></span>
                  <input type="search" class="search-field" placeholder="Instant search ..." value="" name="s">
                  <button type="submit" class="search-submit">
                    <span class="lnr lnr-magnifier"></span>
                  </button>
                </div>
              </div>
            </form>
            <!-- End Form search -->
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Mini cart -->
  <?php
  if (isset($_SESSION['Customer_ID'])) {
    $cus_id = $_SESSION['Customer_ID'];

    $grand_total = 0;

    $query = "SELECT * FROM cart WHERE cus_id = '$cus_id'";
    $result = mysqli_query($conn, $query);
    $count = mysqli_num_rows($result); ?>

    <div class="hamadryad-minicart">
      <div class="hamadryad-minicart-content">
        <h3 class="hamadryad-minicart-title">Your cart</h3>
        <span class="hamadryad-minicart-number"><?php echo $count; ?></span>
        <div class="hamadryad-minicart-close">
          <i class="lnr lnr-cross"></i>
        </div>
        <div class="hamadryad-minicart-list-item">
          <div class="list-item">
            <ol class="hamadryad-minicart-items">

              <?php
              if ($count > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                  $cart_id = $row['id'];
                  $item_id = $row['item_id'];
                  $color_id = $row['color_id'];
                  $quantity = $row['Qty'];

                  $query1 = "SELECT * FROM item WHERE item_id = '$item_id'";
                  $result1 = mysqli_query($conn, $query1);
                  $count1 = mysqli_num_rows($result1);

                  if ($count1 > 0) {
                    while ($row1 = mysqli_fetch_assoc($result1)) {
                      $name = $row1['name'];
                      $colors = $row1['colors'];

                      if ($colors == "Yes") {

                        $query2 = "SELECT * FROM colors WHERE clr_id = '$color_id'";
                        $result2 = mysqli_query($conn, $query2);
                        $count2 = mysqli_num_rows($result2);

                        if ($count2 > 0) {
                          while ($row2 = mysqli_fetch_assoc($result2)) {
                            $color = $row2['color'];
                            $price = $row2['u_price'];
                          }
                        }

                      } else {
                        $price = $row1['price'];
                      }
                    }
                  }

                  $total_price = $price * $quantity;
                  ?>

                  <?php
                  $imageFolderPath = "./assets/imgs/item/$item_id"; // Define the folder path
            
                  // Check if the folder exists and is readable
                  if (is_dir($imageFolderPath) && is_readable($imageFolderPath)) {
                    // Get all files in the folder
                    $files = scandir($imageFolderPath);

                    // Filter the files to include only images (jpg, jpeg, png, gif, etc.)
                    $imageFiles = array_filter($files, function ($file) use ($imageFolderPath) {
                      $filePath = "$imageFolderPath/$file";
                      return is_file($filePath) && preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
                    });

                    // Get the first image if available
                    $firstImage = !empty($imageFiles) ? array_values($imageFiles)[0] : null;
                  } else {
                    $firstImage = null;
                  }

                  // Define the image source or fallback
                  $imageSrc = $firstImage ? "$imageFolderPath/$firstImage" : "./assets/imgs/default-image.jpg";
                  ?>
                  <li class="product-cart">
                    <a href="" class="product-cart-thumb"><img src="<?php echo $imageSrc; ?>"
                        alt="<?php echo htmlspecialchars($item['name'], ENT_QUOTES); ?>" class="product-image"></a>
                    <div class="product-detail">
                      <div class="product-name"><a href="#"><?php echo $name; ?></a></div>
                      <div class="product-detail-info">
                        <div class="product-quality">QTY : <?php echo $quantity; ?></div>
                        <div class="product-cost">Rs <?php echo number_format($total_price, 2, '.', ','); ?></div>
                      </div>
                    </div>
                    <div class="product-remove">
                      <a href="remove_item.php?id=<?php echo $cart_id; ?>"><i class="lnr lnr-cross"></i></a>
                    </div>
                  </li>
                  <?php

                  $grand_total = $grand_total + $total_price;
                }
              }
              ?>

            </ol>
          </div>
        </div>
        <div class="subtotal">
          <span class="total-title">Total:</span>
          <span class="total-price">Rs <?php echo number_format($grand_total, 2, '.', ','); ?></span>
        </div>
        <div class="hamadryad-minicart-action">
          <div class="btn-action-minicart viewcart">Viewcart</div>
          <div class="btn-action-minicart checkout">
            <a style="color: white;" href="./checkout.php">Checkout</a>
          </div>
        </div>
      </div>
    </div>
    <div class="minicart-bg-overlay" style="display: none;"></div>

    <?php
  } else { ?>

    <div class="hamadryad-minicart">
      <div class="hamadryad-minicart-content">
        <h3 class="hamadryad-minicart-title">Your cart</h3>
        <span class="hamadryad-minicart-number"></span>
        <div class="hamadryad-minicart-close">
          <i class="lnr lnr-cross"></i>
        </div>
        <div class="hamadryad-minicart-list-item">
          <div class="list-item">
            <ol class="hamadryad-minicart-items">
              <li class="product-cart" style="display: flex; justify-content: center; align-items: center;">
                <a href="customerLogin.php" class="product-cart-thumb">
                  <button
                    style="background-color: #80af81; color: white; border: none; border-radius: 5px; padding: 10px 20px; width:160px; cursor: pointer; font-size: 16px; transition: background-color 0.3s ease;">
                    Add to cart</button></a>
                <div class="product-detail">
                </div>
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <?php
  }
  ?>

  <!-- Menu mobie -->
  <div class="box-mobile-menu">
    <span class="box-title">Menu</span>
    <a href="#" class="close-menu" id="pull-closemenu">
      <i class="lnr lnr-cross"></i>
    </a>

  </div>
  <div class="menu-overlay"></div>

  </header>

  <script src="js/jquery-1.12.4.min.js"></script>
  <script src="vendors/owlcarousel/owl.carouselv2.2.min.js"></script>
  <script src="vendors/slick/slick.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="vendors/rangeslider/js/ion.rangeSlider.min.js"></script>
  <script src="js/custom.js"></script>
  <script src="assets\js\account-creation.js"></script>
  <script src="assets/js/customer-logoutProccess.js"></script>
</body>