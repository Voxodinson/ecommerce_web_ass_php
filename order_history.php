<?php
// Start the session
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please LOGIN to proceed.'); window.location.href='login.php';</script>";
    exit();
}

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "ecom_web_assignment";
$con = new mysqli($host, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Fetch checkout information for the logged-in user
$sql = "SELECT * FROM checkout_info WHERE user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

echo "<div class='container mt-5'>";

echo "<h3>Checkout Information</h3>";
// Check if checkout info exists for this user
if ($result->num_rows > 0) {
    while ($checkout = $result->fetch_assoc()) {
        $checkout_info_id = $checkout['checkout_info_id']; // Get the checkout_info ID

        // SQL to fetch order history related to this checkout
        $order_sql = "SELECT * FROM order_history WHERE checkout_info_id = ?";
        $order_stmt = $con->prepare($order_sql);
        $order_stmt->bind_param("i", $checkout_info_id);
        $order_stmt->execute();
        $order_result = $order_stmt->get_result();

        // Output the checkout info
        echo "<div class='card mb-3'>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>Customer: " . $checkout['fname'] . " " . $checkout['lname'] . "</h5>";
        echo "<p><strong>Country:</strong> " . $checkout['country'] . "</p>";
        echo "<p><strong>Address:</strong> " . $checkout['address'] . " " . $checkout['address2'] . "</p>";
        echo "<p><strong>City:</strong> " . $checkout['towncity'] . "</p>";
        echo "<p><strong>State:</strong> " . $checkout['stateprovince'] . "</p>";
        echo "<p><strong>ZIP:</strong> " . $checkout['zippostalcode'] . "</p>";
        echo "<p><strong>Email:</strong> " . $checkout['email'] . "</p>";
        echo "<p><strong>Phone:</strong> " . $checkout['phone'] . "</p>";
        echo "<p><strong>Payment Method:</strong> " . $checkout['payment_method'] . "</p>";
        echo "</div>";

        // Check if there are order items for this checkout
        if ($order_result->num_rows > 0) {
            echo "<h4>Order Details</h4>";
            echo "<table class='table p-3 table-striped'>";
            echo "<thead>
                <tr>
                    <th>
                       Image
                    </th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Order Date</th>
                    </tr>
                    </thead>
                <tbody>";

            // Loop through the order items
            while ($order = $order_result->fetch_assoc()) {
                echo "<tr>";
                
                // Assuming you have an 'image' column in the 'order_history' table to store the image URL
                echo "  <td>  
                            <a href='product-detail.php?id=" . htmlspecialchars($order['item_id']) . "' class='product-link'>
                                <img class='product-img' src='" . htmlspecialchars($order['image']) . "' alt='Product Image' width='100'>
                            </a>
                        </td>";
                echo "<td>" . $order['product_name'] . "</td>";
                echo "<td>" . number_format($order['price'], 2) . "</td>";
                echo "<td>" . $order['quantity'] . "</td>";
                echo "<td>" . number_format($order['subtotal'], 2) . "</td>";
                echo "<td>" . $order['order_date'] . "</td>";
                
                echo "</tr>";
            }
            

            echo "</tbody></table>";
        } else {
            echo "<p>No order items found for this checkout.</p>";
        }

        echo "</div>"; // End of container div
    }
} else {
    echo "<p>No checkout information found for this user.</p>";
}
echo "</div>";
$con->close();
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
