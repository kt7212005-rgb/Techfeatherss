<?php include __DIR__ . '/../includes/header.php'; ?>

<?php
$message = $message ?? '';
$batches = $batches ?? [];
$eggRecords = $eggRecords ?? [];
?>

<?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div class="card-panel">
    <h2>Record Egg Collection</h2>
    <form method="post" style="display:grid; gap:12px; max-width:480px;">
        <div class="field">
            <label for="batch_id">Batch</label>
            <select id="batch_id" name="batch_id" required>
                <option value="">Select a batch</option>
                <?php foreach ($batches as $batch): ?>
                    <option value="<?= $batch['id'] ?>"><?= htmlspecialchars($batch['batch_code']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="collected_at">Date</label>
            <input id="collected_at" name="collected_at" type="date" value="<?= date('Y-m-d') ?>" required />
        </div>
        <div class="field">
            <label for="quantity">Quantity</label>
            <input id="quantity" name="quantity" type="number" min="1" value="0" required />
        </div>
        <button class="button" type="submit">Save</button>
    </form>
</div>

<div class="card-panel">
    <h2>Recent Egg Logs</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Batch</th>
                <th>Count</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($eggRecords as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['collected_at']) ?></td>
                    <td><?= htmlspecialchars($row['batch_code']) ?></td>
                    <td><?= htmlspecialchars($row['quantity']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>