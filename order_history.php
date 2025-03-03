<!DOCTYPE html>
<html lang="en">
    <?php 
        session_start();
        include('link_import.php');
    ?>
<head>
    <style>
        .delete-button-wrapper {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
    <?php include('includes/navigation.php') ?>

    <?php
        if (!isset($_SESSION['user_id'])) {
            echo "<script>alert('Please LOGIN to proceed.'); window.location.href='login.php';</script>";
            exit();
        }

        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "ecom_web_assignment";

        try {
            $con = new PDO("mysql:host=$host;dbname=$database", $username, $password);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }

        // Handle delete request
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_order'])) {
            // Get the checkout_info_id from the hidden field
            $checkout_info_id = $_POST['checkout_info_id'];

            try {
                // Start a transaction to ensure both deletions happen together
                $con->beginTransaction();

                // Delete from order_history (delete only the order for this checkout_info_id)
                $delete_order_history_sql = "DELETE FROM order_history WHERE checkout_info_id = ?";
                $delete_stmt = $con->prepare($delete_order_history_sql);
                $delete_stmt->execute([$checkout_info_id]);

                // Delete from checkout_info (delete the checkout information for this specific user and order)
                $delete_checkout_sql = "DELETE FROM checkout_info WHERE checkout_info_id = ?";
                $delete_checkout_stmt = $con->prepare($delete_checkout_sql);
                $delete_checkout_stmt->execute([$checkout_info_id]);

                // Commit transaction
                $con->commit();

                // Redirect after deletion to confirm action
                echo "<script>alert('Your order has been successfully deleted.'); window.location.href='order_history.php';</script>";
            } catch (Exception $e) {
                // If any error occurs, rollback transaction
                $con->rollBack();
                echo "<script>alert('Error deleting order: " . $e->getMessage() . ". Please try again.');</script>";
            }
        }

        $sql = "SELECT * FROM checkout_info WHERE user_id = :user_id";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<div class='container mt-5'>";

        echo "<h3>Checkout Information</h3>";
        if (count($result) > 0) {
            foreach ($result as $checkout) {
                $checkout_info_id = $checkout['checkout_info_id'];

                $order_sql = "SELECT * FROM order_history WHERE checkout_info_id = :checkout_info_id";
                $order_stmt = $con->prepare($order_sql);
                $order_stmt->bindParam(':checkout_info_id', $checkout_info_id, PDO::PARAM_INT);
                $order_stmt->execute();
                $order_result = $order_stmt->fetchAll(PDO::FETCH_ASSOC);

                echo "<div class='card mb-3'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>Customer: " . htmlspecialchars($checkout['fname']) . " " . htmlspecialchars($checkout['lname']) . "</h5>";
                echo "<p><strong>Country:</strong> " . htmlspecialchars($checkout['country']) . "</p>";
                echo "<p><strong>Address:</strong> " . htmlspecialchars($checkout['address']) . " " . htmlspecialchars($checkout['address2']) . "</p>";
                echo "<p><strong>City:</strong> " . htmlspecialchars($checkout['towncity']) . "</p>";
                echo "<p><strong>State:</strong> " . htmlspecialchars($checkout['stateprovince']) . "</p>";
                echo "<p><strong>ZIP:</strong> " . htmlspecialchars($checkout['zippostalcode']) . "</p>";
                echo "<p><strong>Email:</strong> " . htmlspecialchars($checkout['email']) . "</p>";
                echo "<p><strong>Phone:</strong> " . htmlspecialchars($checkout['phone']) . "</p>";
                echo "<p><strong>Payment Method:</strong> " . htmlspecialchars($checkout['payment_method']) . "</p>";
                echo "</div>";

                // Add Delete Button and Form wrapped in a div for positioning
                echo "<div class='delete-button-wrapper'>
                        <form method='POST' action='order_history.php' onsubmit='return confirm(\"Are you sure you want to delete this order?\");'>
                            <!-- Hidden field to pass the checkout_info_id of the order to be deleted -->
                            <input type='hidden' name='checkout_info_id' value='" . htmlspecialchars($checkout_info_id) . "'>
                            <button type='submit' name='delete_order' class='btn btn-danger'>Delete Order</button>
                        </form>
                      </div>";

                if (count($order_result) > 0) {
                    echo "<h4>Order Details</h4>";
                    echo "<table class='table p-3 table-striped'>";
                    echo "<thead>
                        <tr>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Order Date</th>
                        </tr>
                        </thead>
                    <tbody>";

                    foreach ($order_result as $order) {
                        echo "<tr>";
                        echo "<td>  
                                <a href='product-detail.php?id=" . htmlspecialchars($order['item_id']) . "' class='product-link'>
                                    <img 
                                        class='product-img'
                                        src='http://localhost/school_ass/ecom_web_admin/uploads/images/" . htmlspecialchars($order['image']) . "' alt='Product Image' width='100'>
                                </a>
                            </td>";
                        echo "<td>" . htmlspecialchars($order['product_name']) . "</td>";
                        echo "<td>" . number_format($order['price'], 2) . "</td>";
                        echo "<td>" . htmlspecialchars($order['quantity']) . "</td>";
                        echo "<td>" . number_format($order['subtotal'], 2) . "</td>";
                        echo "<td>" . htmlspecialchars($order['order_date']) . "</td>";
                        echo "</tr>";
                    }

                    echo "</tbody></table>";
                } else {
                    echo "<p>No order items found for this checkout.</p>";
                }

                echo "</div>";
            }
        } else {
            echo "<p>No checkout information found for this user.</p>";
        }
        echo "</div>";

        $con = null;
    ?>
</body>
</html>
