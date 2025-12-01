<?php
session_start();
require_once '../../config/Database.php';
require_once '../../classes/Order.php';
if (User::getCurrentUserRole() != 'admin') {
    header('Location: ../login.php');
    exit();
}
$database = new Database();
$db = $database->getConnection();
$order = new Order($db);
$orders = $order->readByUser(0); // All orders (modify for all)
?>
<?php include '../../includes/header.php'; ?>
<h1>Manage Orders</h1>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $o): ?>
            <tr>
                <td><?= $o['id'] ?></td>
                <td><?= $o['user_id'] ?></td> <!-- Add join for name -->
                <td>$<?= $o['total_amount'] ?></td>
                <td><?= $o['status'] ?></td>
                <td><a href="view-order.php?id=<?= $o['id'] ?>" class="btn btn-info btn-sm">View</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include '../../includes/footer.php'; ?>