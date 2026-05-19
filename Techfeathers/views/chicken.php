<?php include __DIR__ . '/../includes/header.php'; ?>

<?php
$message = $message ?? '';
$search = $search ?? '';
$status = $status ?? '';
$viewMode = $viewMode ?? 'list';
$totalPopulation = $totalPopulation ?? 0;
$avgMortality = $avgMortality ?? 0;
$productionReady = $productionReady ?? 0;
$totalBreeds = $totalBreeds ?? 0;
$batches = $batches ?? [];
?>

<?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap: 14px;">
    <div>
        <h1 style="margin:0;">Chicken Management</h1>
        <p style="margin:4px 0 0; color: var(--muted);">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
    </div>
    <div style="display:flex; gap: 10px; flex-wrap: wrap; align-items:center;">
        <a href="?<?= http_build_query(array_merge($_GET, ['export' => 1])) ?>" class="button" style="background: #fff; color: #111; border: 1px solid rgba(0,0,0,0.12);">Export CSV</a>
        <button id="openBatchModal" class="button" type="button" style="background: #2ecc71;">Add New Batch</button>
    </div>
</div>

<?php
$search = $search ?? '';
$status = $status ?? '';
$viewMode = $viewMode ?? 'list';
?>

<form method="get" style="margin-top:18px; display:flex; flex-wrap:wrap; gap:10px; align-items:center;">
    <input type="text" name="search" placeholder="Search batch code or breed" value="<?= htmlspecialchars($search) ?>" style="padding:10px; border-radius:14px; border:1px solid rgba(0,0,0,0.12); width:220px;" />
    <select name="status" style="padding:10px; border-radius:14px; border:1px solid rgba(0,0,0,0.12);">
        <option value="">All Status</option>
        <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active</option>
        <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactive</option>
    </select>
    <input type="hidden" name="view" value="<?= htmlspecialchars($viewMode) ?>" />
    <button class="button" type="submit" style="background: rgba(0,0,0,0.06); color:#111;">Apply</button>

    <div style="margin-left:auto; display:flex; gap:6px;">
        <a href="?<?= http_build_query(array_merge($_GET, ['view' => 'list', 'export' => null])) ?>" class="button" style="background: <?= $viewMode === 'list' ? '#2ecc71' : 'rgba(0,0,0,0.06)' ?>; color: <?= $viewMode === 'list' ? '#fff' : '#111' ?>;">List</a>
        <a href="?<?= http_build_query(array_merge($_GET, ['view' => 'grid', 'export' => null])) ?>" class="button" style="background: <?= $viewMode === 'grid' ? '#2ecc71' : 'rgba(0,0,0,0.06)' ?>; color: <?= $viewMode === 'grid' ? '#fff' : '#111' ?>;">Grid</a>
    </div>
</form>

<div class="stats" style="margin-top: 18px; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px;">
    <div class="stat">
        <h3>Total Population</h3>
        <p><?= number_format($totalPopulation, 0) ?></p>
    </div>
    <div class="stat">
        <h3>Average Mortality</h3>
        <p><?= $avgMortality ?>%</p>
    </div>
    <div class="stat">
        <h3>Production Ready</h3>
        <p><?= number_format($productionReady, 0) ?> Batch</p>
    </div>
    <div class="stat">
        <h3>Total Breeds</h3>
        <p><?= number_format($totalBreeds, 0) ?> Type</p>
    </div>
</div>


