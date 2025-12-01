<?php
session_start();
require_once '../../classes/User.php';
if (!User::isLoggedIn() || User::getCurrentUserRole() != 'admin') {
    header('Location: ../login.php');
    exit();
}
$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$product_stmt = $product->readAll();
$product_count = $product_stmt->rowCount();
?>
<?php include '../../includes/header.php'; ?>
<h1>Admin Dashboard</h1>
<div class="row">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h2><?= $product_count ?></h2>
                <p>Total Products</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h2>0</h2> <!-- Add order count logic -->
                <p>Total Orders</p>
            </div>
        </div>
    </div>
</div>
<div class="mt-4">
    <a href="add-product.php" class="btn btn-primary">Add Product</a>
    <a href="manage-products.php" class="btn btn-secondary">Manage Products</a>
    <a href="manage-orders.php" class="btn btn-warning">Manage Orders</a>
</div>
<?php include '../../includes/footer.php'; ?>