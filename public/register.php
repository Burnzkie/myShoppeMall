<?php
require_once '../config/Database.php';
require_once '../classes/Customer.php';
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    $customer = new Customer($db);
    $customer->name = $_POST['name'];
    $customer->email = $_POST['email'];
    $customer->password = $_POST['password'];
    $customer->phone = $_POST['phone'];
    $customer->address = $_POST['address'];
    if ($customer->create()) {
        $success = 'Account created! Please login.';
    } else {
        $error = 'Registration failed.';
    }
}
?>
<?php include '../includes/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Register</h3>
                <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
                <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Address</label>
                        <textarea name="address" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Register</button>
                </form>
                <p class="mt-3 text-center"><a href="login.php">Already have an account? Login</a></p>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>