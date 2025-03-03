<?php
include_once('services/config.php');
session_start();

if (isset($_GET['query'])) {
    $searchTerm = trim($_GET['query']);

    try {
        // Prepare and execute the search query
        $query = "SELECT id, name, JSON_UNQUOTE(JSON_EXTRACT(images, '$[0]')) AS firstImage, price 
                  FROM products_tb 
                  WHERE name LIKE :searchTerm 
                  LIMIT 5";
        
        $stmt = $con->prepare($query);
        $searchTerm = "%" . $searchTerm . "%";
        $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) > 0) {
            echo "<ul class='list-group' style='list-style-type: none; padding: 0;'>"; // List container
            foreach ($results as $row) {
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
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>
