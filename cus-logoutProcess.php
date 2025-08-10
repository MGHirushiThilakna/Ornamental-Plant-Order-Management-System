
<?php 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$_SESSION = array();
session_destroy();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

include "loginHeader.php";
?>

<body>
   

    <div class='container mt-5'>
        <div class='progress'>
            <div class='progress-bar progress-bar-striped progress-bar-animated my-progress-bar' role='progressbar' aria-valuenow='100' aria-valuemin='0' aria-valuemax='100' style='width: 100%; background-color:#3d8361;  font-size:15px;'>
                <span class='itext'>Please Wait, Logging out...</span>
            </div>
        </div>
        <br>
    </div>
    <div class="container my-container" style=" margin-top:50px" >
    <div class="card my-login-card"  style="margin-top:50px; margin-left:500px">
        <div class="card-body my-card-body"   >
            
            <div class=" my-img-container" >
                
                        <img src="../assets/imgs/dashboars.jpg" class="img-fluid my-bg-img" />
                    </div>

    <script>
        // Redirect after 2 seconds
        setTimeout(function() {
            window.location.href = 'customerLogin.php';
        }, 2000);
    </script>


</body>