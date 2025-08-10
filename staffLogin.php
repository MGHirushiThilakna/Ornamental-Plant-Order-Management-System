<?php include "backendHeader.php"; ?>
<link rel="stylesheet" href="assets\css\login-style-A.css">

<div class="container my-container" style="margin-top:30px">
    <div class="card my-login-card"  style=" margin-left:390px">
        <div class="card-body my-card-body"   >
            
            <div class=" my-img-container" >
                
                        <img src="assets/imgs/dashboars.jpg" class="img-fluid my-bg-img" />
                    </div>
                <div class="row"   >
                    
                    
                    <div class="col-md-6 col-lg-11 col-xl-11 my-col"  >
                        <p class ="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4 title">Sign in</p>
                        <?php
                            if(isset($_GET['message'])){
                                $message=$_GET['message'];
                                echo "<div class='alert alert-danger alert-dismissible fade show mt-1' role='alert' >
                                        $message
                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                    </div>";
                            
                            }
                        ?>
<!--login form-->    <form method="POST" action = "staffLoginProcess.php" id="login">
                        <div class="form-floating my-form  mb-4">
                         <input type="text" id="email" name="email" class="form-control my-input shadow-none" placeholder="Username"/>
                         <label class="" for="email"><i class="fas fa-user me-2"></i>Email Address</label>
                         <div id="strUserError"></div>
                        </div>

                        <div class="form-floating my-form mb-4">
                        <input type="password" id="userpassword" name="password" class="form-control my-input shadow-none" placeholder="Password"/>
                        <label class="" for="password"><i class="fas fa-lock me-2"></i>Password</label>
                        <div id="strpasswordError"></div>
                        </div>
                        <button type="submit" name="login" class="btn  btn-lg btn-block mybtn mb-4">Sign in</button>
                        </form>
                        
                        <div class="row mb-2">
                        <div class="col-md-12 bt-divs">
                         <span>Forgot password? <a href="#" id="passRecover">Recover password</a></span>
                        </div>
                        </div>
                    </div>
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
<div class="modal fade" id="fogot-password-modal" data-bs-backdrop="static" data-bs-keyboard="false">     
    <div class="modal-dialog modal-lg">
        <link rel="stylesheet" href="assets\css\reg-modal-style.css">
        <div class="modal-content modal-forgt">
           
        </div>
    </div>
</div>
<div class="modal fade" id="OTP-modal" data-bs-backdrop="static" data-bs-keyboard="false">     
    <div class="modal-dialog modal-lg">
        <link rel="stylesheet" href="assets\css\reg-modal-style.css">
        <div class="modal-content modal-otp">
           
        </div>
    </div>
</div>
<div class="modal fade" id="change-password" data-bs-backdrop="static" data-bs-keyboard="false">     
    <div class="modal-dialog modal-lg">
        <link rel="stylesheet" href="assets\css\reg-modal-style.css">
        <div class="modal-content modal-change-password">
           
        </div>
    </div>
</div>
<script src="assets\js\account-creation.js"></script>
<script src="assets\js\forgot-pass.js"></script>
<?php include "login-footer.php" ?>