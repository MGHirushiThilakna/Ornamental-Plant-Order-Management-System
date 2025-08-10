<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
 <!-- Footer -->
 
 <!-- jQuery first, then Popper.js, then Bootstrap JS -->
 <script src="js/jquery-1.12.4.min.js"></script>
    <script src="vendors/owlcarousel/owl.carouselv2.2.min.js"></script>
    <script src="vendors/slick/slick.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="vendors/rangeslider/js/ion.rangeSlider.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="assets\js\account-creation.js"></script>
    <script src="assets/js/customer-logoutProccess.js"></script>

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
</html>