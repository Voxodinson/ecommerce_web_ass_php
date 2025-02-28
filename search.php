<?php
session_start();

if (isset($_GET['query'])) {
    $searchTerm = $_GET['query'];

    $con = mysqli_connect("localhost", "root", "", "ecom_web_assignment");

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $searchTerm = mysqli_real_escape_string($con, $searchTerm);

    $query = "SELECT id, name, JSON_UNQUOTE(JSON_EXTRACT(images, '$[0]')) AS firstImage, price 
              FROM products_tb 
              WHERE name LIKE '%$searchTerm%' 
              LIMIT 5";

    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }

    if (mysqli_num_rows($result) > 0) {
        echo "<ul class='list-group' style='list-style-type: none; padding: 0;'>"; // List container
        while ($row = mysqli_fetch_assoc($result)) {
            $productName = htmlspecialchars($row['name']);
            $productId = $row['id'];

            echo "<li>
                    <a href='product-detail.php?id=$productId' class='w-100 h-100 bg-danger'>
                        <p class='' style='font-size: 0.8rem; color: #333;'>$productName</p>
                    </a>
                  </li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No results found.</p>";
    }

    mysqli_close($con);
}
?>
