
<?php include "main-header.php"; ?> 
    <!-- Slide -->
    <div class="slide-page1">
      <div class="js-bg-slide-homepage1">
        <div>
          <img src="images/background/bg-header.jpg" alt="Slide" class="img-fluid bg-st1 img-width">
        </div>
        <div>
          <img src="images/background/bg-header-1.jpg" alt="Slide" class="img-fluid bg-st1 img-width">
        </div>
      </div>
      <div class="header-wrapper">
        <div class="header-inner js-box-space">
          <div class="container">
            <div class="header-slider" >
              <!-- slider -->
              <div class="slide-homepage">

                <!-- item 1 -->
                <div class="">
                  <div class="row">
                    <div class="col-md-9 col-3">
                      <div class="header-banner-left">
                        <div class="info-shop">
                          <span>Location: No 20,Ja-Ela- Gampaha Road, Ja-Ela </span>
                          <span class="time-to">Open-: 8:00 AM to 5:00 PM</span>
                        </div>
                        <h2 class="info-shop-tilte">The Sunshine Plant House </h2><br> <h4>You can easily order the plants you need from our website.</h4>
                        
                        <button class="btn-discovery btn-custom" href="List_PlantShop.php">
                        <a class="btn-custom" href="List_PlantShop.php">
                        <span class="btn-text" >Shop Now</span>
                          <span class="icon-arrow-right">
                            <i class="lnr lnr-arrow-right"></i>
                          </span>
                          </a>
                        </button>
                      </div>
                    </div>
                    
                  </div>
                </div>
                <!-- item 1 -->
                <div class="">
                  <div class="row">
                    <div class="col-md-6 col-6">
                      <div class="header-banner-left">
                        <div class="info-shop">
                          <span></span>
                         
                        </div>
                        <h2 class="info-shop-tilte">The Home <br> Of Interior Plants.</h2>
                        <p class="info-shop-des"> </p>
                        <button class="btn-discovery btn-custom">
                        <a class="btn-custom" href="List_PlantShop.php">
                          <span class="btn-text">Shop Now</span>
                          <span class="icon-arrow-right">
                            <i class="lnr lnr-arrow-right"></i>
                          </span>
                          </a>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
              <!-- end slider -->
            </div>
          </div>
          <div class="box-image-header col-xxl col-md-4 col-4 p-0 hide-laptop">
            <div class="box-image">
              <div class="js-slide-avatar">
                <div>
                  <img src="images/image-top.jpg" alt="Image">
                </div>
                <div>
                  <img src="images/image-top-1.jpg" alt="Image">
                </div>
              </div>
              <span class="box-text"></span>
            </div>
            <div class="row top-pagination homepage1-pagination justify-content-center">
              <a href="javscript:void(0)" class="btn-pg js-slide-previous">Back</a>
              <span class="line">|</span>
              <a href="javscript:void(0)" class="btn-pg js-slide-next">Next</a>
            </div>
          </div>
        </div>
        <div class="box-top-left js-box-mybg"></div>
        <div class="box-bottom-right hide-laptop pagingInfo">
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="main-content">

      <section class="popular-product padding-section" style="margin-top:10px;">
        <div class="container">
          <div class="row">
            <div class="box-title">
              <h5 class="mx-auto box-des des-popular-product">
                <span class="des-line">Perfect for growing plants</span>
              </h5>
              <h2 class="des-title"> Main Categories</h2>
            </div>
          </div>
         
      </section>
                                          <!-- Categories -->
          <?php
            function getCategoriesByType($category_type) {
              global $conn; 
              $sql = "SELECT Cate_Name FROM main_category WHERE Category_type = '$category_type'";
              $result = mysqli_query($conn, $sql);
              
              $categories = array();
              if($result && mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                      $categories[] = $row['Cate_Name'];
                  }
              }
              return $categories;
          }

// Categories section
?>

      <section class="categories padding-section" style="margin-top:10px;">
        <div class="container">
          <div class="row">
            <div class="col-xl-9 col-lg-12 cate-left">
              <div class="row clear">

                <div class="col-md-8 gutter">
                <!-- get the link with query parameter -->
                  <a href="categorized-item.php?type=Outdoor">
                    <div class="categories-item">
                      <div class="categories-info">
                        <h4 class="categories-info-name" style="background-color:white;">Outdoor Plants</h4>
                      </div> 
                      <img src="images/categories/outdoor.jpg" alt="Outdoor Plants">
                    </div>
                  </a>
                </div>

                <div class="col-md-4 gutter">

                  <a href="categorized-item.php?type=Indoor">
                    <div class="categories-item">
                      <img src="images/categories/indoor.jpg" alt="Indoor Plants">
                      <div class="categories-info">
                        <h4 class="categories-info-name" style="background-color:white;">Indoor Plants</h4>
                      </div>  
                    </div>
                  </a>
                </div>
              </div>

              <div class="row clear">
                <div class="col-md-4 gutter">

                  <a href="categorized-item.php?category=Table plants">
                    <div class="categories-item">
                      <img src="images/categories/best-selling.jpg" alt="Table Plants">
                      <div class="categories-info">
                        <h4 class="categories-info-name" style="background-color:white;">Table Plants</h4>
                      </div>  
                    </div>
                  </a>
                </div>

                <div class="col-md-8 gutter">

                  <a href="categorized-item.php?category=Flower">
                    <div class="categories-item">
                      <img src="images/categories/flowewring.jpg" alt="Flowering Plants">
                      <div class="categories-info">
                        <h4 class="categories-info-name" style="background-color:white;">Flowering Plants</h4>
                      </div>  
                    </div>
                  </a>
                </div>
              </div>
            </div>

            <div class="col-xl-3 gutter d-none-19">

              <a href="categorized-item.php?category=Cactus">
                <div class="categories-item">
                  <img src="images/categories/cactus-pot.jpg" alt="Cactus Plants">
                  <div class="categories-info">
                    <h4 class="categories-info-name" style="background-color:white;">Cactus Plant</h4>
                  </div>  
                </div>
              </a>
            </div>
          </div>
        </div>
      </section>

      <!-- Popular product -->
      <section class="popular-product padding-section">
        <div class="container">
          <div class="row">
            <div class="box-title">
              <h5 class="mx-auto box-des des-popular-product">
                <span class="des-line">Perfect for growing plants</span>
              </h5>
              <h2 class="des-title">Popular Products</h2>
            </div>
          </div>
         
      </section>

      <section class="deal-of-the-week padding-section" style="padding-top:50px;">
        <div class="container">
      

    </div>

    <!-- Footer -->
    
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-1.12.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="vendors/owlcarousel/owl.carouselv2.2.min.js"></script>
    <script src="vendors/slick/slick.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="vendors/rangeslider/js/ion.rangeSlider.min.js"></script>
    <script>
         // Show the message after login
    document.addEventListener("DOMContentLoaded", () => {
            const messageBox = document.getElementById("button-message");
            setTimeout(() => {
              messageBox.classList.add("show");
            }, 2000); //after 2 sec

            // Hide  after 8 seconds
            setTimeout(() => {
              messageBox.classList.remove("show");
              localStorage.setItem("messageDisplayed", "true");
            }, 8000);
          });
      </script>
<?php include "footer.php"; ?>   
  </body>
</html>