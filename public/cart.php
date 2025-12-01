<?php
session_start();
require_once '../config/Database.php';
require_once '../classes/Cart.php';
$database = new Database();
$db = $database->getConnection();
$cart = new Cart($db);
$user_id = User::isLoggedIn() ? $_SESSION['user_id'] : null;

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'add' && isset($_GET['id'])) {
        $cart->addItem($user_id, $_GET['id']);
    } elseif ($_GET['action'] == 'remove' && isset($_GET['id'])) {
        // Remove logic here (use cart ID if needed)
    }
}

$items = $cart->getItems($user_id);
$total = $cart->getTotal($items);
?>
<?php include '../includes/header.php'; ?>
<h1>Shopping Cart</h1>
<?php if (empty($items)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= $item['name'] ?></td>
                    <td>$<?= $item['price'] ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>$<?= $item['price'] * $item['quantity'] ?></td>
                    <td><a href="?action=remove&id=<?= $item['id'] ?>" class="btn btn-danger btn-sm">Remove</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <h3>Total: $<?= $total ?></h3>
    <a href="checkout.php" class="btn btn-success">Checkout</a>
    <a href="index.php" class="btn btn-secondary">Continue Shopping</a>
<?php endif; ?>
<?php include '../includes/footer.php'; ?>