<!DOCTYPE HTML>
<html lang="en">
<head>
    <?php include('link_import.php') ?>
</head>
<body>	
	<div class="colorlib-loader"></div>
	<div id="page">	
		<?php include('includes/navigation.php') ?>
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
							<div class="process text-center active">
								<p><span>01</span></p>
								<h3>Shopping Cart</h3>
							</div>
							<div class="process text-center active">
								<p><span>02</span></p>
								<h3>Checkout</h3>
							</div>
							<div class="process text-center">
								<p><span>03</span></p>
								<h3>Order Complete</h3>
							</div>
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
										<label for="country">Select Country</label>
										<div class="form-field">
											<i class="icon icon-arrow-down3"></i>
											<select name="people" id="people" class="form-control">
												<option value="#">Select country</option>
												<option value="Alaska">Alaska</option>
												<option value="China">China</option>
												<option value="Japan">Japan</option>
												<option value="Korea">Korea</option>
												<option value="Philippines">Philippines</option>
											</select>
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="fname">First Name</label>
										<input type="text" name="fname" id="fname" class="form-control" placeholder="Your firstname" required>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="lname">Last Name</label>
										<input type="text" name="lname" id="lname" class="form-control" placeholder="Your lastname" required>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label for="address">Address</label>
										<input type="text" name="address" id="address" class="form-control" placeholder="Enter Your Address" required>
									</div>
									<div class="form-group">
										<input type="text" name="address2" id="address2" class="form-control" placeholder="Second Address">
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label for="towncity">Town/City</label>
										<input type="text" name="towncity" id="towncity" class="form-control" placeholder="Town or City" required>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="stateprovince">State/Province</label>
										<input type="text" name="stateprovince" id="stateprovince" class="form-control" placeholder="State Province">
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="zippostalcode">Zip/Postal Code</label>
										<input type="text" name="zippostalcode" id="zippostalcode" class="form-control" placeholder="Zip / Postal" required>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="email">E-mail Address</label>
										<input type="email" name="email" id="email" class="form-control" placeholder="E-mail Address" required>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="phone">Phone Number</label>
										<input type="tel" name="phone" id="phone" class="form-control" placeholder="Phone Number" required>
									</div>
								</div>
							</div>
					</div>

					<div class="col-lg-4">
						<div class="row">
							<div class="col-md-12">
								<div class="cart-detail">
									<h2>Cart Total</h2>
										<ul id="cart-items">
										<li>
											<span>Subtotal</span> <span id="subtotal">$0.00</span>
											<ul id="cart-details">
												<li class="cart-item" data-price="99.00"><span>1 x Product Name</span> <span class="item-price">$99.00</span></li>
												<li class="cart-item" data-price="78.00"><span>1 x Product Name</span> <span class="item-price">$78.00</span></li>
											</ul>
										</li>
										<li><span>Order Total</span> <span id="order-total">$0.00</span></li>
									</ul>
								</div>
						   </div>

						   <div class="col-md-12">
								<div class="cart-detail">
									<h2>Payment Method</h2>
									<div class="form-group">
										<div class="col-md-12">
											<div class="radio">
											   <label><input type="radio" name="optradio" value="ABA Bank"> ABA Bank</label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12 text-center">
								<p><button type="submit" class="btn btn-primary">Place an order</button></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		</form>
	</div>
	</div>

	<?php include('includes/footer.php') ?>
	<?php include('script_import.php') ?>
	<?php
    // Include your database connection
    include_once('services/config.php');

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $country = $_POST['people'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $address = $_POST['address'];
        $address2 = $_POST['address2'];
        $towncity = $_POST['towncity'];
        $stateprovince = $_POST['stateprovince'];
        $zippostalcode = $_POST['zippostalcode'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        // Validate inputs (optional but recommended)
        if (empty($fname) || empty($lname) || empty($address) || empty($towncity) || empty($zippostalcode)) {
            die("Please fill all required fields.");
        }

        // Prepare your SQL statement with placeholders
        $query = "INSERT INTO checkout_info 
                  (country, first_name, last_name, address, address2, town_city, state_province, zip_postal_code, email, phone) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepare the statement
        if ($stmt = mysqli_prepare($conn, $query)) {
            // Bind the parameters
            mysqli_stmt_bind_param($stmt, "ssssssssss", $country, $fname, $lname, $address, $address2, $towncity, $stateprovince, $zippostalcode, $email, $phone);

            // Execute the query
            if (mysqli_stmt_execute($stmt)) {
                echo "Checkout information saved successfully!";
                // Optionally, redirect to a thank you page or order summary page
                header("Location: thank_you.php");
            } else {
                echo "Error executing query: " . mysqli_stmt_error($stmt);
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        } else {
            echo "Error preparing statement: " . mysqli_error($conn);
        }

        // Close the connection
        mysqli_close($conn);
    }
?>

	<script>
		// Function to update cart totals
		function updateCartTotals() {
			let subtotal = 0;

			// Get all cart item prices
			const cartItems = document.querySelectorAll('.cart-item');
			
			cartItems.forEach(item => {
				const price = parseFloat(item.getAttribute('data-price')); // Get the price from data-price attribute
				subtotal += price; // Add to subtotal
			});

			// Update subtotal
			document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;

			// Set the order total (same as subtotal for now)
			document.getElementById('order-total').textContent = `$${subtotal.toFixed(2)}`;
		}

		// Call the function to update the cart totals
		updateCartTotals();
	</script>

</body>
</html>
