<?php
require_once '../config/Database.php';
require_once '../classes/Product.php';
$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
if (isset($_GET['id'])) {
    $product->id = $_GET['id'];
    $product->readOne();
}
?>
<?php include '../includes/header.php'; ?>
<div class="row">
    <div class="col-md-6">
        <img src="../uploads/<?= $product->image ?>" class="img-fluid" alt="<?= $product->name ?>">
    </div>
    <div class="col-md-6">
        <h1><?= $product->name ?></h1>
        <p><?= $product->description ?></p>
        <h3>$<?= $product->price ?></h3>
        <p>Stock: <?= $product->stock ?></p>
        <a href="cart.php?action=add&id=<?= $product->id ?>" class="btn btn-success">Add to Cart</a>
    </div>
</div>
<?php include '../includes/footer.php'; ?>