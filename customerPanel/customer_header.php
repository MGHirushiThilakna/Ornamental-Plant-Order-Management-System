
<?php  
session_start();
if(!isset($_SESSION['Customer_ID'])){
    header("Location: ../customerLogin.php");
    exit();
} 
$cusID = $_SESSION['Customer_ID'];
?>

<html>
  <head>
     <!-- Required meta tags -->
     <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Font family -->
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700%7CRoboto:400,500,700&display=swap" rel="stylesheet">

    <!-- Libary -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../vendors/owlcarousel/assets/owl.carouselv2.2.css">
    <link rel="stylesheet" href="../vendors/owlcarousel/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="../vendors/slick/slick.css">
    <link rel="stylesheet" href="../vendors/slick/slick-theme.css">
    <link rel="stylesheet" type="text/css" href="../css/animate.min.css">
    <link rel="stylesheet" href="../vendors/rangeslider/css/ion.rangeSlider.min.css"/>
    <link rel="stylesheet" href="../vendors/rangeslider/css/theme.scss.css">
    <link rel="stylesheet" href="../vendors/rangeslider/css/layout.min.css">

    <!-- Font -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../fonts/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="../fonts/linearicons/style.css">
    <link rel="stylesheet" href="../fonts/linea/styles.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    
   <!-- Sweet Alert 2-->
   <script src="..\assets\sweetalert2\jquery-3.5.1.min.js"></script>
    <script src="..\assets\sweetalert2\sweetalert2.all.min.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/homePage-style-A.css">
    <link rel="stylesheet" href="../css/styles-checkout.css">
    <link rel="icon" type="image/jpeg" href="../assets/imgs/brand_logo.jpg">

    <title>Account -Sunshine PlantHouse</title>
    <link rel="icon" type="image/jpeg" href="../assets/imgs/brand_logo.jpg">
  </head>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
		<script src="https://unpkg.com/unlazy@0.11.3/dist/unlazy.with-hashing.iife.js" defer init></script>
		<script type="text/javascript">
			window.tailwind.config = {
				darkMode: ['class'],
				theme: {
					extend: {
						colors: {
							border: 'hsl(var(--border))',
							input: 'hsl(var(--input))',
							ring: 'hsl(var(--ring))',
							background: 'hsl(var(--background))',
							foreground: 'hsl(var(--foreground))',
							primary: {
								DEFAULT: 'hsl(var(--primary))',
								foreground: 'hsl(var(--primary-foreground))'
							},
							secondary: {
								DEFAULT: 'hsl(var(--secondary))',
								foreground: 'hsl(var(--secondary-foreground))'
							},
							destructive: {
								DEFAULT: 'hsl(var(--destructive))',
								foreground: 'hsl(var(--destructive-foreground))'
							},
							muted: {
								DEFAULT: 'hsl(var(--muted))',
								foreground: 'hsl(var(--muted-foreground))'
							},
							accent: {
								DEFAULT: 'hsl(var(--accent))',
								foreground: 'hsl(var(--accent-foreground))'
							},
							popover: {
								DEFAULT: 'hsl(var(--popover))',
								foreground: 'hsl(var(--popover-foreground))'
							},
							card: {
								DEFAULT: 'hsl(var(--card))',
								foreground: 'hsl(var(--card-foreground))'
							},
						},
					}
				}
			}
		</script>
		<style type="text/tailwindcss">
			
  .top-header {
  background-color: #447e42;
  color: white;
  padding: 20px;
  justify-content: space-between;
  align-items: center;
  font-size: 14px;
  height:6%;
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  align-items: center;
  position: fixed;
  z-index: 2;
  top: 0px;
  left: 0px;
  right: 0px;
  border-bottom-width: 1px;
  border-bottom-style: solid;
  border-bottom-color: rgb(229, 224, 224);
}

.contact-info span {
  margin-right: 20px;
}

.user-actions a {
  color: white;
  font-weight:solid;
  text-decoration: none;
  margin-left: 30px;
}


.active-menu-item {
  background-color: #e6f4ea; /* Light green color */
  color: #2e7d32; /* Darker green for text contrast */
}

.sub-menu {
  position: absolute;
  background-color: #beddb5;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
  border-radius: 10px;
  display: none;
  z-index: 200;
  top: 42px;
  right: 6px;
  min-width: 120px;
  text-align: center;
}

.sub-menu a {
  color: #333;
  padding: 8px 16px;
  text-decoration: none;
  display: block;
}

.sub-menu a:hover {
  background-color: #dfeadc;
  border-radius: 10px;
}

@media (max-width: 768px) {
  .top-header {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .contact-info, .user-actions {
    width: 100%;
    margin-bottom: 10px;
  }
  
  .user-actions {
    display: flex;
    justify-content: space-between;
  }
  
  .user-actions a {
    margin-left: 0;
  }
}
		</style>
    <title>Sunshine Plant House - My Account</title>
  </head>
  <body>
    <div class="top-header">
      <div class="contact-info">
        <span>Phone: (+94) 112960036</span>
        <span>Email: sunshineplanthouse.store@gmail.com</span>
      </div>
  
      <div class="user-actions">
        
        <a href="../index.php">Home</a>
        <a href="../checkout.php">Checkout</a>
        <a href="../List_PlantShop.php">Shop</a>
        <a id="my-account" href="#">My Account</a>
        <div class="sub-menu" id="sub-menu">
                        <a href="customerDashboard.php">Sign In / Register</a>
                        <a  href="" id="logout"> Logout</a>
                        
                   </div>
      </div>
  </div>

  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
 <script src="../js/jquery-1.12.4.min.js"></script>
    <script src="../vendors/owlcarousel/owl.carouselv2.2.min.js"></script>
    <script src="../vendors/slick/slick.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../vendors/rangeslider/js/ion.rangeSlider.min.js"></script>
    <script src="../js/custom.js"></script>
    <script src="../assets\js\account-creation.js"></script>
    <script src="../assets/js/cusPanel_logout.js"></script>

    <script>document.addEventListener('DOMContentLoaded', function() {
  var myAccountLink = document.getElementById('my-account');
  var subMenu = document.getElementById('sub-menu');

  myAccountLink.addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default link behavior
    subMenu.classList.toggle('active');
  });
});
</script>
</body>
    