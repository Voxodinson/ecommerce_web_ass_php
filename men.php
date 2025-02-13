<!DOCTYPE HTML>
<html>
<?php include('link_import.php')?>
<?php
	include_once('services/config.php');
	$query = "SELECT * FROM products_tb";
	$result = mysqli_query($con, $query);
?>
<body>	
	<div class="colorlib-loader"></div>
	
	<div id="page">
		<?php include('includes/navigation.php')?>
		<div class="breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col">
						<p class="bread"><span><a href="index.php">Home</a></span> / <span>Men</span></p>
					</div>
				</div>
			</div>
		</div>

		<div class="breadcrumbs-two">
			<div class="container">
				<div class="row">
					<div class="col">
						<div class="breadcrumbs-img" style="background-image: url(images/cover-img-1.jpg);">
							<h2>Men's</h2>
						</div>
						<div class="menu text-center">
							<p><a href="#">New Arrivals</a> <a href="#">Best Sellers</a> <a href="#">Extended Widths</a> <a href="#">Sale</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="colorlib-featured">
			<div class="container">
				<div class="row">
					<div class="col-sm-4 text-center">
						<div class="featured">
							<div class="featured-img featured-img-2" style="background-image: url(images/men.jpg);">
								<h2>Casuals</h2>
								<p><a href="#" class="btn btn-primary btn-lg">Shop now</a></p>
							</div>
						</div>
					</div>
					<div class="col-sm-4 text-center">
						<div class="featured">
							<div class="featured-img featured-img-2" style="background-image: url(images/women.jpg);">
								<h2>Dress</h2>
								<p><a href="#" class="btn btn-primary btn-lg">Shop now</a></p>
							</div>
						</div>
					</div>
					<div class="col-sm-4 text-center">
						<div class="featured">
							<div class="featured-img featured-img-2" style="background-image: url(images/item-11.jpg);">
								<h2>Sports</h2>
								<p><a href="#" class="btn btn-primary btn-lg">Shop now</a></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="colorlib-product">
			<div class="container">
				<div class="row">
					<div class="col-sm-8 offset-sm-2 text-center colorlib-heading colorlib-heading-sm">
						<h2>View All Products</h2>
					</div>
				</div>
				<div class="row row-pb-md">
					<?php
						$bestSaleCount = 0;

						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								if ($row['product_for'] !== 'men') {
									continue;
								}

								$images = json_decode($row['images'], true);
								$firstImage = $images[0];

						?>
								<div class="col-md-6 col-lg-4 col-xl-3 mb-4">
									<div class="card h-100 border shadow-sm">
										<a href="#" class="prod-img">
											<img src="<?php echo htmlspecialchars($firstImage); ?>" class="card-img-top img-fluid" alt="<?php echo htmlspecialchars($row['name']); ?>">
										</a>
										<div class="card-body text-center">
											<h5 class="card-title">
												<a href="#" class="text-decoration-none text-dark"><?php echo htmlspecialchars($row['name']); ?></a>
											</h5>
											<span class="price text-danger fw-bosld">$<?php echo number_format($row['price'], 2); ?></span>
										</div>
									</div>
								</div>
						<?php
							}
						} else {
							echo "<p class='text-center'>No products found.</p>";
						}
					?>
				</div>
				<div class="row">
					<div class="col-md-12 text-center">
						<div class="block-27">
		               <ul>
			               <li><a href="#"><i class="ion-ios-arrow-back"></i></a></li>
		                  <li class="active"><span>1</span></li>
		                  <li><a href="#">2</a></li>
		                  <li><a href="#">3</a></li>
		                  <li><a href="#">4</a></li>
		                  <li><a href="#">5</a></li>
		                  <li><a href="#"><i class="ion-ios-arrow-forward"></i></a></li>
		               </ul>
		            </div>
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

