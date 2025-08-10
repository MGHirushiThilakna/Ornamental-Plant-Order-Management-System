<?php include "./main-header.php"; ?>

<?php
if (isset($_SESSION['Customer_ID'])) {
    $cus_id = $_SESSION['Customer_ID'];
} else {
    header('Location: ./customerLogin.php');
}
?>

<?php // get the delivery charge
$sql = "SELECT value FROM delivery_charge WHERE name = 'delivery-charge'";
$result = $conn->query($sql);

$delivery_charge = 0;
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $delivery_charge = floatval($row['value']);
}
?>

<script>// for the javascript
    const DELIVERY_CHARGE = <?php echo json_encode($delivery_charge); ?>;
</script>

<script src="https://www.payhere.lk/lib/payhere.js"></script>

<style>
    .step-content #order-summary {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        margin: 20px 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .summary-section {
        margin-bottom: 20px;
        padding: 15px;
        background-color: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .summary-section h3 {
        font-size: 20px;
        color: #333;
        margin-bottom: 10px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 5px;
    }

    /* Styling <p> tags */
    .summary-section p {
        font-size: 16px;
        color: #555;
        line-height: 1.6;
        margin: 10px 0;
        padding: 5px 10px;
        background-color: #f7f7f7;
        border: 1px solid #eaeaea;
        border-radius: 4px;
    }

    /* Styling divs for displaying dynamic content */
    .summary-section div {
        font-size: 16px;
        color: #444;
        margin: 10px 0;
        padding: 10px;
        border: 1px solid #ddd;
        background-color: #fdfdfd;
        border-radius: 6px;
    }

    #summary-total-price {
        font-weight: bold;
        color: #007bff;
        font-size: 18px;
        background-color: #eef7ff;
        padding: 8px 12px;
        border-radius: 6px;
    }

    /* Add hover effects for <p> and <div> */
    .summary-section p:hover,
    .summary-section div:hover {
        background-color: #e6f7ff;
        border-color: #b3e5fc;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .summary-breakdown {
        margin-top: 15px;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 6px;
    }

    .summary-breakdown p {
        margin: 8px 0;
        padding: 5px 0;
    }

    .subtotal-line {
        border-bottom: 1px solid #dee2e6;
    }

    .delivery-charge-line {
        color: #666;
        font-style: italic;
    }

    .grand-total-line {
        font-weight: bold;
        color: #007bff;
        border-top: 1px solid #dee2e6;
        margin-top: 10px !important;
        padding-top: 10px !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .step-content {
            padding: 15px;
        }

        .summary-section {
            padding: 10px;
        }

        .summary-section h3 {
            font-size: 18px;
        }

        .summary-section p,
        .summary-section div {
            font-size: 14px;
            padding: 8px;
        }

        #summary-total-price {
            font-size: 16px;
            padding: 6px 10px;
        }
    }
</style>

