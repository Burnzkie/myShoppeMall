<?php
require_once '../classes/User.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (User::authenticate($_POST['email'], $_POST['password'])) {
        $role = User::getCurrentUserRole();
        if ($role == 'admin') {
            header('Location: admin/dashboard.php');
        } elseif ($role == 'staff') {
            header('Location: admin/dashboard.php'); // Shared for simplicity
        } else {
            header('Location: index.php');
        }
        exit();
    } else {
        $error = 'Invalid credentials.';
    }
}
?>
<?php include '../includes/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Login</h3>
                <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <p class="mt-3 text-center">Demo: admin@shoppemall.com / password123</p>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>