<!DOCTYPE HTML>
<html>
<?php include('link_import.php'); ?>

<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $quantity;  
            break;
        }
    }

    header('Location: cart.php');
    exit();
}

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['product_id'] == $product_id) {
            if ($item['quantity'] > 1) {
                $item['quantity']--;
            } else {
                unset($_SESSION['cart'][$key]); 
            }
            break; 
        }
    }

    $_SESSION['cart'] = array_values($_SESSION['cart']);  
    header('Location: cart.php');  
    exit();
}

$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
<body>
    <div class="colorlib-loader"></div>
    <div id="page">
        <?php include('includes/navigation.php') ?>

        <div class="breadcrumbs">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <p class="bread"><span><a href="index.php">Home</a></span> / <span>Shopping Cart</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="colorlib-product">
            <div class="container">
                <div class="row row-pb-lg">
                    <div class="col-md-12">
                        <div class="product-name d-flex">
                            <div class="one-forth text-left px-4">
                                <span>Product Details</span>
                            </div>
                            <div class="one-eight text-center">
                                <span>Price</span>
                            </div>
                            <div class="one-eight text-center">
                                <span>Quantity</span>
                            </div>
                            <div class="one-eight text-center">
                                <span>Total</span>
                            </div>
                            <div class="one-eight text-center px-4">
                                <span>Remove</span>
                            </div>
                        </div>
                        
                        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                            <?php foreach ($_SESSION['cart'] as $item): ?>
                                <div class="product-cart d-flex">
									<div style="width: 53%" class="display-tc d-flex gap-3">
										<img class="product-img" src="<?php echo 'http://localhost/school_ass/ecom_web_admin/uploads/images/' . htmlspecialchars($item['image']); ?>"  alt="Product Image">
										<div class="d-flex flex-column ml-3">
											<h5 class=""><?php echo htmlspecialchars($item['name']); ?></h3>
											<p ><strong>Size:</strong> <?php echo htmlspecialchars($item['size']); ?></p> 
										</div>
									</div>
                                    <div class="one-eight text-center">
                                        <div class="display-tc">
                                            <span class="price">$<?php echo number_format($item['price'], 2); ?></span>
                                        </div>
                                    </div>
                                    <div class="one-eight text-center">
                                        <div class="display-tc">
                                            <form action="cart.php" method="POST">
                                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="form-control mt-2 input-number text-center">
                                                <button type="submit" class="btn btn-primary mt-2">Update</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="one-eight text-center">
                                        <div class="display-tc">
                                            <span class="price">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                                        </div>
                                    </div>
                                    <div class="one-eight text-center">
                                        <div class="display-tc">
                                            <a href="cart.php?id=<?php echo $item['product_id']; ?>" class="closed"></a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-center w-100">Your cart is empty.</p>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>

        <div class="cart-total">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <span class="total">Total: $<?php echo number_format($total, 2); ?></span>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </div>

        <?php include('includes/footer.php') ?>
        <?php include('script_import.php') ?>
</body>
</html>
