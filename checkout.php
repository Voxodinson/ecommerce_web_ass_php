<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please LOGIN to proceed with your order.'); window.location.href='login.php';</script>";
    exit();
}

$host = "localhost";
$username = "root";
$password = "";
$database = "ecom_web_assignment";

$con = new mysqli($host, $username, $password, $database);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $country = mysqli_real_escape_string($con, $_POST['people']);
    $fname = mysqli_real_escape_string($con, $_POST['fname']);
    $lname = mysqli_real_escape_string($con, $_POST['lname']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $address2 = mysqli_real_escape_string($con, $_POST['address2'] ?? '');
    $towncity = mysqli_real_escape_string($con, $_POST['towncity']);
    $stateprovince = mysqli_real_escape_string($con, $_POST['stateprovince'] ?? '');
    $zippostalcode = mysqli_real_escape_string($con, $_POST['zippostalcode']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $payment_method = mysqli_real_escape_string($con, $_POST['optradio']);

    $sql = "INSERT INTO checkout_info (user_id, country, fname, lname, address, address2, towncity, stateprovince, zippostalcode, email, phone, payment_method) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("isssssssssss", $user_id, $country, $fname, $lname, $address, $address2, $towncity, $stateprovince, $zippostalcode, $email, $phone, $payment_method);

    if ($stmt->execute()) {
        $checkout_info_id = $stmt->insert_id;
        
        $cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

        if (!empty($cartItems)) {
            $order_date = date('Y-m-d H:i:s');
            foreach ($cartItems as $item) {
                $item_id = $item['product_id'];
                $product_name = mysqli_real_escape_string($con, $item['name']);
                $price = $item['price'];
                $quantity = $item['quantity'];
                $subtotal = $price * $quantity;
                $image = mysqli_real_escape_string($con, $item['image']);
                
                
                $order_sql = "INSERT INTO order_history (checkout_info_id, item_id, product_name, price, quantity, subtotal, order_date, image) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

                $order_stmt = $con->prepare($order_sql);

                if ($order_stmt === false) {
                    echo "Error preparing statement: " . $con->error;
                    exit();
                }

                $order_stmt->bind_param("iisdddss", $checkout_info_id, $item_id, $product_name, $price, $quantity, $subtotal, $order_date, $image);

                if ($order_stmt->execute()) {
                    echo "Inserted order history for product: $product_name\n";
                } else {
                    echo "Error executing order insert: " . $order_stmt->error . "\n";
                }
                $order_stmt->close();
            }
            
        } else {
            echo "<script>alert('Your cart is empty. Please add items to the cart before checking out.'); window.location.href='cart.php';</script>";
            exit();
        }
        $_SESSION['cart'] = [];
        echo "<script>window.location.href='order-complete.php'</script>";
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
                                // Ensure cart exists
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
