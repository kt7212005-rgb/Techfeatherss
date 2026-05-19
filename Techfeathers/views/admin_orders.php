<?php include __DIR__ . '/../includes/header.php'; ?>

<?php
$message = $message ?? '';
$orders = $orders ?? [];
?>

<?php if ($message): ?>
    <div class="message" style="background: #2ecc71; color: #fff;"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div class="card-panel">
    <h2>Order Management</h2>
    <p style="margin-top: 4px; color: var(--muted);">Review and approve customer orders.</p>

    <table class="table">
        <thead>
            <tr>
                <th>Order Date</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['order_date']) ?></td>
                    <td>
                        <?= htmlspecialchars($order['user_name']) ?><br>
                        <small style="color: var(--muted);"><?= htmlspecialchars($order['user_email']) ?></small>
                    </td>
                    <td><?= htmlspecialchars($order['product_name']) ?></td>
                    <td><?= htmlspecialchars($order['quantity']) ?></td>
                    <td>₱<?= number_format($order['price'] * $order['quantity'], 2) ?></td>
                    <td>
                        <span class="badge <?= $order['status'] === 'approved' ? 'success' : ($order['status'] === 'rejected' ? 'danger' : 'warning') ?>">
                            <?= htmlspecialchars(ucfirst($order['status'])) ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($order['status'] === 'pending'): ?>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>" />
                                <input type="hidden" name="action" value="approved" />
                                <button class="button" type="submit" style="background: #2ecc71; color: white; padding: 4px 8px; font-size: 0.8rem;">Approve</button>
                            </form>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>" />
                                <input type="hidden" name="action" value="rejected" />
                                <button class="button" type="submit" style="background: #e74c3c; color: white; padding: 4px 8px; font-size: 0.8rem;">Reject</button>
                            </form>
                        <?php else: ?>
                            <span style="color: var(--muted); font-size: 0.9rem;">Processed</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>