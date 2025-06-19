<?php
include 'db.php'; // Include database connection

// Fetch categories from the database
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission for adding a new category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];

    // Insert new category into the database
    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
    $stmt->execute(['name' => $category_name]);

    header("Location: categories.php"); // Redirect to the same page
    exit;
}

// Handle deletion of a category
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Delete category from the database
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
    $stmt->execute(['id' => $delete_id]);

    header("Location: categories.php"); // Redirect to the same page
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Manage Categories</title>
</head>
<body>
    <h1>Manage Categories</h1>

    <form method="POST" action="">
        <input type="text" name="category_name" placeholder="New Category Name" required>
        <button style="padding: 10px 15px;background-color:rgb(7, 52, 136);color: white;border: none;border-radius: 4px;cursor: pointer;" type="submit" name="add_category">Add Category</button>
    </form>

    <h2>Existing Categories</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($categories) > 0): ?>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category['id']); ?></td>
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td>
    <a href="edit_category.php?id=<?php echo $category['id']; ?>" class="btn">Edit</a>
    <a href="categories.php?delete_id=<?php echo $category['id']; ?>" class="btn delete">Delete</a>
</td>
                    </tr>
                    
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No categories found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>