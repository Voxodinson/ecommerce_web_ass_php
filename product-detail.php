<!DOCTYPE HTML>
<html>
<?php include('link_import.php')?>
<body>	
	<div class="colorlib-loader"></div>
	<div id="page">
		<?php include('includes/navigation.php')?>
		<div class="breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col">
						<p class="bread"><span><a href="index.php">Home</a></span> / <span>Product Details</span></p>
					</div>
				</div>
			</div>
		</div>
		<div class="colorlib-product">
			<div class="container">
				<div class="row row-pb-lg product-detail-wrap">
					<div class="col-sm-8">
						<div class="owl-carousel">
							<div class="item">
								<div class="product-entry border">
									<a href="#" class="prod-img">
										<img src="images/item-1.jpg" class="img-fluid" alt="Free html5 bootstrap 4 template">
									</a>
								</div>
							</div>
							<div class="item">
								<div class="product-entry border">
									<a href="#" class="prod-img">
										<img src="images/item-2.jpg" class="img-fluid" alt="Free html5 bootstrap 4 template">
									</a>
								</div>
							</div>
							<div class="item">
								<div class="product-entry border">
									<a href="#" class="prod-img">
										<img src="images/item-3.jpg" class="img-fluid" alt="Free html5 bootstrap 4 template">
									</a>
								</div>
							</div>
							<div class="item">
								<div class="product-entry border">
									<a href="#" class="prod-img">
										<img src="images/item-4.jpg" class="img-fluid" alt="Free html5 bootstrap 4 template">
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="product-desc">
							<h3>Women's Boots Shoes Maca</h3>
							<p class="price">
								<span>$68.00</span> 
								<span class="rate">
									<i class="icon-star-full"></i>
									<i class="icon-star-full"></i>
									<i class="icon-star-full"></i>
									<i class="icon-star-full"></i>
									<i class="icon-star-half"></i>
									(74 Rating)
								</span>
							</p>
							<p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>
							<div class="size-wrap">
								<div class="block-26 mb-2">
									<h4>Size</h4>
				               <ul>
				                  <li><a href="#">7</a></li>
				                  <li><a href="#">7.5</a></li>
				                  <li><a href="#">8</a></li>
				                  <li><a href="#">8.5</a></li>
				                  <li><a href="#">9</a></li>
				                  <li><a href="#">9.5</a></li>
				                  <li><a href="#">10</a></li>
				                  <li><a href="#">10.5</a></li>
				                  <li><a href="#">11</a></li>
				                  <li><a href="#">11.5</a></li>
				                  <li><a href="#">12</a></li>
				                  <li><a href="#">12.5</a></li>
				                  <li><a href="#">13</a></li>
				                  <li><a href="#">13.5</a></li>
				                  <li><a href="#">14</a></li>
				               </ul>
				            </div>
				            <div class="block-26 mb-4">
									<h4>Width</h4>
				               <ul>
				                  <li><a href="#">M</a></li>
				                  <li><a href="#">W</a></li>
				               </ul>
				            </div>
							</div>
                     <div class="input-group mb-4">
                     	<span class="input-group-btn">
                        	<button type="button" class="quantity-left-minus btn"  data-type="minus" data-field="">
                           <i class="icon-minus2"></i>
                        	</button>
                    		</span>
                     	<input type="text" id="quantity" name="quantity" class="form-control input-number" value="1" min="1" max="100">
                     	<span class="input-group-btn ml-1">
                        	<button type="button" class="quantity-right-plus btn" data-type="plus" data-field="">
                             <i class="icon-plus2"></i>
                         </button>
                     	</span>
                  	</div>
                  	<div class="row">
	                  	<div class="col-sm-12 text-center">
									<p class="addtocart"><a href="cart.php" class="btn btn-primary btn-addtocart"><i class="icon-shopping-cart"></i> Add to Cart</a></p>
								</div>
							</div>
						</div>åß
					</div>
				</div> 
			</div>
		</div>
		<?php include('includes/footer.php')?>
		<?php include('script_import.php')?>
		<script>
			$(document).ready(function(){

			var quantitiy=0;
			$('.quantity-right-plus').click(function(e){
					
					e.preventDefault();
					var quantity = parseInt($('#quantity').val());
						$('#quantity').val(quantity + 1);
				});

				$('.quantity-left-minus').click(function(e){
					e.preventDefault();
					var quantity = parseInt($('#quantity').val());
						if(quantity>0){
						$('#quantity').val(quantity - 1);
						}
				});
				
			});
		</script>
	</body>
</html>

