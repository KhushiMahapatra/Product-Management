<?php
include 'db.php'; // Include database connection

// Check if the category ID is provided
if (isset($_GET['id'])) {
    $category_id = $_GET['id'];

    // Fetch the category from the database
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = :id");
    $stmt->execute(['id' => $category_id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    // Handle form submission for updating the category
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_category'])) {
        $category_name = $_POST['category_name'];

        // Update the category in the database
        $stmt = $pdo->prepare("UPDATE categories SET name = :name WHERE id = :id");
        $stmt->execute(['name' => $category_name, 'id' => $category_id]);

        header("Location: categories.php"); // Redirect to the categories page
        exit;
    }
} else {
    // Redirect if no category ID is provided
    header("Location: categories.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Edit Category</title>
</head>
<body>
    <h1>Edit Category</h1>

    <form method="POST" action="">
        <input type="text" name="category_name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
        <button style="padding: 10px 15px;background-color:rgb(72, 107, 173);color: white;border: none;border-radius: 4px;cursor: pointer;" type="submit" name="update_category">Update Category</button>
        <a href="categories.php" style="display: inline-block; padding: 10px 15px;  background-color:rgb(15, 22, 131);color: white;  text-decoration: none; 
    border-radius: 4px;" >Cancel</a>
    </form>

    
</body>
</html>