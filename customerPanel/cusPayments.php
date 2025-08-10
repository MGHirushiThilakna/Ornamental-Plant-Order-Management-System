<?php 
include "customerSidePanal.php";
$cusid =$_SESSION['Customer_ID'];
$cusName =$_SESSION['Name'];
?>
<head>
  <style>
   .mydash{
    height:500px;
   }
   .mainDash{
    margin-top: 100px;
    margin-left: 250px;
   }
    main{
      width: 70%;
    }
    img{
      width: 50px;
      height: 50px;
    }
    h2{
      font-size: 24px;
    }
    p{
      font-size: 18px;
    }
    img:hover{
      transform: scale(1.1);
    }
    div{
      margin-bottom: 20px;
    }
    h3{
      font-size: 20px;
    }
    p{
      font-size: 16px;
    }
    h4{
      font-size: 18px;
    }
    p{
      font-size: 14px;
    }
</style>
</head>
<body>
    <div class="flex flex-col lg:flex-row mydash" style="" >
      <main class="flex-1 lg:ml-2 mainDash" >
        <div class="bg-card p-4 rounded-lg shadow-md mb-3">
          <div class="flex items-center">
            <img src="../assets/imgs/signin.png" alt="User profile picture" class="w-16 h-16 rounded-full mr-4">
            <div>
              <h2 class="text-xl font-semibold">Payment</h2>
            </div>
          </div>

          
  </body>
  </html>