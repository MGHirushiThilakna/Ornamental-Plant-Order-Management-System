<?php 
include "header.php"; ?>
<?php
    session_start();
    if(isset($_SESSION['customerID'])){
        unset($_SESSION['customerID']);
    }
    if(isset($_SESSION['empID'])){
        unset($_SESSION['empID']);
    }
    if(isset($_SESSION['Driver_ID'])){
        unset($_SESSION['Driver_ID']);
    }
    session_destroy();

    echo"<meta http-equiv='refresh' content='2;url=staffLogin.php'>";
    echo"<div class='container mt-5'>
    <div class='progress'>
        <div class='progress-bar progress-bar-striped progress-bar-animated my-progress-bar' role='progressbar' aria-valuenow='100' aria-valuemin='0' aria-valuemax='100' style='width: 100%; background-color:#3d8361;''>
            <span class='itext'>Please Wait Loging out !...</span>
        </div>
    </div>
    <br>
            
    </div>";
    ?>

<?php include "footer.php";
?>