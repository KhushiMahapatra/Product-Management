<?php
include 'db.php'; // Include database connection

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Update the product's active status to false
    $stmt = $pdo->prepare("UPDATE products SET active = 0 WHERE id = :id");
    $stmt->execute(['id' => $product_id]);

    header("Location: index.php"); // Redirect back to the product list
    exit;
}
?>