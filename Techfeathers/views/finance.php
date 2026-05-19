<?php include __DIR__ . '/../includes/header.php'; ?>

<?php
$message = $message ?? '';
$transactions = $transactions ?? [];
?>

<?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div class="card-panel">
    <h2>New Transaction</h2>

    <form method="post" style="display:grid; gap:12px; max-width:480px;">
        <div class="field">
            <label for="type">Type</label>
            <select id="type" name="type" required>
                <option value="sale">Sale</option>
                <option value="expense">Expense</option>
            </select>
        </div>

        <div class="field">
            <label for="description">Description</label>
            <input id="description" name="description" type="text" placeholder="e.g. Egg sales" required />
        </div>

        <div class="field">
            <label for="amount">Amount</label>
            <input id="amount" name="amount" type="number" step="0.01" min="0" value="0" required />
        </div>

        <div class="field">
            <label for="incurred_at">Date</label>
            <input id="incurred_at" name="incurred_at" type="date" value="<?= date('Y-m-d') ?>" required />
        </div>

        <button class="button" type="submit">Save Transaction</button>
    </form>
</div>

<div class="card-panel">
    <h2>Recent Transactions</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $tx): ?>
                <tr>
                    <td><?= htmlspecialchars($tx['incurred_at']) ?></td>
                    <td><span class="badge <?= $tx['type'] === 'sale' ? 'success' : 'danger' ?>"><?= htmlspecialchars(ucfirst($tx['type'])) ?></span></td>
                    <td><?= htmlspecialchars($tx['description']) ?></td>
                    <td>₱<?= number_format($tx['amount'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>