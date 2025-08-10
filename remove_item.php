<?php
// Start the session if required
session_start();

include('./config/constant.php');

// Get the cart ID from the URL
if (isset($_GET['id'])) {
    $cart_id = intval($_GET['id']); // Ensure it's an integer to prevent SQL injection

    // Prepare and execute the DELETE query
    $sql = "DELETE FROM cart WHERE id = ?";
    $stmt = $conn->prepare($sql); // Use prepared statements for security
    $stmt->bind_param("i", $cart_id); // Bind the parameter as an integer

    if ($stmt->execute()) {
        // Successfully deleted the item
        $_SESSION['success'] = "Item removed from the cart.";
    } else {
        // Failed to delete the item
        $_SESSION['error'] = "Failed to remove the item. Please try again.";
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "Invalid request.";
}

// Redirect back to the cart page
header("Location: index.php"); // Replace with your cart page's name
exit;
?>