<div class="card-panel" style="margin-top: 16px;">
    <?php if ($viewMode === 'grid'): ?>
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:12px;">
            <?php foreach ($batches as $batch): ?>
                <?php
                    $ageWeeks = floor((time() - strtotime($batch['started_at'])) / (7 * 24 * 60 * 60));
                    $mortality = 1;
                    $statusClass = $batch['status'] === 'active' ? 'success' : 'danger';
                ?>
                <div style="border:1px solid rgba(0,0,0,0.12); border-radius:12px; padding:14px; background:#fff;">
                    <h4 style="margin:0 0 8px;"><?= htmlspecialchars($batch['batch_code']) ?></h4>
                    <p style="margin:2px 0;"><strong>Breed:</strong> <?= htmlspecialchars($batch['breed']) ?></p>
                    <p style="margin:2px 0;"><strong>Age:</strong> <?= max(1, $ageWeeks) ?> week(s)</p>
                    <p style="margin:2px 0;"><strong>Qty:</strong> <?= htmlspecialchars($batch['quantity']) ?></p>
                    <p style="margin:2px 0;"><strong>Status:</strong> <span class="badge <?= $statusClass ?>"><?= htmlspecialchars(ucfirst($batch['status'])) ?></span></p>
                    <p style="margin:2px 0;"><strong>Mortality:</strong> <span class="badge <?= $mortality > 5 ? 'danger' : 'success' ?>"><?= $mortality ?>%</span></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <table class="table" style="width:100%;">
            <thead>
                <tr>
                    <th>Batch ID</th>
                    <th>Breed</th>
                    <th>Age (Weeks)</th>
                    <th>Current</th>
                    <th>Mortality %</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($batches as $batch): ?>
                    <?php
                        $ageWeeks = floor((time() - strtotime($batch['started_at'])) / (7 * 24 * 60 * 60));
                        $mortality = 1;
                        $statusClass = $batch['status'] === 'active' ? 'success' : 'danger';
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($batch['batch_code']) ?></td>
                        <td><?= htmlspecialchars($batch['breed']) ?></td>
                        <td><?= max(1, $ageWeeks) ?> Week</td>
                        <td><?= htmlspecialchars($batch['quantity']) ?> of <?= htmlspecialchars($batch['quantity']) ?></td>
                        <td><span class="badge <?= $mortality > 5 ? 'danger' : 'success' ?>"><?= $mortality ?>%</span></td>
                        <td><span class="badge <?= $statusClass ?>"><?= htmlspecialchars(ucfirst($batch['status'])) ?></span></td>
                        <td style="text-align:right;">⋮</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Batch creation modal -->
<div id="batchModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:99; justify-content:center; align-items:center;">
    <div style="background:#f2f2f2; border-radius:16px; width:min(540px,95%); padding: 24px; box-shadow:0 20px 40px rgba(0,0,0,0.22); position:relative;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2 style="margin:0;">Add New Batch</h2>
            <button id="closeBatchModal" style="background:transparent; border:none; font-size:1.4rem; cursor:pointer;">✕</button>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:10px; margin-top: 14px;">
            <button class="button" type="button" style="background: rgba(0,0,0,0.12); color: #111;">Export Ledger</button>
            <button class="button" type="button" id="saveBatchBtn" style="background: rgba(0,0,0,0.25); color: #111;">Save Changes</button>
        </div>

        <form id="batchForm" method="post" style="margin-top: 18px; display:grid; gap: 12px;">
            <input type="hidden" name="batch_submission" value="1" />
            <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                <div class="field">
                    <label for="batch_code">Batch ID</label>
                    <input id="batch_code" name="batch_code" type="text" placeholder="Batch ID" required />
                </div>
                <div class="field">
                    <label for="breed">Breed</label>
                    <select id="breed" name="breed" required>
                        <option value="">Select</option>
                        <option>Leghorn</option>
                        <option>Rhode Island Red</option>
                        <option>Silkie</option>
                    </select>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                <div class="field">
                    <label for="purpose">Purpose</label>
                    <input id="purpose" name="purpose" type="text" placeholder="Purpose" />
                </div>
                <div class="field">
                    <label for="started_at">Intake Date</label>
                    <input id="started_at" name="started_at" type="date" value="<?= date('Y-m-d') ?>" required />
                </div>
            </div>

            <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                <div class="field">
                    <label for="quantity">Initial Quantity</label>
                    <input id="quantity" name="quantity" type="number" min="1" value="100" required />
                </div>
                <div class="field">
                    <label for="housing_unit">Housing Unit</label>
                    <input id="housing_unit" name="housing_unit" type="text" placeholder="Housing Unit" />
                </div>
            </div>

            <div class="field">
                <label for="avg_weight">Avg. Initial Weight</label>
                <input id="avg_weight" name="avg_weight" type="text" placeholder="e.g. 1.4 kg" />
            </div>

            <div style="display:flex; flex-direction:column; gap:10px; margin-top: 10px;">
                <button class="button" type="submit" style="background:#b0b0b0;">Save Changes</button>
                <button id="cancelBatch" type="button" class="button" style="background:#666;">Cancel</button>
                <button id="openMortalityModal" type="button" class="button" style="background:#d63d3d;">Mortality Recording</button>
            </div>
        </form>
    </div>
</div>

