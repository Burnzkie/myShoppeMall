<?php
session_start();
require_once '../../config/Database.php';
require_once '../../classes/Product.php';
if (User::getCurrentUserRole() != 'admin') {
    header('Location: ../login.php');
    exit();
}
$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$stmt = $product->readAll();
if (isset($_GET['delete'])) {
    $product->id = $_GET['delete'];
    $product->delete();
    header('Location: manage-products.php');
}
?>
<?php include '../../includes/header.php'; ?>
<h1>Manage Products</h1>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td>$<?= $row['price'] ?></td>
                <td><?= $row['stock'] ?></td>
                <td>
                    <a href="edit-product.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<a href="add-product.php" class="btn btn-primary">Add New Product</a>
<?php include '../../includes/footer.php'; ?>