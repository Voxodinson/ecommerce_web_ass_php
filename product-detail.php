<?php 
include('link_import.php');
include_once('services/config.php');
session_start();

$product_id = isset($_GET['id']) ? $_GET['id'] : null;

// Initialize variables for product data
$product_name = '';
$product_price = '';
$product_description = '';
$product_images = [];
$product_sizes = [];
$product_widths = [];

// Handle cart logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $size = $_POST['size'];

    // Check if size is selected
    if (empty($size)) {
        echo "<p>Please select a size before adding to cart.</p>";
        exit();
    }

    // Fetch product details from the database
    $product_details = getProductDetails($product_id);  // Function to get product details by ID
    
    if (!$product_details) {
        // Product not found
        echo "<p>Product not found.</p>";
        exit();
    }
    
    $product_images = json_decode($product_details['images'], true);
    $product_image = isset($product_images[0]) ? $product_images[0] : ''; 
    $product_name = $product_details['name'];   
    $product_price = $product_details['price']; 
    $product_description = $product_details['details'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $exists = false;
    foreach ($_SESSION['cart'] as &$cart_item) {
        if ($cart_item['product_id'] == $product_id && $cart_item['size'] == $size) {
            $cart_item['quantity'] += $quantity;  // Update quantity if product exists
            $exists = true;
            break;
        }
    }

    if (!$exists) {
        // Add new product to the cart with the first image and additional details
        $cart_item = [
            'product_id' => $product_id,
            'quantity' => $quantity,
            'size' => $size,
            'name' => $product_name,
            'price' => $product_price,
            'image' => $product_image,
            'details' => $product_description// Store only the first image
        ];
        $_SESSION['cart'][] = $cart_item;
    }

    header('Location: ' . $_SERVER['REQUEST_URI']);  // Reload page to avoid resubmission
    exit();
}

if ($product_id) {
    $product_id = mysqli_real_escape_string($con, $product_id);
    $query = "SELECT * FROM products_tb WHERE id = '$product_id'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Decode images and sizes from JSON
        $product_images = json_decode($row['images'], true);
        $product_name = $row['name'];
        $product_price = $row['price'];
        $product_description = $row['details'];
        $product_sizes = json_decode($row['size'], true);
    } else {
        echo "<p>Product not found.</p>";
    }
} else {
    echo "<p>No product ID provided.</p>";
}

// Function to fetch product details by ID
function getProductDetails($product_id) {
    global $con;
    $query = "SELECT * FROM products_tb WHERE id = '$product_id'";
    $result = mysqli_query($con, $query);
    
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <?php include('link_import.php'); ?>
    <style>
        .size-item.active {
            background-color: #17a2b8;
            font-weight: bold;
        }
    </style>
</head>
<body>    
    <div class="colorlib-loader"></div>
    <div id="page">
        <?php include('includes/navigation.php'); ?>
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
                            <?php foreach ($product_images as $image): ?>
                                <div class="item">
                                    <div class="product-entry border">
                                        <a href="#" class="prod-img">
                                            <img src="<?php echo htmlspecialchars($image); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product_name); ?>">
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="product-desc">
                            <h3><?php echo htmlspecialchars($product_name); ?></h3>
                            <p class="price">
                                <span>$<?php echo number_format($product_price, 2); ?></span> 
                                <span class="rate">
                                    <?php 
                                    $rating = $row['rating'];  
                                    $fullStars = floor($rating);
                                    $halfStar = ($rating - $fullStars >= 0.5) ? 1 : 0;
                                    $emptyStars = 5 - $fullStars - $halfStar;
                                    for ($i = 0; $i < $fullStars; $i++) {
                                        echo '<i class="icon-star-full"></i>';
                                    }
                                    if ($halfStar) {
                                        echo '<i class="icon-star-half"></i>';
                                    }
                                    for ($i = 0; $i < $emptyStars; $i++) {
                                        echo '<i class="icon-star-empty"></i>';
                                    }
                                    ?>
                                    (<?php echo $rating; ?> Rating)
                                </span>
                            </p>

                            <p><?php echo htmlspecialchars($product_description); ?></p>
                            
                            <div class="size-wrap">
                                <div class="block-26 mb-2">
                                    <h4>Size</h4>
                                    <ul>
                                        <?php foreach ($product_sizes as $size): ?>
                                            <li><a class="size-item" href="#"><?php echo htmlspecialchars($size); ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>

                            <div class="input-group mb-4">
                                <span class="input-group-btn">
                                    <button type="button" class="quantity-left-minus btn" data-type="minus" data-field="">
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

                            <form method="POST" action="">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
                                <input type="hidden" name="quantity" id="quantity_input" value="1">
                                <input type="hidden" name="size" id="size_input" value="">

                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <p class="addtocart">
                                            <button type="submit" class="btn btn-primary btn-addtocart">
                                                <i class="icon-shopping-cart bg-[#17a2b8]"></i> Add to Cart
                                            </button>
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        <?php include('includes/footer.php'); ?>
        <?php include('script_import.php'); ?>

        <script>
            $(document).ready(function(){
                var quantity = 1;
                $('.quantity-right-plus').click(function(e){
                    e.preventDefault();
                    quantity = parseInt($('#quantity').val());
                    $('#quantity').val(quantity + 1);
                    $('#quantity_input').val(quantity + 1);  // Update hidden input
                });

                $('.quantity-left-minus').click(function(e){
                    e.preventDefault();
                    quantity = parseInt($('#quantity').val());
                    if (quantity > 1) {
                        $('#quantity').val(quantity - 1);
                        $('#quantity_input').val(quantity - 1);  // Update hidden input
                    }
                });
            });

            $(document).ready(function() {
                $('.size-item').click(function() {
                    $('.size-item').removeClass('active');
                    $(this).addClass('active');
                    var selectedSize = $(this).text();  // Get the size text
                    $('#size_input').val(selectedSize);  // Set it to the hidden input field
                });
            });
        </script>
    </body>
</html>
