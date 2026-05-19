<?php include __DIR__ . '/../includes/header.php'; ?>

<?php
$message = $message ?? '';
$products = $products ?? [];
$orders = $orders ?? [];
?>

<?php if ($message): ?>
    <div class="message" style="background: #2ecc71; color: #fff;"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div class="card-panel">
    <h2>Available Products</h2>
    <p style="margin-top: 4px; color: var(--muted);">Choose from our fresh farm products.</p>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px; margin-top: 20px;">
        <?php foreach ($products as $product): ?>
            <div style="border: 1px solid rgba(0,0,0,0.12); border-radius: 12px; padding: 16px; background: #fff;">
                <h3 style="margin: 0 0 8px;"><?= htmlspecialchars($product['name']) ?></h3>
                <p style="margin: 0 0 8px; color: var(--muted); font-size: 0.9rem;"><?= htmlspecialchars($product['description']) ?></p>
                <p style="margin: 0 0 12px; font-weight: 600; color: #2ecc71;">₱<?= number_format($product['price'], 2) ?></p>
                <p style="margin: 0 0 12px; font-size: 0.9rem;">Available: <?= $product['available_quantity'] ?> units</p>

                <form method="post" style="display: flex; gap: 8px; align-items: center;">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>" />
                    <input type="number" name="quantity" min="1" max="<?= $product['available_quantity'] ?>" value="1" style="width: 80px; padding: 8px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.12);" required />
                    <button class="button" type="submit" style="background: #2ecc71; padding: 8px 16px;">Order</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="card-panel">
    <h2>Your Orders</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Order Date</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['order_date']) ?></td>
                    <td><?= htmlspecialchars($order['product_name']) ?></td>
                    <td><?= htmlspecialchars($order['quantity']) ?></td>
                    <td>₱<?= number_format($order['price'] * $order['quantity'], 2) ?></td>
                    <td><span class="badge <?= $order['status'] === 'approved' ? 'success' : ($order['status'] === 'rejected' ? 'danger' : 'warning') ?>"><?= htmlspecialchars(ucfirst($order['status'])) ?></span></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>