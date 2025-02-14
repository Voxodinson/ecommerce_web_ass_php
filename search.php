<?php
session_start();

if (isset($_GET['query'])) {
    $searchTerm = $_GET['query'];

    // Database connection
    $con = mysqli_connect("localhost", "root", "", "ecom_web_assignment");

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Sanitize input
    $searchTerm = mysqli_real_escape_string($con, $searchTerm);

    // Query to search products and get the first image
    $query = "SELECT id, name, JSON_UNQUOTE(JSON_EXTRACT(images, '$[0]')) AS firstImage, price 
              FROM products_tb 
              WHERE name LIKE '%$searchTerm%' 
              LIMIT 5";

    $result = mysqli_query($con, $query);

    // Check for errors in the query
    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }

    // Display search results
    if (mysqli_num_rows($result) > 0) {
        echo "<ul class='list-group' style='list-style-type: none; padding: 0;'>"; // List container
        while ($row = mysqli_fetch_assoc($result)) {
            $productName = htmlspecialchars($row['name']);
            $productId = $row['id'];

            echo "<li class='list-group-item pe-auto mt-1 table-hover' style='border: 1px solid #ddd; border-radius: 8px; display: flex; align-items: center;'>
                    <a href='product-detail.php?id=$productId' class='prod-link' style='text-decoration: none; display: flex; align-items: center; width: 100%;'>
                            <p class='card-title' style='font-size: 0.8rem; color: #333;'>$productName</p>
                    </a>
                  </li>";
        }
        echo "</ul>"; // Close the list container
    } else {
        echo "<p>No results found.</p>";
    }

    mysqli_close($con);
}
?>
