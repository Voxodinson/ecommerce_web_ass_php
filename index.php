<!DOCTYPE HTML>
<html>
<?php include('link_import.php')?>
<?php
	session_start();
    include_once('services/config.php');

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SELECT * FROM products_tb";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
?>
<body>

	<div class="colorlib-loader"></div>
	<?php include('includes/navigation.php')?>
	<div id="page">
		<aside id="colorlib-hero">
			<div class="flexslider">
				<ul class="slides">
			   	<li style="background-image: url(images/img_bg_1.jpg);">
			   		<div class="overlay"></div>
			   		<div class="container-fluid">
			   			<div class="row">
				   			<div class="col-sm-6 offset-sm-3 text-center slider-text">
				   				<div class="slider-text-inner">
				   					<div class="desc">
					   					<h1 class="head-1">Men's</h1>
					   					<h2 class="head-2">Shoes</h2>
					   					<h2 class="head-3">Collection</h2>
					   					<p class="category"><span>New trending shoes</span></p>
					   					<p><a href="shop.php" class="btn btn-primary">Shop Collection</a></p>
				   					</div>
				   				</div>
				   			</div>
				   		</div>
			   		</div>
			   	</li>
			   	<li style="background-image: url(images/img_bg_2.jpg);">
			   		<div class="overlay"></div>
			   		<div class="container-fluid">
			   			<div class="row">
				   			<div class="col-sm-6 offset-sm-3 text-center slider-text">
				   				<div class="slider-text-inner">
				   					<div class="desc">
					   					<h1 class="head-1">Huge</h1>
					   					<h2 class="head-2">Sale</h2>
					   					<h2 class="head-3"><strong class="font-weight-bold">50%</strong> Off</h2>
					   					<p class="category"><span>Big sale sandals</span></p>
					   					<p><a href="shop.php" class="btn btn-primary">Shop Collection</a></p>
				   					</div>
				   				</div>
				   			</div>
				   		</div>
			   		</div>
			   	</li>
			   	<li style="background-image: url(images/img_bg_3.jpg);">
			   		<div class="overlay"></div>
			   		<div class="container-fluid">
			   			<div class="row">
				   			<div class="col-sm-6 offset-sm-3 text-center slider-text">
				   				<div class="slider-text-inner">
				   					<div class="desc">
					   					<h1 class="head-1">New</h1>
					   					<h2 class="head-2">Arrival</h2>
					   					<h2 class="head-3">up to <strong class="font-weight-bold">30%</strong> off</h2>
					   					<p class="category"><span>New stylish shoes for men</span></p>
					   					<p><a href="shop.php" class="btn btn-primary">Shop Collection</a></p>
				   					</div>
				   				</div>
				   			</div>
				   		</div>
			   		</div>
			   	</li>
			  	</ul>
		  	</div>
		</aside>
		<div class="colorlib-intro">
			<div class="container">
				<div class="row">
					<div class="col-sm-12 text-center">
						<h2 class="intro">It started with a simple idea: Create quality, well-designed products that I wanted myself.</h2>
					</div>
				</div>
			</div>
		</div>
		<div class="colorlib-product">
			<div class="container-fluid">
				<div class="row">
					<div class="w-100 text-center">
						<div class="featured">
							<a href="shop.php" class="featured-img" style="background-image: url(images/men.jpg);"></a>
							<div class="desc">
								<h2><a href="shop.php">Shop Collection</a></h2>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php include('services/config.php')?>
		<div class="colorlib-product">
			<div class="container">
				<div class="row">
					<div class="col-sm-8 offset-sm-2 text-center colorlib-heading">
						<h2>Best Sellers</h2>
					</div>
				</div>
				<div class="row row-pb-md">
					<?php
						$bestSaleCount = 0;

						if (!empty($result)) {
							foreach ($result as $row) {
								if ($row['status'] !== 'best sale') {
									continue;
								}

								$images = json_decode($row['images'], true);
					?>
								<div class="col-md-6 col-lg-4 col-xl-3 mb-4">
									<div class="card h-100 border shadow-sm">
										<a href="product-detail.php?id=<?php echo $row['id']; ?>" class="prod-img">
											<img 
												src="<?php echo 'http://localhost/school_ass/ecom_web_admin/uploads/images/' . htmlspecialchars($images[0]); ?>" 
												class="card-img-top img-fluid" 
												alt="<?php echo htmlspecialchars($row['name']); ?>"
												style=" height: 250px; object-fit: cover;">
										</a>
										<div class="card-body text-center">
											<h5 class="card-title">
												<a href="#" class="text-decoration-none text-dark"><?php echo htmlspecialchars($row['name']); ?></a>
											</h5>
											<span class="price text-danger fw-bold">$<?php echo number_format($row['price'], 2); ?></span>
										</div>
									</div>
								</div>
					<?php
								$bestSaleCount++;
								if ($bestSaleCount >= 4) {
									break;
								}
							}
						} else {
							echo "<p class='text-center'>No products found.</p>";
						}
					?>

				</div>
				<div class="row">
					<div class="col-md-12 text-center">
						<p><a href="shop.php" class="btn btn-primary btn-lg">Shop All Products</a></p>
					</div>
				</div>
			</div>
		</div>

		<div class="colorlib-partner">
			<div class="container">
				<div class="row">
					<div class="col-sm-8 offset-sm-2 text-center colorlib-heading colorlib-heading-sm">
						<h2>Trusted Partners</h2>
					</div>
				</div>
				<div class="row">
					<div class="col partner-col text-center">
						<img src="images/brand-1.jpg" class="img-fluid" alt="Free html4 bootstrap 4 template">
					</div>
					<div class="col partner-col text-center">
						<img src="images/brand-2.jpg" class="img-fluid" alt="Free html4 bootstrap 4 template">
					</div>
					<div class="col partner-col text-center">
						<img src="images/brand-3.jpg" class="img-fluid" alt="Free html4 bootstrap 4 template">
					</div>
					<div class="col partner-col text-center">
						<img src="images/brand-4.jpg" class="img-fluid" alt="Free html4 bootstrap 4 template">
					</div>
					<div class="col partner-col text-center">
						<img src="images/brand-5.jpg" class="img-fluid" alt="Free html4 bootstrap 4 template">
					</div>
				</div>
			</div>
		</div>
		<?php include('includes/footer.php')?>
		<?php include('script_import.php')?>
	</body>
</html>

