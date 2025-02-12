<nav class="colorlib-nav" role="navigation">
    <div class="top-menu">
        <div class="container">
            <div class="row">
                <div class="col-sm-7 col-md-9">
                    <div id="colorlib-logo"><a href="index.html">Footwear</a></div>
                </div>
                <div class="col-sm-5 col-md-3">
                <form action="#" class="search-wrap">
                    <div class="form-group">
                        <input type="search" class="form-control search" placeholder="Search">
                        <button class="btn btn-primary submit-search text-center" type="submit"><i class="icon-search"></i></button>
                    </div>
                </form>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 text-left menu-1">
                <ul>
                    <li class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                        <a href="index.php">Home</a>
                    </li>
                    <li class="has-dropdown <?= basename($_SERVER['PHP_SELF']) == 'men.php' ? 'active' : '' ?>">
                        <a href="men.php">Men</a>
                        <ul class="dropdown">
                            <li class="<?= basename($_SERVER['PHP_SELF']) == 'product-detail.php' ? 'active' : '' ?>">
                                <a href="product-detail.php">Product Detail</a>
                            </li>
                            <li class="<?= basename($_SERVER['PHP_SELF']) == 'cart.php' ? 'active' : '' ?>">
                                <a href="cart.php">Shopping Cart</a>
                            </li>
                            <li class="<?= basename($_SERVER['PHP_SELF']) == 'checkout.php' ? 'active' : '' ?>">
                                <a href="checkout.php">Checkout</a>
                            </li>
                            <li class="<?= basename($_SERVER['PHP_SELF']) == 'order-complete.php' ? 'active' : '' ?>">
                                <a href="order-complete.php">Order Complete</a>
                            </li>
                            <li class="<?= basename($_SERVER['PHP_SELF']) == 'add-to-wishlist.php' ? 'active' : '' ?>">
                                <a href="add-to-wishlist.php">Wishlist</a>
                            </li>
                        </ul>
                    </li>
                    <li class="<?= basename($_SERVER['PHP_SELF']) == 'women.php' ? 'active' : '' ?>">
                        <a href="women.php">Women</a>
                    </li>
                    <li class="<?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : '' ?>">
                        <a href="about.php">About</a>
                    </li>
                    <li class="<?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : '' ?>">
                        <a href="contact.php">Contact</a>
                    </li>
                    <li class="cart <?= basename($_SERVER['PHP_SELF']) == 'cart.php' ? 'active' : '' ?>">
                        <a href="cart.php"><i class="icon-shopping-cart"></i> Cart [0]</a>
                    </li>
                </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="sale">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 offset-sm-2 text-center">
                    <div class="row">
                        <div class="owl-carousel2">
                            <div class="item">
                                <div class="col">
                                    <h3><a href="#">25% off (Almost) Everything! Use Code: Summer Sale</a></h3>
                                </div>
                            </div>
                            <div class="item">
                                <div class="col">
                                    <h3><a href="#">Our biggest sale yet 50% off all summer shoes</a></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>