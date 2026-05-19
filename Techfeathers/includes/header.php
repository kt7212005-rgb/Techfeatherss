<?php
// includes/header.php

if (!isset($activeNav)) {
    $activeNav = '';
}

$user = current_user();
$userRole = $user ? ($user['role'] ?? 'customer') : 'customer';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Poultry Management - <?= htmlspecialchars($pageTitle ?? '') ?></title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>
    <a class="background-link" href="index.php" title="Back to login">Back to login</a>
    <div class="layout">
        <aside class="sidebar">
            <h2>Poultry</h2>
            <ul class="nav">
                <li><a class="<?= $activeNav === 'dashboard' ? 'active' : '' ?>" href="dashboard.php">Dashboard</a></li>
                <?php if ($userRole === 'admin' || $userRole === 'manager'): ?>
                    <li><a class="<?= $activeNav === 'chickens' ? 'active' : '' ?>" href="chickens.php">Chickens</a></li>
                    <li><a class="<?= $activeNav === 'eggs' ? 'active' : '' ?>" href="eggs.php">Egg Production</a></li>
                    <li><a class="<?= $activeNav === 'feed' ? 'active' : '' ?>" href="feed.php">Feed Inventory</a></li>
                <?php endif; ?>
                <?php if ($userRole === 'admin'): ?>
                    <li><a class="<?= $activeNav === 'finance' ? 'active' : '' ?>" href="finance.php">Finance</a></li>
                    <li><a class="<?= $activeNav === 'reports' ? 'active' : '' ?>" href="reports.php">Reports</a></li>
                    <li><a class="<?= $activeNav === 'products' ? 'active' : '' ?>" href="products.php">Products</a></li>
                    <li><a class="<?= $activeNav === 'orders' ? 'active' : '' ?>" href="admin_orders.php">Orders</a></li>
                    <li><a class="<?= $activeNav === 'users' ? 'active' : '' ?>" href="users.php">Users</a></li>
                <?php endif; ?>
                <?php if ($userRole === 'customer'): ?>
                    <li><a class="<?= $activeNav === 'order' ? 'active' : '' ?>" href="order.php">Order Products</a></li>
                <?php endif; ?>
                <li><a class="<?= $activeNav === 'password' ? 'active' : '' ?>" href="change_password.php">Change Password</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </aside>

        <main class="content">
            <div class="toprow">
                <div>
                    <h1><?= htmlspecialchars($pageTitle ?? '') ?></h1>
                    <?php if (!empty($pageDescription)): ?>
                        <p><?= htmlspecialchars($pageDescription) ?></p>
                    <?php endif; ?>
                </div>
                <div style="text-align:right; min-width: 180px;">
                    <p style="margin:0; font-weight:600;">Welcome, <?= htmlspecialchars($user['name']) ?>.</p>
                    <p style="margin:4px 0 0; font-size:0.9rem; color:var(--muted);">Role: <?= htmlspecialchars($user['role']) ?></p>
                </div>
            </div>
