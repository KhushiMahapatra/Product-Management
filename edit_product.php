<?php
include 'db.php'; // Include database connection

// Initialize an empty product array
$product = [
    'name' => '',
    'category' => '',
    'price' => ''
];

// Fetch the product to edit
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("Product not found.");
    }
} else {
    die("Invalid product ID.");
}

// Handle form submission for editing the product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];

    // Update the product in the database
    $stmt = $pdo->prepare("UPDATE products SET name = :name, category = :category, price = :price WHERE id = :id");
    $stmt->execute(['name' => $name, 'category' => $category, 'price' => $price, 'id' => $id]);

    header("Location: index.php"); // Redirect to the product list
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Edit Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
            color: #333;
        }

        h1 {
            text-align: center;
            color:rgb(30, 70, 112);
        }

        .form-container {
            width: 50%;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        td {
            padding: 10px;
            vertical-align: middle;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            padding: 10px 20px;
            background-color:rgb(10, 51, 95);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            margin-top: 10px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
        }

        .btn:hover {
            background-color: #565e64;
        }
    </style>
</head>
<body>
    <h1>Edit Product</h1>
    <div class="form-container">
        <form method="POST" action="">
            <table>
                <tr>
                    <td><label for="name">Product Name:</label></td>
                    <td>
                        <input type="text" id="name" name="name" 
                               value="<?php echo htmlspecialchars($product['name']); ?>" required>
                    </td>
                </tr>
                <tr>
                    <td><label for="category">Category:</label></td>
                    <td>
                        <input type="text" id="category" name="category" 
                               value="<?php echo htmlspecialchars($product['category']); ?>" required>
                    </td>
                </tr>
                <tr>
                    <td><label for="price">Price:</label></td>
                    <td>
                        <input type="number" id="price" name="price" step="0.01" 
                               value="<?php echo htmlspecialchars($product['price']); ?>" required>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <button type="submit">Update Product</button>
                    </td>
                </tr>
            </table>
        </form>
        <div style="text-align: center;">
            <a href="index.php" class="btn">Back to Product List</a>
        </div>
    </div>
</body>
</html>
