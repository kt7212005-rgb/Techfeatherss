<?php include __DIR__ . '/../includes/header.php'; ?>

<?php
$totalChickens = $totalChickens ?? 0;
$dailyEggs = $dailyEggs ?? 0;
$feedInventoryKg = $feedInventoryKg ?? 0;
$monthlyNetProfit = $monthlyNetProfit ?? 0;
$batches = $batches ?? [];
$eggTrend = $eggTrend ?? ['labels' => [], 'data' => []];
?>

<div class="stats">
    <div class="stat">
        <h3>Total Chickens</h3>
        <p><?= number_format($totalChickens) ?></p>
    </div>

    <div class="stat">
        <h3>Daily Egg Count</h3>
        <p><?= number_format($dailyEggs) ?></p>
    </div>

    <div class="stat">
        <h3>Feed Inventory (kg)</h3>
        <p><?= number_format($feedInventoryKg, 1) ?></p>
    </div>

    <div class="stat">
        <h3>Monthly Net Profit</h3>
        <p style="font-size: 1.5rem;">₱<?= number_format($monthlyNetProfit, 2) ?></p>
    </div>
</div>

<div class="card-panel">
    <h2>Egg Production Trend</h2>
    <p style="margin-top: 4px; color: var(--muted);">Daily collection count over the last 7 days.</p>
    <div style="max-width:520px; margin: 0 auto; height: 160px;">
        <canvas id="eggTrendChart" style="width:100%; height:100%;"></canvas>
    </div>
</div>

<div class="card-panel">
    <h2>Active Batch Status</h2>
    <p style="margin-top: 4px; color: var(--muted);">Monitor batch performance and health.</p>

    <table class="table">
        <thead>
            <tr>
                <th>Batch ID</th>
                <th>Breed / Age</th>
                <th>Mortality</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($batches as $batch): ?>
                <?php
                    $ageDays = (new DateTime($batch['started_at']))->diff(new DateTime())->days;
                    $mortality = 0; // Extend with actual tracking in future
                    $statusClass = 'success';
                    $statusLabel = 'Healthy';
                ?>
                <tr>
                    <td><?= htmlspecialchars($batch['batch_code']) ?></td>
                    <td><?= htmlspecialchars($batch['breed']) ?> · <?= $ageDays ?> days</td>
                    <td><span class="badge <?= $mortality > 5 ? 'danger' : 'success' ?>"><?= $mortality ?>%</span></td>
                    <td><span class="badge <?= $statusClass ?>"><?= $statusLabel ?></span></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="text-align: right; margin-top: 12px;">
        <a href="chickens.php" style="font-weight:600;">View Full Inventory Details →</a>
    </div>
</div>

<script>
    const labels = <?= json_encode($eggTrend['labels'], JSON_HEX_TAG) ?>;
    const data = <?= json_encode($eggTrend['data'], JSON_HEX_TAG) ?>;

    new Chart(document.getElementById('eggTrendChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Eggs collected',
                data,
                borderColor: 'rgba(46, 204, 113, 0.9)',
                backgroundColor: 'rgba(46, 204, 113, 0.15)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 3,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>