<?php
// get from handleBuyNow
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_id = isset($_POST['item_id']) ? $_POST['item_id'] : '0';
    $item_name = isset($_POST['item_name']) ? htmlspecialchars($_POST['item_name']) : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $color_id = isset($_POST['color_id']) ? htmlspecialchars($_POST['color_id']) : null;
    $color = isset($_POST['color']) ? htmlspecialchars($_POST['color']) : null;

    // Calculate total price
    $total_price = $price * $quantity;

    // Fetch additional item details
    $item_query = "SELECT *, 
                    CASE WHEN colors = 'Yes' THEN 
                    (SELECT color FROM colors WHERE clr_id = ?) 
                    ELSE NULL END AS item_color, 
                    CASE WHEN colors = 'Yes' THEN 
                    (SELECT u_price FROM colors WHERE clr_id = ?) 
                    ELSE price END AS color_price
                    FROM item WHERE item_id = ?";
    $stmt = mysqli_prepare($conn, $item_query);
    mysqli_stmt_bind_param($stmt, 'ssi', $color_id, $color_id, $item_id);
    mysqli_stmt_execute($stmt);
    $item_result = mysqli_stmt_get_result($stmt);
    $item = mysqli_fetch_assoc($item_result);
    ?>

    <div class="checkout-center">
        <div class="checkout-container" style="margin-top: 150px; width:1%">
            <div class="row" style="margin-bottom: 25px;width:1% height:0.5% padding:10px;" id="progress-bar">
                <div class="step active">Item Details</div>
                <div class="step">Delivery Option</div>
                <div class="step">Order Summary</div>
            </div>

            <form id="checkout-form">
                <div class="step-content active" id="item-details">
                    <?php if ($item): ?>
                        <?php
                        $imageFolderPath = "./assets/imgs/item/{$item_id}";
                        $files = scandir($imageFolderPath);
                        $imageFiles = array_filter($files, function ($file) {
                            return preg_match('/\.(jpg|jpeg|png)$/i', $file);
                        });

                        sort($imageFiles);

                        $firstImage = !empty($imageFiles) ? $imageFiles[0] : null;

                        if ($firstImage) {
                            $imagePath = "{$imageFolderPath}/{$firstImage}";
                        } else {
                           
                            $imagePath = "./assets/imgs/default-image.jpg";
                        }
                        ?>

                        <div class="product-card" data-product-id="<?php echo $item_id; ?>">
                            <img src="<?php echo $imagePath; ?>" alt="<?php echo $item['name']; ?>" class="product-image">
                            <div class="product-info">
                                <h2 style="font-size:24px; font-weight: bold;"><?php echo $item['name']; ?></h2>

                                <?php if ($item['colors'] == 'Yes' && !empty($item['item_color'])): ?>
                                    <p>Color: <span class="color-circle"
                                            style="background-color: <?php echo $item['item_color']; ?>; 
                                    display: inline-block; width: 15px; height: 15px; border-radius: 50%; margin-left: 5px;"
                                            data-color-id="<?php echo $color_id; ?>"></span></p>
                                <?php endif; ?>

                                <div class="quantity-control">
                                    <button type="button" class="qty-btn minus">-</button>
                                    <input type="number" class="qty-input" value="<?php echo $quantity; ?>" min="1"
                                        data-price="<?php echo $price; ?>">
                                    <button type="button" class="qty-btn plus">+</button>
                                </div>
                                <p class="price">Rs <?php echo number_format($price, 2); ?></p>
                                <p class="total-price">Total: Rs <?php echo number_format($total_price, 2, '.', ','); ?></p>
                            </div>
                        </div>
                    <?php else: ?>
                        <p>No item found.</p>
                    <?php endif; ?>
                </div>
                <button type="button" id="first-next-btn" class="next-btn" <?php echo (!$item) ? 'disabled' : ''; ?>>Next</button>

                <div class="step-content" id="delivery-option">
                    <div class="delivery-selection">
                        <label>
                            <input type="radio" name="delivery" value="store-pickup" checked>
                            Store Pickup
                        </label>
                        <label>
                            <input type="radio" name="delivery" value="delivery">
                            Home Delivery
                        </label>
                        <label>
                            <input type="radio" name="delivery" value="cod">
                            Cash on Delivery
                        </label>
                    </div>
                    <div class="store-pickup-details">
                        <input type="tel" class="store-pickup-tel" placeholder="Phone Number" name="pickup-phone" required>
                    </div>
                    <div class="delivery-details" style="display:none;">
                        <input type="text" placeholder="Full Address" name="address">
                        <input type="tel" placeholder="Phone Number" name="delivery-phone">
                    </div>
                    <div class="row">
                        <button type="button" class="prev-btn" style="flex: 1; margin-right: 10px;">Previous</button>
                        <button type="button" class="next-btn" style="flex: 1; margin-left: 10px;">Next</button>
                    </div>
                </div>

                <div class="step-content" id="order-summary">
                    <div class="summary-section product-summary">
                        <h3>Product Details</h3>
                        <div id="summary-products"></div>
                        <p id="summary-total-price"></p>
                    </div>
                    <div class="summary-section delivery-summary">
                        <h3>Delivery Details</h3>
                        <p id="summary-delivery-method"></p>
                        <p id="summary-address"></p>
                        <p id="summary-phone"></p>
                    </div>
                    <div class="row">
                        <button type="button" class="prev-btn" style="flex: 1; margin-right: 10px;">Previous</button>
                        <button type="button" class="next-btn pay-btn" style="flex: 1; margin-left: 10px;">Pay Now</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php
} else {

    // Fetch cart items from database with color details
    $cart_query = "SELECT ci.*, p.name, p.price, p.colors, 
            CASE WHEN p.colors = 'Yes' THEN c.color ELSE NULL END AS item_color,
            CASE WHEN p.colors = 'Yes' THEN c.u_price ELSE p.price END AS color_price
            FROM cart ci
            JOIN item p ON ci.item_id = p.item_id
            LEFT JOIN colors c ON ci.color_id = c.clr_id
            WHERE ci.cus_id = '$cus_id'";
    $cart_result = mysqli_query($conn, $cart_query);
    ?>

    <div class="checkout-center" style="margin-top: 350px; width:1%">
        <div class="checkout-container">
            <div class="row" style="margin-bottom: 25px;" id="progress-bar">
                <div class="step active">Item Details</div>
                <div class="step">Delivery Option</div>
                <div class="step">Order Summary</div>
            </div>

            <form id="checkout-form">
                <div class="step-content active" id="item-details">
                    <?php if (mysqli_num_rows($cart_result) > 0): ?>
                        <?php while ($item = mysqli_fetch_assoc($cart_result)): ?>

                            <?php
                            $imageFolderPath = "./assets/imgs/item/{$item['item_id']}";

                            $files = scandir($imageFolderPath);
                            $imageFiles = array_filter($files, function ($file) {
                                return preg_match('/\.(jpg|jpeg|png)$/i', $file); 
                            });
                            sort($imageFiles);

                            $firstImage = !empty($imageFiles) ? $imageFiles[0] : null;

                            if ($firstImage) {
                                $imagePath = "{$imageFolderPath}/{$firstImage}";
                            } else {
                               
                                $imagePath = "./assets/imgs/default-image.jpg";
                            }
                            ?>

                            <div class="product-card" data-product-id="<?php echo $item['item_id']; ?>">
                                <img src="<?php echo $imagePath; ?>" alt="<?php echo $item['name']; ?>" class="product-image">
                                <div class="product-info">
                                    <h2 style="font-size:24px; font-weight: bold;"><?php echo $item['name']; ?></h2>

                                    <?php if ($item['colors'] == 'Yes' && !empty($item['item_color'])): ?>
                                        <p>Color: <span class="color-circle"
                                                style="background-color: <?php echo $item['item_color']; ?>; 
                                      display: inline-block; width: 15px; height: 15px; border-radius: 50%; margin-left: 5px;"
                                                data-color-id="<?php echo $item['color_id']; ?>"></span></p>
                                    <?php endif; ?>
                                    <div class="quantity-control">
                                        <button type="button" class="qty-btn minus">-</button>
                                        <input type="number" class="qty-input" value="<?php echo $item['Qty']; ?>" min="1"
                                            data-price="<?php echo $item['color_price']; ?>">
                                        <button type="button" class="qty-btn plus">+</button>
                                    </div>
                                    <p class="price">Rs <?php echo number_format($item['color_price'], 2); ?></p>
                                    <p class="total-price">Total: Rs
                                        <?php echo number_format($item['color_price'] * $item['Qty'], 2, '.', ','); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>Your cart is empty.</p>
                    <?php endif; ?>
                </div>
                <button type="button" id="first-next-btn" class="next-btn" <?php echo (mysqli_num_rows($cart_result) == 0) ? 'disabled' : ''; ?>>Next</button>

                <div class="step-content" id="delivery-option">
                    <div class="delivery-selection">
                        <label>
                            <input type="radio" name="delivery" value="store-pickup" checked>
                            Store Pickup
                        </label>
                        <label>
                            <input type="radio" name="delivery" value="delivery">
                            Home Delivery
                        </label>
                        <label>
                            <input type="radio" name="delivery" value="cod">
                            Cash on Delivery
                        </label>
                    </div>
                    <div class="store-pickup-details">
                        <input type="tel" class="store-pickup-tel" placeholder="Phone Number" name="pickup-phone" required>
                    </div>
                    <div class="delivery-details" style="display:none;">
                        <input type="text" placeholder="Full Address" name="address">
                        <input type="tel" placeholder="Phone Number" name="delivery-phone">
                    </div>
                    <div class="row">
                        <button type="button" class="prev-btn" style="flex: 1; margin-right: 10px;">Previous</button>
                        <button type="button" class="next-btn" style="flex: 1; margin-left: 10px;">Next</button>
                    </div>
                </div>

                <div class="step-content" id="order-summary">
                    <div class="summary-section product-summary">
                        <h3>Product Details</h3>
                        <div id="summary-products"></div>
                        <p id="summary-total-price"></p>
                    </div>
                    <div class="summary-section delivery-summary">
                        <h3>Delivery Details</h3>
                        <p id="summary-delivery-method"></p>
                        <p id="summary-address"></p>
                        <p id="summary-phone"></p>
                    </div>
                    <div class="row">
                        <button type="button" class="prev-btn" style="flex: 1; margin-right: 10px;">Previous</button>
                        <button type="button" class="next-btn pay-btn" style="flex: 1; margin-left: 10px;">Pay Now</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php } ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        const $steps = $(".step");
        const $stepContents = $(".step-content");
        const $nextBtns = $(".next-btn");
        const $prevBtns = $(".prev-btn");



        // Enable or disable the "Next" button in Step 1 based on cart items
        function updateNextButtonState(stepIndex) {
            if (stepIndex === 0) {
                const hasItems = $(".product-card").length > 0;
                $("#first-next-btn").prop("disabled", !hasItems);
            } else if (stepIndex === 1) {
                const deliveryMethod = $('input[name="delivery"]:checked').val();
                if (deliveryMethod === "delivery") {
                    const address = $('input[name="address"]').val().trim();
                    const phone = $('input[name="delivery-phone"]').val().trim();
                    $("#second-next-btn").prop("disabled", !(address && phone));
                } else if (deliveryMethod === "store-pickup") {
                    const phone = $('input[name="pickup-phone"]').val().trim();
                    $("#second-next-btn").prop("disabled", !phone);
                } else {
                    $("#second-next-btn").prop("disabled", true);
                }
            }
        }

        // Navigate to the next or previous step
        function navigateStep(currentIndex, nextIndex) {
            $stepContents.removeClass("active").hide(); 
            $stepContents.eq(nextIndex).addClass("active").show(); 
            $steps
                .removeClass("active")
                .slice(0, nextIndex + 1)
                .addClass("active"); 

            if (nextIndex === 0) {
                $("#first-next-btn").show(); 
            } else {
                $("#first-next-btn").hide(); 
            }
        }
        // Update item total price for a plant
        function updateItemTotal($productCard) {
            const price = parseFloat($productCard.find(".qty-input").data("price"));
            const quantity = parseInt($productCard.find(".qty-input").val());
            const totalPrice = (price * quantity).toFixed(2);
            $productCard.find(".total-price").text(`Total: Rs ${totalPrice}`);
            updateNextButtonState(0);
        }

        // Calculate the overall total for all plants
        function calculateOverallTotal() {
            let overallTotal = 0;
            $(".product-card").each(function () {
                const price = parseFloat($(this).find(".qty-input").data("price"));
                const quantity = parseInt($(this).find(".qty-input").val());
                overallTotal += price * quantity;
            });
            return overallTotal.toFixed(2);
        }

        // Update button text based on delivery method
        function updatePayButtonText() {
            const deliveryMethod = $('input[name="delivery"]:checked').val();
            const $payBtn = $('.pay-btn');
            if (deliveryMethod === 'cod') {
                $payBtn.text('Place Order');
            } else {
                $payBtn.text('Pay Now');
            }
        }

        // Update the summary section in Step 3
        function updateSummary() {
            const deliveryMethod = $('input[name="delivery"]:checked').val();
            let address, phone;
            let deliveryCharge = 0;

            // Determine address, phone, and delivery charge based on delivery method
            switch (deliveryMethod) {
                case 'store-pickup':
                    address = "Store Pickup";
                    phone = $('input[name="pickup-phone"]').val();
                    deliveryCharge = 0;
                    break;
                case 'delivery':
                case 'cod':
                    address = $('input[name="address"]').val();
                    phone = $('input[name="delivery-phone"]').val();
                    deliveryCharge = (deliveryMethod === 'delivery' || deliveryMethod === 'cod') ? DELIVERY_CHARGE : 0;
                    break;
                default:
                    address = "Not specified";
                    phone = "Not specified";
                    deliveryCharge = 0;
            }

            // Format delivery method display text
            let displayMethod = deliveryMethod
                .split('-')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
            if (deliveryMethod === 'cod') {
                displayMethod = 'Cash on Delivery';
            }

            // Build products summary HTML
            let productsHtml = "";
            let subtotal = 0;

            $(".product-card").each(function () {
                const name = $(this).find("h2").text();
                const quantity = $(this).find(".qty-input").val();
                const price = parseFloat($(this).find(".qty-input").data("price"));
                const itemTotal = price * quantity;
                subtotal += itemTotal;
                const itemId = $(this).data("product-id");

                const $colorCircle = $(this).find(".color-circle");
                const colorId = $colorCircle.data("color-id") || 'N/A';
                const colorText = colorId !== 'N/A' ? ` - Color ID: ${colorId}` : '';

                productsHtml += `<p>Item ID: ${itemId}${colorText} - ${name} - Qty: ${quantity} - Total: Rs ${itemTotal.toFixed(2)}</p>`;
            });

            const grandTotal = subtotal + deliveryCharge;

            // Update the summary sections
            $("#summary-products").html(productsHtml);
            const summaryBreakdown = `
        <div class="summary-breakdown">
            <p class="subtotal-line">Subtotal: Rs ${subtotal.toFixed(2)}</p>
            <p class="delivery-charge-line">Delivery Charge: Rs ${deliveryCharge.toFixed(2)}</p>
            <p class="grand-total-line">Grand Total: Rs ${grandTotal.toFixed(2)}</p>
        </div>
    `;

            $("#summary-total-price").html(summaryBreakdown);
            $("#summary-delivery-method").text(`Method: ${displayMethod}`);
            $("#summary-address").text(`Address: ${address || 'Not provided'}`);
            $("#summary-phone").text(`Phone: ${phone || 'Not provided'}`);

            // Store the grand total for payment processing
            window.orderGrandTotal = grandTotal;
        }

        // Event listeners for quantity control
        $(".qty-btn.plus").click(function () {
            const $input = $(this).siblings(".qty-input");
            const currentValue = parseInt($input.val());
            $input.val(currentValue + 1);
            updateItemTotal($(this).closest(".product-card"));
        });

        $(".qty-btn.minus").click(function () {
            const $input = $(this).siblings(".qty-input");
            const currentValue = parseInt($input.val());
            if (currentValue > 1) {
                $input.val(currentValue - 1);
                updateItemTotal($(this).closest(".product-card"));
            }
        });

        // Add to existing delivery option change handler
        $('input[name="delivery"]').change(function () {
            const deliveryValue = $(this).val();
            $(".delivery-details").toggle(deliveryValue === "delivery" || deliveryValue === "cod");
            $(".store-pickup-details").toggle(deliveryValue === "store-pickup");
            updateNextButtonState(1);
            updatePayButtonText();
        });

        updatePayButtonText();

        // Input validation for delivery fields
        $("input").on("input", function () {
            const currentIndex = $stepContents.index($(".step-content.active"));
            updateNextButtonState(currentIndex);
        });

        // Next button functionality
        $nextBtns.click(function () {
            const currentIndex = $stepContents.index($(".step-content.active"));
            if (validateStep(currentIndex)) {
                navigateStep(currentIndex, currentIndex + 1);
                if (currentIndex === 1) updateSummary(); 
            }
        });

        // Previous button functionality
        $prevBtns.click(function () {
            const currentIndex = $stepContents.index($(".step-content.active"));
            navigateStep(currentIndex, currentIndex - 1);
        });

        // Pay button click handler
        
        $(".pay-btn").click(function (e) {
            e.preventDefault();
            e.stopPropagation();

            const deliveryMethod = $('input[name="delivery"]:checked').val();
            let isValid = true;
            let address, phone;
            const deliveryCharge = (deliveryMethod === 'delivery' || deliveryMethod === 'cod') ? 400 : 0; // not used

            // Validate delivery information but  this section will be not used anymore bcz next button is desable when address and phone are not provided
            if (deliveryMethod === "store-pickup") {
                phone = $('input[name="pickup-phone"]').val().trim();
                address = "Store Pickup";
                if (!phone) {
                    alert("Please enter your phone number for store pickup");
                    isValid = false;
                }
            } else {
                address = $('input[name="address"]').val().trim();
                phone = $('input[name="delivery-phone"]').val().trim();
                if (!address || !phone) {
                    alert("Please enter both delivery address and phone number");
                    isValid = false;
                }
            }

            if (!isValid) return;

            // Calculate totals
            const subtotal = parseFloat(calculateOverallTotal());
            const grandTotal = subtotal + deliveryCharge;

            // Collect items information
            const items = [];
            $(".product-card").each(function () {
                const $card = $(this);
                const $colorCircle = $card.find(".color-circle");
                const $qtyInput = $card.find(".qty-input");

                items.push({
                    itemId: $card.data('product-id'),
                    name: $card.find("h2").text(),
                    quantity: parseInt($qtyInput.val()),
                    price: parseFloat($qtyInput.data("price")),
                    colorId: $colorCircle.length ? $colorCircle.data('color-id') : "N/A"
                });
            });

            if (deliveryMethod === "cod") {
                // Handle COD order processing
                const $payBtn = $(this);
                $payBtn.prop('disabled', true).text('Processing...');

                $.ajax({
                    url: 'process-cod-order.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        items: JSON.stringify(items),
                        address: address,
                        phone: phone,
                        deliveryCharge: deliveryCharge,
                        subtotal: subtotal,
                        grandTotal: grandTotal
                    },
                    success: function (response) {
                        if (response.error) {
                            alert('Error: ' + response.message);
                            $payBtn.prop('disabled', false).text('Place Order');
                            return;
                        }
                        window.location.href = "cod-order-confirm.php?order_id=" + response.order_id;
                    },
                    error: function (xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                        alert('Failed to process order. Please try again.');
                        $payBtn.prop('disabled', false).text('Place Order');
                    }
                });
            } else {
                // Handle PayHere payment
                $.ajax({
                    url: 'process-order.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        grandTotal: grandTotal,
                        deliveryMethod: deliveryMethod,
                        address: address,
                        phone: phone,
                        items: items  // Send items array directly
                    },
                    success: function (response) {
                        if (response.error) {
                            alert('Error: ' + response.message);
                            return;
                        }

                        // Configure PayHere payment
                        const payment = {
                            sandbox: true,
                            merchant_id: response.merchant_id,
                            return_url: response.return_url,
                            cancel_url: response.cancel_url,
                            notify_url: response.notify_url,
                            order_id: response.order_id,
                            items: response.items,
                            amount: response.amount,
                            currency: response.currency,
                            hash: response.hash,
                            first_name: response.first_name,
                            last_name: response.last_name,
                            email: response.email,
                            phone: response.phone,
                            address: response.address,
                            city: response.city,
                            country: response.country
                        };

                        // Start PayHere payment
                        payhere.startPayment(payment);
                    },
                    error: function (xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                        alert('Failed to process payment. Please try again.');
                    }
                });
            }
        });

        // Update delivery details visibility based on selected method
        $('input[name="delivery"]').change(function () {
            const deliveryValue = $(this).val();
            $(".delivery-details").toggle(deliveryValue === "delivery" || deliveryValue === "cod");
            $(".store-pickup-details").toggle(deliveryValue === "store-pickup");
            updateNextButtonState(1);
        });

        function validateInputs() {
            // Add validation logic for delivery
            const deliveryMethod = $('input[name="delivery"]:checked').val();
            if (!deliveryMethod) return false;

            if (deliveryMethod === "delivery") {
                const address = $('input[name="address"]').val().trim();
                const phone = $('input[name="delivery-phone"]').val().trim();
                return address && phone;
            }

            return $('input[name="pickup-phone"]').val().trim() !== '';
        }

        // PayHere callback functions 
        payhere.onCompleted = function onCompleted(orderId) {
            console.log("Payment completed. OrderID:" + orderId);

            const deliveryMethod = $('input[name="delivery"]:checked').val();
            const deliveryCharge = (deliveryMethod === 'delivery') ? 400 : 0;

            let address, phone;
            if (deliveryMethod === "store-pickup") {
                address = "Store Pickup";
                phone = $('input[name="pickup-phone"]').val().trim();
            } else {
                address = $('input[name="address"]').val().trim();
                phone = $('input[name="delivery-phone"]').val().trim();
            }

            // Collect payment details
            const paymentDetails = {
                orderId: orderId,
                customerId: <?php echo json_encode($cus_id); ?>,
                deliveryCharge: deliveryCharge,
                subtotal: calculateOverallTotal(),
                grandTotal: parseFloat(calculateOverallTotal()) + deliveryCharge,
                address: address,
                phone: phone,
                items: $(".product-card").map(function () {
                    const $card = $(this);
                    const $colorCircle = $card.find(".color-circle");
                    return {
                        itemId: $card.data('product-id'),
                        name: $card.find("h2").text(),
                        colorId: $colorCircle.length ? $colorCircle.data('color-id') : null,
                        quantity: parseInt($card.find(".qty-input").val()),
                        price: parseFloat($card.find(".qty-input").data("price"))
                    };
                }).get()
            };

            // Store payment details in localStorage before redirect
            localStorage.setItem('orderDetails', JSON.stringify(paymentDetails));

            // Redirect to payment confirmation
            window.location.href = "payment-confirm.php?order_id=" + orderId;
        };

        payhere.onDismissed = function onDismissed() {
            console.log("Payment dismissed");
            window.location.href = "checkout.php";
        };

        payhere.onError = function onError(error) {
            console.log("Error: " + error);
            alert("Payment error: " + error);
        };

        // Validate current step
        function validateStep(stepIndex) {
            if (stepIndex === 1) {
                const deliveryMethod = $('input[name="delivery"]:checked').val();
                if (deliveryMethod === "delivery") {
                    const address = $('input[name="address"]').val().trim();
                    const phone = $('input[name="delivery-phone"]').val().trim();
                    return address && phone;
                } else if (deliveryMethod === "store-pickup") {
                    const phone = $('input[name="pickup-phone"]').val().trim();
                    return phone;
                }
            }
            return true;
        }

        $stepContents.hide(); 
        $stepContents.eq(0).addClass("active").show(); // Show the first section
        updateNextButtonState(0);
    });

</script>


</body>

</html>
<?php include 'footer.php'; ?>