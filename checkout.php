<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "ecom_web_assignment";

$con = new mysqli($host, $username, $password, $database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $country = $_POST['people'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $address = $_POST['address'];
    $address2 = $_POST['address2'] ?? '';
    $towncity = $_POST['towncity'];
    $stateprovince = $_POST['stateprovince'] ?? '';
    $zippostalcode = $_POST['zippostalcode'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $payment_method = $_POST['optradio'];

    // Step 1: Insert checkout information into the checkout_info table
    $sql = "INSERT INTO checkout_info (country, fname, lname, address, address2, towncity, stateprovince, zippostalcode, email, phone, payment_method) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssssssss", $country, $fname, $lname, $address, $address2, $towncity, $stateprovince, $zippostalcode, $email, $phone, $payment_method);
    
    if ($stmt->execute()) {
        $checkout_info_id = $stmt->insert_id; // Get the inserted checkout info ID

        // Step 2: Insert cart items into the order_history table
        session_start(); // Start the session
        $cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

        if (!empty($cartItems)) {
            $order_date = date('Y-m-d H:i:s'); // Get current timestamp
            foreach ($cartItems as $item) {
                $product_name = $item['name'];
                $price = $item['price'];
                $quantity = $item['quantity'];
                $subtotal = $price * $quantity;

                // Insert each cart item into the order_history table
                $order_sql = "INSERT INTO order_history (checkout_info_id, product_name, price, quantity, subtotal, order_date) 
                              VALUES (?, ?, ?, ?, ?, ?)";
                
                $order_stmt = $con->prepare($order_sql);
                $order_stmt->bind_param("issdss", $checkout_info_id, $product_name, $price, $quantity, $subtotal, $order_date);
                $order_stmt->execute();
                $order_stmt->close();
            }
        }

        echo "<script>alert('Order placed successfully!'); window.location.href='order-complete.php';</script>";
    } else {
        echo "<script>alert('Error placing order. Please try again.');</script>";
    }
    
    $stmt->close();
}

$con->close();
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <?php include('link_import.php'); ?>
</head>
<body>
    <div class="colorlib-loader"></div>
    <div id="page">    
        <?php include('includes/navigation.php'); ?>
        <div class="breadcrumbs">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <p class="bread"><span><a href="index.php">Home</a></span> / <span>Checkout</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="colorlib-product">
            <div class="container">
                <div class="row row-pb-lg">
                    <div class="col-sm-10 offset-md-1">
                        <div class="process-wrap">
                            <div class="process text-center active"><p><span>01</span></p><h3>Shopping Cart</h3></div>
                            <div class="process text-center active"><p><span>02</span></p><h3>Checkout</h3></div>
                            <div class="process text-center"><p><span>03</span></p><h3>Order Complete</h3></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <form method="post" class="colorlib-form">
                            <h2>Billing Details</h2>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="people">Select Country</label>
                                        <select name="people" id="people" class="form-control" required>
                                            <option value="">Select country</option>
                                            <option value="Alaska">Alaska</option>
                                            <option value="China">China</option>
                                            <option value="Japan">Japan</option>
                                            <option value="Korea">Korea</option>
                                            <option value="Philippines">Philippines</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group"><label for="fname">First Name</label><input type="text" name="fname" id="fname" class="form-control" required></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group"><label for="lname">Last Name</label><input type="text" name="lname" id="lname" class="form-control" required></div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group"><label for="address">Address</label><input type="text" name="address" id="address" class="form-control" required></div>
                                    <div class="form-group"><input type="text" name="address2" id="address2" class="form-control" placeholder="Second Address"></div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group"><label for="towncity">Town/City</label><input type="text" name="towncity" id="towncity" class="form-control" required></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group"><label for="stateprovince">State/Province</label><input type="text" name="stateprovince" id="stateprovince" class="form-control"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group"><label for="zippostalcode">Zip/Postal Code</label><input type="text" name="zippostalcode" id="zippostalcode" class="form-control" required></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group"><label for="email">E-mail Address</label><input type="email" name="email" id="email" class="form-control" required></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group"><label for="phone">Phone Number</label><input type="tel" name="phone" id="phone" class="form-control" required></div>
                                </div>
                            </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="cart-detail">
                            <h2>Cart Total</h2>
                            <?php
								session_start(); // Start the session

								// Check if cart exists
								$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

								$subtotal = 0;
								?>

								<ul id="cart-items">
									<?php if (!empty($cartItems)): ?>
										<?php foreach ($cartItems as $item): ?>
											<?php $subtotal += $item['price'] * $item['quantity']; ?>
											<li>
												<span><?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['quantity']; ?>)</span> 
												<span>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
											</li>
										<?php endforeach; ?>
									<?php else: ?>
										<li><span>Your cart is empty</span></li>
									<?php endif; ?>
								</ul>

								<ul id="cart-items" class="mt-3">
									<li><span>Subtotal</span> <span id="subtotal">$<?php echo number_format($subtotal, 2); ?></span></li>
									<li><span>Order Total</span> <span id="order-total">$<?php echo number_format($subtotal, 2); ?></span></li>
								</ul>
                        </div>
                        <div class="cart-detail">
                            <h2>Payment Method</h2>
                            <div class="form-group">
                                <label><input type="radio" name="optradio" value="ABA Bank" required> ABA Bank</label>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Place an order</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
    <?php include('includes/footer.php'); ?>
    <?php include('script_import.php'); ?>
</body>
</html>
