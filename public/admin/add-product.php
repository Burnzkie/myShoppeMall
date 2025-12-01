<?php
session_start();
require_once '../../config/Database.php';
require_once '../../classes/Product.php';
require_once '../../classes/Category.php';
if (User::getCurrentUserRole() != 'admin') {
    header('Location: ../login.php');
    exit();
}
$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$category = new Category($db);
$category_stmt = $category->read();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product->name = $_POST['name'];
    $product->description = $_POST['description'];
    $product->price = $_POST['price'];
    $product->stock = $_POST['stock'];
    $product->category_id = $_POST['category_id'];
    if (isset($_FILES['image']) && $product->uploadImage($_FILES['image'])) {
        if ($product->create()) {
            $success = 'Product added!';
        }
    }
}
?>
<?php include '../../includes/header.php'; ?>
<h1>Add Product</h1>
<?php if (isset($success)): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
        <label>Price</label>
        <input type="number" step="0.01" name="price" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Stock</label>
        <input type="number" name="stock" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Category</label>
        <select name="category_id" class="form-control" required>
            <?php while ($row = $category_stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Image</label>
        <input type="file" name="image" class="form-control" accept="image/*" required>
    </div>
    <button type="submit" class="btn btn-success">Add Product</button>
</form>
<?php include '../../includes/footer.php'; ?>