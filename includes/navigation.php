<?php
include('link_import.php');
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$totalUniqueItems = count($_SESSION['cart']);

?>
<nav class="colorlib-nav" role="navigation">
    <div class="top-menu">
        <div class="container">
            <div class="row d-flex">
                <div class="col-sm-4 col-md-6">
                    <div id="colorlib-logo"><a href="index.php">Sneakers</a></div>
                </div>
                <div class="d-flex col-sm-4 col-md-6">
                    <form action="javascript:void(0);" class="search-wrap d-flex w-100">
                        <div class="form-group flex-grow-1" data-bs-toggle="modal" data-bs-target="#searchModal">
                            <input type="search" class="form-control search" style="width: 400px;" placeholder="Search">
                            <button class="btn btn-primary submit-search text-center" type="button">
                                <i class="icon-search"></i>
                            </button>
                        </div>
                    </form>

                    <div class="ms-3">
                        <div class="dropdown">
                            <button class="btn dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php
                                if (isset($_SESSION['username'])) {
                                    echo "Hello, " . htmlspecialchars($_SESSION['username']);
                                } else {
                                    echo "Login / Register";
                                }
                                ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark w-100 mw-100" aria-labelledby="navbarDropdown">
                                <?php
                                if (isset($_SESSION['username'])) {

                                    echo '<li><a class="dropdown-item roboto-font" href="edit_profile.php">Profile</a></li>';
                                    echo '<li><a class="dropdown-item roboto-font" href="logout.php">Logout</a></li>';
                                } else {
                                    echo '<li><a class="dropdown-item" href="login.php">Login</a></li>';
                                    echo '<li><a class="dropdown-item" href="signup.php">Register</a></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-sm-12 text-left menu-1">
                    <ul>
                        <li class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                            <a href="index.php">Home</a>
                        </li>
                        <li class="<?= basename($_SERVER['PHP_SELF']) == 'shop.php' ? 'active' : '' ?>">
                            <a href="shop.php">Shop</a>
                        </li>
                        <li class="<?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : '' ?>">
                            <a href="about.php">About</a>
                        </li>
                        <li class="<?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : '' ?>">
                            <a href="contact.php">Contact</a>
                        </li>
                        <li class="<?= basename($_SERVER['PHP_SELF']) == 'order_history.php' ? 'active' : '' ?>">
                            <a href="order_history.php" class="ml-3 "><i class="bi bi-box-seam"></i> Purchase Items</a>
                        </li>
                        <li class="cart ml-3 <?= basename($_SERVER['PHP_SELF']) == 'cart.php' ? 'active' : '' ?>">
                            <a href="cart.php"><i class="icon-shopping-cart"></i> Cart [<?= $totalUniqueItems ?>]</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="searchModalLabel">Search Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="searchInput" class="form-control" placeholder="Search for products...">
                <div id="searchResults" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('input', function() {
    let searchValue = this.value.trim();
    if (searchValue.length > 1) {
        fetch('search.php?query=' + searchValue)
            .then(response => response.text())
            .then(data => {
                document.getElementById('searchResults').innerHTML = data;
            });
    } else {
        document.getElementById('searchResults').innerHTML = '';
    }
});
</script>