<!-- Mortality recording modal -->
<div id="mortalityModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:99; justify-content:center; align-items:center;">
    <div style="background:#f2f2f2; border-radius:16px; width:min(520px,95%); padding: 24px; box-shadow:0 20px 40px rgba(0,0,0,0.22); position:relative;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2 style="margin:0;">Mortality Recording</h2>
            <button id="closeMortalityModal" style="background:transparent; border:none; font-size:1.4rem; cursor:pointer;">✕</button>
        </div>

        <form id="mortalityForm" method="post" style="margin-top: 18px; display:grid; gap: 12px;">
            <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                <div class="field">
                    <label for="mortality_batch">Batch</label>
                    <select id="mortality_batch" name="mortality_batch" required>
                        <option value="">Select</option>
                        <?php foreach ($batches as $batch): ?>
                            <option value="<?= htmlspecialchars($batch['id']) ?>"><?= htmlspecialchars($batch['batch_code']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label for="mortality_count">Number of Deaths</label>
                    <input id="mortality_count" name="mortality_count" type="number" min="0" value="0" required />
                </div>
            </div>
            <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                <div class="field">
                    <label for="mortality_reason">Observed Reason</label>
                    <select id="mortality_reason" name="mortality_reason" required>
                        <option value="">Select</option>
                        <option>Illness</option>
                        <option>Predation</option>
                        <option>Feed / Water</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="field">
                    <label for="mortality_date">Date</label>
                    <input id="mortality_date" name="mortality_date" type="date" value="<?= date('Y-m-d') ?>" required />
                </div>
            </div>

            <div class="field">
                <label for="mortality_notes">Clinical Observations / Action Taken</label>
                <input id="mortality_notes" name="mortality_notes" type="text" placeholder="Notes" />
            </div>

            <input type="hidden" name="mortality_submission" value="1" />
            <div style="display:flex; flex-direction:column; gap:10px; margin-top: 10px;">
                <button class="button" type="submit" style="background:#b0b0b0;">Save Changes</button>
                <button id="cancelMortality" type="button" class="button" style="background:#666;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div class="card-panel" style="margin-top: 18px;">
    <h2>Mortality Records</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Batch</th>
                <th>Deaths</th>
                <th>Reason</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($mortalityRecords)): ?>
                <tr>
                    <td colspan="5" style="text-align:center; color: var(--muted);">No mortality records yet.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($mortalityRecords as $record): ?>
                    <tr>
                        <td><?= htmlspecialchars($record['recorded_at']) ?></td>
                        <td><?= htmlspecialchars($record['batch_code']) ?></td>
                        <td><?= htmlspecialchars($record['deaths']) ?></td>
                        <td><?= htmlspecialchars($record['reason']) ?></td>
                        <td><?= htmlspecialchars($record['notes']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    const batchModal = document.getElementById('batchModal');
    const openBatchModal = document.getElementById('openBatchModal');
    const closeBatchModal = document.getElementById('closeBatchModal');
    const cancelBatch = document.getElementById('cancelBatch');

    const mortalityModal = document.getElementById('mortalityModal');
    const openMortalityModal = document.getElementById('openMortalityModal');
    const closeMortalityModal = document.getElementById('closeMortalityModal');
    const cancelMortality = document.getElementById('cancelMortality');

    function showModal() {
        batchModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function hideModal() {
        batchModal.style.display = 'none';
        document.body.style.overflow = '';
    }

    function showMortalityModal() {
        mortalityModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function hideMortalityModal() {
        mortalityModal.style.display = 'none';
        document.body.style.overflow = '';
    }

    const saveBatchBtn = document.getElementById('saveBatchBtn');
    const batchForm = document.getElementById('batchForm');

    openBatchModal.addEventListener('click', showModal);
    closeBatchModal.addEventListener('click', hideModal);
    cancelBatch.addEventListener('click', hideModal);

    saveBatchBtn.addEventListener('click', () => {
        batchForm.submit();
    });

    openMortalityModal.addEventListener('click', () => {
        hideModal();
        showMortalityModal();
    });
    closeMortalityModal.addEventListener('click', hideMortalityModal);
    cancelMortality.addEventListener('click', hideMortalityModal);

    // Close modals when clicking outside
    batchModal.addEventListener('click', (event) => {
        if (event.target === batchModal) {
            hideModal();
        }
    });

    mortalityModal.addEventListener('click', (event) => {
        if (event.target === mortalityModal) {
            hideMortalityModal();
        }
    });
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>