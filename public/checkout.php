<?php
session_start();
require_once '../config/Database.php';
require_once '../classes/Cart.php';
require_once '../classes/Order.php';
if (!User::isLoggedIn()) {
    header('Location: login.php');
    exit();
}
$database = new Database();
$db = $database->getConnection();
$cart = new Cart($db);
$order = new Order($db);
$user_id = $_SESSION['user_id'];
$items = $cart->getItems($user_id);
$total = $cart->getTotal($items);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order->create($items, $_POST['address'], $_POST['payment_method']);
    $cart->clearCart($user_id);
    $success = 'Order placed successfully!';
}
?>
<?php include '../includes/header.php'; ?>
<h1>Checkout</h1>
<?php if (isset($success)): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
<form method="POST">
    <div class="mb-3">
        <label>Shipping Address</label>
        <textarea name="address" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
        <label>Payment Method</label>
        <select name="payment_method" class="form-control" required>
            <option value="cash">Cash on Delivery</option>
            <option value="card">Credit Card</option>
        </select>
    </div>
    <h3>Total: $<?= $total ?></h3>
    <button type="submit" class="btn btn-success">Place Order</button>
</form>
<?php include '../includes/footer.php'; ?>