<?php
require_once '../config/Database.php';
require_once '../classes/Product.php';
$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$stmt = $product->readAll();
?>
<?php include '../includes/header.php'; ?>
<h1>Welcome to ShoppeMall</h1>
<div class="row">
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
    <div class="col-md-4 mb-4">
        <div class="card">
            <img src="../uploads/<?= $row['image'] ?>" class="card-img-top" alt="<?= $row['name'] ?>" style="height: 200px; object-fit: cover;">
            <div class="card-body">
                <h5 class="card-title"><?= $row['name'] ?></h5>
                <p class="card-text"><?= substr($row['description'], 0, 100) ?>...</p>
                <p class="card-text"><strong>$<?= $row['price'] ?></strong></p>
                <a href="product-detail.php?id=<?= $row['id'] ?>" class="btn btn-primary">View Details</a>
                <a href="cart.php?action=add&id=<?= $row['id'] ?>" class="btn btn-success">Add to Cart</a>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>
<?php include '../includes/footer.php'; ?>