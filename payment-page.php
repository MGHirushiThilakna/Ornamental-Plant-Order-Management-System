<?php include('../config.php'); ?>

<?php
// Get bill_id and amount from the URL
$bill_id = isset($_GET['bill_id']) ? $_GET['bill_id'] : null;
$amount = isset($_GET['amount']) ? $_GET['amount'] : null;

//Get the bill type
$sql2 = "SELECT * FROM tbl_bill WHERE bill_id='$bill_id'";
$res2 = mysqli_query($conn, $sql2);
$count2 = mysqli_num_rows($res2);

if($count2>0){
    while($row=mysqli_fetch_assoc($res2)){
        $bill_type = $row['bill_type'];
    }
}

//Set the paymet hash and other variables
if ($bill_id && $amount) {
    // Retrieve bill details from the database
    $query = "SELECT * FROM tbl_bill WHERE bill_id = '$bill_id'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $bill = $result->fetch_assoc();
        $order_id = uniqid();
        $merchant_id = "1228591";
        $currency = "LKR";
        $merchant_secret = "MjM5ODI5Mzg0OTIyNjE0ODA1MjYyOTI0NDkxOTMxMjQyMTg1MDUx";

        // Generate hash for PayHere
        $hash = strtoupper(
            md5(
                $merchant_id . 
                $order_id . 
                number_format($amount, 2, '.', '') . 
                $currency .  
                strtoupper(md5($merchant_secret)) 
            ) 
        );
    } else {
        echo "Invalid bill ID.";
        exit;
    }
} else {
    echo "No bill ID or amount provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
    <script src="https://www.payhere.lk/lib/payhere.js"></script>
</head>
<body>

    <script>
        // PayHere callbacks
        payhere.onCompleted = function onCompleted(orderId) {
            console.log("Payment completed. OrderID:" + orderId);
            window.location.href = "payment-confirm.php?bill_id=<?php echo $bill_id; ?>&amount=<?php echo $amount; ?>&order_id=" + orderId;
        };

        payhere.onDismissed = function onDismissed() {
            console.log("Payment dismissed");
            window.location.href = "bill.php";
        };

        payhere.onError = function onError(error) {
            console.log("Error: " + error);
        };

        // Initialize payment object
        var payment = {
            "sandbox": true,
            "merchant_id": "<?php echo $merchant_id; ?>",
            "return_url": "http://localhost/OIMS/payment/",
            "cancel_url": "http://localhost/OIMS/payment/",
            "notify_url": "http://localhost/OIMS/payment/",
            "order_id": "<?php echo $order_id; ?>",
            "items": "<?php echo $bill_type; ?> - Bill #<?php echo $bill_id; ?>",
            "amount": "<?php echo $amount; ?>",
            "currency": "<?php echo $currency; ?>",
            "hash": "<?php echo $hash; ?>",
            "first_name": "Saman",
            "last_name": "Perera",
            "email": "samanp@gmail.com",
            "phone": "0771234567",
            "address": "No.1, Galle Road",
            "city": "Colombo",
            "country": "Sri Lanka"
        };

        // Start payment immediately when the page loads
        payhere.startPayment(payment);
    </script>
</body>
</html>