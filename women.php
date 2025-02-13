<!DOCTYPE HTML>
<html>
<?php include('link_import.php')?>
<?php
include_once('services/config.php');
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$totalQuery = "SELECT COUNT(*) as total FROM products_tb";
$totalResult = mysqli_query($con, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalProducts = $totalRow['total'];
$totalPages = ceil($totalProducts / $limit);

$query = "SELECT * FROM products_tb LIMIT $limit OFFSET $offset";
$result = mysqli_query($con, $query);
?>
<body>	
	<div class="colorlib-loader"></div>
	<div id="page" class=" justify-content-center">
		<?php include('includes/navigation.php')?>
		<div class="breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col">
						<p class="bread"><span><a href="index.php">Home</a></span> / <span>Women</span></p>
					</div>
				</div>
			</div>
		</div>

		<div class="breadcrumbs-two">
			<div class="container">
				<div class="row">
					<div class="col">
						<div class="breadcrumbs-img" style="background-image: url(images/cover-img-1.jpg);">
							<h2>Women's</h2>
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
							<div class="featured-img featured-img-2" style="background-image: url(images/img_bg_2.jpg);">
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
		<div class="colorlib-featured">
			<div class="container">
				<div class="row">
					<div class="col-sm-8 offset-sm-2 text-center colorlib-heading colorlib-heading-sm">
						<h2>View All Products</h2>
					</div>
				</div>
				<div class="row">
					<div class="row row-pb-md" id="products">
						<?php
						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								$images = json_decode($row['images'], true);
								$firstImage = $images[0];
						?>
								<div class="col-md-3 col-lg-3​​ col-xl-3 mb-5">
									<div class="card h-100 border shadow-sm">
										<a href="#" class="prod-img">
											<img src="<?php echo htmlspecialchars($firstImage); ?>" class="card-img-top img-fluid" alt="<?php echo htmlspecialchars($row['name']); ?>">
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
							}
						} else {
							echo "<p class='text-center w-100'>No products found.</p>";
						}
						?>
					</div>
					<div class="row w-100">
						<div class="col-md-12 text-center">
							<div class="block-27">
								<ul>
									<?php if ($page > 1): ?>
										<li><a href="?page=<?php echo $page - 1; ?>"><i class="ion-ios-arrow-back"></i></a></li>
									<?php endif; ?>

									<?php for ($i = 1; $i <= $totalPages; $i++): ?>
										<li class="<?php echo ($i == $page) ? 'active' : ''; ?>">
											<a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
										</li>
									<?php endfor; ?>

									<?php if ($page < $totalPages): ?>
										<li><a href="?page=<?php echo $page + 1; ?>"><i class="ion-ios-arrow-forward"></i></a></li>
									<?php endif; ?>
								</ul>
							</div>
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

