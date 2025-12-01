<?php
require_once '../config/Database.php';
require_once '../classes/Product.php';
$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$stmt = $product->readAll();
?>
<?php include '../includes/header.php'; ?>
<h1>All Products</h1>
<div class="row">
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
    <div class="col-md-4 mb-4">
        <div class="card">
            <img src="../uploads/<?= $row['image'] ?>" class="card-img-top" alt="<?= $row['name'] ?>">
            <div class="card-body">
                <h5><?= $row['name'] ?></h5>
                <p>$<?= $row['price'] ?></p>
                <a href="product-detail.php?id=<?= $row['id'] ?>" class="btn btn-primary">View</a>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>
<?php include '../includes/footer.php'; ?>