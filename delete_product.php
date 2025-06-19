<?php
include 'db.php'; // Include database connection

// Handle deletion of a product
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete product from the database
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute(['id' => $id]);

    header("Location: index.php"); // Redirect to the product list
    exit;
} else {
    die("No product ID specified.");
}
?>