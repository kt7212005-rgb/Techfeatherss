<?php include __DIR__ . '/../includes/header.php'; ?>

<?php
$message = $message ?? '';
$feeds = $feeds ?? [];
?>

<?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div class="card-panel">
    <h2>Add Feed Stock</h2>
    <form method="post" style="display:grid; gap:12px; max-width:480px;">
        <div class="field">
            <label for="name">Feed Name</label>
            <input id="name" name="name" type="text" placeholder="e.g. Layer Mash" required />
        </div>
        <div class="field">
            <label for="quantity_kg">Quantity (kg)</label>
            <input id="quantity_kg" name="quantity_kg" type="number" step="0.1" min="0" value="0" required />
        </div>
        <div class="field">
            <label for="unit_cost">Unit Cost (per kg)</label>
            <input id="unit_cost" name="unit_cost" type="number" step="0.01" min="0" value="0" required />
        </div>
        <button class="button" type="submit">Add Feed</button>
    </form>
</div>

<div class="card-panel">
    <h2>Current Feed Stock</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Quantity (kg)</th>
                <th>Unit Cost</th>
                <th>Last Updated</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($feeds as $feed): ?>
                <tr>
                    <td><?= htmlspecialchars($feed['name']) ?></td>
                    <td><?= number_format($feed['quantity_kg'], 1) ?></td>
                    <td>₱<?= number_format($feed['unit_cost'], 2) ?></td>
                    <td><?= htmlspecialchars($feed['last_updated']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>