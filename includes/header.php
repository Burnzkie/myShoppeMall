<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShoppeMall</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../public/index.php">ShoppeMall</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../public/products.php">Products</a>
                <a class="nav-link" href="../public/cart.php">Cart</a>
                <?php if (User::isLoggedIn()): ?>
                    <span class="nav-link">Welcome, <?= $_SESSION['user_name'] ?> (<?= ucfirst(User::getCurrentUserRole()) ?>)</span>
                    <a class="nav-link" href="../public/logout.php">Logout</a>
                    <?php if (User::getCurrentUserRole() == 'admin'): ?>
                        <a class="nav-link" href="../public/admin/dashboard.php">Admin</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a class="nav-link" href="../public/login.php">Login</a>
                    <a class="nav-link" href="../public/register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container mt-4">