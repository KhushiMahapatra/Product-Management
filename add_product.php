<?php
include 'db.php'; // Include database connection

// Fetch categories from the database
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $category = $_POST['category']; // This will now be the category ID
    $price = $_POST['price'];
    $active = isset($_POST['active']) ? 1 : 0;

    // Handle image upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "uploads/"; // Directory to save uploaded images
        $imagePath = $targetDir . basename($_FILES["image"]["name"]);
        
        // Create the uploads directory if it doesn't exist
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
            // File uploaded successfully
        } else {
            // Handle error
            echo "Error uploading the file.";
            exit;
        }
    }

    // Insert product into the database
    $stmt = $pdo->prepare("INSERT INTO products (name, category, price, active, image) VALUES (:name, :category, :price, :active, :image)");
    $stmt->execute([
        'name' => $name,
        'category' => $category,
        'price' => $price,
        'active' => $active,
        'image' => $imagePath
    ]);

    header("Location: index.php"); // Redirect to the product list
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="add_products.css">
    <title>Add Product</title>
</head>
<body>
    <h1>Add New Product</h1>
    <form method="POST" enctype="multipart/form-data">
        <div>
            <label for="name">Product Name:</label>
            <input type="text" name="name" id="name" placeholder="Product Name" required>
        </div>
        <div>
    <label for="category">Category:</label>
    <select  name="category" id="category" required>
        <option value="">Select a category</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo htmlspecialchars($cat['id']); ?>">
                <?php echo htmlspecialchars($cat['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
        <div>
            <label for="price">Price:</label>
            <input type="number" name="price" id="price" placeholder="Price" step="0.01" required>
        </div>
        <div>
            <label>
                <input type="checkbox" name="active" checked> Active
            </label>
        </div>
        <div>
            <label for="image">Image:</label>
            <input type="file" name="image" id="image" accept="image/*" required>
        </div>
        <button type="submit">Add Product</button>
        <div>
            <a href="index.php" class="btn">Back to Product List</a>
        </div>
    </form>
</body>
</html>