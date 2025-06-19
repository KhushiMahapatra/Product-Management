<?php
include 'db.php'; // Include database connection
include 'Product.php'; // Include Product class
include 'Category.php'; // Include Category class

// Initialize search term
$searchTerm = '';

// Check if a search term has been submitted
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
}

// Determine the sorting option
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';

// Build the SQL query based on the selected sorting option and search term
$query = "
    SELECT p.*, c.name AS category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category = c.id 
    WHERE p.name LIKE :searchTerm OR c.name LIKE :searchTerm"; // Search in product name and category name

switch ($sort) {
    case 'name':
        $query .= " ORDER BY p.name ASC";
        break;
    case 'price':
        $query .= " ORDER BY p.price ASC";
        break;
    case 'category':
        $query .= " ORDER BY c.name ASC"; // Sort by category name
        break;
    case 'status':
        $query .= " ORDER BY p.active DESC";
        break;
    default:
        // No additional sorting
        break;
}

// Prepare and execute the statement
$stmt = $pdo->prepare($query);
$stmt->execute(['searchTerm' => '%' . $searchTerm . '%']);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Product Management</title>
</head>
<body>
    <h1>Product Management</h1>
    <a href="add_product.php" class="btn">Add New Product</a>

    <!-- Search Form -->
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search products or categories..." value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit" style="
            padding: 10px 15px; 
            background-color:rgb(27, 39, 109); 
            color: white; 
            border: none; 
            border-radius: 5px; 
            font-size: 16px;"
            onmouseover="this.style.backgroundColor='#218838'; this.style.transform='scale(1.05)';"
            onmouseout="this.style.backgroundColor='#28a745'; this.style.transform='scale(1)';"
            onfocus="this.style.boxShadow='0 0 5px rgba(40, 142, 167, 0.5)';"
            onblur="this.style.boxShadow='none';">
            Search
        </button>
    </form>

    <!-- Sorting Options -->
    <form method="GET" action="">
        <label for="sort">Sort by:</label>
        <select name="sort" id="sort" onchange="this.form.submit()">
            <option value="default">Default</option>
            <option value="name" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'name' ? 'selected' : ''; ?>>Name</option>
            <option value="price" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price' ? 'selected' : ''; ?>>Price</option>
            <option value="category" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'category' ? 'selected' : ''; ?>>Category</option>
            <option value="status" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'status' ? 'selected' : ''; ?>>Status</option>
        </select>
    </form>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th> <!-- This will show the category name -->
                <th>Price</th>
                <th>Status</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['category_name']); ?></td> <!-- Display category name -->
                       
                        <td><?php echo htmlspecialchars($product['price']); ?></td>
                        <td><?php echo $product['active'] ? 'Active' : 'Inactive'; ?></td>
                        <td>
                            <?php if (!empty($product['image']) && file_exists($product['image'])): ?>
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 50px; height: auto;">
                            <?php else: ?>
                                <img src="path/to/default/image.jpg" alt="No Image" style="width: 50px; height: auto;"> <!-- Default image if none exists -->
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn">Edit</a>
                            <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="btn delete">Delete</a>
                            <?php if ($product['active']): ?>
                            <a href="deactivate_product.php?id=<?php echo $product['id']; ?>" class="btn deactivate">Deactivate</a>
                        <?php else: ?>
                            <a href="activate_product.php?id=<?php echo $product['id']; ?>" class="btn activate">Activate</a>
                        <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No products found.</td>
        </tr>
    <?php endif; ?>
</tbody>
    </table>
</body>
</html>