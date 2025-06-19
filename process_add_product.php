<?php
include 'db.php'; // Include database connection
include 'Product.php'; // Include Product class
include 'Category.php'; // Include Category class

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $categoryName = $_POST['category'];
    $price = $_POST['price'];

    // Create a new category object
    $category = new Category($categoryName);

    // Create a new product object
    $product = new Product($name, $category, $price);

    // Handle file uploads
    if (isset($_FILES['images'])) {
        $uploadDir = 'uploads/'; // Directory to save uploaded images

        // Check if the upload directory exists, if not create it
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Loop through each uploaded file
        foreach ($_FILES['images']['name'] as $key => $name) {
            if ($_FILES['images']['error'][$key] == 0) {
                $tmpName = $_FILES['images']['tmp_name'][$key];
                $uploadFile = $uploadDir . basename($name);

                // Move the uploaded file to the designated directory
                if (move_uploaded_file($tmpName, $uploadFile)) {
                    // Add the image path to the product
                    $product->addImage($uploadFile);
                } else {
                    echo "Error uploading file: " . htmlspecialchars($name);
                }
            } else {
                echo "Error with file: " . htmlspecialchars($name);
            }
        }
    }

    // Insert product into the database
    $stmt = $pdo->prepare("INSERT INTO products (name, category, price, active) VALUES (:name, :category, :price, :active)");
    $stmt->execute([
        'name' => $product->getName(),
        'category' => $product->getCategory()->getName(),
        'price' => $product->getPrice(),
        'active' => $product->isActive() ? 1 : 0 // Store active status as 1 or 0
    ]);

    // Get the last inserted product ID
    $productId = $pdo->lastInsertId();

    // Now, update the product with the image paths
    foreach ($product->getImages() as $imagePath) {
        $stmt = $pdo->prepare("INSERT INTO product_images (product_id, image_path) VALUES (:product_id, :image_path)");
        $stmt->execute([
            'product_id' => $productId,
            'image_path' => $imagePath
        ]);
    }

    header("Location: index.php"); // Redirect to the product list
    exit;
}
?>