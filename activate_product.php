<?php
include 'db.php'; // Include database connection

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Update the product's active status to true
    $stmt = $pdo->prepare("UPDATE products SET active = 1 WHERE id = :id");
    $stmt->execute(['id' => $product_id]);

    header("Location: index.php"); // Redirect back to the product list
    exit;
}
?>