<?php
include('link_import.php');
include_once('services/config.php');
session_start();

$product_id = isset($_GET['id']) ? $_GET['id'] : null;
$product_name = '';
$product_price = '';
$product_description = '';
$product_images = [];
$product_sizes = [];
$rating = 0.0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $size = $_POST['size'];

    if (empty($size)) {
        echo "
            <div class='w-100 h-100 d-flex justify-content-center align-items-center flex-column text-center'>
                <p class='fw-bold text-danger'>Please select a size before adding to cart.</p>
                <button onclick='history.back()' class='btn btn-primary mt-3'>Go Back</button>
            </div>
        ";
        exit();
    }

    $product_details = getProductDetails($product_id);
    
    if (!$product_details) {
        echo "<p>Product not found.</p>";
        exit();
    }
    
    $product_images = json_decode($product_details['images'], true) ?: [];
    $product_image = $product_images[0] ?? ''; 
    $product_name = $product_details['name'];   
    $product_price = $product_details['price']; 
    $product_description = $product_details['details'];
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $exists = false;
    foreach ($_SESSION['cart'] as $index => $cart_item) {
        if ($cart_item['product_id'] == $product_id && $cart_item['size'] == $size) {
            $_SESSION['cart'][$index]['quantity'] += $quantity;
            $exists = true;
            break;
        }
    }
    
    if (!$exists) {
        $_SESSION['cart'][] = [
            'product_id' => $product_id,
            'quantity' => $quantity,
            'size' => $size,
            'name' => $product_name,
            'price' => $product_price,
            'image' => $product_image,
            'details' => $product_description
        ];
    }
    
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
}

if ($product_id) {
    $product_details = getProductDetails($product_id);
    if ($product_details) {
        $product_images = json_decode($product_details['images'], true) ?: [];
        $product_name = $product_details['name'];
        $product_price = $product_details['price'];
        $product_description = $product_details['details'];
        $product_sizes = json_decode($product_details['size'], true) ?: [];
        $rating = (float) $product_details['rating'];
    } else {
        echo "<p>Product not found.</p>";
    }
} else {
    echo "<p>No product ID provided.</p>";
}

function getProductDetails($product_id) {
    global $con;
    try {
        $query = "SELECT name, price, details, images, size, rating FROM products_tb WHERE id = :product_id";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        if ($product) {
            $product['rating'] = (float) $product['rating'];
        }
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
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
        .star {
            color: gray;
        }
        .yellow {
            color: yellow;
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
                        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php if (!empty($product_images)): ?>
                                    <?php foreach ($product_images as $index => $image): ?>
                                        <div class="carousel-item <?php echo ($index == 0) ? 'active' : ''; ?>">
                                            <div class="product-entry border">
                                                <a href="#" class="prod-img">
                                                    <img 
                                                        src="<?php echo !empty($image) ? 'http://localhost/school_ass/ecom_web_admin/uploads/images/' . htmlspecialchars($image) : 'images/no_image.jpg'; ?>" 
                                                        class="d-block w-100" 
                                                        alt="<?php echo htmlspecialchars($product_name); ?>">
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="carousel-item active">
                                        <div class="product-entry border">
                                            <a href="#" class="prod-img">
                                                <img 
                                                    src="images/no_image.jpg" 
                                                    class="d-block w-100" 
                                                    alt="No image available">
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="product-desc">
                            <h3><?php echo htmlspecialchars($product_name); ?></h3>
                            <p class="price">
                                <span>$<?php echo number_format($product_price, 2); ?></span> 
                                <span class="rate">
                                    <?php 
                                        $fullStars = floor($rating);
                                        $halfStar = ($rating - $fullStars >= 0.5) ? 1 : 0;
                                        $emptyStars = 5 - $fullStars - $halfStar;
                                        
                                        for ($i = 0; $i < $fullStars; $i++) {
                                            echo '<i class="icon-star-full" style="color: gold;"></i>';
                                        }
                                        if ($halfStar) {
                                            echo '<i class="icon-star-half" style="color: gold;"></i>';
                                        }
                                        for ($i = 0; $i < $emptyStars; $i++) {
                                            echo '<i class="icon-star-empty star "></i>';
                                        }
                                    ?>
                                    (<?= number_format($rating, 1); ?> Rating)
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
                                            <button type="submit" class="btn btn-primary btn-addtocart d-flex">
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
            document.addEventListener('DOMContentLoaded', function() {
                let quantityInput = document.getElementById('quantity');
                let quantityHiddenInput = document.getElementById('quantity_input');
                let sizeInput = document.getElementById('size_input');
        
                document.querySelectorAll('.quantity-right-plus').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        let quantity = parseInt(quantityInput.value);
                        quantityInput.value = quantity + 1;
                        quantityHiddenInput.value = quantity + 1;
                    });
                });
        
                document.querySelectorAll('.quantity-left-minus').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        let quantity = parseInt(quantityInput.value);
                        if (quantity > 1) {
                            quantityInput.value = quantity - 1;
                            quantityHiddenInput.value = quantity - 1;
                        }
                    });
                });
        
                document.querySelectorAll('.size-item').forEach(function(sizeItem) {
                    sizeItem.addEventListener('click', function(e) {
                        e.preventDefault();
                        document.querySelectorAll('.size-item').forEach(item => item.classList.remove('active'));
                        sizeItem.classList.add('active');
                        sizeInput.value = sizeItem.textContent.trim();
                    });
                });
            });
        </script>

    </body>
</html>
